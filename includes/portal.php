<?php
require_once __DIR__.'/bootstrap.php';
function render_portal(string $role,string $page): void {
    require_login([$role]); $user=current_user(); $pdo=db(); $uid=(int)$user['id']; verify_csrf();
    if ($role === 'admin' && strtolower($page) === 'settings') ensure_app_settings_table($pdo);
    $pageKey=strtolower(str_replace(' ','-', $page));
    $message='';
    try {
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            $action=(string)($_POST['action']??'');
            if ($action==='save_profile') {
                $name=post_string('full_name',100); $phone=post_string('phone',20); $bio=post_string('bio',1000); $address=post_string('address',255); $city=post_string('city',100); $state=post_string('state',100); $pin=post_string('pincode',20);
                if (mb_strlen($name)<2) throw new RuntimeException('Please enter your name.');
                $pdo->beginTransaction(); $pdo->prepare('UPDATE users SET full_name=?,phone=? WHERE id=?')->execute([$name,$phone,$uid]);
                $pdo->prepare('INSERT INTO user_profiles(user_id,address_line1,city,state,pincode,bio) VALUES(?,?,?,?,?,?) ON DUPLICATE KEY UPDATE address_line1=VALUES(address_line1),city=VALUES(city),state=VALUES(state),pincode=VALUES(pincode),bio=VALUES(bio)')->execute([$uid,$address,$city,$state,$pin,$bio]);
                $pdo->commit(); $_SESSION['user']['full_name']=$name; flash('Your profile has been updated.'); redirect(role_home($role));
            }
            if ($role==='donor' && $action==='create_donation') {
                $title=post_string('food_title',150); $category=post_string('category',60); $qty=(int)($_POST['quantity']??0); $unit=post_string('quantity_unit',30) ?: 'servings'; $desc=post_string('description',4000); $prepared=post_string('prepared_at',30); $expiry=post_string('expiry_at',30); $address=post_string('pickup_address',500); $city=post_string('city',100); $contact=post_string('contact_name',100); $phone=post_string('contact_phone',20);
                if ($title===''||$category===''||$qty<1||$desc===''||$prepared===''||$expiry===''||$address===''||$city===''||$contact===''||$phone==='') throw new RuntimeException('Complete all required donation fields.');
                if (strtotime($expiry)<=time() || strtotime($prepared)>time() || strtotime($expiry)<=strtotime($prepared)) throw new RuntimeException('Check the preparation and expiry dates. Food must not be expired.');
                $img=upload_image('food_image','donations');
                $pdo->beginTransaction(); $st=$pdo->prepare("INSERT INTO donations(donor_id,food_title,category,quantity,quantity_unit,available_quantity,description,prepared_at,expiry_at,pickup_address,city,contact_name,contact_phone,image_path,status) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?, 'pending')");
                $st->execute([$uid,$title,$category,$qty,$unit,$qty,$desc,$prepared,$expiry,$address,$city,$contact,$phone,$img]); $id=(int)$pdo->lastInsertId();
                $pdo->commit(); $admins=$pdo->query("SELECT id FROM users WHERE role='admin' AND status='active'")->fetchAll(); foreach($admins as $a) notify_user((int)$a['id'],'New food donation',"A new donation '{$title}' is waiting for review.",'admin/donations.php');
                log_activity($uid,'donation_created','Created donation #'.$id); flash('Your food donation was submitted for admin review.'); redirect('donor/my-donations.php');
            }
            if ($role==='requester' && $action==='create_request') {
                $did=(int)($_POST['donation_id']??0); $qty=(int)($_POST['requested_quantity']??0); $purpose=post_string('purpose',1500);
                if ($did<1||$qty<1||$purpose==='') throw new RuntimeException('Enter a valid quantity and purpose.');
                $pdo->beginTransaction(); $st=$pdo->prepare('SELECT * FROM donations WHERE id=? FOR UPDATE'); $st->execute([$did]); $d=$st->fetch();
                if (!$d || !in_array($d['status'],['approved','partially_claimed'],true) || strtotime($d['expiry_at'])<=time() || (int)$d['available_quantity']<$qty) throw new RuntimeException('This food is no longer available in the requested quantity.');
                $dupe=$pdo->prepare("SELECT COUNT(*) FROM food_requests WHERE donation_id=? AND requester_id=? AND status IN ('pending','approved','assigned')"); $dupe->execute([$did,$uid]); if ((int)$dupe->fetchColumn()>0) throw new RuntimeException('You already have an active request for this donation.');
                $pdo->prepare("INSERT INTO food_requests(donation_id,requester_id,requested_quantity,purpose,status) VALUES(?,?,?,?, 'pending')")->execute([$did,$uid,$qty,$purpose]); $rid=(int)$pdo->lastInsertId();
                $admins=$pdo->query("SELECT id FROM users WHERE role='admin' AND status='active'")->fetchAll(); foreach($admins as $a) notify_user((int)$a['id'],'New food request','A requester submitted a request for '.$d['food_title'].'.','admin/food-requests.php');
                notify_user((int)$d['donor_id'],'Food requested','Someone requested food from your donation #'.$did.'.','donor/donation-details.php?id='.$did);
                $pdo->commit(); log_activity($uid,'food_request_created','Created food request #'.$rid); flash('Your request has been submitted and is awaiting review.'); redirect('requester/my-requests.php');
            }
            if ($role==='volunteer' && $action==='apply_volunteer') {
                $area=post_string('preferred_area',150); $availability=post_string('availability',150); $vehicle=post_string('vehicle_type',40); $experience=post_string('experience',2000);
                if ($area===''||$availability==='') throw new RuntimeException('Please provide your service area and availability.');
                $st=$pdo->prepare('SELECT id,status FROM volunteer_applications WHERE user_id=?'); $st->execute([$uid]); $old=$st->fetch();
                if ($old) { $pdo->prepare("UPDATE volunteer_applications SET preferred_area=?,availability=?,vehicle_type=?,experience=?,status='pending' WHERE user_id=?")->execute([$area,$availability,$vehicle,$experience,$uid]); }
                else { $pdo->prepare("INSERT INTO volunteer_applications(user_id,name,email,phone,preferred_area,availability,vehicle_type,experience,status) VALUES(?,?,?,?,?,?,?,?,'pending')")->execute([$uid,$user['full_name'],$user['email'],$user['phone'],$area,$availability,$vehicle,$experience]); }
                $admins=$pdo->query("SELECT id FROM users WHERE role='admin' AND status='active'")->fetchAll(); foreach($admins as $a) notify_user((int)$a['id'],'Volunteer application','A volunteer application is ready for review.','admin/volunteers.php'); flash('Your volunteer application has been submitted for review.'); redirect('volunteer/application.php');
            }
            if ($role==='volunteer' && $action==='accept_delivery') {
                $id=(int)($_POST['delivery_id']??0);
                // Admin-assigned tasks already belong to this volunteer and have status=assigned.
                $st=$pdo->prepare("UPDATE deliveries SET status='accepted',updated_at=NOW() WHERE id=? AND volunteer_id=? AND status='assigned' AND EXISTS(SELECT 1 FROM volunteer_applications va WHERE va.user_id=? AND va.status='approved')");
                $st->execute([$id,$uid,$uid]);
                // Approved volunteers may also claim a task that is still unassigned.
                if (!$st->rowCount()) {
                    $st=$pdo->prepare("UPDATE deliveries SET volunteer_id=?,status='accepted',updated_at=NOW() WHERE id=? AND status='unassigned' AND volunteer_id IS NULL AND EXISTS(SELECT 1 FROM volunteer_applications va WHERE va.user_id=? AND va.status='approved')");
                    $st->execute([$uid,$id,$uid]);
                }
                if (!$st->rowCount()) throw new RuntimeException('This delivery is not available or your volunteer application is not approved.');
                notify_related_delivery($pdo,$id,'Volunteer accepted delivery','The volunteer accepted the delivery.');
                flash('Delivery accepted.'); redirect('volunteer/my-deliveries.php');
            }
            if ($role==='volunteer' && $action==='update_delivery') {
                $id=(int)($_POST['delivery_id']??0); $status=post_string('status',30); $allowed=['picked_up','in_transit','delivered']; if(!in_array($status,$allowed,true)) throw new RuntimeException('Invalid delivery status.');
                $pdo->beginTransaction(); $st=$pdo->prepare('SELECT request_id,status FROM deliveries WHERE id=? AND volunteer_id=? FOR UPDATE'); $st->execute([$id,$uid]); $d=$st->fetch(); if(!$d) throw new RuntimeException('Delivery not found.');
                $valid=['accepted'=>['picked_up'],'picked_up'=>['in_transit'],'in_transit'=>['delivered']]; if(!in_array($status,$valid[$d['status']]??[],true)) throw new RuntimeException('That status change is not allowed.');
                $pdo->prepare('UPDATE deliveries SET status=?,pickup_time=IF(?="picked_up",NOW(),pickup_time),delivery_time=IF(?="delivered",NOW(),delivery_time) WHERE id=?')->execute([$status,$status,$status,$id]);
                if($status==='delivered') $pdo->prepare("UPDATE food_requests SET status='completed' WHERE id=?")->execute([$d['request_id']]);
                $pdo->commit(); notify_related_delivery($pdo,$id,'Delivery updated','Delivery status changed to '.str_replace('_',' ',$status).'.'); flash('Delivery status updated.'); redirect('volunteer/delivery-details.php?id='.$id);
            }
            if ($role==='admin') {
                if ($action==='save_platform_settings') {
                    $supportEmail = filter_var(trim((string)($_POST['support_email'] ?? '')), FILTER_VALIDATE_EMAIL);
                    $supportPhone = post_string('support_phone', 30);
                    $contactAddress = post_string('contact_address', 255);
                    $defaultCity = post_string('default_city', 100);
                    $registrationEnabled = isset($_POST['registration_enabled']) ? '1' : '0';
                    if (!$supportEmail) throw new RuntimeException('Enter a valid support email address.');
                    if ($defaultCity === '') throw new RuntimeException('Enter the default city.');
                    save_app_settings($pdo, [
                        'support_email' => $supportEmail,
                        'support_phone' => $supportPhone,
                        'contact_address' => $contactAddress,
                        'default_city' => $defaultCity,
                        'registration_enabled' => $registrationEnabled
                    ]);
                    flash('Platform settings have been saved.');
                    redirect('admin/settings.php');
                }
                if ($action==='donation_status') {
                    $id=(int)($_POST['id']??0); $status=post_string('status',20); if(!in_array($status,['approved','rejected'],true)) throw new RuntimeException('Invalid donation decision.');
                    $st=$pdo->prepare('SELECT donor_id,food_title,status,expiry_at FROM donations WHERE id=?'); $st->execute([$id]); $d=$st->fetch(); if(!$d) throw new RuntimeException('Donation not found.');
                    if($d['status']!=='pending') throw new RuntimeException('Only pending donations can be reviewed.');
                    if($status==='approved' && strtotime($d['expiry_at'])<=time()) throw new RuntimeException('This donation has expired and cannot be approved.');
                    $pdo->prepare('UPDATE donations SET status=?,admin_notes=? WHERE id=?')->execute([$status,post_string('admin_notes',1000),$id]); notify_user((int)$d['donor_id'],'Donation '.ucfirst($status),'Your donation "'.$d['food_title'].'" was '.$status.'.','donor/donation-details.php?id='.$id); log_activity($uid,'donation_'.$status,'Reviewed donation #'.$id); flash('Donation '.$status.'.'); redirect('admin/donations.php');
                }
                if ($action==='request_status') {
                    $id=(int)($_POST['id']??0); $status=post_string('status',20); if(!in_array($status,['approved','rejected'],true)) throw new RuntimeException('Invalid request decision.');
                    $pdo->beginTransaction(); $st=$pdo->prepare('SELECT fr.*,d.available_quantity,d.status AS donation_status,d.donor_id,d.food_title,d.expiry_at FROM food_requests fr JOIN donations d ON d.id=fr.donation_id WHERE fr.id=? FOR UPDATE'); $st->execute([$id]); $r=$st->fetch(); if(!$r||$r['status']!=='pending') throw new RuntimeException('Only pending requests can be reviewed.');
                    if($status==='approved') { if(!in_array($r['donation_status'],['approved','partially_claimed'],true)||strtotime($r['expiry_at'])<=time()||(int)$r['available_quantity']<(int)$r['requested_quantity']) throw new RuntimeException('There is not enough available, unexpired food for this request.');
                        $pdo->prepare('UPDATE donations SET available_quantity=available_quantity-? WHERE id=?')->execute([$r['requested_quantity'],$r['donation_id']]);
                        $pdo->prepare("UPDATE donations SET status=IF(available_quantity=0,'fully_claimed','partially_claimed') WHERE id=?")->execute([$r['donation_id']]);
                        $pdo->prepare("INSERT INTO deliveries(request_id,status) VALUES(?,'unassigned')")->execute([$id]);
                    }
                    $pdo->prepare('UPDATE food_requests SET status=?,admin_notes=? WHERE id=?')->execute([$status,post_string('admin_notes',1000),$id]); $pdo->commit(); notify_user((int)$r['requester_id'],'Food request '.ucfirst($status),'Your request for "'.$r['food_title'].'" was '.$status.'.','requester/my-requests.php'); notify_user((int)$r['donor_id'],'Donation request update','A food request for "'.$r['food_title'].'" was '.$status.'.','donor/my-donations.php'); log_activity($uid,'request_'.$status,'Reviewed request #'.$id); flash('Food request '.$status.'.'.($status==='approved'?' A delivery task was created.':'')); redirect('admin/food-requests.php');
                }
                if ($action==='assign_delivery') {
                    $id=(int)($_POST['id']??0); $vol=(int)($_POST['volunteer_id']??0); $st=$pdo->prepare("SELECT id FROM volunteer_applications WHERE user_id=? AND status='approved'"); $st->execute([$vol]); if(!$st->fetch()) throw new RuntimeException('Select an approved volunteer.');
                    $st=$pdo->prepare("UPDATE deliveries SET volunteer_id=?,status='assigned' WHERE id=? AND status='unassigned'"); $st->execute([$vol,$id]); if(!$st->rowCount()) throw new RuntimeException('This delivery has already been assigned or is unavailable.'); notify_user($vol,'Delivery assigned','A new food delivery has been assigned to you.','volunteer/my-deliveries.php'); flash('Volunteer assigned to delivery.'); redirect('admin/deliveries.php');
                }
                if ($action==='volunteer_status') {
                    $id=(int)($_POST['id']??0); $status=post_string('status',20); if(!in_array($status,['approved','rejected'],true)) throw new RuntimeException('Invalid application decision.');
                    $st=$pdo->prepare('SELECT user_id FROM volunteer_applications WHERE id=?'); $st->execute([$id]); $v=$st->fetch(); if(!$v) throw new RuntimeException('Application not found.'); $pdo->prepare('UPDATE volunteer_applications SET status=?,reviewed_at=NOW() WHERE id=?')->execute([$status,$id]); if(!empty($v['user_id'])) notify_user((int)$v['user_id'],'Volunteer application '.ucfirst($status),'Your volunteer application was '.$status.'.','volunteer/application.php'); flash('Volunteer application '.$status.'.'); redirect('admin/volunteers.php');
                }
                if ($action==='feedback_status') { $id=(int)($_POST['id']??0); $st=post_string('status',20); if(!in_array($st,['read','archived'],true)) throw new RuntimeException('Invalid feedback status.'); $pdo->prepare('UPDATE feedback SET status=? WHERE id=?')->execute([$st,$id]); flash('Feedback updated.'); redirect('admin/feedback.php'); }
                if ($action==='contact_status') { $id=(int)($_POST['id']??0); $st=post_string('status',20); if(!in_array($st,['read','archived'],true)) throw new RuntimeException('Invalid contact status.'); $pdo->prepare('UPDATE contact_messages SET status=? WHERE id=?')->execute([$st,$id]); flash('Contact message updated.'); redirect('admin/feedback.php'); }
                if ($action==='user_status') { $id=(int)($_POST['id']??0); $st=post_string('status',20); if(!in_array($st,['active','suspended'],true)) throw new RuntimeException('Invalid user status.'); if($id===$uid) throw new RuntimeException('You cannot suspend your own account.'); $pdo->prepare('UPDATE users SET status=? WHERE id=? AND role<>"admin"')->execute([$st,$id]); flash('User status updated.'); redirect('admin/users.php'); }
            }
            if ($action==='mark_notification') { $id=(int)($_POST['id']??0); $pdo->prepare('UPDATE notifications SET is_read=1 WHERE id=? AND user_id=?')->execute([$id,$uid]); flash('Notification marked as read.'); redirect($role.'/notifications.php'); }
            throw new RuntimeException('That action is not supported.');
        }
    } catch (Throwable $ex) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        flash($ex instanceof PDOException ? 'The request could not be saved. Check the entered information and try again.' : $ex->getMessage(),'error');
        redirect(($role==='admin'?'admin':$role).'/'.$pageKey.'.php');
    }

    $nav=[
        'donor'=>['Dashboard'=>'dashboard.php','Donate Food'=>'donate-food.php','My Donations'=>'my-donations.php','Profile'=>'profile.php','Notifications'=>'notifications.php'],
        'requester'=>['Dashboard'=>'dashboard.php','Available Food'=>'available-food.php','My Requests'=>'my-requests.php','Profile'=>'profile.php','Notifications'=>'notifications.php'],
        'volunteer'=>['Dashboard'=>'dashboard.php','Application'=>'application.php','Available Deliveries'=>'available-deliveries.php','My Deliveries'=>'my-deliveries.php','Profile'=>'profile.php','Notifications'=>'notifications.php'],
        'admin'=>['Dashboard'=>'dashboard.php','Users'=>'users.php','Donations'=>'donations.php','Food Requests'=>'food-requests.php','Volunteers'=>'volunteers.php','Deliveries'=>'deliveries.php','Feedback'=>'feedback.php','Reports'=>'reports.php','Settings'=>'settings.php','Profile'=>'profile.php']
    ];
    $links=$nav[$role]; $active=basename($_SERVER['PHP_SELF']); $flash=get_flash();
    $title=$page; $subtitles=['donor'=>'Manage your food donations and keep track of their progress.','requester'=>'Find available food and follow your requests.','volunteer'=>'Coordinate food pickups and deliveries in your service area.','admin'=>'Monitor the HelpingPlate platform and manage its activity.'];
    $profile=db()->prepare('SELECT p.*,u.full_name,u.email,u.phone FROM users u LEFT JOIN user_profiles p ON p.user_id=u.id WHERE u.id=?'); $profile->execute([$uid]); $me=$profile->fetch();
    $q=trim((string)($_GET['q']??''));
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>'.e($title).' | HelpingPlate</title><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"><link rel="stylesheet" href="'.e(url('css/style.css')).'"><link rel="stylesheet" href="'.e(url('css/portal.css')).'"><link rel="stylesheet" href="'.e(url('css/branding.css')).'"></head><body class="portal-body">';
    echo '<header class="portal-topbar"><a class="portal-brand" href="'.e(url('index.php')).'"><img src="'.e(url('images/logo.png')).'" alt="HelpingPlate"><span><b>Helping<span>Plate</span></b><small>Good Food • Greater Impact</small></span></a><div class="portal-top-actions"><span class="portal-welcome">Hello, '.e($user['full_name']).'</span><a class="notification-link" href="'.e(url($role.'/notifications.php')).'"><i class="fa-regular fa-bell"></i><em>'.unread_count($uid).'</em></a><a class="portal-logout" href="'.e(url('actions/logout.php')).'"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a></div></header>';
    echo '<div class="portal-layout"><aside class="portal-sidebar"><div class="portal-role-label">'.e(ucfirst($role)).' Portal</div><nav>';
    foreach($links as $label=>$path) echo '<a class="'.($active===$path?'active':'').'" href="'.e(url($role.'/'.$path)).'"><i class="fa-solid '.portal_icon($label).'"></i><span>'.e($label).'</span></a>';
    echo '</nav><a class="portal-site-link" href="'.e(url('index.php')).'"><i class="fa-solid fa-arrow-up-right-from-square"></i> View Website</a></aside><main class="portal-main"><div class="portal-heading"><div><span class="portal-eyebrow">HELPINGPLATE / '.e(strtoupper($role)).'</span><h1>'.e($title).'</h1><p>'.e($subtitles[$role]).'</p></div></div>';
    if($flash) echo '<div class="hp-flash '.e($flash['type']).'">'.e($flash['message']).'</div>';
    portal_content($role,$page,$pdo,$uid,$user,$me,$q);
    echo '</main></div><script src="'.e(url('js/portal.js')).'"></script></body></html>';
}
function portal_icon(string $label): string { return match($label){'Dashboard'=>'fa-chart-pie','Donate Food'=>'fa-hand-holding-heart','My Donations'=>'fa-box-open','Profile'=>'fa-user','Notifications'=>'fa-bell','Available Food'=>'fa-bowl-food','My Requests'=>'fa-clipboard-list','Application'=>'fa-file-signature','Available Deliveries'=>'fa-truck-fast','My Deliveries'=>'fa-route','Users'=>'fa-users','Donations'=>'fa-boxes-stacked','Food Requests'=>'fa-clipboard-check','Volunteers'=>'fa-people-group','Deliveries'=>'fa-truck','Feedback'=>'fa-comments','Reports'=>'fa-chart-column','Settings'=>'fa-sliders',default=>'fa-circle'}; }
function notify_related_delivery(PDO $pdo,int $deliveryId,string $title,string $message): void { $st=$pdo->prepare('SELECT fr.requester_id,d.donor_id,dl.volunteer_id FROM deliveries dl JOIN food_requests fr ON fr.id=dl.request_id JOIN donations d ON d.id=fr.donation_id WHERE dl.id=?'); $st->execute([$deliveryId]); $r=$st->fetch(); if($r){ foreach(array_unique(array_filter([(int)$r['requester_id'],(int)$r['donor_id'],(int)$r['volunteer_id']])) as $id) notify_user($id,$title,$message,null); } }
function portal_content(string $role,string $page,PDO $pdo,int $uid,array $user,array $me,string $q): void {
    $p=strtolower(str_replace(' ','-', $page));
    $csrf=csrf_field();
    $metric=function($label,$value,$icon,$tone='green'){ echo '<div class="metric-card"><span class="metric-icon '.e($tone).'"><i class="fa-solid '.e($icon).'"></i></span><div><small>'.e($label).'</small><strong>'.e((string)$value).'</strong></div></div>'; };
    $table=function(array $headers,array $rows){ echo '<div class="table-wrap"><table class="data-table"><thead><tr>'; foreach($headers as $h) echo '<th>'.e($h).'</th>'; echo '</tr></thead><tbody>'; if(!$rows) echo '<tr><td colspan="'.count($headers).'" class="empty-cell">No records to display yet.</td></tr>'; else foreach($rows as $row){echo '<tr>'; foreach($row as $v) echo '<td>'.$v.'</td>'; echo '</tr>';} echo '</tbody></table></div>'; };
    if($p==='dashboard') {
        if($role==='donor') { $s=$pdo->prepare("SELECT COUNT(*) total, SUM(status='pending') pending, SUM(status IN ('approved','partially_claimed')) active, SUM(status='fully_claimed') completed FROM donations WHERE donor_id=?");$s->execute([$uid]);$r=$s->fetch(); echo '<div class="metric-grid">';$metric('Total Donations',$r['total']??0,'fa-box-open');$metric('Pending Review',$r['pending']??0,'fa-clock','orange');$metric('Active Donations',$r['active']??0,'fa-seedling');$metric('Fully Claimed',$r['completed']??0,'fa-circle-check','purple');echo '</div>'; $st=$pdo->prepare('SELECT id,food_title,quantity,quantity_unit,status,created_at FROM donations WHERE donor_id=? ORDER BY created_at DESC LIMIT 6');$st->execute([$uid]); $rows=[];foreach($st as $x)$rows[]=['#'.(int)$x['id'],e($x['food_title']),e($x['quantity'].' '.$x['quantity_unit']),status_badge($x['status']),e(date('d M Y',strtotime($x['created_at']))),'<a class="table-action" href="'.e(url('donor/donation-details.php?id='.$x['id'])).'">View</a>']; echo '<section class="portal-card"><div class="card-heading"><h2>Recent donations</h2><a href="'.e(url('donor/my-donations.php')).'">View all</a></div>'; $table(['ID','Food','Quantity','Status','Submitted',''], $rows); echo '</section>'; }
        elseif($role==='requester') { $s=$pdo->prepare("SELECT COUNT(*) total,SUM(status='pending') pending,SUM(status='approved') approved,SUM(status='completed') completed FROM food_requests WHERE requester_id=?");$s->execute([$uid]);$r=$s->fetch();echo '<div class="metric-grid">';$metric('Total Requests',$r['total']??0,'fa-clipboard-list');$metric('Pending',$r['pending']??0,'fa-clock','orange');$metric('Approved',$r['approved']??0,'fa-circle-check');$metric('Completed',$r['completed']??0,'fa-heart','purple');echo '</div>'; echo '<section class="portal-card"><div class="card-heading"><h2>Discover available food</h2><a href="'.e(url('requester/available-food.php')).'">Browse food</a></div><p class="muted-copy">Browse admin-approved donations with available quantity and submit a request for what you need.</p></section>'; $st=$pdo->prepare('SELECT fr.id, d.food_title, fr.requested_quantity, fr.status, fr.created_at FROM food_requests fr JOIN donations d ON d.id=fr.donation_id WHERE fr.requester_id=? ORDER BY fr.created_at DESC LIMIT 5');$st->execute([$uid]);$rows=[];foreach($st as $x)$rows[]=['#'.$x['id'],e($x['food_title']),e((string)$x['requested_quantity']),status_badge($x['status']),e(date('d M Y',strtotime($x['created_at'])))];echo '<section class="portal-card"><div class="card-heading"><h2>Recent requests</h2><a href="'.e(url('requester/my-requests.php')).'">View all</a></div>'; $table(['Request','Food','Quantity','Status','Date'],$rows);echo '</section>'; }
        elseif($role==='volunteer') { $s=$pdo->prepare("SELECT status FROM volunteer_applications WHERE user_id=? ORDER BY created_at DESC LIMIT 1");$s->execute([$uid]);$app=$s->fetch();$s=$pdo->prepare("SELECT COUNT(*) total,SUM(status='delivered') completed,SUM(status IN ('assigned','accepted','picked_up','in_transit')) active FROM deliveries WHERE volunteer_id=?");$s->execute([$uid]);$r=$s->fetch();echo '<div class="notice-card"><i class="fa-solid fa-id-card"></i><div><strong>Volunteer application: '.e(ucfirst($app['status']??'not submitted')).'</strong><p>'.(($app['status']??'')==='approved'?'You can accept available delivery tasks.':'Submit your application and wait for admin approval before accepting deliveries.').'</p></div><a href="'.e(url('volunteer/application.php')).'">View application</a></div><div class="metric-grid">';$metric('Assigned Deliveries',$r['total']??0,'fa-truck');$metric('In Progress',$r['active']??0,'fa-route','orange');$metric('Delivered',$r['completed']??0,'fa-circle-check');echo '</div>';echo '<section class="portal-card"><div class="card-heading"><h2>Next steps</h2><a href="'.e(url('volunteer/available-deliveries.php')).'">Available tasks</a></div><p class="muted-copy">Once your volunteer application is approved, you can accept unassigned deliveries and update each delivery stage.</p></section>'; }
        else { $stats=[];foreach([['Users','SELECT COUNT(*) FROM users'],['Donations','SELECT COUNT(*) FROM donations'],['Pending Donations',"SELECT COUNT(*) FROM donations WHERE status='pending'"],['Pending Requests',"SELECT COUNT(*) FROM food_requests WHERE status='pending'"],['Completed Deliveries',"SELECT COUNT(*) FROM deliveries WHERE status='delivered'"]] as [$label,$sql])$stats[$label]=(int)$pdo->query($sql)->fetchColumn();echo '<div class="metric-grid">';$metric('Registered Users',$stats['Users'],'fa-users');$metric('Food Donations',$stats['Donations'],'fa-box-open');$metric('Pending Donations',$stats['Pending Donations'],'fa-clock','orange');$metric('Pending Requests',$stats['Pending Requests'],'fa-clipboard-list','purple');$metric('Completed Deliveries',$stats['Completed Deliveries'],'fa-circle-check');echo '</div><section class="portal-card"><div class="card-heading"><h2>Administration overview</h2><a href="'.e(url('admin/reports.php')).'">View reports</a></div><p class="muted-copy">Review new donations and food requests, approve volunteer applications and assign volunteers to approved requests.</p></section>'; }
        return;
    }
    if($p==='donate-food' && $role==='donor') { echo '<section class="portal-card form-card"><h2>Share surplus food</h2><p class="muted-copy">Add accurate food, expiry and pickup information. New donations are reviewed by an administrator before becoming visible.</p><form method="post" enctype="multipart/form-data" class="portal-form">'.$csrf.'<input type="hidden" name="action" value="create_donation"><div class="form-grid"><label>Food name *<input name="food_title" maxlength="150" required placeholder="e.g. Vegetable pulao"></label><label>Category *<select name="category" required><option value="">Choose category</option><option>Cooked Food</option><option>Raw Grocery</option><option>Packaged Snacks</option><option>Bakery</option><option>Beverages</option><option>Other</option></select></label><label>Quantity *<input type="number" name="quantity" min="1" required></label><label>Quantity unit *<select name="quantity_unit"><option>servings</option><option>packets</option><option>kg</option><option>boxes</option><option>litres</option></select></label><label>Prepared date & time *<input type="datetime-local" name="prepared_at" required></label><label>Expiry date & time *<input type="datetime-local" name="expiry_at" required></label><label class="span-two">Food description *<textarea name="description" rows="3" required></textarea></label><label class="span-two">Pickup address *<textarea name="pickup_address" rows="2" required></textarea></label><label>City *<input name="city" required value="'.e(app_setting('default_city', 'Ahmedabad')).'"></label><label>Contact person *<input name="contact_name" required></label><label>Contact phone *<input name="contact_phone" required maxlength="20"></label><label>Food photo (JPG/PNG/WEBP, max 5 MB)<input type="file" name="food_image" accept="image/jpeg,image/png,image/webp"></label></div><button class="primary-btn" type="submit">Submit Donation <i class="fa-solid fa-arrow-right"></i></button></form></section>'; return; }
    if($p==='my-donations' && $role==='donor') { $st=$pdo->prepare('SELECT * FROM donations WHERE donor_id=? ORDER BY created_at DESC');$st->execute([$uid]);$rows=[];foreach($st as $x)$rows[]=['#'.$x['id'],e($x['food_title']),e($x['quantity'].' '.$x['quantity_unit']),e($x['available_quantity']),status_badge($x['status']),e(date('d M Y',strtotime($x['created_at']))),'<a class="table-action" href="'.e(url('donor/donation-details.php?id='.$x['id'])).'">Details</a>'];echo '<section class="portal-card"><div class="card-heading"><h2>Your donations</h2><a class="small-primary" href="'.e(url('donor/donate-food.php')).'">+ Add donation</a></div>'; $table(['ID','Food','Quantity','Available','Status','Submitted',''],$rows);echo '</section>';return; }
    if($p==='donation-details' && $role==='donor') { $id=(int)($_GET['id']??0);$st=$pdo->prepare('SELECT * FROM donations WHERE id=? AND donor_id=?');$st->execute([$id,$uid]);$d=$st->fetch();if(!$d){echo '<div class="portal-card">Donation not found.</div>';return;}echo '<section class="portal-card"><div class="card-heading"><h2>'.e($d['food_title']).'</h2>'.status_badge($d['status']).'</div>';if($d['image_path'])echo '<img class="detail-photo" src="'.e(url($d['image_path'])).'" alt="Food">';echo '<div class="detail-grid">';foreach(['Category'=>'category','Quantity'=>'quantity','Available quantity'=>'available_quantity','Description'=>'description','Prepared at'=>'prepared_at','Expiry'=>'expiry_at','Pickup address'=>'pickup_address','City'=>'city','Contact person'=>'contact_name','Contact phone'=>'contact_phone','Admin notes'=>'admin_notes'] as $label=>$key)echo '<div><small>'.e($label).'</small><p>'.e((string)$d[$key]).'</p></div>';echo '</div></section>';return; }
    if($p==='available-food' && $role==='requester') { $sql="SELECT d.*,u.full_name donor_name FROM donations d JOIN users u ON u.id=d.donor_id WHERE d.status IN ('approved','partially_claimed') AND d.available_quantity>0 AND d.expiry_at>NOW()"; $params=[]; if($q!==''){$sql.=' AND (d.food_title LIKE ? OR d.category LIKE ? OR d.city LIKE ?)';$params=["%$q%","%$q%","%$q%"];}$sql.=' ORDER BY d.expiry_at ASC';$st=$pdo->prepare($sql);$st->execute($params);echo '<section class="portal-card"><form class="search-form" method="get"><input name="q" value="'.e($q).'" placeholder="Search food, category or city"><button class="primary-btn">Search</button></form><div class="food-grid">';$any=false;foreach($st as $d){$any=true;echo '<article class="food-card">'.($d['image_path']?'<img src="'.e(url($d['image_path'])).'" alt="Food">':'<div class="food-placeholder"><i class="fa-solid fa-bowl-food"></i></div>').'<div class="food-card-body"><span class="food-category">'.e($d['category']).'</span><h3>'.e($d['food_title']).'</h3><p>'.e(mb_strimwidth($d['description'],0,120,'…')).'</p><div class="food-meta"><span><i class="fa-solid fa-box"></i> '.e($d['available_quantity'].' '.$d['quantity_unit']).'</span><span><i class="fa-solid fa-location-dot"></i> '.e($d['city']).'</span></div><small>Expires: '.e(date('d M, g:i a',strtotime($d['expiry_at']))).'</small><a class="primary-btn" href="'.e(url('requester/food-details.php?id='.$d['id'])).'">View details</a></div></article>'; }if(!$any)echo '<p class="empty-state">No available food matched your search.</p>';echo '</div></section>';return; }
    if($p==='food-details' && $role==='requester') { $id=(int)($_GET['id']??0);$st=$pdo->prepare("SELECT d.*,u.full_name donor_name FROM donations d JOIN users u ON u.id=d.donor_id WHERE d.id=? AND d.status IN ('approved','partially_claimed') AND d.available_quantity>0 AND d.expiry_at>NOW()");$st->execute([$id]);$d=$st->fetch();if(!$d){echo '<div class="portal-card">This food listing is no longer available.</div>';return;}echo '<section class="portal-card">'.($d['image_path']?'<img class="detail-photo" src="'.e(url($d['image_path'])).'" alt="Food">':'').'<h2>'.e($d['food_title']).'</h2><p>'.e($d['description']).'</p><div class="detail-grid"><div><small>Available quantity</small><p>'.e($d['available_quantity'].' '.$d['quantity_unit']).'</p></div><div><small>Category</small><p>'.e($d['category']).'</p></div><div><small>Pickup city</small><p>'.e($d['city']).'</p></div><div><small>Expiry</small><p>'.e(date('d M Y, g:i a',strtotime($d['expiry_at']))).'</p></div></div><a class="primary-btn" href="'.e(url('requester/request-food.php?id='.$id)).'">Request this food</a></section>';return; }
    if($p==='request-food' && $role==='requester') { $id=(int)($_GET['id']??0);$st=$pdo->prepare("SELECT id,food_title,available_quantity,quantity_unit FROM donations WHERE id=? AND status IN ('approved','partially_claimed') AND available_quantity>0 AND expiry_at>NOW()");$st->execute([$id]);$d=$st->fetch();if(!$d){echo '<div class="portal-card">Food item not available.</div>';return;}echo '<section class="portal-card form-card"><h2>Request '.e($d['food_title']).'</h2><p class="muted-copy">Available: '.e($d['available_quantity'].' '.$d['quantity_unit']).'. Your request will be reviewed before stock is reserved.</p><form method="post" class="portal-form">'.$csrf.'<input type="hidden" name="action" value="create_request"><input type="hidden" name="donation_id" value="'.$id.'"><label>Quantity requested *<input type="number" name="requested_quantity" min="1" max="'.(int)$d['available_quantity'].'" required></label><label>Purpose / people to be served *<textarea name="purpose" rows="4" required></textarea></label><button class="primary-btn">Submit food request</button></form></section>';return; }
    if ($p === 'my-requests' && $role === 'requester') {
        $st = $pdo->prepare("SELECT fr.*, d.food_title, d.quantity_unit, dl.id AS delivery_id, dl.status AS delivery_status
                            FROM food_requests fr
                            JOIN donations d ON d.id = fr.donation_id
                            LEFT JOIN deliveries dl ON dl.request_id = fr.id
                            WHERE fr.requester_id = ? ORDER BY fr.created_at DESC");
        $st->execute([$uid]);
        $rows = [];
        foreach ($st as $r) {
            $delivery = $r['delivery_status'] ? str_replace('_', ' ', $r['delivery_status']) : '—';
            $rows[] = ['#' . $r['id'], e($r['food_title']), e($r['requested_quantity'] . ' ' . $r['quantity_unit']), status_badge($r['status']), e($delivery), e(date('d M Y', strtotime($r['created_at'])))];
        }
        echo '<section class="portal-card"><h2>Your food requests</h2>';
        $table(['ID', 'Food', 'Quantity', 'Request status', 'Delivery', 'Submitted'], $rows);
        echo '</section>';
        return;
    }
    if($p==='application' && $role==='volunteer') { $st=$pdo->prepare('SELECT * FROM volunteer_applications WHERE user_id=?');$st->execute([$uid]);$a=$st->fetch();echo '<section class="portal-card form-card"><div class="card-heading"><h2>Volunteer application</h2>'.($a?status_badge($a['status']):'').'</div><p class="muted-copy">Tell us where you can help and when you are available. Admin approval is required before accepting deliveries.</p><form method="post" class="portal-form">'.$csrf.'<input type="hidden" name="action" value="apply_volunteer"><div class="form-grid"><label>Preferred area / city *<input name="preferred_area" required value="'.e($a['preferred_area']??'').'"></label><label>Availability *<input name="availability" required placeholder="Weekends, evenings..." value="'.e($a['availability']??'').'"></label><label>Vehicle type<select name="vehicle_type"><option '.selected($a['vehicle_type']??'','None').'>None</option><option '.selected($a['vehicle_type']??'','Bicycle').'>Bicycle</option><option '.selected($a['vehicle_type']??'','Two Wheeler').'>Two Wheeler</option><option '.selected($a['vehicle_type']??'','Four Wheeler').'>Four Wheeler</option></select></label><label class="span-two">Experience / note<textarea name="experience" rows="4">'.e($a['experience']??'').'</textarea></label></div><button class="primary-btn">'.($a?'Update application':'Submit application').'</button></form></section>';return; }
    if($p==='available-deliveries' && $role==='volunteer') { $st=$pdo->prepare("SELECT id,status FROM volunteer_applications WHERE user_id=? AND status='approved'");$st->execute([$uid]);if(!$st->fetch()){echo '<div class="notice-card"><i class="fa-solid fa-lock"></i><div><strong>Approval required</strong><p>Your volunteer application must be approved before you can accept deliveries.</p></div><a href="'.e(url('volunteer/application.php')).'">View application</a></div>';return;} $rows=[];$st=$pdo->query("SELECT dl.id,fr.requested_quantity,d.quantity_unit,d.food_title,d.city,d.pickup_address,u.full_name requester FROM deliveries dl JOIN food_requests fr ON fr.id=dl.request_id JOIN donations d ON d.id=fr.donation_id JOIN users u ON u.id=fr.requester_id WHERE dl.status='unassigned' AND dl.volunteer_id IS NULL ORDER BY dl.created_at ASC");foreach($st as $r)$rows[]=['#'.$r['id'],e($r['food_title']),e($r['requested_quantity'].' '.$r['quantity_unit']),e($r['city']),'<a class="table-action" href="'.e(url('volunteer/delivery-details.php?id='.$r['id'])).'">Details</a>','<form method="post">'.$csrf.'<input type="hidden" name="action" value="accept_delivery"><input type="hidden" name="delivery_id" value="'.$r['id'].'"><button class="small-primary">Accept</button></form>'];echo '<section class="portal-card"><h2>Unassigned deliveries</h2>'; $table(['ID','Food','Quantity','City','Details','Action'],$rows);echo '</section>';return; }
    if (($p === 'my-deliveries' || $p === 'delivery-details') && $role === 'volunteer') {
        if ($p === 'my-deliveries') {
            $st = $pdo->prepare('SELECT dl.*, fr.requested_quantity, d.food_title, d.quantity_unit, d.city FROM deliveries dl JOIN food_requests fr ON fr.id=dl.request_id JOIN donations d ON d.id=fr.donation_id WHERE dl.volunteer_id=? ORDER BY dl.created_at DESC');
            $st->execute([$uid]);
            $rows = [];
            foreach ($st as $r) {
                $rows[] = [
                    '#' . (int)$r['id'], e($r['food_title']),
                    e($r['requested_quantity'] . ' ' . $r['quantity_unit']),
                    e($r['city']), status_badge($r['status']),
                    '<a class="table-action" href="' . e(url('volunteer/delivery-details.php?id=' . (int)$r['id'])) . '">Open</a>'
                ];
            }
            echo '<section class="portal-card"><h2>Your deliveries</h2>';
            $table(['ID', 'Food', 'Quantity', 'City', 'Status', ''], $rows);
            echo '</section>';
            return;
        }

        $id = (int)($_GET['id'] ?? 0);
        $st = $pdo->prepare('SELECT dl.*, fr.requested_quantity, fr.purpose, d.food_title, d.pickup_address, d.city, d.contact_name, d.contact_phone FROM deliveries dl JOIN food_requests fr ON fr.id=dl.request_id JOIN donations d ON d.id=fr.donation_id WHERE dl.id=? AND (dl.volunteer_id=? OR (dl.volunteer_id IS NULL AND dl.status="unassigned"))');
        $st->execute([$id, $uid]);
        $r = $st->fetch();
        if (!$r) {
            echo '<div class="portal-card">Delivery not found.</div>';
            return;
        }
        echo '<section class="portal-card"><div class="card-heading"><h2>Delivery #' . (int)$id . '</h2>' . status_badge($r['status']) . '</div>';
        echo '<div class="detail-grid">';
        foreach ([
            'Food' => $r['food_title'],
            'Quantity' => $r['requested_quantity'],
            'Pickup address' => $r['pickup_address'] . ', ' . $r['city'],
            'Contact' => $r['contact_name'] . ' · ' . $r['contact_phone'],
            'Purpose' => $r['purpose']
        ] as $label => $value) {
            echo '<div><small>' . e($label) . '</small><p>' . e((string)$value) . '</p></div>';
        }
        echo '</div>';
        if (!$r['volunteer_id'] || ($r['status']==='assigned' && (int)$r['volunteer_id']===$uid)) {
            echo '<form method="post">' . $csrf . '<input type="hidden" name="action" value="accept_delivery"><input type="hidden" name="delivery_id" value="' . (int)$id . '"><button class="primary-btn">Accept delivery</button></form>';
        } else {
            $next = ['accepted' => 'picked_up', 'picked_up' => 'in_transit', 'in_transit' => 'delivered'];
            if (isset($next[$r['status']])) {
                echo '<form method="post" class="inline-form">' . $csrf . '<input type="hidden" name="action" value="update_delivery"><input type="hidden" name="delivery_id" value="' . (int)$id . '"><input type="hidden" name="status" value="' . e($next[$r['status']]) . '"><button class="primary-btn">Mark as ' . e(str_replace('_', ' ', $next[$r['status']])) . '</button></form>';
            }
        }
        echo '</section>';
        return;
    }
    if($p==='profile') { echo '<section class="portal-card form-card"><h2>Personal profile</h2><form method="post" class="portal-form">'.$csrf.'<input type="hidden" name="action" value="save_profile"><div class="form-grid"><label>Full name *<input name="full_name" required value="'.e($me['full_name']??'').'"></label><label>Email (read only)<input value="'.e($me['email']??'').'" disabled></label><label>Phone<input name="phone" value="'.e($me['phone']??'').'" maxlength="20"></label><label>City<input name="city" value="'.e($me['city']??'').'"></label><label>State<input name="state" value="'.e($me['state']??'').'"></label><label>Pincode<input name="pincode" value="'.e($me['pincode']??'').'"></label><label class="span-two">Address<textarea name="address" rows="2">'.e($me['address_line1']??'').'</textarea></label><label class="span-two">About you<textarea name="bio" rows="3">'.e($me['bio']??'').'</textarea></label></div><button class="primary-btn">Save profile</button></form></section>';return; }
    if($p==='notifications') { $st=$pdo->prepare('SELECT * FROM notifications WHERE user_id=? ORDER BY created_at DESC LIMIT 100');$st->execute([$uid]);echo '<div class="notification-list">';$any=false;foreach($st as $n){$any=true;echo '<article class="notification-card '.(!$n['is_read']?'unread':'').' "><div><strong>'.e($n['title']).'</strong><p>'.e($n['message']).'</p><small>'.e(date('d M Y, g:i a',strtotime($n['created_at']))).'</small></div>'.(!$n['is_read']?'<form method="post">'.$csrf.'<input type="hidden" name="action" value="mark_notification"><input type="hidden" name="id" value="'.$n['id'].'"><button class="table-action">Mark read</button></form>':'').'</article>'; }if(!$any)echo '<div class="portal-card empty-state">You do not have notifications yet.</div>';echo '</div>';return; }
    // ADMIN VIEWS
    if($role==='admin') {
        if($p==='users'||$p==='donors'||$p==='requesters') { $where='';$params=[];$wanted=match($p){'donors'=>'donor','requesters'=>'requester',default=>null};if($wanted){$where=' WHERE role=?';$params[]=$wanted;}if($q!==''){$where.=($where?' AND ':' WHERE ').'(full_name LIKE ? OR email LIKE ?)';$params[]="%$q%";$params[]="%$q%";}$st=$pdo->prepare('SELECT id,full_name,email,phone,role,status,created_at FROM users'.$where.' ORDER BY created_at DESC LIMIT 250');$st->execute($params);$rows=[];foreach($st as $r)$rows[]=['#'.$r['id'],e($r['full_name']),e($r['email']),e($r['phone']),e(ucfirst($r['role'])),status_badge($r['status']),e(date('d M Y',strtotime($r['created_at']))),$r['role']==='admin'?'—':'<form method="post">'.$csrf.'<input type="hidden" name="action" value="user_status"><input type="hidden" name="id" value="'.$r['id'].'"><input type="hidden" name="status" value="'.($r['status']==='active'?'suspended':'active').'"> <button class="table-action">'.($r['status']==='active'?'Suspend':'Activate').'</button></form>'];echo '<section class="portal-card"><form class="search-form"><input name="q" value="'.e($q).'" placeholder="Search name or email"><button class="primary-btn">Search</button></form><h2>'.e(ucfirst($p)).'</h2>'; $table(['ID','Name','Email','Phone','Role','Status','Joined','Action'],$rows);echo '</section>';return; }
        if($p==='donations') { $st=$pdo->query('SELECT d.*,u.full_name donor FROM donations d JOIN users u ON u.id=d.donor_id ORDER BY d.created_at DESC LIMIT 300');$rows=[];foreach($st as $r){$act=$r['status']==='pending'?'<form method="post" class="action-group">'.$csrf.'<input type="hidden" name="action" value="donation_status"><input type="hidden" name="id" value="'.$r['id'].'"><button name="status" value="approved" class="small-primary">Approve</button><button name="status" value="rejected" class="small-danger">Reject</button></form>':'—';$rows[]=['#'.$r['id'],e($r['food_title']),e($r['donor']),e($r['quantity'].' '.$r['quantity_unit']),e($r['available_quantity']),status_badge($r['status']),$act];}echo '<section class="portal-card"><h2>Donation review</h2>'; $table(['ID','Food','Donor','Quantity','Available','Status','Review'],$rows);echo '</section>';return; }
        if($p==='food-requests') { $st=$pdo->query('SELECT fr.*,d.food_title,d.quantity_unit,u.full_name requester FROM food_requests fr JOIN donations d ON d.id=fr.donation_id JOIN users u ON u.id=fr.requester_id ORDER BY fr.created_at DESC LIMIT 300');$rows=[];foreach($st as $r){$act=$r['status']==='pending'?'<form method="post" class="action-group">'.$csrf.'<input type="hidden" name="action" value="request_status"><input type="hidden" name="id" value="'.$r['id'].'"><button name="status" value="approved" class="small-primary">Approve</button><button name="status" value="rejected" class="small-danger">Reject</button></form>':'—';$rows[]=['#'.$r['id'],e($r['food_title']),e($r['requester']),e($r['requested_quantity'].' '.$r['quantity_unit']),status_badge($r['status']),e(date('d M Y',strtotime($r['created_at']))),$act];}echo '<section class="portal-card"><h2>Food requests</h2><p class="muted-copy">Approving a request reserves the requested quantity and creates an unassigned delivery task.</p>'; $table(['ID','Food','Requester','Quantity','Status','Submitted','Review'],$rows);echo '</section>';return; }
        if($p==='volunteers') { $st=$pdo->query('SELECT va.*,COALESCE(u.email,va.email) AS display_email FROM volunteer_applications va LEFT JOIN users u ON u.id=va.user_id ORDER BY va.created_at DESC');$rows=[];foreach($st as $r){$act=$r['status']==='pending'?'<form method="post" class="action-group">'.$csrf.'<input type="hidden" name="action" value="volunteer_status"><input type="hidden" name="id" value="'.$r['id'].'"><button name="status" value="approved" class="small-primary">Approve</button><button name="status" value="rejected" class="small-danger">Reject</button></form>':'—';$rows[]=[e($r['name']),e($r['display_email']),e($r['phone']),e($r['preferred_area']),e($r['availability']),status_badge($r['status']),$act];}echo '<section class="portal-card"><h2>Volunteer applications</h2>'; $table(['Name','Email','Phone','Area','Availability','Status','Review'],$rows);echo '</section>';return; }
        if($p==='deliveries') { $st=$pdo->query("SELECT dl.*,fr.requested_quantity,d.food_title,d.city,v.full_name volunteer FROM deliveries dl JOIN food_requests fr ON fr.id=dl.request_id JOIN donations d ON d.id=fr.donation_id LEFT JOIN users v ON v.id=dl.volunteer_id ORDER BY dl.created_at DESC");$vols=$pdo->query("SELECT u.id,u.full_name FROM users u JOIN volunteer_applications va ON va.user_id=u.id WHERE va.status='approved' AND u.status='active' ORDER BY u.full_name")->fetchAll();$rows=[];foreach($st as $r){$act='—';if($r['status']==='unassigned' && $vols){$act='<form method="post" class="assign-form">'.$csrf.'<input type="hidden" name="action" value="assign_delivery"><input type="hidden" name="id" value="'.$r['id'].'"><select name="volunteer_id">';foreach($vols as $v)$act.='<option value="'.$v['id'].'">'.e($v['full_name']).'</option>';$act.='</select><button class="small-primary">Assign</button></form>';}$rows[]=['#'.$r['id'],e($r['food_title']),e($r['city']),e($r['volunteer']??'Unassigned'),status_badge($r['status']),$act];}echo '<section class="portal-card"><h2>Delivery management</h2>'; $table(['ID','Food','City','Volunteer','Status','Assignment'],$rows);echo '</section>';return; }
        if($p==='feedback') {
            $rows=[];
            $feedbackRows=$pdo->query('SELECT id,type,name,email,category,rating,message,status FROM feedback ORDER BY created_at DESC LIMIT 300')->fetchAll();
            foreach($feedbackRows as $r){
                $action='<form method="post">'.$csrf.'<input type="hidden" name="action" value="feedback_status"><input type="hidden" name="id" value="'.$r['id'].'"><button class="table-action" name="status" value="'.($r['status']==='archived'?'read':'archived').'">'.($r['status']==='archived'?'Mark read':'Archive').'</button></form>';
                $rows[]=[e(strtoupper($r['type'])),e($r['name']),e($r['email']),e($r['category']),e($r['rating']?(string)$r['rating'].'/5':'—'),e(mb_strimwidth($r['message'],0,100,'…')),status_badge($r['status']),$action];
            }
            $contactRows=$pdo->query('SELECT id,name,email,subject,message,status FROM contact_messages ORDER BY created_at DESC LIMIT 300')->fetchAll();
            foreach($contactRows as $r){
                $action='<form method="post">'.$csrf.'<input type="hidden" name="action" value="contact_status"><input type="hidden" name="id" value="'.$r['id'].'"><button class="table-action" name="status" value="'.($r['status']==='archived'?'read':'archived').'">'.($r['status']==='archived'?'Mark read':'Archive').'</button></form>';
                $rows[]=['CONTACT',e($r['name']),e($r['email']),e($r['subject']),'—',e(mb_strimwidth($r['message'],0,100,'…')),status_badge($r['status']),$action];
            }
            echo '<section class="portal-card"><h2>Contact messages & feedback</h2>';
            $table(['Type','Name','Email','Category / Subject','Rating','Message','Status','Action'],$rows);
            echo '</section>';return;
        }
        if($p==='reports') { $month=$pdo->query("SELECT DATE_FORMAT(created_at,'%Y-%m') month,COUNT(*) total FROM donations GROUP BY DATE_FORMAT(created_at,'%Y-%m') ORDER BY month DESC LIMIT 12")->fetchAll();echo '<div class="metric-grid">';foreach([['Total donations','SELECT COUNT(*) FROM donations'],['Total servings listed','SELECT COALESCE(SUM(quantity),0) FROM donations'],['Completed deliveries',"SELECT COUNT(*) FROM deliveries WHERE status='delivered'"],['Active volunteers',"SELECT COUNT(*) FROM volunteer_applications WHERE status='approved'"]] as [$l,$sql])$metric($l,(int)$pdo->query($sql)->fetchColumn(),'fa-chart-line');echo '</div><section class="portal-card"><h2>Monthly donation activity</h2>'; $rows=[];foreach($month as $m)$rows[]=[e($m['month']),e((string)$m['total'])];$table(['Month','Donations'],$rows);echo '</section>';return; }
        if($p==='settings') {
            $settings = get_app_settings($pdo);
            echo '<section class="portal-card form-card"><div class="card-heading"><h2>Platform settings</h2><span class="status-badge status-approved">Saved in database</span></div>';
            echo '<p class="muted-copy">Edit the support details shown on the public Contact page and control whether new accounts can register. Changes are saved in MySQL and stay in place after restarting XAMPP.</p>';
            echo '<form method="post" class="portal-form">'.$csrf.'<input type="hidden" name="action" value="save_platform_settings">';
            echo '<div class="form-grid">';
            echo '<label>Support email *<input type="email" name="support_email" maxlength="150" required value="'.e($settings['support_email']).'"></label>';
            echo '<label>Support phone<input type="text" name="support_phone" maxlength="30" value="'.e($settings['support_phone']).'"></label>';
            echo '<label>Default city *<input type="text" name="default_city" maxlength="100" required value="'.e($settings['default_city']).'"></label>';
            echo '<label class="span-two">Contact address<input type="text" name="contact_address" maxlength="255" value="'.e($settings['contact_address']).'"></label>';
            echo '</div>';
            echo '<label class="settings-toggle"><input type="checkbox" name="registration_enabled" value="1" '.checked($settings['registration_enabled']==='1').'> Allow new donor, requester and volunteer registrations</label>';
            echo '<button class="primary-btn" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save settings</button></form></section>';
            return;
        }
    }
    echo '<section class="portal-card"><h2>Page not found</h2><p>This portal page is not configured.</p></section>';
}

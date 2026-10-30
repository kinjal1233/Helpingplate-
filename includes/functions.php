<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../backend/config/db.php';

function e(?string $value): string { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function url(string $path=''): string { return BASE_URL . ($path !== '' ? '/' . ltrim($path, '/') : '/'); }
function redirect(string $path): never { header('Location: ' . (preg_match('~^https?://~i',$path) ? $path : url($path))); exit; }
function flash(string $message, string $type='success'): void { $_SESSION['_flash']=['message'=>$message,'type'=>$type]; }
function get_flash(): ?array { $f=$_SESSION['_flash']??null; unset($_SESSION['_flash']); return $f; }
function csrf_token(): string { if (empty($_SESSION['_csrf'])) $_SESSION['_csrf']=bin2hex(random_bytes(32)); return $_SESSION['_csrf']; }
function csrf_field(): string { return '<input type="hidden" name="_csrf" value="'.e(csrf_token()).'">'; }
function verify_csrf(): void { if ($_SERVER['REQUEST_METHOD']==='POST') { $token=(string)($_POST['_csrf']??''); if (!$token || !hash_equals(csrf_token(),$token)) { http_response_code(419); exit('Your form session expired. Please go back, refresh the page and try again.'); } } }
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function is_logged_in(): bool { return !empty($_SESSION['user']['id']); }
function require_login(array $roles=[]): void { if (!is_logged_in()) { flash('Please log in to continue.','error'); redirect('login.php'); } if ($roles && !in_array($_SESSION['user']['role']??'', $roles, true)) { http_response_code(403); exit('You do not have permission to access this page.'); } }
function notify_user(int $userId, string $title, string $message, ?string $link=null): void { $s=db()->prepare('INSERT INTO notifications(user_id,title,message,link) VALUES(?,?,?,?)'); $s->execute([$userId,$title,$message,$link]); }
function log_activity(?int $userId, string $action, string $description): void { $s=db()->prepare('INSERT INTO activity_logs(user_id,action,description,ip_address) VALUES(?,?,?,?)'); $s->execute([$userId,$action,$description,$_SERVER['REMOTE_ADDR']??null]); }
function selected(string $a, string $b): string { return $a===$b?'selected':''; }
function checked(bool $v): string { return $v?'checked':''; }
function post_string(string $key, int $max=5000): string { $v=trim((string)($_POST[$key]??'')); return mb_substr($v,0,$max); }
function status_badge(string $status): string { $s=strtolower(str_replace(' ','_',$status)); return '<span class="status-badge status-'.e($s).'">'.e(ucwords(str_replace('_',' ',$status))).'</span>'; }
function upload_image(string $field, string $folder='donations'): ?string {
    if (empty($_FILES[$field]) || ($_FILES[$field]['error']??UPLOAD_ERR_NO_FILE)===UPLOAD_ERR_NO_FILE) return null;
    $f=$_FILES[$field]; if ($f['error']!==UPLOAD_ERR_OK) throw new RuntimeException('The image upload failed.');
    if ($f['size']>5*1024*1024) throw new RuntimeException('Images must be 5 MB or smaller.');
    $finfo=new finfo(FILEINFO_MIME_TYPE); $mime=$finfo->file($f['tmp_name']);
    $allowed=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
    if (!isset($allowed[$mime])) throw new RuntimeException('Upload a JPG, PNG or WEBP image.');
    $name=bin2hex(random_bytes(16)).'.'.$allowed[$mime]; $dir=ROOT_PATH.'/uploads/'.$folder;
    if (!is_dir($dir)) mkdir($dir,0755,true);
    if (!move_uploaded_file($f['tmp_name'],$dir.'/'.$name)) throw new RuntimeException('Could not save the uploaded image.');
    return 'uploads/'.$folder.'/'.$name;
}
function unread_count(int $userId): int { $s=db()->prepare('SELECT COUNT(*) FROM notifications WHERE user_id=? AND is_read=0'); $s->execute([$userId]); return (int)$s->fetchColumn(); }
function role_home(string $role): string { return match($role){'donor'=>'donor/dashboard.php','requester'=>'requester/dashboard.php','volunteer'=>'volunteer/dashboard.php','admin'=>'admin/dashboard.php',default=>'index.php'}; }


/** Create the small settings table on demand so existing XAMPP databases upgrade automatically. */
function ensure_app_settings_table(?PDO $pdo = null): void {
    static $ready = false;
    if ($ready) return;
    $pdo = $pdo ?: db();
    $pdo->exec("CREATE TABLE IF NOT EXISTS app_settings (
        setting_key VARCHAR(80) NOT NULL PRIMARY KEY,
        setting_value TEXT NOT NULL,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $ready = true;
}

function get_app_settings(?PDO $pdo = null): array {
    static $cache = null;
    if ($cache !== null) return $cache;
    $pdo = $pdo ?: db();
    ensure_app_settings_table($pdo);
    $defaults = [
        'support_email' => 'hello@helpingplate.org',
        'support_phone' => '',
        'contact_address' => '',
        'default_city' => 'Ahmedabad',
        'registration_enabled' => '1'
    ];
    $rows = $pdo->query('SELECT setting_key, setting_value FROM app_settings')->fetchAll();
    foreach ($rows as $row) {
        if (array_key_exists($row['setting_key'], $defaults)) {
            $defaults[$row['setting_key']] = (string)$row['setting_value'];
        }
    }
    $cache = $defaults;
    return $cache;
}

function app_setting(string $key, string $fallback = ''): string {
    $settings = get_app_settings();
    return array_key_exists($key, $settings) ? $settings[$key] : $fallback;
}

function save_app_settings(PDO $pdo, array $settings): void {
    ensure_app_settings_table($pdo);
    $statement = $pdo->prepare('INSERT INTO app_settings(setting_key, setting_value) VALUES(?, ?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)');
    foreach ($settings as $key => $value) {
        $statement->execute([(string)$key, (string)$value]);
    }
}

/** Password reset tokens are stored as SHA-256 hashes and expire after one hour. */
function ensure_password_resets_table(?PDO $pdo = null): void {
    static $ready = false;
    if ($ready) return;
    $pdo = $pdo ?: db();
    $pdo->exec("CREATE TABLE IF NOT EXISTS password_resets (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNSIGNED NOT NULL,
        token_hash CHAR(64) NOT NULL UNIQUE,
        request_ip VARCHAR(45) NULL,
        requested_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        expires_at DATETIME NOT NULL,
        used_at DATETIME NULL,
        CONSTRAINT fk_password_resets_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_password_resets_user_time(user_id, requested_at),
        INDEX idx_password_resets_expiry(expires_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $ready = true;
}

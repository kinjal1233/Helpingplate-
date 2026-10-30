<?php
require_once __DIR__.'/includes/bootstrap.php';
if(is_logged_in()) redirect(role_home(current_user()['role'])); $error='';
$registrationEnabled = app_setting('registration_enabled', '1') === '1';
if($_SERVER['REQUEST_METHOD']==='POST'){verify_csrf();if(!$registrationEnabled){$error='New registrations are temporarily disabled by the administrator.';}else{$role=(string)($_POST['role']??'');$name=post_string('name',100);$email=filter_var(trim((string)($_POST['email']??'')),FILTER_VALIDATE_EMAIL);$phone=post_string('phone',25);$pass=(string)($_POST['password']??'');$confirm=(string)($_POST['confirm_password']??'');if(!in_array($role,['donor','requester','volunteer'],true)||mb_strlen($name)<2||!$email||mb_strlen($phone)<8||strlen($pass)<8||$pass!==$confirm)$error='Check your details. Passwords must be at least 8 characters and match.';else{try{$pdo=db();$pdo->beginTransaction();$st=$pdo->prepare('INSERT INTO users(full_name,email,phone,password_hash,role) VALUES(?,?,?,?,?)');$st->execute([$name,$email,$phone,password_hash($pass,PASSWORD_DEFAULT),$role]);$id=(int)$pdo->lastInsertId();$pdo->prepare('INSERT INTO user_profiles(user_id) VALUES(?)')->execute([$id]);if($role==='volunteer'){
    $legacy=$pdo->prepare('SELECT id FROM volunteer_applications WHERE email=? AND user_id IS NULL ORDER BY created_at DESC LIMIT 1 FOR UPDATE');
    $legacy->execute([$email]);
    $legacyId=$legacy->fetchColumn();
    if($legacyId){
        $pdo->prepare('UPDATE volunteer_applications SET user_id=?,name=?,phone=? WHERE id=?')->execute([$id,$name,$phone,(int)$legacyId]);
    } else {
        $pdo->prepare("INSERT INTO volunteer_applications(user_id,name,email,phone,preferred_area,availability,status) VALUES(?,?,?,?, 'Not yet provided','To be updated','pending')")->execute([$id,$name,$email,$phone]);
    }
}$pdo->commit();session_regenerate_id(true);$_SESSION['user']=['id'=>$id,'full_name'=>$name,'email'=>$email,'phone'=>$phone,'role'=>$role];log_activity($id,'registered','New '.$role.' account created');flash('Your account has been created. Welcome to HelpingPlate!');redirect(role_home($role));}catch(PDOException $ex){if(isset($pdo)&&$pdo->inTransaction())$pdo->rollBack();$error=$ex->getCode()==='23000'?'An account with this email already exists.':'Registration could not be completed. Please try again.';}}}}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Account | HelpingPlate</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap"
        rel="stylesheet"
    >

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    >

    <!-- Main CSS -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Authentication CSS -->
    <link rel="stylesheet" href="css/auth.css">

    <link rel="stylesheet" href="css/branding.css">
</head>


<body>
<?php if ($flash = get_flash()): ?><div class="hp-flash <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>
<?php if (!$registrationEnabled): ?><div class="hp-flash error">New registrations are temporarily disabled by the administrator. Existing users can still log in.</div><?php endif; ?>


<!-- =====================================================
     NAVBAR
===================================================== -->

<header class="hp-navbar">

    <div class="hp-container hp-nav-container">


        <!-- Logo -->

        <a href="index.php" class="hp-logo">

            <img
                src="images/logo.png"
                alt="HelpingPlate Logo"
            >

            <div class="hp-logo-text">
                <span>Helping<span>Plate</span></span>
                <small>Good Food • Greater Impact</small>
            </div>

        </a>


        <!-- Navigation -->

        <nav id="hpNavLinks" class="hp-nav-links">

            <a href="index.php">
                Home
            </a>

            <a href="about.php">
                About
            </a>

            <a href="how-it-works.php">
                How It Works
            </a>

            <a href="volunteer.php">
                Volunteer
            </a>

            <a href="feedback.php">
                Feedback
            </a>

            <a href="login.php">
                Login
            </a>

            <a
                href="get-started.php"
                class="hp-nav-btn active"
            >
                Get Started
            </a>

        </nav>


        <!-- Mobile Menu -->

        <button type="button" id="menuBtn" aria-controls="hpNavLinks" aria-expanded="false" aria-label="Open menu"
            class="hp-menu-btn">

            <i class="fa-solid fa-bars"></i>

        </button>

    </div>

</header>



<!-- =====================================================
     REGISTER PAGE
===================================================== -->

<main class="auth-page register-page">

    <div class="auth-container register-container">


        <!-- =================================================
             LEFT INFORMATION
        ================================================== -->

        <div class="auth-intro register-intro">


            <span class="auth-badge">

                <i class="fa-solid fa-user-plus"></i>

                Join HelpingPlate

            </span>


            <h1>

                Create your
                <span>account.</span>

            </h1>


            <p>

                Join the HelpingPlate community and choose
                how you would like to participate in the
                food redistribution process.

            </p>


            <!-- Registration Benefits -->

            <div class="register-benefits">


                <div class="register-benefit">

                    <div class="register-benefit-icon">

                        <i class="fa-solid fa-circle-check"></i>

                    </div>

                    <div>

                        <h3>
                            Simple Registration
                        </h3>

                        <p>
                            Create your account with basic
                            information.
                        </p>

                    </div>

                </div>



                <div class="register-benefit">

                    <div class="register-benefit-icon">

                        <i class="fa-solid fa-chart-line"></i>

                    </div>

                    <div>

                        <h3>
                            Personal Dashboard
                        </h3>

                        <p>
                            Manage your activities from one place.
                        </p>

                    </div>

                </div>



                <div class="register-benefit">

                    <div class="register-benefit-icon">

                        <i class="fa-solid fa-handshake"></i>

                    </div>

                    <div>

                        <h3>
                            Be Part of the Process
                        </h3>

                        <p>
                            Connect your role with the food
                            redistribution workflow.
                        </p>

                    </div>

                </div>


            </div>


        </div>



        <!-- =================================================
             REGISTRATION CARD
        ================================================== -->

        <div class="auth-card register-card">


            <div class="auth-card-header">


                <div class="auth-logo-small">

                    <img
                        src="images/logo.png"
                        alt="HelpingPlate Logo"
                    >

                </div>


                <h2>
                    Create Your Account
                </h2>


                <p>
                    Fill in the information below to register.
                </p>


            </div>



            <!-- Registration Form -->

            <form id="registerForm" class="auth-form" method="post" action="register.php">
                <?= csrf_field() ?>
                <?php if ($error): ?><div class="auth-error"><?= e($error) ?></div><?php endif; ?>


                <!-- Role -->

                <div class="auth-field">

                    <label for="registerRole">

                        Account Type
                        <span>*</span>

                    </label>


                    <div class="auth-input-wrapper">

                        <i class="fa-solid fa-user-tag"></i>


                        <select
                            id="registerRole"
                            name="role"
                            required
                        >

                            <option value="">
                                Select your role
                            </option>

                            <option value="donor">
                                Donor
                            </option>

                            <option value="requester">
                                Requester
                            </option>

                            <option value="volunteer">
                                Volunteer
                            </option>

                        </select>

                    </div>

                </div>



                <!-- Full Name -->

                <div class="auth-field">

                    <label for="registerName">

                        Full Name
                        <span>*</span>

                    </label>


                    <div class="auth-input-wrapper">

                        <i class="fa-regular fa-user"></i>


                        <input
                            type="text"
                            id="registerName"
                            name="name"
                            placeholder="Enter your full name"
                            required
                            minlength="2"
                        >

                    </div>

                </div>



                <!-- Email -->

                <div class="auth-field">

                    <label for="registerEmail">

                        Email Address
                        <span>*</span>

                    </label>


                    <div class="auth-input-wrapper">

                        <i class="fa-regular fa-envelope"></i>


                        <input
                            type="email"
                            id="registerEmail"
                            name="email"
                            placeholder="Enter your email"
                            required
                        >

                    </div>

                </div>



                <!-- Phone -->

                <div class="auth-field">

                    <label for="registerPhone">

                        Phone Number
                        <span>*</span>

                    </label>


                    <div class="auth-input-wrapper">

                        <i class="fa-solid fa-phone"></i>


                        <input
                            type="tel"
                            id="registerPhone"
                            name="phone"
                            placeholder="Enter your phone number"
                            required
                            minlength="10"
                            maxlength="15"
                        >

                    </div>

                </div>



                <!-- Password Row -->

                <div class="register-password-row">


                    <!-- Password -->

                    <div class="auth-field">

                        <label for="registerPassword">

                            Password
                            <span>*</span>

                        </label>


                        <div class="auth-input-wrapper">

                            <i class="fa-solid fa-lock"></i>


                            <input
                                type="password"
                                id="registerPassword"
                                name="password"
                                placeholder="Create password"
                                required
                                minlength="6"
                            >

                        </div>

                    </div>



                    <!-- Confirm Password -->

                    <div class="auth-field">

                        <label for="confirmPassword">

                            Confirm Password
                            <span>*</span>

                        </label>


                        <div class="auth-input-wrapper">

                            <i class="fa-solid fa-lock"></i>


                            <input
                                type="password"
                                id="confirmPassword"
                                name="confirm_password"
                                placeholder="Confirm password"
                                required
                                minlength="6"
                            >

                        </div>

                    </div>


                </div>



                <!-- Agreement -->

                <div class="register-agreement">

                    <input
                        type="checkbox"
                        id="termsAgreement"
                        required
                    >


                    <label for="termsAgreement">

                        I understand that the information provided
                        will be used to create my HelpingPlate account.

                    </label>

                </div>



                <!-- Submit -->

                <button
                    type="submit"
                    class="auth-submit-btn"
                    <?= !$registrationEnabled ? 'disabled aria-disabled="true"' : '' ?>
                >

                    Create Account

                    <i class="fa-solid fa-arrow-right"></i>

                </button>


            </form>



            <!-- Login -->

            <div class="auth-register">

                <p>

                    Already have an account?

                    <a href="login.php">
                        Login here
                    </a>

                </p>

            </div>



            <!-- Security -->

            <div class="auth-security-note">

                <i class="fa-solid fa-shield-halved"></i>

                <p>

                    Your registration information will be
                    handled through the HelpingPlate account
                    system.

                </p>

            </div>


        </div>

    </div>

</main>



<!-- =====================================================
     FOOTER
===================================================== -->

<footer class="hp-footer">

    <div class="hp-container">


        <div class="hp-footer-grid">


            <!-- Brand -->

            <div class="hp-footer-brand">

                <a
                    href="index.php"
                    class="hp-logo"
                >

                    <img
                        src="images/logo.png"
                        alt="HelpingPlate Logo"
                    >

                    <div class="hp-logo-text">
                <span>Helping<span>Plate</span></span>
                <small>Good Food • Greater Impact</small>
            </div>

                </a>


                <p>

                    HelpingPlate connects surplus food
                    with people who can help put it
                    to meaningful use.

                </p>


                <div class="hp-social-links">

                    <a href="#" aria-label="Instagram">
                        <i class="fa-brands fa-instagram"></i>
                    </a>

                    <a href="#" aria-label="Facebook">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>

                    <a href="#" aria-label="LinkedIn">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </a>

                </div>

            </div>



            <!-- Quick Links -->

            <div class="hp-footer-column">

                <h4>
                    Quick Links
                </h4>

                <a href="index.php">
                    Home
                </a>

                <a href="about.php">
                    About
                </a>

                <a href="how-it-works.php">
                    How It Works
                </a>

                <a href="volunteer.php">
                    Volunteer
                </a>

                <a href="feedback.php">
                    Feedback
                </a>

            </div>



            <!-- Get Involved -->

            <div class="hp-footer-column">

                <h4>
                    Get Involved
                </h4>

                <a href="get-started.php">
                    Donate Food
                </a>

                <a href="get-started.php">
                    Request Food
                </a>

                <a href="volunteer.php">
                    Become a Volunteer
                </a>

            </div>



            <!-- Contact -->

            <div class="hp-footer-column">

                <h4>
                    Contact
                </h4>

                <p>
                    <i class="fa-solid fa-envelope"></i>
                    hello@helpingplate.org
                </p>

                <p>
                    <i class="fa-solid fa-location-dot"></i>
                    Helping communities through food sharing
                </p>

            </div>


        </div>



        <div class="hp-footer-bottom">

            <p>
                © 2026 HelpingPlate. All rights reserved.
            </p>

            <p>
                Built to support responsible food sharing.
            </p>

        </div>


    </div>

</footer>



<!-- Main JavaScript -->
<script src="js/script.js"></script>

<!-- Authentication JavaScript -->
<script src="js/auth.js"></script>


</body>
</html>
<?php
require_once __DIR__.'/includes/bootstrap.php';
if (is_logged_in()) redirect(role_home(current_user()['role']));
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') { verify_csrf(); $email=filter_var(trim((string)($_POST['email']??'')),FILTER_VALIDATE_EMAIL); $password=(string)($_POST['password']??''); if(!$email||$password==='') $error='Enter a valid email and password.'; else { $st=db()->prepare('SELECT id,full_name,email,phone,password_hash,role,status FROM users WHERE email=? LIMIT 1'); $st->execute([$email]); $u=$st->fetch(); if(!$u||!password_verify($password,$u['password_hash'])) $error='Email or password is incorrect.'; elseif($u['status']!=='active') $error='This account is currently suspended. Contact the administrator.'; else { session_regenerate_id(true); $_SESSION['user']=['id'=>(int)$u['id'],'full_name'=>$u['full_name'],'email'=>$u['email'],'phone'=>$u['phone'],'role'=>$u['role']]; log_activity((int)$u['id'],'login','User logged in'); redirect(role_home($u['role'])); } } }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | HelpingPlate</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Authentication CSS -->
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="css/branding.css">
</head>

<body>
<?php if ($flash = get_flash()): ?><div class="hp-flash <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>

<!-- =========================
     NAVBAR
========================= -->

<header class="hp-navbar">

    <div class="hp-container hp-nav-container">

        <!-- Logo -->
        <a href="index.php" class="hp-logo">

            <img src="images/logo.png" alt="HelpingPlate Logo">

            <div class="hp-logo-text">
                <span>Helping<span>Plate</span></span>
                <small>Good Food • Greater Impact</small>
            </div>

        </a>


        <!-- Navigation -->
        <nav id="hpNavLinks" class="hp-nav-links">

            <a href="index.php">Home</a>

            <a href="about.php">About</a>

            <a href="how-it-works.php">How It Works</a>

            <a href="volunteer.php">Volunteer</a>

            <a href="feedback.php">Feedback</a>

            <a href="login.php" class="active">Login</a>

            <a href="get-started.php" class="hp-nav-btn">
                Get Started
            </a>

        </nav>


        <!-- Mobile Menu -->
        <button type="button" id="menuBtn" aria-controls="hpNavLinks" aria-expanded="false" aria-label="Open menu" class="hp-menu-btn">
            <i class="fa-solid fa-bars"></i>
        </button>

    </div>

</header>



<!-- =========================
     LOGIN PAGE
========================= -->

<main class="auth-page">

    <div class="auth-container">


        <!-- Left Side -->
        <div class="auth-intro">

            <span class="auth-badge">
                <i class="fa-solid fa-hand-holding-heart"></i>
                Welcome Back
            </span>

            <h1>
                Good to see you
                <span>again.</span>
            </h1>

            <p>
                Sign in to your HelpingPlate account and continue
                contributing to a better way of sharing surplus food.
            </p>


            <!-- Role Information -->

            <div class="auth-role-list">

                <div class="auth-role-item">

                    <div class="auth-role-icon">
                        <i class="fa-solid fa-basket-shopping"></i>
                    </div>

                    <div>
                        <h3>Donor</h3>
                        <p>Share surplus food with the community.</p>
                    </div>

                </div>


                <div class="auth-role-item">

                    <div class="auth-role-icon">
                        <i class="fa-solid fa-utensils"></i>
                    </div>

                    <div>
                        <h3>Requester</h3>
                        <p>Request available food when needed.</p>
                    </div>

                </div>


                <div class="auth-role-item">

                    <div class="auth-role-icon">
                        <i class="fa-solid fa-truck"></i>
                    </div>

                    <div>
                        <h3>Volunteer</h3>
                        <p>Help coordinate food deliveries.</p>
                    </div>

                </div>

            </div>

        </div>



        <!-- Login Card -->
        <div class="auth-card">

            <div class="auth-card-header">

                <div class="auth-logo-small">

                    <img
                        src="images/logo.png"
                        alt="HelpingPlate Logo"
                    >

                </div>

                <h2>Login to HelpingPlate</h2>

                <p>
                    Enter your account details to continue.
                </p>

            </div>


            <!-- Login Form -->

            <form id="loginForm" class="auth-form" method="post" action="login.php">
                <?= csrf_field() ?>
                <?php if ($error): ?><div class="auth-error"><?= e($error) ?></div><?php endif; ?>


                <!-- Email -->
                <div class="auth-field">

                    <label for="loginEmail">
                        Email Address <span>*</span>
                    </label>

                    <div class="auth-input-wrapper">

                        <i class="fa-regular fa-envelope"></i>

                        <input
                            type="email"
                            id="loginEmail"
                            name="email"
                            placeholder="Enter your email"
                            required
                        >

                    </div>

                </div>



                <!-- Password -->
                <div class="auth-field">

                    <div class="auth-label-row">

                        <label for="loginPassword">
                            Password <span>*</span>
                        </label>

                        <a href="forgot-password.php" class="forgot-password">Forgot password?</a>

                    </div>


                    <div class="auth-input-wrapper">

                        <i class="fa-solid fa-lock"></i>

                        <input
                            type="password"
                            id="loginPassword"
                            name="password"
                            placeholder="Enter your password"
                            required
                            minlength="6"
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            id="passwordToggle"
                            aria-label="Show password"
                        >
                            <i class="fa-regular fa-eye"></i>
                        </button>

                    </div>

                </div>



                <!-- Remember Me -->
                <div class="auth-options">

                    <label class="remember-option">

                        <input
                            type="checkbox"
                            id="rememberMe"
                            name="remember"
                        >

                        <span>
                            Remember me
                        </span>

                    </label>

                </div>



                <!-- Login Button -->
                <button
                    type="submit"
                    class="auth-submit-btn"
                >

                    Login

                    <i class="fa-solid fa-arrow-right"></i>

                </button>


            </form>


            <!-- Register -->
            <div class="auth-register">

                <p>
                    Don't have an account?
                    <a href="register.php">
                        Create an account
                    </a>
                </p>

            </div>


            <!-- Demo Role Information -->
            <div class="auth-security-note">

                <i class="fa-solid fa-shield-halved"></i>

                <p>
                    Your account information is protected and
                    used only for accessing the HelpingPlate platform.
                </p>

            </div>

        </div>

    </div>

</main>



<!-- =========================
     FOOTER
========================= -->

<footer class="hp-footer">

    <div class="hp-container">

        <div class="hp-footer-grid">


            <!-- Brand -->
            <div class="hp-footer-brand">

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

                <p>
                    HelpingPlate connects surplus food with people
                    who can help put it to meaningful use.
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

                <h4>Quick Links</h4>

                <a href="index.php">Home</a>

                <a href="about.php">About</a>

                <a href="how-it-works.php">How It Works</a>

                <a href="volunteer.php">Volunteer</a>

                <a href="feedback.php">Feedback</a>

            </div>


            <!-- Get Involved -->
            <div class="hp-footer-column">

                <h4>Get Involved</h4>

                <a href="get-started.php">Donate Food</a>

                <a href="get-started.php">Request Food</a>

                <a href="volunteer.php">Become a Volunteer</a>

            </div>


            <!-- Contact -->
            <div class="hp-footer-column">

                <h4>Contact</h4>

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
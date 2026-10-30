<?php
require_once __DIR__.'/includes/bootstrap.php';
if($_SERVER['REQUEST_METHOD']==='POST'){verify_csrf();$name=post_string('name',100);$email=filter_var(trim((string)($_POST['email']??'')),FILTER_VALIDATE_EMAIL);$category=post_string('category',80);$rating=(int)($_POST['rating']??0);$message=post_string('message',3000);$allow=isset($_POST['permission'])?1:0;if($category===''||$rating<1||$rating>5||mb_strlen($message)<10){flash('Please select a feedback type, rating and enter at least 10 characters.','error');redirect('feedback.php#feedback-form');}db()->prepare("INSERT INTO feedback(type,name,email,category,rating,message,allow_contact) VALUES('feedback',?,?,?,?,?,?)")->execute([$name?:'Website visitor',$email?:'no-reply@example.invalid',$category,$rating,$message,$allow]);flash('Thank you. Your feedback has been saved for the HelpingPlate team.');redirect('feedback.php');}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Feedback | HelpingPlate</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/branding.css">
</head>

<body>
<?php if ($flash = get_flash()): ?><div class="hp-flash <?= e($flash['type']) ?>" role="status"><?= e($flash['message']) ?></div><?php endif; ?>

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

            <a href="feedback.php" class="active">Feedback</a>

            <a href="login.php">Login</a>

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
     FEEDBACK HERO
========================= -->

<section class="feedback-hero">

    <div class="hp-container">

        <div class="feedback-hero-content">

            <span class="feedback-badge">
                <i class="fa-regular fa-message"></i>
                Your Voice Matters
            </span>

            <h1>
                Help Us Make <span>HelpingPlate</span> Better
            </h1>

            <p>
                Your experience, suggestions and ideas help us improve
                the way food is shared and delivered through our platform.
            </p>

            <a href="#feedback-form" class="feedback-primary-btn">
                Share Your Feedback
                <i class="fa-solid fa-arrow-down"></i>
            </a>

        </div>

    </div>

</section>



<!-- =========================
     WHY FEEDBACK
========================= -->

<section class="feedback-why">

    <div class="hp-container">

        <div class="feedback-section-heading">

            <span class="feedback-small-title">
                WE LISTEN
            </span>

            <h2>
                Every Suggestion Can Make a Difference
            </h2>

            <p>
                HelpingPlate is designed to connect people who have
                surplus food with those who can help distribute it.
                Feedback allows us to understand what is working well
                and where the platform can become easier and more useful.
            </p>

        </div>


        <div class="feedback-benefit-grid">

            <div class="feedback-benefit-card">

                <div class="feedback-icon feedback-green">
                    <i class="fa-solid fa-lightbulb"></i>
                </div>

                <h3>Share Ideas</h3>

                <p>
                    Tell us about features or improvements that could
                    make HelpingPlate more convenient for you.
                </p>

            </div>


            <div class="feedback-benefit-card">

                <div class="feedback-icon feedback-orange">
                    <i class="fa-solid fa-star"></i>
                </div>

                <h3>Share Your Experience</h3>

                <p>
                    Let us know how easy you found the platform and
                    which parts of your experience stood out.
                </p>

            </div>


            <div class="feedback-benefit-card">

                <div class="feedback-icon feedback-purple">
                    <i class="fa-solid fa-comments"></i>
                </div>

                <h3>Tell Us What Matters</h3>

                <p>
                    Your comments can help us understand the needs of
                    donors, requesters and volunteers.
                </p>

            </div>

        </div>

    </div>

</section>



<!-- =========================
     FEEDBACK FORM
========================= -->

<section class="feedback-form-section" id="feedback-form">

    <div class="hp-container">

        <div class="feedback-form-wrapper">


            <!-- Form Introduction -->
            <div class="feedback-form-intro">

                <span class="feedback-small-title">
                    FEEDBACK FORM
                </span>

                <h2>
                    Tell Us What You Think
                </h2>

                <p>
                    You can share your experience, suggestion or
                    concern using the form below.
                </p>

                <div class="feedback-note">

                    <i class="fa-solid fa-circle-info"></i>

                    <span>
                        Fields marked with <strong>*</strong> are important
                        and need to be completed.
                    </span>

                </div>

            </div>



            <!-- Form -->
            <form id="feedbackForm" class="feedback-form" method="post" action="feedback.php">
                <?= csrf_field() ?>


                <!-- Name + Email -->
                <div class="feedback-form-row">

                    <div class="feedback-field">

                        <label for="feedbackName">
                            Name
                        </label>

                        <input
                            type="text"
                            id="feedbackName"
                            name="name"
                            placeholder="Enter your name"
                        >

                    </div>


                    <div class="feedback-field">

                        <label for="feedbackEmail">
                            Email
                        </label>

                        <input
                            type="email"
                            id="feedbackEmail"
                            name="email"
                            placeholder="Enter your email"
                        >

                    </div>

                </div>



                <!-- Category -->
                <div class="feedback-field">

                    <label for="feedbackCategory">
                        Feedback Type <span>*</span>
                    </label>

                    <select
                        id="feedbackCategory"
                        name="category"
                        required
                    >

                        <option value="">
                            Select feedback type
                        </option>

                        <option value="suggestion">
                            Suggestion
                        </option>

                        <option value="experience">
                            User Experience
                        </option>

                        <option value="feature">
                            Feature Request
                        </option>

                        <option value="issue">
                            Report an Issue
                        </option>

                        <option value="other">
                            Other
                        </option>

                    </select>

                </div>



                <!-- Rating -->
                <div class="feedback-field">

                    <label>
                        How was your experience?
                        <span>*</span>
                    </label>

                    <div class="feedback-rating">

                        <label>
                            <input
                                type="radio"
                                name="rating"
                                value="5"
                                required
                            >
                            <span>5</span>
                        </label>

                        <label>
                            <input
                                type="radio"
                                name="rating"
                                value="4"
                            >
                            <span>4</span>
                        </label>

                        <label>
                            <input
                                type="radio"
                                name="rating"
                                value="3"
                            >
                            <span>3</span>
                        </label>

                        <label>
                            <input
                                type="radio"
                                name="rating"
                                value="2"
                            >
                            <span>2</span>
                        </label>

                        <label>
                            <input
                                type="radio"
                                name="rating"
                                value="1"
                            >
                            <span>1</span>
                        </label>

                    </div>

                    <small>
                        5 = Very helpful &nbsp; | &nbsp; 1 = Needs improvement
                    </small>

                </div>



                <!-- Message -->
                <div class="feedback-field">

                    <label for="feedbackMessage">
                        Your Feedback <span>*</span>
                    </label>

                    <textarea
                        id="feedbackMessage"
                        name="message"
                        rows="6"
                        placeholder="Write your feedback here..."
                        minlength="10"
                        required
                    ></textarea>

                </div>



                <!-- Permission -->
                <div class="feedback-checkbox">

                    <input
                        type="checkbox"
                        id="feedbackPermission"
                        name="permission"
                    >

                    <label for="feedbackPermission">
                        You may contact me if more information is needed
                        about my feedback.
                    </label>

                </div>



                <!-- Submit -->
                <button
                    type="submit"
                    class="feedback-submit-btn"
                >

                    Send Feedback

                    <i class="fa-solid fa-paper-plane"></i>

                </button>

            </form>

        </div>

    </div>

</section>



<!-- =========================
     FEEDBACK MESSAGE
========================= -->

<section class="feedback-bottom-cta">

    <div class="hp-container">

        <div class="feedback-cta-box">

            <div class="feedback-cta-icon">
                <i class="fa-solid fa-heart"></i>
            </div>

            <div>

                <h2>
                    Thank You for Supporting HelpingPlate
                </h2>

                <p>
                    Every contribution, suggestion and helping hand
                    can support our goal of making surplus food more useful.
                </p>

            </div>

        </div>

    </div>

</section>



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
                    Serving communities through food sharing
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

</body>
</html>
<?php require_once __DIR__.'/includes/bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>HelpingPlate | Good Food • Greater Impact</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap"
          rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="css/branding.css">
</head>


<body>
<?php if ($flash = get_flash()): ?><div class="hp-flash <?= e($flash['type']) ?>" role="status"><?= e($flash['message']) ?></div><?php endif; ?>


<!-- =====================================================
     NAVBAR
===================================================== -->

<header class="hp-navbar">

    <div class="hp-container hp-nav-container">


        <!-- LOGO -->

        <a href="index.php" class="hp-logo">

            <img src="images/logo.png"
                 alt="HelpingPlate Logo">

            <div class="hp-logo-text">

                <span>
                    Helping<span>Plate</span>
                </span>

                <small>
                    Good Food • Greater Impact
                </small>

            </div>

        </a>


        <!-- NAVIGATION -->

        <nav id="hpNavLinks" class="hp-nav-links">

            <a href="index.php"
               class="active">
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

            <a href="get-started.php"
               class="hp-nav-btn">
                Get Started
            </a>

        </nav>


        <!-- MOBILE MENU -->

        <button type="button" id="menuBtn" aria-controls="hpNavLinks" aria-expanded="false" aria-label="Open menu" class="hp-menu-btn">

            <i class="fa-solid fa-bars"></i>

        </button>

    </div>

</header>



<!-- =====================================================
     HERO SECTION
===================================================== -->

<section class="home-hero">

    <div class="hp-container home-hero-container">


        <!-- LEFT CONTENT -->

        <div class="home-hero-content">


            <div class="home-badge">

                <i class="fa-solid fa-heart"></i>

                Share Food • Share Hope

            </div>


            <h1>

                Good Food Should

                <span>
                    Never Go to Waste
                </span>

            </h1>


            <p>

                HelpingPlate connects surplus food with people
                and communities who can benefit from it.
                Together, we can turn extra food into meaningful
                support.

            </p>


            <div class="home-hero-buttons">

                <a href="get-started.php"
                   class="home-primary-btn">

                    Get Started

                    <i class="fa-solid fa-arrow-right"></i>

                </a>


                <a href="how-it-works.php"
                   class="home-secondary-btn">

                    How It Works

                </a>

            </div>


            <div class="home-trust">

                <div class="home-trust-item">

                    <i class="fa-solid fa-circle-check"></i>

                    Easy to Participate

                </div>


                <div class="home-trust-item">

                    <i class="fa-solid fa-circle-check"></i>

                    Community Focused

                </div>


                <div class="home-trust-item">

                    <i class="fa-solid fa-circle-check"></i>

                    Responsible Sharing

                </div>

            </div>

        </div>



        <!-- RIGHT VISUAL -->

        <div class="home-hero-visual">


            <div class="home-visual-circle">


                <div class="home-food-icon food-icon-one">

                    <i class="fa-solid fa-carrot"></i>

                </div>


                <div class="home-food-icon food-icon-two">

                    <i class="fa-solid fa-apple-whole"></i>

                </div>


                <div class="home-food-icon food-icon-three">

                    <i class="fa-solid fa-bowl-food"></i>

                </div>


                <div class="home-main-icon">

                    <i class="fa-solid fa-hand-holding-heart"></i>

                </div>


                <div class="home-leaf leaf-one">

                    <i class="fa-solid fa-leaf"></i>

                </div>


                <div class="home-leaf leaf-two">

                    <i class="fa-solid fa-leaf"></i>

                </div>


                <div class="home-heart">

                    <i class="fa-solid fa-heart"></i>

                </div>

            </div>


            <div class="home-floating-card">

                <div class="home-floating-icon">

                    <i class="fa-solid fa-utensils"></i>

                </div>

                <div>

                    <strong>
                        Food With Purpose
                    </strong>

                    <small>
                        Every contribution matters
                    </small>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     INTRODUCTION
===================================================== -->

<section class="home-intro">

    <div class="hp-container">

        <div class="home-intro-content">


            <div class="home-section-label">

                <i class="fa-solid fa-leaf"></i>

                About HelpingPlate

            </div>


            <h2>

                Turning surplus food into

                <span>
                    meaningful impact.
                </span>

            </h2>


            <p>

                HelpingPlate is a food redistribution platform
                designed to make it easier for surplus food to
                reach people and communities who can benefit
                from it.

            </p>


            <a href="about.php"
               class="home-text-link">

                Learn More

                <i class="fa-solid fa-arrow-right"></i>

            </a>

        </div>

    </div>

</section>



<!-- =====================================================
     HOW IT WORKS
===================================================== -->

<section class="home-how">

    <div class="hp-container">


        <div class="home-section-heading">

            <div>

                <span>
                    Simple Process
                </span>

                <h2>
                    How HelpingPlate Works
                </h2>

            </div>


            <p>

                A simple way for donors, requesters and
                volunteers to work together.

            </p>

        </div>


        <div class="home-process-grid">


            <!-- STEP 1 -->

            <div class="home-process-card">

                <div class="home-process-number">
                    01
                </div>

                <div class="home-process-icon">

                    <i class="fa-solid fa-bowl-food"></i>

                </div>

                <h3>
                    Donate Food
                </h3>

                <p>

                    Donors can share information about
                    surplus food that is available for
                    redistribution.

                </p>

            </div>


            <!-- STEP 2 -->

            <div class="home-process-card">

                <div class="home-process-number">
                    02
                </div>

                <div class="home-process-icon orange">

                    <i class="fa-solid fa-hand-holding-heart"></i>

                </div>

                <h3>
                    Request Food
                </h3>

                <p>

                    People or organizations can submit
                    requests for food according to their
                    requirements.

                </p>

            </div>


            <!-- STEP 3 -->

            <div class="home-process-card">

                <div class="home-process-number">
                    03
                </div>

                <div class="home-process-icon yellow">

                    <i class="fa-solid fa-person-walking"></i>

                </div>

                <h3>
                    Volunteer
                </h3>

                <p>

                    Volunteers can help coordinate pickups
                    and deliveries between participants.

                </p>

            </div>


            <!-- STEP 4 -->

            <div class="home-process-card">

                <div class="home-process-number">
                    04
                </div>

                <div class="home-process-icon purple">

                    <i class="fa-solid fa-people-group"></i>

                </div>

                <h3>
                    Create Impact
                </h3>

                <p>

                    Food reaches its destination while
                    the community works together to reduce
                    unnecessary waste.

                </p>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     FEATURES
===================================================== -->

<section class="home-features">

    <div class="hp-container">


        <div class="home-section-heading centered">

            <span>
                What You Can Do
            </span>

            <h2>
                One Platform, Multiple Ways to Help
            </h2>

            <p>

                HelpingPlate brings different participants
                together in one simple platform.

            </p>

        </div>


        <div class="home-feature-grid">


            <!-- DONOR -->

            <div class="home-feature-card">

                <div class="home-feature-icon green">

                    <i class="fa-solid fa-box-open"></i>

                </div>

                <h3>
                    Donate Surplus Food
                </h3>

                <p>

                    Share available surplus food and provide
                    useful information so that it can be
                    coordinated for redistribution.

                </p>

                <a href="get-started.php">
                    Start Donating
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

            </div>


            <!-- REQUESTER -->

            <div class="home-feature-card">

                <div class="home-feature-icon orange">

                    <i class="fa-solid fa-hand-holding-heart"></i>

                </div>

                <h3>
                    Request Food
                </h3>

                <p>

                    Submit a food request and provide the
                    details needed to coordinate available
                    support.

                </p>

                <a href="get-started.php">
                    Request Food
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

            </div>


            <!-- VOLUNTEER -->

            <div class="home-feature-card">

                <div class="home-feature-icon yellow">

                    <i class="fa-solid fa-person-circle-check"></i>

                </div>

                <h3>
                    Volunteer Your Time
                </h3>

                <p>

                    Help with food pickups and deliveries
                    while supporting the redistribution
                    process.

                </p>

                <a href="volunteer.php">
                    Become a Volunteer
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     WHY HELPINGPLATE
===================================================== -->

<section class="home-why">

    <div class="hp-container home-why-container">


        <div class="home-why-visual">

            <div class="home-why-circle">

                <i class="fa-solid fa-seedling"></i>

            </div>


            <div class="why-small-card why-card-one">

                <i class="fa-solid fa-utensils"></i>

                <span>
                    Share Food
                </span>

            </div>


            <div class="why-small-card why-card-two">

                <i class="fa-solid fa-users"></i>

                <span>
                    Support People
                </span>

            </div>

        </div>



        <div class="home-why-content">

            <span>
                Why HelpingPlate?
            </span>


            <h2>

                Small actions can create

                <strong>
                    meaningful change.
                </strong>

            </h2>


            <p>

                HelpingPlate provides a simple digital space
                where people can participate in food
                redistribution according to their role.

            </p>


            <div class="home-check-list">

                <div>

                    <i class="fa-solid fa-circle-check"></i>

                    Connect surplus food with genuine requests

                </div>


                <div>

                    <i class="fa-solid fa-circle-check"></i>

                    Make volunteer coordination easier

                </div>


                <div>

                    <i class="fa-solid fa-circle-check"></i>

                    Keep donation and request information organized

                </div>


                <div>

                    <i class="fa-solid fa-circle-check"></i>

                    Encourage responsible community participation

                </div>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     VOLUNTEER CTA
===================================================== -->

<section class="home-volunteer">

    <div class="hp-container">

        <div class="home-volunteer-inner">


            <div>

                <span>
                    Give Your Time
                </span>

                <h2>
                    Help move food where it is needed.
                </h2>

                <p>

                    Volunteers play an important role in
                    coordinating food pickups and deliveries.

                </p>

            </div>


            <a href="volunteer.php"
               class="home-volunteer-btn">

                Become a Volunteer

                <i class="fa-solid fa-arrow-right"></i>

            </a>

        </div>

    </div>

</section>



<!-- =====================================================
     FEEDBACK CTA
===================================================== -->

<section class="home-feedback">

    <div class="hp-container home-feedback-inner">


        <div>

            <span>
                Your Voice Matters
            </span>

            <h2>
                Help us improve HelpingPlate.
            </h2>

            <p>

                Share your experience, suggestions or
                feedback about the platform.

            </p>

        </div>


        <a href="feedback.php"
           class="home-feedback-btn">

            Give Feedback

            <i class="fa-solid fa-arrow-right"></i>

        </a>

    </div>

</section>



<!-- =====================================================
     FOOTER
===================================================== -->

<footer class="hp-footer">

    <div class="hp-container">


        <div class="hp-footer-grid">


            <!-- BRAND -->

            <div class="hp-footer-brand">

                <img src="images/logo.png"
                     alt="HelpingPlate Logo">

                <p>

                    HelpingPlate connects surplus food
                    with people and communities who can
                    benefit from it.

                </p>


                <div class="hp-social-links">

                    <a href="#">
                        <i class="fa-brands fa-instagram"></i>
                    </a>

                    <a href="#">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>

                    <a href="#">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </a>

                </div>

            </div>



            <!-- EXPLORE -->

            <div class="hp-footer-column">

                <h3>
                    Explore
                </h3>

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

            </div>



            <!-- COMMUNITY -->

            <div class="hp-footer-column">

                <h3>
                    Community
                </h3>

                <a href="feedback.php">
                    Feedback
                </a>

                <a href="contact.php">
                    Contact
                </a>

                <a href="get-started.php">
                    Get Started
                </a>

            </div>



            <!-- ACCOUNT -->

            <div class="hp-footer-column">

                <h3>
                    Account
                </h3>

                <a href="login.php">
                    Login
                </a>

                <a href="register.php">
                    Register
                </a>

            </div>

        </div>



        <div class="hp-footer-bottom">

            <p>
                © 2026 HelpingPlate. All rights reserved.
            </p>

            <p>
                Good Food • Greater Impact
            </p>

        </div>

    </div>

</footer>



<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script src="js/script.js"></script>





</body>

</html>
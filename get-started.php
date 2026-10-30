<?php require_once __DIR__.'/includes/bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Get Started | HelpingPlate</title>

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

    <link rel="stylesheet" href="css/branding.css">
</head>

<body>
<?php if ($flash = get_flash()): ?><div class="hp-flash <?= e($flash['type']) ?>" role="status"><?= e($flash['message']) ?></div><?php endif; ?>


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

            <a href="contact.php">Contact</a>
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
     GET STARTED HERO
===================================================== -->

<section class="gs-hero">

    <div class="hp-container">

        <div class="gs-hero-content">


            <span class="gs-badge">

                <i class="fa-solid fa-hand-holding-heart"></i>

                Start Making a Difference

            </span>


            <h1>

                How Would You Like to
                <span>Help?</span>

            </h1>


            <p>

                Choose the role that matches how you want
                to participate in the HelpingPlate community.

                You can donate surplus food, request available
                food, or help with deliveries as a volunteer.

            </p>

        </div>

    </div>

</section>



<!-- =====================================================
     ROLE SELECTION
===================================================== -->

<section class="gs-roles-section">

    <div class="hp-container">


        <div class="gs-section-heading">

            <span>
                CHOOSE YOUR ROLE
            </span>

            <h2>
                One Platform, Different Ways to Help
            </h2>

            <p>
                Select an option below to continue.
                You can create an account and manage
                your activities from your personal dashboard.
            </p>

        </div>



        <!-- Role Cards -->

        <div class="gs-role-grid">


            <!-- =========================
                 DONOR
            ========================== -->

            <div class="gs-role-card gs-donor-card">

                <div class="gs-role-icon">

                    <i class="fa-solid fa-basket-shopping"></i>

                </div>


                <span class="gs-role-label">
                    FOR FOOD DONORS
                </span>


                <h3>
                    Donate Food
                </h3>


                <p>

                    Have extra food that is still safe
                    and suitable to share?

                    Register as a donor and provide
                    information about your available
                    surplus food.

                </p>


                <ul>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        Add food donation details
                    </li>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        Track your donations
                    </li>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        View donation status
                    </li>

                </ul>


                <a
                    href="register.php?role=donor"
                    class="gs-role-btn"
                >

                    Continue as Donor

                    <i class="fa-solid fa-arrow-right"></i>

                </a>

            </div>



            <!-- =========================
                 REQUESTER
            ========================== -->

            <div class="gs-role-card gs-requester-card">

                <div class="gs-role-icon">

                    <i class="fa-solid fa-utensils"></i>

                </div>


                <span class="gs-role-label">
                    FOR FOOD REQUESTERS
                </span>


                <h3>
                    Request Food
                </h3>


                <p>

                    Looking for available food for
                    your community or organization?

                    Register as a requester and submit
                    food requirements through the platform.

                </p>


                <ul>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        Submit food requests
                    </li>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        View request status
                    </li>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        Track available support
                    </li>

                </ul>


                <a
                    href="register.php?role=requester"
                    class="gs-role-btn"
                >

                    Continue as Requester

                    <i class="fa-solid fa-arrow-right"></i>

                </a>

            </div>



            <!-- =========================
                 VOLUNTEER
            ========================== -->

            <div class="gs-role-card gs-volunteer-card">

                <div class="gs-role-icon">

                    <i class="fa-solid fa-truck"></i>

                </div>


                <span class="gs-role-label">
                    FOR VOLUNTEERS
                </span>


                <h3>
                    Become a Volunteer
                </h3>


                <p>

                    Want to help move donated food
                    where it is needed?

                    Join HelpingPlate as a volunteer
                    and help coordinate food deliveries.

                </p>


                <ul>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        Apply as a volunteer
                    </li>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        View delivery opportunities
                    </li>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        Track your deliveries
                    </li>

                </ul>


                <a
                    href="register.php?role=volunteer"
                    class="gs-role-btn"
                >

                    Continue as Volunteer

                    <i class="fa-solid fa-arrow-right"></i>

                </a>

            </div>


        </div>

    </div>

</section>



<!-- =====================================================
     HOW IT WORKS
===================================================== -->

<section class="gs-process-section">

    <div class="hp-container">


        <div class="gs-section-heading">

            <span>
                WHAT HAPPENS NEXT?
            </span>

            <h2>
                Getting Started Is Simple
            </h2>

        </div>


        <div class="gs-process-grid">


            <div class="gs-process-item">

                <div class="gs-process-number">
                    01
                </div>

                <div>

                    <h3>
                        Choose a Role
                    </h3>

                    <p>
                        Select the way you want to
                        participate on HelpingPlate.
                    </p>

                </div>

            </div>



            <div class="gs-process-item">

                <div class="gs-process-number">
                    02
                </div>

                <div>

                    <h3>
                        Create an Account
                    </h3>

                    <p>
                        Enter your basic information
                        and create your HelpingPlate account.
                    </p>

                </div>

            </div>



            <div class="gs-process-item">

                <div class="gs-process-number">
                    03
                </div>

                <div>

                    <h3>
                        Access Your Dashboard
                    </h3>

                    <p>
                        Manage donations, requests,
                        applications or deliveries.
                    </p>

                </div>

            </div>



            <div class="gs-process-item">

                <div class="gs-process-number">
                    04
                </div>

                <div>

                    <h3>
                        Take Action
                    </h3>

                    <p>
                        Start contributing to the
                        food redistribution process.
                    </p>

                </div>

            </div>


        </div>

    </div>

</section>



<!-- =====================================================
     LOGIN CTA
===================================================== -->

<section class="gs-login-cta">

    <div class="hp-container">

        <div class="gs-login-box">


            <div>

                <span>
                    ALREADY HAVE AN ACCOUNT?
                </span>

                <h2>
                    Welcome Back to HelpingPlate
                </h2>

                <p>
                    Log in to continue managing your
                    activities and contributions.
                </p>

            </div>


            <a
                href="login.php"
                class="gs-login-btn"
            >

                Login to Your Account

                <i class="fa-solid fa-arrow-right"></i>

            </a>


        </div>

    </div>

</section>



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

</body>
</html>
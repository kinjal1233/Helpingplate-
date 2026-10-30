<?php require_once __DIR__.'/includes/bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>How It Works | HelpingPlate</title>

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

            <a href="index.php">
                Home
            </a>

            <a href="about.php">
                About
            </a>

            <a href="how-it-works.php"
               class="active">
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
     HERO
===================================================== -->

<section class="hiw-hero">

    <div class="hp-container hiw-hero-container">


        <div class="hiw-hero-content">

            <div class="hiw-badge">

                <i class="fa-solid fa-arrows-rotate"></i>

                SIMPLE FOOD REDISTRIBUTION

            </div>


            <h1>

                From Surplus Food

                <span>
                    To Greater Impact
                </span>

            </h1>


            <p>

                HelpingPlate brings donors, requesters and
                volunteers together through a simple process
                that helps move suitable surplus food where
                it can be useful.

            </p>


            <div class="hiw-hero-buttons">

                <a href="get-started.php"
                   class="hiw-primary-btn">

                    Get Started

                    <i class="fa-solid fa-arrow-right"></i>

                </a>


                <a href="volunteer.php"
                   class="hiw-secondary-btn">

                    Become a Volunteer

                </a>

            </div>

        </div>


        <!-- HERO VISUAL -->

        <div class="hiw-hero-visual">

            <div class="hiw-main-circle">

                <i class="fa-solid fa-hand-holding-heart"></i>

            </div>


            <div class="hiw-visual-card hiw-card-one">

                <i class="fa-solid fa-box-open"></i>

                <div>

                    <strong>
                        Food Shared
                    </strong>

                    <small>
                        Donation created
                    </small>

                </div>

            </div>


            <div class="hiw-visual-card hiw-card-two">

                <i class="fa-solid fa-truck"></i>

                <div>

                    <strong>
                        Food Moved
                    </strong>

                    <small>
                        Delivery coordinated
                    </small>

                </div>

            </div>


            <div class="hiw-visual-card hiw-card-three">

                <i class="fa-solid fa-heart"></i>

                <div>

                    <strong>
                        Community Supported
                    </strong>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     MAIN PROCESS
===================================================== -->

<section class="hiw-process">

    <div class="hp-container">


        <div class="hiw-heading">

            <span>
                HOW HELPINGPLATE WORKS
            </span>

            <h2>
                A simple process from sharing to delivery.
            </h2>

            <p>

                Each role has a clear part in the process.
                Together, these steps help keep food
                redistribution organized.

            </p>

        </div>


        <!-- PROCESS STEPS -->

        <div class="hiw-process-grid">


            <!-- STEP 1 -->

            <div class="hiw-step-card">

                <div class="hiw-step-number">
                    01
                </div>

                <div class="hiw-step-icon green">

                    <i class="fa-solid fa-box-open"></i>

                </div>

                <h3>
                    Donor Shares Food
                </h3>

                <p>

                    A donor provides information about
                    suitable surplus food, including its
                    quantity and availability.

                </p>

            </div>


            <!-- STEP 2 -->

            <div class="hiw-step-card">

                <div class="hiw-step-number">
                    02
                </div>

                <div class="hiw-step-icon orange">

                    <i class="fa-solid fa-hand-holding-heart"></i>

                </div>

                <h3>
                    Request Is Created
                </h3>

                <p>

                    A requester can submit a food request
                    based on their requirements and
                    available support.

                </p>

            </div>


            <!-- STEP 3 -->

            <div class="hiw-step-card">

                <div class="hiw-step-number">
                    03
                </div>

                <div class="hiw-step-icon yellow">

                    <i class="fa-solid fa-people-arrows"></i>

                </div>

                <h3>
                    Information Is Coordinated
                </h3>

                <p>

                    The platform helps organize donation,
                    request and delivery information between
                    participants.

                </p>

            </div>


            <!-- STEP 4 -->

            <div class="hiw-step-card">

                <div class="hiw-step-number">
                    04
                </div>

                <div class="hiw-step-icon purple">

                    <i class="fa-solid fa-truck-fast"></i>

                </div>

                <h3>
                    Volunteer Helps Deliver
                </h3>

                <p>

                    A volunteer can accept an available
                    delivery and help move the food to
                    its intended destination.

                </p>

            </div>


            <!-- STEP 5 -->

            <div class="hiw-step-card">

                <div class="hiw-step-number">
                    05
                </div>

                <div class="hiw-step-icon blue">

                    <i class="fa-solid fa-check"></i>

                </div>

                <h3>
                    Delivery Is Completed
                </h3>

                <p>

                    Once the food reaches its destination,
                    the delivery information can be updated
                    through the platform.

                </p>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     ROLE SECTION
===================================================== -->

<section class="hiw-roles">

    <div class="hp-container">


        <div class="hiw-heading">

            <span>
                PLATFORM ROLES
            </span>

            <h2>
                Everyone has a different way to contribute.
            </h2>

        </div>


        <div class="hiw-role-grid">


            <!-- DONOR -->

            <div class="hiw-role-card">

                <div class="hiw-role-top">

                    <div class="hiw-role-icon">

                        <i class="fa-solid fa-box"></i>

                    </div>

                    <span>
                        ROLE 01
                    </span>

                </div>


                <h3>
                    Donor
                </h3>


                <p>

                    Donors can share available surplus food
                    by providing important information about
                    the food and its availability.

                </p>


                <ul>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        Add food donation
                    </li>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        View donation information
                    </li>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        Track donation status
                    </li>

                </ul>

            </div>



            <!-- REQUESTER -->

            <div class="hiw-role-card">

                <div class="hiw-role-top">

                    <div class="hiw-role-icon orange">

                        <i class="fa-solid fa-hand-holding-heart"></i>

                    </div>

                    <span>
                        ROLE 02
                    </span>

                </div>


                <h3>
                    Requester
                </h3>


                <p>

                    Requesters can communicate their food
                    requirements through the platform and
                    follow the status of their requests.

                </p>


                <ul>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        Submit food request
                    </li>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        View request information
                    </li>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        Track request status
                    </li>

                </ul>

            </div>



            <!-- VOLUNTEER -->

            <div class="hiw-role-card">

                <div class="hiw-role-top">

                    <div class="hiw-role-icon yellow">

                        <i class="fa-solid fa-person-walking"></i>

                    </div>

                    <span>
                        ROLE 03
                    </span>

                </div>


                <h3>
                    Volunteer
                </h3>


                <p>

                    Volunteers can support the delivery
                    process by accepting available pickup
                    and delivery activities.

                </p>


                <ul>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        View available deliveries
                    </li>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        Accept delivery tasks
                    </li>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        Update delivery status
                    </li>

                </ul>

            </div>



            <!-- ADMIN -->

            <div class="hiw-role-card">

                <div class="hiw-role-top">

                    <div class="hiw-role-icon purple">

                        <i class="fa-solid fa-user-shield"></i>

                    </div>

                    <span>
                        ROLE 04
                    </span>

                </div>


                <h3>
                    Administrator
                </h3>


                <p>

                    Administrators manage platform information
                    and can monitor users, donations, requests,
                    deliveries and feedback.

                </p>


                <ul>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        Manage users
                    </li>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        Monitor donations and requests
                    </li>

                    <li>
                        <i class="fa-solid fa-check"></i>
                        Review platform activity
                    </li>

                </ul>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     DONATION FLOW
===================================================== -->

<section class="hiw-flow">

    <div class="hp-container">


        <div class="hiw-heading">

            <span>
                THE FOOD JOURNEY
            </span>

            <h2>
                One connected journey.
            </h2>

            <p>

                HelpingPlate organizes the main stages
                involved in moving surplus food from a
                donor toward its intended destination.

            </p>

        </div>


        <div class="hiw-flow-container">


            <!-- DONOR -->

            <div class="hiw-flow-item">

                <div class="hiw-flow-icon green">

                    <i class="fa-solid fa-box-open"></i>

                </div>

                <h3>
                    Donor
                </h3>

                <p>
                    Food is made available.
                </p>

            </div>


            <div class="hiw-flow-arrow">

                <i class="fa-solid fa-arrow-right"></i>

            </div>


            <!-- PLATFORM -->

            <div class="hiw-flow-item">

                <div class="hiw-flow-icon orange">

                    <i class="fa-solid fa-globe"></i>

                </div>

                <h3>
                    HelpingPlate
                </h3>

                <p>
                    Information is organized.
                </p>

            </div>


            <div class="hiw-flow-arrow">

                <i class="fa-solid fa-arrow-right"></i>

            </div>


            <!-- VOLUNTEER -->

            <div class="hiw-flow-item">

                <div class="hiw-flow-icon yellow">

                    <i class="fa-solid fa-truck"></i>

                </div>

                <h3>
                    Volunteer
                </h3>

                <p>
                    Delivery is supported.
                </p>

            </div>


            <div class="hiw-flow-arrow">

                <i class="fa-solid fa-arrow-right"></i>

            </div>


            <!-- REQUESTER -->

            <div class="hiw-flow-item">

                <div class="hiw-flow-icon purple">

                    <i class="fa-solid fa-heart"></i>

                </div>

                <h3>
                    Requester
                </h3>

                <p>
                    Food reaches its destination.
                </p>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     GET STARTED CTA
===================================================== -->

<section class="hiw-cta">

    <div class="hp-container hiw-cta-inner">


        <div>

            <span>
                READY TO PARTICIPATE?
            </span>

            <h2>
                Choose your role and get started.
            </h2>

            <p>

                Join HelpingPlate as a donor, requester
                or volunteer and become part of the
                food-sharing process.

            </p>

        </div>


        <div class="hiw-cta-buttons">

            <a href="get-started.php"
               class="hiw-cta-primary">

                Get Started

                <i class="fa-solid fa-arrow-right"></i>

            </a>


            <a href="volunteer.php"
               class="hiw-cta-secondary">

                Volunteer

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



<!-- JAVASCRIPT -->

<script src="js/script.js"></script>

</body>

</html>
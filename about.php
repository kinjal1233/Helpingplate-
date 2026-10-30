<?php require_once __DIR__.'/includes/bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>About Us | HelpingPlate</title>

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

            <a href="about.php"
               class="active">
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
     ABOUT HERO
===================================================== -->

<section class="about-hero">

    <div class="hp-container about-hero-container">


        <div class="about-hero-content">

            <div class="about-badge">

                <i class="fa-solid fa-heart"></i>

                ABOUT HELPINGPLATE

            </div>


            <h1>

                Food Can Create

                <span>
                    Greater Impact
                </span>

            </h1>


            <p>

                HelpingPlate is a simple food redistribution
                platform created to connect surplus food with
                people and communities who can benefit from it.

            </p>

        </div>


        <div class="about-hero-visual">

            <div class="about-circle">

                <i class="fa-solid fa-hand-holding-heart"></i>

            </div>


            <div class="about-floating about-floating-one">

                <i class="fa-solid fa-bowl-food"></i>

                <span>
                    Share Food
                </span>

            </div>


            <div class="about-floating about-floating-two">

                <i class="fa-solid fa-people-group"></i>

                <span>
                    Build Community
                </span>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     OUR PURPOSE
===================================================== -->

<section class="about-purpose">

    <div class="hp-container about-two-column">


        <!-- VISUAL -->

        <div class="about-purpose-visual">

            <div class="about-image-card">

                <div class="about-big-icon">

                    <i class="fa-solid fa-seedling"></i>

                </div>

                <span>
                    Good Food • Greater Impact
                </span>

            </div>


            <div class="about-small-card">

                <i class="fa-solid fa-heart"></i>

                <div>

                    <strong>
                        Every Contribution Matters
                    </strong>

                    <small>
                        Small actions can support communities.
                    </small>

                </div>

            </div>

        </div>


        <!-- CONTENT -->

        <div class="about-purpose-content">

            <span class="about-section-label">
                OUR PURPOSE
            </span>


            <h2>

                Making food sharing

                <span>
                    easier and more organized.
                </span>

            </h2>


            <p>

                Surplus food can often remain unused even
                when it could be useful to someone else.
                HelpingPlate provides a digital space where
                donors can share available food, requesters
                can communicate their needs, and volunteers
                can help coordinate the movement of food.

            </p>


            <p>

                The platform is designed around simple
                participation. Each person can use the role
                that matches the way they want to contribute,
                while the overall process remains organized
                through the platform.

            </p>


            <div class="about-highlight">

                <i class="fa-solid fa-circle-check"></i>

                <div>

                    <strong>
                        A connected community
                    </strong>

                    <p>
                        Donors, requesters and volunteers
                        can work together through one platform.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     OUR VISION
===================================================== -->

<section class="about-vision">

    <div class="hp-container">


        <div class="about-section-heading">

            <span>
                OUR VISION
            </span>

            <h2>
                A community where food can be shared with purpose.
            </h2>

            <p>

                HelpingPlate aims to encourage responsible
                food sharing by making participation simple,
                accessible and organized.

            </p>

        </div>


        <div class="about-vision-grid">


            <!-- CARD 1 -->

            <div class="about-vision-card">

                <div class="about-card-icon green">

                    <i class="fa-solid fa-recycle"></i>

                </div>

                <h3>
                    Reduce Unnecessary Waste
                </h3>

                <p>

                    Encourage the responsible redistribution
                    of suitable surplus food instead of leaving
                    it unused.

                </p>

            </div>


            <!-- CARD 2 -->

            <div class="about-vision-card">

                <div class="about-card-icon orange">

                    <i class="fa-solid fa-hand-holding-heart"></i>

                </div>

                <h3>
                    Encourage Sharing
                </h3>

                <p>

                    Create an easy way for people and
                    organizations to contribute available
                    food to the community.

                </p>

            </div>


            <!-- CARD 3 -->

            <div class="about-vision-card">

                <div class="about-card-icon yellow">

                    <i class="fa-solid fa-users"></i>

                </div>

                <h3>
                    Strengthen Participation
                </h3>

                <p>

                    Give donors, requesters and volunteers
                    clear ways to participate in the
                    redistribution process.

                </p>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     HOW PEOPLE PARTICIPATE
===================================================== -->

<section class="about-participation">

    <div class="hp-container">


        <div class="about-participation-heading">

            <span>
                WHO CAN PARTICIPATE?
            </span>

            <h2>
                Everyone can contribute in a different way.
            </h2>

        </div>


        <div class="about-role-grid">


            <!-- DONOR -->

            <div class="about-role-card">

                <div class="about-role-icon">

                    <i class="fa-solid fa-box-open"></i>

                </div>

                <div>

                    <h3>
                        Donors
                    </h3>

                    <p>

                        People, restaurants, organizations
                        or other contributors can share
                        suitable surplus food.

                    </p>

                </div>

            </div>


            <!-- REQUESTER -->

            <div class="about-role-card">

                <div class="about-role-icon orange">

                    <i class="fa-solid fa-hand-holding-heart"></i>

                </div>

                <div>

                    <h3>
                        Requesters
                    </h3>

                    <p>

                        Individuals or organizations can
                        submit requests for available food
                        according to their needs.

                    </p>

                </div>

            </div>


            <!-- VOLUNTEER -->

            <div class="about-role-card">

                <div class="about-role-icon yellow">

                    <i class="fa-solid fa-person-walking"></i>

                </div>

                <div>

                    <h3>
                        Volunteers
                    </h3>

                    <p>

                        Volunteers can help with pickups,
                        deliveries and coordination between
                        participants.

                    </p>

                </div>

            </div>


            <!-- ADMIN -->

            <div class="about-role-card">

                <div class="about-role-icon purple">

                    <i class="fa-solid fa-user-shield"></i>

                </div>

                <div>

                    <h3>
                        Administrators
                    </h3>

                    <p>

                        Administrators can manage platform
                        information, users, donations,
                        requests and deliveries.

                    </p>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     OUR APPROACH
===================================================== -->

<section class="about-approach">

    <div class="hp-container about-two-column">


        <div class="about-approach-content">

            <span class="about-section-label">
                OUR APPROACH
            </span>


            <h2>

                Simple technology,

                <span>
                    meaningful participation.
                </span>

            </h2>


            <p>

                HelpingPlate focuses on making the
                redistribution process easier to understand
                and easier to participate in.

            </p>


            <div class="about-approach-list">

                <div>

                    <div class="about-list-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>

                    <div>

                        <strong>
                            Clear role-based participation
                        </strong>

                        <p>
                            Each participant can access
                            features according to their role.
                        </p>

                    </div>

                </div>


                <div>

                    <div class="about-list-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>

                    <div>

                        <strong>
                            Organized information
                        </strong>

                        <p>
                            Donation, request and delivery
                            information can be managed in
                            one platform.
                        </p>

                    </div>

                </div>


                <div>

                    <div class="about-list-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>

                    <div>

                        <strong>
                            Community involvement
                        </strong>

                        <p>
                            The platform provides different
                            ways for people to contribute.
                        </p>

                    </div>

                </div>

            </div>

        </div>



        <div class="about-approach-visual">

            <div class="approach-main-card">

                <i class="fa-solid fa-people-arrows"></i>

                <h3>
                    Connect
                </h3>

                <p>
                    Bring participants together.
                </p>

            </div>


            <div class="approach-mini-card mini-one">

                <i class="fa-solid fa-box"></i>

                Food

            </div>


            <div class="approach-mini-card mini-two">

                <i class="fa-solid fa-truck"></i>

                Delivery

            </div>


            <div class="approach-mini-card mini-three">

                <i class="fa-solid fa-heart"></i>

                Impact

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     CTA
===================================================== -->

<section class="about-cta">

    <div class="hp-container about-cta-inner">


        <div>

            <span>
                JOIN HELPINGPLATE
            </span>

            <h2>
                Be part of a food-sharing community.
            </h2>

            <p>

                Whether you want to donate food, request
                support or volunteer your time, you can
                get started through HelpingPlate.

            </p>

        </div>


        <div class="about-cta-buttons">

            <a href="get-started.php"
               class="about-cta-primary">

                Get Started

                <i class="fa-solid fa-arrow-right"></i>

            </a>


            <a href="volunteer.php"
               class="about-cta-secondary">

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
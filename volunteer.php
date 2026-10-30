<?php
require_once __DIR__.'/includes/bootstrap.php';
if($_SERVER['REQUEST_METHOD']==='POST'){verify_csrf();$name=post_string('name',100);$email=filter_var(trim((string)($_POST['email']??'')),FILTER_VALIDATE_EMAIL);$phone=post_string('phone',25);$area=post_string('area',150);$availability=post_string('availability',150);$experience=post_string('experience',2000);$msg=post_string('message',1000);if(mb_strlen($name)<2||!$email||$phone===''||$area===''||$availability===''){flash('Please complete all required volunteer application fields.','error');redirect('volunteer.php#volunteer-form');}db()->prepare("INSERT INTO volunteer_applications(user_id,name,email,phone,preferred_area,availability,experience,status) VALUES(NULL,?,?,?,?,?,?,'pending')")->execute([$name,$email,$phone,$area,$availability,$experience.($msg?"\n".$msg:'')]);flash('Your volunteer interest has been submitted. Create a volunteer account to access the delivery portal.');redirect('volunteer.php');}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Volunteer | HelpingPlate</title>

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

            <a href="how-it-works.php">
                How It Works
            </a>

            <a href="volunteer.php"
               class="active">
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
     VOLUNTEER HERO
===================================================== -->

<section class="vol-hero">

    <div class="hp-container vol-hero-container">


        <div class="vol-hero-content">

            <div class="vol-badge">

                <i class="fa-solid fa-heart"></i>

                MAKE A DIFFERENCE

            </div>


            <h1>

                Become a

                <span>
                    HelpingPlate Volunteer
                </span>

            </h1>


            <p>

                Give your time to help move suitable
                surplus food from donors to places where
                it can be useful.

            </p>


            <a href="#volunteer-form"
               class="vol-primary-btn">

                Apply as a Volunteer

                <i class="fa-solid fa-arrow-down"></i>

            </a>

        </div>


        <!-- HERO VISUAL -->

        <div class="vol-hero-visual">

            <div class="vol-main-circle">

                <i class="fa-solid fa-person-walking"></i>

            </div>


            <div class="vol-floating-card vol-float-one">

                <i class="fa-solid fa-box-open"></i>

                <span>
                    Pick Up Food
                </span>

            </div>


            <div class="vol-floating-card vol-float-two">

                <i class="fa-solid fa-truck"></i>

                <span>
                    Help Deliver
                </span>

            </div>


            <div class="vol-floating-card vol-float-three">

                <i class="fa-solid fa-heart"></i>

                <span>
                    Support Community
                </span>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     WHY VOLUNTEER
===================================================== -->

<section class="vol-why">

    <div class="hp-container">


        <div class="vol-section-heading">

            <span>
                WHY VOLUNTEER?
            </span>

            <h2>
                Your time can help connect food with people.
            </h2>

            <p>

                Volunteers play an important part in the
                HelpingPlate delivery process by helping
                coordinate the movement of donated food.

            </p>

        </div>


        <div class="vol-benefit-grid">


            <div class="vol-benefit-card">

                <div class="vol-benefit-icon green">

                    <i class="fa-solid fa-hand-holding-heart"></i>

                </div>

                <h3>
                    Help Your Community
                </h3>

                <p>

                    Contribute your time to a community-focused
                    food redistribution process.

                </p>

            </div>


            <div class="vol-benefit-card">

                <div class="vol-benefit-icon orange">

                    <i class="fa-solid fa-truck"></i>

                </div>

                <h3>
                    Support Deliveries
                </h3>

                <p>

                    Help move available food between
                    participants when a delivery is needed.

                </p>

            </div>


            <div class="vol-benefit-card">

                <div class="vol-benefit-icon yellow">

                    <i class="fa-solid fa-users"></i>

                </div>

                <h3>
                    Work With Others
                </h3>

                <p>

                    Coordinate with donors, requesters and
                    other volunteers through the platform.

                </p>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     VOLUNTEER JOURNEY
===================================================== -->

<section class="vol-journey">

    <div class="hp-container">


        <div class="vol-section-heading">

            <span>
                VOLUNTEER JOURNEY
            </span>

            <h2>
                From registration to delivery.
            </h2>

        </div>


        <div class="vol-steps">


            <div class="vol-step">

                <div class="vol-step-number">
                    01
                </div>

                <div class="vol-step-icon">

                    <i class="fa-solid fa-user-plus"></i>

                </div>

                <h3>
                    Register
                </h3>

                <p>
                    Create your volunteer profile.
                </p>

            </div>


            <div class="vol-step-line"></div>


            <div class="vol-step">

                <div class="vol-step-number">
                    02
                </div>

                <div class="vol-step-icon orange">

                    <i class="fa-solid fa-list"></i>

                </div>

                <h3>
                    View Requests
                </h3>

                <p>
                    Check available delivery activities.
                </p>

            </div>


            <div class="vol-step-line"></div>


            <div class="vol-step">

                <div class="vol-step-number">
                    03
                </div>

                <div class="vol-step-icon yellow">

                    <i class="fa-solid fa-hand-pointer"></i>

                </div>

                <h3>
                    Accept a Task
                </h3>

                <p>
                    Choose an available delivery.
                </p>

            </div>


            <div class="vol-step-line"></div>


            <div class="vol-step">

                <div class="vol-step-number">
                    04
                </div>

                <div class="vol-step-icon purple">

                    <i class="fa-solid fa-box"></i>

                </div>

                <h3>
                    Collect Food
                </h3>

                <p>
                    Pick up the available donation.
                </p>

            </div>


            <div class="vol-step-line"></div>


            <div class="vol-step">

                <div class="vol-step-number">
                    05
                </div>

                <div class="vol-step-icon blue">

                    <i class="fa-solid fa-location-dot"></i>

                </div>

                <h3>
                    Deliver
                </h3>

                <p>
                    Help complete the food delivery.
                </p>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     VOLUNTEER FORM
===================================================== -->

<section class="vol-form-section"
         id="volunteer-form">

    <div class="hp-container">


        <div class="vol-form-heading">

            <span>
                VOLUNTEER APPLICATION
            </span>

            <h2>
                Ready to help?
            </h2>

            <p>

                Fill in the important details below.
                Our platform can use this information
                to create your volunteer application.

            </p>

        </div>


        <div class="vol-form-wrapper">


            <form id="volunteerForm" method="post" action="volunteer.php">
                <?= csrf_field() ?>


                <!-- NAME -->

                <div class="vol-form-group">

                    <label for="volName">
                        Full Name
                        <span>*</span>
                    </label>

                    <input type="text"
                           id="volName"
                           name="name"
                           placeholder="Enter your full name"
                           required>

                </div>


                <!-- EMAIL -->

                <div class="vol-form-group">

                    <label for="volEmail">
                        Email Address
                        <span>*</span>
                    </label>

                    <input type="email"
                           id="volEmail"
                           name="email"
                           placeholder="Enter your email address"
                           required>

                </div>


                <!-- PHONE -->

                <div class="vol-form-group">

                    <label for="volPhone">
                        Phone Number
                        <span>*</span>
                    </label>

                    <input type="tel"
                           id="volPhone"
                           name="phone"
                           placeholder="Enter your phone number"
                           required>

                </div>


                <!-- AREA -->

                <div class="vol-form-group">

                    <label for="volArea">
                        Preferred Area
                    </label>

                    <input type="text"
                           id="volArea"
                           name="area"
                           placeholder="City or preferred area">

                </div>


                <!-- AVAILABILITY -->

                <div class="vol-form-group">

                    <label for="volAvailability">
                        Availability
                    </label>

                    <select id="volAvailability"
                            name="availability">

                        <option value="">
                            Select availability
                        </option>

                        <option value="morning">
                            Morning
                        </option>

                        <option value="afternoon">
                            Afternoon
                        </option>

                        <option value="evening">
                            Evening
                        </option>

                        <option value="flexible">
                            Flexible
                        </option>

                    </select>

                </div>


                <!-- EXPERIENCE -->

                <div class="vol-form-group">

                    <label for="volExperience">
                        Previous Volunteer Experience
                    </label>

                    <select id="volExperience"
                            name="experience">

                        <option value="">
                            Select an option
                        </option>

                        <option value="yes">
                            Yes
                        </option>

                        <option value="no">
                            No
                        </option>

                    </select>

                </div>


                <!-- MESSAGE -->

                <div class="vol-form-group vol-full">

                    <label for="volMessage">
                        Why would you like to volunteer?
                    </label>

                    <textarea id="volMessage"
                              name="message"
                              rows="5"
                              placeholder="Tell us a little about your interest..."></textarea>

                </div>


                <!-- AGREEMENT -->

                <div class="vol-agreement vol-full">

                    <label>

                        <input type="checkbox"
                               id="volAgreement"
                               required>

                        <span>
                            I agree to provide genuine information
                            and follow the HelpingPlate volunteer
                            guidelines.
                        </span>

                    </label>

                </div>


                <!-- SUBMIT -->

                <div class="vol-submit vol-full">

                    <button type="submit">

                        Submit Application

                        <i class="fa-solid fa-arrow-right"></i>

                    </button>

                </div>


            </form>

        </div>

    </div>

</section>



<!-- =====================================================
     VOLUNTEER GUIDELINES
===================================================== -->

<section class="vol-guidelines">

    <div class="hp-container">


        <div class="vol-section-heading">

            <span>
                VOLUNTEER GUIDELINES
            </span>

            <h2>
                A few things to keep in mind.
            </h2>

        </div>


        <div class="vol-guideline-grid">


            <div class="vol-guideline">

                <i class="fa-solid fa-clock"></i>

                <div>

                    <h3>
                        Be Reliable
                    </h3>

                    <p>
                        Accept delivery activities that
                        fit your available time.
                    </p>

                </div>

            </div>


            <div class="vol-guideline">

                <i class="fa-solid fa-box"></i>

                <div>

                    <h3>
                        Handle Food Carefully
                    </h3>

                    <p>
                        Treat donated food responsibly
                        throughout the delivery process.
                    </p>

                </div>

            </div>


            <div class="vol-guideline">

                <i class="fa-solid fa-phone"></i>

                <div>

                    <h3>
                        Communicate Clearly
                    </h3>

                    <p>
                        Keep relevant participants informed
                        about delivery progress.
                    </p>

                </div>

            </div>


            <div class="vol-guideline">

                <i class="fa-solid fa-shield-heart"></i>

                <div>

                    <h3>
                        Respect Everyone
                    </h3>

                    <p>
                        Maintain respectful communication
                        with everyone involved.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     CTA
===================================================== -->

<section class="vol-cta">

    <div class="hp-container vol-cta-inner">


        <div>

            <span>
                EVERY ACTION COUNTS
            </span>

            <h2>
                Your time can help food reach further.
            </h2>

            <p>
                Start your volunteer journey with HelpingPlate.
            </p>

        </div>


        <a href="#volunteer-form"
           class="vol-cta-button">

            Apply Now

            <i class="fa-solid fa-arrow-up"></i>

        </a>

    </div>

</section>



<!-- =====================================================
     FOOTER
===================================================== -->

<footer class="hp-footer">

    <div class="hp-container">


        <div class="hp-footer-grid">


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



<script src="js/script.js"></script>

</body>

</html>
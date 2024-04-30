<!doctype html>
<html class="no-js" lang="en">

<?php
    header("Location: user/login.php");
    //header("Location: under-maintenance.php");

    // echo "We Will Be Live Soon...";
    // exit;
    
?>

<head>
    <title>Maxizone</title>
    <?php require_once("head.php"); ?>
</head>

<?php
    $query = "SELECT * FROM `services` WHERE `is_active`=1 AND `delete_date` IS NULL ORDER BY `create_date` DESC";
    $res = mysqli_query($conn,$query);
    $services = mysqli_fetch_all($res,MYSQLI_ASSOC);
    $count_service = count($services);

    if (isset($submit_booking)) {
        $id_service = mysqli_escape_string($conn, $_POST['id_service']);
        $name = mysqli_escape_string($conn, $_POST['name']);
        $mobile = mysqli_escape_string($conn, $_POST['mobile']);
        $email = mysqli_escape_string($conn, $_POST['email']);
        $message = mysqli_escape_string($conn, $_POST['message']);
        $query_insert = "INSERT INTO `leads`(`service_id`, `source`, `category`, `name`, `mobile`, `email`, `message`, `create_date`) VALUES ('$id_service', 'service', 'request', '$name', '$mobile', '$email', '$message', '$current_date')";
        
        if(mysqli_query($conn, $query_insert)) {
            $msg = "Your Service Request has been placed Successfully! Our concerned Team Member will contact you soon.";
            $msg_type = "success";
            stop_form_resubmit();
        }
    }
?>

<body>
    
    
    <!--====== HEADER PART START ======-->
        <header class="header-area">
            <?php require_once("navbar.php"); ?>

            <div id="home" class="header-hero bg_cover" style="background-image: url(assets/images/banner-bg.svg)">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="header-hero-content text-center">
                                <h3 class="header-sub-title wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.2s">
                                    Maxizone - Your Service Partner
                                </h3>
                                <h2 class="header-title wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.5s">
                                    Kickstart Your Growth With Our Team
                                </h2>
                                <p class="text wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.8s">
                                    Join Now and Avail Best Ever Benefits
                                </p>
                                <a href="user/login.php" class="main-btn wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="1.1s">
                                    Get Started
                                </a>
                            </div>
                        </div>
                    </div> <!-- row -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="header-hero-image text-center wow fadeIn" data-wow-duration="1.3s"
                                data-wow-delay="1.4s">
                                <img src="assets/images/about2.svg" alt="hero">
                            </div>
                        </div>
                    </div> <!-- row -->
                </div> <!-- container -->
                <div id="particles-1" class="particles"></div>
            </div>
        </header>
    <!--====== HEADER PART ENDS ======-->

    <!--====== BRAND PART START ======-->
        <div class="brand-area pt-90 d-none">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="brand-logo d-flex align-items-center justify-content-center justify-content-md-between">
                            <div class="single-logo mt-30 wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s">
                                <img src="assets/images/brand-1.png" alt="brand">
                            </div> <!-- single logo -->
                            <div class="single-logo mt-30 wow fadeIn" data-wow-duration="1.5s" data-wow-delay="0.2s">
                                <img src="assets/images/brand-2.png" alt="brand">
                            </div> <!-- single logo -->
                            <div class="single-logo mt-30 wow fadeIn" data-wow-duration="1.5s" data-wow-delay="0.3s">
                                <img src="assets/images/brand-3.png" alt="brand">
                            </div> <!-- single logo -->
                            <div class="single-logo mt-30 wow fadeIn" data-wow-duration="1.5s" data-wow-delay="0.4s">
                                <img src="assets/images/brand-4.png" alt="brand">
                            </div> <!-- single logo -->
                            <div class="single-logo mt-30 wow fadeIn" data-wow-duration="1.5s" data-wow-delay="0.5s">
                                <img src="assets/images/brand-5.png" alt="brand">
                            </div> <!-- single logo -->
                        </div> <!-- brand logo -->
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
        </div>
    <!--====== BRAND PART ENDS ======-->

    <!--====== SERVICES PART START ======-->
        <section id="features" class="services-area pt-120">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="section-title text-center pb-40">
                            <div class="line m-auto"></div>
                            <h3 class="title">
                                Simple yet Powerful<span> The Main Idea behind our services</span>
                            </h3>
                        </div> <!-- section title -->
                    </div>
                </div> <!-- row -->
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-7 col-sm-8">
                        <div class="single-services text-center mt-30 wow fadeIn" data-wow-duration="1s"
                            data-wow-delay="0.2s">
                            <div class="services-icon">
                                <img class="shape" src="assets/images/services-shape.svg" alt="shape">
                                <img class="shape-1" src="assets/images/services-shape-1.svg" alt="shape">
                                <i class="lni-baloon"></i>
                            </div>
                            <div class="services-content mt-30">
                                <h4 class="services-title"><a href="#">Clean</a></h4>
                                <!-- <p class="text"></p>
                                <a class="more" href="#">Learn More <i class="lni-chevron-right"></i></a> -->
                            </div>
                        </div> <!-- single services -->
                    </div>
                    <div class="col-lg-4 col-md-7 col-sm-8">
                        <div class="single-services text-center mt-30 wow fadeIn" data-wow-duration="1s"
                            data-wow-delay="0.5s">
                            <div class="services-icon">
                                <img class="shape" src="assets/images/services-shape.svg" alt="shape">
                                <img class="shape-1" src="assets/images/services-shape-2.svg" alt="shape">
                                <i class="lni-cog"></i>
                            </div>
                            <div class="services-content mt-30">
                                <h4 class="services-title"><a href="#">Robust</a></h4>
                                <!-- <p class="text"></p>
                                <a class="more" href="#">Learn More <i class="lni-chevron-right"></i></a> -->
                            </div>
                        </div> <!-- single services -->
                    </div>
                    <div class="col-lg-4 col-md-7 col-sm-8">
                        <div class="single-services text-center mt-30 wow fadeIn" data-wow-duration="1s"
                            data-wow-delay="0.8s">
                            <div class="services-icon">
                                <img class="shape" src="assets/images/services-shape.svg" alt="shape">
                                <img class="shape-1" src="assets/images/services-shape-3.svg" alt="shape">
                                <i class="lni-bolt-alt"></i>
                            </div>
                            <div class="services-content mt-30">
                                <h4 class="services-title"><a href="#">Powerful</a></h4>
                                <!-- <p class="text"></p>
                                <a class="more" href="#">Learn More <i class="lni-chevron-right"></i></a> -->
                            </div>
                        </div> <!-- single services -->
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
        </section>
    <!--====== SERVICES PART ENDS ======-->

    <!--====== BLOG PART START ======-->
        <section id="services" class="blog-area pt-120">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="section-title pb-35">
                            <div class="line"></div>
                            <h3 class="title"><span>Our</span> Services</h3>
                        </div> <!-- section title -->
                    </div>
                </div> <!-- row -->
                <div class="row justify-content-center">
                    <?php
                        if ($count_service > 0) {
                            foreach ($services as $item) {
                                $id_service = $item['id'];
                                $title_service = $item['title'];
                                $description_service = $item['description'];
                                $price_start = $item['price_start'];
                                $url_file = $item['url_file'];
                                $url_file = "assets/files/service/$url_file";
                                ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="single-blog mt-30 wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s">
                                            <div class="blog-image">
                                                <img src="<?php echo $url_file; ?>" alt="service" class="shadow" style="width: 100%; height:12rem !important">
                                            </div>
                                            <div class="blog-content">
                                                <h3 class="mb-2"><?php echo $title_service; ?></h3>
                                                <ul class="meta justify-content-between">
                                                    <li>Prices start From: <a>Rs.<?php echo $price_start; ?></a></li>
                                                    <!-- <li>03 June, 2023</li> -->
                                                </ul>
                                                <p class="text">
                                                    <?php echo $description_service; ?>
                                                </p>
                                                <a class="more show_pointer" data-toggle="modal" data-target="#ModalBook" style="margin: 5px;" id="btn-<?php echo $id_service; ?>" data-id="<?php echo $id_service; ?>" data-title="<?php echo $title_service; ?>">Book Your Service Now <i class="lni-chevron-right"></i></a>
                                            </div>
                                        </div> <!-- single blog -->
                                    </div>
                                <?php
                            }
                        } else {
                            echo "Services will be Updated Soon!";
                        }
                    ?>                    
                </div> <!-- row -->
            </div> <!-- container -->
        </section>
    <!--====== BLOG PART ENDS ======-->

    <!--====== ABOUT PART START ======-->
        <section id="about" class="about-area pt-70">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="about-content mt-50 wow fadeInLeftBig" data-wow-duration="1s" data-wow-delay="0.5s">
                            <div class="section-title">
                                <div class="line"></div>
                                <h3 class="title">About <span>MT Trade Club</span></h3>
                            </div> <!-- section title -->
                            <p class="text">
                                We are focused on our members benefits in every aspect. We treat you as our bonding team which gets benefits from our platform.
                            </p>
                            <p>
                                Members can get any sort of services booked on our portal. They can also aquire a regenerating income from our various investment plans, if they are intended to do so.
                            </p>
                            <!-- <a href="#" class="main-btn">Try it Free</a> -->
                        </div> <!-- about content -->
                    </div>
                    <div class="col-lg-6">
                        <div class="about-image text-center mt-50 wow fadeInRightBig" data-wow-duration="1s"
                            data-wow-delay="0.5s">
                            <img src="assets/images/about1.svg" alt="about">
                        </div> <!-- about image -->
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
            <div class="about-shape-1">
                <img src="assets/images/about-shape-1.svg" alt="shape">
            </div>
        </section>
    <!--====== ABOUT PART ENDS ======-->

    <!--====== ABOUT PART START ======-->
        <section class="about-area pt-70">
            <div class="about-shape-2">
                <img src="assets/images/about-shape-2.svg" alt="shape">
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 order-lg-last">
                        <div class="about-content mt-50 wow fadeInLeftBig" data-wow-duration="1s" data-wow-delay="0.5s">
                            <div class="section-title">
                                <div class="line"></div>
                                <h3 class="title">Ease and Accessibility <span> with Essential Features</span></h3>
                            </div> <!-- section title -->
                            <p class="text">
                                This platform is designed keeping a thought in mind that all the services we are offering should reach our members at its ease without any hassle in the process.
                            </p>
                            <p>
                                In case if you feel un-easy in aquiring so, you can directly contact us with our <a href="#contact">CONTACT US</a> section.
                            </p>
                            <a href="#contact" class="main-btn">Get In Touch</a>
                        </div> <!-- about content -->
                    </div>
                    <div class="col-lg-6 order-lg-first">
                        <div class="about-image text-center mt-50 wow fadeInRightBig" data-wow-duration="1s"
                            data-wow-delay="0.5s">
                            <img src="assets/images/about2.svg" alt="about">
                        </div> <!-- about image -->
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
        </section>
    <!--====== ABOUT PART ENDS ======-->

    <!--====== ABOUT PART START ======-->
        <section class="about-area pt-70 d-none">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="about-content mt-50 wow fadeInLeftBig" data-wow-duration="1s" data-wow-delay="0.5s">
                            <div class="section-title">
                                <div class="line"></div>
                                <h3 class="title"><span>Crafted for</span> SaaS, App and Software Landing Page</h3>
                            </div> <!-- section title -->
                            <p class="text">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, seiam nonumy eirmod
                                tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et
                                accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus
                                est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing.</p>
                            <a href="#" class="main-btn">Try it Free</a>
                        </div> <!-- about content -->
                    </div>
                    <div class="col-lg-6">
                        <div class="about-image text-center mt-50 wow fadeInRightBig" data-wow-duration="1s"
                            data-wow-delay="0.5s">
                            <img src="assets/images/about3.svg" alt="about">
                        </div> <!-- about image -->
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
            <div class="about-shape-1">
                <img src="assets/images/about-shape-1.svg" alt="shape">
            </div>
        </section>
    <!--====== ABOUT PART ENDS ======-->

    <!--====== VIDEO COUNTER PART START ======-->
        <section id="facts" class="video-counter pt-70">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="video-content mt-50 wow fadeIn" data-wow-duration="1s" data-wow-delay="0.5s">
                            <img class="dots" src="assets/images/dots.svg" alt="dots">
                            <div class="video-wrapper">
                                <div class="video-image">
                                    <img src="assets/images/video.png" alt="video">
                                </div>
                                <!-- <div class="video-icon">
                                    <a href="https://www.youtube.com/watch?v=r44RKWyfcFw" class="video-popup"><i class="lni-play"></i></a>
                                </div> -->
                            </div>
                            <!-- video wrapper -->
                        </div> <!-- video content -->
                    </div>
                    <div class="col-lg-6">
                        <div class="counter-wrapper mt-50 wow fadeIn" data-wow-duration="1s" data-wow-delay="0.8s">
                            <div class="counter-content">
                                <div class="section-title">
                                    <div class="line"></div>
                                    <h3 class="title">Cool facts <span> about MT Club</span></h3>
                                </div> <!-- section title -->
                                <p class="text">
                                    We are focused on providing best in class startegies and services to help you grow in every aspects.
                                </p>
                            </div> <!-- counter content -->
                            <div class="row no-gutters">
                                <div class="col-4">
                                    <div
                                        class="single-counter counter-color-1 d-flex align-items-center justify-content-center">
                                        <div class="counter-items text-center">
                                            <span class="count"><span class="counter">125</span>K</span>
                                            <p class="text">Total Users</p>
                                        </div>
                                    </div> <!-- single counter -->
                                </div>
                                <div class="col-4">
                                    <div
                                        class="single-counter counter-color-2 d-flex align-items-center justify-content-center">
                                        <div class="counter-items text-center">
                                            <span class="count"><span class="counter">87</span>K</span>
                                            <p class="text">Active Users</p>
                                        </div>
                                    </div> <!-- single counter -->
                                </div>
                                <div class="col-4">
                                    <div
                                        class="single-counter counter-color-3 d-flex align-items-center justify-content-center">
                                        <div class="counter-items text-center">
                                            <span class="count"><span class="counter">4.8</span></span>
                                            <p class="text">User Rating</p>
                                        </div>
                                    </div> <!-- single counter -->
                                </div>
                            </div> <!-- row -->
                        </div> <!-- counter wrapper -->
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
        </section>
    <!--====== VIDEO COUNTER PART ENDS ======-->

    <!--====== TEAM PART START ======-->
        <section id="team" class="team-area pt-120 d-none">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="section-title text-center pb-30">
                            <div class="line m-auto"></div>
                            <h3 class="title"><span>Meet Our</span> Creative Team Members</h3>
                        </div> <!-- section title -->
                    </div>
                </div> <!-- row -->
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-7 col-sm-8">
                        <div class="single-team text-center mt-30 wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s">
                            <div class="team-image">
                                <img src="assets/images/team-1.png" alt="Team">
                                <div class="social">
                                    <ul>
                                        <li><a href="#"><i class="lni-facebook-filled"></i></a></li>
                                        <li><a href="#"><i class="lni-twitter-filled"></i></a></li>
                                        <li><a href="#"><i class="lni-instagram-filled"></i></a></li>
                                        <li><a href="#"><i class="lni-linkedin-original"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="team-content">
                                <h5 class="holder-name"><a href="#">Isabela Moreira</a></h5>
                                <p class="text">Founder and CEO</p>
                            </div>
                        </div> <!-- single team -->
                    </div>
                    <div class="col-lg-4 col-md-7 col-sm-8">
                        <div class="single-team text-center mt-30 wow fadeIn" data-wow-duration="1s" data-wow-delay="0.5s">
                            <div class="team-image">
                                <img src="assets/images/team-2.png" alt="Team">
                                <div class="social">
                                    <ul>
                                        <li><a href="#"><i class="lni-facebook-filled"></i></a></li>
                                        <li><a href="#"><i class="lni-twitter-filled"></i></a></li>
                                        <li><a href="#"><i class="lni-instagram-filled"></i></a></li>
                                        <li><a href="#"><i class="lni-linkedin-original"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="team-content">
                                <h5 class="holder-name"><a href="#">Elon Musk</a></h5>
                                <p class="text">Sr. Software Engineer</p>
                            </div>
                        </div> <!-- single team -->
                    </div>
                    <div class="col-lg-4 col-md-7 col-sm-8">
                        <div class="single-team text-center mt-30 wow fadeIn" data-wow-duration="1s" data-wow-delay="0.8s">
                            <div class="team-image">
                                <img src="assets/images/team-3.png" alt="Team">
                                <div class="social">
                                    <ul>
                                        <li><a href="#"><i class="lni-facebook-filled"></i></a></li>
                                        <li><a href="#"><i class="lni-twitter-filled"></i></a></li>
                                        <li><a href="#"><i class="lni-instagram-filled"></i></a></li>
                                        <li><a href="#"><i class="lni-linkedin-original"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="team-content">
                                <h5 class="holder-name"><a href="#">Fiona Smith</a></h5>
                                <p class="text">Business Development Manager</p>
                            </div>
                        </div> <!-- single team -->
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
        </section>
    <!--====== TEAM PART ENDS ======-->

    <!--====== TESTIMONIAL PART START ======-->
        <section id="testimonial" class="testimonial-area pt-120 d-none">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="section-title text-center pb-40">
                            <div class="line m-auto"></div>
                            <h3 class="title">Users sharing<span> their experience</span></h3>
                        </div> <!-- section title -->
                    </div>
                </div> <!-- row -->
                <div class="row testimonial-active wow fadeInUpBig" data-wow-duration="1s" data-wow-delay="0.8s">
                    <div class="col-lg-4">
                        <div class="single-testimonial">
                            <div class="testimonial-review d-flex align-items-center justify-content-between">
                                <div class="quota">
                                    <i class="lni-quotation"></i>
                                </div>
                                <div class="star">
                                    <ul>
                                        <li><i class="lni-star-filled"></i></li>
                                        <li><i class="lni-star-filled"></i></li>
                                        <li><i class="lni-star-filled"></i></li>
                                        <li><i class="lni-star-filled"></i></li>
                                        <li><i class="lni-star-filled"></i></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="testimonial-text">
                                <p class="text">Lorem ipsum dolor sit amet,consetetur sadipscing elitr, seddiam nonu eirmod
                                    tempor invidunt labore.Lorem ipsum dolor sit amet,consetetur sadipscing elitr, seddiam
                                    nonu.</p>
                            </div>
                            <div class="testimonial-author d-flex align-items-center">
                                <div class="author-image">
                                    <img class="shape" src="assets/images/textimonial-shape.svg" alt="shape">
                                    <img class="author" src="assets/images/author-1.png" alt="author">
                                </div>
                                <div class="author-content media-body">
                                    <h6 class="holder-name">Jenny Deo</h6>
                                    <p class="text">CEO, SpaceX</p>
                                </div>
                            </div>
                        </div> <!-- single testimonial -->
                    </div>
                    <div class="col-lg-4">
                        <div class="single-testimonial">
                            <div class="testimonial-review d-flex align-items-center justify-content-between">
                                <div class="quota">
                                    <i class="lni-quotation"></i>
                                </div>
                                <div class="star">
                                    <ul>
                                        <li><i class="lni-star-filled"></i></li>
                                        <li><i class="lni-star-filled"></i></li>
                                        <li><i class="lni-star-filled"></i></li>
                                        <li><i class="lni-star-filled"></i></li>
                                        <li><i class="lni-star-filled"></i></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="testimonial-text">
                                <p class="text">Lorem ipsum dolor sit amet,consetetur sadipscing elitr, seddiam nonu eirmod
                                    tempor invidunt labore.Lorem ipsum dolor sit amet,consetetur sadipscing elitr, seddiam
                                    nonu.</p>
                            </div>
                            <div class="testimonial-author d-flex align-items-center">
                                <div class="author-image">
                                    <img class="shape" src="assets/images/textimonial-shape.svg" alt="shape">
                                    <img class="author" src="assets/images/author-2.png" alt="author">
                                </div>
                                <div class="author-content media-body">
                                    <h6 class="holder-name">Marjin Otte</h6>
                                    <p class="text">UX Specialist, Yoast</p>
                                </div>
                            </div>
                        </div> <!-- single testimonial -->
                    </div>
                    <div class="col-lg-4">
                        <div class="single-testimonial">
                            <div class="testimonial-review d-flex align-items-center justify-content-between">
                                <div class="quota">
                                    <i class="lni-quotation"></i>
                                </div>
                                <div class="star">
                                    <ul>
                                        <li><i class="lni-star-filled"></i></li>
                                        <li><i class="lni-star-filled"></i></li>
                                        <li><i class="lni-star-filled"></i></li>
                                        <li><i class="lni-star-filled"></i></li>
                                        <li><i class="lni-star-filled"></i></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="testimonial-text">
                                <p class="text">Lorem ipsum dolor sit amet,consetetur sadipscing elitr, seddiam nonu eirmod
                                    tempor invidunt labore.Lorem ipsum dolor sit amet,consetetur sadipscing elitr, seddiam
                                    nonu.</p>
                            </div>
                            <div class="testimonial-author d-flex align-items-center">
                                <div class="author-image">
                                    <img class="shape" src="assets/images/textimonial-shape.svg" alt="shape">
                                    <img class="author" src="assets/images/author-3.png" alt="author">
                                </div>
                                <div class="author-content media-body">
                                    <h6 class="holder-name">David Smith</h6>
                                    <p class="text">CTO, Alphabet</p>
                                </div>
                            </div>
                        </div> <!-- single testimonial -->
                    </div>
                    <div class="col-lg-4">
                        <div class="single-testimonial">
                            <div class="testimonial-review d-flex align-items-center justify-content-between">
                                <div class="quota">
                                    <i class="lni-quotation"></i>
                                </div>
                                <div class="star">
                                    <ul>
                                        <li><i class="lni-star-filled"></i></li>
                                        <li><i class="lni-star-filled"></i></li>
                                        <li><i class="lni-star-filled"></i></li>
                                        <li><i class="lni-star-filled"></i></li>
                                        <li><i class="lni-star-filled"></i></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="testimonial-text">
                                <p class="text">Lorem ipsum dolor sit amet,consetetur sadipscing elitr, seddiam nonu eirmod
                                    tempor invidunt labore.Lorem ipsum dolor sit amet,consetetur sadipscing elitr, seddiam
                                    nonu.</p>
                            </div>
                            <div class="testimonial-author d-flex align-items-center">
                                <div class="author-image">
                                    <img class="shape" src="assets/images/textimonial-shape.svg" alt="shape">
                                    <img class="author" src="assets/images/author-2.png" alt="author">
                                </div>
                                <div class="author-content media-body">
                                    <h6 class="holder-name">Fajar Siddiq</h6>
                                    <p class="text">COO, MakerFlix</p>
                                </div>
                            </div>
                        </div> <!-- single testimonial -->
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
        </section>
    <!--====== TESTIMONIAL PART ENDS ======-->

    <!--====== PART START ======-->
        <!--
            <section class="">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-"></div>
                    </div>
                </div>
            </section>
        -->
    <!--====== PART ENDS ======-->

    <?php
        goto skip_content;
    ?>

    <?php
        skip_content:
    ?>

    <?php require_once("footer.php"); ?>
    <?php require_once("scripts.php"); ?>

    
    <!-- BEGIN ModalBook -->
        <div class="modal fade" id="ModalBook" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Book Your Service</h3>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <input type="hidden" name="id_service" id="id_service">
                            <div class="row">
                                <div class="mb-3 col-md-12 col-xs-12 col-sm-12">
                                    <label class="form-label" for="title_service">Service Requested</label>
                                    <input type="text" name="title" id="title_service" class="form-control" placeholder="Title of Service" required readonly>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="form-label" for="name">Your Full Name <span style="color: red;">*</span></label>
                                    <input type="text" name="name" id="name" placeholder="Full Name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label" for="mobile">Mobile Number <span style="color: red;">*</span></label>
                                    <input type="mobile" name="mobile" id="mobile" placeholder="Mobile No." class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label" for="email">Email ID <span style="color: red;">*</span></label>
                                    <input type="email" name="email" id="email" placeholder="Working Email ID" class="form-control" required>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="form-label" for="message">Brief Requirement <span style="color: red;">*</span></label>
                                    <textarea name="message" id="message" rows="3" placeholder="Enter Your Brief Message" class="form-control" required></textarea>
                                </div>
                            </div>
                            <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                            <div style="float:right">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" name="submit_booking" class="btn btn-success" id="btn_save_booking">Save changes</button>
                            </div>
                        </form>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <!-- END ModalBook -->

    <script>
        function openBookNow(btn_id) {
            $("#ModalBook").modal();
        }
        
        // PASS DATA TO MODAL POPUP
            $(function () {
                $('#ModalBook').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var id = button.data('id'); // Extract info from data-* attributes
                    var title = button.data('title'); // Extract info from data-* attributes
                    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
					// package = "Package "+package;
					// payment = "20% Amount of "+package+"= Rs."+payment;
                    var modal = $(this);

                    message = "I am interested in booking the service [" + title + "] Please respond with your best pricing"
                    // modal.find(id).val(id);
                    modal.find("#id_service").val(id);
                    modal.find("#title_service").val(title);
                    modal.find("#message").val(message);
                    // SET IMAGE SRC
					// $("#image").attr("src", src);
                });
            });
        // PASS DATA TO MODAL POPUP
    </script>
</body>

</html>
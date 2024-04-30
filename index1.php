<!DOCTYPE html>
<html lang="en">

<?php
    ob_start();
	require_once("db_connect.php");
	session_start();

	mysqli_query($conn, "set names 'utf8'");  //-------WORKING UTF8 CODE------//

	//-------CURRENT DATE AND TIME TO FEED---------//
	date_default_timezone_set('Asia/Kolkata');
	$current_date = date('Y-m-d H:i:s');
    
    $url_dir_highlight = "assets/files/highlight";

    $url_highlight = "";
    $query_highlight = "SELECT `title` AS 'title_highlight', CONCAT('$url_dir_highlight/',`url_file`) AS 'url_highlight' FROM `highlights` WHERE `is_active`=1 ORDER BY `create_date` DESC LIMIT 1";
    $res = mysqli_query($conn,$query_highlight);
    $count_highlight = mysqli_num_rows($res);
    if ($count_highlight == 1) {
        $res = mysqli_fetch_array($res);
        extract($res);
    }
?>

<head>
    <title>WealthRide - Grow Your Wealth With Your Health</title>
    <?php require_once("head.php"); ?>
</head>

<body>
    <!-- Header Section Starts Here -->
        <?php require_once("navbar.php"); ?>
    <!-- Header Section Ends Here -->

    <!-- Banner Section Starts Here -->
    <section class="banner-section bg_img" style="background: url(assets/images/frontend/banner/61af265b85b201638868571.jpg) left center;">
        <!-- <span class="bg-shape"></span> -->
        <div class="container">
            <div class="banner-content">
                <h1 class="title">Maxizone Is Just What Your Future Needs</h1>
                <p>Maxizone is a Company & Also a Platform which is Going to Fullfill your Future Dreams. Maxizone is a way to get Success in Your Life.</p>
                <div class="button--wrapper">
                    <a href="register.php" class="cmn--btn active"><span>Get started</span></a>
                    <a href="product.php" class="cmn--btn"><span>Product</span></a>
                </div>
            </div>
        </div>
        <div class="shapes d-none d-sm-block">
            <div class="shape shape1">
                <img src="assets/templates/basic/images/shape/circle-triangle.png" alt="shape">
            </div>
            <div class="shape2 shape">
                <!-- <img src="assets/templates/basic/images/shape/shape-circle.png" alt="shape"> -->
            </div>
            <div class="shape3 shape">
                <img src="assets/templates/basic/images/shape/dots-colour.png" alt="shape">
            </div>
            <div class="shape4 shape">
                <img src="assets/templates/basic/images/shape/plus-big.png" alt="shape">
            </div>
            <div class="shape5 shape">
                <img src="assets/templates/basic/images/shape/waves.png" alt="shape">
            </div>
        </div>
    </section>
    <!-- Banner Section Ends Here -->

    <!-- About Us Section Starts Here -->
    <section class="about-section padding-top padding-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="about-thumb rtl">
                        <img src="assets/images/frontend/about/61af59c313a061638881731.png" alt="thumb" class="w-100">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content">
                        <div class="section-header">
                            <span class="subtitle">About Us</span>
                            <h2 class="title">Maxizone is the best Earning Platform</h2>
                            <p>Maxizone is a Company and also a Earning platform from where you can achieve the Goals of your future
                                What you have Planned, We are Totally Focused on Our Member's Development and Our Member's Reviews...</p>
                        </div>

                        <a href="about-us.php" class="cmn--btn active"><span>Learn more</span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="shape shape1"><img src="assets/templates/basic/images/shape/circle-triangle.png" alt="shape"></div>
        <div class="shape shape2"><img src="assets/templates/basic/images/shape/circle-big.png" alt="shape"></div>
    </section>
    <!-- About Us Section Ends Here -->

    <!-- Service Section Starts Here -->
    <section class="service-section padding-bottom pos-rel">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="section-header text-center">
                        <span class="subtitle">Our Services</span>
                        <h2 class="title">Maxizone Provides Awesome Services</h2>
                    </div>
                </div>
            </div>
            <div class="row gy-4 justify-content-center">

            
                <div class="col-lg-4 col-md-6 col-sm-10 align-self-center">
                    <div class="service-item card shadow gradient-flare">
                        <div class="service-icon"><i class="las la-graduation-cap"></i></div>
                        <div class="service-content">
                            <h4 class="title">Marketing Strategy</h4>
                            <p>Maxizone company works a little bit different from the Market Strategies of other companies. Anyone can become his own leader and employee of this company - as he/she is working for the company and for themselves.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-10 align-self-center">
                    <div class="service-item card shadow" style="background: #36D1DC;  /* fallback for old browsers */
                                                            background: -webkit-linear-gradient(to right, #5B86E5, #36D1DC) !important;
                                                            background: linear-gradient(to right, #5B86E5, #36D1DC) !important;
                                                    ">
                        <div class="service-icon"><i class="las la-graduation-cap"></i></div>
                        <div class="service-content">
                            <h4 class="title">Profit Gain</h4>
                            <p>Join Maxizone and earn more to secure your future betterment!</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 col-sm-10 align-self-center">
                    <div class="service-item card shadow" style="
                                                    ">
                        <div class="service-icon"><i class="las la-graduation-cap"></i></div>
                        <div class="service-content">
                            <h4 class="title">Earning Platform</h4>
                            <p>Maxizone is a company which also gives you the way and Guidance to Earning platform without any hesitation.
                                Our Company stands on the commitment and is ready to walk together with customer who Shows interests for Better future Development.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-10 align-self-center">
                    <div class="service-item card shadow" style="background: #1f4037;  /* fallback for old browsers */
                                                            background: -webkit-linear-gradient(to right, #99f2c8, #1f4037) !important;
                                                            background: linear-gradient(to right, #99f2c8, #1f4037) !important;
                                                            ">
                        <div class="service-icon"><i class="las la-clipboard-list"></i></div>
                        <div class="service-content">
                            <h4 class="title">Subsidy</h4>
                            <p>Maxizone provides Subsidy on the product you purchase. By this you get a way to Earning platform from Our Company.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 col-sm-10 align-self-center">
                    <div class="service-item pt-4 card shadow" style="background: #6a3093;  /* fallback for old browsers */
                                                            background: -webkit-linear-gradient(to right, #a044ff, #6a3093) !important;
                                                            background: linear-gradient(to right, #a044ff, #6a3093) !important;
                                                            ">
                        <div class="service-icon"><i class="las la-clipboard-list"></i></div>
                        <div class="service-content">
                            <h4 class="title">Get Commission</h4>
                            <p>Maxizone is also working for Customer's betterment by providing them Commission on their performance of works. Our Company is Totally Committed to its Customers' betterment.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 col-sm-10 align-self-center">
                    <div class="service-item card shadow gradient-flare">
                        <div class="service-icon"><i class="las la-clipboard-list"></i></div>
                        <div class="service-content">
                            <h4 class="title">Risk Free Business</h4>
                            <p>Maxizone is a Risk free Business as it does not promises for the illegal Activities and illegal Transactions.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Service Section Ends Here -->

    <!-- How it Work Section Starts Here -->
    <section class="work-section padding-bottom pos-rel">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="section-header text-center">
                        <span class="subtitle">How It Works</span>
                        <h2 class="title">How Can You Earn Profits</h2>
                    </div>
                </div>
            </div>
            <div class="row gy-5">

                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="work-item">
                        <div class="work-icon"><i class="fas fa-money-bill-wave"></i></div>
                        <div class="work-content">
                            <h4 class="title">Register Account</h4>
                            <p>At first you have to create an Account. Just go to the register page, fill up the form and
                                get registered using your sponsor's referral code or our referral code i.e., <b>wealthride</b>.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="work-item">
                        <div class="work-icon"><i class="fas fa-users"></i></div>
                        <div class="work-content">
                            <h4 class="title">Invite People</h4>
                            <p>After joining, get your ID activated and start referring your friends to join this prominent organization i.e. Maxizone.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="work-item">
                        <div class="work-icon"><i class="fas fa-user-edit"></i></div>
                        <div class="work-content">
                            <h4 class="title">Get Commission</h4>
                            <p>After successfully referring your friends, your regular commission will start crediting to your wallet, which you can withdraw at any time when you need to do so.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="shape shape1"><img src="assets/templates/basic/images/shape/circle-big.png" alt="shape"></div>
    </section>
    <!-- How it Work Section Ends Here -->

    <!-- Pricing Plan Section Starts Here -->
    <!-- <section class="plan-section padding-bottom pos-rel">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="section-header text-center">
                        <span class="subtitle">Our Pricing Plan</span>
                        <h2 class="title">Choose a Plan What Suits to You</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center gy-4">

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-10">
                    <div class="plan-item">
                        <span class="plan-serial">1</span>
                        <div class="plan-bottom">
                            <div class="plan-header">
                                <div class="plan-price"><sup>₹</sup>100</div>
                            </div>
                            <div class="plan-body">
                                <p class="plan-name">Standard</p>
                                <ul class="plan-info">
                                    <li class="active"><i class="las la-business-time __plan_info " data="bv"></i>Business Volume: 221</li>
                                    <li class="active"><i class="las la-comment-dollar __plan_info" data="ref_com"></i>Referral Commission: ₹ 21</li>
                                    <li class="active"><i class="las la-comments-dollar __plan_info" data="tree_com"></i>Commission To Tree: ₹ 45</li>

                                </ul>
                                <div class="text-center"><a href="login.php" class="cmn--btn-2 btn--md active"><span>Subscribe Plan</span></a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-10">
                    <div class="plan-item">
                        <span class="plan-serial">2</span>
                        <div class="plan-bottom">
                            <div class="plan-header">
                                <div class="plan-price"><sup>₹</sup>250</div>
                            </div>
                            <div class="plan-body">
                                <p class="plan-name">Gold</p>
                                <ul class="plan-info">
                                    <li class="active"><i class="las la-business-time __plan_info " data="bv"></i>Business Volume: 80</li>
                                    <li class="active"><i class="las la-comment-dollar __plan_info" data="ref_com"></i>Referral Commission: ₹ 2</li>
                                    <li class="active"><i class="las la-comments-dollar __plan_info" data="tree_com"></i>Commission To Tree: ₹ 5</li>

                                </ul>
                                <div class="text-center"><a href="login.php" class="cmn--btn-2 btn--md active"><span>Subscribe Plan</span></a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-10">
                    <div class="plan-item">
                        <span class="plan-serial">3</span>
                        <div class="plan-bottom">
                            <div class="plan-header">
                                <div class="plan-price"><sup>₹</sup>300</div>
                            </div>
                            <div class="plan-body">
                                <p class="plan-name">Platinum</p>
                                <ul class="plan-info">
                                    <li class="active"><i class="las la-business-time __plan_info " data="bv"></i>Business Volume: 150</li>
                                    <li class="active"><i class="las la-comment-dollar __plan_info" data="ref_com"></i>Referral Commission: ₹ 5</li>
                                    <li class="active"><i class="las la-comments-dollar __plan_info" data="tree_com"></i>Commission To Tree: ₹ 2</li>

                                </ul>
                                <div class="text-center"><a href="login.php" class="cmn--btn-2 btn--md active"><span>Subscribe Plan</span></a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-10">
                    <div class="plan-item">
                        <span class="plan-serial">4</span>
                        <div class="plan-bottom">
                            <div class="plan-header">
                                <div class="plan-price"><sup>₹</sup>199</div>
                            </div>
                            <div class="plan-body">
                                <p class="plan-name">matrix2</p>
                                <ul class="plan-info">
                                    <li class="active"><i class="las la-business-time __plan_info " data="bv"></i>Business Volume: 60</li>
                                    <li class="active"><i class="las la-comment-dollar __plan_info" data="ref_com"></i>Referral Commission: ₹ 10</li>
                                    <li class="active"><i class="las la-comments-dollar __plan_info" data="tree_com"></i>Commission To Tree: ₹ 10</li>

                                </ul>
                                <div class="text-center"><a href="login.php" class="cmn--btn-2 btn--md active"><span>Subscribe Plan</span></a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-10">
                    <div class="plan-item">
                        <span class="plan-serial">5</span>
                        <div class="plan-bottom">
                            <div class="plan-header">
                                <div class="plan-price"><sup>₹</sup>50</div>
                            </div>
                            <div class="plan-body">
                                <p class="plan-name">aaaaa</p>
                                <ul class="plan-info">
                                    <li class="active"><i class="las la-business-time __plan_info " data="bv"></i>Business Volume: 5000</li>
                                    <li class="active"><i class="las la-comment-dollar __plan_info" data="ref_com"></i>Referral Commission: ₹ 4500</li>
                                    <li class="active"><i class="las la-comments-dollar __plan_info" data="tree_com"></i>Commission To Tree: ₹ 4200</li>

                                </ul>
                                <div class="text-center"><a href="login.php" class="cmn--btn-2 btn--md active"><span>Subscribe Plan</span></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <div class="modal fade" id="__modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="__modal_title">Commission to tree info</h5>

                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer text-right ">
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="button" class="btn btn-danger" id="__modal_close">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Referral Section Starts Here -->
        <section class="referral-section" style="background: url(assets/images/frontend/refer/617406882b7241634993800.jpg) center;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="refer-content">

                            <h2 class="title">Refer Anyone & Get Profit on every Transactions</h2>
                            <p>As Maxizone is totally Committed to customers Better future development, We also share the profits to the customers who help our Business to boost. On Each New Joinings we provide a fix Profit to The Customers in the face of Subsidy, Commission & and Referrals.</p>
                            <a href="register.php" class="cmn--btn active"><span>Get Started</span></a>
                            <div class="shape shape1"><img src="assets/templates/basic/images/icon/gft.png" alt="icon">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 d-none d-lg-block">
                        <div class="refer-thumb">
                            <img src="assets/images/frontend/refer/6173be1dedf681634975261.png" alt="thumb">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <!-- Referral Section Ends Here -->

    <section class="about-section padding-top padding-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="about-thumb rtl">
                        <img src="assets/images/frontend/about/61af59c313a061638881731.png" alt="thumb" class="w-100">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content">
                        <div class="section-header">
                            <span class="subtitle">Our Motive</span>
                            <h2 class="title">Grow your Wealth with Your Health</h2>
                            <p>Maxizone stands on a Slogan <b>"Grow your Wealth with Your Health"</b> 
                            This means that our company also helps you in making you Healthy by providing a Better Earnings too.
                            By the help of our Company, You are going not only to achieve your future dreams but you are going to fit your Health too.
                            Since we are working with the physically fit Strategies too.</p>
                        </div>

                        <!-- <a href="about-us.php" class="cmn--btn active"><span>Learn more</span></a> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="shape shape1"><img src="assets/templates/basic/images/shape/circle-triangle.png" alt="shape"></div>
        <div class="shape shape2"><img src="assets/templates/basic/images/shape/circle-big.png" alt="shape"></div>
    </section>

    <!-- Team Section Starts Here -->
        <!-- <section class="team-section padding-top padding-bottom pos-rel">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10">
                        <div class="section-header text-center">
                            <span class="subtitle">Team Member</span>
                            <h2 class="title">Our Pasioniate Team Member</h2>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center gy-4">

                    <div class="col-lg-4 col-md-6 col-sm-10">
                        <div class="team-item">
                            <div class="team-thumb"><img src="assets/images/frontend/team/61740bb6a99b61634995126.jpg" alt="testimonials"></div>
                            <div class="team-content">
                                <h4 class="name">Robinson Datag</h4>
                                <span class="designation">Laravel Developer</span>
                                <ul class="social-icons">
                                    <li><a href="3"><i class="lab la-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="lab la-twitter"></i></a></li>
                                    <li><a href=""><i class="lab la-instagram"></i></a></li>
                                    <li><a href="#"><i class="lab la-vimeo"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-10">
                        <div class="team-item">
                            <div class="team-thumb"><img src="assets/images/frontend/team/61740bc99d1211634995145.jpg" alt="testimonials"></div>
                            <div class="team-content">
                                <h4 class="name">Robinson Datag</h4>
                                <span class="designation">Web Developer</span>
                                <ul class="social-icons">
                                    <li><a href="#"><i class="lab la-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="lab la-twitter"></i></a></li>
                                    <li><a href=""><i class="lab la-instagram"></i></a></li>
                                    <li><a href="#"><i class="lab la-vimeo"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-10">
                        <div class="team-item">
                            <div class="team-thumb"><img src="assets/images/frontend/team/61740b7599fab1634995061.jpg" alt="testimonials"></div>
                            <div class="team-content">
                                <h4 class="name">Robinson Datag</h4>
                                <span class="designation">Web Developer</span>
                                <ul class="social-icons">
                                    <li><a href="#"><i class="lab la-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="lab la-twitter"></i></a></li>
                                    <li><a href="#"><i class="lab la-instagram"></i></a></li>
                                    <li><a href="#"><i class="lab la-vimeo"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section> -->
    <!-- Team Section Ends Here -->

    <!-- Testimonial Section Starts Here -->
    <!-- <section class="testimonial-section padding-top padding-bottom pos-rel">
        <div class="container">
            <div class="testimonial-wrapper row">
                <div class="col-lg-6">
                    <div class="section-header">
                        <span class="subtitle">Client&#039;s Say</span>
                        <h2 class="title">What Our Client Say About Us</h2>
                    </div>
                    <div class="testimonial-slider owl-carousel owl-theme" data-slider-id="1">
                        <div class="testimonial-item">
                            <div class="quote-icon"><i class="flaticon-left-quote"></i></div>
                            <p>Customer testimonials are a beneficial type of social proof: they tell potential new
                                customers about the successes and triumphs others have experienced. And because these
                                are real people.</p>
                            <div class="thumb"><img src="assets/images/frontend/testimonial/61adba0eeecac1638775310.jpg" alt="testimonials"></div>
                            <h4 class="name">Farhan Ahmed</h4>
                            <span class="designation">CEO at Google</span>
                        </div>
                        <div class="testimonial-item">
                            <div class="quote-icon"><i class="flaticon-left-quote"></i></div>
                            <p>Customer testimonials are a beneficial type of social proof: they tell potential new
                                customers about the successes and triumphs others have experienced. And because these
                                are real people.</p>
                            <div class="thumb"><img src="assets/images/frontend/testimonial/61acaae97f4651638705897.jpg" alt="testimonials"></div>
                            <h4 class="name">Farhan Ahmed</h4>
                            <span class="designation">CEO at Google</span>
                        </div>
                        <div class="testimonial-item">
                            <div class="quote-icon"><i class="flaticon-left-quote"></i></div>
                            <p>Customer testimonials are a beneficial type of social proof: they tell potential new
                                customers about the successes and triumphs others have experienced. And because these
                                are real people.</p>
                            <div class="thumb"><img src="assets/images/frontend/testimonial/61acaad39182b1638705875.jpg" alt="testimonials"></div>
                            <h4 class="name">Farhan Ahmed</h4>
                            <span class="designation">CEO at Google</span>
                        </div>
                        <div class="testimonial-item">
                            <div class="quote-icon"><i class="flaticon-left-quote"></i></div>
                            <p>Customer testimonials are a beneficial type of social proof: they tell potential new
                                customers about the successes and triumphs others have experienced. And because these
                                are real people.</p>
                            <div class="thumb"><img src="assets/images/frontend/testimonial/61acaac1510b51638705857.jpg" alt="testimonials"></div>
                            <h4 class="name">Farhan Ahmed</h4>
                            <span class="designation">CEO at Google</span>
                        </div>
                        <div class="testimonial-item">
                            <div class="quote-icon"><i class="flaticon-left-quote"></i></div>
                            <p>Customer testimonials are a beneficial type of social proof: they tell potential new
                                customers about the successes and triumphs others have experienced. And because these
                                are real people.</p>
                            <div class="thumb"><img src="assets/images/frontend/testimonial/61acaaa8bafba1638705832.jpg" alt="testimonials"></div>
                            <h4 class="name">Farhan Ahmed</h4>
                            <span class="designation">CEO at Google</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="owl-thumbs testimonial-img-slider" data-slider-id="1">
                        <div class="owl-thumb-item">
                            <div class="thumb thumb0"><img src="assets/images/frontend/testimonial/61adba0eeecac1638775310.jpg" alt="testimonials"></div>
                        </div>
                        <div class="owl-thumb-item">
                            <div class="thumb thumb1"><img src="assets/images/frontend/testimonial/61acaae97f4651638705897.jpg" alt="testimonials"></div>
                        </div>
                        <div class="owl-thumb-item">
                            <div class="thumb thumb2"><img src="assets/images/frontend/testimonial/61acaad39182b1638705875.jpg" alt="testimonials"></div>
                        </div>
                        <div class="owl-thumb-item">
                            <div class="thumb thumb3"><img src="assets/images/frontend/testimonial/61acaac1510b51638705857.jpg" alt="testimonials"></div>
                        </div>
                        <div class="owl-thumb-item">
                            <div class="thumb thumb4"><img src="assets/images/frontend/testimonial/61acaaa8bafba1638705832.jpg" alt="testimonials"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="shape shape1"><img src="assets/templates/basic/images/shape/blob.png" alt="shape"></div>
        <div class="shape shape2"><img src="assets/templates/basic/images/icon/quote.png" alt="icon"></div>
    </section> -->
    <!-- Testimonial Section Ends Here -->

    <!-- Blog Section Starts Here -->
    <!-- <section class="blog-section padding-bottom pos-rel">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="section-header text-center">
                        <span class="subtitle">LATEST NEWS</span>
                        <h2 class="title">Welcome! Please check our latest news and article here</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center gy-4">
            </div>
        </div>
        <div class="shape shape1">
            <img src="assets/templates/basic/images/shape/blob1.png" alt="shap">
        </div>
    </section> -->
    <!-- Blog Section Ends Here -->
    
    <div class="modal fade" id="modalHighlight" tabindex="-1" role="dialog" aria-labelledby="modalHighlightTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content highlight-body">
                <div class="modal-body">
                    
                    <img src="<?php echo $url_highlight; ?>" class="w-100 highlight-img">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary gradient-amin rounded-pill text-uppercase btn-sm shadow" data-bs-dismiss="modal">Continue</button>
                </div>
            </div>
        </div>
    </div>

    <?php require_once("footer.php"); ?>
    <?php require_once("scripts.php"); ?>

    <?php
        if ($count_highlight == 1) {
            ?>
                <script>
                    'use strict';
                    $(function($) {
                        $("#modalHighlight").modal('show');
                    });
                </script>
            <?php
        }
    ?>

    <script>
        'use strict';
        (function($) {
            $('.__plan_info').on('click', function(e) {
                let html = "";
                let data = $(this).attr('data');
                let modal = $("#__modal");
                if (data == 'bv') {
                    html = ` <h5>   <span class="text-danger">When someone from your below tree subscribe this plan, You will get this Business Volume  which will be used for matching bonus.</span>
                </h5>`
                    modal.find('#__modal_title').html("Business Volume (BV) info")

                }
                if (data == 'ref_com') {
                    html = `  <h5>  <span class=" text-danger">When Your Direct-Referred/Sponsored  User Subscribe in <b> ANY PLAN </b>, You will get this amount.</span>
                        <br>
                        <br>
                        <span class="text-success"> This is the reason You should Choose a Plan With Bigger Referral Commission.</span> </h5>`
                    modal.find('#__modal_title').html("Referral Commission info")

                }
                if (data == 'tree_com') {
                    html = ` <h5 class=" text-danger">When someone from your below tree subscribe this plan, You will get this amount as Tree Commission. </h5>`
                    modal.find('#__modal_title').html(html)
                }
                modal.find('.modal-body').html(html)
                $(modal).modal('show')
            });

            $('body').on('click', '#__modal_close', function(e) {
                $("#__modal").modal('hide');
            });
        })(jQuery)
    </script>
</body>

</html>
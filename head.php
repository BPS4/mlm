<?php
    // DEFINE ROOT PATH FOR ASSETS
    switch ($_SERVER['SERVER_NAME']) {
        case '192.168.1.7': //development
            define('BASE_URL', 'http://192.168.1.7/maxizon_mlm');
            // define('ROOT_PATH', $_SERVER["DOCUMENT_ROOT"].'/maxizon_mlm');
            define('ROOT_PATH', BASE_URL);
            break;
        case 'localhost': //development
            define('BASE_URL', 'http://localhost/maxizon_mlm');
            // define('ROOT_PATH', $_SERVER["DOCUMENT_ROOT"].'/maxizon_mlm');
            define('ROOT_PATH', BASE_URL);
            break;
        case 'maxizone.in': //production
            define('BASE_URL', 'https://maxizone.in');
            // define('ROOT_PATH', $_SERVER["DOCUMENT_ROOT"].'/maxizon_mlm');
            define('ROOT_PATH', BASE_URL);
            break;
        default:
            define('BASE_URL', 'http://192.168.1.8/maxizon_mlm');
            // define('ROOT_PATH', $_SERVER["DOCUMENT_ROOT"].'/maxizon_mlm');
            define('ROOT_PATH', BASE_URL);
            break;
    }

    $website_url = "https://maxizone.in";

    ob_start();
	require_once("db_connect.php");
	session_start();

	mysqli_query($conn, "set names 'utf8'");  //-------WORKING UTF8 CODE------//

	//-------CURRENT DATE AND TIME TO FEED---------//
	date_default_timezone_set('Asia/Kolkata');
	$current_date = date('Y-m-d H:i:s');
    
    $msg = $msg_type = "";

	extract($_REQUEST);

    $login_stop = $signup_stop = $website_stop = "0";
    $query = mysqli_query($conn, "SELECT * FROM `configuration` WHERE `id` IN (3,4,5) AND `delete_date` IS NULL");
    $records = mysqli_fetch_all($query, MYSQLI_ASSOC);
    foreach ($records as $image) {
        //DEFAULT VALUES SET
        $classStatus = 'bg-success';
        $id = $image['id'];

        $key_field = $image['key_field'];
        $value_field = $image['value_field'];
        $is_active = $image['is_active'];
        $remarks = $image['remarks'];

        if ($key_field == "login_maintain") {
            $login_stop = $is_active;
            if ($login_stop) {
                $_SESSION['login_stop'] = 1;
                $_SESSION['login_stop_message'] = $remarks;
            } else {
                $_SESSION['login_stop'] = 0;
                unset($_SESSION['login_stop_message']);
            }
        }

        if ($key_field == "signup_maintain") {
            $signup_stop = $is_active;
            if ($signup_stop) {
                $_SESSION['signup_stop'] = 1;
                $_SESSION['signup_stop_message'] = $remarks;
            } else {
                $_SESSION['signup_stop'] = 0;
                unset($_SESSION['signup_stop_message']);
            }
        }

        unset($_SESSION['signup_stop']);
        unset($_SESSION['signup_stop_message']);

        unset($_SESSION['login_stop']);
        unset($_SESSION['login_stop_message']);

        if ($key_field == "web_maintain") {
            $website_stop = $is_active;
            if ($website_stop) {
                $_SESSION['web_stop'] = 1;
                $_SESSION['web_stop_message'] = $remarks;
            } else {
                $_SESSION['web_stop'] = 0;
                unset($_SESSION['web_stop_message']);
            }
        }
    }
    
    if ($website_stop) {
        header("Location: under-maintenance.php");
    }
    
    // PATTERNS TO CHECK NAME EMAIL AND MOBILE
        $regex_email = "/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/";
        $regex_name = "/^[A-Za-z ]+$/";
        $regex_mobile = "/^[0-9]{10}$/";
        $regex_pan = "/^[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}$/";
        $regex_aadhaar = "/^[0-9]{12}$/";
        $regex_website = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";

        $name = "Name";
        $email = "example@email.com";
        $mobile = "9876543210";
        $pan = "QQQQQ1111Q";
        $aadhaar = "987654321789";
        $website = "https://maxizone.in";

        // $valid_name = preg_match($regex_name,$name);
        // $valid_email = preg_match($regex_email,$email);
        // // $valid_email = filter_var($email, FILTER_VALIDATE_EMAIL);
        // $valid_mobile = preg_match($regex_mobile,$mobile);
        // $valid_pan = preg_match($regex_pan,$pan);
        // $valid_aadhaar = preg_match($regex_aadhaar,$aadhaar);
        // $valid_website = preg_match($regex_website,$website);
    // PATTERNS TO CHECK NAME EMAIL AND MOBILE
?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Maxizone - Home </title>

    <meta name="title" content="Maxizone - Home">
    <meta name="description" content="Maxizone ">
    <meta name="keywords" content="admin,blog,manage,mlm,mlmlab,binary mlm,php mlm">
    <link rel="shortcut icon" href="<?php echo ROOT_PATH; ?>/assets/images/favicon.jpg" type="image/x-icon">


    <link rel="apple-touch-icon" href="<?php echo ROOT_PATH; ?>/assets/images/logo-mt.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Maxizone - Home">

    <meta itemprop="name" content="Maxizone - Home">
    <meta itemprop="description" content="Maxizone">
    <meta itemprop="image" content="">

    <meta property="og:type" content="website">
    <meta property="og:title" content="Maxizone - Home">
    <meta property="og:description" content="Maxizone">
    <meta property="og:image" content="">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="600">
    <meta property="og:image:height" content="315">
    <meta property="og:url" content="<?php echo $website_url; ?>">

    <meta name="twitter:card" content="summary_large_image">

    <!-- <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>/user/assets/vendor/libs/sweetalert2/sweetalert2.css" /> -->

    <!--====== Favicon Icon ======-->
    <link rel="shortcut icon" href="<?php echo ROOT_PATH; ?>/assets/images/favicon.png" type="image/png">

    <!--====== Animate CSS ======-->
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>/assets/css/animate.css">

    <!--====== Magnific Popup CSS ======-->
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>/assets/css/magnific-popup.css">

    <!--====== Slick CSS ======-->
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>/assets/css/slick.css">

    <!--====== Line Icons CSS ======-->
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>/assets/css/LineIcons.css">

    <!--====== Font Awesome CSS ======-->
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>/assets/css/font-awesome.min.css">

    <!--====== Bootstrap CSS ======-->
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>/assets/css/bootstrap.min.css">

    <!--====== Default CSS ======-->
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>/assets/css/default.css">

    <!--====== Style CSS ======-->
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>/assets/css/style.css">

    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->

    <style>
        * {
            font-family:Georgia, 'Times New Roman', Times, serif !important;
        }
        .la, .las {
            font-family: 'Line Awesome Free' !important;
            font-weight: 900;
        }
        .fa, .far, .fas {
            font-family: "Font Awesome 5 Free" !important;
        }
        .lab {
            font-family: 'Line Awesome Brands' !important;
        }
        .fab {
            font-family: "Font Awesome 5 Brands" !important;
        }
        .font-georgia, .menu li a {
            font-family:Georgia, 'Times New Roman', Times, serif !important;
            /* color: purple; */
            font-weight: bolder;
        }
        @media (max-width: 991px) {
            .footer-bottom .footer-bottom-wrapper {
                -webkit-box-pack: center;
                -ms-flex-pack: center;
                justify-content: space-around;
            }
        }

        @media (max-width: 767px) {
            .footer-bottom .footer-bottom-wrapper {
                -webkit-box-pack: center;
                -ms-flex-pack: center;
                justify-content: center;
            }
        }

        .w-50 {
            width: 50% !important;
        }

        .show_pointer {
            cursor: pointer !important;
        }
        
        .placeholder_error::-webkit-input-placeholder {
            color: #fff;
        }
        .gradient-red , .gradient-strain {
            background: #870000;
            background: -webkit-linear-gradient(to right, #190A05, #870000);
            background: linear-gradient(to right, #190A05, #870000);
        }
        .banner-section::before, .plan-item .cmn--btn-2, .inner-banner::before, div[class*='col']:nth-child(odd) .plan-serial, div[class*='col']:nth-child(odd) .post-item .read-more::before {
            background: #870000 !important;
            background: -webkit-linear-gradient(to right, #190A05, #870000) !important;
            background: linear-gradient(to right, #190A05, #870000) !important;
        }

        .account-thumb::before {
            background: #870000 !important;
            background: -webkit-linear-gradient(to right, #190A05, #870000) !important;
            background: linear-gradient(to right, #190A05, #870000) !important;
        }    
    </style>

    <style>
        .fa-telegram, .fa-telegram-plane {
            color: #2AABEE
        }
        .fa-facebook, .fa-facebook-square {
            color: #3b5998
        }
        .fa-twitter, .fa-twitter-square {
            color: #00aced
        }
        .fa-google-plus, .fa-google-plus-square {
            color: #dd4b39
        }
        .fa-youtube, .fa-youtube-play, .fa-youtube-square {
            color: #bb0000
        }
        .fa-tumblr, .fa-tumblr-square {
            color: #32506d
        }
        .fa-vine {
            color: #00bf8f
        }
        .fa-flickr {
            color: #ff0084
        }
        .fa-vimeo-square {
            color: #aad450
        }
        .fa-pinterest, .fa-pinterest-square {
            color: #cb2027
        }
        .fa-linkedin, .fa-linkedin-square {
            color: #007bb6
        }
        .fa-instagram {
            color: #517fa4;
        }
        .fa-spotify {
            color: #1ED760;
        }
        .fa-facebook {
            color: #3b5998;
            background-image: linear-gradient( to bottom, transparent 20%, white 20%, white 93%, transparent 93% );
            background-size: 55%;
            background-position: 70% 0;
            background-repeat: no-repeat;
        }
        .fa-instagram {
            color: transparent;
            background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
            background: -webkit-radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
            background-clip: text;
            -webkit-background-clip: text;
        }
        .fa-whatsapp  {
            color:#fff;
            background:
            linear-gradient(#25d366,#25d366) 14% 84%/16% 16% no-repeat,
            radial-gradient(#25d366 60%,transparent 0);
        }
    </style>

    <style>
        .banner-section::before, .plan-item .cmn--btn-2, .inner-banner::before, div[class*='col']:nth-child(odd) .plan-serial, div[class*='col']:nth-child(odd) .post-item .read-more::before,
        .gradient-flare {
            background: #f12711 !important;
            background: -webkit-linear-gradient(to right, #f5af19, #f12711) !important;
            background: linear-gradient(to right, #f5af19, #f12711) !important;
        }

        .gradient-lush {
            background: #56ab2f;
            background: -webkit-linear-gradient(to right, #a8e063, #56ab2f);
            background: linear-gradient(to right, #a8e063, #56ab2f);
        }

        .gradient-piggy-pink {
            background: #ee9ca7;
            background: -webkit-linear-gradient(to left, #ffdde1, #ee9ca7);
            background: linear-gradient(to left, #ffdde1, #ee9ca7);
        }

        .gradient-red, .gradient-strain {
            background: #870000 !important;
            background: -webkit-linear-gradient(to right, #190A05, #870000) !important;
            background: linear-gradient(to right, #190A05, #870000) !important;
        }
        
        .gradient-ultra-violet {
            background: #654ea3;
            background: -webkit-linear-gradient(to right, #eaafc8, #654ea3);
            background: linear-gradient(to right, #eaafc8, #654ea3);
        }

        .gradient-megatron {
            background: #C6FFDD;
            background: -webkit-linear-gradient(to right, #f7797d, #FBD786, #C6FFDD);
            background: linear-gradient(to right, #f7797d, #FBD786, #C6FFDD);
        }

        .gradient-behongo {
            background: #52c234;
            background: -webkit-linear-gradient(to right, #061700, #52c234);
            background: linear-gradient(to right, #061700, #52c234);
        }

        .gradient-flickr {
            background: #ff0084;
            background: -webkit-linear-gradient(to right, #33001b, #ff0084);
            background: linear-gradient(to right, #33001b, #ff0084);
        }

        .gradient-lawrencium {
            background: #0f0c29;
            background: -webkit-linear-gradient(to right, #24243e, #302b63, #0f0c29);
            background: linear-gradient(to right, #24243e, #302b63, #0f0c29);
        }

        .gradient-twitch {
            background: #6441A5;
            background: -webkit-linear-gradient(to right, #2a0845, #6441A5);
            background: linear-gradient(to right, #2a0845, #6441A5);
        }

        .gradient-amin {
            background: #8E2DE2;
            background: -webkit-linear-gradient(to right, #4A00E0, #8E2DE2);
            background: linear-gradient(to right, #4A00E0, #8E2DE2);
        }

        .gradient-flare {
            background: #f12711;
            background: -webkit-linear-gradient(to right, #f5af19, #f12711);
            background: linear-gradient(to right, #f5af19, #f12711);
        }

        .gradient-sublime {
            background: #FC5C7D;
            background: -webkit-linear-gradient(to right, #6A82FB, #FC5C7D);
            background: linear-gradient(to right, #6A82FB, #FC5C7D);
        }

        .gradient-juicy-orange {
            background: #FF8008;
            background: -webkit-linear-gradient(to right, #FFC837, #FF8008);
            background: linear-gradient(to right, #FFC837, #FF8008);
        }

        .gradient-youtube {
            background: #e52d27;
            background: -webkit-linear-gradient(to right, #b31217, #e52d27);
            background: linear-gradient(to right, #b31217, #e52d27);
        }

        .gradient-martini {
            background: #FDFC47;
            background: -webkit-linear-gradient(to right, #24FE41, #FDFC47);
            background: linear-gradient(to right, #24FE41, #FDFC47);
        }

        .gradient-peach {
            background: #ED4264;
            background: -webkit-linear-gradient(to right, #FFEDBC, #ED4264);
            background: linear-gradient(to right, #FFEDBC, #ED4264);
        }

        .menu.active {
            background: #f12711 !important;
            background: -webkit-linear-gradient(to right, #f5af19, #f12711) !important;
            background: linear-gradient(to right, #f5af19, #f12711) !important;
        }

        .text-header-doc {
            color: purple;
            font-weight: bolder;
            text-shadow: 0px 2px 4px yellow;
        }

        .card-doc, .img-doc {
            border: 2px solid paleturquoise !important;
            box-shadow: 0px 1px 4px #24FE41 !important;
            border: 18px ridge #b83b40 !important;
        }

        .highlight-body {
            border: 9px ridge #3944F7 !important;
            box-shadow: 0px 1px 4px #24FE41 !important;
        }
        .highlight-img {
            border: 5px ridge maroon !important;
            box-shadow: 0px 1px 4px #24FE41 !important;
        }

        .divider-doc {

        }

        .glow {
            font-size: 80px;
            color: #fff;
            text-align: center;
            animation: glow 1s ease-in-out infinite alternate;
        }

        @-webkit-keyframes glow {
            from {
                text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #e60073, 0 0 40px #e60073, 0 0 50px #e60073, 0 0 60px #e60073, 0 0 70px #e60073;
            }
            
            to {
                text-shadow: 0 0 20px #fff, 0 0 30px #ff4da6, 0 0 40px #ff4da6, 0 0 50px #ff4da6, 0 0 60px #ff4da6, 0 0 70px #ff4da6, 0 0 80px #ff4da6;
            }
        }

        .box {
            background: hsl(0, 0%, 100%);
            padding: 16px 24px;
            position: relative;
            border-radius: 8px;
            box-shadow: 0 0 0 1px rgba(0,0,0,.01);

            &::after {
                position: absolute;
                content: "";
                top: 15px;
                left: 0;
                right: 0;
                z-index: -1;
                height: 100%;
                width: 100%;
                transform: scale(0.9) translateZ(0);
                filter: blur(15px);
                background: linear-gradient(
                to left,
                #ff5770,
                #e4428d,
                #c42da8,
                #9e16c3,
                #6501de,
                #9e16c3,
                #c42da8,
                #e4428d,
                #ff5770
                );
                background-size: 200% 200%;
                animation: animateGlow 1.25s linear infinite;
            }
        }

        @keyframes animateGlow {
            0% {
                background-position: 0% 50%;
            }
            100% {
                background-position: 200% 50%;
            }
        }

    </style>

    <!--====== PRELOADER PART START ======-->
        <div class="preloader">
            <div class="loader">
                <div class="ytp-spinner">
                    <div class="ytp-spinner-container">
                        <div class="ytp-spinner-rotator">
                            <div class="ytp-spinner-left">
                                <div class="ytp-spinner-circle"></div>
                            </div>
                            <div class="ytp-spinner-right">
                                <div class="ytp-spinner-circle"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!--====== PRELOADER PART ENDS ======-->

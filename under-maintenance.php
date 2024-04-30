<!doctype html>
<html class="no-js" lang="en">

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
    $maintain_message = "";
    
    $login_stop = $signup_stop = $website_stop = "0";
    // $query = mysqli_query($conn, "SELECT * FROM `configuration` WHERE `id` IN (3,4,5) AND `delete_date` IS NULL");
    // $records = mysqli_fetch_all($query, MYSQLI_ASSOC);
    // foreach ($records as $image) {
    //     //DEFAULT VALUES SET
    //     $classStatus = 'bg-success';
    //     $id = $image['id'];

    //     $key_field = $image['key_field'];
    //     $value_field = $image['value_field'];
    //     $is_active = $image['is_active'];
    //     $remarks = $image['remarks'];

    //     if ($key_field == "login_maintain") {
    //         $login_stop = $is_active;
    //         if ($login_stop) {
    //             $_SESSION['login_stop'] = 1;
    //             $_SESSION['login_stop_message'] = $remarks;
    //         } else {
    //             $_SESSION['login_stop'] = 0;
    //             unset($_SESSION['login_stop_message']);
    //         }
    //     }

    //     if ($key_field == "signup_maintain") {
    //         $signup_stop = $is_active;
    //         if ($signup_stop) {
    //             $_SESSION['signup_stop'] = 1;
    //             $_SESSION['signup_stop_message'] = $remarks;
    //         } else {
    //             $_SESSION['signup_stop'] = 0;
    //             unset($_SESSION['signup_stop_message']);
    //         }
    //     }

    //     if ($key_field == "web_maintain") {
    //         $website_stop = $is_active;
    //         if ($website_stop) {
    //             $_SESSION['web_stop'] = 1;
    //             $_SESSION['web_stop_message'] = $remarks;
    //         } else {
    //             $_SESSION['web_stop'] = 0;
    //             unset($_SESSION['web_stop_message']);
    //         }
    //     }
    // }

    if (isset($_SESSION['web_stop']) && $_SESSION['web_stop']) {
        $website_stop = "1";
        $maintain_message = $_SESSION['web_stop_message'];
    } else if (isset($_SESSION['signup_stop']) && $_SESSION['signup_stop']) {
        $signup_stop = "1";
        $maintain_message = $_SESSION['signup_stop_message'];
    } else if (isset($_SESSION['login_stop']) && $_SESSION['login_stop']) {
        $login_stop = "1";
        $maintain_message = $_SESSION['login_stop_message'];
    }

    if ((!$website_stop && !$signup_stop && !$login_stop)) {
     //   header("Location: ./");
    }

?>

<head>
    <title>Maxizone - Under Maintenance</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Maxizone - Home </title>

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

    <meta name="twitter:card" content="summary_large_image">

    <!-- <link rel="stylesheet" href="user/assets/vendor/libs/sweetalert2/sweetalert2.css" /> -->

    <!--====== Favicon Icon ======-->
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/png">

    <!--====== Animate CSS ======-->
    <link rel="stylesheet" href="assets/css/animate.css">

    <!--====== Magnific Popup CSS ======-->
    <link rel="stylesheet" href="assets/css/magnific-popup.css">

    <!--====== Slick CSS ======-->
    <link rel="stylesheet" href="assets/css/slick.css">

    <!--====== Line Icons CSS ======-->
    <link rel="stylesheet" href="assets/css/LineIcons.css">

    <!--====== Font Awesome CSS ======-->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">

    <!--====== Bootstrap CSS ======-->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!--====== Default CSS ======-->
    <link rel="stylesheet" href="assets/css/default.css">

    <!--====== Style CSS ======-->
    <link rel="stylesheet" href="assets/css/style.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</head>

<body>
    <!--====== HEADER PART START ======-->
        <header class="header-area">
            <div id="home" class="header-hero bg_cover" style="background-image: url(assets/images/banner-bg.svg)">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="header-hero-content text-center">
                            <img src="assets/images/logo-new.png" alt="hero" style="width: 200px; margin-bottom:25px;">
                                <!-- <h3 class="header-sub-title wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.2s">
                                    Maxizone - Your Service Partner
                                </h3> -->
                                <h3 class="text-white title wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.5s">
                                    We are Under Maintenance
                                </h3>
                                <h1 class="mt-3 header-title wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.5s">
                                    <?php echo $maintain_message; ?>
                                </h1>
                                <p class="text wow fadeInUp" data-wow-duration="1.3s" data-wow-delay="0.8s">
                                    Stay Tuned! Our Services Will Resume Soon...
                                </p>
                            </div>
                        </div>
                    </div> <!-- row -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="header-hero-image text-center wow fadeIn" data-wow-duration="1.3s"
                                data-wow-delay="1.4s">
                                <img src="images/illustrations/error-500.svg" alt="hero">
                            </div>
                        </div>
                    </div> <!-- row -->
                </div> <!-- container -->
                <div id="particles-1" class="particles"></div>
            </div>
        </header>
    <!--====== HEADER PART ENDS ======-->

</body>

</html>
<?php
    mysqli_query($conn, "set names 'utf8mb4'");  //-------WORKING UTF8 CODE FOR ALL EMOJIS------//

    $website_url = "https://maxizone.in";

    $url_dir_photo = "../assets/files/photo";
    
    $logo_path = "../assets/images/logo-new.png";

    if (isset($_SESSION['session_id'])) {
        // %0a >> LINE BREAK IN WHATSAPP MESSAGE
        $text_share = "I am glad to invite you for joining Maxizone Platform and Boost your earning while improving your health. Use the following link to sign up along with me: ";
        $space_for_copy_text = "";
        $space_for_whatsapp = "%0a %0a";
        $referral_link_text = "$website_url/user/register.php?ref_code=$user_id";
        $text_share_whatsapp = "$text_share $space_for_whatsapp $referral_link_text";
        $text_share = "$text_share $space_for_copy_text $referral_link_text";
        $text_share = nl2br($text_share);


        // GET USER DETAILS
            // $res = mysqli_fetch_array(mysqli_query($conn,"SELECT `name` AS 'user_name', `mobile` AS 'user_mobile', `email` AS 'user_email', IF(`photo_file` IS NULL,'assets/img/avatars/1.png',CONCAT('$url_dir_photo/',`photo_file`)) AS 'user_profile' FROM `users` WHERE `user_id`='$user_id'"));
            $res = mysqli_fetch_array(mysqli_query($conn,"SELECT `name` AS 'user_name', `mobile` AS 'user_mobile', `email` AS 'user_email', IF(`photo_file` IS NULL,'../assets/images/logo-new.png',CONCAT('$url_dir_photo/',`photo_file`)) AS 'user_profile' FROM `users` WHERE `user_id`='$user_id'"));
            extract($res);
            $_SESSION['user_profile'] = $user_profile;
            $_SESSION['user_email'] = $user_email;
        // GET USER DETAILS

        $query_notification = "SELECT `message` AS 'notification_message' FROM `notification_admin` WHERE `is_active`=1 ORDER BY `create_date` DESC LIMIT 1";
        $res = mysqli_query($conn,$query_notification);
        $count_notif_admin = mysqli_num_rows($res);
        if ($count_notif_admin == 1) {
            $res = mysqli_fetch_array($res);
            extract($res);
        }
    }

    // PATTERNS TO CHECK NAME EMAIL AND MOBILE
        $regex_email = "/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/";
        $regex_name = "/^[A-Za-z ]+$/";
        $regex_mobile = "/^[0-9]{10}$/";
        $regex_pan = "/^[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}$/";
        $regex_aadhaar = "/^[0-9]{12}$/";
        $regex_website = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";

        // $name = "Name";
        // $email = "example@email.com";
        // $mobile = "9876543210";
        // $pan = "QQQQQ1111Q";
        // $aadhaar = "987654321789";
        // $website = "https://maxizone.in";

        // $valid_name = preg_match($regex_name,$name);
        // $valid_email = preg_match($regex_email,$email);
        // // $valid_email = filter_var($email, FILTER_VALIDATE_EMAIL);
        // $valid_mobile = preg_match($regex_mobile,$mobile);
        // $valid_pan = preg_match($regex_pan,$pan);
        // $valid_aadhaar = preg_match($regex_aadhaar,$aadhaar);
        // $valid_website = preg_match($regex_website,$website);
    // PATTERNS TO CHECK NAME EMAIL AND MOBILE    
?>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Maxizone - Your Growth Partner">
    <meta name="keywords" content="Maxizone">
    <meta name="author" content="Maxizone">
    <link rel="icon" href="<?php echo $logo_path; ?>" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo $logo_path; ?>" type="image/x-icon">

    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="assets/css/custom/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
    <link href="assets/css/custom/css2-1?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
    
    <link rel="stylesheet" type="text/css" href="../assets/gradient.css?<?php echo rand(); ?>">

    <link rel="stylesheet" type="text/css" href="assets/css/vendors/font-awesome.css">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/icofont.css">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/themify.css">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/flag-icon.css">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/feather-icon.css">
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/sweetalert2.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/scrollbar.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/datatables-1.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/animate.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/date-picker.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/photoswipe.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/select2-1.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/prism-1.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/whether-icon-1.css">
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vendors/bootstrap.min.css">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link id="color" rel="stylesheet" href="assets/css/color-1.css" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="assets/css/responsive.css">

    <link rel="stylesheet" href="../assets/sweetalert2/sweetalert2.css" />

    <style>
        .select2-container .select2-selection--single {
            border: 1px solid #ced4da !important;
        }
    </style>

    <style>
        .show_pointer, .show-pointer {
            cursor: pointer;
        }
        .page-wrapper.compact-wrapper .page-body-wrapper div.sidebar-wrapper .sidebar-main .simplebar-offset {
            height: calc(100vh - 350px);
            height: 100vh !important;
        }
        
        @media print {

            div.dt-buttons,
            .no_print {
                display: none !important;
            }

            /*------------FOR HIDING URL OF HREF IN PRINTING-------------*/
            a[href]:after {
                content: none !important;
            }

            /* .section_to_print a[href]:before { display:none; visibility:hidden; }*/
            @page {
                size: A4 landscape;
                /*//auto, portrait, landscape or length (2 parameters width and height. sets both equal if only one is provided. % values not allowed.)*/

                margin-top: 1cm;
                margin-bottom: 1cm;
            }
        }

        * {
            font-family:Georgia, 'Times New Roman', Times, serif !important;
        }

        .form-label, .text-bold {
            font-weight: bold;
        }

        .border-color-success {
            border-color: #28C76F;
        }

        .border-color-primary {
            border-color: #7367f0;
        }

        .border-color-purple {
            border-color: #7367f0;
        }

        .border-color-black {
            border-color: black;
        }

        .border-color-red {
            border-color: red;
        }
        
        .bg-transparent {
            /* background: transparent !important; */
        }

        .bg-white {
            background: white !important;
        }

        .no-wrap {
            white-space: nowrap !important;
        }
    </style>

    <!-- Loader starts-->
        <div class="loader-wrapper d-none">
            <div class="loader">
                <div class="loader-bar"></div>
                <div class="loader-bar"></div>
                <div class="loader-bar"></div>
                <div class="loader-bar"></div>
                <div class="loader-bar"></div>
                <div class="loader-ball"></div>
            </div>
        </div>
    <!-- Loader ends-->

    <!-- tap on top starts-->
        <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->

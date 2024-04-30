<!DOCTYPE html>
<html lang="en">

<?php
    ob_start();
    require_once("../db_connect.php");
    session_start();

    mysqli_query($conn, "set names 'utf8'");  //-------WORKING UTF8 CODE------//

    //-------CURRENT DATE AND TIME TO FEED---------//
    date_default_timezone_set('Asia/Kolkata');
    $current_date = date('Y-m-d H:i:s');

    // check login status
    if (isset($_SESSION['session_id'])) {
        // $name = json_decode(base64_decode($_GET['name']));    //DECODE
        // $name_encode = base64_encode(json_encode($name));     //ENCODE

        $user_id = $_SESSION['user_id'];
        $sponsor_id = $_SESSION['sponsor_id'];
        $session_id = $_SESSION['session_id'];
        $name = $_SESSION['name'];    //DECODE
        $name_encode = base64_encode(json_encode($name));     //ENCODE

        // USER AUTHENTICATION
        $session_id_pass = $_SESSION['session_id'];
        $query = mysqli_query($conn, "SELECT `unique_id`, `session_id` FROM `login_sessions` WHERE `unique_id`='$user_id' ORDER BY `create_date` DESC LIMIT 1");
        if ($row = mysqli_fetch_array($query)) {
            $session_id = $row['session_id'];
            // $name = $row['name'];

            header("Location: ./");

            // ADMIN LOGIN MULTIPLE LOGIN ALLOWED 11-02-2022
            // if ($session_id_pass != $session_id) {
            //   echo "<script>alert('Session Expired. Login Again!!!');</script>";
            //   echo "Redirecting...Please Wait";
            //   header("Refresh:0, url=logout/");
            //   exit;
            // }
        } else {
            echo "<script>alert('You Are Not An Authorised Person!!!');</script>";
            echo "Redirecting...Please Wait";
            header("Refresh:0, url=logout/");
            exit;
        }
        // USER AUTHENTICATION
    }
    // check login status

    $msg = $msg_type = "";
    if (isset($_POST['login'])) {
        $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        if (!filter_var($user_id, FILTER_VALIDATE_EMAIL) && $user_id != "maxizone") {
            $user_id = strtoupper($user_id);
        }

        $result = mysqli_query($conn, "SELECT * FROM `users` WHERE (`user_id` = '$user_id' OR `email` = '$user_id') AND `password` = '$password'");
        if ($row = mysqli_fetch_array($result)) {
            $session_id = rand(1, 999999);
            $user_id = $row['user_id'];
            $name = $row['name'];
            $sponsor_id = $row['sponsor_id'];
            $is_active = $row['is_active'];
            $status = $row['status'];

            if ($is_active == 0 || $status == "inactive") {
                $msg = "ID Is Inactive! Kindly Contact Admin For Support.<br> <a href='../contact.php' class='fw-bold text-danger'>Click to Contact Us</a>";
                $msg = mysqli_real_escape_string($conn, $msg);
                $msg_type = "error";
                goto error_occurred;
            }

            $_SESSION['user_id'] = $user_id;
            $_SESSION['sponsor_id'] = $sponsor_id;
            $_SESSION['session_id'] = $session_id;
            $_SESSION['name'] = $name;

            $user_id_encode = base64_encode(json_encode($user_id));     //ENCODE
            $name_encode = base64_encode(json_encode($name));     //ENCODE
            // $user_id = json_decode(base64_decode($_GET['user_id']));    //DECODE  

            // CREATE LOGIN SESSION
            if (mysqli_query($conn, "INSERT INTO `login_sessions` (`unique_id`,`session_id`,`login_date`,`create_date`) VALUES ('$user_id','$session_id','$current_date','$current_date') ")) {
                header("Location: ../user/");
                exit;
            } else {
                $msg = "Session Error. Please Try Again...";
                $msg_type = "warning";
            }
            // CREATE LOGIN SESSION
        } else {
            $msg = "Wrong Credentials! Retry...";
            $msg_type = "error";
        }
    }
    error_occurred:

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

        unset($_SESSION['signup_stop']);
        unset($_SESSION['signup_stop_message']);

        // if ($key_field == "signup_maintain") {
        //     $signup_stop = $is_active;
        //     if ($signup_stop) {
        //         $_SESSION['signup_stop'] = 1;
        //         $_SESSION['signup_stop_message'] = $remarks;
        //     } else {
        //         $_SESSION['signup_stop'] = 0;
        //         unset($_SESSION['signup_stop_message']);
        //     }
        // }

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
    
    if ($website_stop || $login_stop) {
        header("Location: ../under-maintenance.php");
    }
?>

<head>
    <title>Maxizone - Your Growth Partner</title>
    <?php require_once("head.php"); ?>
</head>

<body>
    <!-- page-wrapper Start-->
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-7 order-1"><img class="bg-img-cover bg-center" src="assets/images/login/2.jpg" alt="looginpage"></div>
                <div class="col-xl-5 p-0">
                    <div class="login-card">
                        <form class="theme-form login-form needs-validation" novalidate="" method="post" action="">
                            <a href="../" class="lead"> 
                                <img src="<?php echo $logo_path; ?>" class="rounded mb-3" style="width: 40px;"> Maxizone
                            </a>
                            <h4>Login</h4>
                            <h6>Welcome back! Log in to your account.</h6>
                            
                            <div class="form-group">
                                <label for="user_id" class="form-label">Email ID / User ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="user_id" name="user_id" placeholder="Enter Email ID / User ID" required>
                            </div>
                            <div class="form-group">
                                <label for="password" class="form-label">Password<span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                            </div>
                            <!-- <div class="form-group custom--checkbox">
                                <input id="remember-me" type="checkbox" name="remember" class="form-control">
                                <label for="remember-me" class="form-label">Remember Me</label>
                            </div> -->
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" type="submit" name="login">Log in</button>
                            </div>
                            <!-- <div class="form-group">
                                <div class="checkbox">
                                    <input id="checkbox1" type="checkbox">
                                    <label class="text-muted" for="checkbox1">Remember Me</label>
                                </div>
                            </div> -->
                            <a class="link" href="forget-password.php">Forgot password/username?</a>
                            <p class="mt-2">Don't have an account?<a class="ms-2 lead" href="register.php">Register Now</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- page-wrapper end-->

    <?php require_once("scripts.php"); ?>

    <?php
    if ($msg != "") {
    ?>
        <script>
            showNotif("<?php echo $msg; ?>", "<?php echo $msg_type; ?>")
        </script>
    <?php
        exit;
    }
    ?>
</body>

</html>
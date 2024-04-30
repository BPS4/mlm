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

$auth_key = "72fede3cfdb461993f399b2ac0363e73";
$client_id = "78262";
$sender_id = "MTCLIN";

function generateRandomString($length = 10)
{
    // $characters = '0123456789abcdefghjkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ';
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function send_message($user_id, $name, $mobile, $password)
{
    $conn = $GLOBALS['conn'];
    $current_date = $GLOBALS['current_date'];
    $auth_key = $GLOBALS['auth_key'];
    $sender_id = $GLOBALS['sender_id'];

    $otp_type = "info";
    $request_for = "forget_password";

    $otp = generateRandomString(6);
    $query_insert = "INSERT INTO `register`(`request_for`, `otp_type`, `user_id`, `name`, `mobile`, `status`, `otp`, `otp_date`, `otp_verified`, `otp_verify_date`, `create_date`) VALUES ('$request_for', '$otp_type', '$user_id', '$name', '$mobile', 'verified', NULL, NULL, 1, '$current_date', '$current_date')";


    $res = explode(" ", $name, 2);
    $name_first = $res[0];

    $msg = "Your login username is $user_id and password is $password for M T Club. Do not share your password with anyone for your safety. mtclub.in";

    $curl = curl_init();

    // -----------senderId is Approved in msgclub.net Dashboard---------------
    //-----------routeId depends upon the CATEGORY OF MESSAGE We are SENDING viz. Transactional DND etc---------------
    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://msg.msgclub.net/rest/services/sendSMS/sendGroupSms?AUTH_KEY=$auth_key",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\"smsContent\":\"$msg\",\"groupId\":\" \",\"routeId\":\"1\",\"mobileNumbers\":\"$mobile\",\"senderId\":\"$sender_id\",\"signature\":\"signature\",\"smsContentType\":\"english\"}",
        CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($response == "") {
        return "error";
        exit;
    }

    $obj = json_decode($response);

    $responseCode = $obj->responseCode;
    if ($responseCode != "3001") {
        return "error";
        exit;
    }

    if ($err) {
        // echo "cURL Error #:" . $err;
        return "error";
    } else {
        // MSG SENT
        if (mysqli_query($conn, $query_insert)) {
            // GET LAST ID INSERTED
            $last_id_insert = mysqli_insert_id($conn);

            // $response['last_id_insert'] = $last_id_insert;
            // $response['mobile_verified'] = $mobile;

            if ($request_for == "mobile_verify") {
                return $last_id_insert;
            } else if ($request_for == "forget_password") {
                return "success";
            } else if ($request_for == "withdrawal") {
                return "success";
            }
        } else {
            return "error";
        }
        exit;
    }
}

$msg = $msg_type = "";
if (isset($_POST['btn_forget_password'])) {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);

    if (!filter_var($user_id, FILTER_VALIDATE_EMAIL) && $user_id != "maxizone") {
        $user_id = strtoupper($user_id);
    }

    $query = "SELECT `name`, `mobile`, `user_id`, `password` FROM `users` WHERE (`user_id` = '$user_id' OR `mobile` = '$user_id')";
    $query = mysqli_query($conn, $query);
    $count_rows = mysqli_num_rows($query);

    if ($count_rows > 0) {
        $res = mysqli_fetch_array($query);
        extract($res);
        $response = send_message($user_id, $name, $mobile, $password);
        if ($response == "success") {
            $msg = "Your Login Credentials have been sent!<br>Use them to <a href='login.php'>Login now</a>...";
            $msg_type = "success";
        } else {
            $msg = "Unable to send your Credentials!<br>Retry...";
            $msg_type = "error";
        }
    } else {
        $msg = "User ID/Mobile Number is Invalid!<br>Retry with Correct Details...";
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
        <div class="container-fluid p-0">
            <div class="row m-0">
                <div class="col-12 p-0">
                    <div class="login-card">
                        <div class="login-main">
                            <form class="theme-form login-form" action="" method="post">
                                <a href="../" class="lead">
                                    <img src="../assets/images/logo-new.png" class="rounded mb-3" style="width: 40px;"> Maxizone
                                </a>
                                <h4 class="mb-3">Forgot Username/Password?</h4>
                                <div class="form-group">
                                    <label>User ID / Registered Mobile</label>
                                    <div class="row">
                                        <div class="col-12">
                                            <input class="form-control" type="text" name="user_id" placeholder="Enter User ID / Mobile" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="btn_forget_password" value="Send" class="btn btn-primary btn-block">
                                </div>
                                <p>Remember your Login Details?<a class="ms-2" href="login.php">Log-in</a> Now</p>
                            </form>
                        </div>
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
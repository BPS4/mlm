<?php
session_start();
ob_start();

require_once("db_connect.php");
mysqli_query($conn, "set names 'utf8'");  //-------WORKING UTF8 CODE------//

//-------CURRENT DATE AND TIME TO FEED---------//
date_default_timezone_set('Asia/Kolkata');
$current_date = date('Y-m-d H:i:s');
$current_timestamp = time();
$current_day = date('D');
$current_datetimestart = date('H');
$current_date2 = date('d');
$auth_key = "72fede3cfdb461993f399b2ac0363e73";
$client_id = "78262";
$sender_id = "MTCLIN";
$firstdate= date('Y-m-d', strtotime('first day of previous month'));

$lastdate=date('Y-m-d', strtotime('last day of previous month'));

extract($_REQUEST);
// POST VARIABLES
$action;
$password;

$name="";
// if(isset($_SESSION['name_admin_diet'])){
//     $name = $_SESSION['name_admin_diet'];    //DECODE
// }

// CONVERSION=> $_POST['action']=> $action

if ($action == "get_sponsor") {
    $sponsor_id = mysqli_real_escape_string($conn,$_POST['sponsor_id']);
    if ($rw = mysqli_fetch_array(mysqli_query($conn, "SELECT `name` FROM `users` WHERE `user_id`='$sponsor_id' "))) {
		$name = $rw['name'];
        $response = $name;
	} else {
        $response = "NO RECORD";
    }
    echo $response;
} else if ($action == "check_exist") {
    $res = mysqli_query($conn,"SELECT `id` FROM `users` WHERE $type='$value'");
    $count_record = mysqli_num_rows($res);
    if ($count_record>0) {
        echo "EXIST";
    } else {
        echo "UNIQUE";
    }
} else if ($action == "register_user") {
    $sponsor_id = mysqli_real_escape_string($conn, $_POST['sponsor_id']);
    $sponsor_name = mysqli_real_escape_string($conn, $_POST['sponsor_name']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sponsor_id = trim(strtolower($sponsor_id));
    $sponsor_name = trim($sponsor_name);
    $name = trim($name);
    $email = trim(strtolower($email));
    $mobile = trim($mobile);
    
    // CHECK UNIQUE
        $count_record_email = mysqli_num_rows(mysqli_query($conn,"SELECT `id` FROM `users` WHERE `email`='$email'"));
        $count_record_mobile = mysqli_num_rows(mysqli_query($conn,"SELECT `id` FROM `users` WHERE `mobile`='$mobile'"));
        if ($count_record_mobile > 0) {
            echo "mobile_exist";
            exit;
        }
        if ($count_record_email > 0) {
            echo "email_exist";
            exit;
        }
    // CHECK UNIQUE

    $query_old_user = mysqli_query($conn, "SELECT `user_no` FROM `users` ORDER BY `user_no` DESC");
    if ($rw = mysqli_fetch_array($query_old_user)) {
        $user_no = $rw['user_no'];
 
        $user_no_new = $user_no+1;
        $user_id_new = sprintf("%06d", $user_no_new); // returns 04

        $user_id_new = "MX$user_id_new";

        // NEW USER ID AS MOBILE
        // $user_id_new = $mobile;
        
        $randomString = generateRandomUserID();

        // NEW USER ID AS RANDOM 6 DIGIT
        $user_id_new = $randomString;

        // PREFIX ZEROES ON LEFT
            // $num = 4;
            // $num_padded = sprintf("%06d", $num); // returns 04
            // $number = 45678;
            // echo(str_pad($number, 6, '0', STR_PAD_LEFT)); // returns 04
        // PREFIX ZEROES ON LEFT
        
    }

    // $levels_array = json_decode(get_sponsor_data('MX000001'));
    // print_r($levels_array->sponsor_id);
    // print_r($levels_array->sponsor_name);
    $query_insert = "INSERT INTO `users`(`sponsor_id`, `user_id`, `user_no`, `name`, `mobile`, `email`, `password`, `create_date`) VALUES ('$sponsor_id', '$user_id_new', '$user_no_new', '$name', '$mobile', '$email', '$password', '$current_date')";
    $query_insert_wallet = "INSERT INTO `wallets`(`user_id`, `create_date`) VALUES ('$user_id_new', '$current_date')";

    if (mysqli_query($conn, $query_insert)) {
        // INSERT INTO WALLETS
            mysqli_query($conn, $query_insert_wallet);
        // INSERT INTO WALLETS

        // GET MEMBER LEVEL IN UPLINE TILL LEVEL 24
            $level = 0;
            $levels_array[0] = $user_id_new;
            while ($level < 25) {
                $sp_id_obtained = get_level_members($conn,$level,$levels_array[$level]);
                $level++;
                if ($sp_id_obtained != "NULL") {
                    $levels_array[$level] = $sp_id_obtained;
                } else {
                    break;
                }
            }
        // GET MEMBER LEVEL IN UPLINE TILL LEVEL 5
        
        // GET DOWNLINE MEMBERS
            // $member_count_array = array();
        // GET DOWNLINE MEMBERS

        // goto skip_insert_level;

        // INSERT INTO LEVELS TABLE AND UPDATE THE DOWNLINE MEMBER COUNT
            $query_insert_level = "INSERT INTO `levels`(`level`, `user_id`, `user_id_up`, `wallet_amount_percent`, `commission_amount_percent`, `create_date`) VALUES ";
            $value_insert = "";

            // CHECK LEVEL MEMBER IS UPDATING OR NOT
                $set_level1 = $set_level2 = $set_level3 = $set_level4 = $set_level5 = 0;
            // CHECK LEVEL MEMBER IS UPDATING OR NOT

            $query_update = 
                "
                    UPDATE `users`
                ";

            $query_update_level1_member = 
                "
                    SET `level1_member` = CASE `user_id`
                ";

            $query_update_level2_member = 
                "
                    `level2_member` = CASE `user_id`
                ";

            $query_update_level3_member = 
                "
                    `level3_member` = CASE `user_id`
                ";

            $query_update_level4_member = 
                "
                    `level4_member` = CASE `user_id`
                ";

            $query_update_level5_member = 
                "
                    `level5_member` = CASE `user_id`
                ";

            $query_update_date = 
                "
                    `update_date` = CASE `user_id`
                ";

            foreach ($levels_array as $key => $value) {
                $user_level = $key;
                $user_id_up = $value;
                
                if ($user_id_up == "" || $user_level == 0 || $user_level > 24) {
                    continue;
                }
                
                // GET DOWNLINE MEMBERS
                    // array_push($member_count_array,get_downline_member($user_id_up));
                // GET DOWNLINE MEMBERS
        
                // switch ($user_level) {
                //     case '1':
                //         $wallet_amount_percent = "10";
                //         $commission_amount_percent = "4";

                //         $set_level1 = 1;

                //         $query_update_level1_member .= 
                //             "
                //                 WHEN '$user_id_up' THEN `level1_member`+1
                //             ";
                //         break;
                    
                //     case '2':
                //         $wallet_amount_percent = "5";
                //         $commission_amount_percent = "2";
                        
                //         $set_level2 = 1;
                        
                //         $query_update_level2_member .= 
                //             "
                //                 WHEN '$user_id_up' THEN `level2_member`+1
                //             ";
                //         break;

                //     case '3':
                //         $wallet_amount_percent = "2";
                //         $commission_amount_percent = "2";
                        
                //         $set_level3 = 1;
                        
                //         $query_update_level3_member .= 
                //             "
                //                 WHEN '$user_id_up' THEN `level3_member`+1
                //             ";
                //         break;
                    
                //     case '4':
                //         $wallet_amount_percent = "2";
                //         $commission_amount_percent = "1";
                        
                //         $set_level4 = 1;

                //         $query_update_level4_member .= 
                //             "
                //                 WHEN '$user_id_up' THEN `level4_member`+1
                //             ";
                //         break;
                    
                //     case '5':
                //         $wallet_amount_percent = "1";
                //         $commission_amount_percent = "1";
                        
                //         $set_level5 = 1;

                //         $query_update_level5_member .= 
                //             "
                //                 WHEN '$user_id_up' THEN `level5_member`+1
                //             ";
                //         break;
                // }
                
                if ($user_level == 1) {
                    $wallet_amount_percent = "5";
                    $commission_amount_percent = "1";
                } else if ($user_level >= 2 && $user_level <= 5) {
                    $wallet_amount_percent = "0";
                    $commission_amount_percent = "0.5";
                } else if ($user_level >= 6 && $user_level <= 9) {
                    $wallet_amount_percent = "0";
                    $commission_amount_percent = "0.25";
                } else if ($user_level >= 10 && $user_level <= 14) {
                    $wallet_amount_percent = "0";
                    $commission_amount_percent = "0.10";
                } else if ($user_level >= 15 && $user_level <= 24) {
                    $wallet_amount_percent = "0";
                    $commission_amount_percent = "0.05";
                }

                $query_update_date .= 
                    "
                        WHEN '$user_id_up' THEN '$current_date'
                    ";
                    
                // CHECK UNIQUE LEVEL ENTRY
                    $count_record = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `levels` WHERE `level`='$user_level' AND `user_id`='$user_id_new' AND `user_id_up`='$user_id_up'"));
                    if ($count_record == 0) {
                        $value_insert .= "('$user_level','$user_id_new','$user_id_up','$wallet_amount_percent','$commission_amount_percent','$current_date'),";
                    }
                // CHECK UNIQUE LEVEL ENTRY
            }

            if ($value_insert != "") {
                $value_insert = rtrim($value_insert,",");
                $query_insert_level .= "$value_insert";

                // INSERT AND UPDATE
                    if (mysqli_query($conn,$query_insert_level)) {
                        goto skip_update_members;

                            $query_update_level1_member .= 
                                "
                                    ELSE `level1_member`
                                    END,
                                ";
                            $query_update_level2_member .= 
                                "
                                    ELSE `level2_member`
                                    END,
                                ";
                            $query_update_level3_member .= 
                                "
                                    ELSE `level3_member`
                                    END,
                                ";
                            $query_update_level4_member .= 
                                "
                                    ELSE `level4_member`
                                    END,
                                ";
                            $query_update_level5_member .= 
                                "
                                    ELSE `level5_member`
                                    END,
                                ";
                            $query_update_date .= 
                                "
                                    ELSE `update_date`
                                    END
                                ";
                
                            $query_update = "$query_update";
                            if ($set_level1 == 1) {
                                $query_update .= "$query_update_level1_member";
                            }
                            if ($set_level2 == 1) {
                                $query_update .= "$query_update_level2_member";
                            }
                            if ($set_level3 == 1) {
                                $query_update .= "$query_update_level3_member";
                            }
                            if ($set_level4 == 1) {
                                $query_update .= "$query_update_level4_member";
                            }
                            if ($set_level5 == 1) {
                                $query_update .= "$query_update_level5_member";
                            }
                            $query_update .= "$query_update_date";
                
                            // UPDATE MEMBER COUNT
                                mysqli_query($conn, $query_update);
                            // UPDATE MEMBER COUNT
                            
                        skip_update_members:
                    }
                // INSERT AND UPDATE
            }
        // INSERT INTO LEVELS TABLE AND UPDATE THE DOWNLINE MEMBER COUNT
        
        skip_insert_level:
        
        // AFTER REGISTRATION >> GO FOR LOGIN
            $result = mysqli_query($conn, "SELECT * FROM `users` WHERE `user_id` = '$user_id_new' AND `is_active` = 1");
            if ($row = mysqli_fetch_array($result)) {
                $session_id = rand(1, 999999);
                $user_id = $row['user_id'];
                $name = $row['name'];
                $mobile = $row['mobile'];
                $email = $row['email'];
                $sponsor_id = $row['sponsor_id'];

                $_SESSION['user_id'] = $user_id;
                $_SESSION['sponsor_id'] = $sponsor_id;
                $_SESSION['session_id'] = $session_id;
                $_SESSION['name'] = $name;

                $user_id_encode = base64_encode(json_encode($user_id));     //ENCODE
                $name_encode = base64_encode(json_encode($name));     //ENCODE
                // $user_id = json_decode(base64_decode($_GET['user_id']));    //DECODE  

                // CREATE LOGIN SESSION
                    if (mysqli_query($conn, "INSERT INTO `login_sessions` (`unique_id`,`session_id`,`login_date`,`create_date`) VALUES ('$user_id_new','$session_id','$current_date','$current_date') ")) {
                        // echo "success";
                        $response['user_id'] = $user_id_new;
                        $response['name'] = $name;
                        $response['email'] = $email;
                        $response['password'] = $password;

                        $otp_type = "info";
                        $request_for = "registration";
                        send_message($otp_type,$name,$mobile,$request_for,$user_id,'');
                        
                        echo json_encode($response);
                        exit;
                    } else {
                        $msg = "session_error";
                        $msg_type = "warning";
                        echo $msg;
                    }
                // CREATE LOGIN SESSION
            } else {
                echo "error";
            }
        // AFTER REGISTRATION >> GO FOR LOGIN
    }
    exit;
} else if ($action == "change_password_user") {
    $user_id = $_SESSION['user_id'];
    //FOR UPDATE QUERIES
    $q_update = mysqli_query($conn, "UPDATE `users` SET `password`='$new_password', `update_date`='$current_date' WHERE `user_id`='$user_id' AND `password`='$current_password' ");
    $rows_affected = mysqli_affected_rows($conn);

    //FOR SELECT QUERIES
    // $res = mysqli_query($conn, "SELECT *");
    // $rows_affected = mysqli_num_rows($res);

    if ($rows_affected > 0) {
        $query_insert_password_change_log = "INSERT INTO `activity_log`(`user_id`, `action`, `activity`, `performed_by`, `create_date`) VALUES ('$user_id', 'password_change', 'Password Changed To: $new_password', '$user_id', '$current_date')";

        mysqli_query($conn, $query_insert_password_change_log);
        echo "Success";
    } else {
        echo "Error";

        // echo "Trainee is Not Eligible For Internship. Contact DIET For More Information.";
    }
} else if ($action == "save_lead") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    $name = trim($name);
    $email = trim(strtolower($email));
    $mobile = trim($mobile);
    $category = trim($category);
    $message = trim($message);

    $query_insert = "INSERT INTO `leads`(`category`, `name`, `mobile`, `email`, `message`, `create_date`) VALUES ('$category', '$name', '$mobile', '$email', '$message', '$current_date')";
    
    if (mysqli_query($conn, $query_insert)) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
} else if ($action == "generate_otp") {
    if (isset($_POST['name']) && isset($_POST['mobile']) && isset($_POST['otp_type'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
        $otp_type = mysqli_real_escape_string($conn, $_POST['otp_type']);
        
        $name = trim($name);
        $mobile = trim($mobile);
        $otp_type = trim($otp_type);
        $request_for = "mobile_verify";

        if ($otp_type != "fresh" && $otp_type != "resend") {
            echo "error";
            exit;
        }
        $user_id = "MOBILE_VERIFY";
        send_message($otp_type,$name,$mobile,$request_for,$user_id,'');
        exit;
    }
} else if ($action == "verify_otp") {
    if (isset($_POST['id']) && isset($_POST['otp']) && isset($_POST['mobile_verified'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $otp = mysqli_real_escape_string($conn, $_POST['otp']);
        $mobile_verified = mysqli_real_escape_string($conn, $_POST['mobile_verified']);
        
        $id = trim($id);
        $otp = trim($otp);
        $mobile_verified = trim($mobile_verified);

        $seconds_to_check = 300;

        $query = "SELECT `id`, TIMESTAMPDIFF(SECOND, `create_date`, '$current_date') AS 'seconds_created', `mobile` AS 'mobile_registered', `otp` AS 'otp_registered' FROM `register` WHERE `id`='$id'";
        $res = mysqli_fetch_array(mysqli_query($conn,$query));
        extract($res);
        
        if ($seconds_created > $seconds_to_check) {
            echo "time_over";
            exit;
        }
        if ($mobile_registered != $mobile_verified) {
            echo "mobile_error";
            exit;
        }
        if ($otp_registered != $otp) {
            echo "incorrect_otp";
            exit;
        }

        $query_update = "UPDATE `register` SET `status`='verified',`otp_verified`='1',`otp_verify_date`='$current_date',`update_date`='$current_date' WHERE `id`='$id'";
        mysqli_query($conn,$query_update);
        $count_row_affected = mysqli_affected_rows($conn);
        if ($count_row_affected > 0) {
            echo "verified";
        } else {
            echo "incorrect_otp";
        }
        exit;
    }
} else if ($action == "forget_password") {
    if (isset($_POST['type']) && isset($_POST['username'])) {
        $type = mysqli_real_escape_string($conn, $_POST['type']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        
        $type = trim($type);
        $username = trim($username);
        
        if ($type == "email") {
            $query_data = "SELECT `user_id`, `name`, `mobile`, `email`, `password` FROM `users` WHERE `email` LIKE '%$username%'";
        } else if ($type == "username") {
            $query_data = "SELECT `user_id`, `name`, `mobile`, `email`, `password` FROM `users` WHERE `mobile` LIKE '%$username%'";
        }

        $res = mysqli_query($conn,$query_data);
        $count_rows = mysqli_num_rows($res);
        if ($count_rows > 0) {
            $res = mysqli_fetch_array($res);
            extract($res);
        } else {
            echo "no_data";
            exit;
        }

        $otp_type = "info";
        $request_for = "forget_password";
        send_message($otp_type,$name,$mobile,$request_for,$user_id,'');
        exit;
    }
} else if ($action == "save_withdraw_request") {
    
    if (isset($_POST['user_id']) && isset($_POST['withdrawal_amount'])) {
        $user_id = $_POST['user_id'];
        $withdrawal_amount = $_POST['withdrawal_amount'];
        $admin_charges = $_POST['admin_charges'];
        $tds_charges = $_POST['tds_charges'];
        $total_withdrawal_amount = $_POST['total_withdrawal_amount'];

        $a = $withdrawal_amount%1000;
        $min_allowed_amount = 1000;
        $max_allowed_amount = +($withdrawal_amount - $a);
        
        // GET USER DATA
            $query = "SELECT `name`, `mobile` FROM `users` WHERE `user_id`='$user_id'";
            $query = mysqli_query($conn, $query);
            $res = mysqli_fetch_array($query);
            extract($res);
        // GET USER DATA

        // GET WALLET DATA
            $query_wallet = "SELECT `wallet_investment`, `wallet_roi`, `wallet_commission`, `wallet_fd`, `superwallet` FROM `wallets` WHERE `user_id`='$user_id'";
            $query = mysqli_query($conn, $query_wallet);
            $res = mysqli_fetch_array($query);
            extract($res);  
        // GET WALLET DATA

        

        // GET SELF INVESTMENT
            // $query_selfinvestment = "Select `transaction_amount` as `self_investement` from fund_transaction  where user_id = '$user_id' and transaction_type in ('fresh' , 'admin_credit', 'superwallet') and create_date >= '$firstdate' and create_date <=  '$lastdate';";
            // $queryselfinvestment = mysqli_query($conn, $query_selfinvestment);
            // $resselfinvestment = mysqli_fetch_array($queryselfinvestment);
            // extract($resselfinvestment);  
        // GET SELF INVESTMENT

        // echo'hellow';

        // GET LEVEL 1 INVESTMENT
        //     $query_level1investment = "SELECT sum(ifnull((SELECT`wallets`.`wallet_investment` FROM `wallets` WHERE `user_id`=`levels`.`user_id` and `wallets`.`create_date` >= '$firstdate'
        //     and `wallets`.`create_date` <=  '$lastdate'),0)) AS 'level1_investment' 
        //     FROM `levels` LEFT JOIN `users` ON `users`.`user_id`=`levels`.`user_id` LEFT JOIN `wallets` ON `wallets`.`user_id`=`levels`.`user_id_up` WHERE `levels`.`user_id_up`='$user_id' 
        //     and `levels`.`level` = 1 ORDER BY `levels`.`level` ASC";
        //     $querylevel1investment = mysqli_query($conn, $query_level1investment);
        //     $reslevel1investment = mysqli_fetch_array($querylevel1investment);
        //     extract($reslevel1investment);  
        // // GET LEVEL 1 INVESTMENT
        // $allowedwithdrawalamount = 0;
        // if($self_investement < $level1_investment){
        //     $allowedwithdrawalamount = $level1_investment *20 / 100;
        // }else{
        //     $allowedwithdrawalamount = $self_investement *20 / 100;
        // }

        // if ($wallet_investment == 0) {
        //     echo "not_active";
        //     exit;
        // }

        /*if ($superwallet == 0) {
            echo "not_active";
            exit;
        }*/

        // $kyc_done = 0;
        // $current_date2 = 16;
        //   $current_datetimestart = 13;


        if ($wallet_roi==0)
{
    echo "not_active";
    exit;
}

if (!(($current_date2 == 1 || $current_date2 == 16) && ($current_datetimestart >= 10 && $current_datetimestart <= 15))){
 echo "not_allowed";
            exit;
}


if ($withdrawal_amount > 1000000) {
    echo "fund_limit_exceed";
            exit;
}
// if ($current_day != "Fri") {
  //          echo "not_allowed";
    //        exit;
      //  }

        if ($withdrawal_amount > $max_allowed_amount) {
            echo "fund_exceed";
            exit;
        }
        
        $query_check_pending_withdrawal = "SELECT `withdrawal_amount` AS 'withdraw_amount_pending', `status`, `create_date`, `update_date` FROM `withdrawal` WHERE `user_id`='$user_id' AND `status`='pending' ORDER BY `create_date` DESC LIMIT 1";
        $res = mysqli_query($conn, $query_check_pending_withdrawal);
        $count_record = mysqli_num_rows($res);
        $res = mysqli_fetch_array($res);
        if ($count_record == 1) {
            echo "request_pending";
            exit;
        }

        $query_insert = "INSERT INTO `withdrawal`(`user_id`, `withdrawal_amount`, `create_date`, `admin_charges`, `tds`, `total_withdraw`, `from_wallet`) VALUES ('$user_id', '$withdrawal_amount', '$current_date', '$admin_charges', '$tds_charges', '$total_withdrawal_amount','ROI Wallet')";
        if (mysqli_query($conn,$query_insert)) {
            // $query_update_user_withdrawal = "UPDATE `users` SET `fund_withdrawn`=`fund_withdrawn`+'$withdrawal_amount', `update_date`='$current_date' WHERE `user_id`='$user_id'";
            // mysqli_query($conn,$query_update_user_withdrawal);

            $query_update_user_withdrawal = "UPDATE `wallets` SET `wallet_roi`=`wallet_roi`-'$withdrawal_amount', `update_date`='$current_date' WHERE `user_id`='$user_id'";
            mysqli_query($conn,$query_update_user_withdrawal);

            $msg = "Withdrawal Request For Rs.$withdrawal_amount Submitted Successfully";

            insert_notif($user_id,$msg);

            $otp_type = "info";
            $request_for = "withdrawal";
            
            send_message($otp_type,$name,$mobile,$request_for,$user_id,$withdrawal_amount);

            echo "success";
        }
    }
} else if ($action == "save_commission_withdraw_request") {
    if (isset($_POST['user_id']) && isset($_POST['withdrawal_amount'])) {
        $user_id = $_POST['user_id'];
        $withdrawal_amount = $_POST['withdrawal_amount'];
        $admin_charges_commission = $_POST['admin_charges_commission'];
        $tds_charges_commission = $_POST['tds_charges_commission'];
        $total_withdrawal_amount_commission = $_POST['total_withdrawal_amount_commission'];

        $a = $withdrawal_amount%1000;
        $min_allowed_amount = 1000;
        $max_allowed_amount = +($withdrawal_amount - $a);
        
        // GET USER DATA
            $query = "SELECT `name`, `mobile` FROM `users` WHERE `user_id`='$user_id'";
            $query = mysqli_query($conn, $query);
            $res = mysqli_fetch_array($query);
            extract($res);
        // GET USER DATA

        // GET WALLET DATA
            $query_wallet = "SELECT `wallet_investment`, `wallet_roi`, `wallet_commission`, `wallet_fd`, `superwallet` FROM `wallets` WHERE `user_id`='$user_id'";
            $query = mysqli_query($conn, $query_wallet);
            $res = mysqli_fetch_array($query);
            extract($res);  
        // GET WALLET DATA

        // GET SELF INVESTMENT
            // $query_selfinvestment = "Select `transaction_amount` as `self_investement` from fund_transaction  where user_id = '$user_id' and transaction_type in ('fresh' , 'admin_credit', 'superwallet') and create_date >= '$firstdate' and create_date <=  '$lastdate';";
            // $queryselfinvestment = mysqli_query($conn, $query_selfinvestment);
            // $resselfinvestment = mysqli_fetch_array($queryselfinvestment);
            // extract($resselfinvestment);  
        // GET SELF INVESTMENT

        // GET LEVEL 1 INVESTMENT
            // $query_level1investment = "SELECT sum(ifnull((SELECT`wallets`.`wallet_investment` FROM `wallets` WHERE `user_id`=`levels`.`user_id` and `wallets`.`create_date` >= '$firstdate'
            // and `wallets`.`create_date` <=  '$lastdate'),0)) AS 'level1_investment' 
            // FROM `levels` LEFT JOIN `users` ON `users`.`user_id`=`levels`.`user_id` LEFT JOIN `wallets` ON `wallets`.`user_id`=`levels`.`user_id_up` WHERE `levels`.`user_id_up`='$user_id' 
            // and `levels`.`level` = 1 ORDER BY `levels`.`level` ASC";
            // $querylevel1investment = mysqli_query($conn, $query_level1investment);
            // $reslevel1investment = mysqli_fetch_array($querylevel1investment);
            // extract($reslevel1investment);  
        // GET LEVEL 1 INVESTMENT
        // $allowedwithdrawalamount = 0;
        // if($self_investement < $level1_investment){
        //     $allowedwithdrawalamount = $level1_investment *20 / 100;
        // }else{
        //     $allowedwithdrawalamount = $self_investement *20 / 100;
        // }

        // if ($wallet_investment == 0) {
        //     echo "not_active";
        //     exit;
        // }

        // if ($superwallet == 0) {
        //     echo "not_active";
        //     exit;
        // }
if ($wallet_commission==0)
{
    echo "not_active";
    exit;
}

// $kyc_done = 0;
// $current_date2 = 16;
// $current_datetimestart = 12;


if (!(($current_date2 == 1 || $current_date2 == 16) && ($current_datetimestart >= 10 && $current_datetimestart <= 15))){
 echo "not_allowed";
            exit;
}

// if ($withdrawal_amount > $allowedwithdrawalamount) {
//     echo "fund_limit_exceed";
//             exit;
// }

// if ($current_day != "Fri") {
  //          echo "not_allowed";
    //        exit;
      //  }

        if ($withdrawal_amount > $max_allowed_amount) {
            echo "fund_exceed";
            exit;
        }
        
        $query_check_pending_withdrawal = "SELECT `withdrawal_amount` AS 'withdraw_amount_pending', `status`, `create_date`, `update_date` FROM `withdrawal` WHERE `user_id`='$user_id' AND `status`='pending' ORDER BY `create_date` DESC LIMIT 1";
        $res = mysqli_query($conn, $query_check_pending_withdrawal);
        $count_record = mysqli_num_rows($res);
        $res = mysqli_fetch_array($res);
        if ($count_record == 1) {
            echo "request_pending";
            exit;
        }

        $query_insert = "INSERT INTO `withdrawal`(`user_id`, `withdrawal_amount`, `create_date`, `admin_charges`, `tds`, `total_withdraw`, `from_wallet`) VALUES ('$user_id', '$withdrawal_amount', '$current_date', '$admin_charges_commission', '$tds_charges_commission', '$total_withdrawal_amount_commission','Comission Wallet')";
        if (mysqli_query($conn,$query_insert)) {
            // $query_update_user_withdrawal = "UPDATE `users` SET `fund_withdrawn`=`fund_withdrawn`+'$withdrawal_amount', `update_date`='$current_date' WHERE `user_id`='$user_id'";
            // mysqli_query($conn,$query_update_user_withdrawal);

            $query_update_user_withdrawal = "UPDATE `wallets` SET `wallet_commission`=`wallet_commission`-'$withdrawal_amount', `update_date`='$current_date' WHERE `user_id`='$user_id'";
            mysqli_query($conn,$query_update_user_withdrawal);

            $msg = "Withdrawal Request For Rs.$withdrawal_amount Submitted Successfully";

            insert_notif($user_id,$msg);

            $otp_type = "info";
            $request_for = "withdrawal";
            
            send_message($otp_type,$name,$mobile,$request_for,$user_id,$withdrawal_amount);

            echo "success";
        }
    }
}
else if ($action == "superwallet_transfer_request") {
    $tax_percent = 15;
    $tds_percent = 5;
    $service_charge_percent = 10;

    $query_invest = "";

    if (isset($_POST['user_id']) && isset($_POST['withdrawal_amount'])) {
        $user_id = $_POST['user_id'];
        $withdrawal_amount = $_POST['withdrawal_amount'];

        $amount_credit_to_superwallet = 0;

        // GET WALLET DATA
            $query_wallet = "SELECT `wallet_investment`, `wallet_roi`, `wallet_commission`, `wallet_fd`, `superwallet` FROM `wallets` WHERE `user_id`='$user_id'";
            $query = mysqli_query($conn, $query_wallet);
            $res = mysqli_fetch_array($query);
            extract($res);  
        // GET WALLET DATA
        
        // if ($wallet_investment == 0) {
        //     echo "not_active";
        //     exit;
        // }

        // if ($current_day != "Fri") {
        //     echo "not_allowed";
        //     exit;
        // }

        if ($wallet_roi <= 0 && $wallet_commission <= 0 && $wallet_investment <=0) {
            echo "fund_exceed";
            exit;
        }

        if ($withdrawal_amount > $wallet_roi+$wallet_commission+$wallet_investment) {
            echo "fund_exceed";
            exit;
        }

        $query_insert = "INSERT INTO `transaction_superwallet`(`from_wallet`, `user_id`, `transaction_amount`, `tds`, `service_charge`, `net_amount`, `status`, `create_date`) VALUES ";
        $value_insert = "";
        if ($withdrawal_amount <= $wallet_roi) {
            // r
            $withdrawal_amount = round($withdrawal_amount,2);

            // $set_wallet = "`wallet_roi` = `wallet_roi`-$withdrawal_amount";
            $set_wallet = "`wallet_roi` = IF((`wallet_roi`-$withdrawal_amount)<=0,0,ROUND((`wallet_roi`-$withdrawal_amount),2))";

            $tax = $withdrawal_amount*$tax_percent/100;
            $tax = number_format((float)$tax, 2, '.', '');

            $tds = $withdrawal_amount*$tds_percent/100;    //5% in 15%
            $service_charge = $withdrawal_amount*$service_charge_percent/100; //10% in 15%

            $tds = number_format((float)$tds, 2, '.', '');  //PHP NUMBER FORMAT DECIMAL VALUES WITHOUT COMMA
            $service_charge = number_format((float)$service_charge, 2, '.', '');

            $withdrawal_credit = $withdrawal_amount - $tax;
            $net_amount = $withdrawal_credit;

            $amount_credit_to_superwallet += $net_amount;

            if ($wallet_roi > 0) {
                $value_insert .= "('wallet_roi', '$user_id', '$withdrawal_amount', '$tds', '$service_charge', '$net_amount', '1', '$current_date')";
            }
        } else if ($withdrawal_amount >= $wallet_roi && $withdrawal_amount <= $wallet_roi+$wallet_commission) {
            // rc
            $wallet_roi = round($wallet_roi,2);
            $remaining_balance = $withdrawal_amount-$wallet_roi;
            $wallet_roi = round($wallet_roi,2);
            $remaining_balance = round($remaining_balance,2);

            // $set_wallet = "`wallet_roi` = `wallet_roi`-$wallet_roi, `wallet_commission` = `wallet_commission`-$remaining_balance";
            $set_wallet = "`wallet_roi` = IF((`wallet_roi`-$wallet_roi)<=0,0,ROUND((`wallet_roi`-$wallet_roi),2)), `wallet_commission` = IF((`wallet_commission`-$remaining_balance)<=0,0,ROUND((`wallet_commission`-$remaining_balance),2))";

            $tax = $wallet_roi*$tax_percent/100;
            $tax = number_format((float)$tax, 2, '.', '');

            $tds = $wallet_roi*$tds_percent/100;    //5% in 15%
            $service_charge = $wallet_roi*$service_charge_percent/100; //10% in 15%

            $tds = number_format((float)$tds, 2, '.', '');  //PHP NUMBER FORMAT DECIMAL VALUES WITHOUT COMMA
            $service_charge = number_format((float)$service_charge, 2, '.', '');

            $net_amount = $wallet_roi - $tax;

            if ($wallet_roi > 0) {
                $value_insert .= "('wallet_roi', '$user_id', '$wallet_roi', '$tds', '$service_charge', '$net_amount', '1', '$current_date'),";
            }

            $amount_credit_to_superwallet += $net_amount;
            
            $tax = $remaining_balance*$tax_percent/100;
            $tax = number_format((float)$tax, 2, '.', '');

            $tds = $remaining_balance*$tds_percent/100;    //5% in 15%
            $service_charge = $remaining_balance*$service_charge_percent/100; //10% in 15%

            $tds = number_format((float)$tds, 2, '.', '');  //PHP NUMBER FORMAT DECIMAL VALUES WITHOUT COMMA
            $service_charge = number_format((float)$service_charge, 2, '.', '');

            $net_amount = $remaining_balance - $tax;

            $amount_credit_to_superwallet += $net_amount;
            
            if ($wallet_commission > 0) {
                $value_insert .= "('wallet_commission', '$user_id', '$remaining_balance', '$tds', '$service_charge', '$net_amount', '1', '$current_date'),";
            }
        } else if ($withdrawal_amount > $wallet_roi+$wallet_commission && $withdrawal_amount <= $wallet_roi+$wallet_commission+$wallet_investment) {
            // rci
            $wallet_roi = round($wallet_roi,2);
            $wallet_commission = round($wallet_commission,2);
            $remaining_balance = $withdrawal_amount-$wallet_roi-$wallet_commission;
            $wallet_roi = round($wallet_roi,2);
            $wallet_commission = round($wallet_commission,2);
            $remaining_balance = round($remaining_balance,2);

            // $set_wallet = "`wallet_roi` = `wallet_roi`-$wallet_roi, `wallet_commission` = `wallet_commission`-$wallet_commission, `wallet_investment` = `wallet_investment`-$remaining_balance";
            $set_wallet = "`wallet_roi` = IF((`wallet_roi`-$wallet_roi)<=0,0,ROUND((`wallet_roi`-$wallet_roi),2)), `wallet_commission` = IF((`wallet_commission`-$wallet_commission)<=0,0,ROUND((`wallet_commission`-$wallet_commission),2)), `wallet_investment` = IF((`wallet_investment`-$remaining_balance)<=0,0,ROUND((`wallet_investment`-$remaining_balance),2))";
            
            $tax = $wallet_roi*$tax_percent/100;
            $tax = number_format((float)$tax, 2, '.', '');

            $tds = $wallet_roi*$tds_percent/100;    //5% in 15%
            $service_charge = $wallet_roi*$service_charge_percent/100; //10% in 15%

            $tds = number_format((float)$tds, 2, '.', '');  //PHP NUMBER FORMAT DECIMAL VALUES WITHOUT COMMA
            $service_charge = number_format((float)$service_charge, 2, '.', '');

            $net_amount = $wallet_roi - $tax;

            $amount_credit_to_superwallet += $net_amount;

            if ($wallet_roi > 0) {
                $value_insert .= "('wallet_roi', '$user_id', '$wallet_roi', '$tds', '$service_charge', '$net_amount', '1', '$current_date'),";
            }


            $tax = $wallet_commission*$tax_percent/100;

            $tax = number_format((float)$tax, 2, '.', '');

            $tds = $wallet_commission*$tds_percent/100;    //5% in 15%

            $service_charge = $wallet_commission*$service_charge_percent/100; //10% in 15%

            $tds = number_format((float)$tds, 2, '.', '');  //PHP NUMBER FORMAT DECIMAL VALUES WITHOUT COMMA

            $service_charge = number_format((float)$service_charge, 2, '.', '');

            $net_amount = $wallet_commission - $tax;

            $amount_credit_to_superwallet += $net_amount;

            if ($wallet_commission > 0) {
                $value_insert .= "('wallet_commission', '$user_id', '$wallet_commission', '$tds', '$service_charge', '$net_amount', '1', '$current_date'),";
            }
            
	    
            $tax = $remaining_balance*$tax_percent/100;
            $tax = number_format((float)$tax, 2, '.', '');

            $tds = $remaining_balance*$tds_percent/100;    //5% in 15%
            $service_charge = $remaining_balance*$service_charge_percent/100; //10% in 15%

            $tds = number_format((float)$tds, 2, '.', '');  //PHP NUMBER FORMAT DECIMAL VALUES WITHOUT COMMA
            $service_charge = number_format((float)$service_charge, 2, '.', '');

            $net_amount = $remaining_balance - $tax;

            $amount_credit_to_superwallet += $net_amount;

            if ($wallet_investment > 0) {
                $value_insert .= "('wallet_investment', '$user_id', '$remaining_balance', '$tds', '$service_charge', '$net_amount', '1', '$current_date'),";
                $query_invest = "INSERT INTO `fund_transaction`(`transaction_type`, `user_id`, `transaction_mode`, `transaction_amount`, `transaction_id`, `status`, `create_date`) VALUES ('withdraw', '$user_id', '1', '$remaining_balance', 'superwallet-withdraw', '1', '$current_date')";
            }
        }

        // if ($withdrawal_amount == $wallet_roi) {
            //     // only r
            //     $set_wallet = "`wallet_roi` = `wallet_roi`-$withdrawal_amount";
            //     echo "4 $set_wallet";
        // }
        
        // if ($withdrawal_amount == $wallet_commission) {
            //     // only c
            //     $set_wallet = "`wallet_commission` = `wallet_commission`-$withdrawal_amount";
            //     echo "5 $set_wallet";
        // }

        // if ($withdrawal_amount == $wallet_investment) {
            //     // only i
            //     $set_wallet = "`wallet_investment` = `wallet_investment`-$withdrawal_amount";
            //     echo "6 $set_wallet";
        // }

        if ($value_insert != "") {
		  $value_insert = rtrim($value_insert,",");
          $query_insert .= "$value_insert";
        
          $amount_credit_to_superwallet = round($amount_credit_to_superwallet,2);
          $set_wallet .= ", `superwallet` = ROUND((`superwallet`+$amount_credit_to_superwallet),2)";

            if (mysqli_query($conn,$query_insert)) {
                
                if ($query_invest != "") {
                    mysqli_query($conn,$query_invest);
                }

                $query_update_user_withdrawal = "UPDATE `wallets` SET $set_wallet, `update_date`='$current_date' WHERE `user_id`='$user_id'";
                mysqli_query($conn,$query_update_user_withdrawal);
                
                
                $msg = "Superwallet Fund Transfer Request For Rs.$withdrawal_amount Has Been Processed Successfully";

                insert_notif($user_id,$msg);

                $otp_type = "info";
                $request_for = "withdrawal";
                
                //send_message($otp_type,$name,$mobile,$request_for,$user_id,$withdrawal_amount);

                echo "success";
            }
        }
    }
} else if ($action == "superwallet_use_request") {
    if (isset($_POST['user_id']) && isset($_POST['withdrawal_amount']) && isset($_POST['transfer_action']) && isset($_POST['transfer_to'])) {
        $user_id = mysqli_escape_string($conn,$_POST['user_id']);
        $withdrawal_amount = mysqli_escape_string($conn,$_POST['withdrawal_amount']);
        $transfer_action = mysqli_escape_string($conn,$_POST['transfer_action']);
        $transfer_to = mysqli_escape_string($conn,$_POST['transfer_to']);

        $amount_credit_to_superwallet = 0;
        $query_invest = "";

        // GET WALLET DATA
            $query_wallet = "SELECT `wallet_investment`, `wallet_roi`, `wallet_commission`, `wallet_fd`, `superwallet` FROM `wallets` WHERE `user_id`='$user_id'";
            $query = mysqli_query($conn, $query_wallet);
            $res = mysqli_fetch_array($query);
            extract($res);  
        // GET WALLET DATA
        
        // if ($wallet_investment == 0) {
        //     echo "not_active";
        //     exit;
        // }

        // if ($current_day != "Fri") {
        //     echo "not_allowed";
        //     exit;
        // }

        $a = $superwallet%1000;
        $min_allowed_amount = 1000;
        $max_allowed_amount = +($superwallet - $a);
        
        if ($withdrawal_amount > $superwallet) {
            echo "fund_exceed";
            exit;
        }

        if ($withdrawal_amount%1000 != 0) {
            echo "multiple_required";
            exit;
        }

        $query_insert = "INSERT INTO `transaction_superwallet`(`from_wallet`, `user_id`, `from_user_id`, `to_user_id`, `transaction_mode`, `transaction_amount`, `net_amount`, `status`, `create_date`) VALUES ";
        $value_insert = "";
        
        if ($transfer_action == "transfer_member") {
            if (trim($transfer_to) == "") {
                echo "member_id_empty";
                exit;
            } else {
                $query_check_member = "SELECT `id` FROM `users` WHERE `user_id`='$transfer_to'";
                $query = mysqli_query($conn,$query_check_member);
                $count_member = mysqli_num_rows($query);

                if ($count_member == 0) {
                    echo "member_id_invalid";
                    exit;
                }
                
                $value_insert .= "('superwallet_transfer', '$transfer_to', '$user_id', NULL, 'credit', '$withdrawal_amount', '$withdrawal_amount', '1', '$current_date'),";
                $value_insert .= "('superwallet_transfer', '$user_id', '$user_id', '$transfer_to', 'debit', '$withdrawal_amount', '$withdrawal_amount', '1', '$current_date'),";
            }
        } else if ($transfer_action == "invest") {
            $value_insert .= "('superwallet_invest', '$user_id', NULL, NULL, 'debit', '$withdrawal_amount', '$withdrawal_amount', '1', '$current_date'),";

            $query_invest = "INSERT INTO `fund_transaction`(`transaction_type`, `user_id`, `transaction_mode`, `transaction_amount`, `transaction_id`, `status`, `create_date`) VALUES ('superwallet', '$user_id', '1', '$withdrawal_amount', 'superwallet-investment', '1', '$current_date')";
        } else {
            echo "invalid_action";
            exit;
        }

        if ($value_insert != "") {
		  $value_insert = rtrim($value_insert,",");
          $query_insert .= "$value_insert";
        
          $withdrawal_amount = round($withdrawal_amount,2);
          $set_wallet = "`superwallet` = `superwallet`-$withdrawal_amount";
		
            if (mysqli_query($conn,$query_insert)) {

                if ($transfer_action == "transfer_member") {
                    $query_update_user_withdrawal = "UPDATE `wallets` SET $set_wallet, `update_date`='$current_date' WHERE `user_id`='$user_id'";
                    mysqli_query($conn,$query_update_user_withdrawal);

                    $query_update_user_withdrawal = "UPDATE `wallets` SET `superwallet` = `superwallet`+$withdrawal_amount, `update_date`='$current_date' WHERE `user_id`='$transfer_to'";
                    mysqli_query($conn,$query_update_user_withdrawal);
                    
                    $msg = "Superwallet Fund (Rs.$withdrawal_amount) Has Been Successfully Transferred To Member ID $transfer_to";
                    insert_notif($user_id,$msg);

                    $msg = "Superwallet Fund (Rs.$withdrawal_amount) Has Been Credited From Member ID $user_id";
                    insert_notif($transfer_to,$msg);
                } else if ($transfer_action == "invest") {
                    $query_invest = "INSERT INTO `fund_transaction`(`transaction_type`, `user_id`, `transaction_mode`, `transaction_amount`, `transaction_id`, `status`, `create_date`) VALUES ('superwallet', '$user_id', '1', '$withdrawal_amount', 'superwallet-investment', '1', '$current_date')";

                    if (mysqli_query($conn,$query_invest)) {

                        $query_sponsor = "SELECT `users`.`user_id` AS 'donor_id', `sponsor_id` AS 'receiver_id', (SELECT `wallets`.`wallet_investment` FROM `wallets` WHERE `user_id`=`users`.`sponsor_id`) AS 'sponsor_investment' FROM `users` LEFT JOIN `wallets` ON `wallets`.`user_id`=`users`.`sponsor_id` WHERE `users`.`user_id`='$user_id'";
                        $query = mysqli_query($conn,$query_sponsor);
                        $res = mysqli_fetch_array($query);
                        extract($res);

                        $user_investment = $withdrawal_amount;

                        $query_update_user_withdrawal = "UPDATE `wallets` SET `wallet_investment` = `wallet_investment`+$withdrawal_amount, $set_wallet, `update_date`='$current_date' WHERE `user_id`='$user_id'";
                        $res = mysqli_query($conn,$query_update_user_withdrawal);
                        $count_record_update = mysqli_affected_rows($conn);
                        if ($count_record_update > 0) {            
                            // ACTIVE INVESTMENT FOR BOTH USER AND SPONSOR
                                if ($sponsor_investment != 0 && $user_investment != 0) {
                                    if (($sponsor_investment >= 151000) || ($sponsor_investment > $user_investment)) {
                                        $sponsor_commission = 0.05*$user_investment;
                                    } else {
                                        $sponsor_commission = 0.05*$sponsor_investment;
                                    }
                                    $sponsor_commission = round($sponsor_commission,2);

                                    $query_insert_commission = "INSERT INTO `wallet_transaction`(`transaction_type`, `transaction_mode`, `user_id`, `from_user_id`, `transaction_amount`, `updated_by`, `create_date`) VALUES ('sponsor_commission','credit','$receiver_id','$donor_id','$sponsor_commission','$donor_id','$current_date')";
                                    if (mysqli_query($conn,$query_insert_commission)) {
                                        $query_update_wallet = "UPDATE `wallets` SET `wallet_commission`=`wallet_commission`+$sponsor_commission, `update_date`='$current_date' WHERE `user_id` = '$receiver_id'";
                                        mysqli_query($conn,$query_update_wallet);
                                    }
                                }
                            // ACTIVE INVESTMENT FOR BOTH USER AND SPONSOR
                        }
                    }
                    $msg = "Superwallet Fund (Rs.$withdrawal_amount) Has Been Successfully Used For Your Investments";
                    insert_notif($user_id,$msg);
                }

                $otp_type = "info";
                $request_for = "withdrawal";
                
                //send_message($otp_type,$name,$mobile,$request_for,$user_id,$withdrawal_amount);

                echo "success";
            }
        }
    }
}


// FUNCTIONS
    function get_sponsor_data($user_id_member) {
        $conn = $GLOBALS['conn'];
        $query_get_data = "SELECT `user_id`,`name` FROM `users` WHERE `user_id` = (SELECT `sponsor_id` FROM `users` WHERE `user_id` LIKE '$user_id_member')";
        $res = mysqli_query($conn, $query_get_data);
        $response = array();
        if ($row = mysqli_fetch_array($res)) {
            $user_id = $row['user_id'];
            $name = $row['name'];

            $response["sponsor_id"] = $user_id;
            $response["sponsor_name"] = $name;

            return(json_encode($response));
        }
    }

    function get_level_members($conn,$level,$user_id_member) {
        $query_get_data = "SELECT `sponsor_id`,`name` FROM `users` WHERE `user_id`='$user_id_member'";
        $res = mysqli_query($conn, $query_get_data);
        if ($row = mysqli_fetch_array($res)) {
            $response['sponsor_id'] = $row['sponsor_id'];
            $response['name'] = $row['name'];
            $response['level'] = $level;
            
            $sponsor_id = $row['sponsor_id'];
        } else {
            $sponsor_id = "NULL";
        }
        
        return($sponsor_id);
    }

    function get_downline_member($user_id) {
        $conn = $GLOBALS['conn'];
        // $res = mysqli_query($conn, "SELECT `user_rank`,`level1_member`, `level2_member`, `level3_member`, `level4_member`, `level5_member` FROM `users` WHERE `user_id`='$user_id'");
        
        // ONLY GET ACTIVE IDs
        $res = mysqli_query($conn, "SELECT `user_rank`,`level1_member`, `level2_member`, `level3_member`, `level4_member`, `level5_member` FROM `users` WHERE `user_id`='$user_id' AND `status`='active'");
        if ($row = mysqli_fetch_array($res)) {
            $user_rank = $row['user_rank'];
            $level1_member = $row['level1_member'];
            $level2_member = $row['level2_member'];
            $level3_member = $row['level3_member'];
            $level4_member = $row['level4_member'];
            $level5_member = $row['level5_member'];

            $response['user_id'] = $user_id;
            $response['user_rank'] = $user_rank;
            $response['level1_member'] = $level1_member;
            $response['level2_member'] = $level2_member;
            $response['level3_member'] = $level3_member;
            $response['level4_member'] = $level4_member;
            $response['level5_member'] = $level5_member;

            // return (json_encode($response));
            return (($response));
        }
    }

    function generateRandomUserID($length = 6) {
        $conn = $GLOBALS['conn'];
        // $characters = '0123456789abcdefghjkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ';
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $query_check_exist = mysqli_query($conn,"SELECT `id` FROM `users` WHERE `user_id`='$randomString'");
        $count_exist = mysqli_num_rows($query_check_exist);

        if ($count_exist > 0) {
            generateRandomUserID();
            exit;
        }

        return $randomString;
    }

    function generateRandomString($length = 10) {
        // $characters = '0123456789abcdefghjkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ';
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function send_message($otp_type,$name,$mobile,$request_for,$user_id,$withdrawal_amount) {
        $conn = $GLOBALS['conn'];
        $current_date = $GLOBALS['current_date'];
        $auth_key = $GLOBALS['auth_key'];
        $sender_id = $GLOBALS['sender_id'];

        $otp = generateRandomString(6);
        $query_insert = "INSERT INTO `register`(`request_for`, `otp_type`, `user_id`, `name`, `mobile`, `status`, `otp`, `otp_date`, `otp_verified`, `otp_verify_date`, `create_date`) VALUES ('$request_for', '$otp_type', '$user_id', '$name', '$mobile', 'verified', NULL, NULL, 1, '$current_date', '$current_date')";

        //---------------- OTP MESSAGE SENDING STARTS-------------------
        if ($request_for == "mobile_verify") {
            // $msg = "OTP Verification Code is $otp for Your M T Club Registration. OTP is Valid for 5 minutes. Please do not share this OTP with anyone M T Club";
            $msg = "Mobile Verification Code is $otp for Your M T Club Registration. OTP is Valid for 5 minutes. Please do not share this OTP with anyone Maxizone Trade Club";
            
            $query_insert = "INSERT INTO `register`(`request_for`, `otp_type`, `name`, `mobile`, `otp`, `otp_date`, `create_date`) VALUES ('$request_for', '$otp_type', '$name', '$mobile', '$otp', '$current_date', '$current_date')";
        } else if ($request_for == "registration") {
            $query = "SELECT `name`, `mobile`, `user_id`, `password` FROM `users` WHERE `user_id`='$user_id'";
            $query = mysqli_query($conn, $query);
            $res = mysqli_fetch_array($query);
            extract($res);

            $res = explode(" ", $name, 2);
            $name_first = $res[0];

            $msg = "Thank you $name_first for your registration on M T Club. Your UserName:- $user_id and Password:- $password Keep this info safe for future login. mtclub.in";
        } else if ($request_for == "forget_password") {
            $query = "SELECT `name`, `mobile`, `user_id`, `password` FROM `users` WHERE `user_id`='$user_id'";
            $query = mysqli_query($conn, $query);
            $res = mysqli_fetch_array($query);
            extract($res);

            $res = explode(" ", $name, 2);
            $name_first = $res[0];

            $msg = "Your login username is $user_id and password is $password for M T Club. Do not share your password with anyone for your safety. mtclub.in";
        } else if ($request_for == "withdrawal") {
            $msg = "Your withdrawal request for Rs.$withdrawal_amount has been submitted successfully. It will be processed within 3 working days. M T Club";
        } else {
            echo "error";
            exit;
        }

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
            echo "error";
            exit;
        }
        
        $obj = json_decode($response);

        $responseCode = $obj->responseCode;
        if ($responseCode != "3001") {
            echo "error";
            exit;
        }

        if ($err) {
            // echo "cURL Error #:" . $err;
            echo "error";
        } else {
            // MSG SENT
            if (mysqli_query($conn, $query_insert)) {
                // GET LAST ID INSERTED
                $last_id_insert = mysqli_insert_id($conn);
    
                // $response['last_id_insert'] = $last_id_insert;
                // $response['mobile_verified'] = $mobile;
    
                if ($request_for == "mobile_verify") {
                    echo $last_id_insert;
                } else if ($request_for == "forget_password") {
                    echo "success";
                } else if ($request_for == "withdrawal") {
                    echo "success";
                }
            } else {
                echo "error";
            }
            exit;
        }
    }
    
    function get_sms_current_balance() {
        $curl = curl_init();
        $auth_key = $GLOBALS['auth_key'];
        $client_id = $GLOBALS['client_id'];
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://msg.msgclub.net/rest/services/sendSMS/getClientRouteBalance?AUTH_KEY=$auth_key&clientId=$client_id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            // CURLOPT_HTTPHEADER => array(
            //     'Cookie: JSESSIONID=A8FA45DF1EBAFD85046948FE9004A5D0.node3'
            // ),
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response);
        foreach ($response as $key => $value) {
            $userId = $value->userId;
            $balance = $value->balance;
            $displayRouteId = $value->displayRouteId;
            $displayRouteName = $value->displayRouteName;

            if ($displayRouteId == 1 && $displayRouteName == "Transactional sms" && $userId == $client_id) {
                return $balance;
            } else {
                continue;
            }
        }
    }

    function get_sms_total_log() {
        $curl = curl_init();
        $auth_key = $GLOBALS['auth_key'];
        $client_id = $GLOBALS['client_id'];
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://msg.msgclub.net/rest/services/transaction/transactionLog?AUTH_KEY=$auth_key",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            // CURLOPT_HTTPHEADER => array(
            //     'Cookie: JSESSIONID=F44CDB460C25DAB4A6481D2AABC94E06.node3'
            // ),
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $total_sms = 0;
        $response = json_decode($response);
        foreach ($response as $key => $value) {
            $balance = (float) $value->balance;
            $userNameFrom = $value->userNameFrom;
            $userIdT0 = $value->userIdT0;
            $routeName = $value->routeName;

            if ($routeName == "Transactional sms" && $userIdT0 == $client_id) {
                $total_sms += $balance;
            } else {
                continue;
            }
        }
        return $total_sms;
    }

    // $total_credit_sms = get_sms_total_log();
    // $current_available_sms = get_sms_current_balance();

    // echo $total_credit_sms;
    // echo "<hr>";
    // echo $current_available_sms;
    // echo "<hr>";
    // $used_message_server = $total_credit_sms-$current_available_sms;

    function db_used_sms() {
        $conn = $GLOBALS['conn'];
        $query = "SELECT COUNT(`id`) AS 'used_sms_db' FROM `register`";
        $res = mysqli_fetch_array(mysqli_query($conn,$query));
        extract($res);
        return $used_sms_db;
    }

    function db_sms_data() {
        $conn= $GLOBALS['conn'];
        $query = "SELECT `key_field`, `value_field` FROM `configuration` WHERE `delete_date` IS NULL";
        $res = mysqli_query($conn,$query);
        $records = mysqli_fetch_all($res,MYSQLI_ASSOC);

        $message_total = $message_used = 0;
        foreach ($records as $key => $value) {
            $key_field = $value['key_field'];
            $value_field = $value['value_field'];

            if ($key_field == "message_total") {
                $message_total += $value_field;
            }

            if ($key_field == "message_used") {
                $message_used += $value_field;
            }
        }
        echo $message_total;
        echo "<hr>";
        echo $message_used;
        echo "<hr>";
    }
?>

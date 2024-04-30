<!DOCTYPE html>
<html lang="en">

<?php
    ob_start();
    require_once("../db_connect.php");
    session_start();

    mysqli_query($conn, "set names 'utf8mb4'");  //-------WORKING UTF8 CODE FOR ALL EMOJIS------//

    //-------CURRENT DATE AND TIME TO FEED---------//
    date_default_timezone_set('Asia/Kolkata');
    $current_date = date('Y-m-d H:i:s');
    $today_date_only = date('Y-m-d');

    // check login status
        if (isset($_SESSION['session_id'])) {
            $user_id = $_SESSION['user_id'];
            $sponsor_id = $_SESSION['sponsor_id'];
            $session_id = $_SESSION['session_id'];
            $name = $_SESSION['name'];    //DECODE
            $name_encode = base64_encode(json_encode($name));     //ENCODE
            $user_id_encode = base64_encode(json_encode($user_id));     //ENCODE

            // $user_id = strtoupper($user_id);

            // USER AUTHENTICATION
                $session_id_pass = $_SESSION['session_id'];
                $query = mysqli_query($conn, "SELECT `unique_id`, `session_id` FROM `login_sessions` WHERE `unique_id`='$user_id' ORDER BY `create_date` DESC LIMIT 1");
                if ($row = mysqli_fetch_array($query)) {
                    $session_id = $row['session_id'];
                    // $name = $row['name'];

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
        } else {
            echo "<script>alert('You Are Not Logged In!!!');</script>";
            echo "Redirecting...Please Wait";
            header("Refresh:0, url=logout/");
            exit;
        }
    // check login status

    // DEFAULT
        $page_id_home = 1;
        $bank_update = $photo_update = $pan_update = $aadhaar_update = 0;
        $kyc_done = 0;
        
        $fresh_txn_added_to_company = 2;
        $transaction_needed = 0;

        $wallet_percent = $commission_percent = $salary_percent = $withdrawal_percent = $available_percent = $withdrawn_percent = $total_percent = "0%";
        $last_withdrawal = $total_withdrawal = $today_wallet = $today_commission = $today_total = $credit_salary = 0;
        $today_wallet_percent = $today_commission_percent = $today_salary_percent = $credit_salary_percent = "0%";

        $fund_available = 0;


        $bank_cheque_sel = $bank_passbook_sel = "";
        $bank_disabled = $aadhaar_disabled = $pan_disabled = $photo_disabled = "";
        
        $user_doc_status = "";

        $url_dir = "../assets/files/transaction";
        $url_dir_aadhaar = "../assets/files/aadhaar";
        $url_dir_pan = "../assets/files/pan";
        $url_dir_bank = "../assets/files/bank";
        $url_dir_photo = "../assets/files/photo";	
        
        $msg = $msg_type = "";
        $error = false;

        if ($user_id == "maxizone") {
            $bank_update = $photo_update = $pan_update = $aadhaar_update = 1;
            $fresh_txn_added_to_company = 1;
            $transaction_needed = 0;
            $kyc_done = 1;
            $user_doc_status = "approved";
        }
    // DEFAULT

    // FUNCTIONS
        function fetch_sponsor_name($conn, $sponsor_id) {
            $query_sp_name = mysqli_query($conn,"SELECT `name` FROM `users` WHERE `user_id`='$sponsor_id'");
            if($rw = mysqli_fetch_array($query_sp_name)) {
                $name = $rw['name'];
            }
            return $name;
        }

        function get_level_data_for_wallet($user_id) {
            $conn = $GLOBALS['conn'];
            $query_lvl_with_invest_amt = "SELECT `levels`.`level`,`levels`.`user_id` AS 'member_id',`users`.`status`,(SELECT `wallets`.`wallet_investment` FROM `wallets` WHERE `user_id`=`levels`.`user_id`) AS 'member_investment' FROM `levels` LEFT JOIN `users` ON `users`.`user_id`=`levels`.`user_id` LEFT JOIN `wallets` ON `wallets`.`user_id`=`levels`.`user_id_up` WHERE `levels`.`user_id_up`='$user_id' ORDER BY `levels`.`level` ASC";
            $res = mysqli_query($conn, $query_lvl_with_invest_amt);
            $records = mysqli_fetch_all($res, MYSQLI_ASSOC);
            return $records;
        }
    // FUNCTIONS

		$level_array = get_level_data_for_wallet($user_id);

        for ($k=1; $k <=24 ; $k++) { 
            // echo 'if ($user_level == '.$k.') {
            //     $r = "level'.$k.'";
            //     $$r++;
            //     if ($member_investment != 0) {
            //         $s = "level_active'.$k.'";
            //         $$s++;
            //     } else {
            //         $t = "level_inactive'.$k.'";
            //         $$t++;
            //     }
            // } else ';
            $r = "level$k";
            $$r = 0;

            $s = "level_active$k";
            $$s = 0;

            $t = "level_inactive$k";
            $$t = 0;

            $investmentlevelamout = "level_investment$k";
            $$investmentlevelamout = 0;
        }

        $total_member = $total_active = $total_inactive = 0;
        $j = 0;
        foreach ($level_array as $row) {
            $user_level = $row['level'];
            $member_id = $row['member_id'];	//ACTIVE FETCHED
            $member_investment = $row['member_investment'];
            
            // NO USER LEVEL DATA
                if ($user_level == "" || $user_level > 24) {
                    continue;
                }
            // NO USER LEVEL DATA
            
            $total_member++;

            // NO INVESTMENT BY ANY PARTY
                if ($member_investment == 0) {
                    $total_inactive++;
                } else {
                    $total_active++;
                }
            // NO INVESTMENT BY ANY PARTY

            // if ($user_level == 1) { $r = "level1"; $$r++; } else if ($user_level == 2) { $r = "level2"; $$r++; } else if ($user_level == 3) { $r = "level3"; $$r++; } else if ($user_level == 4) { $r = "level4"; $$r++; } else if ($user_level == 5) { $r = "level5"; $$r++; } else if ($user_level == 6) { $r = "level6"; $$r++; } else if ($user_level == 7) { $r = "level7"; $$r++; } else if ($user_level == 8) { $r = "level8"; $$r++; } else if ($user_level == 9) { $r = "level9"; $$r++; } else if ($user_level == 10) { $r = "level10"; $$r++; } else if ($user_level == 11) { $r = "level11"; $$r++; } else if ($user_level == 12) { $r = "level12"; $$r++; } else if ($user_level == 13) { $r = "level13"; $$r++; } else if ($user_level == 14) { $r = "level14"; $$r++; } else if ($user_level == 15) { $r = "level15"; $$r++; } else if ($user_level == 16) { $r = "level16"; $$r++; } else if ($user_level == 17) { $r = "level17"; $$r++; } else if ($user_level == 18) { $r = "level18"; $$r++; } else if ($user_level == 19) { $r = "level19"; $$r++; } else if ($user_level == 20) { $r = "level20"; $$r++; } else if ($user_level == 21) { $r = "level21"; $$r++; } else if ($user_level == 22) { $r = "level22"; $$r++; } else if ($user_level == 23) { $r = "level23"; $$r++; } else if ($user_level == 24) { $r = "level24"; $$r++; }

            // if ($user_level == 1) { $r = "level1"; $$r++; if ($member_investment == 0) { $s = "level_active1"; $$s++; } else { $s = "level_inactive1"; $$s++; } } else if ($user_level == 2) { $r = "level2"; $$r++; if ($member_investment == 0) { $s = "level_active2"; $$s++; } else { $s = "level_inactive2"; $$s++; } } else if ($user_level == 3) { $r = "level3"; $$r++; if ($member_investment == 0) { $s = "level_active3"; $$s++; } else { $s = "level_inactive3"; $$s++; } } else if ($user_level == 4) { $r = "level4"; $$r++; if ($member_investment == 0) { $s = "level_active4"; $$s++; } else { $s = "level_inactive4"; $$s++; } } else if ($user_level == 5) { $r = "level5"; $$r++; if ($member_investment == 0) { $s = "level_active5"; $$s++; } else { $s = "level_inactive5"; $$s++; } } else if ($user_level == 6) { $r = "level6"; $$r++; if ($member_investment == 0) { $s = "level_active6"; $$s++; } else { $s = "level_inactive6"; $$s++; } } else if ($user_level == 7) { $r = "level7"; $$r++; if ($member_investment == 0) { $s = "level_active7"; $$s++; } else { $s = "level_inactive7"; $$s++; } } else if ($user_level == 8) { $r = "level8"; $$r++; if ($member_investment == 0) { $s = "level_active8"; $$s++; } else { $s = "level_inactive8"; $$s++; } } else if ($user_level == 9) { $r = "level9"; $$r++; if ($member_investment == 0) { $s = "level_active9"; $$s++; } else { $s = "level_inactive9"; $$s++; } } else if ($user_level == 10) { $r = "level10"; $$r++; if ($member_investment == 0) { $s = "level_active10"; $$s++; } else { $s = "level_inactive10"; $$s++; } } else if ($user_level == 11) { $r = "level11"; $$r++; if ($member_investment == 0) { $s = "level_active11"; $$s++; } else { $s = "level_inactive11"; $$s++; } } else if ($user_level == 12) { $r = "level12"; $$r++; if ($member_investment == 0) { $s = "level_active12"; $$s++; } else { $s = "level_inactive12"; $$s++; } } else if ($user_level == 13) { $r = "level13"; $$r++; if ($member_investment == 0) { $s = "level_active13"; $$s++; } else { $s = "level_inactive13"; $$s++; } } else if ($user_level == 14) { $r = "level14"; $$r++; if ($member_investment == 0) { $s = "level_active14"; $$s++; } else { $s = "level_inactive14"; $$s++; } } else if ($user_level == 15) { $r = "level15"; $$r++; if ($member_investment == 0) { $s = "level_active15"; $$s++; } else { $s = "level_inactive15"; $$s++; } } else if ($user_level == 16) { $r = "level16"; $$r++; if ($member_investment == 0) { $s = "level_active16"; $$s++; } else { $s = "level_inactive16"; $$s++; } } else if ($user_level == 17) { $r = "level17"; $$r++; if ($member_investment == 0) { $s = "level_active17"; $$s++; } else { $s = "level_inactive17"; $$s++; } } else if ($user_level == 18) { $r = "level18"; $$r++; if ($member_investment == 0) { $s = "level_active18"; $$s++; } else { $s = "level_inactive18"; $$s++; } } else if ($user_level == 19) { $r = "level19"; $$r++; if ($member_investment == 0) { $s = "level_active19"; $$s++; } else { $s = "level_inactive19"; $$s++; } } else if ($user_level == 20) { $r = "level20"; $$r++; if ($member_investment == 0) { $s = "level_active20"; $$s++; } else { $s = "level_inactive20"; $$s++; } } else if ($user_level == 21) { $r = "level21"; $$r++; if ($member_investment == 0) { $s = "level_active21"; $$s++; } else { $s = "level_inactive21"; $$s++; } } else if ($user_level == 22) { $r = "level22"; $$r++; if ($member_investment == 0) { $s = "level_active22"; $$s++; } else { $s = "level_inactive22"; $$s++; } } else if ($user_level == 23) { $r = "level23"; $$r++; if ($member_investment == 0) { $s = "level_active23"; $$s++; } else { $s = "level_inactive23"; $$s++; } } else if ($user_level == 24) { $r = "level24"; $$r++; if ($member_investment == 0) { $s = "level_active24"; $$s++; } else { $s = "level_inactive24"; $$s++; } }

            if ($user_level == 1) { $r = "level1"; $$r++; if ($member_investment != 0) { $s = "level_active1"; $$s++; $investmentlevelamout = "level_investment1"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive1"; $$t++;  } } 
            else if ($user_level == 2) { $r = "level2"; $$r++; if ($member_investment != 0) { $s = "level_active2"; $$s++;  $investmentlevelamout = "level_investment2"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive2"; $$t++; } }
            else if ($user_level == 3) { $r = "level3"; $$r++; if ($member_investment != 0) { $s = "level_active3"; $$s++;  $investmentlevelamout = "level_investment3"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive3"; $$t++; } } 
            else if ($user_level == 4) { $r = "level4"; $$r++; if ($member_investment != 0) { $s = "level_active4"; $$s++;  $investmentlevelamout = "level_investment4"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive4"; $$t++; } } 
            else if ($user_level == 5) { $r = "level5"; $$r++; if ($member_investment != 0) { $s = "level_active5"; $$s++;  $investmentlevelamout = "level_investment5"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive5"; $$t++; } } 
            else if ($user_level == 6) { $r = "level6"; $$r++; if ($member_investment != 0) { $s = "level_active6"; $$s++;  $investmentlevelamout = "level_investment6"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive6"; $$t++; } } 
            else if ($user_level == 7) { $r = "level7"; $$r++; if ($member_investment != 0) { $s = "level_active7"; $$s++;  $investmentlevelamout = "level_investment7"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive7"; $$t++; } } 
            else if ($user_level == 8) { $r = "level8"; $$r++; if ($member_investment != 0) { $s = "level_active8"; $$s++;  $investmentlevelamout = "level_investment8"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive8"; $$t++; } } 
            else if ($user_level == 9) { $r = "level9"; $$r++; if ($member_investment != 0) { $s = "level_active9"; $$s++;  $investmentlevelamout = "level_investment9"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive9"; $$t++; } } 
            else if ($user_level == 10) { $r = "level10"; $$r++; if ($member_investment != 0) { $s = "level_active10"; $$s++;  $investmentlevelamout = "level_investment10"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive10"; $$t++; } } 
            else if ($user_level == 11) { $r = "level11"; $$r++; if ($member_investment != 0) { $s = "level_active11"; $$s++;  $investmentlevelamout = "level_investment11"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive11"; $$t++; } } 
            else if ($user_level == 12) { $r = "level12"; $$r++; if ($member_investment != 0) { $s = "level_active12"; $$s++;  $investmentlevelamout = "level_investment12"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive12"; $$t++; } } 
            else if ($user_level == 13) { $r = "level13"; $$r++; if ($member_investment != 0) { $s = "level_active13"; $$s++;  $investmentlevelamout = "level_investment13"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive13"; $$t++; } } 
            else if ($user_level == 14) { $r = "level14"; $$r++; if ($member_investment != 0) { $s = "level_active14"; $$s++;  $investmentlevelamout = "level_investment14"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive14"; $$t++; } } 
            else if ($user_level == 15) { $r = "level15"; $$r++; if ($member_investment != 0) { $s = "level_active15"; $$s++;  $investmentlevelamout = "level_investment15"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive15"; $$t++; } } 
            else if ($user_level == 16) { $r = "level16"; $$r++; if ($member_investment != 0) { $s = "level_active16"; $$s++;  $investmentlevelamout = "level_investment16"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive16"; $$t++; } } 
            else if ($user_level == 17) { $r = "level17"; $$r++; if ($member_investment != 0) { $s = "level_active17"; $$s++;  $investmentlevelamout = "level_investment17"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive17"; $$t++; } } 
            else if ($user_level == 18) { $r = "level18"; $$r++; if ($member_investment != 0) { $s = "level_active18"; $$s++;  $investmentlevelamout = "level_investment18"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive18"; $$t++; } } 
            else if ($user_level == 19) { $r = "level19"; $$r++; if ($member_investment != 0) { $s = "level_active19"; $$s++;  $investmentlevelamout = "level_investment19"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive19"; $$t++; } } 
            else if ($user_level == 20) { $r = "level20"; $$r++; if ($member_investment != 0) { $s = "level_active20"; $$s++;  $investmentlevelamout = "level_investment20"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive20"; $$t++; } } 
            else if ($user_level == 21) { $r = "level21"; $$r++; if ($member_investment != 0) { $s = "level_active21"; $$s++;  $investmentlevelamout = "level_investment21"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive21"; $$t++; } } 
            else if ($user_level == 22) { $r = "level22"; $$r++; if ($member_investment != 0) { $s = "level_active22"; $$s++;  $investmentlevelamout = "level_investment22"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive22"; $$t++; } } 
            else if ($user_level == 23) { $r = "level23"; $$r++; if ($member_investment != 0) { $s = "level_active23"; $$s++;  $investmentlevelamout = "level_investment23"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive23"; $$t++; } } 
            else if ($user_level == 24) { $r = "level24"; $$r++; if ($member_investment != 0) { $s = "level_active24"; $$s++;  $investmentlevelamout = "level_investment24"; $$investmentlevelamout = $$investmentlevelamout + $member_investment; } else { $t = "level_inactive24"; $$t++; } }
            // print_r("$user_level >> {$level3} >> $member_id >> $member_investment");
            // echo "<hr>L:$user_level >> T:{$$r} >> A:{$$s} >> I:{$$t} >> {$member_investment}<hr>";
            // CHECK ACTIVE ST>ATUS OF DONOR ID
                $query = "SELECT `user_id` FROM `users` WHERE `user_id`='$member_id' AND `status`='active'";
                $res = mysqli_query($conn,$query);
                $count_status = mysqli_num_rows($res);
                if ($count_status == 0) {
                    // continue;
                }
            // CHECK ACTIVE STATUS OF DONOR ID            
        }

        // echo "
        //     $total_member = $total_active = $total_inactive
        // ";

    // GET USER DATA
        $query = "SELECT `sponsor_id`, `user_id`, `user_rank`, `name`, `mobile`, `email`, `aadhaar`, `aadhaar_file`, `pan`, `pan_file`, `bank_name`, `branch_name`, `account_no`, `ifs_code`, `upi_handle`, `address`, `city`, `state` AS 'state_name', `pin_code`, `fund_wallet`, `fund_commission`, `fund_salary`, `fund_total`, `fund_withdrawn`, `status`, `is_pan_updated`, `is_aadhaar_updated`, `is_photo_updated`, `is_bank_updated`, `level1_member`, `level2_member`, `level3_member`, `level4_member`, `level5_member`, `level1_member_active`, `level2_member_active`, `level3_member_active`, `level4_member_active`, `level5_member_active`, `create_date` AS 'joining_date', `active_date` AS 'activation_date' FROM `users` WHERE `user_id`='$user_id'";
        $query = mysqli_query($conn, $query);
        $res = mysqli_fetch_array($query);
        extract($res);
        
        //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT
            $regEx = '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/';
            // preg_match($regEx, $details['date'], $result);
            if (preg_match($regEx, $joining_date) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $joining_date)) {
                $date = date_create($joining_date);
                $user_create_date = date_format($date, "d M Y h:ia");
                $joining_date = date_format($date, "d-M-Y");
            }
        //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT
        
        //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT
            $regEx = '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/';
            // preg_match($regEx, $details['date'], $result);
            if (preg_match($regEx, $activation_date) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $activation_date)) {
                $date = date_create($joining_date);
                $activation_date = date_format($date, "d-M-Y");
            } else if ($activation_date == "") {
                $activation_date = "Pending";
            }
        //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT

        $user_name = $name;

        // $query_doc = "SELECT `id`, `user_id`, `doc_type`, `doc_number`, `doc_file`, `doc_file2`, `status`, `create_date` FROM `user_document` WHERE `user_id`='$user_id'";
        // SQL SELECT LATEST RECORD FOR EACH GROUP >> GROUP WISE DATA PICK 11-09-2022
            // https://bigboxcode.com/sql-tips-select-latest-record-for-each-group
            
        // USE IN CASE OF SQL MODE FULL_GROUP_BY ERROR >> SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
        $query_doc = "SELECT ud.* FROM `user_document` ud INNER JOIN (SELECT `doc_type`, MAX(`create_date`) AS max_date FROM `user_document` WHERE `user_id`='$user_id' GROUP BY `doc_type`) group_data ON ud.`doc_type`=group_data.`doc_type` AND ud.`create_date`=group_data.max_date;";
        $res = mysqli_query($conn,$query_doc);
        $docs = mysqli_fetch_all($res,MYSQLI_ASSOC);
    // GET USER DATA

    $wallet_investment = $wallet_roi = $wallet_commission = $wallet_fd = 0;

    // GET WALLET DATA
        $query_wallet = "SELECT `wallet_investment`, `wallet_roi`, `wallet_commission`, `wallet_fd`, `superwallet` FROM `wallets` WHERE `user_id`='$user_id'";
        $query = mysqli_query($conn, $query_wallet);
        $res = mysqli_fetch_array($query);
        extract($res);  
    // GET WALLET DATA

    if ($sponsor_id != "") {
        $sponsor_name = fetch_sponsor_name($conn, $sponsor_id);
    } else {
        $sponsor_name = $name;
        $sponsor_id = $user_id;
    }

    $member_status = "<span class='btn btn-xs btn-success'>ACTIVE</span>";

    if ($wallet_investment <= 0) {
        $member_status = "<span class='btn btn-xs btn-danger'>INACTIVE</span>";
    }

    $today_income = 0;
    $query_todaye_income = "SELECT IF(SUM(`transaction_amount`) IS NULL, 0, SUM(`transaction_amount`)) AS 'today_transaction' FROM `wallet_transaction` WHERE `user_id`='$user_id' AND `status`=1 AND `create_date` LIKE '%$today_date_only%'";
    $query = mysqli_query($conn,$query_todaye_income);
    $res = mysqli_fetch_array($query);
    extract($res);
    $today_income += $today_transaction;
    
    $active_percent = $inactive_percent = 0;
    $self_invest_percent = $super_invest_percent = 0;
    $superwallet_received = 0;
    // CREDIT DEBIT SUMMARY
        $invest_credit = $invest_debit = $roi_credit = $roi_debit = $commission_credit = $commission_debit = $superwallet_credit = $superwallet_debit = 0;
        $invest_credit_percent = $invest_debit_percent = $roi_credit_percent = $roi_debit_percent = $commission_credit_percent = $commission_debit_percent = $superwallet_credit_percent = $superwallet_debit_percent = 0;

        $query_invest = "SELECT IF(SUM(`transaction_amount`) IS NULL,0,SUM(`transaction_amount`)) AS 'invest_credit' FROM `fund_transaction` WHERE `user_id`='$user_id' AND `status`=1 AND `transaction_type` IN ('admin_credit','fresh','superwallet')";
        $query = mysqli_query($conn,$query_invest);
        $res = mysqli_fetch_array($query);
        extract($res);

        $query_invest = "SELECT IF(SUM(`transaction_amount`) IS NULL,0,SUM(`transaction_amount`)) AS 'invest_debit' FROM `fund_transaction` WHERE `user_id`='$user_id' AND `status`=1 AND `transaction_type` IN ('admin_debit','withdraw')";
        $query = mysqli_query($conn,$query_invest);
        $res = mysqli_fetch_array($query);
        extract($res);

        $query_roi = "SELECT IF(SUM(`transaction_amount`) IS NULL,0,SUM(`transaction_amount`)) AS 'roi_credit' FROM `wallet_transaction` WHERE `user_id`='$user_id' AND `status`=1 AND `transaction_type`='roi'";
        $query = mysqli_query($conn,$query_roi);
        $res = mysqli_fetch_array($query);
        extract($res);

        $query_roi = "SELECT IF(SUM(`transaction_amount`) IS NULL,0,SUM(`transaction_amount`)) AS 'roi_debit' FROM `transaction_superwallet` WHERE `user_id`='$user_id' AND `status`=1 AND `from_wallet`='wallet_roi'";
        $query = mysqli_query($conn,$query_roi);
        $res = mysqli_fetch_array($query);
        extract($res);

        $query_commission = "SELECT IF(SUM(`transaction_amount`) IS NULL,0,SUM(`transaction_amount`)) AS 'commission_credit' FROM `wallet_transaction` WHERE `user_id`='$user_id' AND `status`=1 AND `transaction_type` IN ('sponsor_commission','level_commission')";
        $query = mysqli_query($conn,$query_commission);
        $res = mysqli_fetch_array($query);
        extract($res);

        $query_commission = "SELECT IF(SUM(`transaction_amount`) IS NULL,0,SUM(`transaction_amount`)) AS 'commission_debit' FROM `transaction_superwallet` WHERE `user_id`='$user_id' AND `status`=1 AND `from_wallet`='wallet_commission'";
        $query = mysqli_query($conn,$query_commission);
        $res = mysqli_fetch_array($query);
        extract($res);

        $query_superwallet = "SELECT IF(SUM(`transaction_amount`) IS NULL,0,SUM(`transaction_amount`)) AS 'superwallet_credit' FROM `transaction_superwallet` WHERE `user_id`='$user_id' AND `status`=1 AND `from_wallet` IN ('wallet_investment', 'wallet_roi', 'wallet_commission', 'wallet_fd', 'superwallet_transfer') AND `transaction_mode`='credit'";
        $query = mysqli_query($conn,$query_superwallet);
        $res = mysqli_fetch_array($query);
        extract($res);

        $query_superwallet = "SELECT IF(SUM(`transaction_amount`) IS NULL,0,SUM(`transaction_amount`)) AS 'superwallet_debit' FROM `transaction_superwallet` WHERE `user_id`='$user_id' AND `status`=1 AND `from_wallet`='superwallet_transfer' AND `transaction_mode`='debit'";
        $query = mysqli_query($conn,$query_superwallet);
        $res = mysqli_fetch_array($query);
        extract($res);
        

        $query_withdraw = "SELECT IF(SUM(`withdrawal_amount`) IS NULL,0,SUM(`withdrawal_amount`)) AS 'total_withdrawal' FROM `withdrawal` WHERE `user_id`='$user_id' AND `status`!='rejected'";
        $query = mysqli_query($conn,$query_withdraw);
        $res = mysqli_fetch_array($query);
        extract($res);
        
        $query_self_invest = "SELECT IF(SUM(`transaction_amount`) IS NULL,0,SUM(`transaction_amount`)) AS 'total_self_invest' FROM `fund_transaction` WHERE `user_id`='$user_id' AND `status`=1 AND (`transaction_type`='fresh' OR `transaction_type`='admin_credit')";
        $query = mysqli_query($conn,$query_self_invest);
        $res = mysqli_fetch_array($query);
        extract($res);
        
        $query_self_invest = "SELECT IF(SUM(`transaction_amount`) IS NULL,0,SUM(`transaction_amount`)) AS 'total_superwallet_invest' FROM `fund_transaction` WHERE `user_id`='$user_id' AND `status`=1 AND `transaction_type`='superwallet'";
        $query = mysqli_query($conn,$query_self_invest);
        $res = mysqli_fetch_array($query);
        extract($res);

        $superwallet_transfer = $superwallet_debit;

        $query_superwallet = "SELECT IF(SUM(`transaction_amount`) IS NULL,0,SUM(`transaction_amount`)) AS 'superwallet_received' FROM `transaction_superwallet` WHERE `user_id`='$user_id' AND `status`=1 AND `from_wallet` IN ('superwallet_transfer') AND `transaction_mode`='credit'";
        $query = mysqli_query($conn,$query_superwallet);
        $res = mysqli_fetch_array($query);
        extract($res);
    // CREDIT DEBIT SUMMARY

    if ($invest_credit>0 || $invest_debit>0) {
        $invest_credit_percent = $invest_credit/($invest_credit+$invest_debit);
        $invest_credit_percent = round($invest_credit_percent*100,2);
        $invest_debit_percent = $invest_debit/($invest_credit+$invest_debit);
        $invest_debit_percent = round($invest_debit_percent*100,2);
    }
    
    if ($roi_credit>0 || $roi_debit>0) {
        $roi_credit_percent = $roi_credit/($roi_credit+$roi_debit);
        $roi_credit_percent = round($roi_credit_percent*100,2);
        $roi_debit_percent = $roi_debit/($roi_credit+$roi_debit);
        $roi_debit_percent = round($roi_debit_percent*100,2);
    }

    if ($commission_credit>0 || $commission_debit>0) {
        $commission_credit_percent = $commission_credit/($commission_credit+$commission_debit);
        $commission_credit_percent = round($commission_credit_percent*100,2);
        $commission_debit_percent = $commission_debit/($commission_credit+$commission_debit);
        $commission_debit_percent = round($commission_debit_percent*100,2);
    }
    
    if ($superwallet_credit>0 || $superwallet_debit>0) {
        $superwallet_credit_percent = $superwallet_credit/($superwallet_credit+$superwallet_debit);
        $superwallet_credit_percent = round($superwallet_credit_percent*100,2);
        $superwallet_debit_percent = $superwallet_debit/($superwallet_credit+$superwallet_debit);
        $superwallet_debit_percent = round($superwallet_debit_percent*100,2);
    }

    if ($total_active>0 || $total_inactive>0) {
        $active_percent = $total_active/($total_active+$total_inactive);
        $active_percent = round($active_percent*100,2);
        $inactive_percent = $total_inactive/($total_active+$total_inactive);
        $inactive_percent = round($inactive_percent*100,2);
    }
    
    if ($total_self_invest>0 || $total_superwallet_invest>0) {
        $self_invest_percent = $total_self_invest/($total_self_invest+$total_superwallet_invest);
        $self_invest_percent = round($self_invest_percent*100,2);
        $super_invest_percent = $total_superwallet_invest/($total_self_invest+$total_superwallet_invest);
        $super_invest_percent = round($super_invest_percent*100,2);
    }
    
    // echo "<hr>";
    // echo $superwallet_credit;
    // echo "<hr>";
    // echo $superwallet_debit;

    // echo "<hr>";
    // echo $invest_credit;
    // echo "<hr>";
    // echo $invest_debit;
    // echo "<hr>";
    // echo $roi_credit;
    // echo "<hr>";
    // echo $roi_debit;

    // INDIVIDUAL KYC STATUS
        $kyc_status = "<span class='btn btn-xs btn-warning'>PENDING</span>";
        // GET DATA OF SAME CATEGORY BY ONLY LAST CREATE DATE
        // $query_doc = "SELECT ud.* FROM `user_document` ud INNER JOIN (SELECT `doc_type`, MAX(`create_date`) AS max_date FROM `user_document` WHERE `user_id`='$member_id' GROUP BY `doc_type`) group_data ON ud.`doc_type`=group_data.`doc_type` AND ud.`create_date`=group_data.max_date";
        $query_doc = "SELECT * FROM `user_document` WHERE `user_id`='$user_id' ORDER BY `create_date` ASC";
        $res = mysqli_query($conn,$query_doc);
        $docs = mysqli_fetch_all($res,MYSQLI_ASSOC);
        $count_docs_uploaded = mysqli_num_rows($res);
        
        $aadhaar_update = $pan_update = $bank_update = $photo_update = $kyc_done = 0;
        
        $i = 0;
        $count_docs = $count_docs_approved = $count_docs_rejected = 0;
        $aadhaar_approved = $pan_approved = $cheque_approved = $passbook_approved = $address_approved = $photo_approved = 0;
        $aadhaar_rejected = $pan_rejected = $cheque_rejected = $passbook_rejected = $address_rejected = $photo_rejected = 0;
        $aadhaar_pending = $pan_pending = $cheque_pending = $passbook_pending = $address_pending = $photo_pending = 0;
        $aadhaar_needed = $pan_needed = $bank_needed = $cheque_needed = $passbook_needed = $photo_needed = $personal_needed = 0;
        $aadhaar_readonly = $pan_readonly = $bank_readonly = $cheque_readonly = $passbook_readonly = $photo_readonly = $personal_readonly = "readonly";        
        
        if ($count_docs_uploaded == 0) {
            $aadhaar_needed = $pan_needed = $bank_needed = $cheque_needed = $passbook_needed = $photo_needed = 1;
        }
        
        $aadhar_back = "";
        $aadhaar_error = $pan_error = $bank_error = $photo_error = "";
            
        foreach ($docs as $item) {
            $i++;
            
            // DATA
                $user_doc_id = $item['id'];
                $user_doc_type = $item['doc_type'];
                $user_doc_number = $item['doc_number'];
                $user_doc_file = $item['doc_file'];
                $user_doc_file2 = $item['doc_file2'];
                $user_doc_status = $item['status'];
                $comment = $item['comment'];
                $create_date = $item['create_date'];
            // DATA
            
            if ($user_doc_status == "approved") {
                $count_docs_approved++;
            } else if ($user_doc_status == "rejected") {
                $count_docs_rejected++;
            }
            
            
            switch ($user_doc_type) {
                case 'aadhaar':
                    $aadhaar_update = 1;
                    $aadhaar = $user_doc_number;
                    $photo_path1 = "$url_dir_aadhaar/$user_doc_file";
                    $photo_path2 = "$url_dir_aadhaar/$user_doc_file2";
                    $aadhaar_doc_front = "$url_dir_aadhaar/$user_doc_file";
                    $aadhaar_doc_back = "$url_dir_aadhaar/$user_doc_file2";
                    $file_text = "Aadhaar Front";
                    $aadhar_back = "<br>
                                <span class='badge bg-warning text-uppercase' style='white-space: normal;' data-bs-toggle='modal' data-bs-target='#ModalShowDoc' id='img-$user_doc_id' data-src='$photo_path2'>
                                    Aadhaar Back
                                </span>";
                    $class_doc_type = "success";
                    $aadhaar_disabled = "disabled";
                    $aadhaar_status = $user_doc_status;
                    
                    $count = $user_doc_type."_".$user_doc_status;
                    $$count++;
                    $aadhaar_error = $comment;
                    break;
                
                case 'pan':
                    $pan_update = 1;
                    $pan = $user_doc_number;
                    $photo_path1 = "$url_dir_pan/$user_doc_file";
                    $pan_doc = "$url_dir_pan/$user_doc_file";
                    $file_text = "PAN";
                    $class_doc_type = "warning";
                    $pan_disabled = "disabled";
                    $pan_status = $user_doc_status;
                    
                    $count = $user_doc_type."_".$user_doc_status;
                    $$count++;
                    $pan_error = $comment;
                    break;
                
                case 'cheque':
                    $bank_update = 1;
                    $account_no = $user_doc_number;
                    $photo_path1 = "$url_dir_bank/$user_doc_file";
                    $bank_doc = "$url_dir_bank/$user_doc_file";
                    $bank_cheque_sel = "selected";
                    $file_text = "Cancelled Cheque";
                    $class_doc_type = "primary";
                    $bank_disabled = "disabled";
                    $bank_status = $user_doc_status;
                    
                    $count = $user_doc_type."_".$user_doc_status;
                    $$count++;
                    $bank_error = $comment;
                    break;
                
                case 'passbook':
                    $bank_update = 1;
                    $account_no = $user_doc_number;
                    $photo_path1 = "$url_dir_bank/$user_doc_file";
                    $bank_doc = "$url_dir_bank/$user_doc_file";
                    $bank_passbook_sel = "selected";
                    $file_text = "Passbook";
                    $class_doc_type = "primary";
                    $bank_disabled = "disabled";
                    $bank_status = $user_doc_status;
                    
                    $count = $user_doc_type."_".$user_doc_status;
                    $$count++;
                    $bank_error = $comment;
                    break;
                
                case 'address':
                    $address_update = 1;
                    $file_text = "Address";
                    $class_doc_type = "danger";
                    $address_disabled = "disabled";
                    $address_status = $user_doc_status;
                    
                    $count = $user_doc_type."_".$user_doc_status;
                    $$count++;
                    $address_error = $comment;
                    break;
                    
                case 'photo':
                    $photo_update = 1;
                    $photo_path1 = "$url_dir_photo/$user_doc_file";
                    $photo = "$url_dir_photo/$user_doc_file";
                    $file_text = "Photo";
                    $class_doc_type = "danger";
                    $photo_disabled = "disabled";
                    $photo_status = $user_doc_status;
                    
                    $count = $user_doc_type."_".$user_doc_status;
                    $$count++;
                    $photo_error = $comment;
                    break;
                
                default:
                    $aadhaar_update = 
                    $pan_update = 
                    $bank_update = 
                    $photo_update = 0;
                    $class_doc_type = "info";
                    break;                    
            }                
        }

        if ($aadhaar_approved > 0) {
            $aadhaar_needed = 0;
        } else if ($aadhaar_pending == 0) {
            $aadhaar_needed = 1;
            $aadhaar_readonly = "";
        }

        if ($pan_approved > 0) {
            $pan_needed = 0;
        } else if ($pan_pending == 0) {
            $pan_needed = 1;
            $pan_readonly = "";
        }

        if (($cheque_approved > 0) || ($passbook_approved > 0)) {
            $bank_needed = 0;
        } else if (($cheque_pending == 0) && ($passbook_pending == 0)) {
            $bank_needed = 1;
            $bank_readonly = "";
        }

        if ($photo_approved > 0) {
            $photo_needed = 0;
        } else if ($photo_pending == 0) {
            $photo_needed = 1;
            $photo_readonly = "";
        }

        if ($address == "" || $address == NULL) {
            $personal_needed = 1;
            $personal_readonly = "";
        }

        // if ($user_doc_status == "approved") {
        // } else if ($user_doc_status == "rejected") {
        //     $kyc_status = "<span class='btn btn-xs btn-danger'>REJECTED</span>";
        // } else {
        // }

        // if ($count_docs_approved > 3) {
        // if ($pan_approved > 0 && $aadhaar_approved > 0 && ($cheque_approved > 0 || $passbook_approved > 0)) {
        if ($pan_approved > 0 && $aadhaar_approved > 0 && $address_approved > 0 && ($cheque_approved > 0 || $passbook_approved > 0) && $photo_approved > 0) {
            $kyc_done = 1;
            $kyc_status = "<span class='btn btn-xs btn-success'>APPROVED</span>";
        } else {
            $kyc_status = "<span class='btn btn-xs btn-info'>PENDING</span>";
        }
    
        // if ($aadhaar_update == 1 && $pan_update == 1) {
        //     $kyc_done = 1;
        // }

        // $aadhaar_update
        // $pan_update
        // $bank_update
        // $bank_update
        // $photo_update
    // INDIVIDUAL KYC STATUS

    if ($user_id == "maxizone") {
        $bank_update = $photo_update = $pan_update = $aadhaar_update = 1;
        $fresh_txn_added_to_company = 1;
        $transaction_needed = 0;
        $kyc_done = 1;
        $user_doc_status = "approved";
        $kyc_status = "<span class='btn btn-xs btn-success'>APPROVED</span>";
    }

    // LIVE GRAPHS FOR ACTIVATION AND EARNINGS
        $date_today = date('Y-m-d'); //today date
        $weekOfdays = array();
        $weekOfdays[0] = $date_today;
        for($i = 6; $i >= 1; $i--){
            $date_today = date('Y-m-d', strtotime('-1 day', strtotime($date_today)));
            // $weekOfdays[] = date('l : Y-m-d', strtotime($date));
            $weekOfdays[$i] = date('Y-m-d', strtotime($date_today));
        }
        asort($weekOfdays);
        // echo '<p>Previous 7 days from the current date are as shown below</p>';
        // print_r($weekOfdays);

        $query = "SELECT `user_id`  FROM `levels` WHERE `user_id_up` LIKE '$user_id'";
        $res = mysqli_query($conn,$query);
        // $ids = mysqli_fetch_all($res,MYSQLI_ASSOC);
        $ids = mysqli_fetch_all($res,MYSQLI_ASSOC);
        $user_id_activation = "";
        foreach ($ids as $id) {
            $uid = $id['user_id'];
            $user_id_activation .= "$uid,";
        }
        
        $data_graph_activation = $data_graph_earning = $day_graph_earning = $day_graph_activation = "";
        foreach($weekOfdays as $date){
            $date_to_fetch = $date;
            
            if ($user_id_activation != "") {
                $user_id_activation = rtrim($user_id_activation,",");

                $query_fetch_activation = "SELECT `user_id` FROM `users` WHERE `user_id` IN ($user_id_activation) AND `create_date` LIKE '%$date_to_fetch%'";
                $res = mysqli_query($conn,$query_fetch_activation);

                $count_activated = mysqli_num_rows($res);

                $data_graph_activation .= "$count_activated,";
            }

            $query_fetch_activation = "SELECT SUM(`transaction_amount`) AS 'data_earning' FROM `wallet_transaction` WHERE `user_id`='$user_id' AND `status`=1 AND `create_date` LIKE '%$date_to_fetch%'";
            $res = mysqli_query($conn,$query_fetch_activation);
            $res = mysqli_fetch_array($res);
            extract($res);
            
            // $query_today_income = "SELECT IF(SUM(`transaction_amount`) IS NULL, 0, SUM(`transaction_amount`)) AS 'data_earning' FROM `wallet_transaction` WHERE `user_id`='$user_id' AND `create_date` LIKE '%$date_to_fetch%'";
            // $query = mysqli_query($conn,$query_today_income);
            // $res = mysqli_fetch_array($query);
            // extract($res);

            if ($data_earning != "") {
                $data_earning = round($data_earning,2);
                $data_graph_earning .= "$data_earning,";
            } else {
                $data_graph_earning .= "0,";
            }

            $day = date('D', strtotime($date_to_fetch));

            //CHECK DATE FOR YYYY-MM-DD FORMAT
                if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date_to_fetch)) {
                    $date_show = date_create($date_to_fetch);
                    $date_show = date_format($date_show, "d M");
                }
            //CHECK DATE FOR YYYY-MM-DD FORMAT

            $day_graph_activation .= "['$day', '$date_show'],"; //  MORE TEXT IN NEW LINE
            $day_graph_earning .= "['$day', '$date_show'],"; //  MORE TEXT IN NEW LINE
            // $day_graph_earning .= "'$day',";    // NORMAL WAY
        }
        
        $data_graph_activation = ($data_graph_activation == "0,0,0,0,0,0,0,")?"":$data_graph_activation;
        $data_graph_earning = ($data_graph_earning == "0,0,0,0,0,0,0,")?"":$data_graph_earning;

        // $data_graph_earning = "50, 100, 25, 90, 159, 259, 350";
        // $data_graph_activation = "0, 0, 25, 90, 159, 259, 350";
        // $day_graph_earning = '"M", "T", "W", "T", "F", "S", "S",';
        // $day_graph_activation = '"M", "T", "W", "T", "F", "S", "S",';

        $colors_graph_earning = 
            '
                config.colors_label.success,
                config.colors_label.success,
                config.colors_label.success,
                config.colors_label.success,
                config.colors_label.success,
                config.colors_label.success,
                config.colors.success,
            ';
        $colors_graph_activation = 
            '
                config.colors_label.warning,
                config.colors_label.warning,
                config.colors_label.warning,
                config.colors_label.warning,
                config.colors_label.warning,
                config.colors_label.warning,
                config.colors.success,
            ';
        $colors_graph_activation = 
            '
                "#383CC1",
                "#383CC1",
                "#383CC1",
                "#383CC1",
                "#383CC1",
                "#383CC1",
                "#120E43",
            ';
        $colors_graph_earning = 
            '
                "#50DBB4",
                "#50DBB4",
                "#50DBB4",
                "#50DBB4",
                "#50DBB4",
                "#50DBB4",
                "#00D84A",
            ';

        $xaxis_style_graph_activation = "colors: '#120E43', fontSize: '14.5px', fontFamily:'georgia', fontWeight:'bold', cssClass: 'apexcharts-xlabel-custom'";
        $yaxis_style_graph_activation = "colors: '#120E43', fontSize: '14px', fontFamily:'georgia', fontWeight:'bold'";
        
        $xaxis_style_graph_earning = "colors: '#120E43', fontSize: '14.5px', fontFamily:'georgia', fontWeight:'bold', cssClass: 'apexcharts-xlabel-custom'";
        $yaxis_style_graph_earning = "colors: '#120E43', fontSize: '12px', fontFamily:'georgia', fontWeight:'bold'";
    // LIVE GRAPHS FOR ACTIVATION AND EARNINGS
?>

<head>
    <title>Maxizone - Your Growth Partner - Member Dashboard - <?php echo "$name" ?></title>
    <?php require_once("head.php"); ?>
</head>

<body>
    <div class="page-wrapper compact-wrapper" id="pageWrapper">

        <?php require_once("navbar.php"); ?>

        <!-- Page Body Start-->
        <div class="page-body-wrapper">
            <?php require_once("sidebar.php"); ?>

            <div class="page-body pb-5">
                <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <h3>User Dashboard</h3>
                            </div>
                            <div class="col-12 col-sm-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a class="home-item" href="./">
                                            <i data-feather="home"></i>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active"> Dashboard</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid default-dash">
                    <div class="row"> 
                        <div class="col-xl-6 col-md-6 dash-xl-50">
                            <div class="card profile-greeting">
                                <div class="card-body">
                                    <div class="media">
                                    <div class="media-body">
                                        <div class="greeting-user">
                                            <h1>Welcome back,<br><?php echo $name; ?></h1>
                                            <!-- <p>Welcome back, Continue Earning 💰</p> -->
                                            <p>Stay Active To Earn More and Grow With Us💰</p>
                                            <a class="btn btn-outline-white_color d-none" href="view_transaction.php">
                                                Get Started
                                                <!-- <i class="icofont icofont-circled-right"></i> -->
                                                <i class="icofont icofont-stylish-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="cartoon-img"><img class="img-fluid" src="assets/images/images.svg" alt=""></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 dash-xl-50">
                            <div class="card pb-0 o-hidden earning-card">
                                <div class="card-header earning-back"></div>
                                <div class="card-body p-1">
                                    <div class="earning-content pt-2">
                                        <!-- <img class="img-fluid" src="assets/images/avatar.jpg" alt=""> -->
                                        <img src="<?php echo $user_profile; ?>" alt="" style="width:90px; height:90px; border-radius:50%; z-index:999 !important; position:relative;" class="img-fluid shadow">
                                        <a class="" href="view_investment.php">
                                            <h4>Today's Earning</h4>
                                        </a>
                                        <span class="d-none">(Mon 15 - Sun 21)</span>
                                    <h6 class="">
                                        ₹ <?php echo number_format($today_income,2); ?>                                        
                                    </h6>
                                    <div id="earning-chart" class="d-none"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6 dash-xl-50">
                            <div class="card gradient-radar text-white p-3">
                                <div class="card-header p-1 ps-2 pt-2 gradient-radar">
                                    <medium class="card-title text-bold d-none">Actions</medium>
                                </div>
                                <div class="card-body p-2 pt-1">
                                    <ul class="p-0 m-0">
                                        <li class="mb-1 pb-1 d-flex justify-content-between align-items-center">
                                            <div class="badge bg-label-success rounded p-1"><i class="icofont icofont-user-alt-7"></i></div>
                                            <div class="d-flex justify-content-between w-100 flex-wrap">
                                                <h6 class="mb-0 ms-3">User ID</h6>
                                                <div class="d-flex">
                                                    <p class="mb-0 fw-semibold"><?php echo $user_id; ?></p>
                                                    <p class="ms-3 text-warning mb-0" onclick="copyToClipboard('<?php echo $user_id; ?>');"><i class="icofont icofont-copy-black"></i></p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="mb-1 pb-1 d-flex justify-content-between align-items-center">
                                            <div class="badge bg-label-info rounded p-1"><i class="icofont icofont-business-man-alt-3"></i></div>
                                            <div class="d-flex justify-content-between w-100 flex-wrap">
                                                <h6 class="mb-0 ms-3">Sponsor</h6>
                                                <div class="d-flex">
                                                    <p class="mb-0 fw-semibold"><?php echo "$sponsor_id-$sponsor_name"; ?></p>
                                                    <!-- <p class="ms-3 text-success mb-0" onclick="copyToClipboard('<?php echo $sponsor_id; ?>');"><i class="icofont icofont-copy-black"></i></p> -->
                                                </div>
                                            </div>
                                        </li>
                                        <li class="mb-1 pb-1 d-flex justify-content-between align-items-center">
                                            <div class="badge bg-label-warning rounded p-1"><i class="icofont icofont-calendar"></i></div>
                                            <div class="d-flex justify-content-between w-100 flex-wrap">
                                                <h6 class="mb-0 ms-3 text-white">Joining Date</h6>
                                                <div class="d-flex">
                                                    <p class="mb-0 fw-semibold"><?php echo $joining_date; ?></p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="mb-1 pb-1 d-flex justify-content-between align-items-center">
                                            <div class="badge bg-label-warning rounded p-1"><i class="icofont icofont-info-circle"></i></div>
                                            <div class="d-flex justify-content-between w-100 flex-wrap">
                                                <h6 class="mb-0 ms-3 text-white">Status</h6>
                                                <div class="d-flex">
                                                    <p class="mb-0 fw-semibold"><?php echo $member_status; ?></p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="mb-1 pb-1 d-flex justify-content-between align-items-center">
                                            <div class="badge bg-label-warning rounded p-1"><i class="icofont icofont-info-circle"></i></div>
                                            <div class="d-flex justify-content-between w-100 flex-wrap">
                                                <h6 class="mb-0 ms-3 text-white">KYC Status</h6>
                                                <div class="d-flex">
                                                    <p class="mb-0 fw-semibold"><?php echo $kyc_status; ?></p>
                                                </div>
                                            </div>
                                        </li>
                                        <!-- <li class="mb-1 pb-1 d-flex justify-content-between align-items-center">
                                            <div class="badge bg-label-warning rounded p-1"><i class="ti ti-calendar ti-sm"></i></div>
                                            <div class="d-flex justify-content-between w-100 flex-wrap">
                                                <h6 class="mb-0 ms-3 text-white">Activated On</h6>
                                                <div class="d-flex">
                                                    <p class="mb-0 fw-semibold"><?php echo $activation_date; ?></p>
                                                </div>
                                            </div>
                                        </li> -->
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6 dash-xl-50">
                            <div class="card border-2 border-color-purple p-2 pt-5 pb-5 gradient-summerdog">
                                <!-- <a href="https://api.whatsapp.com/send?text=<?php echo $text_share; ?>" target="_blank">Share</a> -->
                                <div class="d-flex justify-content-between">
                                    <medium class="d-block mb-1 text-bold text-white">Referral Link</medium>
                                    <div class="card-icon">
                                        <a href="whatsapp://send?text=<?php echo $text_share_whatsapp; ?>" data-action="share/whatsapp/share" class="text-primary rounded-pill p-1"><i class="text-green icofont icofont-social-whatsapp"></i>Share</a>
                                    </div>
                                </div>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control bg-white" placeholder="Referral Link" aria-label="Referral Link" aria-describedby="referral_link" value="<?php echo $referral_link_text; ?>" readonly disabled/>
                                    <span class="input-group-text btn btn-label-success" id="referral_link" onclick="copyToClipboard('<?php echo $text_share; ?>');"><i class="icofont icofont-copy-black"></i></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- INCOME SLABS -->
                            <div class="col-xl-6 col-md-6 dash-xl-50">
                                <div class="ribbon-wrapper card shadow">
                                    <div class="card-body">
                                        <div class="ribbon ribbon-clip ribbon-secondary shadow text-bold">Investment</div>
                                        <div class="media static-widget mb-3">
                                            <div class="media-body">
                                                <h6 class="font-roboto">Active Investment</h6>
                                                <h4 class="mb-0 counter">
                                                    ₹ <?php echo number_format($wallet_investment,2); ?>
                                                    <a class="pull-right me-5 badge badge-secondary shadow text-bold" href="view_investment.php">
                                                        <i class="icofont icofont-eye"></i>
                                                        View Txn
                                                    </a>
                                                </h4>
                                            </div>
                                            <svg class="fill-secondary" width="48" height="48" viewbox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M22.5938 14.1562V17.2278C20.9604 17.8102 19.7812 19.3566 19.7812 21.1875C19.7812 23.5138 21.6737 25.4062 24 25.4062C24.7759 25.4062 25.4062 26.0366 25.4062 26.8125C25.4062 27.5884 24.7759 28.2188 24 28.2188C23.2241 28.2188 22.5938 27.5884 22.5938 26.8125H19.7812C19.7812 28.6434 20.9604 30.1898 22.5938 30.7722V33.8438H25.4062V30.7722C27.0396 30.1898 28.2188 28.6434 28.2188 26.8125C28.2188 24.4862 26.3263 22.5938 24 22.5938C23.2241 22.5938 22.5938 21.9634 22.5938 21.1875C22.5938 20.4116 23.2241 19.7812 24 19.7812C24.7759 19.7812 25.4062 20.4116 25.4062 21.1875H28.2188C28.2188 19.3566 27.0396 17.8102 25.4062 17.2278V14.1562H22.5938Z"></path>
                                                <path d="M25.4062 0V11.4859C31.2498 12.1433 35.8642 16.7579 36.5232 22.5938H48C47.2954 10.5189 37.4829 0.704531 25.4062 0Z"></path>
                                                <path d="M14.1556 31.8558C12.4237 29.6903 11.3438 26.9823 11.3438 24C11.3438 17.5025 16.283 12.1958 22.5938 11.4859V0C10.0492 0.731813 0 11.2718 0 24C0 30.0952 2.39381 35.6398 6.14897 39.8624L14.1556 31.8558Z"></path>
                                                <path d="M47.9977 25.4062H36.5143C35.8044 31.717 30.4977 36.6562 24.0002 36.6562C21.0179 36.6562 18.3099 35.5763 16.1444 33.8444L8.13779 41.851C12.3604 45.6062 17.905 48 24.0002 48C36.7284 48 47.2659 37.9508 47.9977 25.4062Z"></path>
                                            </svg>
                                        </div>
                                        
                                        <div class="col-12">
                                            <h3 class="mb-3">Summary</h3>
                                            <div class="card-body p-1 pb-4">
                                                <div class="row"> 
                                                    <div class="col-6">
                                                        <div class="report-content text-center"> 
                                                            <p class="font-theme-light">Credited</p>
                                                            <h5>+ ₹ <?php echo number_format($invest_credit,2); ?></h5>
                                                            <div class="progress sm-progress-bar progress-animate progress-round-primary">
                                                                <div class="progress-gradient-success" role="progressbar" style="width: <?php echo $invest_credit_percent; ?>%" aria-valuenow="<?php echo $invest_credit_percent; ?>" aria-valuemin="0" aria-valuemax="100">
                                                                    <span class="animate-circle"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="report-content text-center"> 
                                                            <p class="font-theme-light">Debited</p>
                                                            <h5>- ₹ <?php echo number_format($invest_debit,2); ?></h5>
                                                            <div class="progress sm-progress-bar progress-animate progress-round-secondary">
                                                                <div class="progress-gradient-danger" role="progressbar" style="width: <?php echo $invest_debit_percent; ?>%" aria-valuenow="<?php echo $invest_debit_percent; ?>" aria-valuemin="0" aria-valuemax="100">
                                                                    <span class="animate-circle"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="progress-widget d-none">
                                            <div class="progress sm-progress-bar progress-animate">
                                                <div class="progress-gradient-secondary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                                    <span class="animate-circle"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6 dash-xl-50">
                                <div class="ribbon-wrapper-right card shadow">
                                    <div class="card-body">
                                        <div class="ribbon ribbon-clip-right ribbon-right ribbon-success shadow text-bold">ROI Income</div>
                                        <div class="media static-widget mb-3">
                                            <div class="media-body">
                                                <h6 class="font-roboto">Amount in ROI wallet</h6>
                                                <h4 class="mb-0 counter">
                                                    ₹ <?php echo number_format($wallet_roi,2); ?>
                                                    <a class="pull-right me-5 badge badge-success shadow text-bold" href="view_transaction.php?type=<?php echo base64_encode(json_encode('roi')); ?>">
                                                        <i class="icofont icofont-eye"></i>
                                                        View Txn
                                                    </a>
                                                </h4>
                                            </div>
                                            <svg class="fill-success" width="48" height="48" viewbox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M22.5938 14.1562V17.2278C20.9604 17.8102 19.7812 19.3566 19.7812 21.1875C19.7812 23.5138 21.6737 25.4062 24 25.4062C24.7759 25.4062 25.4062 26.0366 25.4062 26.8125C25.4062 27.5884 24.7759 28.2188 24 28.2188C23.2241 28.2188 22.5938 27.5884 22.5938 26.8125H19.7812C19.7812 28.6434 20.9604 30.1898 22.5938 30.7722V33.8438H25.4062V30.7722C27.0396 30.1898 28.2188 28.6434 28.2188 26.8125C28.2188 24.4862 26.3263 22.5938 24 22.5938C23.2241 22.5938 22.5938 21.9634 22.5938 21.1875C22.5938 20.4116 23.2241 19.7812 24 19.7812C24.7759 19.7812 25.4062 20.4116 25.4062 21.1875H28.2188C28.2188 19.3566 27.0396 17.8102 25.4062 17.2278V14.1562H22.5938Z"></path>
                                                <path d="M25.4062 0V11.4859C31.2498 12.1433 35.8642 16.7579 36.5232 22.5938H48C47.2954 10.5189 37.4829 0.704531 25.4062 0Z"></path>
                                                <path d="M14.1556 31.8558C12.4237 29.6903 11.3438 26.9823 11.3438 24C11.3438 17.5025 16.283 12.1958 22.5938 11.4859V0C10.0492 0.731813 0 11.2718 0 24C0 30.0952 2.39381 35.6398 6.14897 39.8624L14.1556 31.8558Z"></path>
                                                <path d="M47.9977 25.4062H36.5143C35.8044 31.717 30.4977 36.6562 24.0002 36.6562C21.0179 36.6562 18.3099 35.5763 16.1444 33.8444L8.13779 41.851C12.3604 45.6062 17.905 48 24.0002 48C36.7284 48 47.2659 37.9508 47.9977 25.4062Z"></path>
                                            </svg>
                                        </div>
                                        
                                        <div class="col-12">
                                            <h3 class="mb-3">Summary</h3>
                                            <div class="card-body p-1 pb-4">
                                                <div class="row"> 
                                                    <div class="col-6">
                                                        <div class="report-content text-center"> 
                                                            <p class="font-theme-light">Credited</p>
                                                            <h5>+ ₹ <?php echo number_format($roi_credit,2); ?></h5>
                                                            <div class="progress sm-progress-bar progress-animate progress-round-primary">
                                                                <div class="progress-gradient-success" role="progressbar" style="width: <?php echo $roi_credit_percent; ?>%" aria-valuenow="<?php echo $roi_credit_percent; ?>" aria-valuemin="0" aria-valuemax="100">
                                                                    <span class="animate-circle"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="report-content text-center"> 
                                                            <p class="font-theme-light">Debited</p>
                                                            <h5>- ₹ <?php echo number_format($roi_debit,2); ?></h5>
                                                            <div class="progress sm-progress-bar progress-animate progress-round-secondary">
                                                                <div class="progress-gradient-danger" role="progressbar" style="width: <?php echo $roi_debit_percent; ?>%" aria-valuenow="<?php echo $roi_debit_percent; ?>" aria-valuemin="0" aria-valuemax="100">
                                                                    <span class="animate-circle"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="progress-widget d-none">
                                            <div class="progress sm-progress-bar progress-animate">
                                                <div class="progress-gradient-success" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                                    <span class="animate-circle"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6 dash-xl-50">
                                <div class="ribbon-wrapper card shadow">
                                    <div class="card-body">
                                        <div class="ribbon ribbon-clip ribbon-primary shadow text-bold" style="z-index:auto">Commission</div>
                                        <div class="media static-widget mb-3">
                                            <div class="media-body">
                                                <h6 class="font-roboto">Amount in Commission wallet</h6>
                                                <h4 class="mb-0 counter">
                                                    ₹ <?php echo number_format($wallet_commission,2); ?>
                                                    <a class="pull-right me-5 badge badge-primary shadow text-bold" href="view_transaction.php?type=<?php echo base64_encode(json_encode('lc_sc')); ?>">
                                                        <i class="icofont icofont-eye"></i>
                                                        View Txn
                                                    </a>
                                                </h4>
                                            </div>
                                            <svg class="fill-primary" width="48" height="48" viewbox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M22.5938 14.1562V17.2278C20.9604 17.8102 19.7812 19.3566 19.7812 21.1875C19.7812 23.5138 21.6737 25.4062 24 25.4062C24.7759 25.4062 25.4062 26.0366 25.4062 26.8125C25.4062 27.5884 24.7759 28.2188 24 28.2188C23.2241 28.2188 22.5938 27.5884 22.5938 26.8125H19.7812C19.7812 28.6434 20.9604 30.1898 22.5938 30.7722V33.8438H25.4062V30.7722C27.0396 30.1898 28.2188 28.6434 28.2188 26.8125C28.2188 24.4862 26.3263 22.5938 24 22.5938C23.2241 22.5938 22.5938 21.9634 22.5938 21.1875C22.5938 20.4116 23.2241 19.7812 24 19.7812C24.7759 19.7812 25.4062 20.4116 25.4062 21.1875H28.2188C28.2188 19.3566 27.0396 17.8102 25.4062 17.2278V14.1562H22.5938Z"></path>
                                                <path d="M25.4062 0V11.4859C31.2498 12.1433 35.8642 16.7579 36.5232 22.5938H48C47.2954 10.5189 37.4829 0.704531 25.4062 0Z"></path>
                                                <path d="M14.1556 31.8558C12.4237 29.6903 11.3438 26.9823 11.3438 24C11.3438 17.5025 16.283 12.1958 22.5938 11.4859V0C10.0492 0.731813 0 11.2718 0 24C0 30.0952 2.39381 35.6398 6.14897 39.8624L14.1556 31.8558Z"></path>
                                                <path d="M47.9977 25.4062H36.5143C35.8044 31.717 30.4977 36.6562 24.0002 36.6562C21.0179 36.6562 18.3099 35.5763 16.1444 33.8444L8.13779 41.851C12.3604 45.6062 17.905 48 24.0002 48C36.7284 48 47.2659 37.9508 47.9977 25.4062Z"></path>
                                            </svg>
                                        </div>
                                        
                                        <div class="col-12">
                                            <h3 class="mb-3">Summary</h3>
                                            <div class="card-body p-1 pb-4">
                                                <div class="row"> 
                                                    <div class="col-6">
                                                        <div class="report-content text-center"> 
                                                            <p class="font-theme-light">Credited</p>
                                                            <h5>+ ₹ <?php echo number_format($commission_credit,2); ?></h5>
                                                            <div class="progress sm-progress-bar progress-animate progress-round-primary">
                                                                <div class="progress-gradient-success" role="progressbar" style="width: <?php echo $commission_credit_percent; ?>%" aria-valuenow="<?php echo $commission_credit_percent; ?>" aria-valuemin="0" aria-valuemax="100">
                                                                    <span class="animate-circle"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="report-content text-center"> 
                                                            <p class="font-theme-light">Debited</p>
                                                            <h5>- ₹ <?php echo number_format($commission_debit,2); ?></h5>
                                                            <div class="progress sm-progress-bar progress-animate progress-round-secondary">
                                                                <div class="progress-gradient-danger" role="progressbar" style="width: <?php echo $commission_debit_percent; ?>%" aria-valuenow="<?php echo $commission_debit_percent; ?>" aria-valuemin="0" aria-valuemax="100">
                                                                    <span class="animate-circle"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="progress-widget d-none">
                                            <div class="progress sm-progress-bar progress-animate">
                                                <div class="progress-gradient-primary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                                    <span class="animate-circle"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                          <!--   <div class="col-xl-6 col-md-6 dash-xl-50">
                                <div class="ribbon-wrapper-right card shadow">
                                    <div class="card-body">
                                        <div class="ribbon ribbon-clip-right ribbon-right ribbon-info shadow text-bold">Maxizone SuperWallet</div>
                                        <div class="media static-widget mb-3">
                                            <div class="media-body">
                                                <h6 class="font-roboto">Amount in Superwallet</h6>
                                                <h4 class="mb-0 counter">
                                                    ₹ <?php echo number_format($superwallet,2); ?>
                                                    <a class="pull-right me-5 badge badge-info shadow text-bold" href="view_superwallet.php">
                                                        <i class="icofont icofont-eye"></i>
                                                        View Txn
                                                    </a>
                                                </h4>
                                            </div>
                                            <svg class="fill-info" width="48" height="48" viewbox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M22.5938 14.1562V17.2278C20.9604 17.8102 19.7812 19.3566 19.7812 21.1875C19.7812 23.5138 21.6737 25.4062 24 25.4062C24.7759 25.4062 25.4062 26.0366 25.4062 26.8125C25.4062 27.5884 24.7759 28.2188 24 28.2188C23.2241 28.2188 22.5938 27.5884 22.5938 26.8125H19.7812C19.7812 28.6434 20.9604 30.1898 22.5938 30.7722V33.8438H25.4062V30.7722C27.0396 30.1898 28.2188 28.6434 28.2188 26.8125C28.2188 24.4862 26.3263 22.5938 24 22.5938C23.2241 22.5938 22.5938 21.9634 22.5938 21.1875C22.5938 20.4116 23.2241 19.7812 24 19.7812C24.7759 19.7812 25.4062 20.4116 25.4062 21.1875H28.2188C28.2188 19.3566 27.0396 17.8102 25.4062 17.2278V14.1562H22.5938Z"></path>
                                                <path d="M25.4062 0V11.4859C31.2498 12.1433 35.8642 16.7579 36.5232 22.5938H48C47.2954 10.5189 37.4829 0.704531 25.4062 0Z"></path>
                                                <path d="M14.1556 31.8558C12.4237 29.6903 11.3438 26.9823 11.3438 24C11.3438 17.5025 16.283 12.1958 22.5938 11.4859V0C10.0492 0.731813 0 11.2718 0 24C0 30.0952 2.39381 35.6398 6.14897 39.8624L14.1556 31.8558Z"></path>
                                                <path d="M47.9977 25.4062H36.5143C35.8044 31.717 30.4977 36.6562 24.0002 36.6562C21.0179 36.6562 18.3099 35.5763 16.1444 33.8444L8.13779 41.851C12.3604 45.6062 17.905 48 24.0002 48C36.7284 48 47.2659 37.9508 47.9977 25.4062Z"></path>
                                            </svg>
                                        </div>
                                        
                                        <div class="col-12">
                                            <h3 class="mb-3">Summary</h3>
                                            <div class="card-body p-1 pb-4">
                                                <div class="row"> 
                                                    <div class="col-6">
                                                        <div class="report-content text-center"> 
                                                            <p class="font-theme-light">Received</p>
                                                            <h5>+ ₹ <?php echo number_format($superwallet_credit,2); ?></h5>
                                                            <div class="progress sm-progress-bar progress-animate progress-round-primary">
                                                                <div class="progress-gradient-success" role="progressbar" style="width: <?php echo $superwallet_credit_percent; ?>%" aria-valuenow="<?php echo $superwallet_credit_percent; ?>" aria-valuemin="0" aria-valuemax="100">
                                                                    <span class="animate-circle"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="report-content text-center"> 
                                                            <p class="font-theme-light">Transferred</p>
                                                            <h5>- ₹ <?php echo number_format($superwallet_debit,2); ?></h5>
                                                            <div class="progress sm-progress-bar progress-animate progress-round-secondary">
                                                                <div class="progress-gradient-danger" role="progressbar" style="width: <?php echo $superwallet_debit_percent; ?>%" aria-valuenow="<?php echo $superwallet_debit_percent; ?>" aria-valuemin="0" aria-valuemax="100">
                                                                    <span class="animate-circle"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="progress-widget d-none">
                                            <div class="progress sm-progress-bar progress-animate">
                                                <div class="progress-gradient-info" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                                    <span class="animate-circle"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6 dash-xl-50 d-none">
                                <div class="ribbon-wrapper-right card shadow">
                                    <div class="card-body">
                                        <div class="ribbon ribbon-clip-right ribbon-right ribbon-info shadow text-bold">Fixed Deposit</div>
                                        <div class="media static-widget mb-3">
                                            <div class="media-body">
                                                <h6 class="font-roboto">Amount in FD</h6>
                                                <h4 class="mb-0 counter">
                                                    ₹ <?php echo number_format($wallet_fd,2); ?>
                                                    <a class="pull-right me-5 badge badge-info shadow text-bold" href="view_transaction.php">
                                                        <i class="icofont icofont-eye"></i>
                                                        View Txn
                                                    </a>
                                                </h4>
                                            </div>
                                            <svg class="fill-info" width="48" height="48" viewbox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M22.5938 14.1562V17.2278C20.9604 17.8102 19.7812 19.3566 19.7812 21.1875C19.7812 23.5138 21.6737 25.4062 24 25.4062C24.7759 25.4062 25.4062 26.0366 25.4062 26.8125C25.4062 27.5884 24.7759 28.2188 24 28.2188C23.2241 28.2188 22.5938 27.5884 22.5938 26.8125H19.7812C19.7812 28.6434 20.9604 30.1898 22.5938 30.7722V33.8438H25.4062V30.7722C27.0396 30.1898 28.2188 28.6434 28.2188 26.8125C28.2188 24.4862 26.3263 22.5938 24 22.5938C23.2241 22.5938 22.5938 21.9634 22.5938 21.1875C22.5938 20.4116 23.2241 19.7812 24 19.7812C24.7759 19.7812 25.4062 20.4116 25.4062 21.1875H28.2188C28.2188 19.3566 27.0396 17.8102 25.4062 17.2278V14.1562H22.5938Z"></path>
                                                <path d="M25.4062 0V11.4859C31.2498 12.1433 35.8642 16.7579 36.5232 22.5938H48C47.2954 10.5189 37.4829 0.704531 25.4062 0Z"></path>
                                                <path d="M14.1556 31.8558C12.4237 29.6903 11.3438 26.9823 11.3438 24C11.3438 17.5025 16.283 12.1958 22.5938 11.4859V0C10.0492 0.731813 0 11.2718 0 24C0 30.0952 2.39381 35.6398 6.14897 39.8624L14.1556 31.8558Z"></path>
                                                <path d="M47.9977 25.4062H36.5143C35.8044 31.717 30.4977 36.6562 24.0002 36.6562C21.0179 36.6562 18.3099 35.5763 16.1444 33.8444L8.13779 41.851C12.3604 45.6062 17.905 48 24.0002 48C36.7284 48 47.2659 37.9508 47.9977 25.4062Z"></path>
                                            </svg>
                                        </div>
                                        <div class="progress-widget">
                                            <div class="progress sm-progress-bar progress-animate">
                                                <div class="progress-gradient-info" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                                    <span class="animate-circle"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                        <!-- INCOME SLABS -->

                        
                        <div class="col-xl-6 col-md-6 dash-xl-50">
                            <div class="ribbon-wrapper card shadow">
                                <div class="card-body">
                                    <div class="ribbon ribbon-clip ribbon ribbon-dark shadow text-bold">Team Size</div>
                                    <div class="media static-widget mb-3">
                                        <div class="media-body">
                                            <h5 class="font-roboto text-bold">
                                                Total Team Members: 
                                                <span class="me-5 badge badge-info shadow text-bold" style="font-size: medium;">
                                                    <?php echo $total_member; ?>
                                                </span>
                                            </h5>
                                        </div>
                                        
                                        <!-- <i class="icofont icofont-users-alt-1"></i> -->
                                        <img src="../assets/images/ico-members.png" alt="" style="width: 48px; height: 48px;">

                                    </div>

                                    <div class="col-12">
                                        <h3 class="mb-3">Summary</h3>
                                        <div class="card-body p-1 pb-4">
                                            <div class="row"> 
                                                <div class="col-6">
                                                    <div class="report-content text-center"> 
                                                        <p class="font-theme-light">Active Members</p>
                                                        <h5><?php echo $total_active; ?></h5>
                                                        <div class="progress sm-progress-bar progress-animate progress-round-primary">
                                                            <div class="progress-gradient-success" role="progressbar" style="width: <?php echo $active_percent; ?>%" aria-valuenow="<?php echo $active_percent; ?>" aria-valuemin="0" aria-valuemax="100">
                                                                <span class="animate-circle"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="report-content text-center"> 
                                                        <p class="font-theme-light">Inactive Members</p>
                                                        <h5><?php echo $total_inactive; ?></h5>
                                                        <div class="progress sm-progress-bar progress-animate progress-round-secondary">
                                                            <div class="progress-gradient-danger" role="progressbar" style="width: <?php echo $inactive_percent; ?>%" aria-valuenow="<?php echo $inactive_percent; ?>" aria-valuemin="0" aria-valuemax="100">
                                                                <span class="animate-circle"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="progress-widget d-none">
                                        <div class="progress sm-progress-bar progress-animate">
                                            <div class="progress-gradient-dark" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                                <span class="animate-circle"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                       <!--   <div class="col-xl-6 col-md-6 dash-xl-50">
                            <div class="ribbon-wrapper-right card shadow">
                                <div class="card-body">
                                    <div class="ribbon ribbon-clip-right ribbon-right ribbon-danger shadow text-bold">Finances</div>
                                    <div class="media static-widget mb-3">
                                        <div class="media-body">
                                            <h5 class="font-roboto text-bold d-inline" style="font-size: small;">
                                                Total Bank Withdrawal: 
                                                <span class="me-2 text-danger pull-right text-bold">
                                                    ₹ <?php echo number_format($total_withdrawal,2); ?>
                                                </span>
                                            </h5>
                                            <br>
                                            <h5 class="font-roboto text-bold d-inline" style="font-size: small;">
                                                Transferred to others: 
                                                <span class="me-2 text-danger pull-right text-bold">
                                                    ₹ <?php echo number_format($superwallet_transfer,2); ?>
                                                </span>
                                            </h5>
                                            <br>
                                            <h5 class="font-roboto text-bold d-inline" style="font-size: small;">
                                                Received by others: 
                                                <span class="me-2 text-danger pull-right text-bold">
                                                    ₹ <?php echo number_format($superwallet_received,2); ?>
                                                </span>
                                            </h5>
                                        </div>
                                        <svg class="fill-danger" width="48" height="48" viewbox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M22.5938 14.1562V17.2278C20.9604 17.8102 19.7812 19.3566 19.7812 21.1875C19.7812 23.5138 21.6737 25.4062 24 25.4062C24.7759 25.4062 25.4062 26.0366 25.4062 26.8125C25.4062 27.5884 24.7759 28.2188 24 28.2188C23.2241 28.2188 22.5938 27.5884 22.5938 26.8125H19.7812C19.7812 28.6434 20.9604 30.1898 22.5938 30.7722V33.8438H25.4062V30.7722C27.0396 30.1898 28.2188 28.6434 28.2188 26.8125C28.2188 24.4862 26.3263 22.5938 24 22.5938C23.2241 22.5938 22.5938 21.9634 22.5938 21.1875C22.5938 20.4116 23.2241 19.7812 24 19.7812C24.7759 19.7812 25.4062 20.4116 25.4062 21.1875H28.2188C28.2188 19.3566 27.0396 17.8102 25.4062 17.2278V14.1562H22.5938Z"></path>
                                            <path d="M25.4062 0V11.4859C31.2498 12.1433 35.8642 16.7579 36.5232 22.5938H48C47.2954 10.5189 37.4829 0.704531 25.4062 0Z"></path>
                                            <path d="M14.1556 31.8558C12.4237 29.6903 11.3438 26.9823 11.3438 24C11.3438 17.5025 16.283 12.1958 22.5938 11.4859V0C10.0492 0.731813 0 11.2718 0 24C0 30.0952 2.39381 35.6398 6.14897 39.8624L14.1556 31.8558Z"></path>
                                            <path d="M47.9977 25.4062H36.5143C35.8044 31.717 30.4977 36.6562 24.0002 36.6562C21.0179 36.6562 18.3099 35.5763 16.1444 33.8444L8.13779 41.851C12.3604 45.6062 17.905 48 24.0002 48C36.7284 48 47.2659 37.9508 47.9977 25.4062Z"></path>
                                        </svg>
                                    </div>

                                  <div class="col-12">
                                        <h3 class="mb-3">Investment Summary</h3>
                                        <div class="card-body p-1 pb-4">
                                            <div class="row"> 
                                                <div class="col-6">
                                                    <div class="report-content text-center"> 
                                                        <p class="font-theme-light">Self OR Admin</p>
                                                        <h5>₹ <?php echo number_format($total_self_invest,2); ?></h5>
                                                        <div class="progress sm-progress-bar progress-animate progress-round-primary">
                                                            <div class="progress-gradient-success" role="progressbar" style="width: <?php echo $self_invest_percent; ?>%" aria-valuenow="<?php echo $self_invest_percent; ?>" aria-valuemin="0" aria-valuemax="100">
                                                                <span class="animate-circle"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="report-content text-center"> 
                                                        <p class="font-theme-light">Superwallet Investment</p>
                                                        <h5>₹ <?php echo number_format($total_superwallet_invest,2); ?></h5>
                                                        <div class="progress sm-progress-bar progress-animate progress-round-secondary">
                                                            <div class="progress-gradient-danger" role="progressbar" style="width: <?php echo $super_invest_percent; ?>%" aria-valuenow="<?php echo $super_invest_percent; ?>" aria-valuemin="0" aria-valuemax="100">
                                                                <span class="animate-circle"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="progress-widget d-none">
                                        <div class="progress sm-progress-bar progress-animate">
                                            <div class="progress-gradient-dark" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                                <span class="animate-circle"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <div class="col-xl-6 col-md-6 dash-xl-50">
                            <div class="card weekly-column gradient-azur shadow">
                                <div class="card-header py-3 gradient-juicy-orange shadow">
                                    <h5 class="mb-1 text-nowrap">Joining</h5>
                                    <small>Weekly Report</small>
                                </div>
                                <div class="card-body p-0 py-3">
                                    <div id="liveGraphActivation"> </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-6 col-md-6 dash-xl-50">
                            <div class="card weekly-column gradient-azur shadow">
                                <div class="card-header py-3 gradient-juicy-orange shadow">
                                    <h5 class="mb-1 text-nowrap">Earnings</h5>
                                    <small>Weekly Report</small>
                                </div>
                                <div class="card-body p-0 py-3">
                                    <div id="liveGraphEarnings"> </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 dash-xl-50 d-none">
                            <div class="card weekly-column">
                                <div class="card-body p-0">
                                    <div id="weekly-chart"> </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6 dash-31 dash-xl-50 d-none">
                            <div class="card news-update">
                            <div class="card-header card-no-border"> 
                                <div class="header-top">
                                <h5 class="m-0">News &amp; Update</h5>
                                <div class="icon-box onhover-dropdown"><i data-feather="more-horizontal"></i>
                                    <div class="icon-box-show onhover-show-div">
                                    <ul> 
                                        <li> <a>
                                            Today</a></li>
                                        <li> <a>
                                            Yesterday</a></li>
                                        <li> <a>
                                            Tommorow</a></li>
                                    </ul>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="table-responsive custom-scrollbar">        
                                <table class="table table-bordernone">                          
                                    <tbody> 
                                    <tr>
                                        <td>
                                        <div class="media"><img class="img-fluid me-3 b-r-5" src="assets/images/dashboard/rectangle-26.jpg" alt="">
                                            <div class="media-body"><a href="blog-single.html">
                                                <h5>Google Project Apply Reviwe</h5></a>
                                            <p>Today's News Headlines, Breaking...</p>
                                            </div>
                                        </div>
                                        </td>
                                        <td><span class="badge badge-light-theme-light font-theme-light">1 day ago</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                        <div class="media"> <img class="img-fluid me-3 b-r-5" src="assets/images/dashboard/rectangle-27.jpg" alt="">
                                            <div class="media-body"><a href="blog-single.html">
                                                <h5>Business Logo Create</h5></a>
                                            <p>Check out the latest news from...</p>
                                            </div>
                                        </div>
                                        </td>
                                        <td><span class="badge badge-light-theme-light font-theme-light">2 weeks ago</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                        <div class="media"><img class="img-fluid me-3 b-r-5" src="assets/images/dashboard/rectangle-28.jpg" alt="">
                                            <div class="media-body"><a href="blog-single.html">
                                                <h5>Business Project Research</h5></a>
                                            <p>News in English: Get all Breaking...</p>
                                            </div>
                                        </div>
                                        </td>
                                        <td><span class="badge badge-light-theme-light font-theme-light">3 day ago</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                        <div class="media"><img class="img-fluid me-3 b-r-5" src="assets/images/dashboard/rectangle-29.jpg" alt="">
                                            <div class="media-body"><a href="blog-single.html">
                                                <h5>Recruitment in it Department</h5></a>
                                            <p>Technology and Indian Business News...</p>
                                            </div>
                                        </div>
                                        </td>
                                        <td><span class="badge badge-light-theme-light font-theme-light">2 hours ago</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                        <div class="media"><img class="img-fluid me-3 b-r-5" src="assets/images/dashboard/rectangle-28.jpg" alt="">
                                            <div class="media-body"><a href="blog-single.html">
                                                <h5>Business Project Research</h5></a>
                                            <p>News in English: Get all Breaking...</p>
                                            </div>
                                        </div>
                                        </td>
                                        <td><span class="badge badge-light-theme-light font-theme-light">3 day ago</span></td>
                                    </tr>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            </div>
                        </div>

                        <div class="col-xl-12 col-md-12">
                            <div class="card">
                                <div class="card-header card-no-border">
                                    <div class="media media-dashboard">
                                        <div class="media-body">
                                            <h5 class="mb-0">Members Summary</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="table-responsive custom-scrollbar">
                                        <!-- <table class="table table-bordernone table-striped table-warnig table-hover progress-gradientt-secondaryy gradient-radar"> -->
                                        <table class="table table-bordernone table-striped table-hover gradient-megatron">
                                            <thead class="bg-primasry gradient-lush"> 
                                                <tr class="text-center text-bold lead" style="color:white !important;">
                                                    <th>
                                                        <span>Level</span>
                                                    </th>
                                                    <th>
                                                        <span>Total Members</span>
                                                    </th>
                                                    <th>
                                                        <span>Active Members</span>
                                                    </th>
                                                    <th>
                                                        <span>Inactive Members</span>
                                                    </th>
                                                    <th>
                                                        <span>Active Business</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    for ($k=1; $k <=24; $k++) {
                                                        $r = "level$k";

                                                        $s = "level_active$k";

                                                        $t = "level_inactive$k";
                                                        // echo "<hr> >> $r = {$$r} >> $s = {$$s} >> $t = {$$t} << <hr>";
                                                        $lvl = strtoupper($r);
                                                        $investmentlevelamout = "level_investment$k";
                                                        ?>
                                                            <tr class="text-center">
                                                                <td>
                                                                    <div class="badge badge-warning b-r-10 py-0 pt-2 shadow">
                                                                        <h6><?php echo $lvl; ?></h6>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    
                                                                <?php if($$r != 0) { ?>
                                                                    <div class="badge badge-primary b-r-10 py-0 pt-2 shadow">
                                                                        <h6><?php echo $$r; ?></h6>
                                                                    </div>
                                                                    <?php }else { ?>
                                                                        <div class=" b-r-10 py-0 pt-2" >
                                                                        <h6>-</h6>
                                                                    </div>
                                                                    <?php } ?>
                                                                   
                                                                </td>
                                                                <td>
                                                                <?php if($$s != 0) { ?>
                                                                    <div class="badge badge-success b-r-10 py-0 pt-2 shadow">
                                                                        <h6><?php echo $$s; ?></h6>
                                                                    </div>
                                                                    <?php }else { ?>
                                                                        <div class=" b-r-10 py-0 pt-2" >
                                                                        <h6>-</h6>
                                                                    </div>
                                                                    <?php } ?>
                                                                    
                                                                </td>
                                                                <td>
                                                                <?php if($$t != 0) { ?>
                                                                    <div class="badge badge-danger b-r-10 py-0 pt-2 shadow">
                                                                        <h6><?php echo $$t; ?></h6>
                                                                    </div>
                                                                    <?php }else { ?>
                                                                        <div class=" b-r-10 py-0 pt-2" >
                                                                        <h6>-</h6>
                                                                    </div>
                                                                    <?php } ?>
                                                                   
                                                                </td>
                                                                <td>
                                                                    <?php if(round($$investmentlevelamout,0) != 0) { ?>
                                                                    <div class="badge badge-danger b-r-10 py-0 pt-2 shadow" style="background-color: #b378f0">
                                                                        <h6><?php echo round($$investmentlevelamout,0); ?></h6>
                                                                    </div>
                                                                    <?php }else { ?>
                                                                        <div class=" b-r-10 py-0 pt-2" >
                                                                        <h6>-</h6>
                                                                    </div>
                                                                    <?php } ?>
                                                                    
                                                                </td>
                                                                
                                                            </tr>
                                                        <?php
                                                    }
                                                ?>                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-12 dash-xl-100 d-none">
                            <div class="card total-transactions">
                                <div class="row m-0">
                                    <div class="col-md-6 col-sm-6 p-0">
                                        <div class="card-header card-no-border">
                                            <h5>Total Transactions</h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div> 
                                            <div id="transaction-chart"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 p-0 report-sec">
                                        <div class="card-header card-no-border"> 
                                            <div class="header-top">
                                            <h5 class="m-0">Report</h5>
                                            <div class="icon-box onhover-dropdown"><i data-feather="more-horizontal"></i>
                                                <div class="icon-box-show onhover-show-div">
                                                <ul> 
                                                    <li> <a>
                                                        Today</a></li>
                                                    <li> <a>
                                                        Yesterday</a></li>
                                                    <li> <a>
                                                        Tommorow</a></li>
                                                </ul>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="row"> 
                                            <div class="col-6 report-main">
                                                <div class="report-content text-center"> 
                                                <p class="font-theme-light">This Week</p>
                                                <h5>+86.53%</h5>
                                                <div class="progress progress-round-primary">
                                                    <div class="progress-bar" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="report-content text-center"> 
                                                <p class="font-theme-light">Last Week</p>
                                                <h5>-34.50%</h5>
                                                <div class="progress progress-round-secondary">
                                                    <div class="progress-bar" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-12">        
                                                <div class="media report-perfom">
                                                <div class="media-body">
                                                    <p class="font-theme-light">Performance </p>
                                                    <h5 class="m-0">+93.82%</h5>
                                                </div><a class="btn btn-primary" href="blog-single.html">New Report</a>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 dash-xl-50 d-none">
                            <div class="card yearly-chart">
                            <div class="card-header card-no-border pb-0">
                                <h5 class="pb-2">$3,500,000</h5>
                                <h6 class="font-theme-light f-14 m-0">November 2021</h6>
                            </div>
                            <div class="card-body pt-0">
                                <div> 
                                <div id="yearly-chart"></div>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 dash-xl-50 d-none">
                            <div class="card bg-primary premium-access">
                            <div class="card-body">                  
                                <h6 class="f-22">Premium Access!</h6>
                                <p>We add 20+ new features and update community in your project We add 20+ new features</p><a class="btn btn-outline-white_color" href="blog-single.html"> Try now for free</a>
                            </div>
                            <!-- Root element of PhotoSwipe. Must have class pswp.-->
                            <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="pswp__bg"></div>
                                <div class="pswp__scroll-wrap">
                                <div class="pswp__container">
                                    <div class="pswp__item"></div>
                                    <div class="pswp__item"></div>
                                    <div class="pswp__item"></div>
                                </div>
                                <div class="pswp__ui pswp__ui--hidden">
                                    <div class="pswp__top-bar">
                                    <div class="pswp__counter"></div>
                                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                                    <button class="pswp__button pswp__button--share" title="Share"></button>
                                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                                    <div class="pswp__preloader">
                                        <div class="pswp__preloader__icn">
                                        <div class="pswp__preloader__cut">
                                            <div class="pswp__preloader__donut"></div>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                                    <div class="pswp__share-tooltip"></div>
                                    </div>
                                    <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                                    <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                                    <div class="pswp__caption">
                                    <div class="pswp__caption__center"></div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid Ends-->
            </div>

            <?php require_once("footer.php"); ?>
        </div>
    </div>
    
    <?php require_once("scripts.php"); ?>

    <style>
        .apexcharts-xlabel-custom {
            font-style: italic !important;
            font-size: smaller !important;
        }
    </style>
    
    <script>
        "use strict";
        var options = {
            series: [{
                name: 'Joining',
                data: [44, 55, 57, 56, 61, 58, 63]
            }, {
                name: 'Earnings',
                data: [76, 85, 101, 98, 87, 105, 91]
            }],
            chart: {
                type: 'bar',
                height: 350,
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    startingShape: 'rounded',
                    // endingShape: 'rounded',
                    borderRadius: 6,
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'],
                axisBorder: { show: !1 },
                axisTicks: { show: !1 },
                labels: {
                style: { colors: "blue", fontSize: "14px", fontFamily: "Public Sans" },
                },
            },
            yaxis: {
                title: {
                    text: 'Numbers'
                }
            },
            fill: {
                colors: undefined,
                opacity: 1,
                type: 'solid',
                gradient: {
                    shade: 'dark',
                    type: "horizontal",
                    shadeIntensity: 0.5,
                    gradientToColors: "red",
                    inverseColors: true,
                    opacityFrom: 1,
                    opacityTo: 0.5,
                    stops: [0, 50, 100],
                    colorStops: ['#1e1e1e','#12e23e']
                },
                pattern: {
                    style: 'verticalLines',
                    width: 6,
                    height: 6,
                    strokeWidth: 2,
                },
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return "INR " + val + "k"
                    }
                }
            },
            grid: {
                show: !1,
                padding: { top: -20, bottom: -12, left: -10, right: 0 },
            },
            colors: [
                "red",
                "green",
                "yellow",
                "blue",
                "maroon",
                "green",
                "green",
            ],
        };

        // var chart = new ApexCharts(document.querySelector("#liveeGraphEarnings"), options);
        // chart.render();

        var isDarkStyle = true;

        !(function () {
            let o, i, t, n, l;
            t = isDarkStyle
                ? ((o = config.colors_dark.cardColor),
                (i = config.colors_dark.textMuted),
                (n = config.colors_dark.bodyColor),
                (l = config.colors_dark.borderColor),
                "dark")
                : ((o = config.colors.cardColor),
                (i = config.colors.textMuted),
                (n = config.colors.bodyColor),
                (l = config.colors.borderColor),
                "");
            var e = document.querySelector("#liveGraphActivation"),
                r = {
                    chart: {
                        height: 210,
                        type: "bar",
                        parentHeightOffset: 0,
                        toolbar: { show: !1 },
                    },
                    plotOptions: {
                        bar: {
                        barHeight: "80%",
                        columnWidth: "30%",
                        startingShape: "rounded",
                        endingShape: "rounded",
                        borderRadius: 6,
                        distributed: !0,
                        },
                    },
                    tooltip: { 
                        enabled: 1, 
                        y: {
                            formatter: function (val) {
                                return "" + val + ""
                            }
                        }
                    },
                    grid: {
                        show: !1,
                        padding: { top: -20, bottom: -12, left: -10, right: 0 },
                    },
                    colors: [                            
                        <?php echo $colors_graph_activation; ?>
                    ],
                    dataLabels: { enabled: !1 },
                    series: [{ name: 'Joining', data: [<?php echo $data_graph_activation; ?>] }],
                    legend: { show: !1 },
                    xaxis: {
                        categories: [<?php echo $day_graph_activation; ?>],
                        axisBorder: { show: !1 },
                        axisTicks: { show: !1 },
                        labels: {
                            style: { <?php echo $xaxis_style_graph_activation; ?> },
                            rotate: 0,
                            formatter: function (value) {
                                return value;
                            }
                        },
                    },
                    // yaxis: {
                    //     title: {
                    //         text: 'Numbers'
                    //     }
                    // },
                    yaxis: { 
                        // tickAmount: 2,  // NO. OF SLABS
                        labels: {
                            show: 1,
                            style: { <?php echo $yaxis_style_graph_activation; ?> },
                            formatter: function(val) {
                                return val.toFixed(0);  // TO MAKE ONLY INTEGERS ON AXIS
                            }
                        }
                    },
                    states: { hover: { filter: { type: "none" } } },
                    noData: {
                        text: "There is not enough data to display...",
                        align: 'center',
                        verticalAlign: 'middle',
                        offsetX: 0,
                        offsetY: 0,
                        style: {
                            color: "#000",
                            fontSize: '17px',
                            fontFamily: "georgia",
                        }
                    },
                    responsive: [
                        {
                            breakpoint: 1471,
                            options: { plotOptions: { bar: { columnWidth: "45%" } } },
                        },
                        {
                            breakpoint: 1350,
                            options: { plotOptions: { bar: { columnWidth: "40%" } } },
                        },
                        {
                            breakpoint: 1032,
                            options: { plotOptions: { bar: { columnWidth: "35%" } } },
                        },
                        {
                            breakpoint: 992,
                            options: {
                                plotOptions: { bar: { columnWidth: "35%", borderRadius: 8 } },
                            },
                        },
                        {
                            breakpoint: 855,
                            options: {
                                plotOptions: { bar: { columnWidth: "30%", borderRadius: 6 } },
                            },
                        },
                        {
                            breakpoint: 440,
                            options: { plotOptions: { bar: { columnWidth: "30%" } } },
                        },
                        {
                            breakpoint: 381,
                            options: { plotOptions: { bar: { columnWidth: "25%" } } },
                        },
                    ],
                };
            null !== e && new ApexCharts(e, r).render();

            var e = document.querySelector("#liveGraphEarnings"),
                r = {
                    chart: {
                        height: 210,
                        type: "bar",
                        parentHeightOffset: 0,
                        toolbar: { show: !1 },
                    },
                    plotOptions: {
                        bar: {
                        barHeight: "80%",
                        columnWidth: "30%",
                        startingShape: "rounded",
                        endingShape: "rounded",
                        borderRadius: 6,
                        distributed: !0,
                        },
                    },
                    tooltip: { 
                        enabled: 1, 
                        y: {
                            formatter: function (val) {
                                return "INR " + val + "/-"
                            }
                        }
                    },
                    grid: {
                        show: !1,
                        padding: { top: -20, bottom: -12, left: -10, right: 0 },
                    },
                    colors: [
                        <?php echo $colors_graph_earning; ?>
                    ],
                    dataLabels: { enabled: !1 },
                    series: [{ name: 'Earnings', data: [<?php echo $data_graph_earning; ?>] }],
                    legend: { show: !1 },
                    xaxis: {
                        categories: [<?php echo $day_graph_earning; ?>],
                        axisBorder: { show: !1 },
                        axisTicks: { show: !1 },
                        labels: {
                            style: { <?php echo $xaxis_style_graph_earning; ?> },
                        },
                    },
                    // yaxis: {
                    //     title: {
                    //         text: 'Numbers'
                    //     }
                    // },
                    yaxis: { 
                        labels: {
                            show: 1,
                            style: { <?php echo $yaxis_style_graph_earning; ?> },
                            formatter: function(val) {
                                return val.toFixed(0);  // TO MAKE ONLY INTEGERS ON AXIS
                            }
                        }
                    },
                    states: { hover: { filter: { type: "none" } } },
                    noData: {
                        text: "There is not enough data to display...",
                        align: 'center',
                        verticalAlign: 'middle',
                        offsetX: 0,
                        offsetY: 0,
                        style: {
                            color: "#000",
                            fontSize: '17px',
                            fontFamily: "georgia",
                        }
                    },
                    responsive: [
                        {
                            breakpoint: 1471,
                            options: { plotOptions: { bar: { columnWidth: "45%" } } },
                        },
                        {
                            breakpoint: 1350,
                            options: { plotOptions: { bar: { columnWidth: "40%" } } },
                        },
                        {
                            breakpoint: 1032,
                            options: { plotOptions: { bar: { columnWidth: "35%" } } },
                        },
                        {
                            breakpoint: 992,
                            options: {
                                plotOptions: { bar: { columnWidth: "35%", borderRadius: 8 } },
                            },
                        },
                        {
                            breakpoint: 855,
                            options: {
                                plotOptions: { bar: { columnWidth: "30%", borderRadius: 6 } },
                            },
                        },
                        {
                            breakpoint: 440,
                            options: { plotOptions: { bar: { columnWidth: "30%" } } },
                        },
                        {
                            breakpoint: 381,
                            options: { plotOptions: { bar: { columnWidth: "25%" } } },
                        },
                    ],
                };
            null !== e && new ApexCharts(e, r).render();
        })();
    </script>

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
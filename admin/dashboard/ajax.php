<?php
    session_start();
    ob_start();

    require_once("../../db_connect.php");
    mysqli_query($conn, "set names 'utf8'");  //-------WORKING UTF8 CODE------//

    //-------CURRENT DATE AND TIME TO FEED---------//
    date_default_timezone_set('Asia/Kolkata');
    $current_date = date('Y-m-d H:i:s');
    $current_timestamp = time();

    extract($_REQUEST);
    // POST VARIABLES
    $action;
    $password;
    // echo "<h2 style='text-align:center;font-weight:bold;color:red;'>
    // Time Is Over Now... Kindly Close This Window
    // </h2>";
    // exit;

    $name = "";
    // if(isset($_SESSION['name_admin_diet'])){
    //     $name = $_SESSION['name_admin_diet'];    //DECODE
    // }

    // CONVERSION=> $_POST['action']=> $action

    if ($action == "get_sponsor") {
        // $response['html_data'] = "<span class='text-danger'>$sponsor_id</span>";
        // echo json_encode($response);
        if ($rw = mysqli_fetch_array(mysqli_query($conn, "SELECT `name` FROM `users` WHERE `id`='$sponsor_id' "))) {
            $name = $rw['name'];
        }
        echo "$name";
    }

    if ($action == "change_password") {
        $user_id = $_SESSION['user_id_admin'];
        //FOR UPDATE QUERIES
        $q_update = mysqli_query($conn, "UPDATE `users_admin` SET `password`='$new_password', `update_date`='$current_date' WHERE `user_id`='$user_id' AND `password`='$current_password' ");
        $rows_affected = mysqli_affected_rows($conn);
        //FOR SELECT QUERIES
        // $res = mysqli_query($conn, "SELECT *");
        // $rows_affected = mysqli_num_rows($res);

        if ($rows_affected > 0) {
            $query_insert_password_change_log = "INSERT INTO `activity_log`(`user_id`, `activity`, `performed_by`, `create_date`) VALUES ('$user_id', 'Password Changed To: $new_password For Admin', '$user_id', '$current_date')";

            mysqli_query($conn, $query_insert_password_change_log);
            echo "Success";
        } else {
            echo "Error";

            // echo "Trainee is Not Eligible For Internship. Contact DIET For More Information.";
        }
    }

    if ($action == "process_withdrawal") {
        if (isset($_POST['id_user']) && isset($_POST['id_withdrawal']) && isset($_POST['amount_withdrawal']) && isset($_POST['action_to_perform']) && isset($_POST['reject_comment']) && isset($_POST['from_wallet'])) {
            $id_user = mysqli_real_escape_string($conn,$_POST['id_user']);
            $id_withdrawal = mysqli_real_escape_string($conn,$_POST['id_withdrawal']);
            $amount_withdrawal = mysqli_real_escape_string($conn,$_POST['amount_withdrawal']);
            $action_to_perform = mysqli_real_escape_string($conn,$_POST['action_to_perform']);
            $reject_comment = mysqli_real_escape_string($conn,$_POST['reject_comment']);
            $from_wallet = mysqli_real_escape_string($conn,$_POST['from_wallet']);

            // echo "$action_to_perform >> $id_user >> ";
            if ($action_to_perform == "approved") {
                $query_approve = "UPDATE `withdrawal` SET `status`='approved',`update_date`='$current_date' WHERE `id`='$id_withdrawal'";
                $res = mysqli_query($conn,$query_approve);
                $count_record_update = mysqli_affected_rows($conn);
                if ($count_record_update > 0) {
                    echo 'approved';
                    exit;
                }

            } else if ($action_to_perform == "rejected") {

                // echo $_POST['from_wallet'];
                // die();
                $query_approve = "UPDATE `withdrawal` SET `status`='rejected',`comment`='$reject_comment',`update_date`='$current_date' WHERE `id`='$id_withdrawal'";
                $res = mysqli_query($conn,$query_approve);
                $count_record_update = mysqli_affected_rows($conn);
                if ($count_record_update > 0) {
                    $query_approve = "";
                    if($from_wallet == "ROI Wallet")
                    {
                        $query_approve = "UPDATE `wallets` SET `wallet_roi`=`wallet_roi`+$amount_withdrawal,`update_date`='$current_date' WHERE `user_id`='$id_user'";
                    }
                    else if($from_wallet == "Comission Wallet")
                    {
                        $query_approve = "UPDATE `wallets` SET `wallet_commission`=`wallet_commission`+$amount_withdrawal,`update_date`='$current_date' WHERE `user_id`='$id_user'";
                    }

                    else if($from_wallet == "Investment Wallet")
                    {
                        $query_approve = "UPDATE `wallets` SET `wallet_investment`=`wallet_investment`+$amount_withdrawal,`update_date`='$current_date' WHERE `user_id`='$id_user'";
                    }


                    else
                    {
                        $query_approve = "UPDATE `wallets` SET `superwallet`=`superwallet`+$amount_withdrawal,`update_date`='$current_date' WHERE `user_id`='$id_user'";
                    }
                    $res = mysqli_query($conn,$query_approve);
                    $count_record_update = mysqli_affected_rows($conn);
                    if ($count_record_update > 0) {
                        echo 'rejected';
                        exit;
                    }
                }
            }
            

        }
    }

    if ($action == "process_kyc") {
        if (isset($_POST['id_user']) && isset($_POST['id_doc']) && isset($_POST['action_to_perform']) && isset($_POST['reject_comment']) && isset($_POST['force_reject'])) {
            $id_user = mysqli_real_escape_string($conn,$_POST['id_user']);
            $id_doc = mysqli_real_escape_string($conn,$_POST['id_doc']);
            $action_to_perform = mysqli_real_escape_string($conn,$_POST['action_to_perform']);
            $reject_comment = mysqli_real_escape_string($conn,$_POST['reject_comment']);
            $force_reject = mysqli_real_escape_string($conn,$_POST['force_reject']);

            // NORMAL UPDATES >> CHECK FOR CONDITION
            $check_condition = "";
            if ($force_reject == 0) {
                $check_condition = " AND `status`='pending'";
            }

            // echo "$action_to_perform >> $id_user >> ";
            if ($action_to_perform == "approved") {
                $query = "UPDATE `user_document` SET `status`='approved', `update_date`='$current_date' WHERE `id`='$id_doc' $check_condition";
                $res = mysqli_query($conn,$query);
                $count_record_update = mysqli_affected_rows($conn);
                
                if ($count_record_update > 0) {
                    $kyc_Status = get_kyc_status ($id_user);

                    if ($kyc_Status == "kyc_pending") {
                        // KYC PENDING
                        $query_approve = "UPDATE `users` SET `kyc_status`='pending', `active_date`=NULL, `update_date`='$current_date' WHERE `user_id`='$id_user'";
                        $res = mysqli_query($conn,$query_approve);
                        $count_record_update = mysqli_affected_rows($conn);
                        // if ($count_record_update > 0) {
                        //     echo 'kyc_rejected';
                        //     exit;
                        // }
                    } else if ($kyc_Status == "kyc_done") {
                        // KYC DONE
                        $query_approve = "UPDATE `users` SET `kyc_status`='approved', `active_date`='$current_date', `update_date`='$current_date' WHERE `user_id`='$id_user'";
                        $res = mysqli_query($conn,$query_approve);
                        $count_record_update = mysqli_affected_rows($conn);
                        if ($count_record_update > 0) {
                            echo 'kyc_approved';
                            exit;
                        }
                    }
                    echo 'approved';
                    exit;
                } else {
                    echo 'approved_already';
                    exit;
                }

            } else if ($action_to_perform == "rejected") {
                $query = "UPDATE `user_document` SET `status`='rejected', `comment`='$reject_comment', `update_date`='$current_date' WHERE `id`='$id_doc' $check_condition";
                $res = mysqli_query($conn,$query);
                $count_record_update = mysqli_affected_rows($conn);
                if ($count_record_update > 0) {
                    $kyc_Status = get_kyc_status ($id_user);

                    if ($kyc_Status == "kyc_pending") {
                        // KYC PENDING
                        $query_approve = "UPDATE `users` SET `kyc_status`='pending', `active_date`=NULL, `update_date`='$current_date' WHERE `user_id`='$id_user'";
                        $res = mysqli_query($conn,$query_approve);
                        $count_record_update = mysqli_affected_rows($conn);
                        // if ($count_record_update > 0) {
                        //     echo 'kyc_rejected';
                        //     exit;
                        // }
                    } else if ($kyc_Status == "kyc_done") {
                        // KYC DONE
                        $query_approve = "UPDATE `users` SET `kyc_status`='approved', `active_date`='$current_date', `update_date`='$current_date' WHERE `user_id`='$id_user'";
                        $res = mysqli_query($conn,$query_approve);
                        $count_record_update = mysqli_affected_rows($conn);
                    }
                    echo 'rejected';
                    exit;
                } else {
                    echo 'rejected_already';
                    exit;
                }
            }
        }
    }

    if ($action == "process_transaction") {
        if (isset($_POST['id_user']) && isset($_POST['updated_by']) && isset($_POST['txn_id']) && isset($_POST['action_to_perform']) && isset($_POST['reject_comment'])) {
            $id_user = mysqli_real_escape_string($conn,$_POST['id_user']);
            $updated_by = mysqli_real_escape_string($conn,$_POST['updated_by']);
            $txn_id = mysqli_real_escape_string($conn,$_POST['txn_id']);
            $action_to_perform = mysqli_real_escape_string($conn,$_POST['action_to_perform']);
            $reject_comment = mysqli_real_escape_string($conn,$_POST['reject_comment']);
    
            // $query_sponsor = "SELECT `users`.`user_id`, `sponsor_id`, (SELECT `wallets`.`wallet_investment` FROM `wallets` WHERE `user_id`=`users`.`user_id`) AS 'user_investment',(SELECT `wallets`.`wallet_investment` FROM `wallets` WHERE `user_id`=`users`.`sponsor_id`) AS 'sponsor_investment' FROM `users` LEFT JOIN `wallets` ON `wallets`.`user_id`=`users`.`sponsor_id` WHERE `users`.`user_id`='$id_user'";

            // echo "$action_to_perform >> $id_user >> ";
            if ($action_to_perform == "approved") {

               

                $query_sponsor = "SELECT `users`.`user_id` AS 'donor_id', `sponsor_id` AS 'receiver_id', (SELECT `wallets`.`wallet_investment` FROM `wallets` WHERE `user_id`=`users`.`sponsor_id`) AS 'sponsor_investment' FROM `users` LEFT JOIN `wallets` ON `wallets`.`user_id`=`users`.`sponsor_id` WHERE `users`.`user_id`='$id_user'";
                $query = mysqli_query($conn,$query_sponsor);
                $res = mysqli_fetch_array($query);
                extract($res);

                $query_user = "SELECT `transaction_amount` AS 'user_investment' FROM `fund_transaction` WHERE `id`='$txn_id'";
                $query = mysqli_query($conn,$query_user);
                $res = mysqli_fetch_array($query);
                extract($res);

                $query_approve = "UPDATE `fund_transaction` SET `status`='1',`updated_by`='$updated_by',`update_date`='$current_date' WHERE `id`='$txn_id'";
                $res = mysqli_query($conn,$query_approve);
                $count_record_update = mysqli_affected_rows($conn);
                if ($count_record_update > 0) {
                    $query_approve = "UPDATE `wallets` SET `wallet_investment`=`wallet_investment`+$user_investment, `update_date`='$current_date' WHERE `user_id` = '$id_user'";
                    $res = mysqli_query($conn,$query_approve);
                    $count_record_update = mysqli_affected_rows($conn);
                    if ($count_record_update > 0) {            
                        // ACTIVE INVESTMENT FOR BOTH USER AND SPONSOR
                            if ($sponsor_investment != 0 && $user_investment != 0) {
                                if (($sponsor_investment >= 151000) || ($sponsor_investment > $user_investment)) {
                                    $sponsor_commission = 0.05*$user_investment;
                                } else {
                                    // if ($sponsor_investment < $user_investment) {
                                        $sponsor_commission = 0.05*$sponsor_investment;
                                    // } else {
                                    //     $sponsor_commission = 0.05*$user_investment;
                                    // }
                                }
                                $sponsor_commission = round($sponsor_commission,2);

                                $query_insert_commission = "INSERT INTO `wallet_transaction`(`transaction_type`, `transaction_mode`, `user_id`, `from_user_id`, `transaction_amount`, `updated_by`, `create_date`) VALUES ('sponsor_commission','credit','$receiver_id','$donor_id','$sponsor_commission','$updated_by','$current_date')";
                                if (mysqli_query($conn,$query_insert_commission)) {
                                    $query_update_wallet = "UPDATE `wallets` SET `wallet_commission`=`wallet_commission`+$sponsor_commission, `update_date`='$current_date' WHERE `user_id` = '$receiver_id'";
                                    mysqli_query($conn,$query_update_wallet);
                                }
                            }
                        // ACTIVE INVESTMENT FOR BOTH USER AND SPONSOR

                        echo 'approved';
                        exit;
                    }
                }
            } else if ($action_to_perform == "rejected") {

              

                $query_approve = "UPDATE `fund_transaction` SET `status`='0',`comment`='$reject_comment',`updated_by`='$updated_by',`update_date`='$current_date' WHERE `id`='$txn_id'";
                $res = mysqli_query($conn,$query_approve);
                $count_record_update = mysqli_affected_rows($conn);
                if ($count_record_update > 0) {
                    echo 'rejected';
                    exit;
                }
            }
            
    
        }
    }

    if ($action == "go_to_member") {
        if (isset($_POST['member_id'])) {
            $result = mysqli_query($conn, "SELECT * FROM `users` WHERE `user_id`='$member_id'");
            if ($row = mysqli_fetch_array($result)) {
                $session_id = rand(1, 999999);
                $user_id = $row['user_id'];
                $name = $row['name'];
                $sponsor_id = $row['sponsor_id'];
                $is_active = $row['is_active'];
                $status = $row['status'];

                $_SESSION['user_id'] = $user_id;
                $_SESSION['sponsor_id'] = $sponsor_id;
                $_SESSION['session_id'] = $session_id;
                $_SESSION['name'] = $name;

                $user_id_encode = base64_encode(json_encode($user_id));     //ENCODE
                $name_encode = base64_encode(json_encode($name));     //ENCODE
                // $user_id = json_decode(base64_decode($_GET['user_id']));    //DECODE  
                
                $_SESSION['admin_access'] = true;

                // CREATE LOGIN SESSION
                if (mysqli_query($conn, "INSERT INTO `login_sessions` (`unique_id`,`session_id`,`login_date`,`create_date`) VALUES ('$user_id','$session_id','$current_date','$current_date') ")) {
                    echo "success";
                }

            }
        }
    }


    $user_id = "029689";
    function get_kyc_status ($user_id) {
        $conn = $GLOBALS['conn'];
        $query_doc = "SELECT * FROM `user_document` WHERE `user_id`='$user_id' ORDER BY `create_date` ASC";
        $res = mysqli_query($conn,$query_doc);
        $docs = mysqli_fetch_all($res,MYSQLI_ASSOC);

        $count_docs = $count_docs_approved = $count_docs_rejected = 0;

        $aadhaar_approved = $pan_approved = $cheque_approved = $passbook_approved = $address_approved = $photo_approved = 0;
        $aadhaar_rejected = $pan_rejected = $cheque_rejected = $passbook_rejected = $address_rejected = $photo_rejected = 0;
        $aadhaar_pending = $pan_pending = $cheque_pending = $passbook_pending = $address_pending = $photo_pending = 0;

        foreach ($docs as $item) {
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
                    // $photo_path1 = "$url_dir_aadhaar/$user_doc_file";
                    // $photo_path2 = "$url_dir_aadhaar/$user_doc_file2";
                    // $aadhaar_doc_front = "$url_dir_aadhaar/$user_doc_file";
                    // $aadhaar_doc_back = "$url_dir_aadhaar/$user_doc_file2";
                    $file_text = "Aadhaar Front";
                    // $aadhar_back = "<br>
                    //             <span class='badge bg-warning text-uppercase' style='white-space: normal;' data-bs-toggle='modal' data-bs-target='#ModalShowDoc' id='img-$user_doc_id' data-src='$photo_path2'>
                    //                 Aadhaar Back
                    //             </span>";
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
                    // $photo_path1 = "$url_dir_pan/$user_doc_file";
                    // $pan_doc = "$url_dir_pan/$user_doc_file";
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
                    // $photo_path1 = "$url_dir_bank/$user_doc_file";
                    // $bank_doc = "$url_dir_bank/$user_doc_file";
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
                    // $photo_path1 = "$url_dir_bank/$user_doc_file";
                    // $bank_doc = "$url_dir_bank/$user_doc_file";
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
                    // $photo_path1 = "$url_dir_photo/$user_doc_file";
                    // $photo = "$url_dir_photo/$user_doc_file";
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
                    $address_update =
                    $photo_update = 0;
                    $class_doc_type = "info";
                    break;                    
            }                
        }

        if ($pan_approved > 0 && $aadhaar_approved > 0 && $address_approved > 0 && ($cheque_approved > 0 || $passbook_approved > 0)) {
            return "kyc_done";
        } else {
            return "kyc_pending";
        }
    }




   

               





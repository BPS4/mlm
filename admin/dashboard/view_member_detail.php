<!DOCTYPE html>
<html lang="en">
<link rel="shortcut icon" href="../../assets/images/favicon.svg" type="image/x-icon">
<?php
    ob_start();
    require_once '../../db_connect.php';
    session_start();

    mysqli_query($conn, "set names 'utf8'"); //-------WORKING UTF8 CODE------//

    //-------CURRENT DATE AND TIME TO FEED---------//
    date_default_timezone_set('Asia/Kolkata');
    $current_date = date('Y-m-d H:i:s');

    extract($_REQUEST);

    // check login status
		// if (isset($_GET['name']) && isset($_SESSION['session_id_admin'])) {
		if (isset($_SESSION['session_id_admin'])) {
			// $name = json_decode(base64_decode($_GET['name']));    //DECODE
			// $name_encode = base64_encode(json_encode($name));     //ENCODE

			$user_id = $_SESSION['user_id_admin'];
			$session_id = $_SESSION['session_id_admin'];
			$name = $_SESSION['name_admin'];    //DECODE
			$name_encode = base64_encode(json_encode($name));     //ENCODE
			$user_id_encode = base64_encode(json_encode($user_id));     //ENCODE

			// $user_id = strtoupper($user_id);

			// USER AUTHENTICATION
				$session_id_pass = $_SESSION['session_id_admin'];
				$query = mysqli_query($conn, "SELECT `unique_id`, `session_id` FROM `login_sessions` WHERE `unique_id`='$user_id' ORDER BY `create_date` DESC LIMIT 1");
				if ($row = mysqli_fetch_array($query)) {
					$session_id = $row['session_id'];
					// $name = $row['name'];

					// ADMIN LOGIN MULTIPLE LOGIN ALLOWED 11-02-2022
					// if ($session_id_pass != $session_id) {
					//   echo "<script>alert('Session Expired. Login Again!!!');</script>";
					//   echo "Redirecting...Please Wait";
					//   header("Refresh:0, url=../logout/");
					//   exit;
					// }
				} else {
					echo "<script>alert('You Are Not An Authorised Person!!!');</script>";
					echo "Redirecting...Please Wait";
					header("Refresh:0, url=../logout/");
					exit;
				}
			// USER AUTHENTICATION
		} else {
			echo "<script>alert('You Are Not Logged In!!!');</script>";
			echo "Redirecting...Please Wait";
			header("Refresh:0, url=../logout/");
			exit;
		}
	// check login status

    // CHECK AVAILABILITY
        if (isset($_GET['uid']) && isset($_GET['mid'])) {
            $user_id = json_decode(base64_decode($_GET['uid']));    //--DECODE THE SECRET CODE PASSED--//
            $user_id_encode = base64_encode(json_encode($user_id));     //ENCODE
            
            $user_id_member = json_decode(base64_decode($_GET['mid']));    //--DECODE THE SECRET CODE PASSED--//
            $user_id_member_encode = base64_encode(json_encode($user_id_member));     //ENCODE
        } else {
            echo "<script>alert('Required Parameters Not Passed!!!');</script>";
            echo "Redirecting...Please Wait";
            header("Refresh:1, url=../dashboard");
            exit;
        }
    // CHECK AVAILABILITY

    // DEFAULT
        $bank_update = $address_update = $photo_update = $pan_update = $aadhaar_update = $fresh_txn_added_to_company = 0;

        $kyc_done = 0;

        $bank_cheque_sel = $bank_passbook_sel = "";
        $bank_disabled = $aadhaar_disabled = $pan_disabled = $photo_disabled = "";
        
        $url_dir = "../../assets/files/transaction";
        $url_dir_aadhaar = "../../assets/files/aadhaar";
        $url_dir_pan = "../../assets/files/pan";
        $url_dir_bank = "../../assets/files/bank";
        $url_dir_photo = "../../assets/files/photo";	

        $show_modal = 0;
        
        $msg = $msg_type = "";
		$error = false;

    if (isset($_POST['submit_approve'])) {
        $user_id_member = mysqli_real_escape_string($conn, $_POST['user_id_member']);
        $doc_id = mysqli_real_escape_string($conn, $_POST['doc_id']);
        
        $query_update_doc = "UPDATE `user_document` SET `status`='approved',`update_date`='$current_date' WHERE `id` IN ($doc_id) AND `status`='pending'";
        mysqli_query($conn,$query_update_doc);
        $count_record_update_doc = mysqli_affected_rows($conn);
        if ($count_record_update_doc > 0) {
            $query_update = "UPDATE `users` SET `status`='approved', `update_date`='$current_date' WHERE `user_id`='$user_id_member' AND `status`='pending'";
            mysqli_query($conn,$query_update);
            $count_record_update = mysqli_affected_rows($conn);
            if ($count_record_update == 1) {
                $msg = " >> Member KYC Approved Successfully!";
                $msg_type = "success";
            } else {
                $msg .= " >> Error in Approving Member Details or Already Approved...Try Again";
                $msg_type = "danger";
            }
        } else {
            $msg .= " >> Error in Approving KYC Documents Uploaded or Already Approved...Try Again";
            $msg_type = "danger";
        }        
    }

    if (isset($_POST['submit_reject'])) {
        $user_id_member = mysqli_real_escape_string($conn, $_POST['user_id_member']);
        $doc_id = mysqli_real_escape_string($conn, $_POST['doc_id']);

        $query_update_doc = "UPDATE `user_document` SET `status`='rejected',`update_date`='$current_date' WHERE `id` IN ($doc_id) AND `status`='pending'";
        mysqli_query($conn,$query_update_doc);
        $count_record_update_doc = mysqli_affected_rows($conn);
        if ($count_record_update_doc > 0) {
            $msg = " >> KYC Documents Rejected!";
            $msg_type = "danger";
        } else {
            $msg .= " >> Error in Rejecting KYC Documents or Already Rejected...Try Again";
            $msg_type = "default";
        }
    }

    if (isset($_POST['status'])) {
        $user_id_member = mysqli_real_escape_string($conn, $_POST['user_id_member']);
        $status = ($is_active == 0 ? 1 : 0);
        $user_status = ($is_active == 0 ? "active" : "inactive");
        $msg_type = ($is_active == 0 ? "success" : "danger");
        $msg_done = ($is_active == 0 ? "Activated" : "Deactivated");

        $query_status = "SELECT `status` AS 'user_status_current', `status_old` FROM `users` WHERE `user_id`='$user_id_member'";
        $query_status = mysqli_query($conn,$query_status);
        $res = mysqli_fetch_array($query_status);
        extract($res);

        // if ($status_old == "active" && $user_status_current == "active") {
        //     $user_status = ($is_active == 0 ? "active" : "inactive");
        // }

        if (mysqli_query($conn, "UPDATE `users` SET `is_active`='$status', `status_old`='$user_status_current', `status`='$user_status', `update_date`='$current_date' WHERE `user_id`='$user_id_member'")) {
            $msg = " >> User ID $msg_done Successfully!";
            // $msg_type = "danger";
        }
    }

    if (isset($_POST['submit_update'])) {
        $filename = $set_filename = "";
        /*IMAGE UPLOAD*/
        // Check if file was uploaded without errors
        if ((isset($_FILES["file"]) && $_FILES["file"]["error"] == 0)) {
            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "JPG" => "image/jpg", "JPEG" => "image/jpeg", "png" => "image/png", "PNG" => "image/png", "webp" => "image/webp");
            $filename = $_FILES["file"]["name"];
            $filetype = $_FILES["file"]["type"];
            $filesize = $_FILES["file"]["size"];

            // Verify file extension
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            
            // Extract FileName
            $file_basename = basename($filename, ".$ext");            

            if (!array_key_exists($ext, $allowed)) {
                $error = true;
                $msg .= " >> Only JPEG/JPG/PNG/WEBP Formats are Allowed!!!";
                $msg_type = "danger";
            }

            // Verify file size - 100kB maximum
            $minsize = 1 * 1024;
            $maxsize = 300 * 1024;

            if ($filesize > $maxsize || $filesize < $minsize) {
                $error = true;
                $msg .= " >> Error!!! File size should not be greater than 300kb.";
                $msg_type = "danger";
            }

            //----------IMAGE IS JPEG/JPG AND NO ERROR-----------//
            if (!$error) {
                // Check whether file exists before uploading it
                // if(file_exists("../../collegeportal/images/files/$batch" . $filename))
                // {
                //     $error_message = "$reg_no already registered!!!";
                // } 
                // else 
                $uploadedfile = $_FILES["file"]["tmp_name"];

                $current_timestamp = time();
                // $filename = $current_timestamp.".$ext";
                $filename = "$file_basename-$current_timestamp".".$ext";
                $file_dir = "$url_dir/$filename";                            
            } else {
                $msg .= " >> There was a problem uploading your record. Please try again.";
                $msg_type = "danger";
                
                goto error_occurred;
            }
        }

        if($filename!=""){
            $set_filename = "`url_file`='$filename',";
            // UPLOAD FILE
            if ((move_uploaded_file($_FILES['file']['tmp_name'], $file_dir))) {
                unlink($url_file);                
            }
        }        
        // correctImageOrientation($file_dir);
        /*IMAGE UPLOAD*/

        $res = mysqli_query($conn, "UPDATE `gallery` SET `title`='$title', $set_filename `update_date`='$current_date', `updated_by`='$user_id' WHERE `id`='$id'");
        if (mysqli_affected_rows($conn)>0) {
            $msg = "Record Updated Successfully!";
            $msg_type = "default";
        }else {
            $msg .= " >> Error in Updating The Record...Try Again";
            $msg_type = "danger";
        }
    }

    if (isset($_POST['btn_update'])) {
        $query = "SELECT `id` FROM `users` WHERE `mobile`='$u_mobile' AND `user_id`!='$u_id_user'";
        $res = mysqli_query($conn, $query);
        $count_mobile = mysqli_num_rows($res);
        if ($count_mobile > 0) {
            $msg = "Mobile No. $u_mobile Already Registered! Retry with Another...";
            $msg_type = "danger";
            goto error_occurred;
        }
        
        $query = "SELECT `id` FROM `users` WHERE `whatsapp`='$u_whatsapp' AND `user_id`!='$u_id_user'";
        $res = mysqli_query($conn, $query);
        $count_whatsapp = mysqli_num_rows($res);
        if ($count_whatsapp > 0) {
            $msg = "Whatsapp No. $u_whatsapp Already Registered! Retry with Another...";
            $msg_type = "danger";
            goto error_occurred;
        }

        $query = "SELECT `id` FROM `users` WHERE `email`='$u_email' AND `user_id`!='$u_id_user'";
        $res = mysqli_query($conn, $query);
        $count_email = mysqli_num_rows($res);
        if ($count_email > 0) {
            $msg = "Email ID $u_email Already Registered! Retry with Another...";
            $msg_type = "danger";
            goto error_occurred;
        }
        
        $res = mysqli_query($conn, "UPDATE `users` SET `name`='$u_name', `fhname`='$u_fhname', `mobile`='$u_mobile', `whatsapp`='$u_whatsapp', `email`='$u_email', `update_date`='$current_date' WHERE `user_id`='$u_id_user'");
        if (mysqli_affected_rows($conn)>0) {
            $msg = "Record Updated Successfully!";
            $msg_type = "default";
            stop_form_resubmit();
        }else {
            $msg .= " >> Error in Updating The Record...Try Again";
            $msg_type = "danger";
        }
    }

    $set_wallet = "";
    if (isset($_POST['btn_submit_fund'])) {
        $user_id_member = mysqli_real_escape_string($conn,$_POST['id_user']);
        
        $max_wallet_roi = mysqli_real_escape_string($conn,$_POST['max_wallet_roi']);
        $max_wallet_commission = mysqli_real_escape_string($conn,$_POST['max_wallet_commission']);
        $max_wallet_investment = mysqli_real_escape_string($conn,$_POST['max_wallet_investment']);
        
        $choose_action = mysqli_real_escape_string($conn,$_POST['choose_action']);

        $select_wallet = mysqli_real_escape_string($conn,$_POST['select_wallet']);
        $invest_fund = mysqli_real_escape_string($conn,$_POST['invest_fund']);

        // select_wallet_roi
        // select_wallet_commission
        // select_wallet_investment

        if ($choose_action == "invest") {
            if ($select_wallet == "select_wallet_roi") {
                $query_invest = "INSERT INTO `wallet_transaction`(`transaction_type`, `transaction_mode`, `user_id`, `transaction_amount`, `updated_by`, `create_date`) VALUES ('admin_roi','credit','$user_id_member','$invest_fund','$user_id','$current_date')";
                $wallet_update = "`wallet_roi` = ROUND((`wallet_roi`+$invest_fund),2)";
            } else if ($select_wallet == "select_wallet_commission") {
                $query_invest = "INSERT INTO `wallet_transaction`(`transaction_type`, `transaction_mode`, `user_id`, `transaction_amount`, `updated_by`, `create_date`) VALUES ('admin_commission','credit','$user_id_member','$invest_fund','$user_id','$current_date')";
                $wallet_update = "`wallet_commission` = ROUND((`wallet_commission`+$invest_fund),2)";
            } else if ($select_wallet == "select_wallet_investment") {
                $query_invest = "INSERT INTO `fund_transaction`(`transaction_type`, `user_id`, `transaction_mode`, `transaction_amount`, `transaction_id`, `status`, `updated_by`, `create_date`) VALUES ('admin_credit', '$user_id_member', '1', '$invest_fund', 'admin-investment', '1', '$user_id', '$current_date')";
                $wallet_update = "`wallet_investment` = ROUND((`wallet_investment`+$invest_fund),2)";
            }
        } else if ($choose_action == "deduct") {
            if ($select_wallet == "select_wallet_roi") {
                if ($max_wallet_roi < $invest_fund) {
                    $msg .= "Deducted Amount is more than Max Limit $max_wallet_roi Retry with Lower Amount...";
                    $msg_type = "danger";
                    goto error_occurred;
                }
                $query_invest = "INSERT INTO `wallet_transaction`(`transaction_type`, `transaction_mode`, `user_id`, `transaction_amount`, `updated_by`, `create_date`) VALUES ('admin_roi','debit','$user_id_member','$invest_fund','$user_id','$current_date')";
                $wallet_update = "`wallet_roi` = ROUND((`wallet_roi`-$invest_fund),2)";
            } else if ($select_wallet == "select_wallet_commission") {
                if ($max_wallet_commission < $invest_fund) {
                    $msg .= "Deducted Amount is more than Max Limit $max_wallet_commission Retry with Lower Amount...";
                    $msg_type = "danger";
                    goto error_occurred;
                }
                $query_invest = "INSERT INTO `wallet_transaction`(`transaction_type`, `transaction_mode`, `user_id`, `transaction_amount`, `updated_by`, `create_date`) VALUES ('admin_commission','debit','$user_id_member','$invest_fund','$user_id','$current_date')";
                $wallet_update = "`wallet_commission` = ROUND((`wallet_commission`-$invest_fund),2)";
            } else if ($select_wallet == "select_wallet_investment") {
                if ($max_wallet_investment < $invest_fund) {
                    $msg .= "Deducted Amount is more than Max Limit $max_wallet_investment Retry with Lower Amount...";
                    $msg_type = "danger";
                    goto error_occurred;
                }
                $query_invest = "INSERT INTO `fund_transaction`(`transaction_type`, `user_id`, `transaction_mode`, `transaction_amount`, `transaction_id`, `status`, `updated_by`, `create_date`) VALUES ('admin_debit', '$user_id_member', '1', '$invest_fund', 'admin-investment', '1', '$user_id', '$current_date')";
                $wallet_update = "`wallet_investment` = ROUND((`wallet_investment`-$invest_fund),2)";
            }
        }
        
        if (mysqli_query($conn,$query_invest)) {
            $query_sponsor = "SELECT `users`.`user_id` AS 'donor_id', `sponsor_id` AS 'receiver_id', (SELECT `wallets`.`wallet_investment` FROM `wallets` WHERE `user_id`=`users`.`sponsor_id`) AS 'sponsor_investment' FROM `users` LEFT JOIN `wallets` ON `wallets`.`user_id`=`users`.`sponsor_id` WHERE `users`.`user_id`='$user_id_member'";
            $query = mysqli_query($conn,$query_sponsor);
            $res = mysqli_fetch_array($query);
            extract($res);

            $user_investment = $invest_fund;

            $query_update_user_wallet = "UPDATE `wallets` SET $wallet_update, `update_date`='$current_date' WHERE `user_id`='$user_id_member'";
            $res = mysqli_query($conn,$query_update_user_wallet);
            $count_record_update = mysqli_affected_rows($conn);            
            if ($count_record_update > 0) {

                if ($choose_action == "invest") {
                    if ($select_wallet == "select_wallet_investment") {
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

                $msg = "Investment Updated Successfully!";
                $msg_type = "default";
                stop_form_resubmit();
            } else {
                $msg .= " >> Error in Updating The Investment...Try Again";
                $msg_type = "danger";
            }
        }
    }
    
    function get_user_name($conn,$user_id) {
        $query_get_data = "SELECT `name` FROM `users` WHERE `user_id`='$user_id'";
        $res = mysqli_query($conn, $query_get_data);
        if ($row = mysqli_fetch_array($res)) {
            $name = $row['name'];
            return($name);
        }
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghjkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    error_occurred:
	
    // GET USER DATA
        $query = "SELECT `sponsor_id`, `user_id` AS 'member_id', `name` AS 'member_name', `fhname`, `mobile`, `whatsapp`, `email`, `aadhaar`, `aadhaar_file`, `pan`, `pan_file`, `bank_name`, `branch_name`, `account_no`, `ifs_code`, `upi_handle`, `address`, `city`, `landmark`, `state` AS 'state_name', `pin_code`, `kyc_status`, `status`, `is_active`, `is_bank_updated`, `create_date` FROM `users` WHERE `user_id`='$user_id_member'";    
        $query = mysqli_query($conn, $query);
        $res = mysqli_fetch_array($query);
        extract($res);
        
        //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT
            $regEx = '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/';
            // preg_match($regEx, $details['date'], $result);
            if (preg_match($regEx, $create_date) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $create_date)) {
                $date = date_create($create_date);
                $create_date = date_format($date, "d M Y h:ia");
                $joining_date = date_format($date, "d-M-Y");
            }
        //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT

        if ($sponsor_id != "") {
            $sponsor_name = get_user_name($conn, $sponsor_id);
        } else {
            $sponsor_name = $member_name;
            $sponsor_id = $member_id;
        }        

        // GET DATA OF SAME CATEGORY BY ONLY LAST CREATE DATE
        // $query_doc = "SELECT ud.* FROM `user_document` ud INNER JOIN (SELECT `doc_type`, MAX(`create_date`) AS max_date FROM `user_document` WHERE `user_id`='$member_id' GROUP BY `doc_type`) group_data ON ud.`doc_type`=group_data.`doc_type` AND ud.`create_date`=group_data.max_date";
        $query_doc = "SELECT * FROM `user_document` WHERE `user_id`='$member_id' ORDER BY `create_date` DESC";
        $res = mysqli_query($conn,$query_doc);
        $docs = mysqli_fetch_all($res,MYSQLI_ASSOC);
        $count_docs = count($docs);
        
        $pending_docs = $approved_docs = $rejected_docs = $count_docs_status = 0;

        // $query_doc_status = "SELECT IF(`status`='pending',COUNT(`status`),0) AS 'pending_docs', IF(`status`='approved',COUNT(`status`),0) AS 'approved_docs', IF(`status`='rejected',COUNT(`status`),0) AS 'rejected_docs' FROM `user_document` WHERE `user_id`='$member_id' GROUP BY `status`";
        // $res = mysqli_query($conn,$query_doc_status);
        // $count_docs_status = mysqli_num_rows($res);
        // $res = mysqli_fetch_array($res);
        // extract($res);

        $query_doc = "SELECT COUNT(`id`) AS 'pending_docs' FROM `user_document` WHERE `user_id`='$member_id' AND `status`='pending'";
        $res = mysqli_fetch_array(mysqli_query($conn,$query_doc));
        extract($res);
        $query_doc = "SELECT COUNT(`id`) AS 'approved_docs' FROM `user_document` WHERE `user_id`='$member_id' AND `status`='approved'";
        $res = mysqli_fetch_array(mysqli_query($conn,$query_doc));
        extract($res);
        $query_doc = "SELECT COUNT(`id`) AS 'rejected_docs' FROM `user_document` WHERE `user_id`='$member_id' AND `status`='rejected'";
        $res = mysqli_fetch_array(mysqli_query($conn,$query_doc));
        extract($res);

        $count_docs_status = $pending_docs + $approved_docs + $rejected_docs;
    // GET USER DATA
    
    $classStatus = ($is_active == 0) ? "bg-danger" : "bg-success";

    // DEFAULT ORDER COLUMN SR
        $default_Order = 0;
            
    // LENGTH MENU
        // $length_menu = "all";

    $total_count = mysqli_num_rows($query);

    // USED FOR CENTER THE TEXT PDF EXPORT
        echo "<input type='hidden' id='total_count' value='$total_count'>";
    
    if ($count_docs_status == 0) {
        echo "<script>alert('No KYC Documents Uploaded By The Member!!! Try Another Member');</script>";
        // echo "Redirecting...Please Wait";
        // header("Refresh:1, url=view_member_total.php");
        // exit;
    } else {
        // if (($pending_docs + $approved_docs + $rejected_docs) < 4) {
            // echo "<script>alert('All KYC Documents Are Not Uploaded By The Member!!! Only $pending_docs Documents Uploaded. Contact Member For Document Upload.');</script>";
            // echo "Redirecting...Please Wait";
            // header("Refresh:1, url=view_member_total.php");
            // exit;
        // }
    }

    $doc_id = "";
    foreach ($docs as $item) {
        $user_doc_id = $item['id'];
        $user_doc_type = $item['doc_type'];
        $user_doc_number = $item['doc_number'];
        $user_doc_file = $item['doc_file'];
        $user_doc_file2 = $item['doc_file2'];
        $user_doc_status = $item['status'];
        
        $doc_id .= "$user_doc_id,";

        if ($user_doc_status == "rejected") {
            continue;
        }
        
        switch ($user_doc_type) {
            case 'aadhaar':
                $aadhaar_update = 1;
                $aadhaar = $user_doc_number;
                $aadhaar_doc_front = "$url_dir_aadhaar/$user_doc_file";
                $aadhaar_doc_back = "$url_dir_aadhaar/$user_doc_file2";
                $aadhaar_disabled = "disabled";
                break;
            
            case 'pan':
                $pan_update = 1;
                $pan = $user_doc_number;
                $pan_doc = "$url_dir_pan/$user_doc_file";
                $pan_disabled = "disabled";
                break;
            
            case 'cheque':
                $bank_update = 1;
                $account_no = $user_doc_number;
                $bank_doc = "$url_dir_bank/$user_doc_file";
                $bank_cheque_sel = "selected";
                $bank_disabled = "disabled";
                break;
            
            case 'passbook':
                $bank_update = 1;
                $account_no = $user_doc_number;
                $bank_doc = "$url_dir_bank/$user_doc_file";
                $bank_passbook_sel = "selected";
                $bank_disabled = "disabled";
                break;
            
            case 'address':
                $address_update = 1;
                $address_disabled = "disabled";
                break;
            
            case 'photo':
                $photo_update = 1;
                $photo = "$url_dir_photo/$user_doc_file";
                $photo_disabled = "disabled";
                break;
            
            default:
                $aadhaar_update = 
                $pan_update = 
                $bank_update = 
                $address_update = 
                $photo_update = 0;
                break;
        }
    }

    if ($doc_id != "") {
        $doc_id = rtrim($doc_id,",");
    }

    if ($aadhaar_update == 1 && $pan_update == 1) {
        $kyc_done = 1;
    }

    if ($pan_update == 0) {
        $pan = "PENDING";
    }

    if ($aadhaar_update == 0) {
        $aadhaar = "PENDING";
    }

    $wallet_investment = $wallet_roi = $wallet_commission = $wallet_fd = 0;

    // GET WALLET DATA
        $query_wallet = "SELECT `wallet_investment`, `wallet_roi`, `wallet_commission`, `wallet_fd`, `superwallet` FROM `wallets` WHERE `user_id`='$user_id_member'";
        $query = mysqli_query($conn, $query_wallet);
        $res = mysqli_fetch_array($query);
        extract($res);  
    // GET WALLET DATA

    $fund_total = $fund_available = $wallet_investment + $wallet_roi + $wallet_commission;

?>

<head>
    <?php include_once('head.php'); ?>

    <title><?php  echo "Member Details - Admin - Maxizone"; ?></title>

    <link rel="canonical" href="view_member_detail.php">

    <style>
        @page {
            size: ledger landscape;
            /*//auto, portrait, landscape or length (2 parameters width and height. sets both equal if only one is provided. % values not allowed.)*/

            margin-top: 0.5cm;
            margin-bottom: 0.5cm;
            margin-left: 0.5cm;
            margin-right: 0.5cm;
        }
        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
            .dataTables_length, .dataTables_filter, .dt-buttons, .no_print {
                display: none;
                visibility: hidden;
            }
        }
        .form-control {
            background: white !important;
        }
    </style>
</head>

<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-behavior="sticky">
    <div class="wrapper">
        <?php include_once('sidebar.php'); ?>

        <div class="main">
            <?php include_once('navbar.php'); ?>

            <main class="content">
                <div class="container-fluid p-0">

                    <h1 class="h3 mb-3">Member Profile</h1>

                    <div class="row">
                        <div class="col-md-12 col-xl-12">
                            <div class="card mb-2">
                                <div class="card-header pb-0">
                                    <span style="position:absolute;left:0px;margin-left:15px;">
                                        <a href='./' class="badge bg-warning fs-100" style="text-decoration:none;">
                                            Dashboard
                                        </a>
                                        <b>
                                            <i class="align-middle" data-feather="chevrons-right"></i>
                                        </b>
                                        <a href="view_member_total.php" class="badge bg-success fs-100" style="text-decoration:none;">
                                            Members
                                        </a>
                                        <b>
                                            <i class="align-middle" data-feather="chevrons-right"></i>
                                        </b>
                                        <a href="view_member.php" class="badge bg-danger fs-100" style="text-decoration:none;">
                                            Pending KYC
                                        </a>
                                        <a href="view_member.php?type=<?php echo base64_encode(json_encode("kyc_done")); ?>" class="badge bg-info fs-100" style="text-decoration:none;">
                                            Approved KYC
                                        </a>
                                        <b>
                                            <i class="align-middle" data-feather="chevrons-right"></i>
                                        </b>
                                        <a class="badge bg-warning fs-100" style="text-decoration:none;">
                                            Member Details
                                        </a>
                                        <b>
                                            <i class="align-middle" data-feather="chevrons-right"></i>
                                        </b>
                                        <a href="view_member.php?type=<?php echo base64_encode(json_encode("inactive")); ?>" class="badge bg-dark fs-100" style="text-decoration:none;">
                                            Inactive Members
                                        </a>
                                    </span>
                                    <br>
                                    <h5 class="card-title mt-3 mb-0">Personal info</h5>
                                </div>
                                <div class="card-body">                                                
                                    <div class="row">                                                
                                        <div class="mb-3 col-md-6">
                                            <label for="sponsor_id" class="form-label">Sponsor ID</label>
                                            <input type="text" class="form-control" id="sponsor_id" readonly value="<?php echo $sponsor_id;?>">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="sponsor_name" class="form-label">Sponsor Name</label>
                                            <input type="text" class="form-control" id="sponsor_name" readonly value="<?php echo $sponsor_name;?>">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="user_id_member" class="form-label">User ID</label>
                                            <input type="text" class="form-control" id="user_id_member" readonly value="<?php echo $user_id_member;?>">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="user_name" class="form-label">User Name</label>
                                            <input type="text" class="form-control" id="user_name" readonly value="<?php echo $member_name;?>">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="aadhaar" class="form-label">Aadhaar No.</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="aadhaar" readonly value="<?php echo $aadhaar;?>">
                                                <a class="btn btn-primary" data-bs-toggle='modal' data-bs-target='#ModalShowPhoto' data-src="<?php echo $aadhaar_doc_front; ?>" data-src1="<?php echo $aadhaar_doc_back; ?>" data-from="kyc">View</a>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3 col-md-6">
                                            <label for="pan" class="form-label">PAN No.</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="pan" readonly value="<?php echo $pan;?>">
                                                <a class="btn btn-primary" data-bs-toggle='modal' data-bs-target='#ModalShowPhoto' data-src="<?php echo $pan_doc; ?>" data-from="kyc">View</a>
                                            </div>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="mobile" class="form-label">Mobile</label>
                                            <input type="number" class="form-control" name="mobile" id="mobile" placeholder="Mobile" value="<?php echo $mobile;?>" required readonly>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $email;?>" required readonly>
                                        </div>
                                        
                                        <div class="mb-3 col-md-6">
                                            <label for="fhname" class="form-label">Father/Husband Name</label>
                                            <input type="text" class="form-control" name="fhname" id="fhname" placeholder="Father/Husband Name" value="<?php echo $fhname;?>" required readonly>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="whatsapp" class="form-label">Whatsapp</label>
                                            <input type="number" class="form-control" name="whatsapp" id="whatsapp" placeholder="Whatsapp" value="<?php echo $whatsapp;?>" required readonly>
                                        </div>
                                        
                                        <div class="mb-3 col-md-12">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea rows="2" class="form-control" id="address" name="address" placeholder="Your complete address" required readonly><?php echo $address;?></textarea>
                                        </div>
                                        <div class="mb-3 col-md-12">
                                            <label for="address" class="form-label">Lankmark</label>
                                            <input type="text" class="form-control" id="landmark" name="landmark" placeholder="Landmark" required readonly><?php echo $landmark;?></textarea>
                                        </div>

                                        <div class="mb-3 col-md-5">
                                            <label for="city" class="form-label">City</label>
                                            <input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo $city;?>" readonly>
                                        </div>
                                        <div class="mb-3 col-md-4">
                                            <label for="state" class="form-label">State</label>
                                            <select class="form-control" id="state_name" name="state_name" required disabled>
                                                <option value="" hidden>Choose State</option>
                                                <?php
                                                    $res = mysqli_query($conn, "SELECT `state_name` FROM `indian_states` ORDER BY `state_name` ASC");
                                                    $states = mysqli_fetch_all($res, MYSQLI_ASSOC);
                                                    foreach ($states as $state) {
                                                        $state_name_fetch = $state['state_name'];
                                                        $selected = "";
                                                        if ($state_name_fetch == $state_name) {
                                                            $selected = "selected='selected'";
                                                        }
                                                        ?>
                                                            <option value="<?php echo $state_name_fetch;?>" <?php echo $selected; ?>><?php echo $state_name_fetch;?></option>
                                                        <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-2">
                                            <label for="pin_code" class="form-label">PIN Code</label>
                                            <input type="text" class="form-control" id="pin_code" name="pin_code" placeholder="PIN Code" required value="<?php echo $pin_code;?>" readonly>
                                        </div>
                                        <div class="mb-3 col-md-1">
                                            <label for="photo" class="form-label">Photo</label>
                                            <br>
                                            <a class="btn btn-primary" data-bs-toggle='modal' data-bs-target='#ModalUploadPhoto' id="btn_photo">View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Banking info</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row">                                                    
                                            <div class="mb-3 col-xl-6">
                                                <label for="bank_name" class="form-label">Bank Name</label>
                                                <select class="form-control " id="bank_name" name="bank_name" required disabled>
                                                    <option value="" hidden>Choose Bank</option>
                                                    <?php
                                                        $res = mysqli_query($conn, "SELECT `id` AS 'bank_name_id', `name` AS 'bank_name' FROM `bank_names` ORDER BY `name` ASC");
                                                        $banks = mysqli_fetch_all($res, MYSQLI_ASSOC);
                                                        foreach ($banks as $bank) {
                                                            $bank_name_fetch = $bank['bank_name'];
                                                            $selected = "";
                                                            if ($bank_name_fetch == $bank_name) {
                                                                $selected = "selected='selected'";
                                                            }
                                                            ?>
                                                                <option value="<?php echo $bank_name_fetch;?>" <?php echo $selected; ?>><?php echo $bank_name_fetch;?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="mb-3 col-xl-6">
                                                <label for="bank_name_other" class="form-label">Other Bank (Specify Here)</label>
                                                <input type="text" class="form-control" name="bank_name_other" id="bank_name_other" placeholder="In case of other Bank, specify here" value="<?php echo $bank_name;?>" readonly required>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="branch_name" class="form-label">Branch Name</label>
                                                <input type="text" class="form-control" name="branch_name" id="branch_name" placeholder="Branch" value="<?php echo $branch_name;?>" readonly>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="account_no" class="form-label">Account Number</label>
                                                <input type="text" class="form-control" name="account_no" id="account_no" placeholder="Account Number" value="<?php echo $account_no;?>" readonly>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="ifs_code" class="form-label">IFSC Code</label>
                                                <input type="text" class="form-control" name="ifs_code" id="ifs_code" placeholder="IFSC Code" value="<?php echo $ifs_code;?>" readonly>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="upi_handle" class="form-label">UPI Handle (@ybl, @paytm etc...)</label>
                                                <input type="text" class="form-control" name="upi_handle" id="upi_handle" placeholder="UPI" value="<?php echo $upi_handle;?>" readonly>
                                            </div>
                                            <div class="mb-3 col-xl-7 col-md-4">
                                                <label for="doc_type" class="form-label">Bank Proof <span class="text-danger">*</span></label>
                                                <select class="select2 form-select" name="doc_type" id="doc_type" required <?php echo $bank_disabled; ?>>
                                                    <option value="" hidden>Choose Document Type</option>
                                                    <option value="cheque" <?php echo $bank_cheque_sel; ?>>Cancelled Cheque</option>
                                                    <option value="passbook" <?php echo $bank_passbook_sel; ?>>Passbook</option>
                                                </select>
                                            </div>
                                            <?php
                                                if ($bank_update == 2) {
                                                    ?>
                                                        <div class="mb-3 col-xl-5 col-md-8">
                                                            <label for="file_bank_proof" class="form-label">Proof Photo <span class="text-danger">*jpg/jpeg/png format; 500KB max</span></label>
                                                            <input type="file" name="file" id="file_bank_proof" class="form-control" accept="image/*" required onchange="check_size(this.id,500);">
                                                        </div>
                                                    <?php
                                                } else {
                                                    ?>
                                                        <div class="mb-3 col-xl-5 col-md-8">
                                                            <label for="file_bank_proof" class="form-label">Uploaded Photo</label>
                                                            <!-- <span class='mt-2 badge bg-info show-pointer' > -->
                                                            <br>
                                                            <a class="btn btn-primary" data-bs-toggle='modal' data-bs-target='#ModalShowPhoto' data-src="<?php echo $bank_doc; ?>" data-from="bank">View</a>

                                                            <!-- <input type="text" class="form-control bg-success show-pointer" data-bs-toggle='modal' data-bs-target='#ModalShowPhoto' data-src="<?php echo $bank_doc; ?>" data-from="bank" value="View Now" disabled> -->
                                                        </div>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <div class="no_print" id="loader"></div>
                                    <div class="card">
                                        <div class="card-header no_print" style="margin-bottom:0px;padding: 0.5rem 1.25rem;">
                                            <h5 class="card-title">Total Records Present <span class="badge bg-success" style="font-weight: bold;"><?php echo $count_docs; ?></span></h5>
                                            <?php
                                                if ($kyc_status == "pending") {
                                                    ?>
                                                        <button onclick="go_to_member_profile('<?php echo $user_id_member; ?>')" type="button" class="badge btn btn-success my-1" style="text-decoration:none;position:absolute;right:0px;margin:15px;">
                                                            <i class="align-middle" data-feather="user-plus" style="border-radius: 50%;margin-right:5px;"></i>
                                                            Add Documents
                                                        </button>
                                                    <?php
                                                }
                                            ?>
                                            <a class='btn btn-info badge rounded show-pointer' data-bs-toggle="modal" data-bs-target="#ModalUpdate" data-user_id="<?php echo $user_id_member; ?>" data-mobile="<?php echo $mobile; ?>" data-email="<?php echo $email; ?>" data-name="<?php echo $member_name; ?>" data-father="<?php echo $fhname; ?>" data-whatsapp="<?php echo $whatsapp; ?>">Update Details</a>
                                            
                                            <a class='btn btn-danger badge rounded show-pointer' data-bs-toggle="modal" data-bs-target="#ModalFinance">Add/Deduct Funds</a>
                                        </div>
                                        <div class="card-body" style="padding:0.5rem;">
                                            <form action="" method="post" id="formChoiceAllot">
                                                <table id="datatables" class="table table-striped" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr</th>
                                                            <th class="no_print">Action</th>
                                                            <th style="white-space: nowrap;">User ID</th>
                                                            <th>Doc Name</th>
                                                            <th>Doc Number</th>
                                                            <th>File</th>
                                                            <th>Status</th>
                                                            <th>Uploaded On</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tableBody">
                                                        <?php
                                                        $i = 0;
                                                        foreach ($docs as $item) {
                                                            $i++;
                                                            
                                                            //DEFAULT VALUES SET
                                                            $class_center = "has-html text-center";
                                                            
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
                                                            
                                                            $doc_id .= "$user_doc_id,";
                                                    
                                                            $aadhar_back = "";
                                                            switch ($user_doc_type) {
                                                                case 'aadhaar':
                                                                    $aadhaar_update = 1;
                                                                    $aadhaar = $user_doc_number;
                                                                    $photo_path1 = "$url_dir_aadhaar/$user_doc_file";
                                                                    $photo_path2 = "$url_dir_aadhaar/$user_doc_file2";
                                                                    $file_text = "Aadhaar Front";
                                                                    $aadhar_back = "<br>
                                                                                <span class='badge bg-warning text-uppercase' style='white-space: normal;' data-bs-toggle='modal' data-bs-target='#ModalShowDoc' id='img-$user_doc_id' data-src='$photo_path2'>
                                                                                    Aadhaar Back
                                                                                </span>";
                                                                    $class_doc_type = "success";
                                                                    break;
                                                                
                                                                case 'pan':
                                                                    $pan_update = 1;
                                                                    $pan = $user_doc_number;
                                                                    $photo_path1 = "$url_dir_pan/$user_doc_file";
                                                                    $file_text = "PAN";
                                                                    $class_doc_type = "warning";
                                                                    break;
                                                                
                                                                case 'cheque':
                                                                    $bank_update = 1;
                                                                    $account_no = $user_doc_number;
                                                                    $photo_path1 = "$url_dir_bank/$user_doc_file";
                                                                    $bank_cheque_sel = "selected";
                                                                    $file_text = "Cancelled Cheque";
                                                                    $class_doc_type = "primary";
                                                                    break;
                                                                
                                                                case 'passbook':
                                                                    $bank_update = 1;
                                                                    $account_no = $user_doc_number;
                                                                    $photo_path1 = "$url_dir_bank/$user_doc_file";
                                                                    $bank_passbook_sel = "selected";
                                                                    $file_text = "Passbook";
                                                                    $class_doc_type = "primary";
                                                                    break;
                                                                
                                                                case 'address':
                                                                    $address_update = 1;
                                                                    $address = $user_doc_number;
                                                                    $photo_path1 = "";
                                                                    $file_text = "Address";
                                                                    $class_doc_type = "danger";
                                                                    break;
                                                                    
                                                                case 'photo':
                                                                    $photo_update = 1;
                                                                    $photo_path1 = "$url_dir_photo/$user_doc_file";
                                                                    $file_text = "Photo";
                                                                    $class_doc_type = "danger";
                                                                    break;
                                                                
                                                                default:
                                                                    $aadhaar_update = 
                                                                    $pan_update = 
                                                                    $bank_update = 
                                                                    $photo_update = 0;
                                                                    $class_doc_type = "info";
                                                                    break;
                                                            }
                                                            
                                                            switch ($user_doc_status) {
                                                                case 'pending':
                                                                    $class_doc_status = "warning";
                                                                    break;
                                                                
                                                                case 'approved':
                                                                    $class_doc_status = "success";
                                                                    break;
                                                                
                                                                case 'rejected':
                                                                    $class_doc_status = "danger";
                                                                    break;
                                                                
                                                                default:
                                                                    $class_doc_status = "info";
                                                                    break;
                                                            }

                                                            $class_txn = "success";
                                                            
                                                            //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT
                                                                $regEx = '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/';
                                                                // preg_match($regEx, $details['date'], $result);
                                                                if (preg_match($regEx, $create_date) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $create_date)) {
                                                                    $create_date = date_create($create_date);
                                                                    $create_date = date_format($create_date, "d M Y h:ia");
                                                                }
                                                            //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT

                                                            // PHOTO PATH
                                                                // $photo_path1 = $photo_path2 = "";
                                                                
                                                            ?>
                                                                <!-- REPEAT ITEM -->
                                                                <tr>
                                                                    <td class="text-center"><?php echo $i; ?></td>
                                                                    <td class="no_print text-center">
                                                                        <?php
                                                                            if ($user_doc_status == 'pending') {
                                                                                ?>
                                                                                    <!-- <input type="hidden" name="approve_transaction[<?php echo $user_doc_id;?>]" value=""> -->
                                                                                    <!-- <input type="checkbox" id="approve_transaction_<?php echo $user_doc_id;?>" name="approve_transaction[]" class="checkSingle" value="<?php echo $user_doc_id;?>">
                                                                                    <label for="approve_transaction_<?php echo $user_doc_id;?>"><?php echo $user_id_member;?></label> -->
                                                                                    
                                                                                    <!-- <span class='badge bg-success rounded-pill show-pointer' style="background: #E07C24 !important;" data-bs-toggle="modal" data-bs-target="#ModalAction">Approve</span> -->
                                                                                    <span class='badge bg-success rounded show-pointer' style="background: #E07C24 !important; font-size: larger;" data-bs-toggle="modal" data-bs-target="#ModalAction" data-id="<?php echo $user_doc_id; ?>" data-user_id="<?php echo $user_id_member; ?>" data-force_reject="0" data-force_approve="0">Approve/Reject</span>

                                                                                    <!-- <span class='badge bg-danger rounded-pill show-pointer' style="background: #6A1B4D !important;">Reject</span> -->

                                                                                <?php
                                                                            } else if ($user_doc_status == 'approved') {
                                                                                ?>
                                                                                    <span class='badge bg-danger rounded-pill' style="background: #6A1B4D !important;">Approved</span>
                                                                                    <span class='badge bg-dark rounded show-pointer' data-bs-toggle="modal" data-bs-target="#ModalAction" data-id="<?php echo $user_doc_id; ?>" data-user_id="<?php echo $user_id_member; ?>" data-force_reject="1" data-force_approve="0">Reject Now</span>
                                                                                <?php
                                                                            } else if ($user_doc_status == 'rejected') {
                                                                                ?>
                                                                                    <span class='badge bg-danger rounded-pill'>Rejected</span>
                                                                                    <!-- <span class='badge bg-danger rounded show-pointer' data-bs-toggle="modal" data-bs-target="#ModalAction" data-id="<?php echo $user_doc_id; ?>" data-user_id="<?php echo $user_id_member; ?>" data-force_reject="0" data-force_approve="1">Reject</span> -->
                                                                                <?php
                                                                            }
                                                                        ?>
                                                                    </td>
                                                                    <td class="has-html text-center">
                                                                        <span>
                                                                            <?php echo $user_id_member; ?>
                                                                            <input type="hidden" id="txn_user_<?php echo $user_doc_id;?>" name="txn_user_id_member[]" class="" value="<?php echo $user_id_member;?>">
                                                                        </span>
                                                                    </td>
                                                                    <td class="text-center"><span class="text-uppercase badge bg-<?php echo $class_doc_type; ?>"><?php echo $user_doc_type; ?></span></td>
                                                                    <td class="text-center"><?php echo $user_doc_number; ?></td>
                                                                    <td class="has-html text-center show-pointer no-wrap">
                                                                        <?php
                                                                            if($user_doc_type == "address") {
                                                                                ?>
                                                                                    <span class="badge bg-<?php echo $class_doc_type; ?> text-uppercase">
                                                                                        <?php echo $file_text; ?>
                                                                                    </span>
                                                                                <?php
                                                                            } else {
                                                                                ?>
                                                                                    <span class="badge bg-<?php echo $class_doc_type; ?> text-uppercase" data-bs-toggle='modal' data-bs-target='#ModalShowDoc' id="img-<?php echo $user_doc_id;?>" data-src='<?php echo $photo_path1; ?>'>
                                                                                        <?php echo $file_text; ?>
                                                                                    </span>
                                                                                    <?php echo $aadhar_back; ?>
                                                                                <?php
                                                                            }
                                                                        ?>
                                                                    </td>
                                                                    <td class="has-html text-center">
                                                                        <?php
                                                                            if ($user_doc_status == "rejected") {
                                                                                ?>
                                                                                    <span class="text-uppercase badge bg-<?php echo $class_doc_status; ?> show-pointer" style="white-space: normal;" data-bs-toggle="modal" data-bs-target="#ModalShowMessage" data-message_rejection="<?php echo $comment; ?>">
                                                                                        <?php echo $user_doc_status; ?>
                                                                                    </span>
                                                                                <?php
                                                                            } else {
                                                                                ?>
                                                                                    <span class="text-uppercase badge bg-<?php echo $class_doc_status; ?>" style="white-space: normal;">
                                                                                        <?php echo $user_doc_status; ?>
                                                                                    </span>
                                                                                <?php
                                                                            }
                                                                        ?>
                                                                        
                                                                    </td>
                                                                    <td class="text-center"><?php echo $create_date; ?></td>
                                                                </tr>

                                                            <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                    <tfoot class="no_print">
                                                        <tr>
                                                            <th>Sr</th>
                                                            <th class="no_print">Action</th>
                                                            <th style="white-space: nowrap;">User ID</th>
                                                            <th>Doc Name</th>
                                                            <th>Doc Number</th>
                                                            <th>File</th>
                                                            <th>Status</th>
                                                            <th>Uploaded On</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form action="" method="post">
                                <fieldset>
                                    <div class="card text-center">
                                        <div class="card-header">
                                            <input type="hidden" name="user_id_member" value="<?php echo $user_id_member; ?>">
                                            <input type="hidden" name="doc_id" value="<?php echo $doc_id; ?>">

                                            <?php
                                                if ($status == "active" || $status == "inactive") {
                                                    ?>
                                                        <span class="btn btn-md <?php echo $classStatus; ?>" style="white-space: normal;">
                                                            <input type="hidden" name="id" value="<?php echo $user_id_member; ?>">
                                                            <input type="hidden" name="is_active" value="<?php echo $is_active; ?>">
                                                            <input type="submit" name="status" value="<?php echo ($is_active) ? "ACTIVATED" : "DEACTIVATED"; ?>" onclick="return confirm('Are you sure to proceed with <?php echo ($is_active) ? 'Deactivation' : 'Activation'; ?>');" style="background: transparent; border-style:hidden; color:white;">
                                                        </span>

                                                        <!-- <span class="btn btn-md bg-success text-white" style="white-space: normal;">
                                                            KYC APPROVED
                                                        </span> -->
                                                    <?php
                                                }
                                                /*
                                                    if ($status == "pending") {
                                                        ?>
                                                            <button type="submit" name="submit_approve" class="btn btn-success" onclick="return confirm('Are you sure to proceed with Approval');">Approve</button>
                                                            <button type="submit" name="submit_reject" class="btn btn-danger" onclick="return confirm('Are you sure to proceed with Rejection');">Reject</button>
                                                        <?php
                                                    }
                                                */
                                            ?>                                            

                                            <!-- <button type="submit" name="submit" class="btn btn-primary">Save changes</button> -->
                                            
                                        </div>
                                    </div>
                                </fieldset>
                            </form>

                        </div>
                    </div>

                </div>
            </main>

            <?php include_once('footer.php'); ?>
        </div>
    </div>

    <!-- BEGIN ModalAction -->
		<div class="modal fade" id="ModalAction" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">Approve/Reject Document</h3>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p class="mb-0">
							<div class="row align-items-center">
								<input type="hidden" id="id_doc">
								<input type="hidden" id="id_user">
								<input type="hidden" id="force_reject">
								<input type="hidden" id="force_approve">
								<div class="mb-3 col-md-12">
									<label for="select_action" class="text-bold">Select Action To Perform</label>
									<select name="" id="select_action" class="form-control" onchange="toggle_comment();">
										<option value="">Choose Action</option>
										<option value="approved" id="select_approve">Approve</option>
										<option value="rejected" id="select_reject">Reject</option>
									</select>
								</div>
								<div class="mb-3 col-md-12 d-none" id="reject_comment_container">
									<label for="reject_comment" class="text-bold">Reason/Comment For Rejection</label>
									<input type="text" name="reject_comment" id="reject_comment" class="form-control" placeholder="Enter comment">
								</div>
							</div>
							<div style="float:right;">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success" id="btn_submit" onclick="process_kyc();">Submit</button>
							</div>
						</p>
					</div>
				</div>
			</div>
		</div>
	<!-- END ModalAction -->

    <!-- BEGIN ModalFinance -->
		<div class="modal fade" id="ModalFinance" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">Invest/Deduct Funds</h3>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
                        <form action="" method="post">
                            <p class="mb-0">
                                <div class="row align-items-center">
                                    <input type="hidden" name="id_user" value="<?php echo $user_id_member; ?>">
                                    <input type="hidden" name="max_wallet_roi" value="<?php echo $wallet_roi; ?>">
                                    <input type="hidden" name="max_wallet_commission" value="<?php echo $wallet_commission; ?>">
                                    <input type="hidden" name="max_wallet_investment" value="<?php echo $wallet_investment; ?>">
                                    <div class="mb-3 col-md-12">
                                        <label for="choose_action" class="text-bold">Select Action To Perform</label>
                                        <select name="choose_action" id="choose_action" class="form-control select2" data-placeholder="Select Action" required>
                                            <option value="">Choose Action</option>
                                            <option value="invest" id="select_invest">Add</option>
                                            <option value="deduct" id="select_deduct">Deduct</option>
                                            <!-- <option value="transfer" id="select_transfer">Transfer</option> -->
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="select_wallet" class="text-bold">Select Wallet</label>
                                        <select name="select_wallet" id="select_wallet" class="form-control select2" data-placeholder="Select Wallet" required>
                                            <option value="">Choose Wallet</option>
                                            <option value="select_wallet_roi" id="select_wallet_roi">ROI Wallet [<?php echo "Rs. $wallet_roi"; ?>]</option>
                                            <option value="select_wallet_commission" id="select_wallet_commission">Commission Wallet [<?php echo "Rs. $wallet_commission"; ?>]</option>
                                            <option value="select_wallet_investment" id="select_wallet_investment">Investment Wallet [<?php echo "Rs. $wallet_investment"; ?>]</option>
                                            <!-- <option value="transfer" id="select_transfer">Transfer</option> -->
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-6" id="invest_container">
                                        <label for="invest_fund" class="text-bold">Amount to Invest</label>
                                        <input type="number" name="invest_fund" id="invest_fund" class="form-control" placeholder="Funds to Invest" required>
                                    </div>
                                    <!-- <div class="mb-3 col-md-12 d-none" id="deduct_container">
                                        <label for="invest_fund" class="text-bold">Amount to Invest</label>
                                        <input type="number" name="invest_fund" id="invest_fund" class="form-control" placeholder="Funds to Invest">
                                    </div>
                                    <div class="mb-3 col-md-12 d-none" id="transfer_container">
                                        <label for="invest_fund" class="text-bold">Amount to Invest</label>
                                        <input type="number" name="invest_fund" id="invest_fund" class="form-control" placeholder="Funds to Invest">
                                    </div> -->
                                </div>
                                <div style="float:right;">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success" id="btn_submit_fund" name="btn_submit_fund">Submit</button>
                                </div>
                            </p>
                        </form>
					</div>
				</div>
			</div>
		</div>
	<!-- END ModalFinance -->

    <!-- BEGIN ModalUpdate -->
		<div class="modal fade" id="ModalUpdate" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">Update Personal Details</h3>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
                        <form action="" method="post">
                            <p class="mb-0">
                                <div class="row align-items-center">
                                    <input type="hidden" id="u_id_user" name="u_id_user">
                                    <div class="mb-3 col-md-6">
                                        <label for="u_name" class="text-bold">Full Name</label>
                                        <input type="text" name="u_name" id="u_name" class="form-control">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="u_fhname" class="text-bold">Father/Husband Name</label>
                                        <input type="text" name="u_fhname" id="u_fhname" class="form-control">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="u_mobile" class="text-bold">Mobile No.</label>
                                        <input type="number" name="u_mobile" id="u_mobile" class="form-control">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="u_whatsapp" class="text-bold">Whatsapp No.</label>
                                        <input type="number" name="u_whatsapp" id="u_whatsapp" class="form-control">
                                    </div>
                                    <div class="mb-3 col-md-12">
                                        <label for="u_email" class="text-bold">Email ID</label>
                                        <input type="email" name="u_email" id="u_email" class="form-control">
                                    </div>
                                </div>
                                <div style="float:right;">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success" id="btn_update" name="btn_update" onclick="process_updation();">Save Changes</button>
                                </div>
                            </p>
                        </form>
					</div>
				</div>
			</div>
		</div>
	<!-- END ModalUpdate -->

    <!-- BEGIN ModalUploadDocument -->
        <div class="modal fade" id="ModalUploadDocument" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="modal_title">User KYC Form</h3>
                        <?php
                            if ($pan_update == 1 && $aadhaar_update == 1) {
                                ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <?php
                            }
                        ?>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">
                        <form method="POST" action="" id="form_upload_record" enctype="multipart/form-data">
                            <div class="row align-items-center">
                                <div class="mb-3 col-md-6">
                                    <label for="user_id" class="form-label">User ID</label>
                                    <input type="text" class="form-control text-readonly" value="<?php echo "$user_id-$name"; ?>" readonly>
                                    <input type="hidden" value="<?php echo $user_id; ?>" name="user_id" id="user_id">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="sponsor_id_upload" class="form-label">Sponsor</label>
                                    <input type="text" class="form-control text-readonly" value="<?php echo "$sponsor_id-$sponsor_name"; ?>" readonly>
                                    <input type="hidden" value="<?php echo $sponsor_id; ?>" name="sponsor_id_upload">
                                </div>

                                <div class="row g-2">
                                    <label for="pan" class="text-bold mt-1">PAN Details</label>

                                    <div class="mb-3 col-xl-6 col-md-5">
                                        <label for="pan" class="form-label">PAN Number</label>
                                        <input type="text" class="form-control" id="pan" name="pan" placeholder="PAN No." value="<?php echo isset($pan) ? $pan : ''; ?>" min="0" maxlength="10" pattern="[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}" title="Enter valid 10 digit PAN Number" required <?php echo $pan_disabled; ?>>
                                    </div>
                                    
                                    <?php
                                        if ($pan_update == 0) {
                                            ?>
                                                <div class="mb-3 col-xl-6 col-md-7">
                                                    <label for="file_pan" class="form-label">Upload PAN <span class="text-danger">*jpg/jpeg/png format; 500KB max</span></label>
                                                    <input type="file" name="file_pan" id="file_pan" class="form-control" accept="image/*" required onchange="check_size(this.id,500);">
                                                </div>

                                            <?php
                                        } else {
                                            ?>
                                                <div class="mb-3 col-xl-6 col-md-7">
                                                    <label class="form-label">Uploaded PAN</label>
                                                    <input type="text" class="form-control bg-success show-pointer" data-bs-toggle='modal' data-bs-target='#ModalShowPhoto' data-src="<?php echo $pan_doc; ?>" data-from="kyc" value="View Now" disabled>
                                                </div>
                                            <?php
                                        }
                                    ?>   

                                    <label for="aadhaar" class="text-bold mt-1">Aadhaar Details</label>

                                    <div class="mb-3 col-xl-4">
                                        <label for="aadhaar" class="form-label">Aadhaar Number</label>
                                        <input type="text" class="form-control" id="aadhaar" name="aadhaar" placeholder="Aadhaar No." value="<?php echo isset($aadhaar) ? $aadhaar : ''; ?>" min="0" maxlength="12" pattern="[0-9]{12}" title="Enter valid 12 digit Aadhaar Number" required <?php echo $aadhaar_disabled; ?>>
                                    </div>
                                    <?php
                                        if ($aadhaar_update == 0) {
                                            ?>
                                                <div class="mb-3 col-xl-4">
                                                    <label for="file_aadhaar_front" class="form-label">Aadhaar Front<span class="text-danger">*jpg/jpeg/png format; 500KB max</span></label>
                                                    <input type="file" name="file_aadhaar_front" id="file_aadhaar_front" class="form-control" accept="image/*" required onchange="check_size(this.id,500);">
                                                </div>
                                                <div class="mb-3 col-xl-4">
                                                    <label for="file_aadhaar_back" class="form-label">Aadhaar Back<span class="text-danger">*jpg/jpeg/png format; 500KB max</span></label>
                                                    <input type="file" name="file_aadhaar_back" id="file_aadhaar_back" class="form-control" accept="image/*" required onchange="check_size(this.id,500);">
                                                </div>
                                            <?php
                                        } else {
                                            ?>
                                                <div class="mb-3 col-xl-8">
                                                    <label class="form-label">Uploaded Aadhaar</label>
                                                    <input type="text" class="form-control bg-success show-pointer" data-bs-toggle='modal' data-bs-target='#ModalShowPhoto' data-src="<?php echo $aadhaar_doc_front; ?>" data-src1="<?php echo $aadhaar_doc_back; ?>" data-from="kyc" value="View Now" disabled>
                                                </div>
                                            <?php
                                        }
                                    ?>

                                    <div class="mb-3 col-md-12">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea rows="2" class="form-control" id="address" name="address" placeholder="Your complete address" required><?php echo $address;?></textarea>
                                    </div>
                                    
                                    <div class="mb-3 col-md-12">
                                            <label for="address" class="form-label">Lankmark</label>
                                            <input type="text" class="form-control" id="landmark" name="landmark" placeholder="Landmark" required readonly><?php echo $landmark;?></textarea>
                                        </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo $city;?>">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label for="state" class="form-label">State</label>
                                        <select class="form-control select2" id="state_name" name="state_name" required>
                                            <option value="" hidden>Choose State</option>
                                            <?php
                                                $res = mysqli_query($conn, "SELECT `state_name` FROM `indian_states` ORDER BY `state_name` ASC");
                                                $states = mysqli_fetch_all($res, MYSQLI_ASSOC);
                                                foreach ($states as $state) {
                                                    $state_name_fetch = $state['state_name'];
                                                    $selected = "";
                                                    if ($state_name_fetch == $state_name) {
                                                        $selected = "selected='selected'";
                                                    }
                                                    ?>
                                                        <option value="<?php echo $state_name_fetch;?>" <?php echo $selected; ?>><?php echo $state_name_fetch;?></option>
                                                    <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-2">
                                        <label for="pin_code" class="form-label">PIN Code</label>
                                        <input type="text" class="form-control" id="pin_code" name="pin_code" placeholder="PIN Code" value="<?php echo $pin_code;?>" min="0" maxlength="6" pattern="[0-9]{6}" title="Enter valid 6 digit PIN Code" required>
                                    </div>
                                </div>
                            </div>
                            <div style="float:right;">
                                <?php
                                    if ($pan_update == 0 && $aadhaar_update == 0) {
                                        ?>
                                            <a href="logout/" class="btn btn-danger btn-sm"><i class="ti ti-power ti-sm"></i>Logout</a>
                                            <button type="submit" class="btn btn-success" id="btn_submit_document" name="submit_document">Submit</button>
                                        <?php
                                    } else {
                                        ?>
                                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                        <?php
                                    }
                                ?>
                            </div>
                        </form>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <!-- END ModalUploadDocument -->

    <!-- BEGIN ModalUploadBank -->
        <div class="modal fade" id="ModalUploadBank" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="modal_title">Bank Details</h3>
                        <?php
                            // if ($bank_update == 1) {
                                ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <?php
                            // }
                        ?>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">
                        <form method="POST" action="" id="form_upload_record" enctype="multipart/form-data">
                            <div class="row align-items-center">

                                <div class="mb-3 col-md-6">
                                    <label for="user_id" class="form-label">User ID</label>
                                    <input type="text" class="form-control text-readonly" value="<?php echo "$user_id-$name"; ?>" readonly>
                                    <input type="hidden" value="<?php echo $user_id; ?>" name="user_id" id="user_id">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="sponsor_id_upload" class="form-label">Sponsor</label>
                                    <input type="text" class="form-control text-readonly" value="<?php echo "$sponsor_id-$sponsor_name"; ?>" readonly>
                                    <input type="hidden" value="<?php echo $sponsor_id; ?>" name="sponsor_id_upload">
                                </div>

                                <div class="row g-2">
                                    <label for="bank_name" class="text-bold mt-1">Banking info</label>
                                    
                                    <div class="mb-3 col-xl-7">
                                        <label for="bank_name" class="form-label">Bank Name <span class="text-danger">*</span></label>
                                        <select class="select2 form-select" name="bank_name" id="bank_name" required onchange="set_bank_field();" <?php echo $bank_disabled; ?>>
                                            <option value="" hidden>Choose Bank</option>
                                            <?php
                                                $bank_selected = 0;
                                                $res = mysqli_query($conn, "SELECT `id` AS 'bank_name_id', `name` AS 'bank_name' FROM `bank_names` ORDER BY `name` ASC");
                                                $banks = mysqli_fetch_all($res, MYSQLI_ASSOC);
                                                foreach ($banks as $bank) {
                                                    $bank_name_fetch = $bank['bank_name'];
                                                    $selected = "";
                                                    if ($bank_name_fetch == $bank_name) {
                                                        $selected = "selected='selected'";
                                                        $bank_selected = 1;
                                                    }
                                                    ?>
                                                        <option value="<?php echo $bank_name_fetch;?>" <?php echo $selected; ?>><?php echo $bank_name_fetch;?></option>
                                                    <?php
                                                }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="mb-3 col-xl-5">
                                        <label for="bank_name_other" class="form-label">Other Bank (Specify Here)</label>
                                        <input type="text" class="form-control" name="bank_name_other" id="bank_name_other" placeholder="In case of other Bank, specify here" value="<?php echo ($bank_selected == 0)?$bank_name:'';?>" onblur="set_bank_attr();" <?php echo $bank_disabled; ?>>
                                    </div>
                                    
                                    <div class="mb-3 col-md-6">
                                        <label for="branch_name" class="form-label">Branch Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="branch_name" id="branch_name" placeholder="Branch" value="<?php echo $branch_name;?>" required <?php echo $bank_disabled; ?>>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="account_no" class="form-label">Account Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="account_no" id="account_no" placeholder="Account Number" value="<?php echo $account_no;?>" required <?php echo $bank_disabled; ?>>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="ifs_code" class="form-label">IFSC Code <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="ifs_code" id="ifs_code" placeholder="IFSC Code" value="<?php echo $ifs_code;?>" required <?php echo $bank_disabled; ?>>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="upi_handle" class="form-label">UPI Handle (@ybl, @paytm etc...)</label>
                                        <input type="text" class="form-control" name="upi_handle" id="upi_handle" placeholder="UPI" value="<?php echo $upi_handle;?>" <?php echo $bank_disabled; ?>>
                                    </div>
                                        
                                    <div class="mb-3 col-xl-6 col-md-4">
                                        <label for="doc_type" class="form-label">Bank Proof <span class="text-danger">*</span></label>
                                        <select class="select2 form-select" name="doc_type" id="doc_type" required <?php echo $bank_disabled; ?>>
                                            <option value="" hidden>Choose Document Type</option>
                                            <option value="cheque" <?php echo $bank_cheque_sel; ?>>Cancelled Cheque</option>
                                            <option value="passbook" <?php echo $bank_passbook_sel; ?>>Passbook</option>
                                        </select>
                                    </div>
                                    <?php
                                        if ($bank_update == 2) {
                                            ?>
                                                <div class="mb-3 col-xl-6 col-md-8">
                                                    <label for="file_bank_proof" class="form-label">Proof Photo <span class="text-danger">*jpg/jpeg/png format; 500KB max</span></label>
                                                    <input type="file" name="file" id="file_bank_proof" class="form-control" accept="image/*" required onchange="check_size(this.id,500);">
                                                </div>
                                            <?php
                                        } else {
                                            ?>
                                                <div class="mb-3 col-xl-6 col-md-8">
                                                    <label for="file_bank_proof" class="form-label">Uploaded Photo</label>
                                                    <!-- <span class='mt-2 badge bg-info show-pointer' > -->

                                                    <input type="text" class="form-control bg-success show-pointer" data-bs-toggle='modal' data-bs-target='#ModalShowPhoto' data-src="<?php echo $bank_doc; ?>" data-from="bank" value="View Now" disabled>
                                                </div>
                                            <?php
                                        }
                                    ?>
                                </div>
                            </div>
                            <div style="float:right;">
                                <?php
                                    if ($bank_update == 2) {
                                        ?>
                                            <a href="logout/" class="btn btn-danger btn-sm"><i class="ti ti-power ti-sm"></i>Logout</a>
                                            <button type="submit" class="btn btn-success" id="btn_submit_bank" name="submit_bank">Submit</button>
                                        <?php
                                    } else {
                                        ?>
                                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                        <?php
                                    }
                                ?>
                            </div>
                        </form>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <!-- END ModalUploadBank -->

    <!-- BEGIN ModalUploadPhoto -->
        <div class="modal fade" id="ModalUploadPhoto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="modal_title">User Photo</h3>
                        <?php
                            // if ($photo_update == 1) {
                                ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <?php
                            // }
                        ?>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">
                        <form method="POST" action="" id="form_upload_record" enctype="multipart/form-data">
                            <div class="row align-items-center">
                                <div class="mb-3 col-md-6">
                                    <label for="user_id" class="form-label">User ID</label>
                                    <input type="text" class="form-control text-readonly" value="<?php echo "$member_id-$member_name"; ?>" readonly>
                                    <input type="hidden" value="<?php echo $member_id; ?>" name="user_id" id="user_id">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="sponsor_id_upload" class="form-label">Sponsor</label>
                                    <input type="text" class="form-control text-readonly" value="<?php echo "$sponsor_id-$sponsor_name"; ?>" readonly>
                                    <input type="hidden" value="<?php echo $sponsor_id; ?>" name="sponsor_id_upload">
                                </div>

                                <div class="row g-2">

                                    <?php
                                        if ($photo_update == 2) {
                                            ?>
                                                <div class="mb-3 col-md-12 text-center">
                                                    <label for="file" class="form-label">Upload Photo <span class="text-danger">* (Realtime Photo if you're on Phone)</span></label>
                                                    <input type="file" name="file" id="file_photo" class="form-control" accept="image/*" capture required onchange="loadImageFile(this.id)">
                                                    <!-- <input type="file" name="file" id="file_photo" class="form-control" accept="image/*;capture=camera" required onchange="loadImageFile(this.id)"> -->

                                                    <input type="hidden" name="file" id="upload_compress" type="file" value="" />
                                                    <img src="#" id="upload_preview">
                                                </div>                                                
                                            <?php
                                        } else {
                                            ?>
                                                <div class="mb-3 col-md-12 text-center">
                                                    <label class="form-label">Uploaded Photo</label>
                                                    <br>
                                                    <img src="<?php echo $photo; ?>" style="width:200px;">
                                                    <!-- <input type="text" class="form-control bg-success show-pointer" data-bs-toggle='modal' data-bs-target='#ModalShowPhoto' data-src="<?php echo $pan_doc; ?>" data-from="kyc" value="View Now" disabled> -->
                                                </div>
                                            <?php
                                            
                                        }
                                    ?>
                                    
                                </div>

                                
                            </div>
                            <div style="float:right;">
                                <?php
                                    if ($photo_update == 2) {
                                        ?>
                                            <a href="logout/" class="btn btn-danger btn-sm"><i class="ti ti-power ti-sm"></i>Logout</a>
                                            <button type="submit" class="btn btn-success" id="btn_submit_photo" name="submit_photo">Submit</button>
                                        <?php
                                    } else {
                                        ?>
                                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                        <?php
                                    }
                                ?>
                            </div>
                        </form>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <!-- END ModalUploadPhoto -->

    <!-- BEGIN ModalShowPhoto -->
        <div class="modal fade" id="ModalShowPhoto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog- scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="modal_title_photo">Photo Uploaded</h3>
                        <?php
                            // if ($pan_update == 1 && $aadhaar_update == 1 && $bank_update == 1) {
                                ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <?php
                            // }
                        ?>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">
                            <div class="row align-items-center">
                                <div class="col-md-6 text-center" id="photo1_container">
                                    <img id="photo1" style="width:100%;">
                                </div>
                                <div class="col-md-6 text-center" id="photo2_container" style="display:none;">
                                    <img id="photo2" style="width:100%;">
                                </div>
                            </div>
                        </p>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <?php
                            // if ($pan_update == 1 && $aadhaar_update == 1 && $bank_update == 1) {
                                ?>
                                    <button class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                <?php
                            // }
                            
                            if ($bank_update == 1) {
                                ?>
                                    <button class="d-none btn btn-primary waves-effect waves-light" id="buttonModalUploadBank" data-bs-target="#ModalUploadBank" data-bs-toggle="modal" data-bs-dismiss="modal">View Bank Details</button>
                                <?php
                            }
                            if ($pan_update == 1) {
                                ?>
                                    <button class="d-none btn btn-danger waves-effect waves-light" id="buttonModalUploadDocument" data-bs-target="#ModalUploadDocument" data-bs-toggle="modal" data-bs-dismiss="modal">View KYC Details</button>
                                <?php
                            }
                        ?>
                        
                    </div>
                </div>
            </div>
        </div>
    <!-- END ModalShowPhoto -->

    <!-- BEGIN ModalShowDoc -->
		<div class="modal fade" id="ModalShowDoc" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-md" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">Document Uploaded</h3>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p class="mb-0">
							<div class="row align-items-center" align="center">
								<div class="mb-3 col-md-12">
									<img src="" alt="" id="image" style="width: 400px;">
								</div>
							</div>
							<div style="float:right;">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<!-- <button type="submit" class="btn btn-success" id="btn_submit" name="submit">Submit</button> -->
							</div>
						</p>
					</div>
				</div>
			</div>
		</div>
	<!-- END ModalShowDoc -->

    <!-- BEGIN ModalShowMessage -->
        <div class="modal fade" id="ModalShowMessage" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog- scrollable" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title" id="modal_title_message">Accept/Reject KYC</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p class="mb-0">
							<div class="row align-items-center">
                                <div class="col-md-12 text-center">
                                    <textarea name="message_rejection" id="message_rejection" cols="10" rows="2" class="form-control" readonly></textarea>
                                </div>
                            </div>
                        </p>
					</div>
					<div class="modal-footer d-flex justify-content-center">
                        <button class="btn btn-dark waves-effect waves-light" data-bs-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	<!-- END ModalShowMessage -->

    <?php include_once('scripts.php'); ?>

    <!-- BEGIN ModalAdd -->
        <div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Add new Record</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="sponsor_id_add">Sponsor ID</label>
                                    <input type="text" name="sponsor_id_add" id="sponsor_id_add" value="<?php echo $user_id;?>" class="form-control text-readonly" readonly>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="sponsor_name">Sponsor Name</label>
                                    <input type="text" name="sponsor_name" id="sponsor_name" value="<?php echo $sponsor_name;?>" class="form-control text-readonly" readonly>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="package_amount">Package Selected</label>
                                    <input type="text" name="package_amount" id="package_amount" value="<?php echo $package_amount;?>" class="form-control text-readonly" readonly>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="form_no">Form No.</label>
                                    <select name="form_no" id="form_no" class="form-control" required>
                                        <option value="" hidden>Select Form No.</option>
                                        <option value="1" <?php echo $disabled_option1;?> >Form-1</option>
                                        <option value="2" <?php echo $disabled_option2;?> >Form-2</option>
                                        <option value="3" <?php echo $disabled_option3;?> >Form-3</option>
                                        <option value="4" <?php echo $disabled_option4;?> >Form-4</option>
                                        <option value="5" <?php echo $disabled_option5;?> >Additional Member</option>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="name_add">Member Name</label>
                                    <input type="text" name="name_add" id="name_add" class="form-control">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="mobile">Member Mobile</label>
                                    <input type="number" name="mobile" id="mobile" class="form-control">
                                </div>
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="email">Member Email</label>
                                    <input type="text" name="email" id="email" class="form-control">
                                </div>
                                <div class="mb-3 col-md-12">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea rows="1" class="form-control" id="address" name="address" placeholder="Member Complete Address" required></textarea>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city" placeholder="City" required>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="state" class="form-label">State</label>
                                    <select class="form-control " id="state" name="state" required>
                                        <option value="" hidden>Choose State</option>
                                        <?php
                                            $res = mysqli_query($conn, "SELECT `state_name` FROM `indian_states` ORDER BY `state_name` ASC");
                                            $states = mysqli_fetch_all($res, MYSQLI_ASSOC);
                                            foreach ($states as $state) {
                                                $state_name = $state['state_name'];
                                                ?>
                                                    <option value="<?php echo $state_name;?>"><?php echo $state_name;?></option>
                                                <?php
                                            }
                                        ?>															
                                    </select>
                                </div>
                                <div class="mb-3 col-md-2">
                                    <label for="pin_code" class="form-label">PIN Code</label>
                                    <input type="text" class="form-control" id="pin_code" name="pin_code" placeholder="PIN Code" required>
                                </div>
                            </div>
                            <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                            <div style="float:right">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="submit" class="btn btn-success" id="btn_save">Save changes</button>
                            </div>
                        </form>
                        </p>
                    </div>
                    <!-- <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div> -->
                </div>
            </div>
        </div>
    <!-- END ModalAdd -->

    <script>
		function toggle_funds() {
			var choose_action = document.getElementById("choose_action");
			var invest_container = document.getElementById("invest_container");
			var deduct_container = document.getElementById("deduct_container");
			var transfer_container = document.getElementById("transfer_container");
			var invest_fund = document.getElementById("invest_fund");

			if (choose_action.value == "invest") {
				invest_container.classList.remove('d-none');
				deduct_container.classList.add('d-none');
				transfer_container.classList.add('d-none');

				invest_fund.setAttribute('required','true');
			} else if (choose_action.value == "deduct") {
				deduct_container.classList.remove('d-none');
				invest_container.classList.add('d-none');
				transfer_container.classList.add('d-none');
                
				invest_fund.setAttribute('required','false');
			} else if (choose_action.value == "transfer") {
				transfer_container.classList.remove('d-none');
                invest_container.classList.add('d-none');
				deduct_container.classList.add('d-none');
                
				invest_fund.setAttribute('required','false');
            }
		}

		function toggle_comment() {
			var select_action = document.getElementById("select_action");
			var reject_comment_container = document.getElementById("reject_comment_container");
			var reject_comment = document.getElementById("reject_comment");
			var id_doc = document.getElementById("id_doc");

			if (select_action.value == "rejected") {
				reject_comment_container.classList.remove('d-none');
				reject_comment.setAttribute('required','true');
			} else {
				reject_comment_container.classList.add('d-none');
				reject_comment.setAttribute('required','false');
			}
		}

		function process_kyc() {
			var action = "process_kyc";
			var select_action = document.getElementById("select_action");
			var reject_comment = document.getElementById("reject_comment");
			var id_doc = document.getElementById("id_doc");
			var id_user = document.getElementById("id_user");
			var force_reject = document.getElementById("force_reject");
			var force_approve = document.getElementById("force_approve");

			if (select_action.value == "") {
				showNotif("Please select an action to perform!","danger");
				return false;
			}

			if (select_action.value == "rejected") {
				if (reject_comment.value.trim() == "") {
					reject_comment.focus();
					showNotif("Comment is required for Rejection!","danger");
					return false;
				}
			}

			$.ajax({
				type: "POST",
				url: "ajax.php",
				data: {
					action: action,
					id_user: id_user.value,
					id_doc: id_doc.value,
					action_to_perform: select_action.value,
					reject_comment: reject_comment.value,
                    force_reject: force_reject.value
				},
				
				// data: $("#form_register").serialize() + "&action=" +action , // getting filed value in serialize form And Passing additional Value of 'action' To perform Task Accordingly
				// dataType: 'JSON',
				cache: false,

				// xhr: function () {
					//     var xhr = $.ajaxSettings.xhr();
					//     xhr.onprogress = function (e) 
					//     {
					//         // For downloads
					//         if (e.lengthComputable)                             
					//         {
					//             console.log(e.loaded / e.total);
					//         }
					//     };
					//     xhr.upload.onprogress = function (e) 
					//     {
					//         // For uploads
					//         if (e.lengthComputable) 
					//         {
					//             document.getElementById("overlay").style.display = "block";
					//             console.log(e.loaded / e.total);
					//         }
					//     };
					//     return xhr;
				// },

				success: function(response) {
					console.log(response);
					if (response == "approved") {
						showNotif("Document Approved Successfully","success");
					} else if (response === "approved_already") {
						showNotif("Document Approved Already","warning");
                    } else if (response == "rejected") {
						showNotif("Document Rejected Successfully","danger");
					} else if (response === "rejected_already") {
						showNotif("Document Rejected Already","warning");
                    } else if (response == "kyc_rejected") {
						showNotif("Member KYC Rejected Successfully","danger");
                    } else if (response == "kyc_approved") {
						showNotif("Member KYC Done Successfully","success");
					} else if (response === "kyc_approved_already") {
						showNotif("Member KYC Done Already","warning");
                    } 

					setTimeout(function(){
						location.reload();
					},1500);
					// document.getElementById("overlay").style.display = "none";

					// var response_obj = $.parseJSON(response); // create an object with the key of the array
					// PARSE/DECODE THE JSON OBJECT
					// var response_obj = JSON.parse(response);
					// alert(response_obj.html_data); // where html is the key of array that you want, $response['html'] = "<a>something..</a>";

				}
			});
		}

        // PASS DATA TO MODAL POPUP
            $(function () {
                $('#ModalUpdate').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var id = button.data('id'); // Extract info from data-* attributes
                    var id_user = button.data('user_id'); // Extract info from data-* attributes
                    var mobile = button.data('mobile'); // Extract info from data-* attributes
                    var email = button.data('email'); // Extract info from data-* attributes
                    var name = button.data('name'); // Extract info from data-* attributes
                    var father = button.data('father'); // Extract info from data-* attributes
                    var whatsapp = button.data('whatsapp'); // Extract info from data-* attributes
                    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
					// package = "Package "+package;
					// payment = "20% Amount of "+package+"= Rs."+payment;
                    var modal = $(this);
                    // modal.find(id).val(src);
                    // modal.find('#image').src=src;
					document.getElementById("u_id_user").value = id_user;
					document.getElementById("u_name").value = name;
					document.getElementById("u_fhname").value = father;
					document.getElementById("u_whatsapp").value = whatsapp;
					document.getElementById("u_mobile").value = mobile;
					document.getElementById("u_email").value = email;

                });
            });
        // PASS DATA TO MODAL POPUP
        
		// PASS DATA TO MODAL POPUP
            $(function () {
                $('#ModalAction').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var id = button.data('id'); // Extract info from data-* attributes
                    var id_user = button.data('user_id'); // Extract info from data-* attributes
                    var force_reject = button.data('force_reject'); // Extract info from data-* attributes
                    var force_approve = button.data('force_approve'); // Extract info from data-* attributes
                    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
					// package = "Package "+package;
					// payment = "20% Amount of "+package+"= Rs."+payment;
                    var modal = $(this);
                    // modal.find(id).val(src);
                    // modal.find('#image').src=src;
					document.getElementById("id_doc").value = id;
					document.getElementById("id_user").value = id_user;
					document.getElementById("force_reject").value = force_reject;
					document.getElementById("force_approve").value = force_approve;
                    if (force_reject == 1) {
                        document.getElementById("select_approve").disabled = true;
                        document.getElementById("select_approve").classList.add("bg-danger");
                        document.getElementById("select_approve").classList.add("text-white");
                        document.getElementById("select_reject").selected = true;
                        toggle_comment();
                    } else {
                        document.getElementById("select_approve").disabled = false;
                        document.getElementById("select_approve").classList.remove("bg-danger");
                        document.getElementById("select_approve").classList.remove("text-white");
                        document.getElementById("select_reject").selected = false;
                        toggle_comment();
                    }
                    // else if (force_approve == 1) {
                    //     document.getElementById("select_approve").selected = true;
                    //     document.getElementById("select_reject").classList.add("bg-danger");
                    //     document.getElementById("select_reject").classList.add("text-white");
                    //     document.getElementById("select_reject").disabled = true;
                    //     toggle_comment();
                    // }
                });
            });
        // PASS DATA TO MODAL POPUP

        // PASS DATA TO MODAL POPUP
            $(function () {
                $('#ModalShowDoc').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var src = button.data('src'); // Extract info from data-* attributes
                    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
					// package = "Package "+package;
					// payment = "20% Amount of "+package+"= Rs."+payment;
                    var modal = $(this);
					
                    // modal.find(id).val(src);
                    // modal.find('#image').src=src;
					$("#image").attr("src", src);
                });
            });
        // PASS DATA TO MODAL POPUP
	</script>
    
    <script>
        // DataTables with Column Search by Text Inputs
            document.addEventListener("DOMContentLoaded", function () {
                // DataTables
                var $filename = document.title;
                var default_Order = '<?php echo $default_Order;?>';
                var length_menu = '<?php echo isset($length_menu)?$length_menu:"";?>';
                var table = $('#datatables').DataTable({
                    dom: /*'lBfrtip'*/ 'lfrtip',
                    // Default Sort
                    "order": [[default_Order, "asc"]],
                    // "scrollX": true,
                    "lengthMenu": (length_menu == "all")?[[-1],["All"]]:[[25, 50, 100, -1],[25, 50, 100, "All"]],
                    buttons: [{
                        extend: 'excel',
                        text: 'Export In Excel',
                        //className: 'btn-success', TO CHANGE THE BUTTON STYLE, ADD A CLASS THEN MODIFY IT IN CSS
                        filename: $filename,
                        title: $filename,
                        titleAttr: "Export In Excel",
                        //orientation : 'landscape',
                        //pageSize:'LEGAL',    //fullpage
                        "oSekectorOpts":{filter:'applied', order:'current'},
                        exportOptions: //EXPORTING PARTICULAR COLUMN STARTS FROM 0, 1, 2 ETC ETC
                        {
                            //**IMPORTANT**//columns: ':visible',  //exporting only visible columns
                            // columns: [1, 2, 3, 4, 5, 6], //-----------HIDING ACTION COLUMN IN PDF EXPORT----------//
                            columns: ':visible:not(.no_print)', //HIDE ACTION COLUMN
                            // rows: ':visible'
                        },
                        key: { // press E for export EXCEL
                            key: 'e',
                            altKey: false
                        },
                    },
                    {
                        text: 'Download PDF',
                        extend: 'pdfHtml5',
                        className: 'btn-warning',
                        // TO CHANGE THE BUTTON STYLE, ADD A CLASS THEN MODIFY IT IN CSS
                        filename: $filename,
                        orientation: 'landscape',
                        pageSize: 'A4', //A3 , A5 , A6 , legal , letter, TABLOID
                        //pageSize:'LEGAL',    //fullpage
                        // pageMargins: [0, 0, 0, 0], // try #1 setting margins
                        // margin: [0, 0, 0, 0], // try #2 setting margins
                        exportOptions: //EXPORTING PARTICULAR COLUMN STARTS FROM 0, 1, 2 ETC ETC
                        {
                            // columns: [ 0, 1, 2, 3, 4, 5, 6, 7],
                            // EXPORTING WITH DEFAULT INITIAL ORDER
                            columns: ':visible:not(.no_print)', //HIDE ACTION COLUMN
                            // rows: ':visible',

                            modifier: {
                                order: 'index',

                                // pageMargins: [0, 0, 0, 0], // try #3 setting margins
                                // margin: [0, 0, 0, 0], // try #4 setting margins
                                // alignment: 'center'
                            },
                            body: {
                                // margin: [0, 0, 0, 0],
                                // pageMargins: [0, 0, 0, 0]
                            } // try #5 setting margins         
                            ,
                            // columns: [0, 1], //column id visible in PDF    
                            // columnGap: 1 // optional space between columns
                        },

                        key: { // press D for export PDF
                            key: 'd',
                            altKey: false
                        },
                        // content: [{
                        //     style: 'fullWidth'
                        // }],
                        // styles: { // style for printing PDF body
                        //     fullWidth: {
                        //         fontSize: 18,
                        //         bold: true,
                        //         alignment: 'right',
                        //         margin: [0, 0, 0, 0]
                        //     }
                        // },
                        // download: 'download',
                        customize: function (doc) {
                            // Splice the image in after the header, but before the table
                            // doc.content.splice(1, 0, {
                            //     margin: [0, 0, 0, 12],
                            //     alignment: 'center',
                            //     image: 'data:image/png;base64,'
                            // });

                            var filteredRows = getNumFilteredRows('#datatables');

                            //Remove the title created by datatTables
                            doc.content.splice(0, 1);
                            //Create a date string that we use in the footer. Format is dd-mm-yyyy
                            var now = new Date();
                            var jsDate = now.getDate() + '-' + (now.getMonth() + 1) + '-' + now.getFullYear();
                            // Logo converted to base64
                            // var logo = getBase64FromImageUrl('https://datatables.net/media/images/logo.png');
                            // The above call should work, but not when called from codepen.io
                            // So we use a online converter and paste the string in.
                            // Done on http://codebeautify.org/image-to-base64-converter
                            // It's a LONG string scroll down to see the rest of the code !!!

                            // 		var logo = database image path

                            // A documentation reference can be found at
                            // https://github.com/bpampuch/pdfmake#getting-started
                            // Set page margins [left,top,right,bottom] or [horizontal,vertical]
                            // or one number for equal spread
                            // It's important to create enough space at the top for a header !!!
                            // 		doc.pageMargins = [20,60,20,30];
                            doc.pageMargins = [20, 60, 20, 80];

                            // Set the font size fot the entire document

                            // 		doc.defaultStyle.fontSize = 7;
                            // doc.defaultStyle.alignment = 'center';

                            // UNIFORM COLUMN WIDTH (100% WIDTH)
                            // doc.content[0].table.widths = Array(doc.content[0].table.body[0].length + 1).join('*').split('');

                            // VARIABLE COLUMN WIDTH
                            // doc.content[0].table.widths = [90,60,90,60,90,90,60,90,60,90,60];

                            // Set the fontsize for the table header
                            doc.styles.tableHeader.fontSize = 12;
                            doc.styles.tableHeader.alignment = 'center';

                            // doc.styles.tableHeader.fillColor = "green";

                            // var countTotal = <?php /* echo $total_trainee; */ ?>;
                            // TOTAL ROWS WITH HEADER FOOTER >> ID OF TABLE
                            // var countRows = $('#datatables-column-search-text-inputs tr').length;
                            // TOTAL ROWS WITHOUT HEADER FOOTER >> ID OF TABLE-BODY
                            var countRows = document.getElementById("tableBody").rows.length;
                            // ABOVE METHOD ONLY COUNTS THE DISPLAYED ROWS
                            
                            // var countRows = document.getElementById("total_count").value;
                            // ABOVE METHOD SETS THE ROW COUNT TO TOTAL RECORDS
                            
                            for (var i = 1; i <= countRows; i++) {
                                doc.content[0].table.body[i][0].alignment = 'center';
                                doc.content[0].table.body[i][0].bold = 'true';
                                // doc.content[0].table.find(".letter_download").html().bold = 'true';
                                // doc.content[0].table.body[0].getElementById("letter_download").style.bold = 'true';
                            }

                            // var myTab = document.getElementById('list');
                            // var obj = myTab.rows[2].cells.namedItem("letter_download");
                            // obj.style.fontWeight = "bold";
                            // obj.innerHTML = '<b>' + obj.innerHTML + '</b>';
                            // myTab.rows[1].cells.namedItem("letter_download").innerHTML;

                            // LOOP THROUGH EACH ROW OF THE TABLE AFTER HEADER.
                            // for (i = 1; i < myTab.rows.length; i++) {
                            //     // GET THE CELLS COLLECTION OF THE CURRENT ROW.
                            //     var objCells = myTab.rows.item(i).cells;

                            //     // LOOP THROUGH EACH CELL OF THE CURENT ROW TO READ CELL VALUES.
                            //     for (var j = 0; j < objCells.length; j++) {
                            //         // alert(objCells.item(j).innerHTML)
                            //     }
                            // }


                            // for (var i = 0; i < 40; i++) {
                            //     // COLUMN COLOR
                            //     // doc.content[0].table.body[i+1][0].fillColor = 'blue';

                            //     // ROW COLOR
                            //     for (var j = 0; j < 5; j++) {
                            //         doc.content[0].table.body[1][j].fillColor = 'lime';
                            //     }
                            // }

                            // doc.content[1].margin = [ 100, 0, 100, 0 ]; //left, top, right, bottom

                            // doc.styles.tableBodyOdd.noWrap = true;
                            // doc.styles.tableBodyEven.noWrap = true;

                            // AVOID BREAKING OF ROWS
                            doc.content[0].table.dontBreakRows = true;

                            var pageTitle = document.title;
                            // Create a header object with 3 columns
                            // Left side: Logo
                            // Middle: brandname
                            // Right side: A document title
                            doc['header'] = (function () {
                                return {
                                    columns: [
                                        {
                                            alignment: 'left',
                                            italics: true,
                                            bold: true,
                                            text: ['', { text: pageTitle.toString() }],

                                            fontSize: 12,
                                            color: "#B4161B",
                                            margin: [5, 0]
                                        },
                                        // {
                                        //     alignment: 'right',
                                        //     text: 'School : ',
                                        //     fontSize: 12,
                                        //     color: "#B4161B",
                                        //     margin: [10, 0],
                                        //     bold: true
                                        // },
                                        // 	{
                                        // 		alignment: 'right',
                                        // 		fontSize: 14,
                                        // 		text: 'Total Trainees: <?php /* echo " $total_count, Present: $present_count";*/ ?>',
                                        // 		margin: [10,0]
                                        // 	}
                                    ],
                                    margin: 20
                                }
                            });
                            // Create a footer object with 2 columns
                            // Left side: report creation date
                            // Right side: current page and total pages
                            doc['footer'] = (function (page, pages) {
                                return {
                                    columns: [{
                                        alignment: 'left',
                                        text: '<?php echo "Print Date: $print_date";?>',
                                        color: "#120E43",
                                        margin: [5, 0]
                                    },
                                    {
                                        text: ['Total Records Exported: ', { text: filteredRows.toString() }],
                                        margin: [5, 0]
                                    },
                                    // {
                                    //     text: ['Result Date:     25 May 2020'],
                                    //     // 		text: ['Result Declared on: ', { text: jsDate.toString() }],
                                    //     margin: [5, 0]
                                    // },
                                    // {
                                    //     text: '<?php /* echo "Total Trainees: $total_trainee";*/ ?>',
                                    //     margin: [3, 0]
                                    // },
                                    // {
                                    //     text: '<?php /* echo "Allotted Trainees: $allotted_trainee";*/ ?>',
                                    //     margin: [3, 0]
                                    // },
                                    // {
                                    //     text: '<?php /* echo "Total Trainees: {" . $total_trainee . "} Allotted: {" . $allotted_trainee . "} Remaining: {" . $remaining_trainee . "}" */ ?>',
                                    //     color: "#120E43",
                                    //     margin: [3, 0]
                                    // },

                                    {
                                        alignment: 'right',
                                        bold: true,
                                        fontSize: 12,
                                        // text: "Generated By - Maxizone",
                                        text: ['Generated By - ', {
                                            text: "Maxizone"
                                        }],
                                        color: "#120E43",
                                        margin: [10, 0]
                                        // 		text: ['page ', { text: page.toString() },	' of ',	{ text: pages.toString() }]
                                    }
                                    ],
                                    marginTop: 55
                                }
                            });


                            // Change dataTable layout (Table styling)
                            // To use predefined layouts uncomment the line below and comment the custom lines below
                            // doc.content[0].layout = 'lightHorizontalLines'; // noBorders , headerLineOnly
                            var objLayout = {};
                            objLayout['hLineWidth'] = function (i) {
                                return .5;
                            };
                            objLayout['vLineWidth'] = function (i) {
                                return .5;
                            };
                            objLayout['hLineColor'] = function (i) {
                                return '#aaa';
                            };
                            objLayout['vLineColor'] = function (i) {
                                return '#aaa';
                                // return '#00FFFFFF';
                            };
                            objLayout['paddingLeft'] = function (i) {
                                return 4;
                            };
                            objLayout['paddingRight'] = function (i) {
                                return 4;
                            };
                            doc.content[0].layout = objLayout;
                        }

                    },
                    {
                        extend: 'colvis',
                        text: 'COLUMNS',
                        className: 'btn-info',
                        key: { // press L for COLUMNS
                            key: 'l',
                            altKey: false
                        },
                        postfixButtons: [{
                            extend: 'colvisRestore',
                            text: 'Show All'
                        }]
                    },
                    ],
                    "columnDefs": [{
                        // //   DISABLE ORDERING OF ACTION COLUMN
                        // "targets": [0],
                        // // DISABLE SORTING ON LAST COLUMN
                        // // "targets": [-1],
                        // "orderable": false,
                    },],
                });
                // Apply the search
                table.columns().every(function () {
                    var that = this;
                    $('input', this.footer()).on('keyup change clear', function () {
                        if (that.search() !== this.value) {
                            that.search(this.value).draw();
                        }
                    });
                });
            });
        // DataTables with Column Search by Text Inputs
    </script>

    <script>
        // PASS DATA TO MODAL POPUP
            $(function () {
                $('#ModalShowMessage').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var message_rejection = button.data('message_rejection'); // Extract info from data-* attributes
                    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
					// package = "Package "+package;
					// payment = "20% Amount of "+package+"= Rs."+payment;
                    var modal = $(this);
                    document.getElementById('message_rejection').value = message_rejection;
                });
            });
        // PASS DATA TO MODAL POPUP

        // PASS DATA TO MODAL POPUP
            $(function () {
                $('#ModalShowPhoto').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var src = button.data('src'); // Extract info from data-* attributes
                    var src1 = button.data('src1'); // Extract info from data-* attributes
                    var from = button.data('from'); // Extract info from data-* attributes
                    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                    // package = "Package "+package;
                    // payment = "20% Amount of "+package+"= Rs."+payment;
                    var modal = $(this);

                    // return false;
                    // document.getElementById('buttonModalUploadBank').style.display = "none";
                    // document.getElementById('buttonModalUploadDocument').style.display = "none";
                    
                    if (from == "bank") {
                        // document.getElementById('buttonModalUploadBank').style.display = "block";
                        title = "Uploaded Bank Proof";
                    }

                    if (from == "kyc") {
                        // document.getElementById('buttonModalUploadDocument').style.display = "block";
                        title = "Uploaded PAN";
                    }

                    // document.getElementById('buttonModalUploadDocument'));
                    
                    if(src1 !== undefined) {
                        title = "Uploaded Aadhaar";
                        document.getElementById('photo2_container').style.display = "block";
                        document.getElementById('photo2').src = src1;
                    } else {
                        document.getElementById('photo2_container').style.display = "none";
                    }

                    modal.find('#modal_title_photo').html(title);
                    document.getElementById('photo1').src = src;
                });
            });
        // PASS DATA TO MODAL POPUP
    </script>

    <?php
        // function show_reg_send_msg($user_id_new,$package_amount,$name_add,$mobile,$email,$password) {
        function show_reg_send_msg($success_user_id,$success_name,$success_password) {
            // $msg = "$user_id_new,$package_amount,$name_add,$mobile,$email,$password";
            // $msg_type = "success";
            ?>
                <script>
                    user_id_new = "<?php echo $success_user_id; ?>";
                    name_add = "<?php echo $success_name; ?>";
                    password = "<?php echo $success_password; ?>";

                    document.addEventListener("DOMContentLoaded", function () {
                        $('#ModalShowRegistration').modal('show');
                        $('#success_user_id').val(user_id_new);
                        $('#success_name').val(name_add);
                        $('#success_password').val(password);
                    });

                    // PASS DATA TO MODAL POPUP
                        $(function () {
                            $('#ModalShowRegistration').on('show.bs.modal', function (event) {
                                var button = $(event.relatedTarget); // Button that triggered the modal
                                var package = button.data('package'); // Extract info from data-* attributes
                                var payment = button.data('payment'); // Extract info from data-* attributes
                                var beneficiary = button.data('beneficiary'); // Extract info from data-* attributes
                                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                                // package = "Package "+package;
                                // payment = "20% Amount of "+package+"= Rs."+payment;
                                var modal = $(this);
                                modal.find('#package_amount').val(package);
                                modal.find('#transaction_amount').val(payment);
                                modal.find('#to_user_id').val(beneficiary);
                            });
                        });
                    // PASS DATA TO MODAL POPUP
                </script>
            <?php
        }

        if ($show_modal == 1) {
            show_reg_send_msg($success_user_id,$success_name,$success_password);
        }
        if ($msg != '') {
            ?>
                <script>
                    showNotif("<?php echo $msg; ?>", "<?php echo $msg_type; ?>")
                </script>
            <?php 
            exit();
        }
    ?>
</body>

</html>
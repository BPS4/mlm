<!DOCTYPE html>
<html lang="en">

<?php
	// COLLEGE DASHBOARD PAGE
	ob_start();
	require_once("../db_connect.php");
	session_start();

	mysqli_query($conn, "set names 'utf8'");  //-------WORKING UTF8 CODE------//

	//-------CURRENT DATE AND TIME TO FEED---------//
	date_default_timezone_set('Asia/Kolkata');
	$current_date = date('Y-m-d H:i:s');

	extract($_REQUEST);

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

    $admin_access = 0;
    if (isset($_SESSION['admin_access'])) {
        $admin_access = $_SESSION['admin_access'];
    }

    $position = 4;
    $header_text = "KYC Details For $name ($user_id)";

	// DEFAULT
		$page_id_home = 1;
		$bank_update = $photo_update = $pan_update = $aadhaar_update = 0;
        $msg = $msg_type = "";
		$error = false;
        
        $bank_cheque_sel = $bank_passbook_sel = "";
        $bank_disabled = $aadhaar_disabled = $pan_disabled = $photo_disabled = "";
        
        $user_doc_status = "";

        $aadhaar_error = $pan_error = $bank_error = $photo_error = $address_error = "";
        
        
        $url_dir = "../assets/files/transaction";
        $url_dir_aadhaar = "../assets/files/aadhaar";
        $url_dir_pan = "../assets/files/pan";
        $url_dir_bank = "../assets/files/bank";
        $url_dir_photo = "../assets/files/photo";
	// DEFAULT

    // FUNCTIONS
        function fetch_sponsor_name($conn, $sponsor_id) {
            $query_sp_name = mysqli_query($conn,"SELECT `name` FROM `users` WHERE `user_id`='$sponsor_id'");
            if($rw = mysqli_fetch_array($query_sp_name)) {
                $name = $rw['name'];
            }
            return $name;
        }
    // FUNCTIONS

    // DEFAULT ORDER COLUMN SR
		$default_Order = 0;

    // UPDATE PERSONAL
        if (isset($_POST['submit_personal'])) {
            $user_id = mysqli_real_escape_string($conn,$_POST['user_id']);
            
            $fhname = mysqli_real_escape_string($conn,$_POST['fhname']);
            $whatsapp = mysqli_real_escape_string($conn,$_POST['whatsapp']);
            
            $address = mysqli_real_escape_string($conn,$_POST['address']);
            $city = mysqli_real_escape_string($conn,$_POST['city']);
            $state_name = mysqli_real_escape_string($conn,$_POST['state_name']);
            $pin_code = mysqli_real_escape_string($conn,$_POST['pin_code']);
            

            $fhname = trim($fhname);
            $whatsapp = trim($whatsapp);

            $address = trim($address);
            $city = trim($city);
            $state_name = trim($state_name);
            $pin_code = trim($pin_code);

            if (!$error) {
                $current_timestamp = time();
                
                $doc_pan_update = $doc_aadhaar_update = $value_insert_doc = $query_insert_bank = $doc_photo_update = $personal_update = "";

                $query_insert_address = "INSERT INTO `user_document`(`user_id`, `doc_type`, `doc_number`, `create_date`) VALUES ('$user_id', 'address', '$address, $city', '$current_date')";

                $personal_update = "`fhname`='$fhname',`whatsapp`='$whatsapp', ";
                $address_update = "`address`='$address',`city`='$city',`state`='$state_name',`pin_code`='$pin_code', ";

                // FOR SELECT QUERIES
                    $query_already_exist = "SELECT `id` FROM `user_document` WHERE `doc_type` IN ('address') AND `user_id`='$user_id' AND `status`!='rejected'";
                    $res = mysqli_query($conn, $query_already_exist);
                    $check_doc_available_address = mysqli_num_rows($res);
                    
                    if ($check_doc_available_address==0) {
                        //FOR UPDATE QUERIES
                            $query_update = mysqli_query($conn, "UPDATE `users` SET $address_update $personal_update `update_date`='$current_date' WHERE `user_id`='$user_id'");
                            $rows_affected = mysqli_affected_rows($conn);
                            
                        if ($rows_affected==1) {

                            if (isset($query_insert_address) && $query_insert_address != "") {
                                mysqli_query($conn, $query_insert_address);
                            }

                            $msg .= "Personal Details, Address, ";
                            $msg_type = "success";

                            $msg .= " Added Successfully!";

                            $personal_detail_done = true;
                        } else {
                            $msg .= " >> Error in Updating The Record...Try Again";
                            $msg_type = "error";
                        }
                    } else {
                        $msg = ">> Personal Details for User ID $user_id Added Already! <<";
                        $msg_type = "info";
                    }
                // FOR SELECT QUERIES

            } else {
                $msg .= " >> There was a problem uploading your record. Please try again.";
                $msg_type = "error";
            }
        }
    // UPDATE PERSONAL

    // UPDATE KYC
        if (isset($_POST['submit_kyc'])) {
            $user_id = mysqli_real_escape_string($conn,$_POST['user_id']);
            
            $pan = mysqli_real_escape_string($conn,$_POST['pan']);
            // $file_pan = mysqli_real_escape_string($conn,$_POST['file_pan']);
            $aadhaar = mysqli_real_escape_string($conn,$_POST['aadhaar']);
            
            $pan = trim($pan);
            $aadhaar = trim($aadhaar);

            $query_pan_exist = "SELECT `id` FROM `users` WHERE `pan`='$pan' AND `user_id`!='$user_id'";
            $res = mysqli_query($conn, $query_pan_exist);
            $check_pan_available = mysqli_num_rows($res);

            $query_aadhaar_exist = "SELECT `id` FROM `users` WHERE `aadhaar`='$aadhaar' AND `user_id`!='$user_id'";
            $res = mysqli_query($conn, $query_aadhaar_exist);
            $check_aadhaar_available = mysqli_num_rows($res);

            if ($check_pan_available>0) {
                $error = true;
                $msg .= " >> PAN No. Already Registered";                    
                $msg_type = "error";                    
            }

            if ($check_aadhaar_available>0) {
                $error = true;
                $msg .= " >> Aadhaar No. Already Registered";
                $msg_type = "error";
            }

            if ($check_pan_available>0 || $check_aadhaar_available>0) {
                goto error_occured;
            }

            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "JPG" => "image/jpg", "JPEG" => "image/jpeg", "png" => "image/png", "PNG" => "image/png", "webp" => "image/webp");

            /*****IMAGE UPLOAD*****/
                // Check if file was uploaded without errors
                if ((isset($_FILES["file_pan"]) && $_FILES["file_pan"]["error"] == 0)) {
                    $filename_pan = $_FILES["file_pan"]["name"];
                    
                    $filetype_pan = $_FILES["file_pan"]["type"];
                    
                    $filesize_pan = $_FILES["file_pan"]["size"];
                    
                    // Verify file extension
                    $ext_pan = pathinfo($filename_pan, PATHINFO_EXTENSION);
                    
                    // Extract FileName
                    $file_basename_pan = basename($filename_pan, ".$ext_pan");            
                    
                    if (!array_key_exists($ext_pan, $allowed)) {
                        $error = true;
                        $msg .= " >> Only JPEG/JPG/PNG/WEBP Formats are Allowed For PAN!!!";
                        $msg_type = "error";
                    }

                    // Verify file size - 500kB maximum
                    $minsize = 1 * 1024;
                    $maxsize = 500 * 1024;

                    if ($filesize_pan > $maxsize || $filesize_pan < $minsize) {
                        $error = true;
                        $pan_error = " >> Error!!! PAN File size should not be greater than 500kb For PAN.";
                        $msg .= " >> Error!!! File size should not be greater than 500kb For PAN.";
                        $msg_type = "error";
                    }
                }

                if ((isset($_FILES["file_aadhaar_front"]) && $_FILES["file_aadhaar_front"]["error"] == 0) && (isset($_FILES["file_aadhaar_back"]) && $_FILES["file_aadhaar_back"]["error"] == 0)) {
                    $filename_af = $_FILES["file_aadhaar_front"]["name"];
                    $filename_ab = $_FILES["file_aadhaar_back"]["name"];

                    $filetype_af = $_FILES["file_aadhaar_front"]["type"];
                    $filetype_ab = $_FILES["file_aadhaar_back"]["type"];

                    $filesize_af = $_FILES["file_aadhaar_front"]["size"];
                    $filesize_ab = $_FILES["file_aadhaar_back"]["size"];

                    // Verify file extension
                    $ext_af = pathinfo($filename_af, PATHINFO_EXTENSION);
                    $ext_ab = pathinfo($filename_ab, PATHINFO_EXTENSION);
                    
                    // Extract FileName
                    $file_basename_af = basename($filename_af, ".$ext_af");            
                    $file_basename_ab = basename($filename_ab, ".$ext_ab");            

                    if (!array_key_exists($ext_af, $allowed)) {
                        $error = true;
                        $msg .= " >> Only JPEG/JPG/PNG/WEBP Formats are Allowed For Aadhaar Front!!!";
                        $msg_type = "error";
                    }

                    if (!array_key_exists($ext_ab, $allowed)) {
                        $error = true;
                        $msg .= " >> Only JPEG/JPG/PNG/WEBP Formats are Allowed For Aadhaar Back!!!";
                        $msg_type = "error";
                    }

                    // Verify file size - 500kB maximum
                    $minsize = 1 * 1024;
                    $maxsize = 500 * 1024;

                    if ($filesize_af > $maxsize || $filesize_af < $minsize) {
                        $error = true;
                        $aadhaar_error = " >> Error!!! Aadhaar Front size should not be greater than 500kb For Aadhaar Front.";
                        $msg .= " >> Error!!! File size should not be greater than 500kb For Aadhaar Front.";
                        $msg_type = "error";
                    }

                    if ($filesize_ab > $maxsize || $filesize_ab < $minsize) {
                        $error = true;
                        $aadhaar_error = " >> Error!!! Aadhaar Back size should not be greater than 500kb For Aadhaar Back.";
                        $msg .= " >> Error!!! File size should not be greater than 500kb For Aadhaar Back.";
                        $msg_type = "error";
                    }
                }
                
                //----------IMAGE IS JPEG/JPG AND NO ERROR-----------//
                if (!$error) {
                    $current_timestamp = time();
                    // $filename = $current_timestamp.".$ext";
                    // $filename = "$file_basename-$current_timestamp".".$ext";

                    $doc_pan_update = $doc_aadhaar_update = $value_insert_doc = $query_insert_bank = $doc_photo_update = $personal_update = "";

                    if (isset($filename_pan) && $filename_pan != "") {
                        $uploadedfile_pan = $_FILES["file_pan"]["tmp_name"];
                        $filename_pan = "$user_id-pan-$current_timestamp".".$ext_pan";

                        $doc_pan_update = "`is_pan_updated`=1,`pan`='$pan',`pan_file`='$filename_pan',`pan_date`='$current_date', ";
                        
                        $file_dir_pan = "$url_dir_pan/$filename_pan";
                        
                        $value_insert_doc .= "('$user_id', 'pan', '$pan', '$filename_pan', NULL, '$current_date'),";
                    }

                    if ((isset($filename_af) && $filename_af != "") && (isset($filename_ab) && $filename_ab != "")) {
                        $uploadedfile_af = $_FILES["file_aadhaar_front"]["tmp_name"];
                        $uploadedfile_ab = $_FILES["file_aadhaar_back"]["tmp_name"];

                        $filename_af = "$user_id-aadhaar_front-$current_timestamp".".$ext_af";
                        $filename_ab = "$user_id-aadhaar_back-$current_timestamp".".$ext_ab";

                        $doc_aadhaar_update = "`is_aadhaar_updated`=1,`aadhaar`='$aadhaar',`aadhaar_front_file`='$filename_af',`aadhaar_back_file`='$filename_ab',`aadhaar_date`='$current_date', ";
                    
                        $file_dir_af = "$url_dir_aadhaar/$filename_af";
                        $file_dir_ab = "$url_dir_aadhaar/$filename_ab";
                    
                        $value_insert_doc .= "('$user_id', 'aadhaar', '$aadhaar', '$filename_af', '$filename_ab', '$current_date'),";
                    }

                    // FOR SELECT QUERIES
                        $query_already_exist = "SELECT `id` FROM `user_document` WHERE `doc_type` IN ('pan') AND `user_id`='$user_id' AND `status`!='rejected'";
                        $res = mysqli_query($conn, $query_already_exist);
                        $check_doc_available_pan = mysqli_num_rows($res);

                        $query_already_exist = "SELECT `id` FROM `user_document` WHERE `doc_type` IN ('aadhaar') AND `user_id`='$user_id' AND `status`!='rejected'";
                        $res = mysqli_query($conn, $query_already_exist);
                        $check_doc_available_aadhaar = mysqli_num_rows($res);
                        
                        if ($check_doc_available_pan==0 || $check_doc_available_aadhaar==0) {
                            //FOR UPDATE QUERIES
                                $query_update = mysqli_query($conn, "UPDATE `users` SET $doc_pan_update $doc_aadhaar_update `update_date`='$current_date' WHERE `user_id`='$user_id'");
                                $rows_affected = mysqli_affected_rows($conn);
                                
                            if ($rows_affected==1) {

                                $query_insert_doc = "INSERT INTO `user_document`(`user_id`, `doc_type`, `doc_number`, `doc_file`, `doc_file2`, `create_date`) VALUES ";

                                if ($value_insert_doc != "") {
                                    $value_insert_doc = rtrim($value_insert_doc,",");
                                    $query_insert_doc .= "$value_insert_doc";
                                    mysqli_query($conn, $query_insert_doc);
                                }
                                
                                // UPLOAD FILE
                                    if (
                                        (isset($file_dir_pan)) && 
                                        move_uploaded_file($_FILES['file_pan']['tmp_name'], $file_dir_pan)
                                        ) {
                                        $msg .= "PAN, ";
                                        $msg_type = "success";
                                        // echo "<script>window.close();</script>";
                                    }

                                    if (
                                            (isset($file_dir_af) && isset($file_dir_ab)) && 
                                            (move_uploaded_file($_FILES['file_aadhaar_front']['tmp_name'], $file_dir_af)) && 
                                            (move_uploaded_file($_FILES['file_aadhaar_back']['tmp_name'], $file_dir_ab))
                                        ) {

                                        $msg .= "Aadhaar, ";
                                        $msg_type = "success";
                                        // echo "<script>window.close();</script>";
                                    }
                                // UPLOAD FILE

                                $msg .= " Added Successfully!";

                                $kyc_detail_done = true;

                            } else {
                                $msg .= " >> Error in Updating The Record...Try Again";
                                $msg_type = "error";
                            }
                        } else {
                            $msg = ">> KYC Details for User ID $user_id Added Already! <<";
                            $msg_type = "info";
                        }
                    // FOR SELECT QUERIES

                } else {
                    $msg .= " >> There was a problem uploading your record. Please try again.";
                    $msg_type = "error";
                }
            /*****IMAGE UPLOAD*****/

        }
    // UPDATE KYC

    // UPDATE BANK
        if (isset($_POST['submit_bank'])) {
            $user_id = mysqli_real_escape_string($conn,$_POST['user_id']);
            
            $bank_name = mysqli_real_escape_string($conn,$_POST['bank_name']);
            $bank_name_other = mysqli_real_escape_string($conn,$_POST['bank_name_other']);
            $branch_name = mysqli_real_escape_string($conn,$_POST['branch_name']);
            $account_no = mysqli_real_escape_string($conn,$_POST['account_no']);
            $ifs_code = mysqli_real_escape_string($conn,$_POST['ifs_code']);
            $upi_handle = mysqli_real_escape_string($conn,$_POST['upi_handle']);
            $doc_type = mysqli_real_escape_string($conn,$_POST['doc_type']);
            // $file = mysqli_real_escape_string($conn,$_POST['file']);

            $bank_name = trim($bank_name);
            $bank_name_other = trim($bank_name_other);
            $branch_name = trim($branch_name);
            $account_no = trim($account_no);
            $ifs_code = trim($ifs_code);
            $upi_handle = trim($upi_handle);

            // BANK MANDATORY
                if (($bank_name == "" && $bank_name_other == "") || $branch_name == "" || $account_no == "" || $ifs_code == "") {
                    $error = true;
                    $msg .= "Bank Details Are Mandatory!!!";
                    $msg_type = "error";
                } else {
                    $bank_update_field = '`is_bank_updated`=1';
                }
            // BANK MANDATORY

            if ($bank_name_other != "") {
                $bank_name = $bank_name_other;
            }
            
            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "JPG" => "image/jpg", "JPEG" => "image/jpeg", "png" => "image/png", "PNG" => "image/png", "webp" => "image/webp");

            /*****IMAGE UPLOAD*****/
                // Check if file was uploaded without errors
                if ((isset($_FILES["file_bank"]) && $_FILES["file_bank"]["error"] == 0)) {
                    $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "JPG" => "image/jpg", "JPEG" => "image/jpeg", "png" => "image/png", "PNG" => "image/png", "webp" => "image/webp");
                    $filename_bank = $_FILES["file_bank"]["name"];
                    $filetype = $_FILES["file_bank"]["type"];
                    $filesize = $_FILES["file_bank"]["size"];

                    // Verify file extension
                    $ext_bank = pathinfo($filename_bank, PATHINFO_EXTENSION);
                    
                    // Extract FileName
                    $file_basename = basename($filename_bank, ".$ext_bank");            

                    if (!array_key_exists($ext_bank, $allowed)) {
                        $error = true;
                        $msg .= " >> Only JPEG/JPG/PNG/WEBP Formats are Allowed!!!";
                        $msg_type = "error";
                    }

                    // Verify file size - 500kB maximum
                    $minsize = 1 * 1024;
                    $maxsize = 500 * 1024;

                    if ($filesize > $maxsize || $filesize < $minsize) {
                        $error = true;
                        $msg .= " >> Error!!! Bank Proof size should not be greater than 500kb.";
                        $msg_type = "error";
                    }
                } else {
                    $error = true;
                    $msg .= " >> Bank Proof Not Uploaded";
                    $msg_type = "error";
                }
                
                //----------IMAGE IS JPEG/JPG AND NO ERROR-----------//
                if (!$error) {
                    $current_timestamp = time();
                    // $filename = $current_timestamp.".$ext";
                    // $filename = "$file_basename-$current_timestamp".".$ext";

                    $doc_pan_update = $doc_aadhaar_update = $value_insert_doc = $query_insert_bank = $doc_photo_update = $personal_update = "";

                    if (isset($filename_bank) && $filename_bank != "") {
                        $uploadedfile = $_FILES["file_bank"]["tmp_name"];
                        $filename_bank = "$user_id-$doc_type-$current_timestamp".".$ext_bank";

                        $doc_bank_update = "`bank_name`='$bank_name', `branch_name`='$branch_name', `account_no`='$account_no', `ifs_code`='$ifs_code', `upi_handle`='$upi_handle', $bank_update_field,  ";
                        
                        $file_dir_bank = "$url_dir_bank/$filename_bank";
                        
                        $query_insert_bank = "INSERT INTO `user_document`(`user_id`, `doc_type`, `doc_number`, `doc_file`, `bank_name`, `branch_name`, `account_no`, `ifs_code`, `upi_handle`, `create_date`) VALUES ('$user_id', '$doc_type', '$account_no', '$filename_bank', '$bank_name', '$branch_name', '$account_no', '$ifs_code', '$upi_handle', '$current_date')";
                    }

                    // FOR SELECT QUERIES
                        $query_already_exist = "SELECT `id` FROM `user_document` WHERE `doc_type` IN ('passbook','cheque') AND `user_id`='$user_id' AND `status`!='rejected'";
                        $res = mysqli_query($conn, $query_already_exist);
                        $check_doc_available_bank = mysqli_num_rows($res);
                        
                        if ($check_doc_available_bank==0) {
                            //FOR UPDATE QUERIES
                                $query_update = mysqli_query($conn, "UPDATE `users` SET $doc_bank_update `update_date`='$current_date' WHERE `user_id`='$user_id'");
                                $rows_affected = mysqli_affected_rows($conn);
                                
                            if ($rows_affected==1) {

                                if ($query_insert_bank != "") {
                                    mysqli_query($conn, $query_insert_bank);
                                }
                                
                                // UPLOAD FILE
                                    if (
                                        (isset($file_dir_bank)) && 
                                        (move_uploaded_file($_FILES['file_bank']['tmp_name'], $file_dir_bank))
                                    ) {

                                        $msg .= "Bank Details ";
                                        $msg_type = "success";
                                        // echo "<script>window.close();</script>";
                                    }
                                // UPLOAD FILE

                                $msg .= " Added Successfully!";

                                $bank_detail_done = true;
                                
                            } else {
                                $msg .= " >> Error in Updating The Record...Try Again";
                                $msg_type = "error";
                            }
                        } else {
                            $msg = ">> Bank Details for User ID $user_id Added Already! <<";
                            $msg_type = "info";
                        }
                    // FOR SELECT QUERIES

                } else {
                    $msg .= " >> There was a problem uploading your record. Please try again.";
                    $msg_type = "error";
                }
            /*****IMAGE UPLOAD*****/

        }
    // UPDATE BANK

    // UPDATE PROFILE PICTURE
        if (isset($_POST['submit_profile'])) {
            $user_id = mysqli_real_escape_string($conn,$_POST['user_id']);
            
            // ADD PROFILE PHOTO >> BASE64 IMAGE UPLOAD
                $allowed_extension = array("data:image/JPEG", "data:image/JPG", "data:image/jpeg", "data:image/jpg", "data:image/png", "data:image/PNG", "data:image/webp");

                // BASE64 IMAGE UPLOAD
                    /** if image is attached with request **/
                    // $image = "Yourbase64StringHere";
                    $image = "$file_photo";                    
                
                // Set image path
                    if ($image != ""){
                        // PHOTO IS UPLOADED
                        list($type, $image) = explode(';', $image);
                        list($size, $image) = explode(',', $image);
    
                        /** decode the base 64 image **/
                            $Image = base64_decode($image);
    
                        // GET IMAGE DATA >> HEIGHT WIDTH TYPE
                        $data = getimagesizefromstring($Image);
                        $mime_type = $data['mime']; // image/png
                        
                        $ext = substr($mime_type, strpos($mime_type, "/") + 1);    
    
                        // GET IMAGE SIZE FROM BASE64 IMAGE STRING
                            // $size = (int)(strlen(rtrim($image, '=')) * 0.75)/1024; // in kilo byte
    
                        // Verify file extension
                            if (!in_array($type, $allowed_extension)) {
                                // Type not allowed
                                $error = true;
                                $msg .= " >> Only JPEG/JPG/PNG/WEBP Formats are Allowed!!!";
                                $msg_type = "error";
                            } else {
                                // Type allowed
                            }
                        // Verify file extension
                        
                        // Verify file size - 1MB maximum
                            $minsize = 50 * 1024;   //50KB
                            $maxsize = 1024 * 1024;  //1MB
                        // Verify file size - 1MB maximum
                        

                        $current_timestamp = time();
                        $image_name = "$user_id-photo-$current_timestamp".".$ext";
                        $photo_dir = "$url_dir_photo/$image_name";
                        // HOLD PREVIOUS EXISTENCE
                        // if (!$error) {
                        //     if (file_exists($photo_dir)) {
                        //         $error = true;
                        //         $photo_error = "Unable To Upload The File As You Have Already Uploaded The Joining Letter Of This Trainee!!!";
                        //     } else {
                        //         // echo "The file $filename does not exist";
                        //     }
                        // }
                    }else{
                        // PHOTO IS NOT UPLOADED
                        //************** PHOTO MADE OPTIONAL 28-02-2023 **************//
                            // $error = true;
                            // $msg .= "Your Live Photo is not uploaded! Please Retry...";
                            // $msg_type = "error";
                        //************** PHOTO MADE OPTIONAL 28-02-2023 **************//
                    }
                // Set image path

                if (isset($photo_error) && $photo_error!="") {
                    echo "<script>alert('$photo_error')</script>";
                }
            // ADD PROFILE PHOTO >> BASE64 IMAGE UPLOAD

            if (!$error) {
                $current_timestamp = time();
                // $filename = $current_timestamp.".$ext";
                // $filename = "$file_basename-$current_timestamp".".$ext";

                $doc_pan_update = $doc_aadhaar_update = $value_insert_doc = $query_insert_bank = $doc_photo_update = $personal_update = "";

                if (isset($photo_dir) && $photo_dir != "") {
                    /** decode the base 64 image **/
                        $image = base64_decode($image);
                    /** decode the base 64 image **/
                    
                    $doc_photo_update = " `is_photo_updated`=1, `photo_file`='$image_name', `photo_date`='$current_date',  ";
                    
                    $query_insert_photo = "INSERT INTO `user_document`(`user_id`, `doc_type`, `doc_file`, `status`, `create_date`) VALUES ('$user_id', 'photo', '$image_name', 'approved', '$current_date')";
                }

                // FOR SELECT QUERIES
                    $query_already_exist = "SELECT `id` FROM `user_document` WHERE `doc_type` IN ('photo') AND `user_id`='$user_id' AND `status`!='rejected'";
                    $res = mysqli_query($conn, $query_already_exist);
                    $check_doc_available_photo = mysqli_num_rows($res);
                    
                    $check_doc_available_photo = 0;
                    if ($check_doc_available_photo==0) {
                        //FOR UPDATE QUERIES
                            $query_update = mysqli_query($conn, "UPDATE `users` SET $doc_photo_update `update_date`='$current_date' WHERE `user_id`='$user_id'");
                            $rows_affected = mysqli_affected_rows($conn);
                            
                        if ($rows_affected==1) {

                            if (isset($query_insert_photo) && $query_insert_photo != "") {
                                mysqli_query($conn, $query_insert_photo);
                            }

                            // $msg .= "Personal Details, Address, ";
                            $msg_type = "success";
                            
                            // UPLOAD FILE
                                if (isset($photo_dir) && $photo_dir != "") {
                                    if (file_put_contents($photo_dir, $image)) {
                                        $msg .= "Photo ";
                                        $msg_type = "success";
                                        // echo "<script>window.close();</script>";
                                    }
                                }
                            // UPLOAD FILE

                            $msg .= " Added Successfully!";

                            // $personal_detail_done = true;
                            stop_form_resubmit();
                        } else {
                            $msg .= " >> Error in Updating The Record...Try Again";
                            $msg_type = "error";
                        }
                    } else {
                        $msg = ">> Personal Details for User ID $user_id Added Already! <<";
                        $msg_type = "info";
                    }
                // FOR SELECT QUERIES

            } else {
                $msg .= " >> There was a problem uploading your record. Please try again.";
                $msg_type = "error";
            }
        }
    // UPDATE PROFILE PICTURE

    // GET DATA
        $query = "SELECT `sponsor_id`, `user_id`, `name`, `mobile`, `fhname`, `whatsapp`, `email`, `aadhaar`, `aadhaar_file`, `pan`, `pan_file`, `bank_name`, `branch_name`, `account_no`, `ifs_code`, `upi_handle`, `address`, `city`, `state` AS 'state_name', `pin_code`, `status`, `is_bank_updated`, `create_date`, `active_date` FROM `users` WHERE `user_id`='$user_id' ORDER BY `create_date` DESC";
        $query = mysqli_query($conn,$query);
        $res = mysqli_fetch_array($query);
        extract($res);
    // GET DATA

    if ($sponsor_id != "") {
        $sponsor_name = fetch_sponsor_name($conn, $sponsor_id);
    } else {
        $sponsor_name = $name;
        $sponsor_id = $user_id;
    }
    
    // INDIVIDUAL KYC STATUS
        $kyc_status = "<span class='btn btn-xs btn-warning'>PENDING</span>";
        // GET DATA OF SAME CATEGORY BY ONLY LAST CREATE DATE
        // $query_doc = "SELECT ud.* FROM `user_document` ud INNER JOIN (SELECT `doc_type`, MAX(`create_date`) AS max_date FROM `user_document` WHERE `user_id`='$member_id' GROUP BY `doc_type`) group_data ON ud.`doc_type`=group_data.`doc_type` AND ud.`create_date`=group_data.max_date";
        $query_doc = "SELECT * FROM `user_document` WHERE `user_id`='$user_id' ORDER BY `create_date` ASC";
        $res = mysqli_query($conn,$query_doc);
        $docs = mysqli_fetch_all($res,MYSQLI_ASSOC);
        $count_docs_uploaded = mysqli_num_rows($res);
        
        $aadhaar_update = $pan_update = $bank_update = $address_update = $photo_update = $kyc_done = 0;
        
        $i = 0;
        $count_docs = $count_docs_approved = $count_docs_rejected = 0;
        $aadhaar_approved = $pan_approved = $cheque_approved = $passbook_approved = $address_approved = $photo_approved = 0;
        $aadhaar_rejected = $pan_rejected = $cheque_rejected = $passbook_rejected = $address_rejected = $photo_rejected = 0;
        $aadhaar_pending = $pan_pending = $cheque_pending = $passbook_pending = $address_pending = $photo_pending = 0;
        $aadhaar_needed = $pan_needed = $bank_needed = $cheque_needed = $passbook_needed = $address_needed = $photo_needed = $personal_needed = 0;
        $aadhaar_readonly = $pan_readonly = $bank_readonly = $cheque_readonly = $passbook_readonly = $photo_readonly = $personal_readonly = "readonly";        
        
        if ($count_docs_uploaded == 0) {
            $aadhaar_needed = $pan_needed = $bank_needed = $cheque_needed = $passbook_needed = $address_needed = $photo_needed = 1;
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
                    $address_update =
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

        /* ***** 07-04-2023 ***** */
            // if ($photo_approved > 0) {
            //     $photo_needed = 0;
            // } else if ($photo_pending == 0) {
            //     $photo_needed = 1;
            //     $photo_readonly = "";
            // }
        /* ***** 07-04-2023 ***** */

        if ($address_approved > 0) {
            $address_needed = 0;
            
            $personal_needed = 0;
        } else if ($address_pending == 0) {
            $address_needed = 1;
            $address_readonly = "";

            $personal_needed = 1;
            $personal_readonly = "";
        }

        /* ***** 07-04-2023 ***** */
            // if ($address == "" || $address == NULL) {
            //     $personal_needed = 1;
            //     $personal_readonly = "";
            // }
        /* ***** 07-04-2023 ***** */

        // if ($user_doc_status == "approved") {
        // } else if ($user_doc_status == "rejected") {
        //     $kyc_status = "<span class='btn btn-xs btn-danger'>REJECTED</span>";
        // } else {
        // }

        // if ($count_docs_approved > 3) {
        if ($pan_approved > 0 && $aadhaar_approved > 0 && $address_approved > 0 && ($cheque_approved > 0 || $passbook_approved > 0)) {
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

    // UPDATE KYC
        // if (isset($_POST['submit_document'])) {
        //     $user_id = mysqli_real_escape_string($conn,$_POST['user_id']);
            
        //     $pan = mysqli_real_escape_string($conn,$_POST['pan']);
        //     // $file_pan = mysqli_real_escape_string($conn,$_POST['file_pan']);
        //     $aadhaar = mysqli_real_escape_string($conn,$_POST['aadhaar']);
        //     $address = mysqli_real_escape_string($conn,$_POST['address']);
        //     $city = mysqli_real_escape_string($conn,$_POST['city']);
        //     $state_name = mysqli_real_escape_string($conn,$_POST['state_name']);
        //     $pin_code = mysqli_real_escape_string($conn,$_POST['pin_code']);
        //     // $file_aadhaar_front = mysqli_real_escape_string($conn,$_POST['file_aadhaar_front']);
        //     // $file_aadhaar_back = mysqli_real_escape_string($conn,$_POST['file_aadhaar_back']);



        //     $bank_name = mysqli_real_escape_string($conn,$_POST['bank_name']);
        //     $bank_name_other = mysqli_real_escape_string($conn,$_POST['bank_name_other']);
        //     $branch_name = mysqli_real_escape_string($conn,$_POST['branch_name']);
        //     $account_no = mysqli_real_escape_string($conn,$_POST['account_no']);
        //     $ifs_code = mysqli_real_escape_string($conn,$_POST['ifs_code']);
        //     $upi_handle = mysqli_real_escape_string($conn,$_POST['upi_handle']);
        //     $doc_type = mysqli_real_escape_string($conn,$_POST['doc_type']);
        //     // $file = mysqli_real_escape_string($conn,$_POST['file']);

        //     $bank_name = trim($bank_name);
        //     $bank_name_other = trim($bank_name_other);
        //     $branch_name = trim($branch_name);
        //     $account_no = trim($account_no);
        //     $ifs_code = trim($ifs_code);
        //     $upi_handle = trim($upi_handle);
            
        //     // BANK MANDATORY
        //         if (($bank_name == "" && $bank_name_other == "") || $branch_name == "" || $account_no == "" || $ifs_code == "") {
        //             $error = true;
        //             $msg .= "Bank Details Are Mandatory!!!";
        //             $msg_type = "error";
        //         } else {
        //             $bank_update_field = '`is_bank_updated`=1';
        //         }
        //     // BANK MANDATORY

        //     if ($bank_name_other != "") {
        //         $bank_name = $bank_name_other;
        //     }


        //     $pan = trim($pan);
        //     $aadhaar = trim($aadhaar);
        //     $pan = strtoupper($pan);



        //     $fhname = mysqli_real_escape_string($conn,$_POST['fhname']);
        //     $whatsapp = mysqli_real_escape_string($conn,$_POST['whatsapp']);
            
        //     $fhname = trim($fhname);
        //     $whatsapp = trim($whatsapp);

        //     $query_pan_exist = "SELECT `id` FROM `users` WHERE `pan`='$pan' AND `user_id`!='$user_id'";
        //     $res = mysqli_query($conn, $query_pan_exist);
        //     $check_pan_available = mysqli_num_rows($res);

        //     $query_aadhaar_exist = "SELECT `id` FROM `users` WHERE `aadhaar`='$aadhaar' AND `user_id`!='$user_id'";
        //     $res = mysqli_query($conn, $query_aadhaar_exist);
        //     $check_aadhaar_available = mysqli_num_rows($res);

        //     if ($check_pan_available>0) {
        //         $error = true;
        //         $msg .= " >> PAN No. Already Registered";                    
        //         $msg_type = "error";                    
        //     }

        //     if ($check_aadhaar_available>0) {
        //         $error = true;
        //         $msg .= " >> Aadhaar No. Already Registered";
        //         $msg_type = "error";
        //     }

        //     if ($check_pan_available>0 || $check_aadhaar_available>0) {
        //         goto error_occured;
        //     }

        //     $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "JPG" => "image/jpg", "JPEG" => "image/jpeg", "png" => "image/png", "PNG" => "image/png", "webp" => "image/webp");

        //     /*****IMAGE UPLOAD*****/
        //         // Check if file was uploaded without errors
        //         if ((isset($_FILES["file_pan"]) && $_FILES["file_pan"]["error"] == 0)) {
        //             $filename_pan = $_FILES["file_pan"]["name"];
                    
        //             $filetype_pan = $_FILES["file_pan"]["type"];
                    
        //             $filesize_pan = $_FILES["file_pan"]["size"];
                    
        //             // Verify file extension
        //             $ext_pan = pathinfo($filename_pan, PATHINFO_EXTENSION);
                    
        //             // Extract FileName
        //             $file_basename_pan = basename($filename_pan, ".$ext_pan");            
                    
        //             if (!array_key_exists($ext_pan, $allowed)) {
        //                 $error = true;
        //                 $msg .= " >> Only JPEG/JPG/PNG/WEBP Formats are Allowed For PAN!!!";
        //                 $msg_type = "error";
        //             }

        //             // Verify file size - 500kB maximum
        //             $minsize = 1 * 1024;
        //             $maxsize = 500 * 1024;

        //             if ($filesize_pan > $maxsize || $filesize_pan < $minsize) {
        //                 $error = true;
        //                 $pan_error = " >> Error!!! PAN File size should not be greater than 500kb For PAN.";
        //                 $msg .= " >> Error!!! File size should not be greater than 500kb For PAN.";
        //                 $msg_type = "error";
        //             }
        //         } else {
        //             $error = true;
        //             $msg .= " >> PAN Card Photo Not Uploaded";
        //             $msg_type = "error";
        //         }

        //         if ((isset($_FILES["file_aadhaar_front"]) && $_FILES["file_aadhaar_front"]["error"] == 0) && (isset($_FILES["file_aadhaar_back"]) && $_FILES["file_aadhaar_back"]["error"] == 0)) {
        //             $filename_af = $_FILES["file_aadhaar_front"]["name"];
        //             $filename_ab = $_FILES["file_aadhaar_back"]["name"];

        //             $filetype_af = $_FILES["file_aadhaar_front"]["type"];
        //             $filetype_ab = $_FILES["file_aadhaar_back"]["type"];

        //             $filesize_af = $_FILES["file_aadhaar_front"]["size"];
        //             $filesize_ab = $_FILES["file_aadhaar_back"]["size"];

        //             // Verify file extension
        //             $ext_af = pathinfo($filename_af, PATHINFO_EXTENSION);
        //             $ext_ab = pathinfo($filename_ab, PATHINFO_EXTENSION);
                    
        //             // Extract FileName
        //             $file_basename_af = basename($filename_af, ".$ext_af");            
        //             $file_basename_ab = basename($filename_ab, ".$ext_ab");            

        //             if (!array_key_exists($ext_af, $allowed)) {
        //                 $error = true;
        //                 $msg .= " >> Only JPEG/JPG/PNG/WEBP Formats are Allowed For Aadhaar Front!!!";
        //                 $msg_type = "error";
        //             }

        //             if (!array_key_exists($ext_ab, $allowed)) {
        //                 $error = true;
        //                 $msg .= " >> Only JPEG/JPG/PNG/WEBP Formats are Allowed For Aadhaar Back!!!";
        //                 $msg_type = "error";
        //             }

        //             // Verify file size - 500kB maximum
        //             $minsize = 1 * 1024;
        //             $maxsize = 500 * 1024;

        //             if ($filesize_af > $maxsize || $filesize_af < $minsize) {
        //                 $error = true;
        //                 $aadhaar_error = " >> Error!!! Aadhaar Front size should not be greater than 500kb For Aadhaar Front.";
        //                 $msg .= " >> Error!!! File size should not be greater than 500kb For Aadhaar Front.";
        //                 $msg_type = "error";
        //             }

        //             if ($filesize_ab > $maxsize || $filesize_ab < $minsize) {
        //                 $error = true;
        //                 $aadhaar_error = " >> Error!!! Aadhaar Back size should not be greater than 500kb For Aadhaar Back.";
        //                 $msg .= " >> Error!!! File size should not be greater than 500kb For Aadhaar Back.";
        //                 $msg_type = "error";
        //             }
        //         } else {
        //             $error = true;
        //             $msg .= " >> Aadhaar Card Photo Not Uploaded";
        //             $msg_type = "error";
        //         }

        //         if ((isset($_FILES["file_bank"]) && $_FILES["file_bank"]["error"] == 0)) {
        //             $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "JPG" => "image/jpg", "JPEG" => "image/jpeg", "png" => "image/png", "PNG" => "image/png", "webp" => "image/webp");
        //             $filename_bank = $_FILES["file_bank"]["name"];
        //             $filetype = $_FILES["file_bank"]["type"];
        //             $filesize = $_FILES["file_bank"]["size"];

        //             // Verify file extension
        //             $ext_bank = pathinfo($filename_bank, PATHINFO_EXTENSION);
                    
        //             // Extract FileName
        //             $file_basename = basename($filename_bank, ".$ext_bank");            

        //             if (!array_key_exists($ext_bank, $allowed)) {
        //                 $error = true;
        //                 $msg .= " >> Only JPEG/JPG/PNG/WEBP Formats are Allowed!!!";
        //                 $msg_type = "error";
        //             }

        //             // Verify file size - 500kB maximum
        //             $minsize = 1 * 1024;
        //             $maxsize = 500 * 1024;

        //             if ($filesize > $maxsize || $filesize < $minsize) {
        //                 $error = true;
        //                 $msg .= " >> Error!!! Bank Proof size should not be greater than 500kb.";
        //                 $msg_type = "error";
        //             }
        //         } else {
        //             $error = true;
        //             $msg .= " >> Bank Proof Not Uploaded";
        //             $msg_type = "error";
        //         }
                
        //         // ADD PROFILE PHOTO >> BASE64 IMAGE UPLOAD
        //             $allowed_extension = array("data:image/JPEG", "data:image/JPG", "data:image/jpeg", "data:image/jpg", "data:image/png", "data:image/PNG", "data:image/webp");

        //             // BASE64 IMAGE UPLOAD
        //                 /** if image is attached with request **/
        //                 // $image = "Yourbase64StringHere";
        //                 $image = "$file_photo";
        //                 list($type, $image) = explode(';', $image);
        //                 list($size, $image) = explode(',', $image);
        //             // BASE64 IMAGE UPLOAD

        //             /** decode the base 64 image **/
        //                 $Image = base64_decode($image);

        //             // GET IMAGE DATA >> HEIGHT WIDTH TYPE
        //             $data = getimagesizefromstring($Image);
        //             $mime_type = $data['mime']; // image/png
                    
        //             $ext = substr($mime_type, strpos($mime_type, "/") + 1);    

        //             // GET IMAGE SIZE FROM BASE64 IMAGE STRING
        //                 // $size = (int)(strlen(rtrim($image, '=')) * 0.75)/1024; // in kilo byte

        //             // Verify file extension
        //                 if (!in_array($type, $allowed_extension)) {
        //                     // Type not allowed
        //                     $error = true;
        //                     $msg .= " >> Only JPEG/JPG/PNG/WEBP Formats are Allowed!!!";
        //                     $msg_type = "error";
        //                 } else {
        //                     // Type allowed
        //                 }
        //             // Verify file extension
                    
        //             // Verify file size - 1MB maximum
        //                 $minsize = 50 * 1024;   //50KB
        //                 $maxsize = 1024 * 1024;  //1MB
        //             // Verify file size - 1MB maximum
                    
        //             // Set image path
        //                 if ($image != ""){
        //                     // PHOTO IS UPLOADED
        //                     $current_timestamp = time();
        //                     $image_name = "$user_id-photo-$current_timestamp".".$ext";
        //                     $photo_dir = "$url_dir_photo/$image_name";
        //                     // HOLD PREVIOUS EXISTENCE
        //                     // if (!$error) {
        //                     //     if (file_exists($photo_dir)) {
        //                     //         $error = true;
        //                     //         $photo_error = "Unable To Upload The File As You Have Already Uploaded The Joining Letter Of This Trainee!!!";
        //                     //     } else {
        //                     //         // echo "The file $filename does not exist";
        //                     //     }
        //                     // }
        //                 }else{
        //                     // PHOTO IS NOT UPLOADED
        //                     $error = true;
        //                     $msg .= "Your Live Photo is not uploaded! Please Retry...";
        //                     $msg_type = "error";
        //                 }
        //             // Set image path

        //             if (isset($photo_error) && $photo_error!="") {
        //                 echo "<script>alert('$photo_error')</script>";
        //             }
        //         // ADD PROFILE PHOTO >> BASE64 IMAGE UPLOAD

        //         // {
        //         //     //ERROR IN fileS
        //         //     $msg .= " >> Error in PAN " . $_FILES["file_pan"]["error"];
        //         //     $msg .= " >> Error in Aadhaar Front " . $_FILES["file_aadhaar_front"]["error"];
        //         //     $msg .= " >> Error in Aadhaar Back " . $_FILES["file_aadhaar_back"]["error"];
        //         //     $msg_type = "error";
        //         // }
        //         //----------IMAGE IS JPEG/JPG AND NO ERROR-----------//
        //         if (!$error) {
        //             $current_timestamp = time();
        //             // $filename = $current_timestamp.".$ext";
        //             // $filename = "$file_basename-$current_timestamp".".$ext";

        //             $doc_pan_update = $doc_aadhaar_update = $value_insert_doc = $query_insert_bank = $doc_photo_update = $personal_update = "";

        //             if (isset($filename_pan) && $filename_pan != "") {
        //                 $uploadedfile_pan = $_FILES["file_pan"]["tmp_name"];
        //                 $filename_pan = "$user_id-pan-$current_timestamp".".$ext_pan";

        //                 $doc_pan_update = "`is_pan_updated`=1,`pan`='$pan',`pan_file`='$filename_pan',`pan_date`='$current_date', ";
                        
        //                 $file_dir_pan = "$url_dir_pan/$filename_pan";
                        
        //                 $value_insert_doc .= "('$user_id', 'pan', '$pan', '$filename_pan', NULL, '$current_date'),";
        //             }

        //             if ((isset($filename_af) && $filename_af != "") && (isset($filename_ab) && $filename_ab != "")) {
        //                 $uploadedfile_af = $_FILES["file_aadhaar_front"]["tmp_name"];
        //                 $uploadedfile_ab = $_FILES["file_aadhaar_back"]["tmp_name"];

        //                 $filename_af = "$user_id-aadhaar_front-$current_timestamp".".$ext_af";
        //                 $filename_ab = "$user_id-aadhaar_back-$current_timestamp".".$ext_ab";

        //                 $doc_aadhaar_update = "`is_aadhaar_updated`=1,`aadhaar`='$aadhaar',`aadhaar_front_file`='$filename_af',`aadhaar_back_file`='$filename_ab',`aadhaar_date`='$current_date', ";
                    
        //                 $file_dir_af = "$url_dir_aadhaar/$filename_af";
        //                 $file_dir_ab = "$url_dir_aadhaar/$filename_ab";
                    
        //                 $value_insert_doc .= "('$user_id', 'aadhaar', '$aadhaar', '$filename_af', '$filename_ab', '$current_date'),";
        //             }

        //             if (isset($filename_bank) && $filename_bank != "") {
        //                 $uploadedfile = $_FILES["file_bank"]["tmp_name"];
        //                 $filename_bank = "$user_id-$doc_type-$current_timestamp".".$ext_bank";

        //                 $doc_bank_update = "`bank_name`='$bank_name', `branch_name`='$branch_name', `account_no`='$account_no', `ifs_code`='$ifs_code', `upi_handle`='$upi_handle', $bank_update_field,  ";
                        
        //                 $file_dir_bank = "$url_dir_bank/$filename_bank";
                        
        //                 $query_insert_bank = "INSERT INTO `user_document`(`user_id`, `doc_type`, `doc_number`, `doc_file`, `bank_name`, `branch_name`, `account_no`, `ifs_code`, `upi_handle`, `create_date`) VALUES ('$user_id', '$doc_type', '$account_no', '$filename_bank', '$bank_name', '$branch_name', '$account_no', '$ifs_code', '$upi_handle', '$current_date')";
        //             }

        //             if (isset($photo_dir) && $photo_dir != "") {
        //                 /** decode the base 64 image **/
        //                     $image = base64_decode($image);
        //                 /** decode the base 64 image **/
                        
        //                 $doc_photo_update = " `is_photo_updated`=1, `photo_file`='$image_name', `photo_date`='$current_date',  ";
                        
        //                 $query_insert_photo = "INSERT INTO `user_document`(`user_id`, `doc_type`, `doc_file`, `create_date`) VALUES ('$user_id', 'photo', '$image_name', '$current_date')";
        //             }

        //             $personal_update = "`fhname`='$fhname',`whatsapp`='$whatsapp', ";
        //             $address_update = "`address`='$address',`city`='$city',`state`='$state_name',`pin_code`='$pin_code', ";

        //             // FOR SELECT QUERIES
        //                 // $query_already_exist = "SELECT `id` FROM `users` WHERE `is_pan_updated`=1 AND `is_aadhaar_updated`=1 AND `user_id`='$user_id'";
        //                 $query_already_exist = "SELECT `id` FROM `user_document` WHERE `doc_type` IN ('pan') AND `user_id`='$user_id' AND `status`!='rejected'";
        //                 $res = mysqli_query($conn, $query_already_exist);
        //                 $check_doc_available_pan = mysqli_num_rows($res);

        //                 $query_already_exist = "SELECT `id` FROM `user_document` WHERE `doc_type` IN ('aadhaar') AND `user_id`='$user_id' AND `status`!='rejected'";
        //                 $res = mysqli_query($conn, $query_already_exist);
        //                 $check_doc_available_aadhaar = mysqli_num_rows($res);
                        
        //                 $query_already_exist = "SELECT `id` FROM `user_document` WHERE `doc_type` IN ('passbook','cheque') AND `user_id`='$user_id' AND `status`!='rejected'";
        //                 $res = mysqli_query($conn, $query_already_exist);
        //                 $check_doc_available_bank = mysqli_num_rows($res);

        //                 $query_already_exist = "SELECT `id` FROM `user_document` WHERE `doc_type` IN ('photo') AND `user_id`='$user_id' AND `status`!='rejected'";
        //                 $res = mysqli_query($conn, $query_already_exist);
        //                 $check_doc_available_photo = mysqli_num_rows($res);
                        
        //                 if ($check_doc_available_pan==0 || $check_doc_available_aadhaar==0 || $check_doc_available_bank==0 || $check_doc_available_photo==0) {
        //                     //FOR UPDATE QUERIES
        //                         $query_update = mysqli_query($conn, "UPDATE `users` SET $doc_pan_update $doc_aadhaar_update $address_update $doc_bank_update $doc_photo_update $personal_update `update_date`='$current_date' WHERE `user_id`='$user_id'");
        //                         $rows_affected = mysqli_affected_rows($conn);
                                
        //                     if ($rows_affected==1) {

        //                         $query_insert_doc = "INSERT INTO `user_document`(`user_id`, `doc_type`, `doc_number`, `doc_file`, `doc_file2`, `create_date`) VALUES ";
                                
        //                         if ($value_insert_doc != "") {
        //                             $value_insert_doc = rtrim($value_insert_doc,",");
        //                             $query_insert_doc .= "$value_insert_doc";
        //                             mysqli_query($conn, $query_insert_doc);
        //                         }

        //                         if ($query_insert_bank != "") {
        //                             mysqli_query($conn, $query_insert_bank);
        //                         }

        //                         if ($query_insert_photo != "") {
        //                             mysqli_query($conn, $query_insert_photo);
        //                         }

        //                         // UPLOAD FILE
        //                         if (
        //                             (isset($file_dir_pan)) && 
        //                             move_uploaded_file($_FILES['file_pan']['tmp_name'], $file_dir_pan)
        //                             ) {
        //                             $msg .= "PAN, ";
        //                             $msg_type = "success";
        //                             // echo "<script>window.close();</script>";
        //                         }

        //                         if (
        //                                 (isset($file_dir_af) && isset($file_dir_ab)) && 
        //                                 (move_uploaded_file($_FILES['file_aadhaar_front']['tmp_name'], $file_dir_af)) && 
        //                                 (move_uploaded_file($_FILES['file_aadhaar_back']['tmp_name'], $file_dir_ab))
        //                             ) {

        //                             $msg .= "Aadhaar, ";
        //                             $msg_type = "success";
        //                             // echo "<script>window.close();</script>";
        //                         }
                                
        //                         if (
        //                             (isset($file_dir_bank)) && 
        //                             (move_uploaded_file($_FILES['file_bank']['tmp_name'], $file_dir_bank))
        //                         ) {

        //                             $msg .= "Bank Details, ";
        //                             $msg_type = "success";
        //                             // echo "<script>window.close();</script>";
        //                         }
                                
        //                         $msg .= "Address Added Successfully!";
        //                         $msg_type = "success";
                                
        //                         if (file_put_contents($photo_dir, $image)) {
        //                             $msg .= "Photo, ";
        //                             $msg_type = "success";
        //                             // echo "<script>window.close();</script>";
        //                         }

        //                     } else {
        //                         $msg .= " >> Error in Updating The Record...Try Again";
        //                         $msg_type = "error";
        //                     }
        //                 } else {
        //                     $msg = ">> KYC Details for User ID $user_id Added Already! <<";
        //                     $msg_type = "info";
        //                 }

        //             // correctImageOrientation($file_dir);

        //         } else {
        //             $msg .= " >> There was a problem uploading your record. Please try again.";
        //             $msg_type = "error";
        //         }
        //     /*****IMAGE UPLOAD*****/

        // }
    // UPDATE KYC

    error_occured:
?>

<script>
    admin_access = <?php echo $admin_access; ?>;
</script>

<head>
    <title><?php echo "$header_text - User Dashboard - $name" ?></title>
    <?php require_once("head.php"); ?>
</head>

<body>
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        
        <?php require_once("navbar.php"); ?>
            
            <!-- Page Body Start-->
            <div class="page-body-wrapper">
                <?php require_once("sidebar.php"); ?>
                    
                <div class="page-body">
                    <div class="container-fluid p-4">
                        <!-- Content -->
                            <!-- <div class="container-xxl flex-grow-1 container-p-y"> -->
                                <!-- <div class="row g-4 mb-4 align-items-center"> -->
                                    <!-- Users List Table -->
                                    <div class="row g-4 mb-4 align-items-center">
                                        <div class="col-sm-12 col-xl-12 xl-100">
                                            <div class="card">
                                                <div class="card-header pb-0">
                                                    <h5>User Profile </h5>
                                                    <hr>
                                                    <h5 class="card-title mb-1 text-bold"><?php echo $header_text; ?></h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row mb-3">
                                                        <div class="mb-3 col-md-6">
                                                            <label for="user_id" class="form-label">User Name</label>
                                                            <input type="text" class="form-control text-readonly" value="<?php echo "$user_id-$name"; ?>" readonly>
                                                            <input type="hidden" value="<?php echo $user_id; ?>" name="user_id" id="user_id">
                                                        </div>
                                                        <div class="mb-3 col-md-6">
                                                            <label for="sponsor_id_upload" class="form-label">Sponsor</label>
                                                            <input type="text" class="form-control text-readonly" value="<?php echo "$sponsor_id-$sponsor_name"; ?>" readonly>
                                                            <input type="hidden" value="<?php echo $sponsor_id; ?>" name="sponsor_id_upload">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-3 col-xs-12 mb-3">
                                                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                                                <a class="nav-link active text-start" id="v-pills-personal-tab" data-bs-toggle="pill" href="#v-pills-personal" role="tab" aria-controls="v-pills-home" aria-selected="true">
                                                                    Personal Details
                                                                </a>
                                                                <a class="nav-link text-start" id="v-pills-kyc-tab" data-bs-toggle="pill" href="#v-pills-kyc" role="tab" aria-controls="v-pills-kyc1" aria-selected="false">
                                                                    KYC
                                                                </a>
                                                                <a class="nav-link text-start" id="v-pills-banking-tab" data-bs-toggle="pill" href="#v-pills-banking" role="tab" aria-controls="v-pills-messages" aria-selected="false">
                                                                    Banking
                                                                </a>
                                                                <a class="nav-link text-start" id="v-pills-profile-tab" data-bs-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                                                                    Profile Photo
                                                                </a>
                                                                <!-- <a class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                                                                    Settings
                                                                </a> -->
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-9 col-xs-12 mb-3">
                                                            <div class="tab-content" id="v-pills-tabContent">
                                                                <div class="tab-pane fade show active" id="v-pills-personal" role="tabpanel" aria-labelledby="v-pills-personal-tab">
                                                                    <form method="POST" action="" id="form_upload_record_personal" enctype="multipart/form-data">
                                                                        <input type="hidden" value="<?php echo $user_id; ?>" name="user_id" id="user_id">
                                                                        <input type="hidden" value="<?php echo $sponsor_id; ?>" name="sponsor_id_upload">
                                                                        <div class="row align-items-center">
                                                                            <label for="personal" class="text-bold mt-1 lead">Personal Details</label>

                                                                            <div class="mb-3 col-md-6">
                                                                                <label for="" class="form-label">Mobile</label>
                                                                                <input type="text" class="form-control text-readonly" value="<?php echo $mobile; ?>" readonly>
                                                                            </div>
                                                                            <div class="mb-3 col-md-6">
                                                                                <label for="" class="form-label">Email</label>
                                                                                <input type="text" class="form-control text-readonly" value="<?php echo $email; ?>" readonly>
                                                                            </div>
                                                                            
                                                                            <div class="mb-3 col-xl-6 col-md-6">
                                                                                <label for="fhname" class="form-label">Father / Husband Name</label>
                                                                                <input type="text" class="form-control" id="fhname" name="fhname" placeholder="Father/Husband Name" value="<?php echo isset($fhname) ? $fhname : ''; ?>" pattern="^[A-Za-z ]+$" title="Enter valid Name without any special character" required <?php echo $personal_readonly; ?>>
                                                                            </div>

                                                                            <div class="mb-3 col-xl-6 col-md-6">
                                                                                <label for="whatsapp" class="form-label">Whatsapp Number</label>
                                                                                <input type="text" class="form-control" id="whatsapp" name="whatsapp" placeholder="Whatsapp Number" value="<?php echo isset($whatsapp) ? $whatsapp : ''; ?>" min="0" maxlength="10" pattern="[5-9]{1}[0-9]{9}" title="Enter valid 10 digit Whatsapp Number" required <?php echo $personal_readonly; ?>>
                                                                            </div>

                                                                            <div class="mb-3 col-md-12">
                                                                                <label for="address" class="form-label">Address</label>
                                                                                <textarea rows="2" class="form-control" id="address" name="address" placeholder="Your complete address" required <?php echo $personal_readonly; ?>><?php echo $address;?></textarea>
                                                                            </div>
                                                                            <div class="mb-3 col-md-5">
                                                                                <label for="city" class="form-label">City</label>
                                                                                <input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo $city;?>" <?php echo $personal_readonly; ?>>
                                                                            </div>
                                                                            <div class="mb-3 col-md-4">
                                                                                <label for="state" class="form-label">State</label>
                                                                                <select class="form-control js-example-basic-single select2" id="state_name" name="state_name" required <?php echo $personal_readonly; echo ($personal_needed==0)?" disabled":""; ?>>
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
                                                                                <?php
                                                                                    if ($personal_needed==0) {
                                                                                        ?>
                                                                                            <input type="hidden" name="state_name" value="<?php $state_name; ?>">
                                                                                        <?php
                                                                                    }
                                                                                ?>
                                                                            </div>
                                                                            <div class="mb-3 col-md-3">
                                                                                <label for="pin_code" class="form-label">PIN Code</label>
                                                                                <input type="text" class="form-control" id="pin_code" name="pin_code" placeholder="PIN Code" value="<?php echo $pin_code;?>" min="0" maxlength="6" pattern="[0-9]{6}" title="Enter valid 6 digit PIN Code" required <?php echo $personal_readonly; ?>>
                                                                            </div>

                                                                            <h5 class="text-bold text-danger"><?php echo ($address_error!="")?"Error in PERSONAL DETAILS - ($address_error)":""; ?></h5>

                                                                            <div class="text-center pb-5">
                                                                                <?php
                                                                                    if ($personal_needed == 1) {
                                                                                        ?>
                                                                                            <button type="submit" class="btn btn-success" id="btn_submit_personal" name="submit_personal">Save Changes</button>
                                                                                        <?php
                                                                                    } else {
                                                                                        ?>
                                                                                        <?php
                                                                                    }
                                                                                ?>
                                                                            </div>

                                                                        </div>

                                                                    </form>
                                                                </div>
                                                                <div class="tab-pane fade" id="v-pills-kyc" role="tabpanel" aria-labelledby="v-pills-kyc-tab">
                                                                    <form method="POST" action="" id="form_upload_record_kyc" enctype="multipart/form-data">
                                                                        <input type="hidden" value="<?php echo $user_id; ?>" name="user_id" id="user_id">
                                                                        <input type="hidden" value="<?php echo $sponsor_id; ?>" name="sponsor_id_upload">

                                                                        <div class="row g-2">
                                                                            <label for="pan" class="text-bold mt-1 lead">KYC Details</label>
                                                                            
                                                                            <div class="mb-3 col-xl-6 col-md-6">
                                                                                <label for="pan" class="form-label">PAN Number</label>
                                                                                <input type="text" class="form-control" id="pan" name="pan" placeholder="PAN No." value="<?php echo isset($pan) ? $pan : ''; ?>" min="0" maxlength="10" pattern="[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}" title="Enter valid 10 digit PAN Number" required <?php echo $pan_readonly; ?>>
                                                                            </div>
                                                                            
                                                                            <?php
                                                                                if ($pan_needed == 1) {
                                                                                    ?>
                                                                                        <div class="mb-3 col-xl-6 col-md-6">
                                                                                            <label for="file_pan" class="form-label">Upload PAN <span class="text-danger">*jpg/jpeg/png format; 100KB max</span></label>
                                                                                            <input type="file" name="file_pan" id="file_pan" class="form-control" accept="image/*" required onchange="if(admin_access==0) {check_image_size_before_upload(this.id,100);}">
                                                                                        </div>

                                                                                    <?php
                                                                                } else {
                                                                                    ?>
                                                                                        <div class="mb-3 col-xl-6 col-md-6">
                                                                                            <label class="form-label">Uploaded PAN</label>
                                                                                            <input type="text" class="form-control bg-label-success show-pointer" data-bs-toggle='modal' data-bs-target='#ModalShowPhoto' data-src="<?php echo $pan_doc; ?>" data-from="kyc" value="View Now" disabled>
                                                                                        </div>
                                                                                    <?php
                                                                                }
                                                                            ?>   
                                                                            <h5 class="text-bold text-danger"><?php echo ($pan_error!="")?"Error in PAN - ($pan_error)":""; ?></h5>

                                                                            <div class="mb-3 col-xl-6">
                                                                                <label for="aadhaar" class="form-label">Aadhaar Number</label>
                                                                                <input type="text" class="form-control" id="aadhaar" name="aadhaar" placeholder="Aadhaar No." value="<?php echo isset($aadhaar) ? $aadhaar : ''; ?>" min="0" maxlength="12" pattern="[0-9]{12}" title="Enter valid 12 digit Aadhaar Number" required <?php echo $aadhaar_readonly; ?>>
                                                                            </div>
                                                                            <?php
                                                                                if ($aadhaar_needed == 1) {
                                                                                    ?>
                                                                                        <div class="mb-3 col-xl-6">
                                                                                            <label for="file_aadhaar_front" class="form-label">Aadhaar Front<span class="text-danger">*jpg/jpeg/png format; 100KB max</span></label>
                                                                                            <input type="file" name="file_aadhaar_front" id="file_aadhaar_front" class="form-control" accept="image/*" required onchange="if(admin_access==0) {check_image_size_before_upload(this.id,100);}">
                                                                                        </div>
                                                                                        <div class="mb-3 col-xl-6">
                                                                                            <label for="file_aadhaar_back" class="form-label">Aadhaar Back<span class="text-danger">*jpg/jpeg/png format; 100KB max</span></label>
                                                                                            <input type="file" name="file_aadhaar_back" id="file_aadhaar_back" class="form-control" accept="image/*" required onchange="if(admin_access==0) {check_image_size_before_upload(this.id,100);}">
                                                                                        </div>
                                                                                    <?php
                                                                                } else {
                                                                                    ?>
                                                                                        <div class="mb-3 col-xl-6">
                                                                                            <label class="form-label">Uploaded Aadhaar</label>
                                                                                            <input type="text" class="form-control bg-label-success show-pointer" data-bs-toggle='modal' data-bs-target='#ModalShowPhoto' data-src="<?php echo $aadhaar_doc_front; ?>" data-src1="<?php echo $aadhaar_doc_back; ?>" data-from="kyc" value="View Now" disabled>
                                                                                        </div>
                                                                                    <?php
                                                                                }
                                                                            ?>
                                                                            <h5 class="text-bold text-danger"><?php echo ($aadhaar_error!="")?"Error in AADHAAR - ($aadhaar_error)":""; ?></h5>
                                                                            
                                                                            <div class="text-center pb-5">
                                                                                <?php
                                                                                    if ($pan_needed == 1 || $aadhaar_needed == 1) {
                                                                                        ?>
                                                                                            <button type="submit" class="btn btn-success" id="btn_submit_kyc" name="submit_kyc">Save Changes</button>
                                                                                        <?php
                                                                                    } else {
                                                                                        ?>
                                                                                        <?php
                                                                                    }
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="tab-pane fade" id="v-pills-banking" role="tabpanel" aria-labelledby="v-pills-banking-tab">
                                                                    <form method="POST" action="" id="form_upload_record_banking" enctype="multipart/form-data">
                                                                        <input type="hidden" value="<?php echo $user_id; ?>" name="user_id" id="user_id">
                                                                        <input type="hidden" value="<?php echo $sponsor_id; ?>" name="sponsor_id_upload">

                                                                        <div class="row g-2">
                                                                            <label for="bank_name" class="text-bold mt-1 lead">Banking info <span class="text-bold text-danger"><?php echo ($bank_error!="")?"Error - ($bank_error)":""; ?></span></label>
                                                                            
                                                                            <div class="mb-3 col-xl-7">
                                                                                <label for="bank_name" class="form-label">Bank Name <span class="text-danger">*</span></label>
                                                                                <select class="select2 js-example-basic-single form-select" name="bank_name" id="bank_name" required onchange="set_bank_field();" <?php echo $bank_readonly; echo ($bank_needed!=1)?" disabled":""; ?>>
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
                                                                                <input type="text" class="form-control" name="bank_name_other" id="bank_name_other" placeholder="In case of other Bank, specify here" value="<?php echo ($bank_selected == 0)?$bank_name:'';?>" onblur="set_bank_attr();" <?php echo $bank_readonly; ?> style="border: 1px solid #ced4da !important;">
                                                                            </div>
                                                                            
                                                                            <div class="mb-3 col-md-12">
                                                                                <label for="branch_name" class="form-label">Branch Name <span class="text-danger">*</span></label>
                                                                                <input type="text" class="form-control" name="branch_name" id="branch_name" placeholder="Branch" value="<?php echo $branch_name;?>" required <?php echo $bank_readonly; ?>>
                                                                            </div>
                                                                            <div class="mb-3 col-md-6">
                                                                                <label for="account_no" class="form-label">Account Number <span class="text-danger">*</span></label>
                                                                                <input type="text" class="form-control" name="account_no" id="account_no" placeholder="Account Number" value="<?php echo $account_no;?>" required <?php echo $bank_readonly; ?>>
                                                                            </div>
                                                                            <div class="mb-3 col-md-6">
                                                                                <label for="ifs_code" class="form-label">IFSC Code <span class="text-danger">*</span></label>
                                                                                <input type="text" class="form-control" name="ifs_code" id="ifs_code" placeholder="IFSC Code" value="<?php echo $ifs_code;?>" required <?php echo $bank_readonly; ?>>
                                                                            </div>
                                                                            <!-- <div class="mb-3 col-md-6">
                                                                                <label for="upi_handle" class="form-label">UPI Handle (@ybl, @paytm etc...)</label>
                                                                                <input type="text" class="form-control" name="upi_handle" id="upi_handle" placeholder="UPI" value="<?php echo $upi_handle;?>" <?php echo $bank_readonly; ?>>
                                                                            </div> -->
                                                                            <input type="hidden" name="upi_handle" value="<?php echo $upi_handle;?>">

                                                                            <div class="mb-3 col-xl-6 col-md-4">
                                                                                <label for="doc_type" class="form-label">Bank Proof <span class="text-danger">*</span></label>
                                                                                <select class="select2 form-select" name="doc_type" id="doc_type" required <?php echo $bank_readonly; echo ($bank_needed==0)?" disabled":""; ?>>
                                                                                    <option value="" hidden>Choose Document Type</option>
                                                                                    <option value="cheque" <?php echo $bank_cheque_sel; ?>>Cancelled Cheque</option>
                                                                                    <option value="passbook" <?php echo $bank_passbook_sel; ?>>Passbook</option>
                                                                                </select>
                                                                            </div>
                                                                            <?php
                                                                                if ($bank_needed == 1) {
                                                                                    ?>
                                                                                        <div class="mb-3 col-xl-6 col-md-8">
                                                                                            <label for="file_bank_proof" class="form-label">Proof Photo <span class="text-danger">*jpg/jpeg/png format; 500KB max</span></label>
                                                                                            <input type="file" name="file_bank" id="file_bank_proof" class="form-control" accept="image/*" required onchange="if(admin_access==0) {check_image_size_before_upload(this.id,500);}">
                                                                                        </div>
                                                                                    <?php
                                                                                } else {
                                                                                    ?>
                                                                                        <div class="mb-3 col-xl-6 col-md-8">
                                                                                            <label for="file_bank_proof" class="form-label">Uploaded Photo</label>
                                                                                            <!-- <span class='mt-2 badge bg-label-info show-pointer' > -->

                                                                                            <input type="text" class="form-control bg-label-success show-pointer" data-bs-toggle='modal' data-bs-target='#ModalShowPhoto' data-src="<?php echo $bank_doc; ?>" data-from="bank" value="View Now" disabled>
                                                                                        </div>
                                                                                    <?php
                                                                                }
                                                                            ?>
                                                                            
                                                                            <div class="text-center pb-5">
                                                                                <?php
                                                                                    if ($bank_needed == 1) {
                                                                                        ?>
                                                                                            <button type="submit" class="btn btn-success" id="btn_submit_bank" name="submit_bank">Save Changes</button>
                                                                                        <?php
                                                                                    } else {
                                                                                        ?>
                                                                                        <?php
                                                                                    }
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                                                    <form method="POST" action="" id="form_upload_record_profile" enctype="multipart/form-data">
                                                                        <input type="hidden" value="<?php echo $user_id; ?>" name="user_id" id="user_id">
                                                                        <input type="hidden" value="<?php echo $sponsor_id; ?>" name="sponsor_id_upload">
                                                                        <div class="row align-items-center">
                                                                            <label for="personal" class="text-bold mt-1 lead">Profile Picture</label>

                                                                            <?php
                                                                                // if ($photo_needed == 1) {
                                                                                    ?>
                                                                                        <div class="mb-3 col-md-12 text-center">
                                                                                            <label for="file" class="form-label" style="float:left;">Upload Photo <span class="text-danger">* (Upload Your Realtime Photo)</span></label>
                                                                                            <!-- <input type="file" name="file" id="file_photo" class="form-control" accept="image/*" capture required onchange="loadImageFile(this.id)"> -->
                                                                                            <input type="file" name="file" id="file_photo" class="form-control" accept="image/*" capture onchange="loadImageFile(this.id)" required>
                                                                                            <!-- <input type="file" name="file" id="file_photo" class="form-control" accept="image/*;capture=camera" required onchange="loadImageFile(this.id)"> -->
                                                                                            <h5 class="text-bold text-danger"><?php echo $photo_error; ?></h5>
                                                                                            <br>
                                                                                            <input type="hidden" name="file_photo" id="upload_compress" type="file" value="" />
                                                                                            <img src="#" id="upload_preview" style="border-radius: 15px;">
                                                                                        </div>
                                                                                    <?php
                                                                                // } else {
                                                                                    ?>
                                                                                        <div class="mb-3 col-md-12 text-center">
                                                                                            <label class="form-label">Uploaded Photo</label>
                                                                                            <br>
                                                                                            <img src="<?php echo $photo; ?>" style="width:100px;">
                                                                                            <!-- <input type="text" class="form-control bg-label-success show-pointer" data-bs-toggle='modal' data-bs-target='#ModalShowPhoto' data-src="<?php echo $pan_doc; ?>" data-from="kyc" value="View Now" disabled> -->
                                                                                        </div>
                                                                                    <?php
                                                                                // }
                                                                            ?>

                                                                            <div class="text-center pb-5">
                                                                               <button type="submit" class="btn btn-success" id="btn_submit_profile" name="submit_profile">Save Changes</button>
                                                                            </div>

                                                                        </div>

                                                                    </form>
                                                                </div>
                                                                <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                                                    <p>
                                                                        
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                            /*
                                                <div class="card px-1 d-none">
                                                    <div class="card-header border-bottom ps-1 pb-2 mb-3">
                                                        <h5 class="card-title mb-1 text-bold"><?php echo $header_text; ?></h5>
                                                    </div>
                                                    <div class="card-datatable table-responsive px-3">
                                                        <form method="POST" action="" id="form_upload_record" enctype="multipart/form-data">
                                                            <div class="row align-items-center">
                                                                <label for="personal" class="text-bold mt-1 lead">Personal Details</label>

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

                                                                <div class="mb-3 col-xl-6 col-md-6">
                                                                    <label for="fhname" class="form-label">Father / Husband Name</label>
                                                                    <input type="text" class="form-control" id="fhname" name="fhname" placeholder="Father/Husband Name" value="<?php echo isset($fhname) ? $fhname : ''; ?>" pattern="^[A-Za-z ]+$" title="Enter valid Name without any special character" required>
                                                                </div>

                                                                <div class="mb-3 col-xl-6 col-md-6">
                                                                    <label for="whatsapp" class="form-label">Whatsapp Number</label>
                                                                    <input type="text" class="form-control" id="whatsapp" name="whatsapp" placeholder="Whatsapp Number" value="<?php echo isset($whatsapp) ? $whatsapp : ''; ?>" min="0" maxlength="10" pattern="[5-9]{1}[0-9]{9}" title="Enter valid 10 digit Whatsapp Number" required>
                                                                </div>


                                                                <div class="row g-2">
                                                                    <label for="pan" class="text-bold mt-1">PAN Details</label>

                                                                    <div class="mb-3 col-xl-6 col-md-6">
                                                                        <label for="pan" class="form-label">PAN Number</label>
                                                                        <input type="text" class="form-control" id="pan" name="pan" placeholder="PAN No." value="<?php echo isset($pan) ? $pan : ''; ?>" min="0" maxlength="10" pattern="[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}" title="Enter valid 10 digit PAN Number" required <?php echo $pan_disabled; ?>>
                                                                    </div>
                                                                    
                                                                    <?php
                                                                        if ($pan_update == 0) {
                                                                            ?>
                                                                                <div class="mb-3 col-xl-6 col-md-6">
                                                                                    <label for="file_pan" class="form-label">Upload PAN <span class="text-danger">*jpg/jpeg/png format; 100KB max</span></label>
                                                                                    <input type="file" name="file_pan" id="file_pan" class="form-control" accept="image/*" required onchange="if(admin_access==0) {check_image_size_before_upload(this.id,100);}">
                                                                                </div>

                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                                <div class="mb-3 col-xl-6 col-md-6">
                                                                                    <label class="form-label">Uploaded PAN</label>
                                                                                    <input type="text" class="form-control bg-label-success show-pointer" data-bs-toggle='modal' data-bs-target='#ModalShowPhoto' data-src="<?php echo $pan_doc; ?>" data-from="kyc" value="View Now" disabled>
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
                                                                                    <label for="file_aadhaar_front" class="form-label">Aadhaar Front<span class="text-danger">*jpg/jpeg/png format; 100KB max</span></label>
                                                                                    <input type="file" name="file_aadhaar_front" id="file_aadhaar_front" class="form-control" accept="image/*" required onchange="if(admin_access==0) {check_image_size_before_upload(this.id,100);}">
                                                                                </div>
                                                                                <div class="mb-3 col-xl-4">
                                                                                    <label for="file_aadhaar_back" class="form-label">Aadhaar Back<span class="text-danger">*jpg/jpeg/png format; 100KB max</span></label>
                                                                                    <input type="file" name="file_aadhaar_back" id="file_aadhaar_back" class="form-control" accept="image/*" required onchange="if(admin_access==0) {check_image_size_before_upload(this.id,100);}">
                                                                                </div>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                                <div class="mb-3 col-xl-8">
                                                                                    <label class="form-label">Uploaded Aadhaar</label>
                                                                                    <input type="text" class="form-control bg-label-success show-pointer" data-bs-toggle='modal' data-bs-target='#ModalShowPhoto' data-src="<?php echo $aadhaar_doc_front; ?>" data-src1="<?php echo $aadhaar_doc_back; ?>" data-from="kyc" value="View Now" disabled>
                                                                                </div>
                                                                            <?php
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="row g-2">
                                                                <label for="address" class="text-bold mt-1">Address info <span class="text-bold text-danger"><?php echo ($address_error!="")?"Error - ($address_error)":""; ?></span></label>
                                                                <div class="mb-3 col-md-12">
                                                                    <label for="address" class="form-label">Address</label>
                                                                    <textarea rows="2" class="form-control" id="address" name="address" placeholder="Your complete address" required><?php echo $address;?></textarea>
                                                                </div>
                                                                <div class="mb-3 col-md-6">
                                                                    <label for="city" class="form-label">City</label>
                                                                    <input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo $city;?>">
                                                                </div>
                                                                <div class="mb-3 col-md-4">
                                                                    <label for="state" class="form-label">State</label>
                                                                    <select class="form-control js-example-basic-single select2" id="state_name" name="state_name" required>
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

                                                            <div class="row g-2">
                                                                <label for="bank_name" class="text-bold mt-1">Banking info <span class="text-bold text-danger"><?php echo ($bank_error!="")?"Error - ($bank_error)":""; ?></span></label>
                                                                
                                                                <div class="mb-3 col-xl-7">
                                                                    <label for="bank_name" class="form-label">Bank Name <span class="text-danger">*</span></label>
                                                                    <select class="select2 js-example-basic-single form-select" name="bank_name" id="bank_name" required onchange="set_bank_field();" <?php echo $bank_readonly; echo ($bank_needed!=1)?" disabled":""; ?>>
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
                                                                    <input type="text" class="form-control" name="bank_name_other" id="bank_name_other" placeholder="In case of other Bank, specify here" value="<?php echo ($bank_selected == 0)?$bank_name:'';?>" onblur="set_bank_attr();" <?php echo $bank_readonly; ?> style="border: 1px solid #ced4da !important;">
                                                                </div>
                                                                
                                                                <div class="mb-3 col-md-6">
                                                                    <label for="branch_name" class="form-label">Branch Name <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" name="branch_name" id="branch_name" placeholder="Branch" value="<?php echo $branch_name;?>" required <?php echo $bank_readonly; ?>>
                                                                </div>
                                                                <div class="mb-3 col-md-6">
                                                                    <label for="account_no" class="form-label">Account Number <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" name="account_no" id="account_no" placeholder="Account Number" value="<?php echo $account_no;?>" required <?php echo $bank_readonly; ?>>
                                                                </div>
                                                                <div class="mb-3 col-md-6">
                                                                    <label for="ifs_code" class="form-label">IFSC Code <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" name="ifs_code" id="ifs_code" placeholder="IFSC Code" value="<?php echo $ifs_code;?>" required <?php echo $bank_readonly; ?>>
                                                                </div>
                                                                <div class="mb-3 col-md-6">
                                                                    <label for="upi_handle" class="form-label">UPI Handle (@ybl, @paytm etc...)</label>
                                                                    <input type="text" class="form-control" name="upi_handle" id="upi_handle" placeholder="UPI" value="<?php echo $upi_handle;?>" <?php echo $bank_readonly; ?>>
                                                                </div>
                                                                    
                                                                <div class="mb-3 col-xl-6 col-md-4">
                                                                    <label for="doc_type" class="form-label">Bank Proof <span class="text-danger">*</span></label>
                                                                    <select class="select2 form-select" name="doc_type" id="doc_type" required <?php echo $bank_readonly; echo ($bank_needed==0)?" disabled":""; ?>>
                                                                        <option value="" hidden>Choose Document Type</option>
                                                                        <option value="cheque" <?php echo $bank_cheque_sel; ?>>Cancelled Cheque</option>
                                                                        <option value="passbook" <?php echo $bank_passbook_sel; ?>>Passbook</option>
                                                                    </select>
                                                                </div>
                                                                <?php
                                                                    if ($bank_needed == 1) {
                                                                        ?>
                                                                            <div class="mb-3 col-xl-6 col-md-8">
                                                                                <label for="file_bank_proof" class="form-label">Proof Photo <span class="text-danger">*jpg/jpeg/png format; 500KB max</span></label>
                                                                                <input type="file" name="file_bank" id="file_bank_proof" class="form-control" accept="image/*" required onchange="if(admin_access==0) {check_image_size_before_upload(this.id,500);}">
                                                                            </div>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                            <div class="mb-3 col-xl-6 col-md-8">
                                                                                <label for="file_bank_proof" class="form-label">Uploaded Photo</label>
                                                                                <!-- <span class='mt-2 badge bg-label-info show-pointer' > -->

                                                                                <input type="text" class="form-control bg-label-success show-pointer" data-bs-toggle='modal' data-bs-target='#ModalShowPhoto' data-src="<?php echo $bank_doc; ?>" data-from="bank" value="View Now" disabled>
                                                                            </div>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </div>
                                                            
                                                            <div class="row g-2">

                                                                <?php
                                                                    if ($photo_needed == 1) {
                                                                        ?>
                                                                            <div class="mb-3 col-md-12">
                                                                                <label for="file" class="form-label">Upload Photo <span class="text-danger">* (Upload Your Realtime Photo)</span></label>
                                                                                <!-- <input type="file" name="file" id="file_photo" class="form-control" accept="image/*" capture required onchange="loadImageFile(this.id)"> -->
                                                                                <input type="file" name="file" id="file_photo" class="form-control" accept="image/*" capture required onchange="loadImageFile(this.id)">
                                                                                <!-- <input type="file" name="file" id="file_photo" class="form-control" accept="image/*;capture=camera" required onchange="loadImageFile(this.id)"> -->
                                                                                <h5 class="text-bold text-danger"><?php echo $photo_error; ?></h5>
                                                                                <br>
                                                                                <input type="hidden" name="file_photo" id="upload_compress" type="file" value="" />
                                                                                <img src="#" id="upload_preview">
                                                                            </div>                                                
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                            <div class="mb-3 col-md-12 text-center">
                                                                                <label class="form-label">Uploaded Photo</label>
                                                                                <br>
                                                                                <img src="<?php echo $photo; ?>" style="width:200px;">
                                                                                <!-- <input type="text" class="form-control bg-label-success show-pointer" data-bs-toggle='modal' data-bs-target='#ModalShowPhoto' data-src="<?php echo $pan_doc; ?>" data-from="kyc" value="View Now" disabled> -->
                                                                            </div>
                                                                        <?php
                                                                        
                                                                    }
                                                                ?>
                                                                
                                                            </div>
                                                            <div class="text-center pb-5">
                                                                <?php
                                                                    if ($pan_update == 0 && $aadhaar_update == 0) {
                                                                        ?>
                                                                            <button type="submit" class="btn btn-success" id="btn_submit_document" name="submit_document">Save Changes</button>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            */
                                        ?>
                                    </div>
                                <!-- </div> -->
                            <!-- </div> -->
                        <!-- / Content -->
                    </div>
                </div>

                <?php include("footer.php"); ?>
            </div>
        </div>
    
    <script>
        personal_tab = document.getElementById("v-pills-personal-tab");
        kyc_tab = document.getElementById("v-pills-kyc-tab");
        banking_tab = document.getElementById("v-pills-banking-tab");
        profile_tab = document.getElementById("v-pills-profile-tab");
        
        personal = document.getElementById("v-pills-personal");
        kyc = document.getElementById("v-pills-kyc");
        banking = document.getElementById("v-pills-banking");
        profile = document.getElementById("v-pills-profile");

        function add_active (elem,class_name) {
            elem.classList.add(class_name);
        }

        function remove_active (elem,class_name) {
            elem.classList.remove(class_name);
        }
    </script>

    <?php
        if (isset($personal_detail_done) && $personal_detail_done) {
            ?>
                <script>
                    remove_active (personal_tab,"active");
                    add_active (kyc_tab,"active");
                    remove_active (banking_tab,"active");
                    
                    remove_active (personal,"show");
                    remove_active (personal,"active");

                    add_active (kyc,"show");
                    add_active (kyc,"active");

                    remove_active (banking,"show");
                    remove_active (banking,"active");
                </script>
            <?php
        } else if (isset($kyc_detail_done) && $kyc_detail_done) {
            ?>
                <script>
                    remove_active (personal_tab,"active");
                    remove_active (kyc_tab,"active");
                    add_active (banking_tab,"active");
                    
                    remove_active (personal,"show");
                    remove_active (personal,"active");

                    remove_active (kyc,"show");
                    remove_active (kyc,"active");

                    add_active (banking,"show");
                    add_active (banking,"active");
                </script>
            <?php
        } else if (isset($bank_detail_done) && $bank_detail_done) {
            ?>
                <script>
                    remove_active (personal_tab,"active");
                    remove_active (kyc_tab,"active");
                    remove_active (banking_tab,"active");
                    add_active (profile_tab,"active");
                    
                    remove_active (personal,"show");
                    remove_active (personal,"active");

                    remove_active (kyc,"show");
                    remove_active (kyc,"active");

                    remove_active (banking,"show");
                    remove_active (banking,"active");

                    add_active (profile,"show");
                    add_active (profile,"active");
                </script>
            <?php
        }

        
        /*if ($photo_needed == 1) {
            ?>
                <script>
                    add_active (personal_tab,"active");
                    remove_active (kyc_tab,"active");
                    remove_active (banking_tab,"active");
                    
                    add_active (personal,"show");
                    add_active (personal,"active");

                    remove_active (kyc,"show");
                    remove_active (kyc,"active");

                    remove_active (banking,"show");
                    remove_active (banking,"active");
                </script>
            <?php
        } else*/ if ($personal_needed == 1) {
            ?>
                <script>
                    add_active (personal_tab,"active");
                    remove_active (kyc_tab,"active");
                    remove_active (banking_tab,"active");
                    
                    add_active (personal,"show");
                    add_active (personal,"active");

                    remove_active (kyc,"show");
                    remove_active (kyc,"active");

                    remove_active (banking,"show");
                    remove_active (banking,"active");
                </script>
            <?php 
        } else if ($pan_needed == 1 || $aadhaar_needed == 1) {
            ?>
                <script>
                    remove_active (personal_tab,"active");
                    add_active (kyc_tab,"active");
                    remove_active (banking_tab,"active");
                    
                    remove_active (personal,"show");
                    remove_active (personal,"active");

                    add_active (kyc,"show");
                    add_active (kyc,"active");

                    remove_active (banking,"show");
                    remove_active (banking,"active");
                </script>
            <?php 
        } else if ($bank_needed == 1) {
            ?>
                <script>
                    remove_active (personal_tab,"active");
                    remove_active (kyc_tab,"active");
                    add_active (banking_tab,"active");
                    
                    remove_active (personal,"show");
                    remove_active (personal,"active");

                    remove_active (kyc,"show");
                    remove_active (kyc,"active");

                    add_active (banking,"show");
                    add_active (banking,"active");
                </script>
            <?php 
        }
    ?>

    <!-- BEGIN ModalShowPhoto -->
		<div class="modal fade" id="ModalShowPhoto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog- scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header gradient-amin">
                        <h3 class="modal-title text-white" id="modal_title_photo">Photo Uploaded</h3>
                        <?php
                            if ($pan_needed == 0 || $aadhaar_needed == 0 || $bank_needed == 0) {
                                    ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <?php
                            }
                        ?>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">
                            <div class="row align-items-center justify-content-center">
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
                            if ($pan_needed == 0 && $aadhaar_needed == 0 && $bank_needed == 0) {
                                ?>
                                    <button class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                <?php
                            }
                            
                            if ($bank_update == 1) {
                                ?>
                                    <!-- <button class="btn btn-primary waves-effect waves-light" id="buttonModalUploadBank" data-bs-target="#ModalUploadBank" data-bs-toggle="modal" data-bs-dismiss="modal">View Bank Details</button> -->
                                <?php
                            }
                            if ($pan_update == 1) {
                                ?>
                                    <!-- <button class="btn btn-label-danger waves-effect waves-light" id="buttonModalUploadDocument" data-bs-target="#ModalUploadDocument" data-bs-toggle="modal" data-bs-dismiss="modal">View KYC Details</button> -->
                                <?php
                            }
                        ?>
                        <!-- WORKING -->
                        <!-- <button class="btn btn-primary waves-effect waves-light" id="buttonSwitchModal" data-bs-target="#ModalUploadDocument" data-bs-toggle="modal" data-bs-dismiss="modal">Continue</button> -->
                        <!-- <button class="btn btn-primary waves-effect waves-light" onclick="javascript:location.reload();">Reload</button> -->
                    </div>
                </div>
            </div>
        </div>
	<!-- END ModalShowPhoto -->

    <?php include("scripts.php"); ?>

    <script>
        // DataTables with Column Search by Text Inputs
            document.addEventListener("DOMContentLoaded", function () {
                // Setup - add a text input to each footer cell
                $('#datatables tfoot th').each(function () {
                    // var title = $(this).text();
                    // $(this).html('<input type="text" class="form-control" placeholder="' + title + '" />');
                });
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
                // DataTables
            });
    </script>

    <script>
        function set_bank_attr() {
            bank_name_other = document.getElementById("bank_name_other");
            bank_name = document.getElementById("bank_name");
            
            if (bank_name_other.value.trim()=="") {
                bank_name.required = true;
            } else {
                $('#bank_name').val('').trigger('change');    // EMPTY THE SELECT ELEMENT
                bank_name.required = false;
            }
        }

        function set_bank_field() {
            bank_name_other = document.getElementById("bank_name_other");
            bank_name = document.getElementById("bank_name");
            
            if (bank_name.value!="") {
                bank_name_other.value = "";
            } else {
                bank_name.required = true;
            }
        }

        function check_image_size_before_upload(element_id,max_size) {
            var fileUpload = document.getElementById(element_id);
            if (typeof (fileUpload.files) != "undefined") {
                var size = parseFloat(fileUpload.files[0].size / 1024).toFixed(2);

                if (size > max_size) {
                    msg = max_size + " KB max size allowed.";
                    showNotif(msg, "error");
                    fileUpload.value = "";
                    return false;
                }
            } else {
                alert("This browser does not support HTML5.");
            }
        }
        
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#display_photo')
                        .attr('src', e.target.result)
                        .width(300)
                        .height(450);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        var btn_submit = document.getElementById("btn_submit_profile")
        var fileReader = new FileReader();
        var filterType = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;

        var imgSize = 0;

        fileReader.onload = function(event) {
            var image = new Image();

            image.onload = function() {
                var canvas = document.createElement("canvas");
                var context = canvas.getContext("2d");

                // Size More Than 5MB, Compress
                if (imgsize > 1024) {
                    // SIZE INCREASE IN SOME CASES
                    // canvas.width = (image.width > 2500) ? (image.width / 3) : image.width;
                    // canvas.height = (image.width > 2500) ? (image.height / 3) : image.height;

                    // let max_size = 2000;
                    let width = image.width
                    let height = image.height
                    let max_size = (width > 1000) ? ((width > 2000) ? ((width > 3000) ? ((width > 4000) ? (image.width * 0.10) : (image.width * 0.15)) : (image.width * 0.30)) : (image.width * 0.40)) : (500);
                    // Resizing the image and keeping its aspect ratio
                    if (width > height) {
                        if (width > max_size) {
                            height *= max_size / width
                            width = max_size
                        }
                    } else {
                        if (height > max_size) {
                            width *= max_size / height
                            height = max_size
                        }
                    }

                    canvas.width = width
                    canvas.height = height

                    context.drawImage(image,
                        0,
                        0,
                        image.width,
                        image.height,
                        0,
                        0,
                        canvas.width,
                        canvas.height
                    );

                    base64_string = canvas.toDataURL();                    
                    size_in_kb = get_image_size_base64(base64_string);

                    // alert(size_in_kb + " KB");
                    // var dataUrl = canvas.toDataURL('image/jpeg');
                    // var resizedImage = dataURLToBlob(dataUrl); // BLOB IMAGE TO UPLOAD

                    document.getElementById("upload_preview").src = canvas.toDataURL();
                    $('#upload_preview').width(200).height(200);
                    // Assign Image to Input Field
                    document.getElementById("upload_compress").value = canvas.toDataURL();
                    
                    btn_submit.disabled = false;
                    btn_submit.innerHTML = "Submit";

                    // document.getElementById("upload_preview").src = canvas.toBlob();

                    document.getElementById("file_photo").value = "";
                    document.getElementById('file_photo').required = false;
                } else {
                    canvas.width = image.width;
                    canvas.height = image.height;
                    document.getElementById("upload_preview").src = image.src;
                    $('#upload_preview').width(200).height(200);
                    // Assign Image to Input Field
                    document.getElementById("upload_compress").value = image.src;
                    btn_submit.disabled = false;
                    btn_submit.innerHTML = "Submit";
                    
                    document.getElementById("file_photo").value = "";
                    document.getElementById('file_photo').required = false;
                }
            }
            image.src = event.target.result;
        };

        /* Utility function to convert a canvas to a BLOB */
            var dataURLToBlob = function(dataURL) {
                var BASE64_MARKER = ';base64,';
                if (dataURL.indexOf(BASE64_MARKER) == -1) {
                    var parts = dataURL.split(',');
                    var contentType = parts[0].split(':')[1];
                    var raw = parts[1];

                    return new Blob([raw], {type: contentType});
                }

                var parts = dataURL.split(BASE64_MARKER);
                var contentType = parts[0].split(':')[1];
                var raw = window.atob(parts[1]);
                var rawLength = raw.length;

                var uInt8Array = new Uint8Array(rawLength);

                for (var i = 0; i < rawLength; ++i) {
                    uInt8Array[i] = raw.charCodeAt(i);
                }

                return new Blob([uInt8Array], {type: contentType});
            }
        /* End Utility function to convert a canvas to a BLOB      */

        var loadImageFile = function(element_id) {
            btn_submit.disabled = true;
            btn_submit.innerHTML = "Please Wait...";
            var uploadImage = document.getElementById(element_id);
            var imgpath = uploadImage;

            //check and retuns the length of uploaded file.
            if (uploadImage.files.length === 0) {
                return;
            }

            //Is Used for validate a valid file.
            var uploadFile = uploadImage.files[0];
            if (!filterType.test(uploadFile.type)) {
                alert("Please select a valid image.");
                return;
            }
            
            if (!imgpath.value == "") {
                var img = imgpath.files[0].size;
                imgsize = img / 1024;

                console.log(uploadFile);
                fileReader.readAsDataURL(uploadFile);
            }
        }

        function get_image_size_base64(base64_string) {
            // var src ="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP/// yH5BAEAAAAALAAAAAABAAEAAAIBRAA7";

            var base64str = base64_string.substr(22);
            var decoded = atob(base64str);
            size_in_kb = (decoded.length/1024).toFixed(2);

            return size_in_kb;
        }
    </script>

    <script>
        // PASS DATA TO MODAL POPUP
            $(function () {
                $('#ModalPay').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var payment = button.data('payment'); // Extract info from data-* attributes
                    var beneficiary = button.data('beneficiary'); // Extract info from data-* attributes
                    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
					// package = "Package "+package;
					// payment = "20% Amount of "+package+"= Rs."+payment;
                    var modal = $(this);
                    modal.find('#transaction_amount').val(payment);
                    modal.find('#to_user_id').val(beneficiary);
                });
            });
        // PASS DATA TO MODAL POPUP

		// PASS DATA TO MODAL POPUP
			$(function () {
                $('#ModalUploadDocument').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var doc_type = button.data('doc-type'); // Extract info from data-* attributes
                    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
					// package = "Package "+package;
					// payment = "20% Amount of "+package+"= Rs."+payment;
                    var modal = $(this);
                    modal.find('#type_document').val(doc_type);
                    modal.find('#modal_title_add').html("Add "+doc_type);
                    modal.find('#document_title').html(doc_type);
                    modal.find('#txt_file_upload').html("Upload "+doc_type+" Copy ");
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

    <script>
        set_menu_active(<?php echo $position; ?>);
    </script>

	<?php
		if($msg!=""){
			?>
				<script>
					showNotif("<?php echo $msg;?>", "<?php echo $msg_type;?>")
				</script>
			<?php
			exit;
		}
	?>
</body>

</html>
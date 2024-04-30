<!DOCTYPE html>
<html lang="en">

<?php
	// COLLEGE DASHBOARD PAGE
	ob_start();
	require_once("../db_connect.php");
    require_once("qrcode.php");
	session_start();

	mysqli_query($conn, "set names 'utf8'");  //-------WORKING UTF8 CODE------//

	//-------CURRENT DATE AND TIME TO FEED---------//
	date_default_timezone_set('Asia/Kolkata');
	$current_date = date('Y-m-d H:i:s');

	extract($_REQUEST);

    $file_dir_admindocs= "../assets/files/admindocs";
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

    // CHECK AVAILABILITY
        $position = 7;
            
        $check_type = "";

        $header_text = "Investments Done By - $name ($user_id)";
    // CHECK AVAILABILITY

	// DEFAULT
		$page_id_home = 1;
		$bank_update = $photo_update = $pan_update = $aadhaar_update = 0;
        $url_dir = "../assets/files/transaction";
        
        $msg = $msg_type = "";
		$error = false;
	// DEFAULT

    // ADD TRANSACTION
        if (isset($_POST['submit'])) {
            /*****IMAGE UPLOAD*****/
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
                        $msg_type = "error";
                    }

                    // Verify file size - 500kB maximum
                    $minsize = 1 * 1024;
                    $maxsize = 500 * 1024;

                    if ($filesize > $maxsize || $filesize < $minsize) {
                        $error = true;
                        $msg .= " >> Error!!! File size should not be greater than 500kb.";
                        $msg_type = "error";
                    }

                    //----------IMAGE IS JPEG/JPG AND NO ERROR-----------//
                    if (!$error) {
                        // Check whether file exists before uploading it
                        // if(file_exists("../collegeportal/images/files/$batch" . $filename))
                        // {
                        //     $error_message = "$reg_no already registered!!!";
                        // } 
                        // else 
                        $uploadedfile = $_FILES["file"]["tmp_name"];

                        $current_timestamp = time();
                        // $filename = $current_timestamp.".$ext";
                        // $filename = "$file_basename-$current_timestamp".".$ext";
                        $filename = "$user_id-$to_user_id-$current_timestamp".".$ext";
                        
                        // FOR SELECT QUERIES
                            $check_txn = $for_txn = $query_update_status = "";

                            $for_txn = "Investment";

                            $query_insert_transaction = 
                                "INSERT INTO `fund_transaction` (`transaction_type`, `user_id`, `to_user_id`, `transaction_mode`, `transaction_date`, `transaction_amount`, `transaction_id`, `url_file`, `create_date`) 
                                VALUES ('$transaction_type_txn', '$user_id', '$to_user_id', '$transaction_mode', '$transaction_date', '$transaction_amount', '$transaction_id', '$filename', '$current_date')";
                            if (mysqli_query($conn, $query_insert_transaction)) {
                                $file_dir = "$url_dir/$filename";

                                // UPLOAD FILE
                                if ((move_uploaded_file($_FILES['file']['tmp_name'], $file_dir))) {
                                    $msg = "$for_txn Added Successfully! Wait For Admin Approval";
                                    $msg = mysqli_real_escape_string($conn,$msg);
                                    $msg_type = "success";
                                    insert_notif($user_id,$msg);
                                    
                                    // echo "<script>window.close();</script>";
                                }
                                stop_form_resubmit();
                            } else {
                                $msg .= " >> Error in Inserting The Record...Try Again";
                                $msg_type = "error";
                            }
                        // correctImageOrientation($file_dir);
                    } else {
                        $msg .= " >> There was a problem uploading your record. Please try again.";
                        $msg_type = "error";
                    }
                } else {
                    //ERROR IN fileS
                    $msg .= " >> Error in file " . $_FILES["file"]["error"];
                    $msg_type = "error";
                }
            /*****IMAGE UPLOAD*****/
        }
    // ADD TRANSACTION
    
    // GET DATA
        $query = "SELECT * FROM `fund_transaction` WHERE `user_id`='$user_id' ORDER BY `create_date` DESC";
        $res = mysqli_query($conn,$query);
        $transactions = mysqli_fetch_all($res,MYSQLI_ASSOC);

        // print_r($transactions);
        // die();
    // GET DATA

    // GET USER DATA
        $query_userdata = "SELECT `sponsor_id`, `user_id`, `name`, `mobile`, `email`, `status` AS 'user_status', `create_date` FROM `users` WHERE `user_id`='$user_id'";
        $queryuserdresult = mysqli_query($conn, $query_userdata);
        $resuserdresult = mysqli_fetch_array($queryuserdresult);
        extract($resuserdresult);
    // GET USER DATA

     // GET Admin Bank DATA
        $query_adminbankinfo = "SELECT `id`,`bank_name`, `branch_name`, `branch_address`, `account_no`, `ifs_code`, `upi_handle`,`upi_qrcode` FROM `users_admin` WHERE `user_id`='max_admin'";
        $queryadminbankinfo = mysqli_query($conn, $query_adminbankinfo);        
        $resadminbankinfo = mysqli_fetch_array($queryadminbankinfo);
        extract($resadminbankinfo);
    // GET Admin Bank DATA

    // DEFAULT ORDER COLUMN SR
		$default_Order = 0;

    // FUNCTIONS
        function fetch_sponsor_name($conn, $sponsor_id) {
            $query_sp_name = mysqli_query($conn,"SELECT `name` FROM `users` WHERE `user_id`='$sponsor_id'");
            if($rw = mysqli_fetch_array($query_sp_name)) {
                $name = $rw['name'];
            }
            return $name;
        }
    // FUNCTIONS

    if ($sponsor_id != "") {
        $sponsor_name = fetch_sponsor_name($conn, $sponsor_id);
    } else {
        $sponsor_name = $name;
        $sponsor_id = $user_id;
    }
?>

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
                                        <div class="card px-1">
                                            <div class="card-header border-bottom ps-1 pb-2 mb-3">
                                                <h5 class="card-title mb-1 text-bold"><?php echo $header_text; ?></h5>
                                                <a class='mt-3 btn btn-primary btn-sm text-white show-pointer' data-bs-toggle='modal' data-bs-target='#ModalPay' data-payment='' data-beneficiary='COMPANY' data-transaction_type="fresh" id="btn_pay">
                                                    <i class="icofont icofont-ui-add"></i> Invest Now
                                                </a>
                                            </div>
                                            <div class="card-datatable table-responsive">
                                                <table id="datatables" class="datatables table border-top table-strriped table-info table-hover border-primary">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr</th>
                                                            <th class="d-none">User ID</th>
                                                            <th>Transaction Type</th>
                                                            <th>Amount</th>
                                                            <th>Transaction Date</th>
                                                            <th>Status</th>
                                                            <th>Update Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $i = 0;
                                                            foreach ($transactions as $item) {
                                                                $i++;

                                                                $txn_type = $item['transaction_type'];
                                                                $user_id = $item['user_id'];
                                                                $to_user_id = $item['to_user_id'];
                                                                $transaction_mode = $item['transaction_mode'];
                                                                $transaction_date = $item['transaction_date'];
                                                                $transaction_amount = $item['transaction_amount'];
                                                                $status = $item['status'];
                                                                $transaction_id = $item['transaction_id'];
                                                                $url_file = $item['url_file'];
                                                                $comment = $item['comment'];
                                                                $updated_by = $item['updated_by'];
                                                                $create_date = $item['create_date'];
                                                                $update_date = $item['update_date'];

                                                                //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT
                                                                    $regEx = '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/';
                                                                    // preg_match($regEx, $details['date'], $result);
                                                                    if (preg_match($regEx, $transaction_date) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $transaction_date)) {
                                                                        $transaction_date = date_create($transaction_date);
                                                                        $transaction_date = date_format($transaction_date, "d M Y");
                                                                    }
                                                                //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT

                                                                //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT
                                                                    $regEx = '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/';
                                                                    // preg_match($regEx, $details['date'], $result);
                                                                    if (preg_match($regEx, $create_date) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $create_date)) {
                                                                        $date = date_create($create_date);
                                                                        $create_date = date_format($date, "d-M-Y h:iA");
                                                                        $create_date = date_format($date, "d-M-Y");
                                                                    }
                                                                //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT

                                                                //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT
                                                                    $regEx = '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/';
                                                                    // preg_match($regEx, $details['date'], $result);
                                                                    if (preg_match($regEx, $update_date) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $update_date)) {
                                                                        $date = date_create($update_date);
                                                                        $update_date = date_format($date, "d-M-Y h:iA");
                                                                        $update_date = date_format($date, "d-M-Y");
                                                                    } else {
                                                                    }
                                                                //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT

                                                                switch ($txn_type) {
                                                                    case 'fresh':
                                                                        $transaction_type = "<span class='badge bg-info shadow rounded-pill' style='font-size:11px;'>User Investment</span>";
                                                                        break;
                                                                        
                                                                    case 'withdraw':
                                                                        $transaction_type = "<span class='badge bg-danger shadow rounded-pill' style='font-size:11px;'>SuperWallet Transfer</span>";
                                                                        $transaction_date = $update_date = $create_date;
                                                                        break;
                                                                        
                                                                    case 'superwallet':
                                                                        $transaction_type = "<span class='badge bg-warning shadow rounded-pill' style='font-size:11px;'>SuperWallet Investment</span>";
                                                                        $transaction_date = $update_date = $create_date;
                                                                        break;

                                                                    case 'admin_credit':
                                                                        $transaction_type = "<span class='badge bg-success shadow rounded-pill' style='font-size:11px;'>Admin Added</span>";
                                                                        $transaction_date = $update_date = $create_date;
                                                                        break;

                                                                    case 'admin_debit':
                                                                        $transaction_type = "<span class='badge bg-danger shadow rounded-pill' style='font-size:11px;'>Admin Deducted</span>";
                                                                        $transaction_date = $update_date = $create_date;
                                                                        break;
                                                                }
                                                             
                                                                switch ($status) {

                                                                    
                                                                    // case 'pending':
                                                                    case '2':
                                                                        $update_date = $transaction_status = "<span class='badge bg-warning shadow rounded'>PENDING</span>";
                                                                        break;
                                                                    
                                                                    // case 'approved':
                                                                    case '1':
                                                                        // echo $status;
                                                                        $transaction_status = "<span class='badge bg-success shadow rounded'>ACTIVE</span>";
                                                                        break;
                                                                    
                                                                    // case 'rejected':
                                                                    case '0':
                                                                        // $transaction_status = "<span class='badge bg-danger rounded show-pointer' title='$comment'><i class='icofont icofont-info-circle f-16'></i>REJECTED</span>";
                                                                        $transaction_status = '<span class="badge bg-danger shadow rounded show-pointer" type="button" data-bs-trigger="hover"
                                                                            data-container="body" data-bs-toggle="popover" data-bs-placement="bottom" title="Rejection Reason"
                                                                            data-offset="-20px -20px"
                                                                            data-bs-content="'.$comment.'">
                                                                            <i class="icofont icofont-info-circle f-16"></i>REJECTED
                                                                            </span>';
                                                                        break;
                                                                        case '3':

                                                                            
                                                                        // $transaction_status = "<span class='badge bg-danger rounded show-pointer' title='$comment'><i class='icofont icofont-info-circle f-16'></i>REJECTED</span>";
                                                                        $transaction_status = '<span class="badge bg-danger shadow rounded show-pointer" type="button" data-bs-trigger="hover"
                                                                            data-container="body" data-bs-toggle="popover" data-bs-placement="bottom" title="Rejection Reason"
                                                                            data-offset="-20px -20px"
                                                                            data-bs-content="'.$comment.'">
                                                                            <i class="icofont icofont-info-circle f-16"></i>DEACTIVATED
                                                                            </span>';
                                                                          
                                                                            break;
                                                                }
                                                                
                                                                ?>
                                                                  
                                                                  <?php if (strpos($transaction_status, 'DEACTIVATED') !== false): ?>
<tr style="display: none;">
    <td><?php echo $i; ?></td>
    <td class="d-none"><?php echo $user_id; ?></td>
    <td class="text-center"><?php echo $transaction_type; ?></td>
    <td>₹ <?php echo number_format($transaction_amount, 2); ?></td>
    <td><?php echo $transaction_date; ?></td>
    <td><?php echo $transaction_status; ?></td>
    <td><?php echo $update_date; ?></td>
</tr>
<?php else: ?>
<tr>
    <td><?php echo $i; ?></td>
    <td class="d-none"><?php echo $user_id; ?></td>
    <td class="text-center"><?php echo $transaction_type; ?></td>
    <td>₹ <?php echo number_format($transaction_amount, 2); ?></td>
    <td><?php echo $transaction_date; ?></td>
    <td><?php echo $transaction_status; ?></td>
    <td><?php echo $update_date; ?></td>
</tr>
<?php endif; ?>

                                                                <?php
                                                            }
                                                        ?>                                                    
                                                    </tbody>
                                                    <tfoot class="d-none">
                                                        <tr>
                                                            <th>Sr</th>
                                                            <th>User ID</th>
                                                            <th>Transaction Type</th>
                                                            <th>Amount</th>
                                                            <th>Transaction Date</th>
                                                            <th>Status</th>
                                                            <th>Txn Date</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <!-- </div> -->
                            <!-- </div> -->
                        <!-- / Content -->
                    </div>
                </div>

                <?php include("footer.php"); ?>
            </div>
        </div>
    
    <!-- BEGIN ModalPay -->
		<div class="modal fade" id="ModalPay" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">Add Transaction Details</h3>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p class="mb-0">
						<form method="POST" action="" id="form_add_record" enctype="multipart/form-data">
                            <input type="hidden" name="transaction_type_txn" id="transaction_type_txn">
							<div class="row justify-content-center align-items-center">
								<!-- <div class="mb-3 col-md-12">
                                    <label for="company_account" class="form-label">Pay To <b class="text-danger">UPI ID: MTCLUB@SBI</b> or Scan QR Code given in Your Dashboard</label>
								</div> -->
								<div class="mb-3 col-md-6">
									<label for="user_id" class="form-label">User ID</label>
									<input type="text" class="form-control text-readonly" value="<?php echo "$user_id-$name"; ?>" readonly>
									<input type="hidden" value="<?php echo $user_id; ?>" name="user_id" id="user_id">
								</div>
								<div class="mb-3 col-md-6">
									<label for="sponsor_id" class="form-label">Sponsor</label>
									<input type="text" class="form-control text-readonly" value="<?php echo "$sponsor_id-$sponsor_name"; ?>" readonly>
									<input type="hidden" value="<?php echo $sponsor_id; ?>" name="sponsor_id">
								</div>
								<div class="mb-3 col-md-6">
									<label for="to_user_id" class="form-label">Transfer To</label>
									<input type="text" class="form-control text-readonly" name="to_user_id" id="to_user_id" readonly>
								</div>
								<div class="mb-3 col-md-6">
									<label for="transaction_amount" class="form-label">Amount To Invest <span class="text-danger">*</span></label>
									<input type="number" class="form-control" name="transaction_amount" id="transaction_amount" min="1000" step="1000" placeholder="Enter Amount of Investment" required>
								</div>
								<div class="mb-3 col-md-6">
									<label for="transaction_mode" class="form-label">Mode of Payment <span class="text-danger">*</span></label>
									<select name="transaction_mode" id="transaction_mode" class="form-control" required>
										<option value="" hidden>Select Mode</option>
										<optgroup label="Online Mode (NEFT/UPI/Online Bank Transfer)">
											<option value="1">Online Mode</option>
										</optgroup>
										<optgroup label="Offline Mode (Bank Branch Transfer/Money Order)">
											<option value="0">Offline Mode</option>
										</optgroup>
									</select>
								</div>
								<div class="mb-3 col-md-6">
									<label for="transaction_date" class="form-label">Transaction Date <span class="text-danger">*</span></label>
									<input type="date" class="form-control" name="transaction_date" id="transaction_date" required>
								</div>
								<div class="mb-3 col-md-12">
									<label for="transaction_id" class="form-label">Transaction ID <span class="text-primary">(Enter <b>CASH</b> for Offline Transaction)</span></label>
									<input type="text" class="form-control" name="transaction_id" id="transaction_id" placeholder="Transaction ID" required>
								</div>
								<div class="mb-3 col-md-12">
									<label for="file" class="form-label">Receipt of Payment (Offline Slip/ Online Payment Screenshot) <span class="text-danger">*jpg/jpeg/png format; 100KB max size</span></label>
									<input type="file" name="file" id="file" class="form-control" accept="image/*" required onchange="check_image_size_before_upload(this.id,100);">
								</div>
                                <div class="mb-3 col-md-6">
									<label for="bankdetails" class="form-label">Bank Details <span class="text-primary">(For NEFT/RTGS/IMPS)</span></label>
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label for="accountno" class="form-label">Account No</label>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="accountno"><?php echo "$account_no"; ?></label>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="ifsccode" class="form-label">IFSC Code</label>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="ifsccode"><?php echo "$ifs_code"; ?></label>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="bankname" class="form-label">Bank Name</label>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="bankname"><?php echo "$bank_name"; ?></label>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="branchname" class="form-label">Branch Name</label>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="branchname"><?php echo "$branch_name"; ?></label>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="bankaddress" class="form-label">Branch Address</label>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="bankaddress"><?php echo "$branch_address"; ?></label>
                                        </div>
                                    </div>
								</div>
								<div class="mb-3 col-md-6">
									<label for="upi" class="form-label">UPI QR Code <span class="text-primary">(For Paytm, PhonePe, Gpay, BharatPe etc.)</span></label>
                                    <div class="row">
                                    <div class="mb-3 col-md-6">
                                            <label for="bankaddress" class="form-label">UPI ID</label>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="bankaddress"><?php echo "$upi_handle"; ?></label>
                                        </div>
                                        <div class="mb-3 col-md-12" style="text-align:center;">
                                            <label for="upiqrcode">
                                                <img src="<?php echo $file_dir_admindocs."/".$upi_qrcode?>" style="width:80%"/>
                                                <?php 
                                                    // $qr = new QRCode();
                                                    // $qr->setErrorCorrectLevel(QR_ERROR_CORRECT_LEVEL_L);
                                                    // $qr->setTypeNumber(20);
                                                    // $qr->addData("upi://pay?pa=$upi_handle&pn=Maxizone");
                                                    // $qr->make();
                                                    // $qr->printHTML();
                                                ?>
                                            </label>
                                        </div>
                                    </div>
								</div>
							</div>
							<div style="float:right;">
								<button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success" id="btn_submit" name="submit">Submit</button>
							</div>
						</form>
						</p>
					</div>
					<!-- <div class="modal-footer">
						<button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-success">Save changes</button>
					</div> -->
				</div>
			</div>
		</div>
	<!-- END ModalPay -->

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
                                                    <label for="file_pan" class="form-label">Upload PAN <span class="text-danger">*jpg/jpeg/png format; 100KB max</span></label>
                                                    <input type="file" name="file_pan" id="file_pan" class="form-control" accept="image/*" required onchange="check_image_size_before_upload(this.id,100);">
                                                </div>

                                            <?php
                                        } else {
                                            ?>
                                                <div class="mb-3 col-xl-6 col-md-7">
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
                                                    <input type="file" name="file_aadhaar_front" id="file_aadhaar_front" class="form-control" accept="image/*" required onchange="check_image_size_before_upload(this.id,100);">
                                                </div>
                                                <div class="mb-3 col-xl-4">
                                                    <label for="file_aadhaar_back" class="form-label">Aadhaar Back<span class="text-danger">*jpg/jpeg/png format; 100KB max</span></label>
                                                    <input type="file" name="file_aadhaar_back" id="file_aadhaar_back" class="form-control" accept="image/*" required onchange="check_image_size_before_upload(this.id,100);">
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
							<div style="float:right;">
                                <?php
                                    if ($pan_update == 0 && $aadhaar_update == 0) {
                                        ?>
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
                            if ($bank_update == 1) {
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
                                    <label for="bank_name" class="text-bold mt-1">Banking info</label>
                                    
                                    <div class="mb-3 col-xl-7">
                                        <label for="bank_name" class="form-label">Bank Name <span class="text-danger">*</span></label>
                                        <select class="select2 form-select" data-allow-clear="true" name="bank_name" id="bank_name" required <?php echo $bank_disabled; ?>>
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

                                    <div class="mb-3 col-xl-5">
                                        <label for="bank_name_other" class="form-label">Other Bank (Specify Here)</label>
                                        <input type="text" class="form-control" name="bank_name_other" id="bank_name_other" placeholder="In case of other Bank, specify here" value="<?php echo $bank_name;?>" onblur="set_branch_attr();" <?php echo $bank_disabled; ?>>
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
                                        <label for="bank_name" class="form-label">Bank Proof <span class="text-danger">*</span></label>
                                        <select class="select2 form-select" data-allow-clear="true" name="doc_type" id="doc_type" required <?php echo $bank_disabled; ?>>
                                            <option value="" hidden>Choose Document Type</option>
                                            <option value="cheque" <?php echo $bank_cheque_sel; ?>>Cancelled Cheque</option>
                                            <option value="passbook" <?php echo $bank_passbook_sel; ?>>Passbook</option>
                                        </select>
                                    </div>
                                    <?php
                                        if ($bank_update == 0) {
                                            ?>
                                                <div class="mb-3 col-xl-6 col-md-8">
                                                    <label for="file_bank_proof" class="form-label">Proof Photo <span class="text-danger">*jpg/jpeg/png format; 100KB max</span></label>
                                                    <input type="file" name="file" id="file_bank_proof" class="form-control" accept="image/*" required onchange="check_image_size_before_upload(this.id,100);">
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
							</div>
							<div style="float:right;">
                                <?php
                                    if ($bank_update == 0) {
                                        ?>
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
                            if ($photo_update == 1) {
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

                                    <?php
                                        if ($photo_update == 0) {
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
                                                    <!-- <input type="text" class="form-control bg-label-success show-pointer" data-bs-toggle='modal' data-bs-target='#ModalShowPhoto' data-src="<?php echo $pan_doc; ?>" data-from="kyc" value="View Now" disabled> -->
                                                </div>
                                            <?php
                                            
                                        }
                                    ?>
                                    
                                </div>

                                
							</div>
							<div style="float:right;">
                                <?php
                                    if ($photo_update == 0) {
                                        ?>
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
                            if ($pan_update == 1 && $aadhaar_update == 1 && $bank_update == 1) {
                                ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <?php
                            }
                        ?>
					</div>
					<div class="modal-body">
						<p class="mb-0">
							<div class="row align-items-center">
                                <div class="col-md-6 text-center" id="photo1_container">
                                    <img src="<?php echo $bank_doc; ?>" id="photo1" style="width:100%;">
                                </div>
                                <div class="col-md-6 text-center" id="photo2_container" style="display:none;">
                                    <img src="<?php echo $bank_doc; ?>" id="photo2" style="width:100%;">
                                </div>
                            </div>
                        </p>
					</div>
					<div class="modal-footer d-flex justify-content-center">
                        <?php
                            if ($pan_update == 1 && $aadhaar_update == 1 && $bank_update == 1) {
                                ?>
                                    <button class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                <?php
                            }
                            
                            if ($bank_update == 1) {
                                ?>
						            <button class="btn btn-primary waves-effect waves-light" id="buttonModalUploadBank" data-bs-target="#ModalUploadBank" data-bs-toggle="modal" data-bs-dismiss="modal">View Bank Details</button>
                                <?php
                            }
                            if ($pan_update == 1) {
                                ?>
                                    <button class="btn btn-label-danger waves-effect waves-light" id="buttonModalUploadDocument" data-bs-target="#ModalUploadDocument" data-bs-toggle="modal" data-bs-dismiss="modal">View KYC Details</button>
                                <?php
                            }
                        ?>
                        
					</div>
				</div>
			</div>
		</div>
	<!-- END ModalShowPhoto -->

    <?php include("scripts.php"); ?>

    <script>
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

        var btn_submit = document.getElementById("btn_submit_photo")
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
                    $('#upload_preview').width(200).height(300);
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
                    $('#upload_preview').width(200).height(300);
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
                        }],

                        initComplete: function (settings, json) {
                            let select = document.createElement('select');
                            select.setAttribute("id", "levelddl");
                            select.add(new Option('ACTIVE'));
                            select.add(new Option('DEACTIVATED'));
                            select.add(new Option('All'));
                           
                            let label = document.createElement('label');
                            let level = document.createTextNode("Status:");                            
                            label.setAttribute("for", "Status");                           
                            label.setAttribute("class", "memberlevel");
                            label.appendChild(level);
                            label.appendChild(select);
                            $('#datatables_length').append(label);
                            $('#levelddl').on('change', function () {
                            if ($(this).val() === "DEACTIVATED") {
                                // Search for any occurrence of "DEACTIVATED"
                                table.columns(5).search("DEACTIVATED", true, false).draw();
                            } else if ($(this).val() === "ACTIVE") {
                                // Search for "ACTIVE"
                                table.columns(5).search("^ACTIVE$", true, false).draw();
                            } else {
                                // Clear the search
                                table.columns(5).search('').draw();
                            }
                        }); 






                        }

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
        // PASS DATA TO MODAL POPUP
            $(function () {
                $('#ModalPay').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var payment = button.data('payment'); // Extract info from data-* attributes
                    var beneficiary = button.data('beneficiary'); // Extract info from data-* attributes
                    var transaction_type = button.data('transaction_type'); // Extract info from data-* attributes
                    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
					// package = "Package "+package;
					// payment = "20% Amount of "+package+"= Rs."+payment;
                    var modal = $(this);
                    modal.find('#transaction_type_txn').val(transaction_type);
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


                    // return false;
                    document.getElementById('buttonModalUploadBank').style.display = "none";
                    document.getElementById('buttonModalUploadDocument').style.display = "none";
                    
                    if (from == "bank") {
                        document.getElementById('buttonModalUploadBank').style.display = "block";
                        title = "Uploaded Bank Proof";
                    }

                    if (from == "kyc") {
                        document.getElementById('buttonModalUploadDocument').style.display = "block";
                        title = "Uploaded PAN";
                    }

                    // document.getElementById('buttonModalUploadDocument'));
                    
                    if(src1 !== undefined) {
                        title = "Uploaded Aadhaar";
                        document.getElementById('photo2_container').style.display = "block";
                        modal.find('#photo2').src = src1;
                    } else {
                        document.getElementById('photo2_container').style.display = "none";
                    }

                    modal.find('#modal_title_photo').html(title);
                    modal.find('#photo1').src = src;
                    

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
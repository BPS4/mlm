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

    // CHECK AVAILABILITY
        if (isset($_GET['type'])) {
            $type = json_decode(base64_decode($_GET['type']));    //--DECODE THE SECRET CODE PASSED--//
            $type_encode = base64_encode(json_encode($type));     //ENCODE

            switch ($type) {
                case 'lc':
                    $type = "level_commission";
                    $type_text = "LEVEL COMMISSION";
                    $position = 8;
                    $check_type = "`transaction_type`='$type' AND ";
                    break;

                case 'sc':
                    $type = "sponsor_commission";
                    $type_text = "SPONSOR COMMISSION";
                    $position = 8;
                    $check_type = "`transaction_type`='$type' AND ";
                    break;
                
                case 'lc_sc':
                    $type1 = "sponsor_commission";
                    $type2 = "level_commission";
                    $type_text = "COMMISSIONS";
                    $position = 8;
                    $check_type = "`transaction_type` IN ('sponsor_commission','level_commission') AND ";
                    break;
                
                case 'roi':
                    $type_text = "ROI on Investment";
                    $position = 11;
                    $check_type = "`transaction_type`='$type' AND ";
                    break;
            }

            $header_text = "Summary For $type_text";

        } else {
            $position = 7;
            
            $check_type = "`transaction_type` IN ('sponsor_commission','level_commission','roi') AND ";

            $header_text = "Transaction Summary For All Income";
        }
    // CHECK AVAILABILITY

    // $check_type = "";
    // $header_text = "Transaction Summary For All Income";
            
	// DEFAULT
		$page_id_home = 1;
		$bank_update = $photo_update = $pan_update = $aadhaar_update = 0;
        $msg = $msg_type = "";
		$error = false;
	// DEFAULT

    // GET DATA
        $query = "SELECT `transaction_type`, `level`, `user_id`, IF(`transaction_type`='roi',`user_id`,`from_user_id`) AS 'from_user_id', `transaction_amount`, `create_date` FROM `wallet_transaction` WHERE $check_type `user_id`='$user_id' AND `status`=1 ORDER BY `create_date` DESC";
        $res = mysqli_query($conn,$query);
        $transactions = mysqli_fetch_all($res,MYSQLI_ASSOC);
    // GET DATA

    // DEFAULT ORDER COLUMN SR
		$default_Order = 0;
        
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
                                            </div>
                                            <div class="card-datatable table-responsive">
                                                <table id="datatables" class="datatables table border-top table-strriped table-hover border-danger table-warning">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr</th>
                                                            <!-- <th>User ID</th> -->
                                                            <th>Transfer From</th>
                                                            <th>Member Name</th>
                                                            <th>Transaction Type</th>
                                                            <th>Amount</th>
                                                            <th>Transaction Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $i = 0;
                                                            foreach ($transactions as $item) {
                                                                $i++;
                                                                $txn_type = $item['transaction_type'];
                                                                $level = $item['level'];
                                                                $user_id = $item['user_id'];
                                                                $from_user_id = $item['from_user_id'];
                                                                $transaction_amount = $item['transaction_amount'];
                                                                $create_date = $item['create_date'];
                                                                
                                                                //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT
                                                                    $regEx = '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/';
                                                                    // preg_match($regEx, $details['date'], $result);
                                                                    if (preg_match($regEx, $create_date) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $create_date)) {
                                                                        $date = date_create($create_date);
                                                                        $create_date = date_format($date, "d-M-Y h:iA");
                                                                        $create_date = date_format($date, "d-M-Y");
                                                                    }
                                                                //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT                                                                

                                                                $id_check = "";
                                                                // if ($txn_type != "salary") {
                                                                //     $id_check = "`user_id_up`='$user_id' AND";

                                                                //     $query = "SELECT `level`,`users`.`name` AS 'member_name' FROM `levels` LEFT JOIN `users` ON `users`.`user_id`=`levels`.`user_id` WHERE $id_check `levels`.`user_id`='$from_user_id';";
                                                                //     $res = mysqli_query($conn,$query);
                                                                //     $res = mysqli_fetch_array($res);
                                                                //     extract($res);

                                                                // } else 
                                                                // if ($txn_type != "roi")
                                                                {
                                                                    $query = "SELECT `users`.`name` AS 'member_name' FROM `users` WHERE `users`.`user_id`='$from_user_id'";
                                                                    $res = mysqli_query($conn,$query);
                                                                    $res = mysqli_fetch_array($res);
                                                                    extract($res);
                                                                }

                                                                switch ($txn_type) {
                                                                    case 'level_commission':
                                                                        $transaction_type = "<span class='badge bg-warning rounded'>LEVEL INCOME For Lvl $level</span>";
                                                                        break;
                                                    
                                                                    case 'sponsor_commission':
                                                                        $transaction_type = "<span class='badge bg-success rounded'>SPONSOR INCOME</span>";
                                                                        break;
                                                                    
                                                                    case 'roi':
                                                                        $transaction_type = "<span class='badge bg-info rounded'>ROI Income</span>";
                                                                        break;
                                                                }

                                                                ?>
                                                                    <tr>
                                                                        <td><?php echo $i; ?></td>
                                                                        <!-- <td><?php echo $user_id; ?></td> -->
                                                                        <td><?php echo $from_user_id; ?></td>
                                                                        <td class="no-wrap"><?php echo $member_name; ?></td>
                                                                        <td><?php echo $transaction_type; ?></td>
                                                                        <td>₹ <?php echo number_format($transaction_amount,2); ?></td>
                                                                        <td><?php echo $create_date; ?></td>
                                                                    </tr>
                                                                <?php
                                                            }
                                                        ?>                                                    
                                                    </tbody>
                                                    <tfoot class="d-none">
                                                        <tr>
                                                            <th>Sr</th>
                                                            <!-- <th>User ID</th> -->
                                                            <th>Transfer From</th>
                                                            <th>Member Name</th>
                                                            <th>Transaction Type</th>
                                                            <th>Amount</th>
                                                            <th>Transaction Date</th>
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
		<div class="modal fade" id="ModalPay" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">Add Record</h3>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p class="mb-0">
						<form method="POST" action="" id="form_add_record" enctype="multipart/form-data">
							<input type="hidden" value="<?php echo $user_rank; ?>" name="user_rank" id="user_rank">
							<div class="row align-items-center">
								<div class="mb-3 col-md-12">
									<label for="company_account" class="form-label">COMPANY ACCOUNT DETAILS SHOWN HERE (AC NO QR AND UPI)</label>
								</div>
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
									<label for="transaction_amount" class="form-label">Amount Transfer</label>
									<input type="text" class="form-control text-readonly" name="transaction_amount" id="transaction_amount" readonly>
								</div>
								<div class="mb-3 col-md-6">
									<label for="to_user_id" class="form-label">Transfer To</label>
									<input type="text" class="form-control text-readonly" name="to_user_id" id="to_user_id" readonly>
								</div>
								<div class="mb-3 col-md-6">
									<label for="transaction_mode" class="form-label">Mode of Payment</label>
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
									<label for="transaction_date" class="form-label">Transaction Date</label>
									<input type="date" class="form-control" name="transaction_date" id="transaction_date" required>
								</div>
								<div class="mb-3 col-md-6">
									<label for="transaction_id" class="form-label">Transaction ID</label>
									<input type="text" class="form-control" name="transaction_id" id="transaction_id" placeholder="Transaction ID" required>
								</div>
								<div class="mb-3 col-md-12">
									<label for="file" class="form-label">Receipt of Payment (Offline Slip/ Online Payment Screenshot) <span class="text-danger">*jpg/jpeg/png format; 100KB max size</span></label>
									<input type="file" name="file" id="file" class="form-control" required>
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
                                                    <input type="file" name="file_pan" id="file_pan" class="form-control" accept="image/*" required onchange="check_size(this.id,100);">
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
                                                    <input type="file" name="file_aadhaar_front" id="file_aadhaar_front" class="form-control" accept="image/*" required onchange="check_size(this.id,100);">
                                                </div>
                                                <div class="mb-3 col-xl-4">
                                                    <label for="file_aadhaar_back" class="form-label">Aadhaar Back<span class="text-danger">*jpg/jpeg/png format; 100KB max</span></label>
                                                    <input type="file" name="file_aadhaar_back" id="file_aadhaar_back" class="form-control" accept="image/*" required onchange="check_size(this.id,100);">
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
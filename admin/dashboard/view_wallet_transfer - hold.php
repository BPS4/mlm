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
        $position = 7;
        
        $check_type = "";

        $header_text = "Transaction Summary For Superwallet";
    // CHECK AVAILABILITY

	// DEFAULT
		$page_id_home = 1;
		$bank_update = $photo_update = $pan_update = $aadhaar_update = 0;
        $msg = $msg_type = "";
		$error = false;
        $tax_percent = 15;
        $tds_percent = 5;
        $service_charge_percent = 10;
	// DEFAULT

    // GET DATA
        $query = "SELECT * FROM `transaction_superwallet` WHERE `user_id`='$user_id' ORDER BY `create_date` DESC";
        $res = mysqli_query($conn,$query);
        $transactions = mysqli_fetch_all($res,MYSQLI_ASSOC);
    // GET DATA

    // DEFAULT ORDER COLUMN SR
		$default_Order = 0;

        $wallet_investment = $wallet_roi = $wallet_commission = $wallet_fd = 0;

    // GET WALLET DATA
        $query_wallet = "SELECT `wallet_investment`, `wallet_roi`, `wallet_commission`, `wallet_fd`, `superwallet` FROM `wallets` WHERE `user_id`='$user_id'";
        $query = mysqli_query($conn, $query_wallet);
        $res = mysqli_fetch_array($query);
        extract($res);  
    // GET WALLET DATA

    $fund_total = $fund_available = $wallet_investment + $wallet_roi + $wallet_commission;


    // INDIVIDUAL KYC STATUS
        $url_dir = "../assets/files/transaction";
        $url_dir_aadhaar = "../assets/files/aadhaar";
        $url_dir_pan = "../assets/files/pan";
        $url_dir_bank = "../assets/files/bank";
        $url_dir_photo = "../assets/files/photo";
        
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
        $aadhaar_approved = $pan_approved = $cheque_approved = $passbook_approved = $photo_approved = 0;
        $aadhaar_rejected = $pan_rejected = $cheque_rejected = $passbook_rejected = $photo_rejected = 0;
        $aadhaar_pending = $pan_pending = $cheque_pending = $passbook_pending = $photo_pending = 0;
        $aadhaar_needed = $pan_needed = $bank_needed = $cheque_needed = $passbook_needed = $photo_needed = 0;
        $aadhaar_readonly = $pan_readonly = $bank_readonly = $cheque_readonly = $passbook_readonly = $photo_readonly = "readonly";
        
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

        // if ($user_doc_status == "approved") {
        // } else if ($user_doc_status == "rejected") {
        //     $kyc_status = "<span class='btn btn-xs btn-danger'>REJECTED</span>";
        // } else {
        // }

        // if ($count_docs_approved > 3) {
        if ($pan_approved > 0 && $aadhaar_approved > 0 && ($cheque_approved > 0 || $passbook_approved > 0)) {
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
                                                <p class="d-flex justify-content-around align-items-center" style="flex-wrap: wrap;">
                                                    <?php
                                                        if ($kyc_done == 0) {
                                                            ?>
                                                                <a class='mt-3 btn btn-primary btn-sm text-white show-pointer' href="view_profile.php" onclick="return confirm('Complete Your KYC First!');">
                                                                    <i class="icofont icofont-money"></i> Transfer To SuperWallet
                                                                </a>
                                                                <a class='mt-3 btn btn-success btn-sm text-white show-pointer' href="view_profile.php" onclick="return confirm('Complete Your KYC First!');">
                                                                    <i class="icofont icofont-money"></i> Use SuperWallet
                                                                </a>
                                                                <a class='mt-3 btn btn-warning btn-sm text-white show-pointer' href="view_profile.php" onclick="return confirm('Complete Your KYC First!');">
                                                                    <i class="icofont icofont-money"></i> Withdraw SuperWallet
                                                                </a>
                                                            <?php
                                                        } else {
                                                            ?>
                                                                <a class='mt-3 btn btn-primary btn-sm text-white show-pointer' data-bs-toggle='modal' data-bs-target='#ModalPay' data-payment='' data-beneficiary='COMPANY' data-transaction_type="fresh" id="btn_pay">
                                                                    <i class="icofont icofont-money"></i> Transfer To SuperWallet
                                                                </a>
                                                                <a class='mt-3 btn btn-success btn-sm text-white show-pointer' data-bs-toggle='modal' data-bs-target='#ModalTransfer' id="btn_pay1">
                                                                    <i class="icofont icofont-money"></i> Use SuperWallet
                                                                </a>
                                                                <a class='mt-3 btn btn-warning btn-sm text-white show-pointer' href="view_withdrawal.php">
                                                                    <i class="icofont icofont-money"></i> Withdraw SuperWallet
                                                                </a>
                                                            <?php
                                                        }
                                                    ?>
                                                </p>
                                            </div>
                                            <div class="card-datatable table-responsive">
                                                <table id="datatables" class="datatables table border-top table-strriped table-hover border-danger table-warning">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr</th>
                                                            <th>Transaction Type</th>
                                                            <th>Transfer From</th>
                                                            <th>Transfer To</th>
                                                            <th>Amount Requested</th>
                                                            <th>Deductions (15%)</th>
                                                            <th>Amount Received</th>
                                                            <th>Status</th>
                                                            <th>Transaction Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $i = 0;
                                                            foreach ($transactions as $item) {
                                                                $i++;
                                                                $from_wallet = $item['from_wallet'];
                                                                $user_id = $item['user_id'];
                                                                $from_user_id = $item['from_user_id'];
                                                                $to_user_id = $item['to_user_id'];
                                                                $transaction_mode = $item['transaction_mode'];
                                                                $transaction_amount = $item['transaction_amount'];
                                                                $tds = $item['tds'];
                                                                $service_charge = $item['service_charge'];
                                                                $net_amount = $item['net_amount'];
                                                                $status = $item['status'];
                                                                $create_date = $item['create_date'];
                                                                
                                                                if ($from_user_id == "") {
                                                                    $from_user_id = $user_id;
                                                                }

                                                                //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT
                                                                    $regEx = '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/';
                                                                    // preg_match($regEx, $details['date'], $result);
                                                                    if (preg_match($regEx, $create_date) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $create_date)) {
                                                                        $date = date_create($create_date);
                                                                        $create_date = date_format($date, "d-M-Y h:iA");
                                                                        $create_date = date_format($date, "d-M-Y");
                                                                    }
                                                                //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT

                                                                $receiver_id = $user_id;
                                                                $query_receiver = "SELECT `user_id` AS 'receiver_id', `name` AS 'receiver_name' FROM `users` WHERE `user_id`='$receiver_id'";

                                                                switch ($from_wallet) {
                                                                    case 'wallet_investment':
                                                                        $transaction_type = "<span class='badge bg-info shadow rounded-pill' style='font-size:11px;'>Investment Wallet</span>";
                                                                        break;
                                                                        
                                                                    case 'wallet_roi':
                                                                        $transaction_type = "<span class='badge bg-success shadow rounded-pill' style='font-size:11px;'>ROI Wallet</span>";
                                                                        break;

                                                                    case 'wallet_commission':
                                                                        $transaction_type = "<span class='badge bg-danger shadow rounded-pill' style='font-size:11px;'>Commission Wallet</span>";
                                                                        break;
                                                                        
                                                                    case 'superwallet_transfer':
                                                                        $transaction_type = "<span class='badge bg-primary shadow rounded-pill' style='font-size:11px;'>SuperWallet Transfer</span>";
                                                                        if ($transaction_mode == "debit") {
                                                                            // $user_id = $to_user_id;
                                                                            $receiver_id = $to_user_id;
                                                                            $query_receiver = "SELECT `user_id` AS 'receiver_id', `name` AS 'receiver_name' FROM `users` WHERE `user_id`='$to_user_id'";
                                                                        }
                                                                        break;

                                                                    case 'superwallet_invest':
                                                                        $transaction_type = "<span class='badge bg-dark shadow rounded-pill' style='font-size:11px;'>SuperWallet Investment</span>";
                                                                        
                                                                        $query_receiver = "SELECT `user_id` AS 'receiver_id', `name` AS 'receiver_name' FROM `users` WHERE `user_id`='$receiver_id'";
                                                                        break;
                                                                }

                                                                $query_name = "SELECT `name` AS 'donor_name' FROM `users` WHERE `user_id`='$from_user_id'";
                                                                $query = mysqli_query($conn,$query_name);
                                                                $res = mysqli_fetch_array($query);
                                                                extract($res);
                                                                   
                                                                $query = mysqli_query($conn,$query_receiver);
                                                                $res = mysqli_fetch_array($query);
                                                                extract($res);

                                                                $donor = "$donor_name ($from_user_id)";
                                                                $receiver = "$receiver_name ($receiver_id)";
                                                                
                                                                if ($from_user_id == $user_id) {
                                                                    $donor = "SELF";
                                                                    $donor = "<span class='badge bg-light-info shadow rounded text-danger text-bold'>SELF</span>";
                                                                }

                                                                if ($receiver_id == $user_id) {
                                                                    $receiver = "SELF";
                                                                    $receiver = "<span class='badge bg-light-info shadow rounded text-danger text-bold'>SELF</span>";
                                                                }

                                                                switch ($status) {
                                                                    case '2':
                                                                        $status = "<span class='badge bg-warning shadow rounded'>Pending</span>";
                                                                        break;
                                                                    
                                                                    case '1':
                                                                        $status = "<span class='badge bg-success shadow rounded'>Processed</span>";
                                                                        break;
                                                                    
                                                                    case '0':
                                                                        $status = "<span class='badge bg-danger shadow rounded'>Rejected</span>";
                                                                        break;
                                                                    
                                                                    default:
                                                                        $status = "<span class='badge bg-info shadow rounded'>N/A</span>";
                                                                        break;
                                                                }

                                                                ?>
                                                                    <tr class="no-wrap">
                                                                        <td><?php echo $i; ?></td>
                                                                        <td><?php echo $transaction_type; ?></td>
                                                                        <td><?php echo $donor; ?></td>
                                                                        <td><?php echo $receiver; ?></td>
                                                                        <td class="no-wrap">₹ <?php echo number_format($transaction_amount,2); ?></td>
                                                                        <td class="no-wrap">₹ <?php echo number_format($tds+$service_charge,2); ?></td>
                                                                        <td class="no-wrap">₹ <?php echo number_format($net_amount,2); ?></td>
                                                                        <td><?php echo $status; ?></td>
                                                                        <td><?php echo $create_date; ?></td>
                                                                    </tr>
                                                                <?php
                                                            }
                                                        ?>                                                    
                                                    </tbody>
                                                    <tfoot class="d-none">
                                                        <tr>
                                                            <th>Sr</th>
                                                            <th>Transaction Type</th>
                                                            <th>Transfer From</th>
                                                            <th>Transfer To</th>
                                                            <th>Amount Requested</th>
                                                            <th>Deductions (15%)</th>
                                                            <th>Amount Received</th>
                                                            <th>Status</th>
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
		<div class="modal fade" id="ModalPay" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">Request Fund Transfer To Your Superwallet</h3>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p class="mb-0">
						<form method="POST" action="" id="form_add_record" enctype="multipart/form-data">
                            <input type="hidden" name="transaction_type_txn" id="transaction_type_txn">
							<div class="row justify-content-center align-items-center">
								<div class="mb-3 col-md-12">
                                    <label for="note" class="form-label text-center"><b class="text-danger">Amount transferred to Superwallet will be credited after 15% deductions. The funds available in Superwallet can be further withdrawn or transferred to other Maxizone members.</b></label>
								</div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="fund_available">Available Balance</label>
                                    <input type="text" id="fund_available" class="form-control bg-light-success text-primary" value="₹ <?php echo number_format($fund_available,2); ?>" readonly />                                    
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Wallet Balance</label>

                                    <label id="wallet_roi" class="text--success">ROI Wallet {Available: ₹ <?php echo number_format($wallet_roi,2); ?>}</label>
                                    <label id="wallet_commission" class="text--info">Commission Wallet {Available: ₹ <?php echo number_format($wallet_commission,2); ?>}</label>
                                    <label id="wallet_investment" class="text--primary">Investment Wallet {Available: ₹ <?php echo number_format($wallet_investment,2); ?>}</label>
                                    <!-- <label id="superwallet" class="text--danger text-secondary">Super Wallet {Available: ₹ <?php echo number_format($superwallet,2); ?>}</label> -->
                                </div>
                                <div class="col-md-6 d-none">
                                    <div class="col-sm-12">
                                        <h5>Wallets</h5>
                                    </div>
                                    <div class="card-body animate-chk">
                                        <div class="row">
                                            <div class="col">
                                                <label class="d-block" for="edo-ani">
                                                    <input class="radio_animated me-1" id="edo-ani" type="radio" name="rdo-ani"> Option 1
                                                </label>
                                                <label class="d-block" for="edo-ani1">
                                                    <input class="radio_animated me-1" id="edo-ani1" type="radio" name="rdo-ani"> Option 2
                                                </label>
                                                <label class="d-block" for="edo-ani2">
                                                    <input class="radio_animated me-1" id="edo-ani2" type="radio" name="rdo-ani"> Option 3
                                                </label>
                                                <label class="d-block" for="edo-ani13">
                                                    <input class="radio_animated me-1" id="edo-ani13" type="radio" name="rdo-ani"> Option 4
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								<div class="mb-3 col-md-6 d-none">
									<label for="source" class="form-label">Wallets <span class="text-danger">*</span></label>
                                    <br>
                                    <input type="checkbox" name="source[]" id="source1">
                                    <label for="source1">Investment Wallet {Available: ₹ <?php echo number_format($wallet_investment,2); ?>}</label>
                                    <input type="checkbox" name="source[]" id="source2">
                                    <label for="source2">ROI Wallet {Available: ₹ <?php echo number_format($wallet_roi,2); ?>}</label>
                                    <input type="checkbox" name="source[]" id="source3">
                                    <label for="source3">Commission Wallet {Available: ₹ <?php echo number_format($wallet_commission,2); ?>}</label>
                                    <input type="checkbox" name="source[]" id="source4">
                                    <label for="source4">Super Wallet {Available: ₹ <?php echo number_format($superwallet,2); ?>}</label>

									<select name="source" id="source" class="form-control" required>
										<option value="" hidden>Select Source</option>
											<option value="investment_wallet" data-avalable_amount="<?php echo $wallet_investment; ?>" <?php echo ($wallet_investment==0)?'disabled':''; ?>>Investment Wallet {Available: ₹ <?php echo number_format($wallet_investment,2); ?>}</option>
											<option value="super_wallet" data-avalable_amount="<?php echo $superwallet; ?>" <?php echo ($superwallet==0)?'disabled':''; ?>>Super Wallet {Available: ₹ <?php echo number_format($superwallet,2); ?>}</option>
									</select>
								</div>
								<div class="mb-3 col-md-6">
                                    <label class="form-label" for="withdrawal_amount">Withdrawal Amount</label>
                                    <!-- (In multiples of 100) -->
                                    <!-- <input type="number" class="form-control" id="withdrawal_amount" name="withdrawal_amount" placeholder="Enter Withdrawal Amount" value="" min="1000" step="100" max="<?php echo $fund_total; ?>" required onblur="get_credit(this.id);" onkeyup="get_credit(this.id);" onchange="get_credit(this.id);"> -->
                                    <input type="number" class="form-control" id="withdrawal_amount" name="withdrawal_amount" placeholder="Enter Withdrawal Amount" value="" min="1000" max="<?php echo $fund_total; ?>" required onblur="get_credit(this.id);" onkeyup="get_credit(this.id);" onchange="get_credit(this.id);">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="withdrawal_amount">15% Deductions</label>
                                    <input type="number" class="form-control bg-label-warning" id="deduction" name="deduction" placeholder="Enter Withdrawal Amount" required readonly>
                                </div>
                                <!-- <div class="mb-3 col-md-6">
                                    <label class="form-label" for="withdrawal_amount">5% TDS</label>
                                    <input type="number" class="form-control bg-label-warning" id="tds" name="tds" placeholder="Enter Withdrawal Amount" required readonly>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="withdrawal_amount">10% Service Charge</label>
                                    <input type="number" class="form-control bg-label-warning" id="service_charge" name="service_charge" placeholder="Enter Withdrawal Amount" required readonly>
                                </div> -->
                                <div class="mb-4 col-md-6">
                                    <label class="form-label" for="withdrawal_amount">Amount To Be Received After 15% Deductions</label>
                                    <input type="number" class="form-control bg-success text-white placeholder-text-white" id="withdrawal_credit" name="withdrawal_credit" placeholder="Enter Withdrawal Amount" value="" min="1000" step="100" max="<?php echo $fund_total; ?>" required readonly>
                                </div>
								<div class="mb-3 col-md-12">
                                    <input type="checkbox" name="agree" id="agree">
									<label for="agree" class="form-label" style="display: inline;">I accept the terms while requesting Fund Transfer to my Maxizone Superwallet, <span class="text-danger">I will receive the amount after 15% deductions</span></label>
								</div>
                                <label class="text-danger text-bold mb-3 lead text-center" id="msg_error">Amount To Be Received After 15% Deductions</label>
							</div>
							<div style="float:right;">
								<!-- <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success" id="btn_submit" name="submit">Submit</button> -->
                                <button type="reset" class="btn btn-dark" data-bs-dismiss="modaal" id="btn_reset">RESET</button>
                                
                                <button type="button" class="btn btn-primary me-sm-3 me-1" id="btn_save" onclick="save_withdraw_request();">Submit</button>
                                <button class="btn btn-secondary px-3 py-1 me-sm-3 me-1 d-none"  type="button" disabled id="btn_saving">
                                    <!-- <span class="spinner-border" role="status" aria-hidden="true"></span> -->
                                    <div class="loader-box" style="height:auto">
                                        <div class="loader-7" style="height: 25px; width: 25px;"></div>
                                        Saving...
                                    </div>
                                </button>
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

    <!-- BEGIN ModalTransfer -->
		<div class="modal fade" id="ModalTransfer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">Transfer MT Wallet</h3>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p class="mb-0">
						<form method="POST" action="" id="form_add_record" enctype="multipart/form-data">
                            <input type="hidden" name="transaction_type_txn" id="transaction_type_txn">
							<div class="row justify-content-center align-items-center">
								<div class="mb-3 col-md-12">
                                    <label for="note" class="form-label text-center"><b class="text-danger">Superwallet funds can be used for <span class="text-success">Withdrawal</span>, <span class="text-success">Investment</span> and <span class="text-success">Transfer to other Maxizone Members</span></b></label>
								</div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="available_superwallet">Available Balance</label>
                                    <input type="text" id="available_superwallet" class="form-control bg-light-success text-primary" value="₹ <?php echo number_format($superwallet,2); ?>" readonly />                                    
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Action to Perform</label>
                                    <select name="transfer_action" id="transfer_action" class="form-control" onchange="show_transfer_action();" required>
                                        <option value="">Choose Action</option>
                                        <option value="invest">Update Investment</option>
                                        <option value="transfer_member">Transfer to Member</option>
                                    </select>
                                </div>
                                <div class="row d-none" id="transfer_to_container">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="transfer_to">Transfer To</label>
                                        <input type="text" class="form-control bg-label-warning" id="transfer_to" name="transfer_to" placeholder="Enter Recipient Member ID" onkeyup="get_member_name()">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="transfer_to_member">Member Name</label>
                                        <input type="text" class="form-control bg-label-warning" id="transfer_to_member" name="transfer_to_member" placeholder="Enter Recipient Member ID First" readonly>
                                    </div>
                                    <span id="sponsor_id_error" class="text-danger d-none"></span>
                                </div>
								<div class="mb-3 col-md-6">
                                    <label class="form-label" for="transfer_amount">Transaction Amount</label>
                                    <!-- (In multiples of 100) -->
                                    <input type="number" class="form-control" id="transfer_amount" name="transfer_amount" placeholder="Enter Amount" value="" min="1000" step="100" max="<?php echo $superwallet; ?>" required>
                                </div>
								<!-- <div class="mb-3 col-md-12">
                                    <input type="checkbox" name="agree" id="agree">
									<label for="agree" class="form-label" style="display: inline;">I accept the terms while requesting Fund Transfer to my Maxizone Superwallet, <span class="text-danger">I will receive the amount after 15% deductions</span></label>
								</div> -->
                                <label class="text-danger text-bold mb-3 lead text-center d-none" id="msg_error1">Amount To Be Received After 15% Deductions</label>
							</div>
							<div style="float:right;">
								<!-- <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success" id="btn_submit" name="submit">Submit</button> -->
                                <button type="reset" class="btn btn-dark" data-bs-dismiss="modaal" id="btn_reset1">RESET</button>
                                
                                <button type="button" class="btn btn-primary me-sm-3 me-1" id="btn_save1" onclick="save_transfer_request();">Submit</button>
                                <button class="btn btn-secondary px-3 py-1 me-sm-3 me-1 d-none"  type="button" disabled id="btn_saving1">
                                    <!-- <span class="spinner-border" role="status" aria-hidden="true"></span> -->
                                    <div class="loader-box" style="height:auto">
                                        <div class="loader-7" style="height: 25px; width: 25px;"></div>
                                        Saving...
                                    </div>
                                </button>
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
	<!-- END ModalTransfer -->

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
    </script>

    
    <script>
        function get_credit(elem) {
            withdrawal_amount = document.getElementById(elem);
            withdrawal_credit = document.getElementById("withdrawal_credit");
            // tds = document.getElementById("tds");
            // service_charge = document.getElementById("service_charge");
            deduction = document.getElementById("deduction");

            $withdrawal_amount = withdrawal_amount.value;

            
            var wallet_investment_label = document.getElementById("wallet_investment");
            var wallet_roi_label = document.getElementById("wallet_roi");
            var wallet_commission_label = document.getElementById("wallet_commission");
            
            var fund_available = +<?php echo $fund_available; ?>;
            var wallet_investment = +<?php echo $wallet_investment; ?>;
            var wallet_roi = +<?php echo $wallet_roi; ?>;
            var wallet_commission = +<?php echo $wallet_commission; ?>;
            var superwallet = +<?php echo $superwallet; ?>;

            if ($withdrawal_amount == fund_available) {
                wallet_investment_label.classList.add("text-success");
                wallet_roi_label.classList.add("text-success");
                wallet_commission_label.classList.add("text-success");
            } else {
                wallet_investment_label.classList.remove("text-success");
                wallet_roi_label.classList.remove("text-success");
                wallet_commission_label.classList.remove("text-success");
            }

            // if ($withdrawal_amount = wallet_investment+wallet_roi+wallet_commission) {
            //     wallet_investment_label.classList.add("text-success");
            //     wallet_roi_label.classList.add("text-success");
            //     wallet_commission_label.classList.add("text-success");
            // } else if ($withdrawal_amount >= wallet_investment+wallet_roi) {
            //     wallet_investment_label.classList.add("text-success");
            //     wallet_roi_label.classList.add("text-success");
            //     wallet_commission_label.classList.remove("text-success");
            // } else if ($withdrawal_amount >= wallet_investment+wallet_commission) {
            //     wallet_investment_label.classList.add("text-success");
            //     wallet_roi_label.classList.remove("text-success");
            //     wallet_commission_label.classList.add("text-success");
            // } else if ($withdrawal_amount >= wallet_roi+wallet_commission) {
            //     wallet_investment_label.classList.remove("text-success");
            //     wallet_roi_label.classList.add("text-success");
            //     wallet_commission_label.classList.add("text-success");
            // } else if ($withdrawal_amount >= wallet_investment) {
            //     wallet_investment_label.classList.add("text-success");
            //     wallet_roi_label.classList.remove("text-success");
            //     wallet_commission_label.classList.remove("text-success");
            // } else if ($withdrawal_amount >= wallet_commission) {
            //     wallet_investment_label.classList.remove("text-success");
            //     wallet_roi_label.classList.remove("text-success");
            //     wallet_commission_label.classList.add("text-success");
            // } else if ($withdrawal_amount >= wallet_roi) {
            //     wallet_investment_label.classList.remove("text-success");
            //     wallet_roi_label.classList.add("text-success");
            //     wallet_commission_label.classList.remove("text-success");
            // }

            if ($withdrawal_amount <= wallet_roi) {
                wallet_roi_label.classList.add("text-success");

                wallet_commission_label.classList.remove("text-success");
                wallet_investment_label.classList.remove("text-success");
            } else if ($withdrawal_amount >= wallet_roi && $withdrawal_amount <= wallet_roi+wallet_commission) {
                wallet_roi_label.classList.add("text-success");
                wallet_commission_label.classList.add("text-success");

                wallet_investment_label.classList.remove("text-success");
            } else if ($withdrawal_amount > wallet_roi+wallet_commission && $withdrawal_amount <= wallet_roi+wallet_commission+wallet_investment) {
                wallet_commission_label.classList.add("text-success");
                wallet_roi_label.classList.add("text-success");
                wallet_investment_label.classList.add("text-success");
            }

            if ($withdrawal_amount == wallet_roi) {
                wallet_roi_label.classList.add("text-success");
                
                wallet_investment_label.classList.remove("text-success");
                wallet_commission_label.classList.remove("text-success");
            }
            
            if ($withdrawal_amount == wallet_commission) {
                wallet_commission_label.classList.add("text-success");

                wallet_investment_label.classList.remove("text-success");
                wallet_roi_label.classList.remove("text-success");
            }

            if ($withdrawal_amount == wallet_investment) {
                wallet_investment_label.classList.add("text-success");

                wallet_roi_label.classList.remove("text-success");
                wallet_commission_label.classList.remove("text-success");
            }
            
            tax_percent = <?php echo $tax_percent; ?>;
            tds_percent = <?php echo $tds_percent; ?>;
            service_charge_percent = <?php echo $service_charge_percent; ?>;
            
            $tax = $withdrawal_amount*tax_percent/100;
            $tax = $tax.toFixed(2);
            
            $tds = $withdrawal_amount*tds_percent/100;    //5% in 15%
            $service_charge = $withdrawal_amount*service_charge_percent/100; //10% in 15%
            
            $tds = $tds.toFixed(2);
            $service_charge = $service_charge.toFixed(2);

            $withdrawal_credit = $withdrawal_amount - $tax;
            $withdrawal_credit = $withdrawal_credit.toFixed(2);
            
            withdrawal_credit.value = $withdrawal_credit;
            
            // tds.value = $tds;
            // service_charge.value = $service_charge;

            deduction.value = $tax;
        }

        var btn_save = document.getElementById("btn_save");
        var btn_saving = document.getElementById("btn_saving");
        var msg_error = document.getElementById("msg_error");
        
        function hide_element(elem) {
            elem.classList.add('d-none')
        }

        function show_element(elem) {
            elem.classList.remove('d-none')
        }
        hide_element(msg_error);
        
        function save_withdraw_request() {
            var action = "superwallet_transfer_request";            

            var user_id = '<?php echo $user_id; ?>';
            var fund_available = +<?php echo $fund_available; ?>;

            var withdrawal_amount = document.getElementById("withdrawal_amount");
            var agree = document.getElementById("agree");
            
            var wallet_investment_label = document.getElementById("wallet_investment");
            var wallet_roi_label = document.getElementById("wallet_roi");
            var wallet_commission_label = document.getElementById("wallet_commission");
            var superwallet_label = document.getElementById("superwallet");
            
            var wallet_investment = +<?php echo $wallet_investment; ?>;
            var wallet_roi = +<?php echo $wallet_roi; ?>;
            var wallet_commission = +<?php echo $wallet_commission; ?>;
            var superwallet = +<?php echo $superwallet; ?>;

            a = fund_available%100;
            min_allowed_amount = 1000;
            max_allowed_amount = +(fund_available - a);

            // + USED FOR CONVERTING A STRING TO NUMBER
            withdrawal_amount_value_trim = +withdrawal_amount.value.trim();

            if (withdrawal_amount_value_trim == "" || withdrawal_amount_value_trim == 0 || withdrawal_amount_value_trim < 0) {
                withdrawal_amount.focus();
                err_msg = "***Kindly Enter Valid Amount For Withdrawal***";
                msg_error.innerHTML = err_msg;
                show_element(msg_error);
                return
            }

            if (withdrawal_amount_value_trim < 1000) {
                withdrawal_amount.focus();
                err_msg = "***Kindly Enter Withdrawal Amount More Than Rs.1000***";
                msg_error.innerHTML = err_msg;
                show_element(msg_error);
                return
            }

            // if (withdrawal_amount_value_trim%100 != 0) {
            //     withdrawal_amount.focus();
            //     err_msg = "***Kindly Enter Withdrawal Amount In Multiples Of Rs.100***";
            //     msg_error.innerHTML = err_msg;
            //     show_element(msg_error);
            //     return
            // }

            if (withdrawal_amount_value_trim > fund_available) {
                withdrawal_amount.focus();
                err_msg = "***Kindly Enter Withdrawal Amount Less Than <i>Rs."+fund_available+"</i>***";
                msg_error.innerHTML = err_msg;
                show_element(msg_error);
                return
            }

            if (!agree.checked) {
                agree.focus();
                err_msg = "***Kindly Accept The Terms Checkbox Before Proceeding Further***";
                msg_error.innerHTML = err_msg;
                show_element(msg_error);
                return
            }

            hide_element(msg_error);
            hide_element(btn_save);
            show_element(btn_saving);

            $.ajax({
				type: "POST",
				url: "../ajax.php",
				data: {
					action: action,
					user_id: user_id,
					withdrawal_amount: withdrawal_amount.value
				},
				
				// data: $("#form_register").serialize() + "&action=" +action , // getting filed value in serialize form And Passing additional Value of 'action' To perform Task Accordingly
				// dataType: 'JSON',
				cache: false,

				success: function(response) {
                    console.log(response);

                    hide_element(btn_saving);
                    show_element(btn_save);

                    switch (response) {
                        case 'not_active':
                            showNotif('Unable to Process Superwallet Transfer Request as Your ID Is Not Active! Invest Some Amount Before Proceeding','error');
                            break;
                    
                        case 'not_allowed':
                            showNotif('Superwallet Transfer Requests are allowed Only on Friday of Every Week! Please submit your Superwallet Transfer Request on Next Friday','error');
                            break;
                    
                        case 'fund_exceed':
                            showNotif('Entered amount is more than the fund available in your wallets! Please submit Request for accurate amount','error');
                            break;
                    
                        case 'request_pending':
                            showNotif('A withdrawal request is already pending. Kindly Wait For Its Approval Before Proceeding','error');
                            break;
                    
                        case 'success':
                            showNotif('Your Superwallet Transfer Request Is Processed Successfully.','success');
                            setTimeout(function(){
                                window.location.reload();
                            }, 3000);
                            break;
                    
                        default:
    						showNotif("Error Occured... Try Again", "error");
                            break;
                    }
				}
			});
        }
    </script>
    
    <script>
        function get_member_name() {
			var action = "get_sponsor";

			var sponsor_id = document.getElementById("transfer_to");
			var sponsor_id_error = document.getElementById("sponsor_id_error");
			var sponsor_name = document.getElementById("transfer_to_member");
			
			if (sponsor_id.value == '' || sponsor_id.value.trim() == '' || sponsor_id.value.length<6) {
				sponsor_id.focus();
				// sponsor_id.scrollIntoView();
				//SHOW NOTIFICATION
                sponsor_id_error.classList.remove("d-none");
				sponsor_id_error.innerHTML = "Enter Correct Member ID";
				sponsor_name.placeholder = "Enter Member ID";

				return false;
			} else {
                sponsor_id_error.classList.add("d-none");
				sponsor_id_error.innerHTML="";
            }

			// using this page stop being refreshing 
			event.preventDefault();
			
			// All Validation Done
				sponsor_id = sponsor_id.value;
				
			// Call ajax for pass data to other place
			$.ajax({
				type: "POST",
				url: "../ajax.php",
				data: {
					action: action,
					sponsor_id
				},
				
				// data: $("#form_register").serialize() + "&action=" +action , // getting filed value in serialize form And Passing additional Value of 'action' To perform Task Accordingly
				// dataType: 'JSON',
				cache: false,

				success: function(response) {
                    if (response == "NO RECORD") {
                        sponsor_name.value = "";
                        sponsor_name.style.background = "red";
                        sponsor_name.style.color = "white";
                        sponsor_name.classList.add('placeholder_error');
                        sponsor_name.placeholder = "Enter Correct Member ID";
                        
                        sponsor_id_error.classList.remove("d-none");
				        sponsor_id_error.innerHTML = "Enter Correct Member ID";
                    } else {
                        sponsor_name.style.background = "";
                        sponsor_name.style.color = "";
                        sponsor_name.classList.remove('placeholder_error');
                        sponsor_name.value = response;

                        sponsor_id_error.classList.add("d-none");
				        sponsor_id_error.innerHTML="";
                    }
				}
			});
		}
    </script>

    <script>
        function show_transfer_action() {
            var transfer_action = document.getElementById("transfer_action");
            var transfer_to_container = document.getElementById("transfer_to_container");
            var transfer_to = document.getElementById("transfer_to");

            transfer_to.setAttribute("required",false);

            if (transfer_action.value == "invest") {
                hide_element(transfer_to_container);
            } else if (transfer_action.value == "transfer_member") {
                show_element(transfer_to_container);
                transfer_to.setAttribute("required",true);
            } else {
                hide_element(transfer_to_container);
            }
        }

        function save_transfer_request() {
            var btn_save = document.getElementById("btn_save1");
            var btn_saving = document.getElementById("btn_saving1");
            var msg_error = document.getElementById("msg_error1");

            var action = "superwallet_use_request";            

            var user_id = '<?php echo $user_id; ?>';

            var transfer_action = document.getElementById("transfer_action");
            var transfer_to = document.getElementById("transfer_to");
            var transfer_amount = document.getElementById("transfer_amount");

            var superwallet = +<?php echo $superwallet; ?>;

            a = superwallet%100;
            min_allowed_amount = 1000;
            max_allowed_amount = +(superwallet - a);

            // + USED FOR CONVERTING A STRING TO NUMBER
            withdrawal_amount_value_trim = +transfer_amount.value.trim();

            if (transfer_action.value == "") {
                transfer_action.focus();
                err_msg = "***Kindly Select The Action To Perform***";
                msg_error.innerHTML = err_msg;
                show_element(msg_error);
                return
            }

            if (transfer_action.value == "transfer_member") {
                if (transfer_to.value.trim() == "") {
                    transfer_to.focus();
                    err_msg = "***Kindly Enter Recipient Member ID***";
                    msg_error.innerHTML = err_msg;
                    show_element(msg_error);
                    return
                }
            }

            if (withdrawal_amount_value_trim == "" || withdrawal_amount_value_trim == 0 || withdrawal_amount_value_trim < 0) {
                withdrawal_amount.focus();
                err_msg = "***Kindly Enter Valid Amount***";
                msg_error.innerHTML = err_msg;
                show_element(msg_error);
                return
            }

            if (withdrawal_amount_value_trim < 1000) {
                withdrawal_amount.focus();
                err_msg = "***Kindly Enter Amount More Than Rs.1000***";
                msg_error.innerHTML = err_msg;
                show_element(msg_error);
                return
            }

            if (withdrawal_amount_value_trim%100 != 0) {
                withdrawal_amount.focus();
                err_msg = "***Kindly Enter Amount In Multiples Of Rs.100***";
                msg_error.innerHTML = err_msg;
                show_element(msg_error);
                return
            }

            if (withdrawal_amount_value_trim > max_allowed_amount) {
                withdrawal_amount.focus();
                err_msg = "***Kindly Enter Amount Less Than <i>Rs."+max_allowed_amount+"</i>***";
                msg_error.innerHTML = err_msg;
                show_element(msg_error);
                return
            }

            // if (!agree.checked) {
            //     agree.focus();
            //     err_msg = "***Kindly Accept The Terms Checkbox Before Proceeding Further***";
            //     msg_error.innerHTML = err_msg;
            //     show_element(msg_error);
            //     return
            // }

            hide_element(msg_error);
            hide_element(btn_save);
            show_element(btn_saving);

            $.ajax({
				type: "POST",
				url: "../ajax.php",
				data: {
					action: action,
					user_id: user_id,
					withdrawal_amount: transfer_amount.value,
					transfer_action: transfer_action.value,
					transfer_to: transfer_to.value
				},
				
				// data: $("#form_register").serialize() + "&action=" +action , // getting filed value in serialize form And Passing additional Value of 'action' To perform Task Accordingly
				// dataType: 'JSON',
				cache: false,

				success: function(response) {
                    console.log(response);

                    hide_element(btn_saving);
                    show_element(btn_save);

                    switch (response) {
                        case 'not_active':
                            showNotif('Unable to Process Superwallet Transfer Request as Your ID Is Not Active! Invest Some Amount Before Proceeding','error');
                            break;
                    
                        case 'not_allowed':
                            showNotif('Superwallet Transfer Requests are allowed Only on Friday of Every Week! Please submit your Superwallet Transfer Request on Next Friday','error');
                            break;
                    
                        case 'fund_exceed':
                            showNotif('Entered amount is more than the fund available in your wallets! Please submit Request for accurate amount','error');
                            break;
                    
                        case 'multiple_required':
                            showNotif('Entered amount is not in multiples of Rs.100! Please Enter Amount in multiples of Rs.100','error');
                            break;
                    
                        case 'member_id_empty':
                            showNotif('Recipient Member ID is Required For Processing Your Request!','error');
                            break;
                    
                        case 'member_id_invalid':
                            showNotif('Invalid Recipient Member ID! Retry with Correct ID','error');
                            break;
                    
                        case 'invalid_action':
                            showNotif('Selected Action is Not Valid','error');
                            break;
                    
                        case 'request_pending':
                            showNotif('A Transfer request is already pending. Kindly Wait For Its Approval Before Proceeding','error');
                            break;
                    
                        case 'success':
                            showNotif('Your Fund Transfer Request Is Processed Successfully.','success');
                            setTimeout(function(){
                                window.location.reload();
                            }, 3000);
                            break;
                    
                        default:
    						showNotif("Error Occured... Try Again", "error");
                            break;
                    }
				}
			});
        }
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
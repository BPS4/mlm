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
    $current_day = date('D');

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

        $header_text = "Withdrawal Transaction Summary For $name ($user_id)";
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
        $query = "SELECT * FROM `withdrawal` WHERE `user_id`='$user_id' ORDER BY `create_date` DESC";
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

    $fund_total = $fund_available = $superwallet;


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
                                                <p class="d-flex justify-content-start align-items-center" style="flex-wrap: wrap;">
                                                    <?php
                                                        if ($kyc_done == 0) {
                                                            ?>
                                                                <a class='mt-3 btn btn-primary btn-sm text-white show-pointer' href="view_profile.php" onclick="return confirm('Complete Your KYC First!');">
                                                                    <i class="icofont icofont-money"></i> Request For Withdrawal
                                                                </a>
                                                            <?php
                                                        } else {
                                                            if ($current_day == "Fri") {
                                                                ?>
                                                                    <a class='mt-3 btn btn-primary btn-sm text-white show-pointer' data-bs-toggle='modal' data-bs-target='#ModalPay' data-payment='' data-beneficiary='COMPANY' data-transaction_type="fresh" id="btn_pay">
                                                                        <i class="icofont icofont-money"></i> Request For Withdrawal
                                                                    </a>
                                                                <?php
                                                            }
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
                                                            <th>Withdrawal Amount</th>
                                                            <th>Status</th>
                                                            <th>Transaction Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $i = 0;
                                                            foreach ($transactions as $item) {
                                                                $i++;
                                                                $user_id = $item['user_id'];
                                                                $withdrawal_amount = $item['withdrawal_amount'];
                                                                $status = $item['status'];
                                                                $comment = $item['comment'];
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

                                                                switch ($status) {
                                                                    case 'pending':
                                                                        $status = "<span class='badge bg-warning shadow rounded'>PENDING</span>";
                                                                        break;
                                                                    
                                                                    case 'approved':
                                                                        $status = "<span class='badge bg-success shadow rounded'>PROCESSED</span>";
                                                                        break;
                                                                    
                                                                    case 'rejected':
                                                                        $status = '<span class="badge bg-danger shadow rounded show-pointer" type="button" data-bs-trigger="hover"
                                                                            data-container="body" data-bs-toggle="popover" data-bs-placement="bottom" title="Rejection Reason"
                                                                            data-offset="-20px -20px"
                                                                            data-bs-content="'.$comment.'">
                                                                            <i class="icofont icofont-info-circle f-16"></i>REJECTED
                                                                            </span>';
                                                                        break;
                                                                    
                                                                    default:
                                                                        $status = "<span class='badge bg-info shadow rounded'>N/A</span>";
                                                                        break;
                                                                }

                                                                ?>
                                                                    <tr class="no-wrap">
                                                                        <td><?php echo $i; ?></td>
                                                                        <td>Withdrawal</td>
                                                                        <td class="no-wrap">₹ <?php echo number_format($withdrawal_amount,2); ?></td>
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
                                                            <th>Withdrawal Amount</th>
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
						<h3 class="modal-title">Request Withdrawal From Your Superwallet</h3>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p class="mb-0">
						<form method="POST" action="" id="form_add_record" enctype="multipart/form-data">
                            <input type="hidden" name="transaction_type_txn" id="transaction_type_txn">
							<div class="row justify-content-center align-items-center">
								<div class="mb-3 col-md-12">
                                    <label for="note" class="form-label text-center"><b class="text-primary">Amount available in Superwallet can be used for your self investment purpose OR can be transferred to other Maxizone members.</b></label>
								</div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="fund_available">Available Balance</label>
                                    <input type="text" id="fund_available" class="form-control bg-light-success text-primary" value="₹ <?php echo number_format($fund_available,2); ?>" readonly />                                    
                                </div>
								<div class="mb-3 col-md-6">
                                    <label class="form-label" for="withdrawal_amount">Withdrawal Amount</label>
                                    <!-- (In multiples of 100) -->
                                    <input type="number" class="form-control" id="withdrawal_amount" name="withdrawal_amount" placeholder="Enter Withdrawal Amount" value="" min="1000" step="100" max="<?php echo $fund_total; ?>" required onblur="get_credit(this.id);" onkeyup="get_credit(this.id);" onchange="get_credit(this.id);">
                                </div>
								<div class="mb-3 col-md-12">
                                    <input type="checkbox" name="agree" id="agree">
									<label for="agree" class="form-label" style="display: inline;">I accept the terms while requesting Withdrawal, <span class="text-danger">I will receive the amount within 3-4 working days.</span></label>
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
            var action = "save_withdraw_request";            

            var user_id = '<?php echo $user_id; ?>';
            var fund_available = +<?php echo $fund_available; ?>;

            var withdrawal_amount = document.getElementById("withdrawal_amount");
            var agree = document.getElementById("agree");
            
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

            if (withdrawal_amount_value_trim%100 != 0) {
                withdrawal_amount.focus();
                err_msg = "***Kindly Enter Withdrawal Amount In Multiples Of Rs.100***";
                msg_error.innerHTML = err_msg;
                show_element(msg_error);
                return
            }

            if (withdrawal_amount_value_trim > max_allowed_amount) {
                withdrawal_amount.focus();
                err_msg = "***Kindly Enter Withdrawal Amount Less Than <i>Rs."+max_allowed_amount+"</i>***";
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
                            showNotif('Unable to Process Withdrawal Request as Your ID Is Not Active! Invest Some Amount Before Proceeding','error');
                            break;
                    
                        case 'not_allowed':
                            showNotif('Withdrawal Requests are allowed Only on Friday of Every Week! Please submit your Withdrawal Request on Next Friday','error');
                            break;
                    
                        case 'fund_exceed':
                            showNotif('Entered amount is more than the fund available in your wallets! Please submit Request for accurate amount','error');
                            break;
                    
                        case 'request_pending':
                            showNotif('A withdrawal request is already pending. Kindly Wait For Its Approval Before Proceeding','error');
                            break;
                    
                        case 'success':
                            showNotif('Your Withdrawal Request Is Submitted Successfully.','success');
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
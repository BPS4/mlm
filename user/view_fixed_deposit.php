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

        $header_text = "Transaction Summary For Fixed Deposit";
    // CHECK AVAILABILITY

	// DEFAULT
		$page_id_home = 1;
		$bank_update = $photo_update = $pan_update = $aadhaar_update = 0;
        $msg = $msg_type = "";
		$error = false;
	// DEFAULT

    // GET DATA
        $query = "SELECT `transaction_fixeddeposit`.`user_id`, `transaction_fixeddeposit`.`transaction_type`, `transaction_fixeddeposit`.`transaction_mode`, `transaction_fixeddeposit`.`transaction_amount`, `transaction_fixeddeposit`.`status` AS 'fd_status', `transaction_fixeddeposit`.`create_date` AS 'fd_creation_date', IF(`transaction_fixeddeposit_interest`.`interest_amount` IS NULL,0,`transaction_fixeddeposit_interest`.`interest_amount`) AS 'interest_paid', `transaction_fixeddeposit_interest`.`create_date` AS 'interest_paid_date' FROM `transaction_fixeddeposit` LEFT JOIN `transaction_fixeddeposit_interest` ON `transaction_fixeddeposit_interest`.`fd_id`=`transaction_fixeddeposit`.`id` WHERE `transaction_fixeddeposit`.`user_id`='$user_id' ORDER BY `transaction_fixeddeposit`.`create_date` DESC";
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
                                                    <i class="icofont icofont-ui-add"></i> Create FD Now
                                                </a>
                                            </div>
                                            <div class="card-datatable table-responsive">
                                                <table id="datatables" class="datatables table border-top table-strriped table-hover border-danger table-warning">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr</th>
                                                            <th class="d-none">Transaction Type</th>
                                                            <th>FD Amount</th>
                                                            <th>FD Created On</th>
                                                            <th>FD Status</th>
                                                            <th>Maturity Date</th>
                                                            <th>Interest Paid</th>
                                                            <th>Interest Paid Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $i = 0;
                                                            foreach ($transactions as $item) {
                                                                $i++;
                                                                $user_id = $item['user_id'];
                                                                $txn_type = $item['transaction_type'];
                                                                $transaction_mode = $item['transaction_mode'];
                                                                $transaction_amount = $item['transaction_amount'];
                                                                $status = $item['fd_status'];
                                                                $fd_creation_date = $item['fd_creation_date'];
                                                                $interest_paid = $item['interest_paid'];
                                                                $interest_paid_date = $item['interest_paid_date'];
                                                                
                                                                $maturity_date = date('d-m-Y', strtotime($fd_creation_date.'+365 days'));

                                                                //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT
                                                                    $regEx = '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/';
                                                                    // preg_match($regEx, $details['date'], $result);
                                                                    if (preg_match($regEx, $fd_creation_date) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fd_creation_date)) {
                                                                        $date = date_create($fd_creation_date);
                                                                        $fd_creation_date = date_format($date, "d-M-Y h:iA");
                                                                        $fd_creation_date = date_format($date, "d-M-Y");
                                                                    }
                                                                //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT

                                                                //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT
                                                                    $regEx = '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/';
                                                                    // preg_match($regEx, $details['date'], $result);
                                                                    if (preg_match($regEx, $maturity_date) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $maturity_date)) {
                                                                        $date = date_create($maturity_date);
                                                                        $maturity_date = date_format($date, "d-M-Y h:iA");
                                                                        $maturity_date = date_format($date, "d-M-Y");
                                                                    }
                                                                //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT

                                                                //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT
                                                                    $regEx = '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/';
                                                                    // preg_match($regEx, $details['date'], $result);
                                                                    if (preg_match($regEx, $interest_paid_date) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $interest_paid_date)) {
                                                                        $date = date_create($interest_paid_date);
                                                                        $interest_paid_date = date_format($date, "d-M-Y h:iA");
                                                                        $interest_paid_date = date_format($date, "d-M-Y");
                                                                    }
                                                                //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT

                                                                if ($interest_paid == 0) {
                                                                    $interest_paid_date = "<span class='badge bg-warning shadow rounded' style='font-size:11px;'>ONGOING</span>";
                                                                }
                                                                switch ($txn_type) {
                                                                    case 'fresh':
                                                                        $transaction_type = "<span class='badge bg-info shadow rounded-pill' style='font-size:11px;'>FD Created</span>";
                                                                        break;
                                                                        
                                                                    case 'withdraw':
                                                                        $transaction_type = "<span class='badge bg-danger shadow rounded-pill' style='font-size:11px;'>SuperWallet Transfer</span>";
                                                                        $transaction_date = $update_date = $create_date;
                                                                        break;
                                                                }

                                                                switch ($status) {
                                                                    case 'active':
                                                                        $fd_status = "<span class='badge bg-warning shadow rounded'>ONGOING</span>";
                                                                        break;
                                                                    
                                                                    case 'matured':
                                                                        $fd_status = "<span class='badge bg-success shadow rounded'>MATURED</span>";
                                                                        break;
                                                                    
                                                                    default:
                                                                        $fd_status = "<span class='badge bg-success shadow rounded'>N/A</span>";
                                                                        break;
                                                                }

                                                                ?>
                                                                    <tr class="no-wrap">
                                                                        <td><?php echo $i; ?></td>
                                                                        <td class="d-none"><?php echo $transaction_type; ?></td>
                                                                        <td class="no-wrap">₹ <?php echo number_format($transaction_amount,2); ?></td>
                                                                        <td><?php echo $fd_creation_date; ?></td>
                                                                        <td><?php echo $fd_status; ?></td>
                                                                        <td><?php echo $maturity_date; ?></td>
                                                                        <td>₹ <?php echo number_format($interest_paid,2); ?></td>
                                                                        <td><?php echo $interest_paid_date; ?></td>
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
		<div class="modal fade" id="ModalPay" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">Create FD Now</h3>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p class="mb-0">
						<form method="POST" action="" id="form_add_record" enctype="multipart/form-data">
                            <input type="hidden" name="transaction_type_txn" id="transaction_type_txn">
							<div class="row justify-content-center align-items-center">
								<div class="mb-3 col-md-12">
                                    <label for="company_account" class="form-label"><b class="text-danger">Amount Invested in FD Will Be Blocked For One Year Period. The funds will be available only after the maturity period.</b></label>
								</div>
								<div class="mb-3 col-md-6">
									<label for="fd_source" class="form-label">Source of Investment <span class="text-danger">*</span></label>
									<select name="fd_source" id="fd_source" class="form-control" required>
										<option value="" hidden>Select Source</option>
											<option value="investment_wallet" data-avalable_amount="<?php echo $wallet_investment; ?>" <?php echo ($wallet_investment==0)?'disabled':''; ?>>Investment Wallet {Available: ₹ <?php echo number_format($wallet_investment,2); ?>}</option>
											<option value="super_wallet" data-avalable_amount="<?php echo $superwallet; ?>" <?php echo ($superwallet==0)?'disabled':''; ?>>Super Wallet {Available: ₹ <?php echo number_format($superwallet,2); ?>}</option>
									</select>
								</div>
								<div class="mb-3 col-md-6">
									<label for="transaction_amount" class="form-label">Amount Transfer <span class="text-danger">*</span></label>
									<input type="number" class="form-control" name="transaction_amount" id="transaction_amount" min="0" placeholder="Enter Amount of Investment" required>
								</div>

								<div class="mb-3 col-md-12">
                                    <input type="checkbox" name="agree" id="agree">
									<label for="agree" class="form-label" style="display: inline;">I accept the terms while creating Fixed Deposit that <span class="text-danger">the amount will be blocked for One Year</span> and I will not claim the premature closure of this FD.</label>
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
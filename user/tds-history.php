<!DOCTYPE html>
<html lang="en">

<?php
	// COLLEGE DASHBOARD PAGE
	ob_start();
	require_once("../db_connect.php");
	session_start();

	mysqli_query($conn, "set names 'utf8'");  //-------WORKING UTF8 CODE------//
    // include("kyc_check.php");
	//-------CURRENT DATE AND TIME TO FEED---------//
	date_default_timezone_set('Asia/Kolkata');
	$current_date = date('Y-m-d H:i:s');
	$current_date2 = date('d');
	$current_datetimestart = date('H');
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

        $header_text = "TDS History For $name ($user_id)";
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
$total_withdrawal_amount = 0;
$tds_charges = 0;
$admin_charges = 0;
$total_withdrawal_amount_commission = 0;
$tds_charges_commission = 0;
$admin_charges_commission = 0;


    // GET DATA
        $query = "SELECT * FROM `withdrawal` WHERE `user_id`='$user_id' and create_date >= '2024-04-01' ORDER BY `create_date` DESC";
        $res = mysqli_query($conn,$query);
        $transactions = mysqli_fetch_all($res,MYSQLI_ASSOC);
        // print_r($transactions);
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

    $fund_total = $fund_available = $wallet_roi;
    $fund_total_commission = $fund_available_commission = $wallet_commission;


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
                                        <?php
                                        $header_text = "TDS History Summary For $name ($user_id)";
                                        echo '<div class="card-header border-bottom ps-1 pb-2 mb-3">
                                        <h5 class="card-title mb-1 text-bold">'.$header_text.'</h5></div>';
                                       
                                        ?>
                                            


                                            <div class="card-datatable table-responsive">
                                                <table id="datatables" class="datatables table border-top table-strriped table-hover border-danger table-warning">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr</th>
                                                            <th>Transaction Type</th>
                                                            <th>Transaction Date</th>
                                                            <th>TDS Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $i = 0;
                                                            // print_r($transactions);
                                                            foreach ($transactions as $item) {
                                                                $i++;
                                                                $user_id = $item['user_id'];
                                                                $withdrawal_amount = $item['withdrawal_amount'];
                                                                $admin_charges = $item['admin_charges'];
                                                                if(!$admin_charges) {
                                                                    $percentage = 10;
                                                                    $admin_charges = ($percentage / 100) * $withdrawal_amount;
                                                                }
                                                                $tds = $item['tds'];
                                                                if(!$tds) {
                                                                    $percentage = 5;
                                                                    $tds = ($percentage / 100) * $withdrawal_amount;
                                                                }
                                                                $total_withdraw = $item['total_withdraw'];
                                                                if(!$total_withdraw) {
                                                                    $total_withdraw = $withdrawal_amount - ($admin_charges + $tds);
                                                                }
                                                                $status = $item['status'];
                                                                
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
                                                                    case '2':
                                                                        $status = "<span class='badge bg-warning shadow rounded'>PENDING</span>";
                                                                        break;
                                                                    
                                                                    case '1':
                                                                        $status = "<span class='badge bg-success shadow rounded'>PROCESSED</span>";
                                                                        break;
                                                                    
                                                                    case '0':
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
                                                                        <td>TDS</td>                                                                        
                                                                        <td><?php echo $create_date; ?></td>
                                                                        <td class="no-wrap">₹ <?php echo number_format($item['tds'],2); ?></td>
                                                                    </tr>
                                                                <?php
                                                            }
                                                        ?>                                                    
                                                    </tbody>
                                                    <tfoot class="d-none">
                                                        <tr>
                                                        <th>Sr</th>
                                                            <th>Transaction Type</th>
                                                            <th>Transaction Date</th>
                                                            <th>TDS Amount</th>
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
 
    <?php include("scripts.php"); ?>

    <script>
        // DataTables with Column Search by Text Inputs
            document.addEventListener("DOMContentLoaded", function () {
                //Logic 
                var withdrawal_amount_commission = document.getElementById("withdrawal_amount_commission");
                var admin_charges_commission = document.getElementById("admin_charges_commission");
                var tds_charges_commission = document.getElementById("tds_charges_commission");
                var total_withdrawal_amount_commission = document.getElementById("total_withdrawal_amount_commission");
                // Listen for click event on number field
                withdrawal_amount_commission.addEventListener("click", function() {
                    // Get the value of the number field
                    var withdrawal = parseFloat(withdrawal_amount_commission.value);
                    // If the value is a valid number
                    if (!isNaN(withdrawal)) {
                        admin_charges_commission.value = parseFloat(withdrawal) * 10 / 100;
                        tds_charges_commission.value = parseFloat(withdrawal) * 5 / 100;
                        total_withdrawal_amount_commission.value = parseFloat(withdrawal) - (parseFloat(admin_charges_commission.value) + parseFloat(tds_charges_commission.value));
                    }
                });
                

                //Logic 
                var withdrawal_amount = document.getElementById("withdrawal_amount");
                var admin_charges = document.getElementById("admin_charges");
                var tds_charges = document.getElementById("tds_charges");
                var total_withdrawal_amount = document.getElementById("total_withdrawal_amount");
                // Listen for click event on number field
                withdrawal_amount.addEventListener("click", function() {
                    // Get the value of the number field
                    var withdrawal = parseFloat(withdrawal_amount.value);
                    // If the value is a valid number
                    if (!isNaN(withdrawal)) {
                        admin_charges.value = parseFloat(withdrawal) * 10 / 100;
                        tds_charges.value = parseFloat(withdrawal) * 5 / 100;
                        total_withdrawal_amount.value = parseFloat(withdrawal) - (parseFloat(admin_charges.value) + parseFloat(tds_charges.value));
                    }
                });
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
        var btn_save_commission = document.getElementById("btn_save_commission");
        var btn_saving_commission = document.getElementById("btn_saving_commission");
        var msg_error_commission = document.getElementById("msg_error_commission");
        
        function hide_element(elem) {
            elem.classList.add('d-none')
        }

        function show_element(elem) {
            elem.classList.remove('d-none')
        }
        hide_element(msg_error);
        hide_element(msg_error_commission);
        
        function save_withdraw_request() {
            var action = "save_withdraw_request";            

            var user_id = '<?php echo $user_id; ?>';
            var fund_available = +<?php echo $fund_available; ?>;

            var withdrawal_amount = document.getElementById("withdrawal_amount");
            var admin_charges = document.getElementById("admin_charges");
            var tds_charges = document.getElementById("tds_charges");
            var total_withdrawal_amount = document.getElementById("total_withdrawal_amount");

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

            if (withdrawal_amount_value_trim%1000 != 0) {
                withdrawal_amount.focus();
                err_msg = "***Kindly Enter Withdrawal Amount In Multiples Of Rs.1000***";
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
					admin_charges: admin_charges.value,
					tds_charges: tds_charges.value,
					total_withdrawal_amount: total_withdrawal_amount.value,
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
                            showNotif('Withdrawal Requests are allowed Only on 1st and 16th Day of Every Month Between 10 AM to 4 PM! Please submit your Withdrawal Request on Next Schedule','error');
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
        function save_commission_withdraw_request() {
            var action = "save_commission_withdraw_request";            

            var user_id = '<?php echo $user_id; ?>';
            var fund_available = +<?php echo $fund_available_commission; ?>;

            var withdrawal_amount = document.getElementById("withdrawal_amount_commission");
            var admin_charges_commission = document.getElementById("admin_charges_commission");
            var tds_charges_commission = document.getElementById("tds_charges_commission");
            var total_withdrawal_amount_commission = document.getElementById("total_withdrawal_amount_commission");
            var agree = document.getElementById("agree_commission");
            
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
                msg_error_commission.innerHTML = err_msg;
                show_element(msg_error_commission);
                return
            }

            if (withdrawal_amount_value_trim < 1000) {
                withdrawal_amount.focus();
                err_msg = "***Kindly Enter Withdrawal Amount More Than Rs.1000***";
                msg_error_commission.innerHTML = err_msg;
                show_element(msg_error_commission);
                return
            }

            if (withdrawal_amount_value_trim%1000 != 0) {
                withdrawal_amount.focus();
                err_msg = "***Kindly Enter Withdrawal Amount In Multiples Of Rs.1000***";
                msg_error_commission.innerHTML = err_msg;
                show_element(msg_error_commission);
                return
            }

            if (withdrawal_amount_value_trim > max_allowed_amount) {
                withdrawal_amount.focus();
                err_msg = "***Kindly Enter Withdrawal Amount Less Than <i>Rs."+max_allowed_amount+"</i>***";
                msg_error_commission.innerHTML = err_msg;
                show_element(msg_error_commission);
                return
            }

            if (!agree.checked) {
                agree.focus();
                err_msg = "***Kindly Accept The Terms Checkbox Before Proceeding Further***";
                msg_error_commission.innerHTML = err_msg;
                show_element(msg_error_commission);
                return
            }

            hide_element(msg_error_commission);
            hide_element(btn_save_commission);
            show_element(btn_saving_commission);

            $.ajax({
				type: "POST",
				url: "../ajax.php",
				data: {
					action: action,
					user_id: user_id,
					admin_charges_commission: admin_charges_commission.value,
					tds_charges_commission: tds_charges_commission.value,
					total_withdrawal_amount_commission: total_withdrawal_amount_commission.value,
					withdrawal_amount: withdrawal_amount.value
				},
				
				// data: $("#form_register").serialize() + "&action=" +action , // getting filed value in serialize form And Passing additional Value of 'action' To perform Task Accordingly
				// dataType: 'JSON',
				cache: false,

				success: function(response) {
                    console.log(response);

                    hide_element(btn_saving_commission);
                    show_element(btn_save_commission);

                    switch (response) {
                        case 'not_active':
                            showNotif('Unable to Process Withdrawal Request as Your ID Is Not Active! Invest Some Amount Before Proceeding','error');
                            break;
                    
                        case 'not_allowed':
                            showNotif('Withdrawal Requests are allowed Only on 1st and 16th Day of Every Month Between 10 AM to 4 PM! Please submit your Withdrawal Request on Next Schedule','error');
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
    var admin_charges_commission = 0;
    var tds_charges_commission  = 0;
    var total_withdrawal_amount_commission  = 0;

     function get_credit_commission(amount)
    {
        admin_charges_commission = amount * 10/100;
        tds_charges_commission  = amount * 5/100;
        total_withdrawal_amount_commission  = amount - admin_charges_commission  - tds_charges_commission ; 
        document.cookie = "admin_charges_commission = " + admin_charges_commission;
        
    }
    </script>
   <?php
        $admin_charges_commission = $_COOKIE['admin_charges_commission'];;
        //$admin_charges_commission = "<script>document.write(admin_charges_commission);</script>";
        $tds_charges_commission = "<script>document.write(tds_charges_commission);</script>";
        $total_withdrawal_amount_commission = "<script>document.write(total_withdrawal_amount_commission);</script>";
        ?>
    <script>
    var user_name = "Rohit";
    document.cookie = "name = " + user_name;
    </script>

<?php
    $name= $_COOKIE['name'];
    echo $name;
?>
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
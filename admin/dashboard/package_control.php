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
        
        $msg = $msg_type = "";
		$error = false;

        $file_dir_admindocs= "../../assets/files/admindocs";
    if (isset($_POST['submit_bankdetails'])) {
        $filename_upi_qrcode ="";
        if ((isset($_FILES["upi_qrcode"]) && $_FILES["upi_qrcode"]["error"] == 0)) {
            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "JPG" => "image/jpg", "JPEG" => "image/jpeg", "png" => "image/png", "PNG" => "image/png", "webp" => "image/webp");
            $filename_upi_qrcode = $_FILES["upi_qrcode"]["name"];
            $filetype = $_FILES["upi_qrcode"]["type"];
            $filesize = $_FILES["upi_qrcode"]["size"];

            // Verify file extension
            $ext_bank = pathinfo($filename_upi_qrcode, PATHINFO_EXTENSION);
            
            // Extract FileName
            $file_basename = basename($filename_upi_qrcode, ".$ext_bank");            

            if (!array_key_exists($ext_bank, $allowed)) {
                $error = true;
                $msg .= " >> Only JPEG/JPG/PNG/WEBP Formats are Allowed!!!";
                $msg_type = "error";
            }

            // Verify file size - 500kB maximum
            $minsize = 1 * 1024;
            $maxsize = 150 * 1024;

            if ($filesize > $maxsize || $filesize < $minsize) {
                $error = true;
                $msg .= " >> Error!!! UPI QR Code size should not be greater than 500kb.";
                $msg_type = "error";
            }
        } else {
            $error = true;
            $msg .= " >> UPI QR Code Not Uploaded";
            $msg_type = "error";
        }
        if (!$error) {
            $current_timestamp = time();
            if (isset($filename_upi_qrcode) && $filename_upi_qrcode != "") {
                $uploadedfile = $_FILES["upi_qrcode"]["tmp_name"];
                $filename_upi_qrcode = "$user_id-$current_timestamp".".$ext_bank";
            }
            $file_dir_upi_qrcode = $file_dir_admindocs."/".$filename_upi_qrcode;
            if (
                (isset($file_dir_upi_qrcode)) && 
                (move_uploaded_file($_FILES['upi_qrcode']['tmp_name'], $file_dir_upi_qrcode))
            ) {

                $msg .= "Bank Details ";
                $msg_type = "success";
                // echo "<script>window.close();</script>";
            }

        }
        $user_id = $_POST['user_id'];
        $id = $_POST['id'];
        $bank_name = $_POST['bank_name'];
        $branch_name =$_POST['branch_name'];
        $branch_address =$_POST['branch_address'];
        $account_no =$_POST['account_no'];
        $ifs_code =$_POST['ifs_code'];
        $upi_handle =$_POST['upi_handle'];
        $upi_qrcode=$filename_upi_qrcode;
        $query_update_bankinfo = "UPDATE `users_admin` SET `bank_name`= '$bank_name',`branch_name`='$branch_name',`branch_address`='$branch_address',
        `account_no`='$account_no',`ifs_code`='$ifs_code',`upi_handle`='$upi_handle',`upi_qrcode`='$upi_qrcode' WHERE `id` IN ($id)";
       //  echo $query_update_bankinfo;
         //die;
        mysqli_query($conn,$query_update_bankinfo);
        $count_record_update_doc = mysqli_affected_rows($conn);
       // echo $count_record_update_doc;
    }

 
    // GET WALLET DATA
    //     $query_wallet = "SELECT `id`,`bank_name`, `branch_name`, `branch_address`, `account_no`, `ifs_code`, `upi_handle` , `upi_qrcode` FROM `users_admin` WHERE `user_id`='$user_id'";
    //  //echo $query;
    //     $query = mysqli_query($conn, $query_wallet);        
    //     $res = mysqli_fetch_array($query);
    //     extract($res);
    // // GET WALLET DATA
?>

<head>
    <?php include_once('head.php'); ?>

    <title><?php  echo "Package Control - Admin - Maxizone"; ?></title>

    <link rel="canonical" href="view_bankinfo.php">

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
        #datatables-column-search-text-inputs,
    .card {
      display: block;
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

                    <h1 class="h3 mb-3">Package Control</h1>

                    <div class="row">
                        <div class="col-md-12 col-xl-12">
                          
                        <div class="card">
                                <div class="card-header">
                                    <h3  class=" mb-0">User info</h3>
                                </div>
                                
                          
                               
                               
                                <div class="card-body">
                                    <form id="submit_withdrawal" method="post" enctype="multipart/form-data" >
                                   
                                        <div class="row">                                                    
                                           
                                   
                                        <div class="alert alert-success" id="success_message" role="alert">
                                      
                                        </div>


                                            <div class="mb-3 col-md-6">
                                                <label for="upi_handle" class="form-label">User Id</label>
                                                <input type="text" class="form-control" name="user_id" id="user_id" placeholder="User Id" >
                                                <span id="user_id_err" class="err_mess" style="color:red; font: size 10px;"></span>
                                            </div>	

                                           

                                            <div class="mb-3 col-md-6">
                                                <label for="branch_name" class="form-label">User Name</label>
                                                <input type="text" class="form-control" name="user_name" id="user_name" placeholder="User Name" >
                                            </div>

                                            <div class="mb-3 col-xl-12">
                                                <label for="bank_name" class="form-label">Wallet-Type</label>
                                                <select class="form-control " id="Wallet_Type" name="Wallet_Type" required>
                                                <option value="Select" >Select</option>
                                                    <option value="Investment Wallet" >Investment</option>
                                                    <option value="ROI Wallet" >ROI</option>
                                                    <option value="Comission Wallet" >Commission</option>
                                                   </option>
                                                            
                                                </select>
                                            </div>

                                            <div class="mb-3 col-md-6">
                                                <label for="branch_address" class="form-label">Available Amount</label>
                                                <input type="text" class="form-control" name="Amount" id="Amount" placeholder="Available Amount" readonly >
                                            </div>

                                            <div class="mb-3 col-md-6">
                                                <label for="branch_address" class="form-label">Withdrawal Amount</label>
                                                <input type="text" class="form-control" name="withdrawal_amount" id="withdrawal_amount" placeholder="Withdrawal Amount" >
                                                <span id="withdrawal_amount_err" class="err_mess" style="color:red; font: size 10px;"></span>
                                            </div>
                                          

                                            <div class="mb-3 col-md-6">


                                                <label for="account_no" class="form-label">Deduction</label>
                                                <input type="text" class="form-control" name="Deduction" id="Deduction" placeholder="Deduction" >
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="ifs_code" class="form-label">Net Amount</label>
                                                <input type="text" class="form-control" name="Net_Amount" id="Net_Amount" placeholder="Net Amount" >
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="ifs_code" class="form-label">Balance Amount</label>
                                                <input type="text" class="form-control" name="Balance_Amount" id="Balance_Amount" placeholder="Net Amount" >
                                            </div>
                                           
                                           
                                            <div style="float:right;">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-success" id="submit_withdrawal_button" name="submit_withdrawal">Submit</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                           
                        </div>
                    </div>

                </div>
            </main>

      

            <?php include_once('footer.php'); ?>
        </div>
    </div>

    

    <?php include_once('scripts.php'); ?>

                                <script>
                            $(document).ready(function () {
                                var responseData; // Define responseData in a broader scope
                                var amount; // Define amount variable in a broader scope
                                var deduction; // Define deduction variable in a broader scope

                                // Function to update the Amount input based on the selected option
                                function updateAmount(selectedOption) {
                                    // Get the value of the selected option
                                    var selectedValue = $(selectedOption).val();

                                    // Set the value of the Amount input based on the selected option
                                    if (selectedValue === 'Investment Wallet') {
                                        amount = responseData.wallet_investment;
                                        deduction = 50;
                                    } else if (selectedValue === 'ROI Wallet') {
                                        amount = responseData.wallet_roi;
                                        deduction = 15;
                                    } else if (selectedValue === 'Comission Wallet') {
                                        amount = responseData.wallet_commission;
                                        deduction = 15;
                                    }

                                    $('#Amount').val(amount);

                                    // Event listener for changes in the withdrawal amount input
                                    $('#withdrawal_amount').keyup(function () {
                                        updateDeductions();
                                    });
                                }

                                function updateDeductions() {
                                    // Get the withdrawal amount
                                    var withdrawalAmount = parseFloat($('#withdrawal_amount').val());

                                    if (withdrawalAmount > amount) {
                                        alert('Withdrawal amount cannot exceed available balance.');
                                        return; // Exit the function
                                    }

                                    // Calculate deductedAmount
                                    var deductedAmount = withdrawalAmount * deduction / 100;

                                    // Set Deduction input value
                                    $('#Deduction').val(deductedAmount);

                                    // Calculate Net Amount
                                    var netAmount = withdrawalAmount - deductedAmount;
                                    $('#Net_Amount').val(netAmount);

                                    var balanceAmount = amount-withdrawalAmount;
                                    $('#Balance_Amount').val(balanceAmount);
                                }

                                // Event listener for changes in the select box
                                $('#Wallet_Type').change(function() {
                                    // Call the updateAmount function with the selected option
                                    updateAmount(this);
                                });

                                $('#user_id').keyup(function () {
                                    var user_id = $(this).val();
                                    if (user_id.length === 6) {
                                        // AJAX request
                                        $.ajax({
                                            url: '/mlm/admin/dashboard/withdrawal_ajax.php', // URL of your PHP script
                                            type: 'post',
                                            data: {
                                                package_action: 'user_details', // Action to call user_details function
                                                user_id: user_id
                                            },
                                            success: function (response) {
                                                // Handle response from server
                                                console.log(response);

                                                // Assuming response is the JSON array you received
                                                responseData = response[0]; // Assign responseData
                                                // Set the value of the input box to the value of the "name" field in the response object
                                                $('#user_name').val(responseData.name);

                                                // Call the updateAmount function with the selected option
                                                updateAmount($('#Wallet_Type').get(0));
                                            },
                                            error: function (xhr, status, error) {
                                                // Handle errors
                                                console.error(xhr.responseText);
                                            }
                                        });
                                    }
                                });
                            });
                            </script>


                                    <script>

                                    $('#submit_withdrawal_button').click(function () {

                                        $('.err_mess').text('');
                                        var formData = $('#submit_withdrawal').serialize();
                                        console.log(formData);
                                        // return false();

                                        var user_id = $('#user_id').val();
                                        if (user_id === "") {
                                            $('#user_id_err').text('User ID can not be blank.');
                                            return false();
                                        }

                                        var withdrawal_amount = $('#withdrawal_amount').val();
                                        if( withdrawal_amount == ''){
                                            $('#withdrawal_amount_err').text('withdrawal_amount can not be blank.');
                                            return false();
                                        }

                                     
                                
                                        $.ajax({
                                            url: '/mlm/admin/dashboard/withdrawal_ajax.php', // URL of your PHP script
                                            type: 'post',
                                            data: {
                                                do_action: 'do_action', // Action to call user_details function
                                                formData: formData
                                            },
                                            success: function (response) {
                                                // Handle response from server
                                                console.log(response.message); 
                                                if(response.message){

                                                    showNotif("Withdrawal request submitted successfully.","success");
                                                    // $('#success_message').text(response.message)
                                                    //                     .css('padding', '15px');
                                                    $('#submit_withdrawal')[0].reset();

                                                }

			
        
                                         
                                            },
                                            error: function (xhr, status, error) {
                                                // Handle errors
                                                console.error(xhr.responseText);
                                            }
                                        });
                                    
                                   });

                                 </script>
 
                        </Body>
                        </html>
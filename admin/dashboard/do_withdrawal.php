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
        $query_wallet = "SELECT `id`,`bank_name`, `branch_name`, `branch_address`, `account_no`, `ifs_code`, `upi_handle` , `upi_qrcode` FROM `users_admin` WHERE `user_id`='$user_id'";
     //echo $query;
        $query = mysqli_query($conn, $query_wallet);        
        $res = mysqli_fetch_array($query);
        extract($res);
    // GET WALLET DATA
?>

<head>
    <?php include_once('head.php'); ?>

    <title><?php  echo "Bank Information - Admin - Maxizone"; ?></title>

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

                    <h1 class="h3 mb-3">Do User Withdrawal</h1>

                    <div class="row">
                        <div class="col-md-12 col-xl-12">
                          
                        <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">User info</h5>
                                </div>
                                <div class="card-body">
                                    <form id="submit_bankdetails" method="post" enctype="multipart/form-data" >
                                    <input type="hidden" class="form-control" name="user_id" id="user_id" value="<?php echo $user_id;?>">
                                    <input type="hidden" class="form-control" name="id" id="id" value="<?php echo $id;?>">
                                        <div class="row">                                                    
                                           

                                            <div class="mb-3 col-md-6">
                                                <label for="upi_handle" class="form-label">User Id</label>
                                                <input type="text" class="form-control" name="upi_handle" id="upi_handle" placeholder="User Id" >
                                            </div>	

                                            <div class="mb-3 col-md-6">
                                                <label for="branch_name" class="form-label">User Name</label>
                                                <input type="text" class="form-control" name="branch_name" id="branch_name" placeholder="User Name" value="">
                                            </div>

                                            <div class="mb-3 col-xl-6">
                                                <label for="bank_name" class="form-label">Wallet</label>
                                                <select class="form-control " id="bank_name" name="bank_name" required>
                                                    <option value="" >Investment</option>
                                                    <option value="" >ROI</option>
                                                    <option value="" >Commission</option>
                                                   </option>
                                                            
                                                </select>
                                            </div>

                                            <div class="mb-3 col-md-6">
                                                <label for="branch_address" class="form-label">Amount</label>
                                                <input type="text" class="form-control" name="branch_address" id="branch_address" placeholder="Amount" >
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="account_no" class="form-label">Deduction</label>
                                                <input type="text" class="form-control" name="account_no" id="account_no" placeholder="Deduction" >
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="ifs_code" class="form-label">Net Amount</label>
                                                <input type="text" class="form-control" name="ifs_code" id="ifs_code" placeholder="Net Amount" >
                                            </div>
                                           
                                           
                                            <div style="float:right;">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success" id="submit_bankdetails" name="submit_bankdetails">Submit</button>
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

    <?php include_once('scripts.php'); ?>

                                                    </Body>
                                                    </html>
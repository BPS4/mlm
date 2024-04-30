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
        if (isset($_GET['uid']) && isset($_GET['l'])) {
            $user_id = json_decode(base64_decode($_GET['uid']));    //--DECODE THE SECRET CODE PASSED--//
            $user_id_encode = base64_encode(json_encode($user_id));     //ENCODE
            
            // $level = json_decode(base64_decode($_GET['l']));    //--DECODE THE SECRET CODE PASSED--//
            $level = $_GET['l']; 
            $level_encode = base64_encode(json_encode($level));     //ENCODE
            
        } else {
            // echo "<script>alert('Required Parameters Not Passed!!!');</script>";
            // echo "Redirecting...Please Wait";
            // header("Refresh:1, url=../dashboard");
            // exit;
        }
    // CHECK AVAILABILITY    

    function get_level_members($conn,$level,$user_id_member,$package_amount) {
        $query_get_data = "SELECT `sponsor_id`,`name`,`packages` FROM `users` WHERE `user_id`='$user_id_member'";
        $res = mysqli_query($conn, $query_get_data);
        if ($row = mysqli_fetch_array($res)) {
            $response['sponsor_id'] = $row['sponsor_id'];
            $response['name'] = $row['name'];
            $response['packages'] = $row['packages'];
            $response['level'] = $level;
            
            $sponsor_id = $row['sponsor_id'];
            // STRING CONTAINS
            if (strpos($row['packages'], $package_amount) !== false) {
                // CONTAINS >> TRUE
            } else {
                $sponsor_id = get_level_members($conn,$level,$sponsor_id,$package_amount);
            }
            return($sponsor_id);
        }
    }    

    $show_modal = 0;
    $msg = $msg_type = "";
    $error = false;
    
    function get_user_name($conn,$user_id_member) {
        $query_get_data = "SELECT `name` FROM `users` WHERE `user_id`='$user_id_member'";
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

    if (isset($_POST['status'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $status = ($is_active == 0 ? 1 : 0);
        $user_status = ($is_active == 0 ? "active" : "inactive");
        $msg_type = ($is_active == 0 ? "success" : "danger");
        $msg_done = ($is_active == 0 ? "Activated" : "Deactivated");

        $query_status = "SELECT `status` AS 'user_status_current', `status_old` FROM `users` WHERE `id`='$id'";
        $query_status = mysqli_query($conn,$query_status);
        $res = mysqli_fetch_array($query_status);
        extract($res);

        // if ($status_old == "active" && $user_status_current == "active") {
        //     $user_status = ($is_active == 0 ? "active" : "inactive");
        // }

        if (mysqli_query($conn, "UPDATE `users` SET `is_active`='$status', `status_old`='$user_status_current', `status`='$user_status', `update_date`='$current_date' WHERE `id`='$id'")) {
            $msg = " >> User ID $msg_done Successfully!";
            // $msg_type = "danger";
            stop_form_resubmit();
        }
    }

    error_occurred:
	$query = "SELECT `id`, `sponsor_id`, `user_id`, `user_rank`, `name`, `fhname`, `mobile`, `email`, `whatsapp`,`aadhaar`, `aadhaar_file`, `pan`, `pan_file`, `bank_name`, `branch_name`, `account_no`, `ifs_code`, `upi_handle`, `address`, `city`, `state`, `pin_code`, `fund_wallet`, `fund_commission`, `fund_total`, `status`, `is_bank_updated`, `level1_member`, `level2_member`, `level3_member`, `level4_member`, `level5_member`, `level1_member_active`, `level2_member_active`, `level3_member_active`, `level4_member_active`, `level5_member_active`, `is_active`, `password`, `kyc_status`, `create_date`, `active_date` FROM `users` WHERE `delete_date` IS NULL ORDER BY `create_date` DESC";
    // $query = mysqli_query($conn, "SELECT `sponsor_id`,`user_id`,`password`,`name`,`mobile`,`email`,`create_date` FROM `users` WHERE `packages` LIKE '%$package_amount%' ORDER BY `create_date` DESC");
    $query = mysqli_query($conn, $query);
    $members = mysqli_fetch_all($query, MYSQLI_ASSOC);
    $total_count = mysqli_num_rows($query);

    $sponsor_name = get_user_name($conn,$user_id);

    // USED FOR CENTER THE TEXT PDF EXPORT
    echo "<input type='hidden' id='total_count' value='$total_count'>";

    // DEFAULT ORDER COLUMN SR
        $default_Order = 0;

    // LENGTH MENU
    // $length_menu = "all";

    function get_active_investment($user_id) {
		$conn = $GLOBALS['conn'];
		$query = "SELECT `wallet_investment` FROM `wallets` WHERE `user_id`='$user_id'";
		$res = mysqli_query($conn,$query);
		$res = mysqli_fetch_array($res);
		extract($res);
		return $wallet_investment;
	}
?>

<head>
    <?php include_once('head.php'); ?>

    <title><?php  echo "Members List - Admin - Maxizone"; ?></title>

    <link rel="canonical" href="view_member.php">

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

        .text-red {
            color: red;
            font-weight: bold;
            white-space: nowrap;
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
                    <div class="row mb-2 mb-xl-3">
                        <div class="col-auto">
                            <h3>Members List - Maxizone</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div id="loader"></div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Total Users <span class="badge bg-success" style="font-weight: bold;"><?php echo $total_count; ?></span></h5>
                                    <span style="position:absolute;left:0px;margin-left:15px;">
                                        <a href='./' class="badge bg-warning fs-100" style="text-decoration:none;">
                                            Dashboard
                                        </a>
                                        <b>
                                            <i class="align-middle" data-feather="chevrons-right"></i>
                                        </b>
                                        <a href="view_member_total.php" class="badge bg-success fs-100" style="text-decoration:none;">
                                            Member List
                                        </a>
                                        <b>
                                            <i class="align-middle" data-feather="chevrons-right"></i>
                                        </b>
                                        <a href="view_member.php" class="badge bg-info fs-100" style="text-decoration:none;">
                                            Pending Member List
                                        </a>
                                    </span>
                                    <a href="../../user/register.php" target="_blank" type="button" class="badge btn btn-success my-1" style="text-decoration:none;position:absolute;right:0px;margin:15px;">
                                        <i class="align-middle" data-feather="user-plus" style="border-radius: 50%;margin-right:5px;"></i>
                                        Register New Member
                                    </a>
                                </div>
                                <div class="card-body">

                                    <table id="datatables" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Sr</th>
                                                <th class="no_print">Action</th>
                                                <th>Sponsor ID</th>
                                                <th>Sponsor</th>
                                                <th>User ID</th>
                                                <th>Name</th>
                                                <th>Mobile</th>
                                                <th>Email</th>
                                                <th>Password</th>
                                                <th>Father/Husband</th>
                                                <th>Whatsapp</th>
                                                <th>Aadhaar</th>
                                                <th>PAN</th>
                                                <th>Bank</th>
                                                <th>Branch</th>
                                                <th>IFSC</th>
                                                <th>A/c No.</th>
                                                <th>Investment</th>
                                                <th>Address</th>
                                                <th>KYC</th>
                                                <!-- <th>Fund Wallet</th>
                                                <th>Fund Commission</th>
                                                <th>Fund Total</th>
                                                <th>Level1</th>
                                                <th>Level2</th>
                                                <th>Level3</th>
                                                <th>Level4</th>
                                                <th>Level5</th> -->
                                                <th>Joining Date</th>
                                                <th>Activation Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                            <?php
                                            $i = 0;
                                            // $disabled_option1 = $disabled_option2 = $disabled_option3 = $disabled_option4 = $disabled_option5 = "class='bg-danger text-white' disabled";
                                            $disabled_option1 = $disabled_option2 = $disabled_option3 = $disabled_option4 = $disabled_option5 = "";
                                            $disabled_option4 = $disabled_option5 = "class='bg-danger text-white' disabled";
                                            $count_member = 0;
                                            foreach ($members as $member) {
                                                $i++;
                                                //DEFAULT VALUES SET
                                                $classStatus = 'bg-success';

                                                $id = $member['id'];
                                                $sponsor_id_member = $member['sponsor_id'];
                                                $user_id_member = $member['user_id'];
			                                    $member_id_encode = base64_encode(json_encode($user_id_member));     //ENCODE
                                                
                                                if ($user_id_member == "maxizone") {
                                                    $sponsor_id_member = "maxizone";
                                                }

                                                $sponsor = get_user_name($conn,$sponsor_id_member);

                                                $user_rank_member = $member['user_rank'];
                                                $name_member = $member['name'];
                                                $fhname_member = $member['fhname'];
                                                $mobile_member = $member['mobile'];
                                                $whatsapp_member = $member['whatsapp'];
                                                $email_member = $member['email'];
                                                $aadhaar_member = $member['aadhaar'];
                                                $aadhaar_file_member = $member['aadhaar_file'];
                                                $pan_member = $member['pan'];
                                                $pan_file_member = $member['pan_file'];
                                                $bank_name_member = $member['bank_name'];
                                                $branch_name_member = $member['branch_name'];
                                                $account_no_member = $member['account_no'];
                                                $ifs_code_member = $member['ifs_code'];
                                                $upi_handle_member = $member['upi_handle'];
                                                $address_member = $member['address'];
                                                $city_member = $member['city'];
                                                $state_member = $member['state'];
                                                $pin_code_member = $member['pin_code'];
                                                $fund_wallet_member = $member['fund_wallet'];
                                                $fund_commission_member = $member['fund_commission'];
                                                $fund_total_member = $member['fund_total'];
                                                $status_member = $member['status'];
                                                $is_bank_updated_member = $member['is_bank_updated'];

                                                $is_active = $member['is_active'];
                                                $kyc_status = $member['kyc_status'];
                                                $create_date_member = $member['create_date'];
                                                $active_date = $member['active_date'];
                                                $password = $member['password'];

                                                $investment = get_active_investment($user_id_member);

                                                //CHECK DATE FOR YYYY-MM-DD FORMAT
                                                    if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $create_date_member)) {
                                                        $create_date_member = date_create($create_date_member);
                                                        $create_date_member = date_format($create_date_member, "d-M-Y");
                                                    }
                                                //CHECK DATE FOR YYYY-MM-DD FORMAT
                                                
                                                //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT
                                                    $regEx = '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/';
                                                    // preg_match($regEx, $details['date'], $result);
                                                    if (preg_match($regEx, $create_date_member) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $create_date_member)) {
                                                        $create_date_member = date_create($create_date_member);
                                                        // $create_date_member = date_format($create_date_member, "d M Y h:ia");
                                                        $create_date_member = date_format($create_date_member, "d-M-Y");
                                                    }
                                                    if (preg_match($regEx, $active_date) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $active_date)) {
                                                        $date = date_create($active_date);
                                                        $active_date = date_format($date, "d-M-Y");
                                                    } else {
                                                        $active_date = "NOT ACTIVATED";
                                                    }
                                                //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT
                                                
                                                // $classStatus = "bg-info";
                                                // $count_member++;
                                                // $disabled_option4 = "class='bg-danger text-white' disabled";
                                                
                                                $classStatusWallet = "bg-success";
                                                $classStatusCommission = "bg-warning";
                                                $classStatusTotal = "bg-info";

                                                if ($address_member != "") {
                                                    $address = "$address_member, $city_member, $state_member - $pin_code_member";
                                                    $address_class = "";
                                                } else {
                                                    $address = "<span class='badge bg-danger'>NOT UPDATED</span>";
                                                    $address_class = "has-html";
                                                }

                                                if ($kyc_status == "pending") {
                                                    $kyc_status = "<span class='badge bg-danger'>PENDING</span>";
                                                } else {
                                                    $kyc_status = "<span class='badge bg-success'>APPROVED</span>";
                                                }

                                                $classStatus = ($is_active == 0) ? "bg-danger" : "bg-success";

                                                $fhname_class = $aadhaar_class = $whatsapp_class = $pan_class = $bank_name_class = $branch_name_class = $account_no_class = $ifs_code_class = "";

                                                if ($fhname_member == "") {
                                                    $fhname_member = "<span class='text-red'>N/A</span>";
                                                    $fhname_class = "has-html text-red";
                                                }

                                                if ($aadhaar_member == "") {
                                                    $aadhaar_member = "<span class='text-red'>N/A</span>";
                                                    $aadhaar_class = "has-html text-red";
                                                }
                                                
                                                if ($whatsapp_member == "") {
                                                    $whatsapp_member = "<span class='text-red'>N/A</span>";
                                                    $whatsapp_class = "has-html text-red";
                                                }

                                                if ($pan_member == "") {
                                                    $pan_member = "<span class='text-red'>N/A</span>";
                                                    $pan_class = "has-html text-red";
                                                }

                                                if ($bank_name_member == "") {
                                                    $bank_name_member = "<span class='text-red'>N/A</span>";
                                                    $bank_name_class = "has-html text-red";
                                                }

                                                if ($branch_name_member == "") {
                                                    $branch_name_member = "<span class='text-red'>N/A</span>";
                                                    $branch_name_class = "has-html text-red";
                                                }

                                                if ($account_no_member == "") {
                                                    $account_no_member = "<span class='text-red'>N/A</span>";
                                                    $account_no_class = "has-html text-red";
                                                }

                                                if ($ifs_code_member == "") {
                                                    $ifs_code_member = "<span class='text-red'>N/A</span>";
                                                    $ifs_code_class = "has-html text-red";
                                                }
                                            ?>
                                                <!-- REPEAT ITEM -->
                                                <tr>
                                                    <td class="text-center"><?php echo $i; ?></td>
                                                    <td class="has-html text-center no_print">
                                                        <span class="badge bg-info" style="white-space: normal;">
                                                            <a class="text-white" href="view_member_detail.php?<?php echo "uid=$user_id_encode&mid=$member_id_encode";?>"><i class="align-middle me-1" data-feather="eye"></i>View</a>
                                                        </span>
                                                        <span class="badge <?php echo $classStatus; ?>" style="white-space: normal;">
                                                            <form action="" method="POST">
                                                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                                <input type="hidden" name="is_active" value="<?php echo $is_active; ?>">
                                                                <input type="submit" name="status" value="<?php echo ($is_active) ? "Active" : "InActive"; ?>" style="background: transparent; border-style:hidden; color:white;">
                                                            </form>
                                                        </span>

                                                        <a onclick="go_to_member('<?php echo $user_id_member; ?>')" style="text-decoration: none;">
                                                            <span class="badge bg-warning">
                                                                <i class="align-middle text- success" data-feather="log-in"></i> Open
                                                            </span>
                                                        </a>
                                                    </td>
                                                    <td class="text-center"><?php echo $sponsor_id_member; ?></td>
                                                    <td class="text-start"><?php echo $sponsor; ?></td>
                                                    <td class="has-html text-center" onclick='copyToClipboard("<?php echo "$user_id_member"; ?>")' style="cursor:pointer;" title='Click to copy'>
                                                        <span class="badge rounded-pill alert-success text-uppercase text-bold text-dark" style="white-space: normal;">
                                                            <?php echo $user_id_member; ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-start"><?php echo $name_member; ?></td>                                                    
													<!-- <td class="text-center" onclick='copyToClipboard("<?php echo "$user_rank_member"; ?>")' style="cursor:pointer;" title='Click to copy'>
                                                        <span class="badge bg-info text-uppercase" style="white-space: normal;">
                                                            <?php echo $user_rank_member; ?>
                                                        </span>
                                                    </td> -->
                                                    <td class="text-center"><?php echo $mobile_member; ?></td>
                                                    <td class="text-start"><?php echo $email_member; ?></td>
                                                    <td class="has-html text-center" onclick='copyToClipboard("<?php echo "$password"; ?>")' style="cursor:pointer;" title='Click to copy'>
                                                        <span class="badge rounded-pill alert-info text-bold text-dark" style="white-space: normal;">
                                                            <?php echo $password; ?>
                                                        </span>
                                                    </td>
                                                    <td class="<?php echo $fhname_class; ?> text-start"><?php echo $fhname_member; ?></td>
                                                    <td class="<?php echo $whatsapp_class; ?> text-center"><?php echo $whatsapp_member; ?></td>
                                                    <td class="<?php echo $aadhaar_class; ?> text-center"><?php echo $aadhaar_member; ?></td>
                                                    <td class="<?php echo $pan_class; ?> text-center"><?php echo $pan_member; ?></td>
                                                    <td class="<?php echo $bank_name_class; ?> text-start"><?php echo $bank_name_member; ?></td>
                                                    <td class="<?php echo $branch_name_class; ?> text-start"><?php echo $branch_name_member; ?></td>
                                                    <td class="<?php echo $ifs_code_class; ?> text-center"><?php echo $ifs_code_member; ?></td>
                                                    <td class="<?php echo $account_no_class; ?> text-center"><?php echo $account_no_member; ?></td>
                                                    <td class="has-html text-center">
                                                        <span class="badge rounded alert-success text-uppercase text-bold text-dark" style="white-space: normal;">Rs. <?php echo $investment; ?></span>
                                                    </td>
                                                    <td class="<?php echo $address_class; ?> text-start"><?php echo $address; ?></td>
                                                    <td class="has-html text-center"><?php echo $kyc_status; ?></td>
                                                    <td class="text-center" style="white-space: nowrap;"><?php echo $create_date_member; ?></td>
                                                    <td class="text-center" style="white-space: nowrap;"><?php echo $active_date; ?></td>
                                                </tr>

                                            <?php
                                            }

                                            ?>
                                        </tbody>
                                        <tfoot class="no_print">
                                            <tr>
                                                <th>Sr</th>
                                                <th class="no_print">Action</th>
                                                <th>Sponsor ID</th>
                                                <th>Sponsor</th>
                                                <th>User ID</th>
                                                <th>Name</th>
                                                <th>Mobile</th>
                                                <th>Email</th>
                                                <th>Password</th>
                                                <th>Father/Husband</th>
                                                <th>Whatsapp</th>
                                                <th>Aadhaar</th>
                                                <th>PAN</th>
                                                <th>Bank</th>
                                                <th>Branch</th>
                                                <th>IFSC</th>
                                                <th>A/c No.</th>
                                                <th>Investment</th>
                                                <th>Address</th>
                                                <th>KYC</th>
                                                <!-- <th>Fund Wallet</th>
                                                <th>Fund Commission</th>
                                                <th>Fund Total</th>
                                                <th>Level1</th>
                                                <th>Level2</th>
                                                <th>Level3</th>
                                                <th>Level4</th>
                                                <th>Level5</th> -->
                                                <th>Joining Date</th>
                                                <th>Activation Date</th>
                                            </tr>
                                        </tfoot>
                                    </table>

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
        // DataTables with Column Search by Text Inputs
            document.addEventListener("DOMContentLoaded", function () {
                // DataTables
                var $filename = document.title;
                var default_Order = '<?php echo $default_Order;?>';
                var length_menu = '<?php echo isset($length_menu)?$length_menu:"";?>';
                var table = $('#datatables').DataTable({
                    dom: /*'lBfrtip'*/ 'lBfrtip',
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

                            // EXPORT SELECT VALUES IN DATATABLE AND STRIP OUT HTML TAGS
                                format: {
                                    body: function (data, row, column, node) {
                                        // if it is select
                                            // if (column == 9) {
                                            //     data = $(data).find("option:selected").text()
                                            // }
                                        // STRIP OUT HTML TAGS
                                            // console.log(node.classList.contains("has-html"));
                                            // CUSTOMIZE ELEMENT BY CLASS NAME
                                            if(hasClass(node,"has-html")) {
                                                var column_has_class = column;
                                                if (column == column_has_class) {
                                                    data = $(data).text();
                                                }
                                            }
                                            // if ((column >= 2 && column <=3) || (column >= 8 && column <=9)) {
                                            //     // console.log($(data).find('span').textContent + "");
                                            //     // data = $(data).find('span').innerText
                                            //     data = $(data).text();
                                            // }
                                        // REPLACE <BR> WITH NEW LINE
                                            // if (column == 7) {
                                            //     data = data.replace( /<br\s*\/?>/ig, ";" );
                                            // }

                                        // EXCEL EXPORT NUMBER MORE THAN 15 DIGITS (LAST DIGIT 0) >> ISSUE SOLVED BY CONVERTING NO. TO STRING
                                            data = '\u200C'+data; //will cast the number to string.
                                        // EXCEL EXPORT NUMBER MORE THAN 15 DIGITS (LAST DIGIT 0) >> ISSUE SOLVED BY CONVERTING NO. TO STRING
                                            data = data.replace(/^\s+|\s+$/gm,'');
                                        return data
                                    }
                                }
                        },

                        // EXCEL EXPORT NUMBER MORE THAN 15 DIGITS (LAST DIGIT 0) >> ISSUE SOLVED BY CONVERTING NO. TO STRING
                            customizeData: function (data) {
                                var ind = data.header.indexOf("Passworad"); // This code is to find the column name's index which you want to cast.
                                for (var i = 0; i < data.body.length; i++) {
                                    data.body[i][ind] = '\u200C' + data.body[i][ind]; //will cast the number to string.
                                }
                            },
                        // EXCEL EXPORT NUMBER MORE THAN 15 DIGITS (LAST DIGIT 0) >> ISSUE SOLVED BY CONVERTING NO. TO STRING
                        
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

                            // EXPORT SELECT VALUES IN DATATABLE AND STRIP OUT HTML TAGS
                                format: {
                                    body: function (data, row, column, node) {
                                        // if it is select
                                            // if (column == 9) {
                                            //     data = $(data).find("option:selected").text()
                                            // }
                                        // STRIP OUT HTML TAGS
                                            // console.log(node.classList.contains("has-html"));
                                            // CUSTOMIZE ELEMENT BY CLASS NAME
                                            if(hasClass(node,"has-html")) {
                                                var column_has_class = column;
                                                if (column == column_has_class) {
                                                    data = $(data).text();
                                                }
                                            }
                                            // if ((column >= 2 && column <=3) || (column >= 8 && column <=9)) {
                                            //     // console.log($(data).find('span').textContent + "");
                                            //     // data = $(data).find('span').innerText
                                            //     data = $(data).text();
                                            // }
                                        // REPLACE <BR> WITH NEW LINE
                                            // if (column == 7) {
                                            //     data = data.replace( /<br\s*\/?>/ig, "\n" );
                                            // }
                                        return data
                                    }
                                }
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
                            // var countRows = $('#datatables tr').length;
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
                    // {
                    //     extend: 'colvis',
                    //     text: 'COLUMNS',
                    //     className: 'btn-info',
                    //     key: { // press L for COLUMNS
                    //         key: 'l',
                    //         altKey: false
                    //     },
                    //     postfixButtons: [{
                    //         extend: 'colvisRestore',
                    //         text: 'Show All'
                    //     }]
                    // },
                    ],
                    "columnDefs": [{
                        // //   DISABLE ORDERING OF ACTION COLUMN
                        // "targets": [1],
                        // // DISABLE SORTING ON LAST COLUMN
                        "targets": [-1],
                        "orderable": false,
                    },],

                    // Row Callback for Customization of Row
                    // 'rowCallback': function(row, data, index){
                    //     if(data[3]> 11.7){
                    //         $(row).find('td:eq(3)').css('color', 'red');
                    //     }
                    //     if(data[2].toUpperCase() == 'EE'){
                    //         $(row).find('td:eq(2)').css('color', 'blue');
                    //     }
                    // },
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

        function hasClass( elem, klass ) {
            // https://stackoverflow.com/questions/10960573/what-is-the-best-way-to-check-if-element-has-a-class
            return (" " + elem.className + " " ).indexOf( " "+klass+" " ) > -1;
        }
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
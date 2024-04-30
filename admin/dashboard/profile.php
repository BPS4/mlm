<!DOCTYPE html>
<html lang="en">
<link rel="shortcut icon" href="../../assets/images/favicon.svg" type="image/x-icon">
<?php
	// COLLEGE DASHBOARD PAGE
	ob_start();
	require_once("../../db_connect.php");
	session_start();

	mysqli_query($conn, "set names 'utf8'");  //-------WORKING UTF8 CODE------//

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
		$sponsor_id = $_SESSION['sponsor_id_admin'];
		$session_id = $_SESSION['session_id_admin'];
		$name = $_SESSION['name_admin'];    //DECODE
		$name_encode = base64_encode(json_encode($name));     //ENCODE

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

	//header("Location: view_college_list.php?name=$name_encode&batch=2021");

	// COUNTING
	$page_id_home = 1;
	$batch = "2021";
	
	// $count_college = mysqli_num_rows(mysqli_query($conn, "SELECT `college_code` FROM `users` WHERE 1 "));
	// $count_school = mysqli_num_rows(mysqli_query($conn, "SELECT `udise_code` FROM `schools` WHERE `verified`=1 AND `allowed_internship`=1 "));
	// $count_trainee = mysqli_num_rows(mysqli_query($conn, "SELECT `district_id` FROM `trainee` WHERE `batch`='$batch' AND `college_code` IS NOT NULL "));
	// if ($rw = mysqli_fetch_array(mysqli_query($conn, "SELECT `visits` FROM `counter` WHERE `page_id`='$page_id_home' "))) {
	// 	$count_visitor = $rw['visits'];
	// }
	
	// DEFAULT ORDER COLUMN SR
	$default_Order = 1;

	$msg=$msg_type="";
	if (isset($_POST['submit'])) {
		$member_user_id = mysqli_real_escape_string($conn, $_POST['member_user_id']);
		$amount_package = mysqli_real_escape_string($conn, $_POST['amount_package']);

		//FOR SELECT QUERIES
		$query_confirm_purchase = "SELECT `user_id` FROM `topup` WHERE `for_user_id`='$member_user_id' AND `amount_package`='$amount_package' ";
		$res = mysqli_query($conn, $query_confirm_purchase);
		$rows_available = mysqli_num_rows($res);

		if($rows_available==0){
			$query = "INSERT INTO `topup`(`user_id`, `for_user_id`, `amount_package`, `create_date`) VALUES ('$user_id', '$member_user_id', '$amount_package', '$current_date')";
			if(mysqli_query($conn, $query)) {
				// TOPUP TXN INSERT
					$amount_package_dr = $amount_package*-1;
					$query_txn = "INSERT INTO `fund_transaction`(`user_id`, `amount`, `trans_type`, `trans_id`, `status`, `create_date`) VALUES ('$user_id', '$amount_package_dr', 'DR', 'TOPUP', '1', '$current_date')";
					mysqli_query($conn, $query_txn);

				//FUND AVAILABLE UPDATE
					$query_udate_fund = "UPDATE `users` SET `fund_available`=`fund_available`-'$amount_package', `update_date`='$current_date' WHERE `id`='$user_id'";
					mysqli_query($conn, $query_udate_fund);
					$rows_affected = mysqli_affected_rows($conn);

				if($rows_affected==1) {
					$query_300 = mysqli_query($conn,"SELECT `for_user_id` FROM `topup` WHERE `amount_package`='300'");
					$query_1500 = mysqli_query($conn,"SELECT `for_user_id` FROM `topup` WHERE `amount_package`='1500'");

					$package300 = mysqli_fetch_all($query_300, MYSQLI_ASSOC);
					$count300 = mysqli_num_rows($query_300);

					$package1500 = mysqli_fetch_all($query_1500, MYSQLI_ASSOC);
					$count1500 = mysqli_num_rows($query_1500);

					$distribute300 = $amount_package * 0.38 * 0.02;
					$distribute1500 = $amount_package * 0.38 * 0.06;

					if($count300>0){
						$amount300 = $distribute300/$count300;
						$amount_cashback300 = round($amount300, 2);

						foreach ($package300 as $item) {
							$member_id = $item['for_user_id'];
	
							$query_txn = "INSERT INTO `fund_transaction`(`user_id`, `amount`, `trans_type`, `trans_id`, `status`, `create_date`) VALUES ('$member_id', '$amount_cashback300', 'CR', 'CASHBACK', '1', '$current_date')";
							mysqli_query($conn, $query_txn);
							//FUND AVAILABLE UPDATE AFTER CASHBACK
								$query_udate_fund = "UPDATE `users` SET `fund_available`=`fund_available`+'$amount_cashback300', `update_date`='$current_date' WHERE `id`='$member_id'";
								mysqli_query($conn, $query_udate_fund);
						}
					}
					
					if($count1500>0){
						$amount1500 = $distribute1500/$count1500;
						$amount_cashback1500 = round($amount1500, 2);

						foreach ($package1500 as $item) {
							$member_id = $item['for_user_id'];
	
							$query_txn = "INSERT INTO `fund_transaction`(`user_id`, `amount`, `trans_type`, `trans_id`, `status`, `create_date`) VALUES ('$member_id', '$amount_cashback1500', 'CR', 'CASHBACK', '1', '$current_date')";
							mysqli_query($conn, $query_txn);
							//FUND AVAILABLE UPDATE AFTER CASHBACK
								$query_udate_fund = "UPDATE `users` SET `fund_available`=`fund_available`+'$amount_cashback1500', `update_date`='$current_date' WHERE `id`='$member_id'";
								mysqli_query($conn, $query_udate_fund);

							// LEVEL 1 ID FETCH
								// 2%
								$id_level1 = fetch_user_id($conn,$member_id); //ID OR NONE
								$amount_level1 = $amount_cashback1500 * 0.02;
								$amount_level1 = round($amount_level1, 2);
																
							// LEVEL 2 ID FETCH
								// 1%
								if($id_level1!='NONE'){
									$id_level2 = fetch_user_id($conn,$id_level1); //ID OR NONE
									
									$amount_level2 = $amount_cashback1500 * 0.01;
									$amount_level2 = round($amount_level2, 2);

									$amount_level1_dr = $amount_level1*-1;
									$query_txn = "INSERT INTO `fund_transaction`(`user_id`, `amount`, `trans_type`, `trans_id`, `status`, `create_date`) VALUES ('$member_id', '$amount_level1_dr', 'DR', 'CASHBACK', '1', '$current_date')";
									mysqli_query($conn, $query_txn);
									$query_txn = "INSERT INTO `fund_transaction`(`user_id`, `amount`, `trans_type`, `trans_id`, `status`, `create_date`) VALUES ('$id_level1', '$amount_level1', 'CR', 'CASHBACK', '1', '$current_date')";
									mysqli_query($conn, $query_txn);
									//FUND AVAILABLE UPDATE AFTER CASHBACK
										$query_udate_fund = "UPDATE `users` SET `fund_available`=`fund_available`-'$amount_level1', `update_date`='$current_date' WHERE `id`='$member_id'";
										mysqli_query($conn, $query_udate_fund);
										$query_udate_fund = "UPDATE `users` SET `fund_available`=`fund_available`+'$amount_level1', `update_date`='$current_date' WHERE `id`='$id_level1'";
										mysqli_query($conn, $query_udate_fund);
									
										
									if($id_level2!='NONE'){
										$amount_level2_dr = $amount_level2*-1;
										$query_txn = "INSERT INTO `fund_transaction`(`user_id`, `amount`, `trans_type`, `trans_id`, `status`, `create_date`) VALUES ('$member_id', '$amount_level2_dr', 'DR', 'CASHBACK', '1', '$current_date')";
										mysqli_query($conn, $query_txn);
										$query_txn = "INSERT INTO `fund_transaction`(`user_id`, `amount`, `trans_type`, `trans_id`, `status`, `create_date`) VALUES ('$id_level2', '$amount_level2', 'CR', 'CASHBACK', '1', '$current_date')";
										mysqli_query($conn, $query_txn);
										//FUND AVAILABLE UPDATE AFTER CASHBACK
											$query_udate_fund = "UPDATE `users` SET `fund_available`=`fund_available`-'$amount_level2', `update_date`='$current_date' WHERE `id`='$member_id'";
											mysqli_query($conn, $query_udate_fund);
											$query_udate_fund = "UPDATE `users` SET `fund_available`=`fund_available`+'$amount_level2', `update_date`='$current_date' WHERE `id`='$id_level2'";
											mysqli_query($conn, $query_udate_fund);
									}

								}
						}
					}
					
					$msg = "Topup Done Successfully!";
					$msg_type="success";
				}
			} else {
				$msg = "ID is Active For Package-$amount_package Already! Try Another ID or Package...";
				$msg_type="danger";
			}
		} else {
			$msg = "ID is Active Already! Try Another ID...";
			$msg_type="danger";
		}
	}

	if ($rw = mysqli_fetch_array(mysqli_query($conn, "SELECT `fund_available`, SUM(`amount`) AS 'amount_cashback' FROM `users` INNER JOIN `fund_transaction` ON `fund_transaction`.`user_id`=`users`.`id` WHERE `users`.`id`='$user_id' AND `fund_transaction`.`trans_type`='CR' AND `fund_transaction`.`trans_id` LIKE 'CASHBACK' "))) {
		$fund_available = $rw['fund_available'];
		$amount_cashback = $rw['amount_cashback'];

		$fund_available = isset($fund_available)?$fund_available:'0';
		$amount_cashback = isset($amount_cashback)?$amount_cashback:'0';

		$fund_available = round($fund_available, 2);
		$amount_cashback = round($amount_cashback, 2);

	}
	
	function fetch_user_id($conn, $id_passed){
		$query_id = mysqli_query($conn,"SELECT `sponsor_id` FROM `users` WHERE `id`='$id_passed' ");
		if($rw = mysqli_fetch_array($query_id)){
			$id = $rw['sponsor_id'];
		}
		$id = (isset($id) && $id!='DP000000')?$id:'NONE';
		return $id;
	}
?>

	<head>
		<?php include("head.php"); ?>

		<title><?php echo "$name" ?>  - Profile - Maxizone</title>

		<link rel="canonical" href="index.php">
	</head>

	<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-behavior="sticky">
		<div class="wrapper">
			<?php include("sidebar.php"); ?>

			<div class="main">
				<?php include("navbar.php"); ?>

					<main class="content">
						<div class="container-fluid p-0">

							<h1 class="h3 mb-3">Profile</h1>

							<div class="row">
								<div class="col-md-12 col-xl-12">
									<div class="card">
										<div class="card-header">
											<h5 class="card-title mb-0">Personal info</h5>
										</div>
										<div class="card-body">
											<form>
												<div class="row">
													<div class="mb-3 col-md-6">
														<label for="user_id" class="form-label">User ID</label>
														<input type="text" class="form-control bg-white" id="user_id" placeholder="User ID" readonly>
													</div>
													<div class="mb-3 col-md-6">
														<label for="sponsor_id" class="form-label">Sponsor ID</label>
														<input type="text" class="form-control bg-white" id="sponsor_id" placeholder="Sponsor ID" readonly>
													</div>
													<div class="mb-3 col-md-6">
														<label for="mobile" class="form-label">Mobile</label>
														<input type="number" class="form-control" id="mobile" placeholder="Mobile" value="<?php echo "mobile";?>">
													</div>
													<div class="mb-3 col-md-6">
														<label for="email" class="form-label">Email</label>
														<input type="email" class="form-control" id="email" placeholder="Email" value="<?php echo "email";?>">
													</div>
													<div class="mb-3 col-md-12">
														<label for="address" class="form-label">Address</label>
														<textarea rows="2" class="form-control" id="address" name="address" placeholder="Your complete address" required><?php echo "address";?></textarea>
													</div>
													<div class="mb-3 col-md-6">
														<label for="city" class="form-label">City</label>
														<input type="text" class="form-control" id="city" name="city" placeholder="City" required>
													</div>
													<div class="mb-3 col-md-4">
														<label for="state" class="form-label">State</label>
														<select class="form-control select2" id="state" name="state" required>
															<option value="" hidden>Choose State</option>
															<?php
																$res = mysqli_query($conn, "SELECT `state_name` FROM `indian_states` ORDER BY `state_name` ASC");
																$states = mysqli_fetch_all($res, MYSQLI_ASSOC);
																foreach ($states as $state) {
																	$state_name = $state['state_name'];
																	?>
																		<option value="<?php echo $state_name;?>"><?php echo $state_name;?></option>
																	<?php
																}
															?>															
														</select>
													</div>
													<div class="mb-3 col-md-2">
														<label for="pin_code" class="form-label">PIN Code</label>
														<input type="text" class="form-control" id="pin_code" name="pin_code" placeholder="PIN Code" required>
													</div>
												</div>

												<button type="submit" class="btn btn-primary">Save changes</button>
											</form>

										</div>
									</div>

									<div class="card">
										<div class="card-header">
											<h5 class="card-title mb-0">Banking info</h5>
										</div>
										<div class="card-body">
											<form>
												<div class="row">
													<div class="mb-3 col-md-12">
														<label for="bank_name" class="form-label">Bank Name</label>
														<input type="text" class="form-control" id="bank_name" placeholder="Bank" value="<?php echo "bank_name";?>">
													</div>
													<div class="mb-3 col-md-6">
														<label for="branch_name" class="form-label">Branch Name</label>
														<input type="text" class="form-control" id="branch_name" placeholder="Branch" value="<?php echo "branch_name";?>">
													</div>
													<div class="mb-3 col-md-6">
														<label for="account_no" class="form-label">Account Number</label>
														<input type="text" class="form-control" id="account_no" placeholder="Account Number" value="<?php echo "account_no";?>">
													</div>
													<div class="mb-3 col-md-6">
														<label for="ifs_code" class="form-label">IFSC Code</label>
														<input type="text" class="form-control" id="ifs_code" placeholder="IFSC Code" value="<?php echo "ifs_code";?>">
													</div>
													<div class="mb-3 col-md-6">
														<label for="upi_handle" class="form-label">UPI Handle (@ybl, @paytm etc...)</label>
														<input type="text" class="form-control" id="upi_handle" placeholder="UPI" value="<?php echo "upi_handle";?>">
													</div>
												</div>
												<button type="submit" class="btn btn-primary">Save changes</button>
											</form>

										</div>
									</div>
								</div>
							</div>

						</div>
					</main>

				<?php include("footer.php"); ?>
			</div>
		</div>
	</body>

	<?php include("scripts.php"); ?>

	<?php
		// COUNTER
			$page_id = 1;
			counter($page_id);
		// COUNTER
	?>

	<script>
		function callAjaxMemberName() {
			var action = "get_sponsor";
			var sponsor_id = document.getElementById("member_user_id");
			var sponsor_name = document.getElementById("member_name");

			// Call ajax for pass data to other place
			$.ajax({
				type: "POST",
				url: "ajax.php",
				data: {
					action: action,
					sponsor_id: sponsor_id.value
				},
				
				// data: $("#form_register").serialize() + "&action=" +action , // getting filed value in serialize form And Passing additional Value of 'action' To perform Task Accordingly
				// dataType: 'JSON',
				cache: false,

				// xhr: function () 
				// {
				//     var xhr = $.ajaxSettings.xhr();
				//     xhr.onprogress = function (e) 
				//     {
				//         // For downloads
				//         if (e.lengthComputable)                             
				//         {
				//             console.log(e.loaded / e.total);
				//         }
				//     };
				//     xhr.upload.onprogress = function (e) 
				//     {
				//         // For uploads
				//         if (e.lengthComputable) 
				//         {
				//             document.getElementById("overlay").style.display = "block";
				//             console.log(e.loaded / e.total);
				//         }
				//     };
				//     return xhr;
				// },

				success: function(response) {
					// document.getElementById("overlay").style.display = "none";

					// var response_obj = $.parseJSON(response); // create an object with the key of the array
					// PARSE/DECODE THE JSON OBJECT
					// var response_obj = JSON.parse(response);
					// alert(response_obj.html_data); // where html is the key of array that you want, $response['html'] = "<a>something..</a>";

					sponsor_name.value = response;
				}
			});
		}
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
</html>
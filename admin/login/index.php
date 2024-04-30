<!DOCTYPE html>
<html lang="en">
<link rel="shortcut icon" href="../../assets/images/favicon.svg" type="image/x-icon">
<?php
	ob_start();
	require_once("../../db_connect.php");
	session_start();

	mysqli_query($conn, "set names 'utf8'");  //-------WORKING UTF8 CODE------//

	//-------CURRENT DATE AND TIME TO FEED---------//
	date_default_timezone_set('Asia/Kolkata');
	$current_date = date('Y-m-d H:i:s');

	// check login status
		// if (isset($_GET['name']) && isset($_SESSION['session_id_admin_diet'])) {
		if (isset($_SESSION['session_id_admin'])) {
			// $name = json_decode(base64_decode($_GET['name']));    //DECODE
			// $name_encode = base64_encode(json_encode($name));     //ENCODE

			$user_id = $_SESSION['user_id_admin'];
			$sponsor_id = $_SESSION['sponsor_id_admin'];
			$session_id = $_SESSION['session_id_admin'];		
			$name = $_SESSION['name_admin'];    //DECODE
			$name_encode = base64_encode(json_encode($name));     //ENCODE

			if($user_id == "admin"){
				$session_id_admin = $_SESSION['session_id_admin'];
				header("Location: ../dashboard/");
				exit;
			}
			// $user_id = strtoupper($user_id);
			header("Location: ../dashboard/");

			// USER AUTHENTICATION
				$session_id_pass = $_SESSION['session_id_admin'];
				$query = mysqli_query($conn, "SELECT `unique_id`, `session_id` FROM `login_sessions` WHERE `unique_id`='$user_id' ORDER BY `create_date` DESC LIMIT 1");
				if ($row = mysqli_fetch_array($query)) {
					$session_id = $row['session_id'];
					// $name = $row['name'];

					header("Location: ../dashboard/");

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
		}
	// check login status

	$msg=$msg_type="";
	if (isset($_POST['login'])) {
		$user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);

		$result = mysqli_query($conn, "SELECT * FROM `users_admin` WHERE `user_id` = '$user_id' AND `password` = '$password' AND `is_active` = 1");
		// if (mysqli_num_rows($result) == 1) {
		if ($row = mysqli_fetch_array($result)) {
			$session_id = rand(1, 999999);
			$name = $row['name'];
			$sponsor_id = $row['sponsor_id'];

			$_SESSION['user_id_admin'] = $user_id;
			$_SESSION['session_id_admin'] = $session_id;
			$_SESSION['name_admin'] = $name;

			$user_id_encode = base64_encode(json_encode($user_id));     //ENCODE
			$name_encode = base64_encode(json_encode($name));     //ENCODE
			// $user_id = json_decode(base64_decode($_GET['user_id']));    //DECODE  

			// CREATE LOGIN SESSION
				if (mysqli_query($conn, "INSERT INTO  `login_sessions` (`unique_id`,`session_id`,`login_date`,`create_date`) VALUES ('$user_id','$session_id','$current_date','$current_date') ")) {
					//header("Location: ../dashboard/index.php?name=$name_encode");
					header("Location: ../dashboard/");
					exit;
				} else {
					$q_update = mysqli_query($conn, "UPDATE `login_sessions` SET `session_id` = '$session_id', `login_date` = '$current_date', `update_date` = '$current_date' WHERE `unique_id` = '$user_id' ");
					$rows_affected = mysqli_affected_rows($conn);
					if ($rows_affected == 1) {
						//header("Location: ../dashboard/index.php?name=$name_encode");
						header("Location: ../dashboard/");
						exit;
					} else {
						$msg = "Session Error. Please Try Again...";
						$msg_type="success";
					}
				}
			// CREATE LOGIN SESSION

			if($user_id == "admin"){
				$_SESSION['session_id_admin'] = $session_id;
				header("Location: ../dashboard/admin.php");
				exit;
			}
			header("Location: ../dashboard/");
			exit;
		} else {
			$msg = "Wrong Credentials! Retry...";
			$msg_type="danger";
		}
	}

	// DEFAULT ORDER COLUMN SR
		$default_Order = 0;
?>

<head>
	<?php include("../dashboard/head.php"); ?>

	<title>Maxizone - Login</title>

	<link rel="canonical" href="index.php">
	<!-- REQUIRED AJAX JQUERY LIB WITH SEARCH BOX => -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
</head>

<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-behavior="sticky">
	<div class="main d-flex justify-content-center w-100">

		<nav class="navbar navbar-expand navbar-light navbar-bg no_print">
			
			<div class="input-group input-group-navbar">
				<a href="../../" style="text-decoration: none;cursor:pointer;">
					<img src="../../assets/images/wealthride-logo.png" alt="" width="110">
					<!-- <input type="text" class="form-control" placeholder="Maxizone" aria-label="Search" readonly style="font-weight: bold;text-align: center;cursor:pointer;"> -->
				</a>
			</div>
			
		</nav>

		<main class="content d-flex p-0">
			<div class="container d-flex flex-column">
				<div class="row h-100">
					<div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
						<div class="d-table-cell align-middle">

							<div class="text-center mt-4">
								<h1 class="h2">Admin Login!</h1>
								<p class="lead">
									Login to your account to continue
								</p>
							</div>

							<div class="caard" style="word-wrap: break-word;
												background-clip: border-box;
												background-color: #fff;
												border: 0 solid transparent;
												border-radius: 0.25rem;
												display: flex;
												flex-direction: column;
												min-width: 0;
												position: relative;
												box-shadow: 0 0 0.875rem 0 rgb(41 48 66 / 5%);
												margin-bottom: 24px">
								<div class="card-body">
									<div class="m-sm-4">
										<div class="text-center">
											<img src="../../assets/images/logo-new.png" alt="Maxizone" class="img-fluid rounded-circle" width="132" height="132">
										</div>
										<form role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="loginform">
											<div class="mb-3">
												<label for="user_id" class="form-label">User ID <span class="text-danger">*</span></label>
												<!-- <small><a href="#">Forgot your password?</a></small> -->
												<div class="input-group mb-3">
													<span class="input-group-text btn-secondary">@</span>
													<input type="text" class="form-control form-control-lg" id="user_id" name="user_id" placeholder="Enter User ID" required>
												</div>
											</div>
											<div class="mb-3">
												<label for="password" class="form-label">Password <span class="text-danger">*</span></label>
												<div class="input-group mb-3">
													<span class="input-group-text btn-secondary">
														<i class="fa-solid fa-lock fa-xl"></i>
													</span>
													<input type="password" class="form-control form-control-lg" id="user_password" name="password" placeholder="Enter your password" required>
												</div>
											</div>
											<!-- <div>
												<div class="form-check align-items-center">
													<input id="customControlInline" type="checkbox" class="form-check-input" value="remember-me" name="remember-me" checked="">
													<label class="form-check-label text-small" for="customControlInline">Remember me next time</label>
												</div>
											</div> -->
											<div class="text-center mt-3">
												<!-- <input type="submit" name="login" value="Login" class="btn btn-lg btn-primary" /> -->
												<button type="submit" name="login" class="btn btn-lg btn-primary">Login</button>
											</div>
											<!-- <div class="text-center mt-3">
												<span>Don't Have Account? 
													<a class="dropdown-item" href="#">
														<i class="align-middle me-1" data-feather="phone"></i> Click to Call us now!
													</a>	
												</span>
											</div> -->
										</form>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</main>
		<?php include("../dashboard/footer.php"); ?>
	</div>

	<?php include("../dashboard/scripts.php"); ?>

	<script>
		$(document).ready(function() {
			//change selectboxes to selectize mode to be searchable
			$("#name").select2();
			$(".card").css("display","block");
		});

		function showPassword() {
			var x = document.getElementById("myInput");
			if (x.type === "password") {
				x.type = "text";
			} else {
				x.type = "password";
			}
		}

		function callAjaxSponsor() {
			var action = "get_sponsor";
			var sponsor_id = document.getElementById("sponsor_id");
			var sponsor_name = document.getElementById("sponsor_name");

			// Call ajax for pass data to other place
			$.ajax({
				type: "POST",
				url: "../dashboard/ajax.php",
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

		function callAjaxRegister() {
			var action = "register";
			var sponsor_id = document.getElementById("sponsor_id");
			var sponsor_name = document.getElementById("sponsor_name");
			var full_name = document.getElementById("full_name");
			var email = document.getElementById("email");
			var mobile = document.getElementById("mobile");
			var password = document.getElementById("password");
			var verify_password = document.getElementById("verify_password");
			var btn_register = document.getElementById("btn_register");

			var regex_email = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            var regex_name = /^[A-Za-z ]+$/;
			var regex_mobile = /^[0-9]{10}$/;
			// [6-9]{1}[0-9]{9}
			
			// TRUE >> REQUIRED
			// regex_email.test(x.value)
			// y.value.match(regex_name)
			// y.value.match(regex_mobile)
			
			if (sponsor_id.value == '' || sponsor_id.value.trim() == '') {
				sponsor_id.focus();
				sponsor_id.scrollIntoView();
				//SHOW NOTIFICATION
				showNotif("Enter Sponsor ID", "danger")
				return false;
			} 
			// else if (sponsor_name.value == '' || sponsor_name.value.trim() == '') {
			// 	sponsor_name.focus();
			// 	sponsor_name.scrollIntoView();
			// 	//SHOW NOTIFICATION
			// 	showNotif("Enter Valid Sponsor ID", "danger")
			// 	return false;
			// }
			else if (full_name.value == '' || full_name.value.trim() == '') {
				full_name.focus();
				full_name.scrollIntoView();
				//SHOW NOTIFICATION
				showNotif("Enter Full Name", "danger")
				return false;
			} else if (!(regex_email.test(email.value))) {
				email.focus();
				email.scrollIntoView();
				//SHOW NOTIFICATION
				showNotif("Enter Valid Email ID with @", "danger")
				return false;
			} else if (!mobile.value.match(regex_mobile)) {
				mobile.focus();
				mobile.scrollIntoView();
				//SHOW NOTIFICATION
				showNotif("Enter 10 Digit Mobile No.", "danger")
				return false;
			} else if (password.value == '' || password.value.trim() == '') {
				password.focus();
				password.scrollIntoView();
				//SHOW NOTIFICATION
				showNotif("Enter Password", "danger")
				return false;
			} else if (verify_password.value == '' || verify_password.value.trim() == '') {
				verify_password.focus();
				verify_password.scrollIntoView();
				//SHOW NOTIFICATION
				showNotif("Enter Verify Password", "danger")
				return false;
			}
			
			if (password.value != verify_password.value){
				//SHOW NOTIFICATION
				showNotif("Password and Verify Password Are Different", "danger")
				return false;
			}
			// using this page stop being refreshing 
			event.preventDefault();
			
			btn_register.innerHTML = "Please Wait...";
			btn_register.disabled = true;

			// All Validation Done
				sponsor_id = sponsor_id.value;
				sponsor_name = sponsor_name.value;
				full_name = full_name.value;
				email = email.value;
				mobile = mobile.value;
				password = password.value;
				verify_password = verify_password.value;
				
			// Call ajax for pass data to other place
			$.ajax({
				type: "POST",
				url: "../dashboard/ajax.php",
				data: {
					action: action,
					sponsor_id,
					sponsor_name,
					full_name,
					email,
					mobile,
					password,
					verify_password
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
					btn_register.innerHTML = "Submit";
					btn_register.disabled = false;
					
					if (response) {
						alert(response);

						// STOP RELOADING
						// location.reload();

						// TOASTIFY NOTIFICATION
						if (response == "Error") {
							showNotif("Error! Current Password Don't Match", "danger");
							return false;
						} else if (response == "Success"){
							showNotif("Password changed successfully", "success");
							// CLOSE MODAL
							$('.btn-close').click();
							password.value = '';
							new_password.value = '';
							verify_password.value = '';
						}
					} else {
						showNotif("Error Occured... Try Again", "danger");
					}
				}
			});
		}
	</script>
</body>

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

	<!-- BEGIN ModalRegister -->
		<div class="modal fade" id="ModalRegister" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<a href="./" style="text-decoration: none;cursor:pointer;">
							<img src="../assets/img/logo.png" alt="" width="110">
							<!-- <input type="text" class="form-control" placeholder="Maxizone" aria-label="Search" readonly style="font-weight: bold;text-align: center;cursor:pointer;"> -->
						</a>
						<h3 class="modal-title">Register Now!</h3>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p class="mb-0">
						<form method="POST" action="" id="form_register">
							<div class="mb-3">
								<label for="sponsor_id" class="form-label">Sponsor ID <span class="text-danger">*</span></label>
								<!-- <small><a href="#">Forgot your password?</a></small> -->
								<div class="input-group mb-3">
									<span class="input-group-text btn-secondary">@</span>
									<input type="text" class="form-control" id="sponsor_id" placeholder="Sponsor ID" required onblur="callAjaxSponsor()">
								</div>
							</div>
								
							<div class="mb-3">
								<label for="sponsor_name" class="form-label">Sponsor Name</label>
								<div class="input-group mb-3">
									<span class="input-group-text btn-secondary">
										<i class="fa-solid fa-user"></i>
									</span>
									<input type="text" class="form-control" id="sponsor_name" placeholder="Enter Sponsor ID" required readonly>
								</div>
							</div>

							<div class="mb-3">
								<label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
								<div class="input-group mb-3">
									<span class="input-group-text btn-secondary">
										<i class="fa-solid fa-user"></i>
									</span>
									<input type="text" class="form-control" id="full_name" placeholder="Enter Full Name" required>
								</div>
							</div>

							<div class="mb-3">
								<label for="email" class="form-label">Email ID <span class="text-danger">*</span></label>
								<div class="input-group mb-3">
									<span class="input-group-text btn-secondary">
										<i class="fa-solid fa-envelope"></i>
									</span>
									<input type="text" class="form-control" id="email" placeholder="Enter Email ID" required>
								</div>
							</div>
							
							<div class="mb-3">
								<label for="mobile" class="form-label">Mobile No. <span class="text-danger">*</span></label>
								<div class="input-group mb-3">
									<span class="input-group-text btn-secondary">
										<i class="fa-solid fa-phone"></i>
									</span>
									<input type="text" class="form-control" id="mobile" placeholder="Enter Mobile No." required>
								</div>
							</div>

							<div class="mb-3">
								<label for="password" class="form-label">Password <span class="text-danger">*</span></label>
								<div class="input-group mb-3">
									<span class="input-group-text btn-secondary">
										<i class="fa-solid fa-lock-open"></i>
									</span>
									<input type="password" class="form-control" id="password" required>
								</div>
							</div>

							<div class="mb-3">
								<label for="Verify Password" class="form-label">Verify Password <span class="text-danger">*</span></label>
								<div class="input-group mb-3">
									<span class="input-group-text btn-secondary">
										<i class="fa-solid fa-lock"></i>
									</span>
									<input type="password" class="form-control" id="verify_password" required>
								</div>
							</div>

							<div style="float:right;">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<button type="button" class="btn btn-success" id="btn_register" onclick="callAjaxRegister()">Submit</button>
							</div>
						</form>
						</p>
					</div>
					<!-- <div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-success">Save changes</button>
					</div> -->
				</div>
			</div>
		</div>
	<!-- END ModalRegister -->
	
</html>
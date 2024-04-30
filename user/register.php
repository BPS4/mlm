<!DOCTYPE html>
<html lang="en">

<?php
    ob_start();
    require_once '../db_connect.php';
    session_start();

    mysqli_query($conn, "set names 'utf8'"); //-------WORKING UTF8 CODE------//

    //-------CURRENT DATE AND TIME TO FEED---------//
    date_default_timezone_set('Asia/Kolkata');
    $current_date = date('Y-m-d H:i:s');

    extract($_REQUEST);
    
    $sponsor_id = $sponsor_name = $sponsor_id_readonly = "";

    // CHECK AVAILABILITY
        if (isset($_GET['ref_code'])) {
            // $sponsor_id = json_decode(base64_decode($_GET['ref_code']));    //--DECODE THE SECRET CODE PASSED--//
            // $sponsor_id_encode = base64_encode(json_encode($sponsor_id));     //ENCODE
            $sponsor_id = mysqli_real_escape_string($conn,$_GET['ref_code']);
            if ($rw = mysqli_fetch_array(mysqli_query($conn, "SELECT `name` FROM `users` WHERE `user_id`='$sponsor_id' "))) {
                $sponsor_name = $rw['name'];
                $sponsor_id_readonly = "readonly='true'";
            } else {
                $sponsor_id = $sponsor_name = $sponsor_id_readonly = "";
            }
        }
    // CHECK AVAILABILITY

    $login_stop = $signup_stop = $website_stop = "0";
    $query = mysqli_query($conn, "SELECT * FROM `configuration` WHERE `id` IN (3,4,5) AND `delete_date` IS NULL");
    $records = mysqli_fetch_all($query, MYSQLI_ASSOC);
    foreach ($records as $image) {
        //DEFAULT VALUES SET
        $classStatus = 'bg-success';
        $id = $image['id'];

        $key_field = $image['key_field'];
        $value_field = $image['value_field'];
        $is_active = $image['is_active'];
        $remarks = $image['remarks'];

        // if ($key_field == "login_maintain") {
        //     $login_stop = $is_active;
        //     if ($login_stop) {
        //         $_SESSION['login_stop'] = 1;
        //         $_SESSION['login_stop_message'] = $remarks;
        //     } else {
        //         $_SESSION['login_stop'] = 0;
        //         unset($_SESSION['login_stop_message']);
        //     }
        // }
        
        unset($_SESSION['login_stop']);
        unset($_SESSION['login_stop_message']);

        if ($key_field == "signup_maintain") {
            $signup_stop = $is_active;
            if ($signup_stop) {
                $_SESSION['signup_stop'] = 1;
                $_SESSION['signup_stop_message'] = $remarks;
            } else {
                $_SESSION['signup_stop'] = 0;
                unset($_SESSION['signup_stop_message']);
            }
        }

        if ($key_field == "web_maintain") {
            $website_stop = $is_active;
            if ($website_stop) {
                $_SESSION['web_stop'] = 1;
                $_SESSION['web_stop_message'] = $remarks;
            } else {
                $_SESSION['web_stop'] = 0;
                unset($_SESSION['web_stop_message']);
            }
        }
    }
    
    if ($website_stop || $signup_stop) {
        header("Location: ../under-maintenance.php");
    }
?>

<head>
    <title>Maxizone - Your Growth Partner</title>
    <?php require_once("head.php"); ?>
</head>

<body>
    <!-- page-wrapper Start-->
    <section>
        <div class="container-fluid p-0">
            <div class="row m-0">
                <div class="col-xl-7 p-0"><img class="bg-img-cover bg-center" src="assets/images/login/1.jpg" alt="looginpage"></div>
                <div class="col-xl-5 p-0">
                    <div class="login-card" style="height: auto !important;">
                        <form class="theme-form login-form" style="width: 80% !important;">
                            <a href="../" class="lead"> 
                                <img src="<?php echo $logo_path; ?>" class="rounded mb-3" style="width: 40px;">Maxizone
                            </a>                        
                            <h4>Register yourself with Maxizone</h4>
                            <h6 class="form-label">Use <span class="text-success show-pointer" style="font-weight: bolder; text-transform: lowercase;" onclick="copyToClipboard('maxizone')">maxizone</span> as Sponsor ID in case of Direct Joining</h6>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="sponsor_id" class="form-label">Sponsor ID<span class="text-danger">*</span></label>
                                        <input type="text" id="sponsor_id" name="sponsor_id" class="referral form-control" placeholder="Enter Sponsor ID*" <?php echo $sponsor_id_readonly; ?> required onkeyup="get_sponsor()" value="<?php echo isset($sponsor_id)?$sponsor_id:''; ?>">
                                        <span id="sponsor_id_error" class="text-danger d-none"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="sponsor_name" class="form-label">Sponsor Name</label>
                                        <input type="text" id="sponsor_name" name="sponsor_name" class="form-control" placeholder="Enter Sponsor ID First" readonly required value="<?php echo isset($sponsor_name)?$sponsor_name:''; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Full Name<span class="text-danger">*</span></label>
                                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter Your Full Name" required onchange="check_field(this.id)">
                                        <span id="name_error" class="text-danger d-none"></span>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label for="mobile" class="form-label">Mobile<span class="text-danger">*</span></label>
                                        <input type="text" id="mobile" name="mobile" class="form-control" placeholder="Your Mobile Number" required onkeyup="check_field(this.id)" min="0" maxlength="10" pattern="[5-9]{1}[0-9]{9}" title="Enter 10 Digit Valid Mobile No." />
                                        <span id="mobile_error" class="text-danger d-none"></span>
                                    </div>
                                </div>
                                <div class="col-lg-4 d-flex justify-content-center align-items-center" id="btn_verify_container">
                                    <div class="form-group">
                                        <button class="badge bg-success account--btn" id="btn_verify_mobile" onclick="send_otp('fresh'); return false;">Send OTP</button>
                                    </div>
                                </div>
                                <div class="col-lg-4 justify-content-center d-none" id="otp_container">
                                    <div class="form-group">
                                        <label for="otp" class="form-label">OTP<span class="text-danger">*</span></label>
                                        <input type="text" id="otp" name="otp" class="form-control" placeholder="Enter OTP" required onkeyup="check_field(this.id)" min="0" maxlength="6" pattern="[0-9]{6}" title="Enter 6 Digit Valid OTP" />
                                        <span id="otp_error" class="text-danger d-none">Incorrect OTP</span>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12 text-primary fw-bold mb-2 d-none" id="timer_container">
                                    OTP Sent Successfully!
                                    Resend OTP After
                                        <div id="timer" style="display:inline;font-weight:bold;"></div>
                                    Seconds
                                </div>
                                
                                <div class="col-lg-12 d-flex justify-content-center align-items-center d-none" id="otp_resend_container">
                                    <div class="form-group">
                                        <button class="badge bg-success rounded-pill px-3" id="btn_resend_otp" onclick="send_otp('resend'); return false;">Resend OTP</button>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email (used as User ID)<span class="text-danger">*</span></label>
                                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter User ID or Email" required onchange="check_field(this.id)">
                                        <span id="email_error" class="text-danger d-none"></span>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group hover-input-popup">
                                        <label for="password" class="form-label">Password<span class="text-danger">*</span></label>
                                        <input type="password" id="password" name="password" class="form-control" required placeholder="Enter Password">                                    
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="confirm_password" class="form-label">Confirm Password<span class="text-danger">*</span></label>
                                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required placeholder="Confirm Password">
                                        <span id="password_error" class="text-danger d-none"></span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group text-center">
                                        <input style="width: 20px;" id="agree" name="agree" type="checkbox" required>

                                        <label for="agree">I agree with </label>

                                        <!-- <a class="text-primary show_pointer" id="refund" onclick="show_policy(this.id);"> Refund Policy ,
                                        </a> -->
                                        <a class="text-primary show_pointer" id="tnc" onclick="show_policy(this.id);"> Terms and Condition ,
                                        </a>
                                        <a class="text-primary show_pointer" id="privacy" onclick="show_policy(this.id);"> Privacy Policies
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-block" id="btn_register" type="button" onclick="submit_user_form();">Proceed</button>
                            </div>
                            
                            <p>Already registered with us?<a class="ms-2" href="login.php">Login Now</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- page-wrapper end-->

    <div class="modal fade" id="ModalPolicy" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalTitle">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe type="text/html" id="ModalIframe" width="100%" height="450px" src="" frameborder="0"></iframe>
                    <!-- <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d224346.48129412968!2d77.06889969035102!3d28.52728034389636!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfd5b347eb62d%3A0x52c2b7494e204dce!2sNew%20Delhi%2C%20Delhi%2C%20India!5e0!3m2!1sen!2sbd!4v1638784996798!5m2!1sen!2sbd" style="border:0;" allowfullscreen="" loading="lazy"></iframe> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn--success" data-bs-dismiss="modal">Continue</button>
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN ModalShowRegistration -->
        <div class="modal fade" id="ModalShowRegistration" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Registration Successful</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="success_name">Name</label>
                                    <input type="text" name="success_name" id="success_name" value="" class="form-control text-readonly" readonly>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="success_user_id">User Name</label>
                                    <input type="text" name="success_user_id" id="success_user_id" value="" class="form-control text-readonly" readonly>
                                </div>
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="success_password">Password</label>
                                    <input type="text" name="success_password" id="success_password" value="" class="form-control text-readonly" readonly>
                                </div>
                                <div class="mb-3 col-md-12 text-danger text-center text-bold">
                                    Take Screenshot of it for future usage and Kindly <a href="login.php" class="text-success fw-bold"><u>Login Now</u></a> for Completing Your Profile!
                                </div>
                            </div>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <!-- END ModalShowRegistration -->

    <?php require_once("scripts.php"); ?>

    <script>
        function send_otp(otp_type) {
            var name = document.getElementById("name");
            var mobile = document.getElementById("mobile");
            var otp = document.getElementById("otp");
            var btn_verify_container = document.getElementById("btn_verify_container");
            var btn_verify_mobile = document.getElementById("btn_verify_mobile");
            var otp_container = document.getElementById("otp_container");
            var timer_container = document.getElementById("timer_container");
            var otp_resend_container = document.getElementById("otp_resend_container");
            var btn_resend_otp = document.getElementById("btn_resend_otp");

            if (!check_field("name")) {
                return false;
            } else if (!check_field("mobile")) {
                return false;
            }

            btn_verify_mobile.innerHTML = "Sending...";
            btn_resend_otp.innerHTML = "Sending...";
            send_message(otp_type,name,mobile);

            // setTimeout(function(){
            //     send_message(name,mobile);
            // },300);

            return false;
        }

        function send_message(otp_type,name,mobile) {
            var action = "generate_otp";

            $.ajax({
                async: false,   //SYNC CALL TO WAIT BEFORE PROCESSING
                type: "POST",
                url: "../ajax.php",
                data: {
                    action: action,
                    otp_type,
                    name: name.value,
                    mobile: mobile.value
                },
                
                // data: $("#form_register").serialize() + "&action=" +action , // getting filed value in serialize form And Passing additional Value of 'action' To perform Task Accordingly
                // dataType: 'JSON',
                cache: false,

                success: function(response) {   
                    btn_verify_mobile.innerHTML = "Send OTP";
                    btn_resend_otp.innerHTML = "Resend";

                    if (response == "error" || response == "") {
                        showNotif("Error in sending OTP! Retry...", "error");
                        return false;
                    }

                    name.readOnly = true;
                    mobile.readOnly = true;

                    window.last_id_insert = response;   //GLOBAL VARIABLE
                    window.mobile_verified = mobile.value;
                    
                    otp_resend_container.classList.add("d-none");

                    btn_verify_container.classList.add("d-none");
                    otp_container.classList.remove("d-none");
                    timer_container.classList.remove("d-none");

                    show_timer(60, "timer",timer_container,otp_resend_container);
                    
                    showNotif("OTP Sent Successfully! Verify It Before Proceeding...", "success");
                }
            });                                    
        }

        function show_timer(time_in_second, container_id, timer_container, otp_resend_container) {
            seconds = 1000 * time_in_second; //1000 = 1 second in JS
            max_seconds = 1000 * time_in_second; //1000 = 1 second in JS
            
            // DEFINE GLOBAL VARIABLE IN JAVASCRIPT USING WINDOW OBJECT
                window.container_id = container_id;
            // DEFINE GLOBAL VARIABLE IN JAVASCRIPT USING WINDOW OBJECT
            
            myFunction();
            
            //If seconds are equal or greater than 0, countdown until 1 minute has passed
            //Else, clear the timer
            document.getElementById(window.container_id).innerHTML = "0:" + seconds / 1000;
        }
        
        function myFunction() {
            if (seconds == max_seconds) {
                timer = setInterval(myFunction, 1000)
            }

            seconds -= 1000;

            document.getElementById(window.container_id).innerHTML = '0:' + seconds / 1000;
            if (seconds <= 0) {                                        
                clearInterval(timer);
                timer_container.classList.add("d-none");
                otp_resend_container.classList.remove("d-none");
                // mobile.readOnly = false;
                otp.readOnly = false;

                //OTP TIMER HIDE
                // document.getElementById("otp_timer").style.display = "none";
            }
        }
        
        function show_policy(id) {
            var modal = $('#ModalPolicy');
            var title = "Confirmation!";
            var source_url = "../pp.php";
            switch (id) {
                case 'refund':
                    title = "Refund Policy";
                    source_url = "../rp.php";
                    break;
                case 'tnc':
                    title = "Terms and Condition";
                    source_url = "../tnc.php";
                    break;
                case 'privacy':
                    title = "Privacy and Policies";
                    source_url = "../pp.php";
                    break;
                default:
                    break;
            }
            document.getElementById('ModalTitle').innerHTML=title;
            document.getElementById('ModalIframe').src=source_url;
            modal.find('.p_name').innerHTML="";
            modal.modal('show');
        }

        function get_sponsor() {
			var action = "get_sponsor";

			var sponsor_id = document.getElementById("sponsor_id");
			var sponsor_id_error = document.getElementById("sponsor_id_error");
			var sponsor_name = document.getElementById("sponsor_name");
			
			if (sponsor_id.value == '' || sponsor_id.value.trim() == '' || sponsor_id.value.length<6) {
				sponsor_id.focus();
				// sponsor_id.scrollIntoView();
				//SHOW NOTIFICATION
				sponsor_id_error.innerHTML = "Enter Correct Sponsor ID";
				sponsor_name.placeholder = "Enter Sponsor ID";

				return false;
			} else {
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
                        sponsor_name.placeholder = "Enter Correct Sponsor ID";
                    } else {
                        sponsor_name.style.background = "";
                        sponsor_name.style.color = "";
                        sponsor_name.classList.remove('placeholder_error');
                        sponsor_name.value = response;
                    }
				}
			});
		}

        function check_field(id) {
            var element = document.getElementById(id);
            var element_error = document.getElementById(id+"_error");
            var error_message = "";
            switch (id) {
                case 'name':
                    error_message = "Enter Name (Only Alphabets)";
                    if (!element.value.match(regex_name) || element.value.trim() == "") {
                        element.focus();
                        // element.scrollIntoView();
                        //SHOW NOTIFICATION
                        showNotif(error_message, "error")
                        element_error.innerHTML = error_message;
                        element_error.classList.remove("d-none");
                        return false;
                    } else {
                        element_error.classList.add("d-none");
                        element_error.innerHTML = "";
                    }
                    break;
                case 'email':
                    error_message = "Enter Valid Email ID with @";
                    if (!(regex_email.test(element.value))) {
                        element.focus();
                        // email.scrollIntoView();
                        //SHOW NOTIFICATION
                        showNotif(error_message, "error")
                        element_error.innerHTML = error_message;
                        element_error.classList.remove("d-none");
                        return false;
                    } else {
                        element_error.classList.add("d-none");
                        element_error.innerHTML = "";
                        check_exist_already('email',element.value);
                    }
                    break;
                case 'mobile':
                    error_message = "Enter 10 Digit Valid Mobile No.";
                    if (!element.value.match(regex_mobile) || element.value.trim() == "") {
                        element.focus();
                        // mobile.scrollIntoView();
                        //SHOW NOTIFICATION
                        if (element.value.trim().length == 10) {
                            showNotif(error_message, "error")
                        }
                        element_error.innerHTML = error_message;
                        element_error.classList.remove("d-none");
                        return false;
                    } else {
                        element_error.classList.add("d-none");
                        element_error.innerHTML = "";
                        check_exist_already('mobile',element.value);
                    }
                    break;

                case 'otp':
                    error_message = "Enter OTP";
                    if (element.value.trim() == "" || element.value.trim().length < 6) {
                        element.focus();
                        // mobile.scrollIntoView();
                        //SHOW NOTIFICATION                        
                        element_error.innerHTML = error_message;
                        element_error.classList.remove("d-none");
                        return false;
                    } else {
                        // if (element.value.trim().length == 6) {
                        //     showNotif(error_message, "error")
                        // }
                        element_error.classList.add("d-none");
                        element_error.innerHTML = "";
                        verify_otp(element_error,element.value);
                    }
                    break;

                default:
                    break;
            }
            return true;
        }
        
        function check_exist_already(type,value) {
            var action = "check_exist";
            var element = document.getElementById(type);
            var element_error = document.getElementById(type+"_error");

            $.ajax({
				async: false,   //SYNC CALL TO WAIT BEFORE PROCESSING
                type: "POST",
				url: "../ajax.php",
				data: {
					action: action,
					type,
					value
				},
				
				// data: $("#form_register").serialize() + "&action=" +action , // getting filed value in serialize form And Passing additional Value of 'action' To perform Task Accordingly
				// dataType: 'JSON',
				cache: false,

				success: function(response) {
                    if (response == "EXIST") {
                        element.value = "";
					    element_error.innerHTML = value+" Registered Already!";
                        element_error.classList.remove("d-none");
                        showNotif(value+" Registered Already!","warning");
                        return true;
                    } else if (response == "UNIQUE") {
                        element_error.classList.add("d-none");
                        return false;
                    }
				}
			});
        }

        function verify_otp(element_error,value) {
            var action = "verify_otp";

            $.ajax({
				async: false,   //SYNC CALL TO WAIT BEFORE PROCESSING
                type: "POST",
				url: "../ajax.php",
				data: {
					action: action,
					id: last_id_insert,
                    mobile_verified: mobile.value,
					otp: value
				},
				
				// data: $("#form_register").serialize() + "&action=" +action , // getting filed value in serialize form And Passing additional Value of 'action' To perform Task Accordingly
				// dataType: 'JSON',
				cache: false,

				success: function(response) {
                    if (response == "time_over") {
					    element_error.innerHTML = "OTP Expired! Resend...";
                        element_error.classList.remove("d-none");
                        showNotif("OTP Expired! Resend to Verify...","error");
                        return true;
                    } else if (response == "mobile_error") {
					    element_error.innerHTML = "Mobile Changed!";
                        element_error.classList.remove("d-none");
                        showNotif("You changed your Mobile No. before verification! To Verify, Use the Same Number You Received OTP On...","error");
                        return false;
                    } else if (response == "incorrect_otp") {
					    element_error.innerHTML = "Wrong OTP!";
                        element_error.classList.remove("d-none");
                        showNotif("Wrong OTP! Retry to Verify...","error");
                        return false;
                    } else if (response == "verified") {
                        clearInterval(timer);
                        timer_container.classList.add("d-none");
                        
                        otp_resend_container.classList.add("d-none");
                        element_error.classList.add("d-none");
                        mobile.readOnly = true;
                        otp.readOnly = true;
                        window.otp_succeed = true;
                        showNotif("OTP Verified! Proceed Now...","success");
                        return false;
                    }
				}
			});
        }
        
        function submit_user_form() {
            var action = "register_user";
			var btn_register = document.getElementById("btn_register");

            // TEMPORARY PREVENT MOBILE CHECK
            if (!window.otp_succeed || window.otp_succeed === undefined) {
                showNotif("Kindly Verify Mobile Number Before Proceeding...","warning");
                return false;
            }

            var sponsor_id = document.getElementById("sponsor_id");
			var sponsor_name = document.getElementById("sponsor_name");
			var name = document.getElementById("name");
			var mobile = document.getElementById("mobile");
			var email = document.getElementById("email");
			var password = document.getElementById("password");
			var confirm_password = document.getElementById("confirm_password");
			var agree = document.getElementById("agree");
			var btn_register = document.getElementById("btn_register");

            var email_error = document.getElementById("email_error");
            var mobile_error = document.getElementById("mobile_error");

			if (sponsor_id.value.trim() == '') {
				sponsor_id.focus();
				sponsor_id.scrollIntoView();
				//SHOW NOTIFICATION
				showNotif("Enter Sponsor ID", "error")
				return false;
			} else if (sponsor_name.value.trim() == '' || !sponsor_name.value.match(regex_name)) {
				sponsor_id.focus();
				// sponsor_name.scrollIntoView();
				//SHOW NOTIFICATION
				showNotif("Enter Valid Sponsor ID for Sponsor Name", "error")
				return false;
			} else if (name.value == '' || name.value.trim() == '') {
				name.focus();
				// name.scrollIntoView();
				//SHOW NOTIFICATION
				showNotif("Enter Full Name", "error")
				return false;
			} else if (!mobile.value.match(regex_mobile)) {
				mobile.focus();
				// mobile.scrollIntoView();
				//SHOW NOTIFICATION
				showNotif("Enter 10 Digit Valid Mobile No.", "error")
				return false;
			} else if (!(regex_email.test(email.value))) {
				email.focus();
				// email.scrollIntoView();
				//SHOW NOTIFICATION
				showNotif("Enter Valid Email ID with @", "error")
				return false;
			} else if (password.value == '' || password.value.trim() == '') {
				password.focus();
				// password.scrollIntoView();
				//SHOW NOTIFICATION
				showNotif("Enter Password", "error")
				return false;
			} else if (confirm_password.value == '' || confirm_password.value.trim() == '') {
				confirm_password.focus();
				// confirm_password.scrollIntoView();
				//SHOW NOTIFICATION
				showNotif("Enter Confirm Password", "error")
				return false;
			} else if (!agree.checked) {
                agree.focus();
				// agree.scrollIntoView();
				//SHOW NOTIFICATION
				showNotif("Kindly Accept the Terms Before Proceeding", "error")
				return false;
            }
			
			if (password.value != confirm_password.value){
				confirm_password.focus();
				//SHOW NOTIFICATION
				showNotif("Password and Confirm Password Are Different", "error")
				return false;
			}
			// using this page stop being refreshing 
			event.preventDefault();
			
			btn_register.innerHTML = "Please Wait...";
			btn_register.disabled = true;

			// All Validation Done
				sponsor_id_value = sponsor_id.value;
				sponsor_name_value = sponsor_name.value;
				name_value = name.value;
				email_value = email.value;
				mobile_value = mobile.value;
				password_value = password.value;
				confirm_password_value = confirm_password.value;
			// Call ajax for pass data to other place

			$.ajax({
				type: "POST",
				url: "../ajax.php",
				data: {
					action: action,
					sponsor_id: sponsor_id_value,
					sponsor_name: sponsor_name_value,
					name: name_value,
					email: email_value,
					mobile: mobile_value,
					password: password_value,
					confirm_password: confirm_password_value
				},
				
				// data: $("#form_register").serialize() + "&action=" +action , // getting filed value in serialize form And Passing additional Value of 'action' To perform Task Accordingly
				// dataType: 'JSON',
				cache: false,

				success: function(response) {
					// document.getElementById("overlay").style.display = "none";
					btn_register.innerHTML = "Proceed";
					btn_register.disabled = false;

					if (response == "mobile_exist") {
                        mobile.value = "";
                        mobile_error.innerHTML = mobile_value + " Registered Already!";
                        showNotif("Mobile Registered Already! Try Another...", "error");
				        return false;
                    } else if (response == "email_exist") {
                        email.value = "";
                        email_error.innerHTML = email_value + " Registered Already!";
                        showNotif("Email Registered Already! Try Another...", "error");
                        return false;
                    } else if (response == "session_error") {
                        showNotif("Registration Failed! Retry...", "error");
				        return false;
                    } else if (response == "error") {
                        showNotif("Registration Failed! Retry...", "error");
				        return false;
                    } else if (response == "success") {
                        // showNotif("Registration Successful! Complete your profile.", "success");
				        // return false;
                    } else {
						// showNotif("Registration Successful! Complete your profile.", "success");
					}

                    showNotif("Registration Successful! Complete your profile.", "success");
                    // SHOW USER REGISTRATION DETAILS
                    // REDIRECT TO DASHBOARD
                    // window.location.href = "user/";
                    const obj = JSON.parse(response);
                    console.log(response)
                    // $('#ModalShowRegistration').modal('show');
                    $('#success_user_id').val(obj.user_id);
                    $('#success_name').val(obj.name);
                    $('#success_password').val(obj.password);

                    Swal.fire({
                        // title: 'Thank You '+obj.name+' For Your Registration! \r\nYOUR USER ID: '+obj.user_id+' AND PASSWORD: '+obj.password,
                        html: 'Thank You '+obj.name+' For Your Registration! <span class="mt-3 fw-bold">USER ID: '+obj.user_id+' AND PASSWORD: '+obj.password+'</span>',
                        // text: 'Modal with a custom image.',
                        // width: 500,
                        width: 800,
                        // padding: '3em',
                        color: '#716add',
                        background: '#fff url(../assets/sweet/trees.png)',
                        backdrop: `
                            rgba(0,0,123,0.4)
                            url("../assets/sweet/congratulations-congrats.gif")
                            center center
                            no-repeat
                        `,
                        // footer: 'Take Screenshot of it for future usage and Kindly <a href="login.php" class="text-success fw-bold"><u>Login Now</u></a> for Completing Your Profile!'
                        footer: 'Kindly &nbsp;<a href="login.php" class="text-success fw-bold"><u>Login Now </u></a> &nbsp;for Completing Your Profile!'
                    })
				}
			});
        }
    </script>

</body>

</html>
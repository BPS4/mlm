<!-- Page Header Start-->
<div class="page-header">
    <div class="header-wrapper row m-0">
        <div class="header-logo-wrapper col-auto p-0">
            <div class="logo-wrapper">
                <a href="./">
                    <h4>Maxizone</h4>
                    <!-- <img class="img-fluid" src="assets/images/logo/logo.png" alt=""> -->
                </a>
            </div>
            <div class="toggle-sidebar">
                <div class="status_toggle sidebar-toggle d-flex">
                    <svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <g>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M21.0003 6.6738C21.0003 8.7024 19.3551 10.3476 17.3265 10.3476C15.2979 10.3476 13.6536 8.7024 13.6536 6.6738C13.6536 4.6452 15.2979 3 17.3265 3C19.3551 3 21.0003 4.6452 21.0003 6.6738Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.3467 6.6738C10.3467 8.7024 8.7024 10.3476 6.6729 10.3476C4.6452 10.3476 3 8.7024 3 6.6738C3 4.6452 4.6452 3 6.6729 3C8.7024 3 10.3467 4.6452 10.3467 6.6738Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M21.0003 17.2619C21.0003 19.2905 19.3551 20.9348 17.3265 20.9348C15.2979 20.9348 13.6536 19.2905 13.6536 17.2619C13.6536 15.2333 15.2979 13.5881 17.3265 13.5881C19.3551 13.5881 21.0003 15.2333 21.0003 17.2619Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.3467 17.2619C10.3467 19.2905 8.7024 20.9348 6.6729 20.9348C4.6452 20.9348 3 19.2905 3 17.2619C3 15.2333 4.6452 13.5881 6.6729 13.5881C8.7024 13.5881 10.3467 15.2333 10.3467 17.2619Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </g>
                        </g>
                    </svg>
                </div>
            </div>
        </div>
        <div class="left-side-header col ps-0 d-none d-md-block">
            <div class="input-group d-none">
                <span class="input-group-text" id="basic-addon1">
                    <svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <g>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M11.2753 2.71436C16.0029 2.71436 19.8363 6.54674 19.8363 11.2753C19.8363 16.0039 16.0029 19.8363 11.2753 19.8363C6.54674 19.8363 2.71436 16.0039 2.71436 11.2753C2.71436 6.54674 6.54674 2.71436 11.2753 2.71436Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M19.8987 18.4878C20.6778 18.4878 21.3092 19.1202 21.3092 19.8983C21.3092 20.6783 20.6778 21.3097 19.8987 21.3097C19.1197 21.3097 18.4873 20.6783 18.4873 19.8983C18.4873 19.1202 19.1197 18.4878 19.8987 18.4878Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </g>
                        </g>
                    </svg>
                </span>
                <input class="form-control" type="text" placeholder="Search here.." aria-label="search" aria-describedby="basic-addon1">
            </div>

            <div class="input-group" style="width: 100% !important;">
                <marquee class="mt-2 text-dark" behavior="scroll" direction="left" width="100%" scrollamount="5" style="font-weight:bold;font-style:italic;font-size: large;color: #4CAF50 !important;">
                    <!-- To Approve Transactions Either Select <span style="color: maroon;">Approve All Checkbox</span> or Select <span style="color: maroon;">User IDs Individual Checkbox</span> Then Click <span style="color: maroon;">Approve Transactions</span> Button -->
                    <?php
                        if ($count_notif_admin == 1) {
                            echo $notification_message;
                        } else {
                            echo "Welcome To Wealth Ride Family! Wish You A Very Happy Health With Best Earnings From Our Platform...";
                        }
                    ?>
                </marquee>
            </div>
        </div>
        <div class="nav-right col-10 col-sm-6 pull-right right-header p-0" style="flex: none; width: 15% !important;">
            <ul class="nav-menus">
                <li>
                    <div class="mode animated backOutRight">
                        <svg class="lighticon" width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <g>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M18.1377 13.7902C19.2217 14.8742 16.3477 21.0542 10.6517 21.0542C6.39771 21.0542 2.94971 17.6062 2.94971 13.3532C2.94971 8.05317 8.17871 4.66317 9.67771 6.16217C10.5407 7.02517 9.56871 11.0862 11.1167 12.6352C12.6647 14.1842 17.0537 12.7062 18.1377 13.7902Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </g>
                            </g>
                        </svg>
                        <svg class="darkicon" width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17 12C17 14.7614 14.7614 17 12 17C9.23858 17 7 14.7614 7 12C7 9.23858 9.23858 7 12 7C14.7614 7 17 9.23858 17 12Z"></path>
                            <path d="M18.3117 5.68834L18.4286 5.57143M5.57144 18.4286L5.68832 18.3117M12 3.07394V3M12 21V20.9261M3.07394 12H3M21 12H20.9261M5.68831 5.68834L5.5714 5.57143M18.4286 18.4286L18.3117 18.3117" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                </li>
                <li class="d-md-none resp-serch-input d-none">
                    <div class="resp-serch-box">
                        <i data-feather="search"></i>
                    </div>
                    <div class="form-group search-form">
                        <input type="text" placeholder="Search here...">
                    </div>
                </li>

                <li class="onhover-dropdown d-none">
                    <div class="notification-box">
                        <svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <g>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9961 2.51416C7.56185 2.51416 5.63519 6.5294 5.63519 9.18368C5.63519 11.1675 5.92281 10.5837 4.82471 13.0037C3.48376 16.4523 8.87614 17.8618 11.9961 17.8618C15.1152 17.8618 20.5076 16.4523 19.1676 13.0037C18.0695 10.5837 18.3571 11.1675 18.3571 9.18368C18.3571 6.5294 16.4295 2.51416 11.9961 2.51416Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M14.306 20.5122C13.0117 21.9579 10.9927 21.9751 9.68604 20.5122" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </g>
                            </g>
                        </svg>
                        <span class="badge rounded-pill badge-warning">4 </span>
                    </div>
                    <div class="onhover-show-div notification-dropdown">
                        <div class="dropdown-title">
                            <h3>Notification</h3><a class="f-right" href="#">
                                <i data-feather="bell"></i></a>
                        </div>
                        <ul class="custom-scrollbar">
                            <li>
                                <div class="media">
                                    <div class="notification-img bg-light-primary"><img src="assets/images/avtar/man.png" alt=""></div>
                                    <div class="media-body">
                                        <h5> <a class="f-14 m-0" href="user-profile.html">Allie Grater</a></h5>
                                        <p>Lorem ipsum dolor sit amet...</p>
                                        <span class="mt-1">10:20</span>
                                    </div>
                                    <div class="notification-right d-none"><a href="#"><i data-feather="x"></i></a></div>
                                </div>
                            </li>
                            <li class="p-0">
                                <a class="btn btn-primary" href="#">View all</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="maximize">
                    <a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()">
                        <svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <g>
                                    <path d="M2.99609 8.71995C3.56609 5.23995 5.28609 3.51995 8.76609 2.94995" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M8.76616 20.99C5.28616 20.41 3.56616 18.7 2.99616 15.22L2.99516 15.224C2.87416 14.504 2.80516 13.694 2.78516 12.804" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M21.2446 12.804C21.2246 13.694 21.1546 14.504 21.0346 15.224L21.0366 15.22C20.4656 18.7 18.7456 20.41 15.2656 20.99" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M15.2661 2.94995C18.7461 3.51995 20.4661 5.23995 21.0361 8.71995" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </g>
                            </g>
                        </svg>
                    </a>
                </li>
                <li class="profile-nav onhover-dropdown pe-0 py-0 me-0">
                    <div class="media profile-media">
                        <img src="<?php echo $user_profile; ?>" alt="" style="width:35px; height:35px; border-radius:50%;" class="shadow">
                        <!-- <svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <g>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.55851 21.4562C5.88651 21.4562 2.74951 20.9012 2.74951 18.6772C2.74951 16.4532 5.86651 14.4492 9.55851 14.4492C13.2305 14.4492 16.3665 16.4342 16.3665 18.6572C16.3665 20.8802 13.2505 21.4562 9.55851 21.4562Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.55849 11.2776C11.9685 11.2776 13.9225 9.32356 13.9225 6.91356C13.9225 4.50356 11.9685 2.54956 9.55849 2.54956C7.14849 2.54956 5.19449 4.50356 5.19449 6.91356C5.18549 9.31556 7.12649 11.2696 9.52749 11.2776H9.55849Z" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M16.8013 10.0789C18.2043 9.70388 19.2383 8.42488 19.2383 6.90288C19.2393 5.31488 18.1123 3.98888 16.6143 3.68188" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M17.4608 13.6536C19.4488 13.6536 21.1468 15.0016 21.1468 16.2046C21.1468 16.9136 20.5618 17.6416 19.6718 17.8506" stroke="#130F26" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </g>
                            </g>
                        </svg> -->
                    </div>
                    <ul class="profile-dropdown onhover-show-div" style="width: 14rem !important;">
                        <li>
                            <a href="view_profile.php">
                                <i data-feather="user"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript: void(0)" data-bs-toggle="modal" data-bs-target="#ModalChangePassword">
                                <i data-feather="lock"></i>
                                <span>Change Password</span>
                            </a>
                        </li>
                        <li>
                            <a href="./logout/">
                                <i data-feather="log-in"></i>
                                <span>Log Out</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- <script class="result-template" type="text/x-handlebars-template">
            <div class="ProfileCard u-cf">                        
                <div class="ProfileCard-avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0">
                        <path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path>
                        <polygon points="12 15 17 21 7 21 12 15"></polygon>
                    </svg>
                </div>
                <div class="ProfileCard-details">
                    <div class="ProfileCard-realName">{{name}}</div>
                </div>
            </div>
        </script>
        <script class="empty-template" type="text/x-handlebars-template">
            <div class="EmptyMessage">
                Your search turned up 0 results. This most likely means the backend is down, yikes!
            </div>
        </script> -->
    </div>
</div>
<!-- Page Header Ends -->

<!-- BEGIN ModalChangePassword -->
    <div class="modal fade" id="ModalChangePassword" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Change Password</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="Current Password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" required>
                            <!-- <small><a href="#">Forgot your password?</a></small> -->
                        </div>
                        <div class="mb-3">
                            <label for="New Password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="Verify Password" class="form-label">Verify Password</label>
                            <input type="password" class="form-control" id="verify_password" required>
                        </div>
                        <div style="float:right;">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success" id="btn-save-password" onclick="callAjaxPassword()">Save changes</button>
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
<!-- END ModalChangePassword -->

<script type="text/javascript">
    function callAjaxPassword() {
        var action = "change_password_user";
        var current_password = document.getElementById("current_password");
        var new_password = document.getElementById("new_password");
        var verify_password = document.getElementById("verify_password");
        var btn_save = document.getElementById("btn-save-password");

        if (current_password.value == '' || current_password.value.trim() == '') {
            current_password.focus();
            current_password.scrollIntoView();
            //SHOW NOTIFICATION
            showNotif("Enter Current Password", "error")
            return false;
        }
        else if (new_password.value == '' || new_password.value.trim() == '') {
            new_password.focus();
            new_password.scrollIntoView();
            //SHOW NOTIFICATION
            showNotif("Enter New Password", "error")
            return false;
        }else if (verify_password.value == '' || verify_password.value.trim() == '') {
            verify_password.focus();
            verify_password.scrollIntoView();
            //SHOW NOTIFICATION
            showNotif("Enter Verify Password", "error")
            return false;
        }
        
        if (new_password.value != verify_password.value){
            //SHOW NOTIFICATION
            showNotif("New Password and Verify Password Are Different", "error")
            return false;
        }
        // using this page stop being refreshing 
        event.preventDefault();
        
        btn_save.innerHTML = "SAVING...";
        btn_save.disabled = true;

        // Call ajax for pass data to other place
        $.ajax({
            type: "POST",
            url: "../ajax.php",
            data: {
                action: action,
                current_password:current_password.value,
                new_password:new_password.value
            }, // getting filed value in serialize form And Passing additional Value of 'action' To perform Task Accordingly
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
                btn_save.innerHTML = "Save Changes";
                btn_save.disabled = false;
                
                if (response) {
                    // alert(response);

                    // STOP RELOADING
                    // location.reload();

                    // TOASTIFY NOTIFICATION
                    if (response == "Error") {
                        showNotif("Error! Current Password Don't Match", "error");
                        return false;
                    } else if (response == "Success"){
                        showNotif("Password changed successfully", "success");
                        // CLOSE MODAL
                        $('.btn-close').click();
                        current_password.value = '';
                        new_password.value = '';
                        verify_password.value = '';
                    }
                } else {
                    showNotif("Error Occured... Try Again", "error");
                }
            }
        });
    }
</script>
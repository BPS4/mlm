<nav class="navbar navbar-expand navbar-light navbar-bg no_print sticky-top">
    <a class="sidebar-toggle">
        <i class="hamburger align-self-center"></i>
    </a>
    <div class="input-group input-group-navbar">
        <a href="./" style="text-decoration:none;">
            <input type="text" class="form-control show-pointer" placeholder="Admin Dashboard" aria-label="Search" readonly style="font-weight: bold;text-align: center;">
        </a>
    </div>
    <div class="navbar-collapse collapse">
        <ul class="navbar-nav navbar-align ms-md-100">
            <li class="nav-item dropdown">
                <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                    <i class="align-middle" data-feather="settings"></i>
                </a>

                <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                    <img src="../../assets/images/logo-new.png" class="avatar img-fluid rounded-circle me-1" alt="<?php echo $name; ?>"> <span class="text-dark"><?php echo $name; ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="./" data-bs-toggle="modal" data-bs-target="#ModalChangePassword">
                        <i class="align-middle me-1" data-feather="lock"></i> Change Password
                    </a>

                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item" href="../logout/">
                        <i class="align-middle me-1" data-feather="log-out"></i>Sign out
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>

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
        var action = "change_password";
        var current_password = document.getElementById("current_password");
        var new_password = document.getElementById("new_password");
        var verify_password = document.getElementById("verify_password");
        var btn_save = document.getElementById("btn-save-password");

        if (current_password.value == '' || current_password.value.trim() == '') {
            current_password.focus();
            current_password.scrollIntoView();
            //SHOW NOTIFICATION
            showNotif("Enter Current Password", "danger")
            return false;
        }
        else if (new_password.value == '' || new_password.value.trim() == '') {
            new_password.focus();
            new_password.scrollIntoView();
            //SHOW NOTIFICATION
            showNotif("Enter New Password", "danger")
            return false;
        }else if (verify_password.value == '' || verify_password.value.trim() == '') {
            verify_password.focus();
            verify_password.scrollIntoView();
            //SHOW NOTIFICATION
            showNotif("Enter Verify Password", "danger")
            return false;
        }
        
        if (new_password.value != verify_password.value){
            //SHOW NOTIFICATION
            showNotif("New Password and Verify Password Are Different", "danger")
            return false;
        }
        // using this page stop being refreshing 
        event.preventDefault();
        
        btn_save.innerHTML = "SAVING...";
        btn_save.disabled = true;

        // Call ajax for pass data to other place
        $.ajax({
            type: "POST",
            url: "ajax.php",
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
                        showNotif("Error! Current Password Don't Match", "danger");
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
                    showNotif("Error Occured... Try Again", "danger");
                }
            }
        });
    }
</script>
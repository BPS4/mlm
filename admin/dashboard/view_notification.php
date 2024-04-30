<!DOCTYPE html>
<html lang="en">
<link rel="shortcut icon" href="../../assets/images/favicon.svg" type="image/x-icon">
<?php
	// COLLEGE DASHBOARD PAGE
	ob_start();
	require_once("../../db_connect.php");
	session_start();

	mysqli_query($conn, "set names 'utf8mb4'");  //-------WORKING UTF8 CODE FOR ALL EMOJIS------//

	//-------CURRENT DATE AND TIME TO FEED---------//
	date_default_timezone_set('Asia/Kolkata');
	$current_date = date('Y-m-d H:i:s');
	$current_date_only = date('Y-m-d');
	
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
    if (isset($_POST['submit'])) {
        $message = mysqli_real_escape_string($conn,$message);

        if (mysqli_query($conn, "INSERT INTO `notification_admin`(`message`, `create_date`, `created_by`) VALUES ('$message', '$current_date', '$user_id')")) {
            $msg = "New Record Added Successfully!";
            $msg_type = "success";
            // echo "<script>window.close();</script>";

            stop_form_resubmit();
        } else {
            $msg .= " >> Error in Inserting The Record...Try Again";
            $msg_type = "danger";
        }
    }

    if (isset($_POST['delete'])) {
        $res = mysqli_query($conn, "UPDATE `notification_admin` SET `is_active`='0', `delete_date`='$current_date', `deleted_by`='$user_id' WHERE `id`='$id' AND `delete_date` IS NULL");
        if (mysqli_affected_rows($conn)>0) {
            $msg = "Record Deleted Successfully!";
            $msg_type = "danger";
            
            stop_form_resubmit();

        } else {
            $msg = "Already Deleted!";
            $msg_type = "warning";
        }
    }

    if (isset($_POST['status'])) {
        $status = ($is_active == 0 ? 1 : 0);
        mysqli_query($conn, "UPDATE `notification_admin` SET `is_active`='$status', `update_date`='$current_date', `updated_by`='$user_id' WHERE `id`='$id'");

        stop_form_resubmit();

    }

    if (isset($_POST['submit_update'])) {
        $message = mysqli_real_escape_string($conn,$message);
        
        $res = mysqli_query($conn, "UPDATE `notification_admin` SET `message`='$message', `update_date`='$current_date', `updated_by`='$user_id' WHERE `id`='$id'");
        if (mysqli_affected_rows($conn)>0) {
            $msg = "Record Updated Successfully!";
            $msg_type = "default";
            
            stop_form_resubmit();
            
        }
    }

    $query = mysqli_query($conn, "SELECT * FROM `notification_admin` WHERE `delete_date` IS NULL ORDER BY `create_date` DESC");
    $messages = mysqli_fetch_all($query, MYSQLI_ASSOC);
    $total_count = mysqli_num_rows($query);
    // USED FOR CENTER THE TEXT PDF EXPORT
    echo "<input type='hidden' id='total_count' value='$total_count'>";

    // DEFAULT ORDER COLUMN SR
    $default_Order = 0;

    // LENGTH MENU
    // $length_menu = "all";
?>

<head>
    <?php include_once('head.php'); ?>

    <title>Member Notifications - Admin Dashboard - <?php echo "$name"; ?></title>

    <link rel="canonical" href="highlight.php">
</head>

<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-behavior="sticky">
    <div class="wrapper">
        <?php include_once('sidebar.php'); ?>

        <div class="main">
            <?php include_once('navbar.php'); ?>

            <main class="content">
                <div class="container-fluid p-0">
                    <div class="row mb-2 mb-xl-3">
                        <div class="col-auto d-none d-sm-block">
                            <h3>Member Notifications</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div id="loader"></div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Total Records Present <span class="badge bg-success" style="font-weight: bold;"><?php echo $total_count; ?></span></h5>
                                    <a target="_blank" href='#' class="badge bg-success" style="text-decoration:none;position:absolute;right:0px;margin:15px;" data-bs-toggle="modal" data-bs-target="#ModalAdd">
                                        <i class="align-middle" data-feather="plus-square" style="border-radius: 50%;margin-right:5px;"></i>
                                        Add New
                                    </a>
                                </div>
                                <div class="card-body">

                                    <table id="datatables-column-search-text-inputs" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Sr</th>
                                                <th>Message</th>
                                                <th>Status</th>
                                                <th>Create Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                            <?php
                                            $i = 0;
                                            foreach ($messages as $image) {
                                                $i++;
                                                //DEFAULT VALUES SET
                                                $classStatus = 'bg-success';
                                                $id = $image['id'];
                                                $message = $image['message'];
                                                $is_active = $image['is_active'];
                                                $create_date = $image['create_date'];
                                                $classStatus = ($is_active == 0) ? "bg-danger" : "bg-success";
                                            ?>
                                                <!-- REPEAT ITEM -->
                                                <tr>
                                                    <td class="text-center"><?php echo $i; ?></td>
                                                    <td class="text-start"><?php echo $message; ?></td>
                                                    <td>
                                                        <span class="badge <?php echo $classStatus; ?>" style="white-space: normal;">
                                                            <form action="" method="POST">
                                                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                                <input type="hidden" name="is_active" value="<?php echo $is_active; ?>">
                                                                <input type="submit" name="status" value="<?php echo ($is_active) ? "Active" : "InActive"; ?>" style="background: transparent; border-style:hidden; color:white;">
                                                            </form>
                                                        </span>
                                                    </td>
                                                    <td class="text-center"><?php echo $create_date; ?></td>
                                                    <td class="text-center">
                                                        <a class="btn btn-warning" href="#" data-bs-toggle="modal" data-bs-target="#ModalEdit" data-id="<?php echo $id; ?>" data-message="<?php echo $message; ?>" style="margin: 5px;">Edit</a>
                                                        <form action="" method="POST">
                                                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                            <input type="submit" name="delete" value="Delete" class="btn btn-danger" onclick="return confirm('Are you sure to Delete the Record at Row#<?php echo $i; ?>? \r\nIt will permanently delete the record!')">
                                                        </form>
                                                    </td>
                                                </tr>

                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot class="no_print">
                                            <tr>
                                                <th>Sr</th>
                                                <th>Message</th>
                                                <th>Status</th>
                                                <th>Create Date</th>
                                                <th>Action</th>
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

    <!-- BEGIN ModalAdd -->
    <div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add new Record</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="row">
                            <div class="mb-3 col-md-12 col-xs-12 col-sm-12">
                                <label class="form-label" for="message">Message</label>
                                <textarea name="message" id="message" cols="30" rows="3" placeholder="Enter Your Message For Members" class="form-control"></textarea>
                            </div>
                        </div>
                        <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                        <div style="float:right">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="submit" class="btn btn-success" id="btn-save">Save changes</button>
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
    <!-- END ModalAdd -->

    <!-- BEGIN ModalEdit -->
        <div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Update Record</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="id_edit" value="">
                            <div class="row">
                                <div class="mb-3 col-md-12 col-xs-12 col-sm-12">
                                    <label class="form-label" for="message_edit">Message</label>
                                    <textarea name="message" id="message_edit" cols="30" rows="3" class="form-control"></textarea>
                                </div>
                            </div>
                            <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                            <div style="float:right">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="submit_update" class="btn btn-success" id="btn_save_edit">Save changes</button>
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
    <!-- END ModalEdit -->

    <script>
        // PASS DATA TO MODAL POPUP
            $(function () {
                $('#ModalEdit').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var id = button.data('id'); // Extract info from data-* attributes
                    var message = button.data('message'); // Extract info from data-* attributes
                    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
					// package = "Package "+package;
					// payment = "20% Amount of "+package+"= Rs."+payment;
                    var modal = $(this);
                    // modal.find(id).val(src);
                    // modal.find('#image').src=src;
					document.getElementById("id_edit").value = id;
					document.getElementById("message_edit").value = message;
                });
            });
        // PASS DATA TO MODAL POPUP
    </script>
    <?php 
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
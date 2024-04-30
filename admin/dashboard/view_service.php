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

    $url_dir = "../../assets/files/service";
    $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "JPG" => "image/jpg", "JPEG" => "image/jpeg", "png" => "image/png", "PNG" => "image/png", "webp" => "image/webp");

    $msg = $msg_type = "";
    $error = false;
    if (isset($_POST['submit'])) {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $price_start = mysqli_real_escape_string($conn, $_POST['price_start']);

        /*IMAGE UPLOAD*/
        // Check if file was uploaded without errors
        if ((isset($_FILES["file"]) && $_FILES["file"]["error"] == 0)) {
            $filename = $_FILES["file"]["name"];
            $filetype = $_FILES["file"]["type"];
            $filesize = $_FILES["file"]["size"];

            // Verify file extension
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            
            // Extract FileName
            $file_basename = basename($filename, ".$ext");            

            if (!array_key_exists($ext, $allowed)) {
                $error = true;
                $msg .= " >> Only JPEG/JPG/PNG/WEBP Formats are Allowed!!!";
                $msg_type = "danger";
            }

            // Verify file size - 100kB maximum
            $minsize = 1 * 1024;
            $maxsize = 300 * 1024;

            if ($filesize > $maxsize || $filesize < $minsize) {
                $error = true;
                $msg .= " >> Error!!! File size should not be greater than 300kb.";
                $msg_type = "danger";
            }

            //----------IMAGE IS JPEG/JPG AND NO ERROR-----------//
            if (!$error) {
                // Check whether file exists before uploading it
                // if(file_exists("../../collegeportal/images/files/$batch" . $filename))
                // {
                //     $error_message = "$reg_no already registered!!!";
                // } 
                // else 
                $uploadedfile = $_FILES["file"]["tmp_name"];

                $current_timestamp = time();
                // $filename = $current_timestamp.".$ext";
                // $filename = "$file_basename-$current_timestamp".".$ext";
                $filename = "$unique_id-$current_timestamp".".$ext";
                
                if (mysqli_query($conn, "INSERT INTO `services`(`title`, `description`, `price_start`, `url_file`, `create_date`, `created_by`) VALUES ('$title', '$description', '$price_start', '$filename', '$current_date', '$user_id')")) {

                    $file_dir = "$url_dir/$filename";

                    // UPLOAD FILE
                    if ((move_uploaded_file($_FILES['file']['tmp_name'], $file_dir))) {
                        $msg = "New Record Added Successfully!";
                        $msg_type = "success";
                        // echo "<script>window.close();</script>";

                        stop_form_resubmit();
                    }
                } else {
                    $msg .= " >> Error in Inserting The Record...Try Again";
                    $msg_type = "danger";
                }

                // correctImageOrientation($file_dir);

            } else {
                $msg .= " >> There was a problem uploading your record. Please try again.";
                $msg_type = "danger";
            }
        } else {
            //ERROR IN fileS
            $msg .= " >> Error in file " . $_FILES["file"]["error"];
            $msg_type = "danger";
        }

        /*	   IMAGE UPLOAD        */
    }

    if (isset($_POST['delete'])) {
        $res = mysqli_query($conn, "UPDATE `services` SET `is_active`='0', `delete_date`='$current_date', `deleted_by`='$user_id' WHERE `id`='$id' AND `delete_date` IS NULL");
        if (mysqli_affected_rows($conn)>0) {
            // unlink($url_file);
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
        mysqli_query($conn, "UPDATE `services` SET `is_active`='$status', `update_date`='$current_date', `updated_by`='$user_id' WHERE `id`='$id'");
        stop_form_resubmit();
    }

    if (isset($_POST['submit_update'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id_update']);
        $url_file_delete = mysqli_real_escape_string($conn, $_POST['url_update']);
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $price_start = mysqli_real_escape_string($conn, $_POST['price_start']);

        $filename = $set_filename = "";
        /*IMAGE UPLOAD*/
        // Check if file was uploaded without errors
        if ((isset($_FILES["file"]) && $_FILES["file"]["error"] == 0)) {
            $filename = $_FILES["file"]["name"];
            $filetype = $_FILES["file"]["type"];
            $filesize = $_FILES["file"]["size"];

            // Verify file extension
            $ext = pathinfo($filename, PATHINFO_EXTENSION);

            if (!array_key_exists($ext, $allowed)) {
                $error = true;
                $msg .= " >> Only JPEG/JPG/PNG/WEBP Formats are Allowed!!!";
                $msg_type = "danger";
            }

            // Verify file size - 100kB maximum
            $minsize = 1 * 1024;
            $maxsize = 300 * 1024;

            if ($filesize > $maxsize || $filesize < $minsize) {
                $error = true;
                $msg .= " >> Error!!! File size should not be greater than 300kb.";
                $msg_type = "danger";
            }

            //----------IMAGE IS JPEG/JPG AND NO ERROR-----------//
            if (!$error) {
                // Check whether file exists before uploading it
                // if(file_exists("../../collegeportal/images/files/$batch" . $filename))
                // {
                //     $error_message = "$reg_no already registered!!!";
                // } 
                // else 
                $uploadedfile = $_FILES["file"]["tmp_name"];

                $current_timestamp = time();
                $filename = $current_timestamp.".$ext";
                // $filename = "$file_basename-$current_timestamp".".$ext";
                $filename = "$unique_id-$current_timestamp".".$ext";

                $file_dir = "$url_dir/$filename";                           
            } else {
                $msg .= " >> There was a problem uploading your record. Please try again.";
                $msg_type = "danger";

                goto error_occurred;
            }
        }

        if($filename!=""){
            $set_filename = "`url_file`='$filename',";
            // UPLOAD FILE
            if ((move_uploaded_file($_FILES['file']['tmp_name'], $file_dir))) {
                unlink($url_file_delete);
            }
        }        
        // correctImageOrientation($file_dir);
        /*IMAGE UPLOAD*/

        $res = mysqli_query($conn, "UPDATE `services` SET `title`='$title', `description`='$description', `price_start`='$price_start', $set_filename `update_date`='$current_date' WHERE `id`='$id'");
        if (mysqli_affected_rows($conn)>0) {
            $msg = "Record Updated Successfully!";
            $msg_type = "default";
            stop_form_resubmit();
        }else {
            $msg .= " >> Error in Updating The Record...Try Again";
            $msg_type = "danger";
        }
    }

    error_occurred:

    $query = mysqli_query($conn, "SELECT * FROM `services` WHERE `delete_date` IS NULL ORDER BY `create_date` DESC");
    $records = mysqli_fetch_all($query, MYSQLI_ASSOC);
    $total_count = mysqli_num_rows($query);
    // USED FOR CENTER THE TEXT PDF EXPORT
    echo "<input type='hidden' id='total_count' value='$total_count'>";

    // DEFAULT ORDER COLUMN SR
    $default_Order = 0;

    // LENGTH MENU
    // $length_menu = "all";

    function correctImageOrientation($filename) {
        if (function_exists('exif_read_data')) {
            $img = imagecreatefromjpeg($filename);
            imagejpeg($img, $filename, 60);

            $exif = exif_read_data($filename);
            if ($exif && isset($exif['Orientation'])) {
                $orientation = $exif['Orientation'];
                // echo "<br><br>orie- $orientation<br>";
                if ($orientation != 1) {
                    $img = imagecreatefromjpeg($filename);
                    $deg = 0;
                    switch ($orientation) {
                        case 3:
                            $deg = 180;
                            break;
                        case 6:
                            $deg = 270;
                            break;
                        case 8:
                            $deg = 90;
                            break;
                    }
                    if ($deg) {
                        $img = imagerotate($img, $deg, 0);
                    }
                    // then rewrite the rotated image back to the disk as $filename 
                    imagejpeg($img, $filename, 95);
                } // if there is some rotation necessary
            } // if have the exif orientation info
        } // if function exists      
    }

?>

<head>
    <?php include_once('head.php'); ?>

    <title>Services - Admin Dashboard - <?php echo "$name"; ?></title>

    <link rel="canonical" href="view_service.php">
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
                            <h3>Services</h3>
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
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Starting Price</th>
                                                <th>Image</th>
                                                <th>Status</th>
                                                <th>Create Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                            <?php
                                            $i = 0;
                                            foreach ($records as $image) {
                                                $i++;
                                                //DEFAULT VALUES SET
                                                $classStatus = 'bg-success';
                                                $id = $image['id'];
                                                $title = $image['title'];
                                                $description = $image['description'];
                                                $price_start = $image['price_start'];
                                                $url_file = $image['url_file'];
                                                $is_active = $image['is_active'];
                                                $create_date = $image['create_date'];
                                                $url_file = "$url_dir/$url_file";
                                                $classStatus = ($is_active == 0) ? "bg-danger" : "bg-success";
                                            ?>
                                                <!-- REPEAT ITEM -->
                                                <tr>
                                                    <td class="text-center"><?php echo $i; ?></td>
                                                    <td class="text-start"><?php echo $title; ?></td>
                                                    <td class="text-start"><?php echo $description; ?></td>
                                                    <td class="text-start"><?php echo $price_start; ?></td>
                                                    <td class="text-center"><img class="rounded me-2 mb-2" width="150" height="100" src="<?php echo $url_file; ?>" alt="<?php echo $title; ?>"></td>
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
                                                        <a class="btn btn-warning" href="#" data-bs-toggle="modal" data-bs-target="#ModalEdit" style="margin: 5px;" data-id="<?php echo $id; ?>" data-title="<?php echo $title; ?>" data-description="<?php echo $description; ?>" data-url="<?php echo $url_file; ?>" data-price="<?php echo $price_start; ?>">Edit</a>
                                                        <form action="" method="POST">
                                                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                            <input type="hidden" name="url_file" value="<?php echo $url_file; ?>">
                                                            <input type="submit" name="delete" value="Delete" class="btn btn-danger" onclick="return confirm('Are you sure to Delete the Record at Row#<?php echo $i; ?>? \r\nIt will permanently delete the file associated!')">
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
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Starting Price</th>
                                                <th>Image</th>
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
                                        <label class="form-label" for="title">Title <span style="color: red;">*</span></label>
                                        <input type="text" name="title" id="title" class="form-control" placeholder="Title of Service" required>
                                    </div>
                                    <div class="mb-3 col-md-12 col-xs-12 col-sm-12">
                                        <label class="form-label" for="description">Description <span style="color: red;">*</span></label>
                                        <textarea name="description" id="description" class="form-control" placeholder="Description of Service" rows="2"></textarea>
                                    </div>
                                    <div class="mb-3 col-md-6 col-xs-12 col-sm-12">
                                        <label class="form-label" for="price_start">Price Starts From <span style="color: red;">*</span></label>
                                        <input type="text" name="price_start" id="price_start" class="form-control" placeholder="Prices Starting From" required>
                                    </div>
                                    <!-- <div class="mb-3 col-md-6 offset-md-3 col-xs-12 col-sm-12"> -->
                                    <div class="mb-3 col-md-6 col-xs-12 col-sm-12">
                                        <label class="form-label" for="file">File <span style="color: red;">(Image size upto 300kb only & 1600x900 resolution)</span></label>
                                        <input type="file" name="file" id="file" class="form-control" required accept="image/*" required onchange="check_image_size_before_upload(this.id,300);">
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
                            <input type="hidden" name="id_update" id="id_update">
                            <input type="hidden" name="url_update" id="url_update">
                            <div class="row">
                                <div class="mb-3 col-md-12 col-xs-12 col-sm-12">
                                    <label class="form-label" for="title_update">Title <span style="color: red;">*</span></label>
                                    <input type="text" name="title" id="title_update" class="form-control" placeholder="Title of Service" required>
                                </div>
                                <div class="mb-3 col-md-12 col-xs-12 col-sm-12">
                                    <label class="form-label" for="description_update">Description <span style="color: red;">*</span></label>
                                    <textarea name="description" id="description_update" class="form-control" placeholder="Description of Service" rows="2"></textarea>
                                </div>
                                <div class="mb-3 col-md-6 col-xs-12 col-sm-12">
                                    <label class="form-label" for="price_start_update">Price Starts From <span style="color: red;">*</span></label>
                                    <input type="text" name="price_start" id="price_start_update" class="form-control" placeholder="Prices Starting From" required>
                                </div>
                                <!-- <div class="mb-3 col-md-6 offset-md-3 col-xs-12 col-sm-12"> -->
                                <div class="mb-3 col-md-6 col-xs-12 col-sm-12">
                                    <label class="form-label" for="file">File <span style="color: red;">(Image size upto 300kb only & 1600x900 resolution)</span></label>
                                    <input type="file" name="file" id="file_update" class="form-control" accept="image/*" required onchange="check_image_size_before_upload(this.id,300);">
                                </div>
                            </div>
                            <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                            <div style="float:right">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="submit_update" class="btn btn-success" id="btn_save_update">Save changes</button>
                            </div>
                        </form>
                        </p>
                    </div>
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
                    var title = button.data('title'); // Extract info from data-* attributes
                    var description = button.data('description'); // Extract info from data-* attributes
                    var url = button.data('url'); // Extract info from data-* attributes
                    var price = button.data('price'); // Extract info from data-* attributes
                    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
					// package = "Package "+package;
					// payment = "20% Amount of "+package+"= Rs."+payment;
                    var modal = $(this);

                    // modal.find(id).val(id);
                    modal.find("#id_update").val(id);
                    modal.find("#url_update").val(url);
                    modal.find("#title_update").val(title);
                    modal.find("#description_update").val(description);
                    modal.find("#price_start_update").val(price);
                    // SET IMAGE SRC
					// $("#image").attr("src", src);
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
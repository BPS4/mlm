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
        if (isset($_GET['uid'])) {
            $user_id = json_decode(base64_decode($_GET['uid']));    //--DECODE THE SECRET CODE PASSED--//
            $user_id_encode = base64_encode(json_encode($user_id));     //ENCODE
            
        } else {
            echo "<script>alert('Required Parameters Not Passed!!!');</script>";
            echo "Redirecting...Please Wait";
            header("Refresh:1, url=../dashboard");
            exit;
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
    if (isset($_POST['submit'])) {
        $name_add = mysqli_real_escape_string($conn, $_POST['name_add']);
        $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        $state = mysqli_real_escape_string($conn, $_POST['state']);
        $pin_code = mysqli_real_escape_string($conn, $_POST['pin_code']);        

        $query_old_user = mysqli_query($conn, "SELECT `user_no` FROM `users` ORDER BY `user_no` DESC");
        if ($rw = mysqli_fetch_array($query_old_user)) {
            $user_no = $rw['user_no'];

            // PREFIX ZEROES ON LEFT
                // $num = 4;
                // $num_padded = sprintf("%06d", $num); // returns 04
                // $number = 45678;
                // echo(str_pad($number, 6, '0', STR_PAD_LEFT)); // returns 04
            // PREFIX ZEROES ON LEFT
            
            if ($form_no < 5) {
                $query = mysqli_query($conn, "SELECT `user_id` FROM `users` WHERE `sponsor_id`='$sponsor_id_add' AND `form_no`='$form_no'");
                $count_already_inserted = mysqli_num_rows($query);
            } else {
                $query = mysqli_query($conn, "SELECT `user_id` FROM `users` WHERE `sponsor_id`='$sponsor_id_add' AND `form_no`='$form_no' AND `packages`='$package_amount' AND `name`='$name_add' AND `mobile`='$mobile' AND `email`='$email' AND `address`='$address' AND `city`='$city' AND `state`='$state' AND `pin_code`='$pin_code'");
                $count_already_inserted = mysqli_num_rows($query);
            }
            
            $user_no_new = $user_no+1;
            $user_no_new = sprintf("%06d", $user_no_new); // returns 04

            $user_id_new = "LG$user_no_new";
            
            $password = generateRandomString(6);            
            
            if ($count_already_inserted) {
                $msg = " >> Member Already Added For Form No. $form_no <br>Add Another Member...";
                $msg_type = "default";
                
                if ($form_no == 5) {
                    $msg = " >> Direct Member Already Added With Same Details <br>Add Another Member...";
                    $msg_type = "danger";
                }
            } else {
                if ($form_no < 4) {
                    $total_transaction = 6; // 1-20%-Company | 4-10%-4Members | 1-40%-Sponsor
                } else {
                    $total_transaction = 2; // 1-20%-Company | 1-80%-Sponsor
                }
                if(mysqli_query($conn,"INSERT INTO `users`(`sponsor_id`, `user_id`, `user_no`, `form_no`, `total_transaction`, `packages`, `name`, `mobile`, `email`, `password`, `address`, `city`, `state`, `pin_code`, `create_date`) 
                        VALUES ('$sponsor_id_add', '$user_id_new', '$user_no_new', '$form_no', '$total_transaction', '$package_amount', '$name_add', '$mobile', '$email', '$password', '$address', '$city', '$state', '$pin_code', '$current_date')")) {
                    mysqli_query($conn,"INSERT INTO `user_package`(`user_id`, `transaction_mode`, `package_amount`, `create_date`) VALUES ('$user_id_new','0','$package_amount','$current_date')");

                    // GET MEMBER LEVELS IN TREE
                        $level=1;
                        $levels_array[1] = $user_id_new;
                        while ($level < 13) {
                            $sp_id_obtained = get_level_members($conn,$level,$levels_array[$level],$package_amount);
                            $level++;
                            $levels_array[$level] = $sp_id_obtained;
                        }
                    // GET MEMBER LEVELS IN TREE

                    // INSERT INTO LEVELS TABLE
                        $query_insert_level = "INSERT INTO `levels`(`level`, `package_amount`, `form_no`, `sponsor_id`, `user_id`, `create_date`) VALUES ";
                        $value_insert = "";

                        foreach ($levels_array as $key => $value) {
                            $previous_level = $key;
                            $previous_level_member = $value;
                            $form_no_level = 4;
                            if ($previous_level>=2 && $previous_level<=5) {
                                $form_no_level = 1;
                            } else if ($previous_level>=6 && $previous_level<=9) {
                                $form_no_level = 2;
                            } else if ($previous_level>=10 && $previous_level<=13) {
                                $form_no_level = 3;
                            }
                            
                            $value_insert .= "('$previous_level','$package_amount','$form_no_level','$user_id_new','$previous_level_member','$current_date'),";
                        }
                        $value_insert = rtrim($value_insert,",");
                        $query_insert_level .= "$value_insert";
                        
                        mysqli_query($conn,$query_insert_level);
                    // INSERT INTO LEVELS TABLE

                    // show_reg_send_msg($user_id_new,$package_amount,$name_add,$mobile,$email,$password);
                    $show_modal = 1;
                    $success_user_id = $user_id_new;
                    $success_name = $name_add;
                    $success_password = $password;

                    $msg = " >> Member Added Successfully!";
                    $msg_type = "success";
                } else {
                    $msg .= " >> Error in Adding Member or Already Registered...Try Again";
                    $msg_type = "danger";
                }
            }
        } else {
            $msg .= " >> There was a problem uploading your record. Please try again.";
            $msg_type = "danger";
        }
    }

    if (isset($_POST['delete'])) {
        $res = mysqli_query($conn, "UPDATE `gallery` SET `delete_date`='$current_date', `deleted_by`='$user_id' WHERE `id`='$id'");
        if (mysqli_affected_rows($conn)>0) {
            unlink($url_file);
            $msg = "Record Deleted Successfully!";
            $msg_type = "danger";
        }
    }

    if (isset($_POST['status'])) {
        $status = ($is_active == 0 ? 1 : 0);
        mysqli_query($conn, "UPDATE `gallery` SET `is_active`='$status', `update_date`='$current_date', `updated_by`='$user_id' WHERE `id`='$id'");
    }

    if (isset($_POST['submit_update'])) {
        $filename = $set_filename = "";
        /*IMAGE UPLOAD*/
        // Check if file was uploaded without errors
        if ((isset($_FILES["file"]) && $_FILES["file"]["error"] == 0)) {
            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "JPG" => "image/jpg", "JPEG" => "image/jpeg", "png" => "image/png", "PNG" => "image/png", "webp" => "image/webp");
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
                $filename = "$file_basename-$current_timestamp".".$ext";
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
                unlink($url_file);                
            }
        }        
        // correctImageOrientation($file_dir);
        /*IMAGE UPLOAD*/

        $res = mysqli_query($conn, "UPDATE `gallery` SET `title`='$title', $set_filename `update_date`='$current_date', `updated_by`='$user_id' WHERE `id`='$id'");
        if (mysqli_affected_rows($conn)>0) {
            $msg = "Record Updated Successfully!";
            $msg_type = "default";
        }else {
            $msg .= " >> Error in Updating The Record...Try Again";
            $msg_type = "danger";
        }
    }
    
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

    error_occurred:
    $query = mysqli_query($conn, "SELECT `user_id`,`password`,`form_no`,`packages`,`name`,`mobile`,`email`,`create_date` FROM `users` WHERE `packages` LIKE '%$package_amount%' ORDER BY `create_date` DESC");
    $members = mysqli_fetch_all($query, MYSQLI_ASSOC);
    $total_count = mysqli_num_rows($query);

    $sponsor_name = get_user_name($conn,$user_id);

    // USED FOR CENTER THE TEXT PDF EXPORT
    echo "<input type='hidden' id='total_count' value='$total_count'>";

    // DEFAULT ORDER COLUMN SR
        $default_Order = 0;

    // LENGTH MENU
    // $length_menu = "all";

?>

<head>
    <?php include_once('head.php'); ?>

    <title><?php  echo "Members List - Package-$package_amount - Admin - Maxizone"; ?></title>

    <link rel="canonical" href="member.php">

    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
            .dataTables_length, .dataTables_filter, .dt-buttons, .no_print {
                display: none;
                visibility: hidden;
            }
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
                            <h3>Members List - Package-<?php echo $package_amount;?> - Maxizone</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div id="loader"></div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Total Records Present <span class="badge bg-success" style="font-weight: bold;"><?php echo $total_count; ?></span></h5>
                                    <!-- <a target="_blank" href='#' class="badge bg-success" style="text-decoration:none;position:absolute;right:0px;margin:15px;" data-bs-toggle="modal" data-bs-target="#ModalAdd">
                                        <i class="align-middle" data-feather="plus-square" style="border-radius: 50%;margin-right:5px;"></i>
                                        Add New
                                    </a> -->
                                </div>
                                <div class="card-body">

                                    <table id="datatables-a4-landscape" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Sr</th>
                                                <th>Sponsor ID</th>
                                                <th>User ID</th>
                                                <th>Password</th>
                                                <th>Name</th>
                                                <th>Form No.</th>
                                                <th>Mobile</th>
                                                <th>Email</th>
                                                <th>Create Date</th>
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
                                                $user_id_member = $member['user_id'];
                                                $password_member = $member['password'];
                                                $form_no_member = $member['form_no'];
                                                $packages_member = $member['packages'];
                                                $name_member = $member['name'];
                                                $mobile_member = $member['mobile'];
                                                $email_member = $member['email'];
                                                $create_date = $member['create_date'];
                                                switch ($form_no_member) {
                                                    case '1':
                                                        $classStatus = "bg-success";
                                                        $form_detail = "Form-1";
                                                        $count_member++;
                                                        $disabled_option1 = "class='bg-danger text-white' disabled";
                                                        break;
                                                    
                                                    case '2':
                                                        $classStatus = "bg-danger";
                                                        $form_detail = "Form-2";
                                                        $count_member++;
                                                        $disabled_option2 = "class='bg-danger text-white' disabled";
                                                        break;
                                                    
                                                    case '3':
                                                        $classStatus = "bg-warning";
                                                        $form_detail = "Form-3";
                                                        $count_member++;
                                                        $disabled_option3 = "class='bg-danger text-white' disabled";
                                                        break;
                                                    
                                                    case '4':
                                                        $classStatus = "bg-info";
                                                        $form_detail = "Form-4";
                                                        $count_member++;
                                                        $disabled_option4 = "class='bg-danger text-white' disabled";
                                                        break;
                                                    
                                                    default:
                                                        $classStatus = "bg-dark";
                                                        $form_detail = "Direct Member";
                                                        $count_member++;
                                                        break;
                                                }
                                            ?>
                                                <!-- REPEAT ITEM -->
                                                <tr>
                                                    <td class="text-center"><?php echo $i; ?></td>
                                                    <td class="text-center"><?php echo $user_id; ?></td>
                                                    <td class="text-center"><?php echo $user_id_member; ?></td>
													<td class="text-center" onclick='copyToClipboard("<?php echo "$password_member"; ?>")' style="cursor:pointer;" title='Click to copy'>
                                                        <span class="badge bg-info" style="white-space: normal;">
                                                            <?php echo $password_member; ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center"><?php echo $name_member; ?></td>
                                                    <td class="text-center">
                                                        <span class="badge <?php echo $classStatus; ?>" style="white-space: normal;">
                                                            <?php echo "$form_detail"; ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center"><?php echo $mobile_member; ?></td>
                                                    <td class="text-center"><?php echo $email_member; ?></td>
                                                    <td class="text-center"><?php echo $create_date; ?></td>
                                                </tr>

                                            <?php
                                            }

                                            
                                            if ($count_member==3) {
                                                $disabled_option4 = "";
                                            } else if ($count_member>3) {
                                                $disabled_option5 = "";
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot class="no_print">
                                            <tr>
                                                <th>Sr</th>
                                                <th>Sponsor ID</th>
                                                <th>User ID</th>
                                                <th>Password</th>
                                                <th>Name</th>
                                                <th>Form No.</th>
                                                <th>Mobile</th>
                                                <th>Email</th>
                                                <th>Create Date</th>
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
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="sponsor_id_add">Sponsor ID</label>
                                    <input type="text" name="sponsor_id_add" id="sponsor_id_add" value="<?php echo $user_id;?>" class="form-control text-readonly" readonly>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="sponsor_name">Sponsor Name</label>
                                    <input type="text" name="sponsor_name" id="sponsor_name" value="<?php echo $sponsor_name;?>" class="form-control text-readonly" readonly>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="package_amount">Package Selected</label>
                                    <input type="text" name="package_amount" id="package_amount" value="<?php echo $package_amount;?>" class="form-control text-readonly" readonly>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="form_no">Form No.</label>
                                    <select name="form_no" id="form_no" class="form-control" required>
                                        <option value="" hidden>Select Form No.</option>
                                        <option value="1" <?php echo $disabled_option1;?> >Form-1</option>
                                        <option value="2" <?php echo $disabled_option2;?> >Form-2</option>
                                        <option value="3" <?php echo $disabled_option3;?> >Form-3</option>
                                        <option value="4" <?php echo $disabled_option4;?> >Form-4</option>
                                        <option value="5" <?php echo $disabled_option5;?> >Additional Member</option>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="name_add">Member Name</label>
                                    <input type="text" name="name_add" id="name_add" class="form-control">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="mobile">Member Mobile</label>
                                    <input type="number" name="mobile" id="mobile" class="form-control">
                                </div>
                                <div class="mb-3 col-md-12">
                                    <label class="form-label" for="email">Member Email</label>
                                    <input type="text" name="email" id="email" class="form-control">
                                </div>
                                <div class="mb-3 col-md-12">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea rows="1" class="form-control" id="address" name="address" placeholder="Member Complete Address" required></textarea>
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
                            <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                            <div style="float:right">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="submit" class="btn btn-success" id="btn_save">Save changes</button>
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

    <script>
        // DataTables with Column Search by Text Inputs
            document.addEventListener("DOMContentLoaded", function () {
                // DataTables
                var $filename = document.title;
                var default_Order = '<?php echo $default_Order;?>';
                var length_menu = '<?php echo isset($length_menu)?$length_menu:"";?>';
                var table = $('#datatables-a4-landscape').DataTable({
                    dom: /*'lBfrtip'*/ 'lfrtip',
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
                        },
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

                            var filteredRows = getNumFilteredRows('#datatables-a4-landscape');

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
                            // var countRows = $('#datatables-column-search-text-inputs tr').length;
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
                    {
                        extend: 'colvis',
                        text: 'COLUMNS',
                        className: 'btn-info',
                        key: { // press L for COLUMNS
                            key: 'l',
                            altKey: false
                        },
                        postfixButtons: [{
                            extend: 'colvisRestore',
                            text: 'Show All'
                        }]
                    },
                    ],
                    "columnDefs": [{
                        // //   DISABLE ORDERING OF ACTION COLUMN
                        // "targets": [0],
                        // // DISABLE SORTING ON LAST COLUMN
                        // // "targets": [-1],
                        // "orderable": false,
                    },],
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
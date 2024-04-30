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

    $query = mysqli_query($conn, 'SELECT * FROM `leads` WHERE `delete_date` IS NULL ORDER BY `create_date` DESC');
    $leads = mysqli_fetch_all($query, MYSQLI_ASSOC);
    $total_count = mysqli_num_rows($query);
    // USED FOR CENTER THE TEXT PDF EXPORT
    echo "<input type='hidden' id='total_count' value='$total_count'>";

    // DEFAULT ORDER COLUMN SR
    $default_Order = 0;

    // LENGTH MENU
    // $length_menu = "all";

    function get_service_name($service_id) {
		$conn = $GLOBALS['conn'];
		$query = "SELECT `title` FROM `services` WHERE `id`='$service_id'";
		$res = mysqli_query($conn,$query);
		$res = mysqli_fetch_array($res);
		extract($res);
		return $title;
	}
?>

<head>
    <?php include_once('head.php'); ?>

    <title>Messages - <?php echo "$user_id-$name" ?>  - Dashboard - Maxizone</title>

    <link rel="canonical" href="message.php">
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
                            <h3>Messages</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div id="loader"></div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Total Records Present <span class="badge bg-success" style="font-weight: bold;"><?php echo $total_count; ?></span></h5>
                                    <a href='./' class="badge bg-warning" style="text-decoration:none;position:absolute;left:0px;margin-left:15px;">
                                        <i class="align-middle" data-feather="chevrons-left" style="border-radius: 50%;margin-right:5px;"></i>
                                        Back
                                    </a>
                                </div>
                                <div class="card-body">

                                    <table id="datatables-column-search-text-inputs" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Sr</th>
                                                <th>Query For</th>
                                                <th>Name</th>
                                                <th>Message</th>
                                                <th>Mobile</th>
                                                <th>Email</th>
                                                <th>Create Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                            <?php
                                            $i = 0;
                                            foreach ($leads as $lead) {
                                                $i++;
                                                //DEFAULT VALUES SET
                                                $classStatus = 'bg-success';
                                                $id = $lead['id'];
                                                $service_id = $lead['service_id'];
                                                $name = $lead['name'];
                                                $mobile = $lead['mobile'];
                                                $email = $lead['email'];
                                                $category = $lead['category'];
                                                $message = $lead['message'];
                                                $create_date = $lead['create_date'];
                                                
                                                if($category == "Enquiry") {
                                                    $classStatus = "bg-primary";
                                                } else if($category == "Suggestion") {
                                                    $classStatus = "bg-success";
                                                } else if($category == "Complaint") {
                                                    $classStatus = "bg-danger";
                                                }

                                                $service_name = "";
                                                if ($service_id != "") {
                                                    $category = get_service_name($service_id);
                                                }
                                                
                                                //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT
                                                    $regEx = '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/';
                                                    // preg_match($regEx, $details['date'], $result);
                                                    if (preg_match($regEx, $create_date) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $create_date)) {
                                                        $create_date = date_create($create_date);
                                                        $create_date = date_format($create_date, "d M Y h:ia");
                                                    }
                                                //CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT
                                                
                                            ?>
                                                <!-- REPEAT ITEM -->
                                                <tr>
                                                    <td class="text-center"><?php echo $i; ?></td>
                                                    <td>
                                                        <span class="badge <?php echo $classStatus; ?>" style="white-space: normal;">
                                                            <?php echo $category; ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-start"><?php echo $name; ?></td>
                                                    <td class="text-start">
                                                        <?php
                                                            // if(strlen($message)>25){
                                                                ?>
                                                                    <div class="accordion" id="accordion<?php echo $id; ?>">
                                                                        <div class="accordion-item">
                                                                            <h2 class="accordion-header" id="heading<?php echo $id; ?>">
                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $id; ?>" aria-expanded="true" aria-controls="collapse<?php echo $id; ?>">
                                                                                <?php echo substr($message,0,25)."..."; ?>
                                                                            </button>
                                                                            </h2>
                                                                            <div id="collapse<?php echo $id; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $id; ?>" data-bs-parent="#accordion<?php echo $id; ?>">
                                                                            <div class="accordion-body">
                                                                                <?php echo $message; ?>
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php
                                                            // }else{
                                                            //         echo $message;
                                                            // }
                                                        ?>
                                                        
                                                    </td>
                                                    <td class="text-start"><?php echo $mobile; ?></td>
                                                    <td class="text-start"><?php echo $email; ?></td>
                                                    <td class="text-center"><?php echo $create_date; ?></td>
                                                </tr>

                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot class="no_print">
                                            <tr>
                                                <th>Sr</th>
                                                <th>Query For</th>
                                                <th>Name</th>
                                                <th>Message</th>
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
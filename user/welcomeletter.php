<!doctype html>
<html lang="en">

<?php
	// COLLEGE DASHBOARD PAGE
	ob_start();
	require_once("../db_connect.php");
	session_start();
    
	mysqli_query($conn, "set names 'utf8'");  //-------WORKING UTF8 CODE------//

	//-------CURRENT DATE AND TIME TO FEED---------//
	date_default_timezone_set('Asia/Kolkata');
	$current_date = date('Y-m-d H:i:s');

	extract($_REQUEST);

	// check login status
		if (isset($_SESSION['session_id'])) {
			$user_id = $_SESSION['user_id'];
			$sponsor_id = $_SESSION['sponsor_id'];
			$session_id = $_SESSION['session_id'];
			$name = $_SESSION['name'];    //DECODE
			$name_encode = base64_encode(json_encode($name));     //ENCODE
			$user_id_encode = base64_encode(json_encode($user_id));     //ENCODE

			// $user_id = strtoupper($user_id);

			// USER AUTHENTICATION
				$session_id_pass = $_SESSION['session_id'];
				$query = mysqli_query($conn, "SELECT `unique_id`, `session_id` FROM `login_sessions` WHERE `unique_id`='$user_id' ORDER BY `create_date` DESC LIMIT 1");
				if ($row = mysqli_fetch_array($query)) {
					$session_id = $row['session_id'];
					// $name = $row['name'];

					// ADMIN LOGIN MULTIPLE LOGIN ALLOWED 11-02-2022
					// if ($session_id_pass != $session_id) {
					//   echo "<script>alert('Session Expired. Login Again!!!');</script>";
					//   echo "Redirecting...Please Wait";
					//   header("Refresh:0, url=logout/");
					//   exit;
					// }
				} else {
					echo "<script>alert('You Are Not An Authorised Person!!!');</script>";
					echo "Redirecting...Please Wait";
					header("Refresh:0, url=logout/");
					exit;
				}
			// USER AUTHENTICATION
		} else {
			echo "<script>alert('You Are Not Logged In!!!');</script>";
			echo "Redirecting...Please Wait";
			header("Refresh:0, url=logout/");
			exit;
		}
	// check login status

    
    $url_dir_photo = "../assets/files/photo";

    if ($sponsor_id != "") {
        $sponsor_name = fetch_sponsor_name($conn, $sponsor_id);
    } else {
        $sponsor_name = $name;
        $sponsor_id = $user_id;
    }

    // FUNCTIONS
    function fetch_sponsor_name($conn, $sponsor_id) {
        $query_sp_name = mysqli_query($conn,"SELECT `name` FROM `users` WHERE `user_id`='$sponsor_id'");
        if($rw = mysqli_fetch_array($query_sp_name)) {
            $name = $rw['name'];
        }
        return $name;
    }
    // FUNCTIONS

    $user_profile_photo = '';

    $query_already_exist = "SELECT `id`, `doc_file` FROM `user_document` WHERE `doc_type` IN ('photo') AND `user_id`='$user_id' AND `status`='approved'";
  
    $resphoto = mysqli_query($conn, $query_already_exist);
    if($rows = mysqli_fetch_array($resphoto)) {
        $user_profile_photo = $url_dir_photo."/".$rows['doc_file'];
    }
    // if($rows = mysqli_fetch_array($res)) {
    //    // $user_profile_photo = $url_dir_photo + $rows['doc_file'];
    // }
    $admin_access = 0;
    if (isset($_SESSION['admin_access'])) {
        $admin_access = $_SESSION['admin_access'];
    }

    $position = 4;
    $header_text = "KYC Details For $name ($user_id)" ;

	// DEFAULT
		$page_id_home = 1;
		$bank_update = $photo_update = $pan_update = $aadhaar_update = 0;
        $msg = $msg_type = "";
		$error = false;
        
        $bank_cheque_sel = $bank_passbook_sel = "";
        $bank_disabled = $aadhaar_disabled = $pan_disabled = $photo_disabled = "";
        
        $user_doc_status = "";

        $aadhaar_error = $pan_error = $bank_error = $photo_error = $address_error = "";
        
        
        $url_dir = "../assets/files/transaction";
        $url_dir_aadhaar = "../assets/files/aadhaar";
        $url_dir_pan = "../assets/files/pan";
        $url_dir_bank = "../assets/files/bank";
        $url_dir_photo = "../assets/files/photo";
	// DEFAULT

    // DEFAULT ORDER COLUMN SR
		$default_Order = 0;

    // GET DATA
        $query = "SELECT `sponsor_id`, `user_id`, `name`, `mobile`, `fhname`, `whatsapp`, `landmark`, `email`, `aadhaar`, `aadhaar_file`, `pan`, `pan_file`, `bank_name`, `branch_name`, `account_no`, `ifs_code`, `upi_handle`, `address`, `city`, `state` AS 'state_name', `pin_code`, `status`, `is_bank_updated`, `create_date`, `active_date` FROM `users` WHERE `user_id`='$user_id' ORDER BY `create_date` DESC";
        $query = mysqli_query($conn,$query);
        $res = mysqli_fetch_array($query);
        extract($res);
    // GET DATA

    if ($sponsor_id != "") {
        $sponsor_name = fetch_sponsor_name($conn, $sponsor_id);
    } else {
        $sponsor_name = $name;
        $sponsor_id = $user_id;
    }
    
    // INDIVIDUAL KYC STATUS
        $kyc_status = "<span class='btn btn-xs btn-warning'>PENDING</span>";
        // GET DATA OF SAME CATEGORY BY ONLY LAST CREATE DATE
        // $query_doc = "SELECT ud.* FROM `user_document` ud INNER JOIN (SELECT `doc_type`, MAX(`create_date`) AS max_date FROM `user_document` WHERE `user_id`='$member_id' GROUP BY `doc_type`) group_data ON ud.`doc_type`=group_data.`doc_type` AND ud.`create_date`=group_data.max_date";
        $query_doc = "SELECT * FROM `user_document` WHERE `user_id`='$user_id' AND `status` in ('approved','pending') ORDER BY `create_date` ASC";
        $res = mysqli_query($conn,$query_doc);
        $docs = mysqli_fetch_all($res,MYSQLI_ASSOC);
        $count_docs_uploaded = mysqli_num_rows($res);
        
        $aadhaar_update = $pan_update = $bank_update = $address_update = $photo_update = $kyc_done = 0;
        
        $i = 0;
        $count_docs = $count_docs_approved = $count_docs_rejected = 0;
        $aadhaar_approved = $pan_approved = $cheque_approved = $passbook_approved = $address_approved = $photo_approved = 0;
        $aadhaar_rejected = $pan_rejected = $cheque_rejected = $passbook_rejected = $address_rejected = $photo_rejected = 0;
        $aadhaar_pending = $pan_pending = $cheque_pending = $passbook_pending = $address_pending = $photo_pending = 0;
        $aadhaar_needed = $pan_needed = $bank_needed = $cheque_needed = $passbook_needed = $address_needed = $photo_needed = $personal_needed = 0;
        $aadhaar_readonly = $pan_readonly = $bank_readonly = $cheque_readonly = $passbook_readonly = $photo_readonly = $personal_readonly = "readonly";        
        
        if ($count_docs_uploaded == 0) {
            $aadhaar_needed = $pan_needed = $bank_needed = $cheque_needed = $passbook_needed = $address_needed = $photo_needed = 1;
        }
        
        $aadhar_back = "";
        $aadhaar_error = $pan_error = $bank_error = $photo_error = "";
            
        foreach ($docs as $item) {
            $i++;
            // DATA
                $user_doc_id = $item['id'];
                $user_doc_type = $item['doc_type'];
                $user_doc_number = $item['doc_number'];
                $user_doc_file = $item['doc_file'];
                $user_doc_file2 = $item['doc_file2'];
                $user_doc_status = $item['status'];
                $comment = $item['comment'];
                $create_date = $item['create_date'];
            // DATA
            
            if ($user_doc_status == "approved") {
                $count_docs_approved++;
            } else if ($user_doc_status == "rejected") {
                $count_docs_rejected++;
            }
            
            
            switch ($user_doc_type) {
                case 'aadhaar':
                    $aadhaar_update = 1;
                    $aadhaar = $user_doc_number;
                    $photo_path1 = "$url_dir_aadhaar/$user_doc_file";
                    $photo_path2 = "$url_dir_aadhaar/$user_doc_file2";
                    $aadhaar_doc_front = "$url_dir_aadhaar/$user_doc_file";
                    $aadhaar_doc_back = "$url_dir_aadhaar/$user_doc_file2";
                    $file_text = "Aadhaar Front";
                    $aadhar_back = "<br>
                                <span class='badge bg-warning text-uppercase' style='white-space: normal;' data-bs-toggle='modal' data-bs-target='#ModalShowDoc' id='img-$user_doc_id' data-src='$photo_path2'>
                                    Aadhaar Back
                                </span>";
                    $class_doc_type = "success";
                    $aadhaar_disabled = "disabled";
                    $aadhaar_status = $user_doc_status;
                    
                    $count = $user_doc_type."_".$user_doc_status;
                    $$count++;
                    $aadhaar_error = $comment;
                    break;
                
                case 'pan':
                    $pan_update = 1;
                    $pan = $user_doc_number;
                    $photo_path1 = "$url_dir_pan/$user_doc_file";
                    $pan_doc = "$url_dir_pan/$user_doc_file";
                    $file_text = "PAN";
                    $class_doc_type = "warning";
                    $pan_disabled = "disabled";
                    $pan_status = $user_doc_status;
                    
                    $count = $user_doc_type."_".$user_doc_status;
                    $$count++;
                    $pan_error = $comment;
                    break;
                
                case 'cheque':
                    $bank_update = 1;
                    $account_no = $user_doc_number;
                    $photo_path1 = "$url_dir_bank/$user_doc_file";
                    $bank_doc = "$url_dir_bank/$user_doc_file";
                    $bank_cheque_sel = "selected";
                    $file_text = "Cancelled Cheque";
                    $class_doc_type = "primary";
                    $bank_disabled = "disabled";
                    $bank_status = $user_doc_status;
                    
                    $count = $user_doc_type."_".$user_doc_status;
                    $$count++;
                    $bank_error = $comment;
                    break;
                
                case 'passbook':
                    $bank_update = 1;
                    $account_no = $user_doc_number;
                    $photo_path1 = "$url_dir_bank/$user_doc_file";
                    $bank_doc = "$url_dir_bank/$user_doc_file";
                    $bank_passbook_sel = "selected";
                    $file_text = "Passbook";
                    $class_doc_type = "primary";
                    $bank_disabled = "disabled";
                    $bank_status = $user_doc_status;
                    
                    $count = $user_doc_type."_".$user_doc_status;
                    $$count++;
                    $bank_error = $comment;
                    break;

                case 'address':
                    $address_update = 1;
                    $file_text = "Address";
                    $class_doc_type = "danger";
                    $address_disabled = "disabled";
                    $address_status = $user_doc_status;
                    
                    $count = $user_doc_type."_".$user_doc_status;
                    $$count++;
                    $address_error = $comment;
                    break;
                    
                case 'photo':
                    $photo_update = 1;
                    $photo_path1 = "$url_dir_photo/$user_doc_file";
                    $photo = "$url_dir_photo/$user_doc_file";
                    $file_text = "Photo";
                    $class_doc_type = "danger";
                    $photo_disabled = "disabled";
                    $photo_status = $user_doc_status;
                   
                    $count = $user_doc_type."_".$user_doc_status;
                    $$count++;
                    $photo_error = $comment;
                    break;
                
                default:
                    $aadhaar_update = 
                    $pan_update = 
                    $bank_update =
                    $address_update =
                    $photo_update = 0;
                    $class_doc_type = "info";
                    break;                    
            }                
        }

        if ($aadhaar_approved > 0) {
            $aadhaar_needed = 0;
        } else if ($aadhaar_pending == 0) {
            $aadhaar_needed = 1;
            $aadhaar_readonly = "";
        }

        if ($pan_approved > 0) {
            $pan_needed = 0;
        } else if ($pan_pending == 0) {
            $pan_needed = 1;
            $pan_readonly = "";
        }

        if (($cheque_approved > 0) || ($passbook_approved > 0)) {
            $bank_needed = 0;
        } else if (($cheque_pending == 0) && ($passbook_pending == 0)) {
            $bank_needed = 1;
            $bank_readonly = "";
        }

        /* ***** 07-04-2023 ***** */
            // if ($photo_approved > 0) {
            //     $photo_needed = 0;
            // } else if ($photo_pending == 0) {
            //     $photo_needed = 1;
            //     $photo_readonly = "";
            // }
        /* ***** 07-04-2023 ***** */

        if ($address_approved > 0) {
            $address_needed = 0;
            
            $personal_needed = 0;
        } else if ($address_pending == 0) {
            $address_needed = 1;
            $address_readonly = "";

            $personal_needed = 1;
            $personal_readonly = "";
        }

        if ($photo_approved > 0) {
            $photo_needed = 0;
        } else if ($photo_pending == 0) {
            $photo_needed = 1;
            $photo_readonly = "";
        }else if ($photo_status == "pending") {
            $photo_needed = 0;
        }
        /* ***** 07-04-2023 ***** */
            // if ($address == "" || $address == NULL) {
            //     $personal_needed = 1;
            //     $personal_readonly = "";
            // }
        /* ***** 07-04-2023 ***** */

        // if ($user_doc_status == "approved") {
        // } else if ($user_doc_status == "rejected") {
        //     $kyc_status = "<span class='btn btn-xs btn-danger'>REJECTED</span>";
        // } else {
        // }

        // if ($count_docs_approved > 3) {
        if ($pan_approved > 0 && $aadhaar_approved > 0 && $address_approved > 0 && ($cheque_approved > 0 || $passbook_approved > 0) && $photo_approved > 0) {
            $kyc_done = 1;
            $kyc_status = "<span class='btn btn-xs btn-success'>APPROVED</span>";
        } else {
            $kyc_status = "<span class='btn btn-xs btn-info'>PENDING</span>";
        }
    
        // if ($aadhaar_update == 1 && $pan_update == 1) {
        //     $kyc_done = 1;
        // }

        // $aadhaar_update
        // $pan_update
        // $bank_update
        // $bank_update
        // $photo_update
    // INDIVIDUAL KYC STATUS

    if ($user_id == "maxizone") {
        $bank_update = $photo_update = $pan_update = $aadhaar_update = 1;
        $fresh_txn_added_to_company = 1;
        $transaction_needed = 0;
        $kyc_done = 1;
        $user_doc_status = "approved";
        $kyc_status = "<span class='btn btn-xs btn-success'>APPROVED</span>";
    }
if($kyc_done == 0){
    echo "<script>alert('Please complete kyc first!!');
    setTimeout(function(){
        window.location = '../user/index.php';
    },100);
    </script>";
    //header("Location: ../user/index.php");
}

?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />

    <title>Welcome Letter</title>

    <link rel="stylesheet" media="print" href="../assets/css/print.min.css" />
    <!-- Bootstrap -->
    <link href="assets/css/welcomeletterstyle.css" rel="stylesheet">
    
</head>
<style>
   @media print{
    /* .wcbox {
        padding: 50px 90px;      } */
    #print, #download {display:none;}

    @font-face {
        font-family:"Rye-Regular";
        src: url("../fonts/Rye-Regular.ttf") format("truetype");
        }
        @font-face {
        font-family:"ATLibertyRegular";
        src: url("../fonts/ATLibertyRegular.otf") format("opentype");
        }
        
        body{margin:0px;background:#f5f5dd;}
        
        .welcomeletter{width:700px;height:900px;position:relative;display:block;margin:20px auto;
        background: url(../assets/images/welcometopbg.png) center top no-repeat, url(../assets/images/welcomebottombg.jpg) center bottom no-repeat;background-size:contain;}
         .wcbox{position: relative;display: block; padding: 50px 90px;box-sizing: border-box;} 
        .wcbox .wlogo{width: 150px;height: 130px;margin: auto;overflow: hidden;position: relative;}
        .wcbox .wlogo img,
        .wcbox .wcsignature img{width:auto;max-width:100%;height:auto;max-height:100%;margin:auto;position:relative;}
        .wcbox .welcometxt{width: 100%;height:70px;overflow:hidden; position: relative;display: block;font-family:"Rye-Regular"; font-size: 50px;text-align: center;line-height: 70px;}
        .wcbox ul{margin:0px;padding:0px;list-style:none;}
        .wcbox ul li{float:left;width:calc(100% - 150px);margin-bottom:5px;}
        .wcbox ul li:last-child{width:150px;}
        .wcbox li .wcpix{height: 140px;position: relative;display: block; overflow: hidden; width: 120px;border:2px #000 solid; visibility: hidden;}
        .wcbox li .wcpix img{width:100%;height:100%;}
        .wcbox li p{ padding-top: 80px;margin-bottom:8px;}
        .wcbox p{margin:0 0 10px 0; font-size: 18px;line-height: 20px;}
        .wcbox .wishes{font-family:"ATLibertyRegular";font-size: 35px;text-align: center; color: #F44336; font-weight: bold;padding-top:15px;}
        .wcbox table{width:100%;max-width:600px;margin:auto;}
        .wcbox table td{width:50%;vertical-align:bottom;}
        .wcbox .blackline{display:block;margin:auto;width:100%;text-align:center;max-width:486px;}
        .wcbox .blackline img{width:100%;}
        .wcbox .director{background:url(../images/dirctot.jpg) center bottom no-repeat;background-size:1150px;padding-bottom:43px;width: 150px;float:left;position:relative;overflow:hidden;}
        .wcbox .director img{width:auto;max-width:100%;display:block;margin:auto;}
        .wcbox .conglogo{width:150px;float:right;overflow:hidden;}
        .wcbox .conglogo img{width:100%;}
        .wcbox .wcwebsite{font-weight: bold;font-size: 18px; font-family: monospace;text-align: center;}
        .wcbox .wcwebsite a{color:#000;text-decoration:none;}
        }

</style>
<body>
    <div style="text-align:center; margin-bottom: 10px;">
        <!-- <button id="download" onclick="DownloadPage()">Download</button> -->
        <button id="print" onclick="PrintPage()">Print</button>
    </div>
    <div class="welcomeletter" id="welcomeletter">
        <div class="wcbox">
            <div class="wlogo"><img src="../assets/images/logo-new.png" /></div>
            <div class="welcometxt">Welcome Letter</div>
            <!-- <ul>
                <li>
                    <p>Dear<br>
                        <strong><?php echo $name;?></strong> CONGRATULATIONS.
                    </p>
                </li>
                <li>
                    <div class="wcpix"><img src="<?php echo $user_profile_photo; ?>" /></div>
                </li>
            </ul> -->
            <p>Dear<br>
                        <strong><?php echo $name;?></strong> CONGRATULATIONS.
                    </p>
            <p>Now You Have Joined Successfully. Your I’D No. is <strong><?php echo $user_id;?></strong> & Your Sponsor
                Name is <strong><?php echo $sponsor_name;?></strong> (<?php echo $sponsor_id;?>)</p>
            <p>Maxizone Warmly Welcomes You. We are Proud That You Have Decided to Choose Our Exceptional
                Services. We are Able to Understand Your Financial Goals and Needs are Dedicated to
                Providing you Assistance.</p>
            <p>Our Goal is to Strengthen Your Financial Position and Make Your Investments Safe and Profitable.
                We will work with You to Create a Precise and Personalized Investment Strategy That Will
                Help You Achieve your Goals.</p>
            <p>Always Ready to solve Your Investment and Money Related Queries. Please contact us at any time.</p>
            <p>Thanks for joining us again. We are excited to help you prosper.</p>
            <div class="wishes">With The Best Wishes team Maxizone</div>
            <table>
                <tr>
                    <td colspan="2">
                        <div class="blackline"><img src="assets/images/blackline.jpg"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="director">
                            <img src="assets/images/signature.jpg">
                        </div>
                    </td>
                    <td>
                        <div class="conglogo"><img src="assets/images/conglogo.jpg"></div>
                    </td>
                </tr>

            </table>
            <div class="wcwebsite">For more Details visit Our Website : <a
                    href="https://www.maxizone.in">www.maxizone.in</a></div>
        </div>
    </div>
</body>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>

<script>
function DownloadPage() {
    var doc = new jsPDF();
    
    doc.text(20, 20, 'Welcome Letter PDF file!');
    
    doc.save('welcome letter.pdf');
}
</script>

<script>
    function PrintPage() {
    window.print();
    }
</script>

<script src="assets/js/jquery-3.5.1.min.js"></script>
<script src="../assets/js/html2canvas.js"></script>
<script src="../assets/js/jspdf.umd.min.js"></script>
<script src="../assets/js/print.min.js"></script>
<script src="../assets/js/dom-to-image.js"></script>

</html>
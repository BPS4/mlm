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

        $file_dir_admindocs= "../../assets/files/admindocs";
    if (isset($_POST['submit_bankdetails'])) {
        $filename_upi_qrcode ="";
        if ((isset($_FILES["upi_qrcode"]) && $_FILES["upi_qrcode"]["error"] == 0)) {
            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "JPG" => "image/jpg", "JPEG" => "image/jpeg", "png" => "image/png", "PNG" => "image/png", "webp" => "image/webp");
            $filename_upi_qrcode = $_FILES["upi_qrcode"]["name"];
            $filetype = $_FILES["upi_qrcode"]["type"];
            $filesize = $_FILES["upi_qrcode"]["size"];

            // Verify file extension
            $ext_bank = pathinfo($filename_upi_qrcode, PATHINFO_EXTENSION);
            
            // Extract FileName
            $file_basename = basename($filename_upi_qrcode, ".$ext_bank");            

            if (!array_key_exists($ext_bank, $allowed)) {
                $error = true;
                $msg .= " >> Only JPEG/JPG/PNG/WEBP Formats are Allowed!!!";
                $msg_type = "error";
            }

            // Verify file size - 500kB maximum
            $minsize = 1 * 1024;
            $maxsize = 150 * 1024;

            if ($filesize > $maxsize || $filesize < $minsize) {
                $error = true;
                $msg .= " >> Error!!! UPI QR Code size should not be greater than 500kb.";
                $msg_type = "error";
            }
        } else {
            $error = true;
            $msg .= " >> UPI QR Code Not Uploaded";
            $msg_type = "error";
        }
        if (!$error) {
            $current_timestamp = time();
            if (isset($filename_upi_qrcode) && $filename_upi_qrcode != "") {
                $uploadedfile = $_FILES["upi_qrcode"]["tmp_name"];
                $filename_upi_qrcode = "$user_id-$current_timestamp".".$ext_bank";
            }
            $file_dir_upi_qrcode = $file_dir_admindocs."/".$filename_upi_qrcode;
            if (
                (isset($file_dir_upi_qrcode)) && 
                (move_uploaded_file($_FILES['upi_qrcode']['tmp_name'], $file_dir_upi_qrcode))
            ) {

                $msg .= "Bank Details ";
                $msg_type = "success";
                // echo "<script>window.close();</script>";
            }

        }
        $user_id = $_POST['user_id'];
        $id = $_POST['id'];
        $bank_name = $_POST['bank_name'];
        $branch_name =$_POST['branch_name'];
        $branch_address =$_POST['branch_address'];
        $account_no =$_POST['account_no'];
        $ifs_code =$_POST['ifs_code'];
        $upi_handle =$_POST['upi_handle'];
        $upi_qrcode=$filename_upi_qrcode;
        $query_update_bankinfo = "UPDATE `users_admin` SET `bank_name`= '$bank_name',`branch_name`='$branch_name',`branch_address`='$branch_address',
        `account_no`='$account_no',`ifs_code`='$ifs_code',`upi_handle`='$upi_handle',`upi_qrcode`='$upi_qrcode' WHERE `id` IN ($id)";
       //  echo $query_update_bankinfo;
         //die;
        mysqli_query($conn,$query_update_bankinfo);
        $count_record_update_doc = mysqli_affected_rows($conn);
       // echo $count_record_update_doc;
    }

 
    // GET WALLET DATA
        $query_wallet = "SELECT `id`,`bank_name`, `branch_name`, `branch_address`, `account_no`, `ifs_code`, `upi_handle` , `upi_qrcode` FROM `users_admin` WHERE `user_id`='$user_id'";
     //echo $query;
        $query = mysqli_query($conn, $query_wallet);        
        $res = mysqli_fetch_array($query);
        extract($res);
    // GET WALLET DATA
?>

<head>
    <?php include_once('head.php'); ?>

    <title><?php  echo "Bank Information - Admin - Maxizone"; ?></title>

    <link rel="canonical" href="view_bankinfo.php">

    <style>
        @page {
            size: ledger landscape;
            /*//auto, portrait, landscape or length (2 parameters width and height. sets both equal if only one is provided. % values not allowed.)*/

            margin-top: 0.5cm;
            margin-bottom: 0.5cm;
            margin-left: 0.5cm;
            margin-right: 0.5cm;
        }
        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
            .dataTables_length, .dataTables_filter, .dt-buttons, .no_print {
                display: none;
                visibility: hidden;
            }
        }
        .form-control {
            background: white !important;
        }
        #datatables-column-search-text-inputs,
    .card {
      display: block;
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

                    <h1 class="h3 mb-3">Admin Bank Information</h1>

                    <div class="row">
                        <div class="col-md-12 col-xl-12">
                          
                        <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Banking info</h5>
                                </div>
                                <div class="card-body">
                                    <form id="submit_bankdetails" method="post" enctype="multipart/form-data" >
                                    <input type="hidden" class="form-control" name="user_id" id="user_id" value="<?php echo $user_id;?>">
                                    <input type="hidden" class="form-control" name="id" id="id" value="<?php echo $id;?>">
                                        <div class="row">                                                    
                                            <div class="mb-3 col-xl-6">
                                                <label for="bank_name" class="form-label">Bank Name</label>
                                                <select class="form-control " id="bank_name" name="bank_name" required>
                                                    <option value="" hidden>Choose Bank</option>
                                                    <?php
                                                        $res = mysqli_query($conn, "SELECT `id` AS 'bank_name_id', `name` AS 'bank_name' FROM `bank_names` ORDER BY `name` ASC");
                                                        $banks = mysqli_fetch_all($res, MYSQLI_ASSOC);
                                                        foreach ($banks as $bank) {
                                                            $bank_name_fetch = $bank['bank_name'];
                                                            $selected = "";
                                                            if (strtolower($bank_name_fetch)== strtolower($bank_name)) {
                                                                $selected = "selected='selected'";
                                                            }
                                                            ?>
                                                                <option value="<?php echo $bank_name_fetch;?>" <?php echo $selected; ?>><?php echo $bank_name_fetch;?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="branch_name" class="form-label">Branch Name</label>
                                                <input type="text" class="form-control" name="branch_name" id="branch_name" placeholder="Branch" value="<?php echo $branch_name;?>">
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="branch_address" class="form-label">Branch Address</label>
                                                <input type="text" class="form-control" name="branch_address" id="branch_address" placeholder="Branch Address" value="<?php echo $branch_address;?>">
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="account_no" class="form-label">Account Number</label>
                                                <input type="text" class="form-control" name="account_no" id="account_no" placeholder="Account Number" value="<?php echo $account_no;?>">
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="ifs_code" class="form-label">IFSC Code</label>
                                                <input type="text" class="form-control" name="ifs_code" id="ifs_code" placeholder="IFSC Code" value="<?php echo $ifs_code;?>">
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="upi_handle" class="form-label">UPI Handle (@ybl, @paytm etc...)</label>
                                                <input type="text" class="form-control" name="upi_handle" id="upi_handle" placeholder="UPI" value="<?php echo $upi_handle;?>">
                                            </div>	
                                            <div class="mb-3 col-md-6">
                                                <label for="upi_qrcode" class="form-label">UPI QR Code Image</label>
                                                <input type="file" class="form-control" name="upi_qrcode" id="upi_qrcode">
                                                <img src="<?php echo $file_dir_admindocs."/".$upi_qrcode?>" style="width: 250px;"/>
                                            </div>	
                                            <div style="float:right;">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success" id="submit_bankdetails" name="submit_bankdetails">Submit</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                           
                        </div>
                    </div>

                </div>
            </main>

            <?php include_once('footer.php'); ?>
        </div>
    </div>

    <!-- BEGIN ModalAction -->
		<div class="modal fade" id="ModalAction" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">Approve/Reject Document</h3>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p class="mb-0">
							<div class="row align-items-center">
								<input type="hidden" id="id_doc">
								<input type="hidden" id="id_user">
								<input type="hidden" id="force_reject">
								<input type="hidden" id="force_approve">
								<div class="mb-3 col-md-12">
									<label for="select_action" class="text-bold">Select Action To Perform</label>
									<select name="" id="select_action" class="form-control" onchange="toggle_comment();">
										<option value="">Choose Action</option>
										<option value="approved" id="select_approve">Approve</option>
										<option value="rejected" id="select_reject">Reject</option>
									</select>
								</div>
								<div class="mb-3 col-md-12 d-none" id="reject_comment_container">
									<label for="reject_comment" class="text-bold">Reason/Comment For Rejection</label>
									<input type="text" name="reject_comment" id="reject_comment" class="form-control" placeholder="Enter comment">
								</div>
							</div>
							<div style="float:right;">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success" id="btn_submit" onclick="process_kyc();">Submit</button>
							</div>
						</p>
					</div>
				</div>
			</div>
		</div>
	<!-- END ModalAction -->

    <?php include_once('scripts.php'); ?>

    <script>
		function toggle_funds() {
			var choose_action = document.getElementById("choose_action");
			var invest_container = document.getElementById("invest_container");
			var deduct_container = document.getElementById("deduct_container");
			var transfer_container = document.getElementById("transfer_container");
			var invest_fund = document.getElementById("invest_fund");

			if (choose_action.value == "invest") {
				invest_container.classList.remove('d-none');
				deduct_container.classList.add('d-none');
				transfer_container.classList.add('d-none');

				invest_fund.setAttribute('required','true');
			} else if (choose_action.value == "deduct") {
				deduct_container.classList.remove('d-none');
				invest_container.classList.add('d-none');
				transfer_container.classList.add('d-none');
                
				invest_fund.setAttribute('required','false');
			} else if (choose_action.value == "transfer") {
				transfer_container.classList.remove('d-none');
                invest_container.classList.add('d-none');
				deduct_container.classList.add('d-none');
                
				invest_fund.setAttribute('required','false');
            }
		}

		function toggle_comment() {
			var select_action = document.getElementById("select_action");
			var reject_comment_container = document.getElementById("reject_comment_container");
			var reject_comment = document.getElementById("reject_comment");
			var id_doc = document.getElementById("id_doc");

			if (select_action.value == "rejected") {
				reject_comment_container.classList.remove('d-none');
				reject_comment.setAttribute('required','true');
			} else {
				reject_comment_container.classList.add('d-none');
				reject_comment.setAttribute('required','false');
			}
		}

		function process_kyc() {
			var action = "process_kyc";
			var select_action = document.getElementById("select_action");
			var reject_comment = document.getElementById("reject_comment");
			var id_doc = document.getElementById("id_doc");
			var id_user = document.getElementById("id_user");
			var force_reject = document.getElementById("force_reject");
			var force_approve = document.getElementById("force_approve");

			if (select_action.value == "") {
				showNotif("Please select an action to perform!","danger");
				return false;
			}

			if (select_action.value == "rejected") {
				if (reject_comment.value.trim() == "") {
					reject_comment.focus();
					showNotif("Comment is required for Rejection!","danger");
					return false;
				}
			}

			$.ajax({
				type: "POST",
				url: "ajax.php",
				data: {
					action: action,
					id_user: id_user.value,
					id_doc: id_doc.value,
					action_to_perform: select_action.value,
					reject_comment: reject_comment.value,
                    force_reject: force_reject.value
				},
				
				// data: $("#form_register").serialize() + "&action=" +action , // getting filed value in serialize form And Passing additional Value of 'action' To perform Task Accordingly
				// dataType: 'JSON',
				cache: false,

				// xhr: function () {
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
					console.log(response);
					if (response == "approved") {
						showNotif("Document Approved Successfully","success");
					} else if (response === "approved_already") {
						showNotif("Document Approved Already","warning");
                    } else if (response == "rejected") {
						showNotif("Document Rejected Successfully","danger");
					} else if (response === "rejected_already") {
						showNotif("Document Rejected Already","warning");
                    } else if (response == "kyc_rejected") {
						showNotif("Member KYC Rejected Successfully","danger");
                    } else if (response == "kyc_approved") {
						showNotif("Member KYC Done Successfully","success");
					} else if (response === "kyc_approved_already") {
						showNotif("Member KYC Done Already","warning");
                    } 

					setTimeout(function(){
						location.reload();
					},1500);
					// document.getElementById("overlay").style.display = "none";

					// var response_obj = $.parseJSON(response); // create an object with the key of the array
					// PARSE/DECODE THE JSON OBJECT
					// var response_obj = JSON.parse(response);
					// alert(response_obj.html_data); // where html is the key of array that you want, $response['html'] = "<a>something..</a>";

				}
			});
		}

        // PASS DATA TO MODAL POPUP
            $(function () {
                $('#ModalUpdate').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var id = button.data('id'); // Extract info from data-* attributes
                    var id_user = button.data('user_id'); // Extract info from data-* attributes
                    var mobile = button.data('mobile'); // Extract info from data-* attributes
                    var email = button.data('email'); // Extract info from data-* attributes
                    var name = button.data('name'); // Extract info from data-* attributes
                    var father = button.data('father'); // Extract info from data-* attributes
                    var whatsapp = button.data('whatsapp'); // Extract info from data-* attributes
                    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
					// package = "Package "+package;
					// payment = "20% Amount of "+package+"= Rs."+payment;
                    var modal = $(this);
                    // modal.find(id).val(src);
                    // modal.find('#image').src=src;
					document.getElementById("u_id_user").value = id_user;
					document.getElementById("u_name").value = name;
					document.getElementById("u_fhname").value = father;
					document.getElementById("u_whatsapp").value = whatsapp;
					document.getElementById("u_mobile").value = mobile;
					document.getElementById("u_email").value = email;

                });
            });
        // PASS DATA TO MODAL POPUP
        
		// PASS DATA TO MODAL POPUP
            $(function () {
                $('#ModalAction').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var id = button.data('id'); // Extract info from data-* attributes
                    var id_user = button.data('user_id'); // Extract info from data-* attributes
                    var force_reject = button.data('force_reject'); // Extract info from data-* attributes
                    var force_approve = button.data('force_approve'); // Extract info from data-* attributes
                    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
					// package = "Package "+package;
					// payment = "20% Amount of "+package+"= Rs."+payment;
                    var modal = $(this);
                    // modal.find(id).val(src);
                    // modal.find('#image').src=src;
					document.getElementById("id_doc").value = id;
					document.getElementById("id_user").value = id_user;
					document.getElementById("force_reject").value = force_reject;
					document.getElementById("force_approve").value = force_approve;
                    if (force_reject == 1) {
                        document.getElementById("select_approve").disabled = true;
                        document.getElementById("select_approve").classList.add("bg-danger");
                        document.getElementById("select_approve").classList.add("text-white");
                        document.getElementById("select_reject").selected = true;
                        toggle_comment();
                    } else {
                        document.getElementById("select_approve").disabled = false;
                        document.getElementById("select_approve").classList.remove("bg-danger");
                        document.getElementById("select_approve").classList.remove("text-white");
                        document.getElementById("select_reject").selected = false;
                        toggle_comment();
                    }
                    // else if (force_approve == 1) {
                    //     document.getElementById("select_approve").selected = true;
                    //     document.getElementById("select_reject").classList.add("bg-danger");
                    //     document.getElementById("select_reject").classList.add("text-white");
                    //     document.getElementById("select_reject").disabled = true;
                    //     toggle_comment();
                    // }
                });
            });
        // PASS DATA TO MODAL POPUP

        // PASS DATA TO MODAL POPUP
            $(function () {
                $('#ModalShowDoc').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var src = button.data('src'); // Extract info from data-* attributes
                    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
					// package = "Package "+package;
					// payment = "20% Amount of "+package+"= Rs."+payment;
                    var modal = $(this);
					
                    // modal.find(id).val(src);
                    // modal.find('#image').src=src;
					$("#image").attr("src", src);
                });
            });
        // PASS DATA TO MODAL POPUP
	</script>
    
    <script>
        // DataTables with Column Search by Text Inputs
            document.addEventListener("DOMContentLoaded", function () {
                // DataTables
                var $filename = document.title;
                var default_Order = '<?php echo $default_Order;?>';
                var length_menu = '<?php echo isset($length_menu)?$length_menu:"";?>';
                var table = $('#datatables').DataTable({
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

                            var filteredRows = getNumFilteredRows('#datatables');

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

    <script>
        // PASS DATA TO MODAL POPUP
            $(function () {
                $('#ModalShowMessage').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var message_rejection = button.data('message_rejection'); // Extract info from data-* attributes
                    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
					// package = "Package "+package;
					// payment = "20% Amount of "+package+"= Rs."+payment;
                    var modal = $(this);
                    document.getElementById('message_rejection').value = message_rejection;
                });
            });
        // PASS DATA TO MODAL POPUP

        // PASS DATA TO MODAL POPUP
            $(function () {
                $('#ModalShowPhoto').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var src = button.data('src'); // Extract info from data-* attributes
                    var src1 = button.data('src1'); // Extract info from data-* attributes
                    var from = button.data('from'); // Extract info from data-* attributes
                    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                    // package = "Package "+package;
                    // payment = "20% Amount of "+package+"= Rs."+payment;
                    var modal = $(this);

                    // return false;
                    // document.getElementById('buttonModalUploadBank').style.display = "none";
                    // document.getElementById('buttonModalUploadDocument').style.display = "none";
                    
                    if (from == "bank") {
                        // document.getElementById('buttonModalUploadBank').style.display = "block";
                        title = "Uploaded Bank Proof";
                    }

                    if (from == "kyc") {
                        // document.getElementById('buttonModalUploadDocument').style.display = "block";
                        title = "Uploaded PAN";
                    }

                    // document.getElementById('buttonModalUploadDocument'));
                    
                    if(src1 !== undefined) {
                        title = "Uploaded Aadhaar";
                        document.getElementById('photo2_container').style.display = "block";
                        document.getElementById('photo2').src = src1;
                    } else {
                        document.getElementById('photo2_container').style.display = "none";
                    }

                    modal.find('#modal_title_photo').html(title);
                    document.getElementById('photo1').src = src;
                });
            });
        // PASS DATA TO MODAL POPUP
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

        // if ($show_modal == 1) {
        //     show_reg_send_msg($success_user_id,$success_name,$success_password);
        // }
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
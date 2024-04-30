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

	$company_payment_amount = 51000;

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

	$header = "Withdrawal Requests Pending For Approval";

	// DEFAULT
		$page_id_home = 1;
        $msg = $msg_type = "";
		$error = false;
		
		$pan_upload = $aadhaar_upload = 0;

	// $count_school = mysqli_num_rows(mysqli_query($conn, "SELECT `udise_code` FROM `schools` WHERE `verified`=1 AND `allowed_internship`=1 "));
	// $count_trainee = mysqli_num_rows(mysqli_query($conn, "SELECT `id_member` FROM `trainee` WHERE `batch`='$batch' AND `college_code` IS NOT NULL "));
	// if ($rw = mysqli_fetch_array(mysqli_query($conn, "SELECT `visits` FROM `counter` WHERE `page_id`='$page_id_home' "))) {
	// 	$count_visitor = $rw['visits'];
	// }
	
	// GET TRANSACTION
		// FOR SELECT QUERIES
			$query_transaction = "SELECT `withdrawal`.`id`, `withdrawal`.`user_id`, `withdrawal`.`withdrawal_amount`, `withdrawal`.`status`, `withdrawal`.`comment`, `withdrawal`.`create_date`,`users`.`name`,`users`.`mobile`,`users`.`email`,`users`.`pan`,`users`.`bank_name`,`users`.`branch_name`,`users`.`account_no`,`users`.`ifs_code` FROM `withdrawal` LEFT JOIN `users` ON `users`.`user_id`=`withdrawal`.`user_id` WHERE `withdrawal`.`status`='pending' ORDER BY `withdrawal`.`create_date` DESC";
			$query_transaction = mysqli_query($conn, $query_transaction);
			// FETCH ALL DATA
			$records = mysqli_fetch_all($query_transaction,MYSQLI_ASSOC);
			$count_available_txn = mysqli_num_rows($query_transaction);
	// GET TRANSACTION
	
	$total_count = mysqli_num_rows($query_transaction);
	// USED FOR CENTER THE TEXT PDF EXPORT
		echo "<input type='hidden' id='total_count' value='$total_count'>";
	
	// DEFAULT ORDER COLUMN SR
		$default_Order = 0;

	// LENGTH MENU
    	$length_menu = "all";
?>

	<head>
		<?php include("head.php"); ?>

		<title><?php echo $header; ?> - Admin Dashboard - Maxizone - <?php echo "$current_date" ?></title>

		<link rel="canonical" href="view_withdrawal_pending.php">

		<style>
			input[type="checkbox"],#check_all {
				box-shadow: 0px 0px 2px 4px #D9534F;
				margin: 5px !important;
			}
			input[type="checkbox"]:checked,#check_all:checked {
				box-shadow: 0 0 2px 4px #4BBF73;
			}

			/* Labels for checked inputs */
			label.check

			/*check class label*/
				{
				text-align: justify;
				color: black;

			}

			input:checked+label {
				color: green;
				font-weight: bold;
			}

			#check_all:checked+label {
				color:green;
			}
			
			.blink {
				animation: blinker 2s linear infinite both;
			}

			/* FADE IN OUT BLINK ANIMATION */
			@keyframes blinker {
				25%,
				75% {
					opacity: 0;
				}
				12.5%,
				37.5%,
				62.5% {
					opacity: 0.7;
				}
				0%,
				50%,
				100% {
					opacity: 1;
				}
			}

			@page {
				size: ledger LANDSCAPE;
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
		</style>
	</head>

	<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-behavior="sticky">
		<div class="wrapper">
			<?php include("sidebar.php"); ?>

			<div class="main">
				<?php include("navbar.php"); ?>

				<main class="content">
					<div class="container-fluid p-0">
						<div class="row mb-2 mb-xl-3">
							<div class="col-auto">
								<h3><?php echo $header; ?></h3>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<div class="no_print" id="loader"></div>
								<div class="card">
									<div class="card-header no_print" style="margin-bottom:0px;padding: 0.5rem 1.25rem;">
										<h5 class="card-title">Total Records Present <span class="badge bg-success" style="font-weight: bold;"><?php echo $total_count; ?></span></h5>
										<span style="position:absolute;left:0px;margin-left:15px;">
											<a href='./' class="badge bg-warning fs-100" style="text-decoration:none;">
												Dashboard
											</a>
											<b>
												<i class="align-middle" data-feather="chevrons-right"></i>
											</b>
											<a href="view_withdrawal.php" class="badge bg-success fs-100" style="text-decoration:none;">
												All Withdrawals
											</a>
											<b>
												<i class="align-middle" data-feather="chevrons-right"></i>
											</b>
											<a href="view_withdrawal_pending.php" class="badge bg-info fs-100" style="text-decoration:none;">
												Pending Withdrawals
											</a>
											<a onclick="javascript:window.print()" class="badge bg-danger ms-2" style="text-decoration:none;">
												<i class="align-middle" data-feather="printer" style="border-radius: 50%;margin-right:5px;"></i>
												Print This Page
											</a>
										</span>
										
										<!-- <button type="button" class="btn btn-success my-1" data-bs-toggle="modal" data-bs-target="#ModalCustomerAdd" style="text-decoration:none;position:absolute;right:0px;margin:15px;">
											<i class="align-middle" data-feather="user-plus" style="border-radius: 50%;margin-right:5px;"></i>
											New Customer
										</button> -->
									</div>
									<div class="card-body" style="padding:0.5rem;">
										<form action="" method="post" id="formChoiceAllot">
											<table id="datatables" class="table table-striped" style="width:100%">
												<colgroup>
													<!-- <col width="5%">
													<col width="10%">
													<col width="5%">
													<col width="10%">
													<col width="5%">
													<col width="5%">
													<col width="15%">
													<col width="15%">
													<col width="15%">
													<col width="5%">
													<col width="5%">
													<col width="5%"> -->
												</colgroup>
												<thead>
													<tr>
														<th>Sr</th>
														<th class="no_print">
															<input class="d-none" type="checkbox" id="check_all" name="approve_all" value="approve_all">
															<label class="d-none" for="check_all">Approve All</label>
															Action
														</th>
														<th style="white-space: nowrap;">User ID</th>
														<th>Name</th>
														<th>PAN</th>
														<th>Mobile</th>
														<th>Transaction For</th>
														<th>Payable Amount</th>
														<th>Bank</th>
														<th>Branch</th>
														<th>A/c No.</th>
														<th>IFSC Code</th>
														<th>Transaction Date</th>
													</tr>
												</thead>
												<tbody id="tableBody">
													<?php
													$i = 0;
													foreach ($records as $row) {
														$i++;
														//DEFAULT VALUES SET
															$class_center = "has-html text-center";

														// DATA
															$id_member = $row['id'];
															$id_member_encode = base64_encode(json_encode($id_member));     //ENCODE
															$user_id_member = $row['user_id'];
															$name_member = $row['name'];
															$email = $row['email'];
															$mobile = $row['mobile'];
															$pan = $row['pan'];
															$withdrawal_amount = $row['withdrawal_amount'];
															$bank_name = $row['bank_name'];
															$branch_name = $row['branch_name'];
															$account_no = $row['account_no'];
															$ifs_code = $row['ifs_code'];
															$status = $row['status'];
															$create_date = $row['create_date'];
														// DATA

                                                        $user_txn_for = "WITHDRAWAL";
                                                        $class_txn = "success";
														
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
																<td class="no_print text-center">
																	<?php
																		if ($status!='approved') {
																			?>
																				<!-- <input type="hidden" name="approve_transaction[<?php echo $id_member;?>]" value=""> -->
																				<!-- <input type="checkbox" id="approve_transaction_<?php echo $id_member;?>" name="approve_transaction[]" class="checkSingle" value="<?php echo $id_member;?>">
																				<label for="approve_transaction_<?php echo $id_member;?>"><?php echo $user_id_member;?></label> -->
																				<!-- <a target='_blank' href='print_relieving.php?id_member=<?php echo $id_member_encode; ?>' class='badge' style='background:#E07C24;margin-top:5px;margin-bottom:5px;' >
																					<i class="align-middle" data-feather="printer"></i>  Approve
																				</a> -->

																				<!-- <span class='badge bg-success rounded-pill show-pointer' style="background: #E07C24 !important;" data-bs-toggle="modal" data-bs-target="#ModalAction">Approve</span> -->
																				<span class='badge bg-success rounded-pill show-pointer' style="background: #E07C24 !important;" data-bs-toggle="modal" data-bs-target="#ModalAction" data-id="<?php echo $id_member; ?>" data-user_id="<?php echo $user_id_member; ?>" data-amount_withdrawal="<?php echo $withdrawal_amount;?>">Action</span>

																				<!-- <span class='badge bg-danger rounded-pill show-pointer' style="background: #6A1B4D !important;">Reject</span> -->

																			<?php
																		} else {
																			?>
																				<span class='badge bg-danger rounded-pill' style="background: #6A1B4D !important;">Approved</span>
																			<?php
																		}
																	?>
																</td>
																<td class="has-html text-center">
																	<span>
																		<?php echo $user_id_member; ?>
																		<input type="hidden" id="txn_user_<?php echo $id_member;?>" name="txn_user_id_member[]" class="" value="<?php echo $user_id_member;?>">
																	</span>
																</td>
																<td class="text-start"><?php echo $name_member; ?></td>
																<td class="text-center"><?php echo $pan; ?></td>
																<td class="text-center"><?php echo $mobile; ?></td>
																<td class="has-html text-center" onclick='copyToClipboard("<?php echo "$user_txn_for"; ?>")' style="cursor:pointer;" title='Click to copy'>
																	<span class="badge bg-<?php echo $class_txn; ?> text-uppercase" style="white-space: normal;">
																		<?php echo $user_txn_for; ?>
																	</span>
																</td>
																<td class="has-html text-center"><span class="badge bg-success" style="white-space: normal;"><?php echo $withdrawal_amount; ?></span></td>
																<td class="text-start"><?php echo $bank_name; ?></td>
																<td class="text-start"><?php echo $branch_name; ?></td>
																<td class="has-html text-start"><span><?php echo $account_no; ?></span></td>
																<td class="text-start"><?php echo $ifs_code; ?></td>
																<td class="text-center"><?php echo $create_date; ?></td>
															</tr>

														<?php
													}
													?>
												</tbody>
												<tfoot class="no_print">
													<tr>
														<th>Sr</th>
														<th class="no_print">
															<input class="d-none" type="checkbox" id="check_all" name="approve_all" value="approve_all">
															<label class="d-none" for="check_all">Approve All</label>
															Action
														</th>
														<th style="white-space: nowrap;">User ID</th>
														<th>Name</th>
														<th>PAN</th>
														<th>Mobile</th>
														<th>Transaction For</th>
														<th>Payable Amount</th>
														<th>Bank</th>
														<th>Branch</th>
														<th>A/c No.</th>
														<th>IFSC Code</th>
														<th>Transaction Date</th>
													</tr>
												</tfoot>
											</table>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</main>

				<?php include("footer.php"); ?>
			</div>
		</div>
	</body>

	<?php include("scripts.php"); ?>

	<!-- BEGIN ModalAction -->
		<div class="modal fade" id="ModalAction" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">Withdrawal Transaction</h3>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p class="mb-0">
							<div class="row align-items-center">
								<input type="hidden" id="id_withdrawal">
								<input type="hidden" id="id_user">
								<input type="hidden" id="amount_withdrawal">
								<div class="mb-3 col-md-12">
									<label for="select_action" class="text-bold">Select Action To Perform</label>
									<select name="" id="select_action" class="form-control" onchange="toggle_comment();">
										<option value="">Choose Action</option>
										<option value="approved">Approve</option>
										<option value="rejected">Reject</option>
									</select>
								</div>
								<div class="mb-3 col-md-12 d-none" id="reject_comment_container">
									<label for="reject_comment" class="text-bold">Reason/Comment For Rejection</label>
									<input type="text" name="reject_comment" id="reject_comment" class="form-control" placeholder="Enter comment">
								</div>
							</div>
							<div style="float:right;">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success" id="btn_submit" onclick="process_withdrawal();">Submit</button>
							</div>
						</p>
					</div>
				</div>
			</div>
		</div>
	<!-- END ModalAction -->

	<?php
		// COUNTER
			$page_id = 1;
			counter($page_id);
		// COUNTER
	?>

	<script type="text/javascript">
        var restrict_all = document.getElementById("restrict_all");
        $(document).ready(function() { 
            // $("#restrict_all").click(function(){
            //     $('input:checkbox').not(this).prop('checked', this.checked);
            // });

            $("#check_all").change(function() {
                if (this.checked) {
                    $(".checkSingle").each(function() {
                        this.checked=true;
                    });
                } else {
                    $(".checkSingle").each(function() {
                        this.checked=false;
                    });
                }
            });

            $(".checkSingle").click(function () {
                if ($(this).is(":checked")) {
                    var isAllChecked = 1;
                    $(".checkSingle").each(function() {
                        if (!this.checked)
                            isAllChecked = 0;   // 0 if any one not checked
                    });

                    if (isAllChecked == 1) {
                        $("#check_all").prop("checked", true);
                    }
                }
                else {
                    var isAllChecked = 0;
                    $(".checkSingle").each(function() {
                        if (this.checked)
                            isAllChecked = 1;   // 1 if any one or (all-1) remain checked || (all-1) >> Bcz it is called when One uncheck is performed
                    });

                    if (isAllChecked == 1) {
                        $("#check_all").prop("checked", false);
                    }
                }
            });

			// MAKE ONLY SINGLE ITEM CLICKED
			$('input[type="checkbox"]').on('change', function() {
				$('input[type="checkbox"]').not(this).prop('checked', false);
			});
        });
    </script>

	<script>
        // DataTables with Column Search by Text Inputs
        document.addEventListener("DOMContentLoaded", function () {
            // DataTables
            var $filename = document.title;
            var default_Order = '<?php echo $default_Order;?>';
            var length_menu = '<?php echo isset($length_menu)?$length_menu:"";?>';
            var table = $('#datatables').DataTable({
                dom: /*'lBfrtip'*/ 'lBfrtip',
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

                        // EXPORT SELECT VALUES IN DATATABLE AND STRIP OUT HTML TAGS
                            format: {
                                body: function (data, row, column, node) {
                                    // if it is select
                                        // if (column == 9) {
                                        //     data = $(data).find("option:selected").text()
                                        // }
                                    // STRIP OUT HTML TAGS
                                        // console.log(node.classList.contains("has-html"));
                                        // CUSTOMIZE ELEMENT BY CLASS NAME
                                        if(hasClass(node,"has-html")) {
                                            var column_has_class = column;
                                            if (column == column_has_class) {
                                                data = $(data).text();
                                            }
                                        }
                                        // if ((column >= 2 && column <=3) || (column >= 8 && column <=9)) {
                                        //     // console.log($(data).find('span').textContent + "");
                                        //     // data = $(data).find('span').innerText
                                        //     data = $(data).text();
                                        // }
                                    // REPLACE <BR> WITH NEW LINE
                                        // if (column == 7) {
                                        //     data = data.replace( /<br\s*\/?>/ig, ";" );
                                        // }
									// EXCEL EXPORT NUMBER MORE THAN 15 DIGITS (LAST DIGIT 0) >> ISSUE SOLVED BY CONVERTING NO. TO STRING
										data = '\u200C' + data; //will cast the number to string.
									// EXCEL EXPORT NUMBER MORE THAN 15 DIGITS (LAST DIGIT 0) >> ISSUE SOLVED BY CONVERTING NO. TO STRING
									data = data.replace(/^\s+|\s+$/gm,'');
                                    return data
                                }
                            }
                    },

					// EXCEL EXPORT NUMBER MORE THAN 15 DIGITS (LAST DIGIT 0) >> ISSUE SOLVED BY CONVERTING NO. TO STRING
						customizeData: function (data) {
							var ind = data.header.indexOf("A/c No."); // This code is to find the column name's index which you want to cast.
							for (var i = 0; i < data.body.length; i++) {
								data.body[i][ind] = '\u200C' + data.body[i][ind]; //will cast the number to string.
							}
						},
					// EXCEL EXPORT NUMBER MORE THAN 15 DIGITS (LAST DIGIT 0) >> ISSUE SOLVED BY CONVERTING NO. TO STRING

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

                        // EXPORT SELECT VALUES IN DATATABLE AND STRIP OUT HTML TAGS
                            format: {
                                body: function (data, row, column, node) {
                                    // if it is select
                                        // if (column == 9) {
                                        //     data = $(data).find("option:selected").text()
                                        // }
                                    // STRIP OUT HTML TAGS
                                        // console.log(node.classList.contains("has-html"));
                                        // CUSTOMIZE ELEMENT BY CLASS NAME
                                        if(hasClass(node,"has-html")) {
                                            var column_has_class = column;
                                            if (column == column_has_class) {
                                                data = $(data).text();
                                            }
                                        }
                                        // if ((column >= 2 && column <=3) || (column >= 8 && column <=9)) {
                                        //     // console.log($(data).find('span').textContent + "");
                                        //     // data = $(data).find('span').innerText
                                        //     data = $(data).text();
                                        // }
                                    // REPLACE <BR> WITH NEW LINE
                                        // if (column == 7) {
                                        //     data = data.replace( /<br\s*\/?>/ig, "\n" );
                                        // }
                                    return data
                                }
                            }
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
                        // var countRows = $('#datatables tr').length;
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
                // {
                //     extend: 'colvis',
                //     text: 'COLUMNS',
                //     className: 'btn-info',
                //     key: { // press L for COLUMNS
                //         key: 'l',
                //         altKey: false
                //     },
                //     postfixButtons: [{
                //         extend: 'colvisRestore',
                //         text: 'Show All'
                //     }]
                // },
                ],
                "columnDefs": [{
                    // //   DISABLE ORDERING OF ACTION COLUMN
                    "targets": [1],
                    // // DISABLE SORTING ON LAST COLUMN
                    // "targets": [-1],
                    "orderable": false,
                },],

                // Row Callback for Customization of Row
                // 'rowCallback': function(row, data, index){
                //     if(data[3]> 11.7){
                //         $(row).find('td:eq(3)').css('color', 'red');
                //     }
                //     if(data[2].toUpperCase() == 'EE'){
                //         $(row).find('td:eq(2)').css('color', 'blue');
                //     }
                // },
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

        function hasClass( elem, klass ) {
            // https://stackoverflow.com/questions/10960573/what-is-the-best-way-to-check-if-element-has-a-class
            return (" " + elem.className + " " ).indexOf( " "+klass+" " ) > -1;
        }
    </script>

	<script>
		function toggle_comment() {
			var select_action = document.getElementById("select_action");
			var reject_comment_container = document.getElementById("reject_comment_container");
			var reject_comment = document.getElementById("reject_comment");
			var id_withdrawal = document.getElementById("id_withdrawal");

			if (select_action.value == "rejected") {
				reject_comment_container.classList.remove('d-none');
				reject_comment.setAttribute('required','true');
			} else {
				reject_comment_container.classList.add('d-none');
				reject_comment.setAttribute('required','false');
			}
		}

		function process_withdrawal() {
			var action = "process_withdrawal";
			var select_action = document.getElementById("select_action");
			var reject_comment = document.getElementById("reject_comment");
			var id_withdrawal = document.getElementById("id_withdrawal");
			var amount_withdrawal = document.getElementById("amount_withdrawal");
			var id_user = document.getElementById("id_user");

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
					id_withdrawal: id_withdrawal.value,
					amount_withdrawal: amount_withdrawal.value,
					action_to_perform: select_action.value,
					reject_comment: reject_comment.value
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
						showNotif("Withdrawal Approved Successfully","success");
					} else {
						showNotif("Withdrawal Rejected Successfully","danger");
					}

					setTimeout(function(){
						location.reload();
					},2000);
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
                $('#ModalAction').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var id = button.data('id'); // Extract info from data-* attributes
                    var id_user = button.data('user_id'); // Extract info from data-* attributes
                    var amount_withdrawal = button.data('amount_withdrawal'); // Extract info from data-* attributes
                    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
					// package = "Package "+package;
					// payment = "20% Amount of "+package+"= Rs."+payment;
                    var modal = $(this);
                    // modal.find(id).val(src);
                    // modal.find('#image').src=src;
					document.getElementById("id_withdrawal").value = id;
					document.getElementById("id_user").value = id_user;
					document.getElementById("amount_withdrawal").value = amount_withdrawal;
                });
            });
        // PASS DATA TO MODAL POPUP
	</script>

	<script>
        function callAjaxMemberName() {
			var action = "get_sponsor";
			var sponsor_id = document.getElementById("member_user_id");
			var sponsor_name = document.getElementById("member_name");

			// Call ajax for pass data to other place
			$.ajax({
				type: "POST",
				url: "ajax.php",
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
    </script>

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
</html>
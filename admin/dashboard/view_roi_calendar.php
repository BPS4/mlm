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

    $show_modal = 0;
    $msg = $msg_type = "";
    $error = false;
    
	$rows_inserted = 0;
    if (isset($submit)) {
        $date_range = mysqli_real_escape_string($conn,$_POST['date_range']);
        $remarks = mysqli_real_escape_string($conn,$_POST['remarks']);

        $query_insert = "INSERT INTO `roi_calendar`(`roi_date`, `remarks`, `create_date`) VALUES ";
        $query_insert_value="";
        
        // Remove Intermediate Commas From String
        $date_range_array = explode(",", $date_range);
        foreach ($date_range_array as $key => $date) {
            $query = mysqli_query($conn, "SELECT `roi_date` FROM `roi_calendar` WHERE `roi_date`='$date' AND `is_active`=1 ");
            $num_rows = mysqli_num_rows($query);
            if ($num_rows==0) {
                $query_insert_value .= "('$date','$remarks','$current_date'),";
                $rows_inserted++;
            }
        }

        // Remove Trailing Comma From Query
        // $query_insert = substr($query_insert,0,strlen($query_insert)-1);

        if ($query_insert_value != "") {
            $query_insert_value = rtrim($query_insert_value,",");
            $query_insert .= "$query_insert_value";
            if (mysqli_query($conn,$query_insert)) {
                $msg = "$rows_inserted Date Added Successfully To Stop ROI Transactions!";
                $msg_type = "success";
            } else {
                $msg = "Error in submitting Dates!";
                $msg_type = "danger";
            }
        } else {
            $msg = "Same date is already inserted!";
            $msg_type = "warning";
        }
        // $rows_inserted = mysqli_num_rows($res);
    }

    if (isset($_POST['status'])) {
        $status = ($is_active == 0 ? 1 : 0);
        $id = mysqli_real_escape_string($conn,$_POST['id']);
        $date_to_update = mysqli_real_escape_string($conn,$_POST['date_to_update']);
        
        if ($current_date_only > $date_to_update) {
            $msg = "Unable to Change The Status, as the Selected Date is before the Current Date. Kindly choose a Future Date!";
            $msg_type = "default";
            goto error_occurred;
        }
        if (mysqli_query($conn, "UPDATE `roi_calendar` SET `is_active`='$status', `update_date`='$current_date' WHERE `id`='$id'")) {
            if ($is_active == 0) {
                $msg = "Date Banned Successfully";
                $msg_type = "danger";
            } else if ($is_active == 1) {
                $msg = "Date Allowed Successfully";
                $msg_type = "success";
            }
        }
    }
    
    if (isset($_POST['delete'])) {
        $id = mysqli_real_escape_string($conn,$_POST['id']);
        $date_to_update = mysqli_real_escape_string($conn,$_POST['date_to_update']);
        
        // if ($current_date_only > $date_to_update) {
        //     $msg = "Unable to Delete Selected Date as it is before the Current Date. Kindly choose a Future Date!";
        //     $msg_type = "default";
        //     goto error_occurred;
        // }

        $res = mysqli_query($conn, "UPDATE `roi_calendar` SET `is_active`=0, `delete_date`='$current_date' WHERE `id`='$id'");
        if (mysqli_affected_rows($conn)>0) {
            // unlink($url_file);
            $msg = "Date Deleted Successfully!";
            $msg_type = "danger";
        }
    }
    
    error_occurred:

    $query = mysqli_query($conn, "SELECT * FROM `roi_calendar` WHERE `delete_date` IS NULL ORDER BY `roi_date` DESC ");
    $roi_dates = mysqli_fetch_all($query,MYSQLI_ASSOC);
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

    <title><?php  echo "ROI Dates List - Admin - Maxizone"; ?></title>

    <link rel="canonical" href="view_member.php">

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
                            <h3>ROI Dates List</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div id="loader"></div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Total Dates closed for ROI Transactions <span class="badge bg-success" style="font-weight: bold;"><?php echo $total_count; ?></span></h5>
                                    <span style="position:absolute;left:0px;margin-left:15px;">
                                        <a href='./' class="badge bg-warning fs-100" style="text-decoration:none;">
                                            Dashboard
                                        </a>
                                        <b>
                                            <i class="align-middle" data-feather="chevrons-right"></i>
                                        </b>
                                        <a href="ciew_roi_calendar.php" class="badge bg-info fs-100" style="text-decoration:none;">
                                            ROI Calendar
                                        </a>
                                    </span>
                                    <a class="badge bg-success" data-bs-toggle="modal" data-bs-target="#ModalAdd" style="text-decoration:none;position:absolute;right:0px;margin:15px;">
										<i class="align-middle" data-feather="plus-square" style="border-radius: 50%;margin-right:5px;"></i>
										Add New
									</a>
                                </div>
                                <div class="card-body">

                                    <table id="datatables-a4-landscape" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th style="white-space: nowrap;">Sr</th>
                                                <th class="no_print" style="white-space: nowrap;">Action</th>
                                                <th style="white-space: nowrap;">Banned Date</th>
                                                <th class="no_print" style="white-space: nowrap;">Status</th>
                                                <th style="white-space: nowrap;">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                            <?php
                                                $i = 0;
                                                foreach ($roi_dates as $item) {
                                                    $i++;
                                                    $id = $item['id'];
                                                    $roi_date = $item['roi_date'];
                                                    $is_active = $item['is_active'];
                                                    $remarks = $item['remarks'];
                                                    $classStatus = ($is_active == 0) ? "bg-danger" : "bg-success";
                                                    if ($remarks == "") {
                                                        $remarks = ($is_active == 0) ? "<span class='badge bg-success'>ROI Allowed</span>" : "<span class='badge bg-warning'>ROI Stopped</span>";
                                                    } else {
                                                        $remarks = "<span class='badge bg-primary'>$remarks</span>";
                                                    }
                                                    //CHECK DATE FOR YYYY-MM-DD FORMAT
                                                        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $roi_date)) {
                                                            $date = date_create($roi_date);
                                                            $roi_date_show = date_format($date, "d-M-Y");
                                                        }
                                                    //CHECK DATE FOR YYYY-MM-DD FORMAT
                                                    ?>
                                                        <!-- REPEAT ITEM -->
                                                        <tr>
                                                            <td class="text-center"><?php echo $i; ?></td>
                                                            <td class="text-center no_print">
                                                                <span class="badge bg-danger" style="white-space: normal;">
                                                                    <form action="" method="POST">
                                                                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                                        <input type="hidden" name="date_to_update" value="<?php echo $roi_date; ?>">
                                                                        <input type="submit" name="delete" onclick="return confirm('Are you sure to Delete Date <?php echo $roi_date_show; ?> From ROI Transaction Stop List? \r\nIt will remove the selected date from Stop List and Will Allow ROI Transactions on the date!');" value="Delete" style="background: transparent; border-style:hidden; color:white;">
                                                                    </form>
                                                                </span>
                                                            </td>
                                                            <td class="text-center"><?php echo $roi_date_show; ?></td>
                                                            <td class="text-center no_print">
                                                                <span class="badge <?php echo $classStatus; ?>" style="white-space: normal;">
                                                                    <form action="" method="POST">
                                                                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                                        <input type="hidden" name="date_to_update" value="<?php echo $roi_date; ?>">
                                                                        <input type="hidden" name="is_active" value="<?php echo $is_active; ?>">
                                                                        <input type="submit" name="status" onclick="return confirm('Are you sure to <?php echo ($is_active == 0)?'Allow':'Ban'; ?> Date <?php echo $roi_date_show; ?> For ROI Transactions?');" value="<?php echo ($is_active) ? "Active" : "InActive"; ?>" style="background: transparent; border-style:hidden; color:white;">
                                                                    </form>
                                                                </span>
                                                            </td>
                                                            </td><td class="text-center"><?php echo $remarks; ?></td>
                                                        </tr>

                                                    <?php
                                                }
                                            ?>
                                        </tbody>
                                        <tfoot class="no_print d-none">
                                            <tr>
                                                <th style="white-space: nowrap;">Sr</th>
                                                <th style="white-space: nowrap;">Action</th>
                                                <th style="white-space: nowrap;">Banned Date</th>
                                                <th class="no_print" style="white-space: nowrap;">Status</th>
                                                <th style="white-space: nowrap;">Remark</th>
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
    
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" rel="stylesheet" /> -->

    <!-- DATEPICKER SET 2 ALL TOGETHER NOT IN HEAD BUT ANYWHERE -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <!-- <script src="https://code.jquery.com/jquery-3.6.0.js"></script> -->
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <!-- DATEPICKER SET 2 ALL TOGETHER NOT IN HEAD BUT ANYWHERE -->

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            $("#Txt_Date").datepicker({
                // format: 'd-M-yyyy',
                dateFormat: 'yy-mm-dd',
                inline: false,
                lang: 'en',
                step: 5,
                // multidate: 5,
                multidate: true,
                closeOnDateSelect: true,
                // DISABLE PAST DATES
                startDate: '+1d',
                minDate: 1,
                // showButtonPanel: true
            });
        });
    </script>
    
    <!-- BEGIN ModalAdd -->
    <div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">ROI Stop Dates</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">
                    <form method="POST" action="">                        
                        <div class="row">
                            <div class="mb-1 col-md-12">
                                <label class="form-label" for="Txt_Date">Select Date To Stop ROI Transaction</label>
                            </div>
                            <div class="mb-3 col-md-12">
                                <input type="text" name="date_range" id="Txt_Date" placeholder="Choose Date" style="cursor: pointer;" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-1 col-md-12">
                                <label class="form-label" for="remarks">Remarks</label>
                            </div>
                            <div class="mb-3 col-md-12">
                                <input name="remarks" id="remarks" placeholder="Enter Holiday Remarks" class="form-control">
                            </div>
                        </div>
                        <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                        <div style="float:right">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success" id="btn-save" name="submit">Save changes</button>
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
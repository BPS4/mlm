<script src="../assets/js/app.js"></script>

<!-- HOME PAGE SCRIPTS -->
    <!-- <script>
        document.addEventListener("DOMContentLoaded", function () {
            $("#datetimepicker-dashboard").datetimepicker({
                inline: true,
                sideBySide: false,
                format: "L"
            });
        });
    </script> -->

<!-- DATATABLE SCRIPTS -->
    <script>
        $(document).ready(function() {
            // $('#list').style.display="block";
            // $(".card").show();
            $("#loader").hide();
            $(".card").css("display","block");
            $("#datatables-column-search-text-inputs").css("display","table");
        });

        function getNumFilteredRows(id){
            var info = $(id).DataTable().page.info();
            return info.recordsDisplay;
        }

        // DataTables with Column Search by Text Inputs
            document.addEventListener("DOMContentLoaded", function () {
                // Setup - add a text input to each footer cell
                $('#datatables-column-search-text-inputs tfoot th').each(function () {
                    // var title = $(this).text();
                    // $(this).html('<input type="text" class="form-control" placeholder="' + title + '" />');
                });
                // DataTables
                var $filename = document.title;
                var default_Order = '<?php echo $default_Order;?>';
                var length_menu = '<?php echo isset($length_menu)?$length_menu:"";?>';
                var table = $('#datatables-column-search-text-inputs').DataTable({
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
                        pageSize: 'TABLOID', //A3 , A5 , A6 , legal , letter
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

                            var filteredRows = getNumFilteredRows('#datatables-column-search-text-inputs');

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
        // DataTables with Column Search by Select Inputs
            document.addEventListener("DOMContentLoaded", function () {
                $('#datatables-column-search-select-inputs').DataTable({
                    initComplete: function () {
                        this.api().columns().every(function () {
                            var column = this;
                            var select = $('<select class="form-control"><option value=""></option></select>').appendTo($(column.footer()).empty()).on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });
                            column.data().unique().sort().each(function (d, j) {
                                select.append('<option value="' + d + '">' + d + '</option>')
                            });
                        });
                    }
                });
            });
    </script>

<!-- NOTIFICATION SCRIPT -->
    <script>
        function showNotif(message,type){
            var message = message;
            var type = type;
            var duration = '5000';
            var ripple = 'true';
            var dismissible = 'true';
            var positionX = 'right';
            var positionY = 'bottom';
            window.notyf.open({
                type,
                message,
                duration,
                ripple,
                dismissible,
                position: {
                    x: positionX,
                    y: positionY
                }
            });
        }
    </script>

    <script>
        function check_image_size_before_upload(element_id,max_size) {
            var fileUpload = document.getElementById(element_id);
            if (typeof (fileUpload.files) != "undefined") {
                var size = parseFloat(fileUpload.files[0].size / 1024).toFixed(2);

                if (size > max_size) {
                    msg = max_size + " KB max size allowed.";
                    showNotif(msg, "error");
                    fileUpload.value = "";
                    return false;
                }
            } else {
                alert("This browser does not support HTML5.");
            }
        }
    </script>
    
<!--FOR COPY CSV EXCEL PDF PRINT BUTTONS-->
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<!--FOR COLUMN VISIBILITY "COLVIS" SELECTOR-->
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.colVis.min.js"></script>

    <script>
        // You can use any function to copy here
        function copyToClipboard(textToCopy) {
            showNotif('Copied!',"default");

            var input = document.createElement("input");
            document.body.appendChild(input);
            input.value = textToCopy;
            input.select();
            document.execCommand("Copy");
            input.remove();


        }

        function copyLastColumn(tr) {
            copyToClipboard(tr.lastElementChild.innerHTML);
            showNotif('copied to clipboard',"default");
        }
    </script>

    <script>
		document.addEventListener("DOMContentLoaded", function () {
			// Select2
			$(".select2").each(function () {
				$(this)
					.select2({
						placeholder: "Select Option",
						dropdownParent: $(this).parent()
					});
			})
        });
    </script>
    
    <script>
        function go_to_member(member_id) {
            var action = "go_to_member";

            // Call ajax for pass data to other place
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {
                    action: action,
                    member_id
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
                    //             // document.getElementById("overlay").style.display = "block";
                    //             btn_save.innerHTML="SAVING...";
                    //             console.log(e.loaded / e.total);
                    //         }
                    //     };
                    //     return xhr;
                // },

                success: function(response) {
                    if (response == "success") {
                        // OPEN DASHBOARD IN NEW TAB
                        window.open('../../user/', "_blank") || window.location.replace('../../user/');
                        // This will open it on the same tab if the pop-up is blocked.
                    } else {
                        showNotif("Unable to Switch To Member... Try Again", "danger");
                    }
                }
            });
        }
        
        function go_to_member_profile(member_id) {
            var action = "go_to_member";

            // Call ajax for pass data to other place
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {
                    action: action,
                    member_id
                }, // getting filed value in serialize form And Passing additional Value of 'action' To perform Task Accordingly
                // dataType: 'JSON',
                cache: false,

                success: function(response) {
                    if (response == "success") {
                        // OPEN DASHBOARD IN NEW TAB
                        window.open('../../user/view_profile.php', "_blank") || window.location.replace('../../user/view_profile.php');
                        // This will open it on the same tab if the pop-up is blocked.
                    } else {
                        showNotif("Unable to Switch To Member... Try Again", "danger");
                    }
                }
            });
        }
    </script>

    <?php
        // COUNTER
            function counter($page_id) {
                // $page_id = 1;
                $conn = $GLOBALS['conn'];
                $current_date = $GLOBALS['current_date'];
                $sql = "UPDATE `counter` SET `visits` = `visits`+1 WHERE `page_id` = '$page_id'";
                mysqli_query($conn, $sql);

                $sql = "SELECT `visits` FROM `counter` WHERE `page_id` = '$page_id'";
                $res = mysqli_query($conn, $sql);

                if (mysqli_num_rows($res) > 0) {
                    if ($row = mysqli_fetch_array($res)) {
                        $visits = $row["visits"];
                    }
                } else {
                    // echo "no results";
                }

                // IP BASED COUNTER
                    function getUserIpAddr() {
                        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                            //ip from share internet
                            $ip = $_SERVER['HTTP_CLIENT_IP'];
                        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                            //ip pass from proxy
                            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                        } else {
                            $ip = $_SERVER['REMOTE_ADDR'];
                        }
                        return $ip;
                    }

                    // echo 'Your IP Address - '.getUserIpAddr();
                    $ip = getUserIpAddr();
                    // echo "<br>";

                    $qry = "SELECT * FROM `visitor_ip` WHERE `ip` = '$ip' AND `page_id` = '$page_id'";
                    $result = mysqli_query($conn, $qry);
                    $num = mysqli_num_rows($result);
                    if ($num == 0) {
                        $qry3 = "INSERT INTO `visitor_ip`(`page_id`, `ip`,`visit_date`) VALUES ('$page_id', '$ip','$current_date')";
                        mysqli_query($conn, $qry3);
                        //echo "new ip register";	
                        $qry1 = "SELECT * FROM `counter_ip` WHERE `page_id` = '$page_id'";
                        $result1 = mysqli_query($conn, $qry1);
                        $row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);
                        $count = $row1['visits'];
                        $count = $count + 1;
                        //echo "<br>";
                        //echo "number of unique visiters is $count";
                        $qry2 = "UPDATE `counter_ip` SET `visits`=`visits`+1 WHERE `page_id` = '$page_id' ";
                        $result2 = mysqli_query($conn, $qry2);
                    } else {
                        $qry1 = "SELECT * FROM `counter_ip` WHERE `page_id` = '$page_id'";
                        $result1 = mysqli_query($conn, $qry1);
                        $row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);
                        $count = $row1['visits'];
                        //echo "<br>";
                        //echo "number of unique visiters is $count";
                    }
                    $numlength = strlen((string)$count);
                // IP BASED COUNTER
            }
        // COUNTER
    ?>
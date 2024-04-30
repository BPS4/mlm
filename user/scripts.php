
    <!-- latest jquery-->
        <script src="assets/js/jquery-3.5.1.min.js"></script>
        
    <!-- Bootstrap js-->
        <script src="assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- feather icon js-->
        <script src="assets/js/icons/feather-icon/feather.min.js"></script>
        <script src="assets/js/icons/feather-icon/feather-icon.js"></script>
    <!-- scrollbar js-->
        <script src="assets/js/scrollbar/simplebar.js"></script>
        <script src="assets/js/scrollbar/custom.js"></script>
    <!-- Sidebar jquery-->
        <script src="assets/js/config.js?1.0"></script>
    <!-- Plugins JS start-->
        <script src="assets/js/sidebar-menu.js?1"></script>
        <script src="assets/js/chart/knob/knob.min.js"></script>
        <script src="assets/js/chart/knob/knob-chart.js"></script>

        <!-- TOGETHER -->
        <script src="assets/js/chart/apex-chart/apex-chart.js"></script>
        <script src="assets/js/chart/apex-chart/stock-prices.js"></script>
        <!-- <script src="assets/js/dashboard/default.js"></script> -->
        <!-- TOGETHER -->
        <script src="assets/js/notify/bootstrap-notify.min.js"></script>
        <!-- <script src="assets/js/notify/index.js"></script> -->
        <script src="assets/js/datepicker/date-picker/datepicker.js"></script>
        <script src="assets/js/datepicker/date-picker/datepicker.en.js"></script>
        <script src="assets/js/datepicker/date-picker/datepicker.custom.js"></script>
        <script src="assets/js/photoswipe/photoswipe.min.js"></script>
        <script src="assets/js/photoswipe/photoswipe-ui-default.min.js"></script>
        <script src="assets/js/photoswipe/photoswipe.js"></script>
        <script src="assets/js/typeahead/handlebars.js"></script>
        <!-- <script src="assets/js/typeahead/typeahead.bundle.js"></script> -->
        <!-- <script src="assets/js/typeahead/typeahead.custom.js"></script> -->
        <script src="assets/js/typeahead-search/handlebars.js"></script>
        <!-- <script src="assets/js/typeahead-search/typeahead-custom.js"></script> -->
        <script src="assets/js/height-equal.js"></script>
        <script src="assets/js/sweet-alert/sweetalert.min.js"></script>
        <script src="assets/js/select2/select2.full.min-1.js"></script>
        <script src="assets/js/select2/select2-custom-1.js"></script>
        <script src="assets/js/form-validation-custom-1.js"></script>
        <script src="assets/js/prism/prism.min-1.js"></script>
        <script src="assets/js/counter/jquery.waypoints.min-1.js"></script>
        <script src="assets/js/counter/jquery.counterup.min-1.js"></script>
        <script src="assets/js/datatable/datatables/jquery.dataTables.min-1.js"></script>
        <script src="assets/js/datatable/datatables/datatable.custom-1.js"></script>
        <!-- <script src="assets/js/tooltip-init-1.js"></script> -->
        <script src="assets/js/popover-custom-1.js"></script>

    <!-- Plugins JS Ends-->
    <!-- Theme js-->
        <script src="assets/js/script.js"></script>
        <!-- <script src="assets/js/theme-customizer/customizer.js"></script> -->
    <!-- login js-->
    <!-- Plugin used-->

    <script src="../assets/sweetalert2/sweetalert2.js"></script>

    <link rel="stylesheet" href="../assets/iziToast/iziToast.min.css">
    <script src="../assets/iziToast/iziToast.min.js"></script>
    
    <script>
        function showNotif(message, status) {
            // status>> question warning error success
            iziToast[status]({
                message: message,
                position: "bottomRight"
            });
        }

        // You can use any function to copy here
        function copyToClipboard(textToCopy) {
            showNotif('Copied!',"success");
            var input = document.createElement("input");
            document.body.appendChild(input);
            input.value = textToCopy;
            input.select();
            document.execCommand("Copy");
            input.remove();
        }

        function set_menu_active(position) {
            // $(".sidebar-list:nth-of-type("+position+")").children.addClass("active");
        }
    </script>

<script>
    // PATTERNS TO CHECK NAME EMAIL AND MOBILE
        var regex_email = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        var regex_name = /^[A-Za-z ]+$/;
        var regex_mobile = /^[5-9]{1}[0-9]{9}$/;
        var regex_pan = /^[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}$/;
        var regex_aadhaar = /^[0-9]{12}$/;
    
        // [6-9]{1}[0-9]{9}
        
        // TRUE >> REQUIRED
        // regex_email.test(x.value)
        // y.value.match(regex_name)
        // y.value.match(regex_mobile)
    // PATTERNS TO CHECK NAME EMAIL AND MOBILE
</script>
<!-- <a href="#0" class="scrollToTop active"><i class="las la-chevron-up"></i></a> -->

<!--====== BACK TOP TOP PART START ======-->
    <a href="#" class="back-to-top"><i class="lni-chevron-up"></i></a>
<!--====== BACK TOP TOP PART ENDS ======-->

    <!-- <script src="<?php echo ROOT_PATH; ?>/user/assets/vendor/libs/sweetalert2/sweetalert2.js"></script> -->
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>/assets/iziToast/iziToast.min.css">
    <script src="<?php echo ROOT_PATH; ?>/assets/iziToast/iziToast.min.js"></script>

    <!--====== Jquery js ======-->
    <script src="<?php echo ROOT_PATH; ?>/assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="<?php echo ROOT_PATH; ?>/assets/js/vendor/modernizr-3.7.1.min.js"></script>

    <!--====== Bootstrap js ======-->
    <script src="<?php echo ROOT_PATH; ?>/assets/js/popper.min.js"></script>
    <script src="<?php echo ROOT_PATH; ?>/assets/js/bootstrap.min.js"></script>

    <!--====== Plugins js ======-->
    <script src="<?php echo ROOT_PATH; ?>/assets/js/plugins.js"></script>

    <!--====== Slick js ======-->
    <script src="<?php echo ROOT_PATH; ?>/assets/js/slick.min.js"></script>

    <!--====== Ajax Contact js ======-->
    <script src="<?php echo ROOT_PATH; ?>/assets/js/ajax-contact.js"></script>

    <!--====== Counter Up js ======-->
    <script src="<?php echo ROOT_PATH; ?>/assets/js/waypoints.min.js"></script>
    <script src="<?php echo ROOT_PATH; ?>/assets/js/jquery.counterup.min.js"></script>

    <!--====== Magnific Popup js ======-->
    <script src="<?php echo ROOT_PATH; ?>/assets/js/jquery.magnific-popup.min.js"></script>

    <!--====== Scrolling Nav js ======-->
    <script src="<?php echo ROOT_PATH; ?>/assets/js/jquery.easing.min.js"></script>
    <script src="<?php echo ROOT_PATH; ?>/assets/js/scrolling-nav.js"></script>

    <!--====== wow js ======-->
    <script src="<?php echo ROOT_PATH; ?>/assets/js/wow.min.js"></script>

    <!--====== Particles js ======-->
    <script src="<?php echo ROOT_PATH; ?>/assets/js/particles.min.js"></script>

    <!--====== Main js ======-->
    <script src="<?php echo ROOT_PATH; ?>/assets/js/main.js"></script>

<script>
    "use strict";

    function showNotif(message, status) {
        // status>> question warning error success
        iziToast[status]({
            message: message,
            position: "bottomCenter"
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

<script>
    // Swal.fire({
    // title: 'Custom width, padding, color, background.',
    // width: 600,
    // padding: '3em',
    // color: '#716add',
    // background: '#fff url(/images/trees.png)',
    // backdrop: `
    //     rgba(0,0,123,0.4)
    //     url("/images/nyan-cat.gif")
    //     left top
    //     no-repeat
    // `
    // })
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
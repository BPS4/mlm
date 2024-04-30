<?php
require_once("../db_connect.php");

//Check User has KYC Done 
if (isset($_SESSION['session_id'])) {
    $user_id = $_SESSION['user_id'];
    $query_doc = "SELECT * FROM `user_document` WHERE `user_id`='$user_id' ORDER BY `create_date` ASC";
    $res = mysqli_query($conn,$query_doc);
    $docs = mysqli_fetch_all($res,MYSQLI_ASSOC);
    $count_docs_uploaded = mysqli_num_rows($res);
    if($count_docs_uploaded == 0) {
        //echo '<script>alert("Profile photo required.")</script>'; 
        $_SESSION['user_doc_message'] = "Profile photo required.";
        header("Location: ../user/view_profile.php");
    }
    else {
        if(isset($_SESSION['user_doc_message'])) {
        unset($_SESSION['user_doc_message']);
        }
    }


    $aadhaar_update = $pan_update = $bank_update = $photo_update = $kyc_done = 0;
    foreach ($docs as $item) {
        $user_doc_type = $item['doc_type'];
        $doc_file = $item['doc_file'];
        /*if ($user_doc_type == "aadhaar") {
        }

        if ($user_doc_type == "pan") {
        }

        if ($user_doc_type == "cheque") {
        }

        if ($user_doc_type == "passbook") {
            echo '<script>alert("Profile photo required.")</script>'; 
            header("Location: ../user/view_profile.php");
        }

        if ($user_doc_type == "address") {
        }
        */


        if ($user_doc_type == "photo" && $doc_file == null) {
            $_SESSION['user_doc_message'] = "Profile photo required.";
            header("Location: ../user/view_profile.php");
        }
    }
}

?>
<?php
    /* Database connection start */
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "maxizone";
        $conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
    /* Database connection start */
        
    $config_path = dirname(__FILE__)."/config/config.php";
    require_once($config_path);

?>
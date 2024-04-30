<?php
    // require 'Mail.php';

    // $issues = preg_replace("/\r|\n/", " ", $issues);
    // $address = preg_replace("/\r|\n/", " ", $address);
       
    $date = date('Y-m-d');

    // Define SMTP authentication parameters:
        $smtp_params['host'] = 'sg1-ss18.a2hosting.com';
        $smtp_params['auth'] = true;
        $smtp_params['username'] = 'webmaster@beckonglobal.com';
        $smtp_params['password'] = 'admin@123$DM';
    // Define SMTP authentication parameters:
    
    // echo serialize(array_values($_POST));
   
    function send_mail_register($name,$email,$mobile,$city_name) {
        $conn = $GLOBALS['conn'];
        $smtp_params = $GLOBALS['smtp_params'];
        $message = $GLOBALS['message'];

        $to_email = $email;
        
        // Define basic e-mail parameters:
        // $recipient = 'info.bikedoot@gmail.com,abhishek@beckonglobal.com';
        $recipient = $to_email;
        //$recipient = 'abhishek@beckonglobal.com';
        $headers['From'] = 'Beckon Global<webmaster@beckonglobal.com>';
        $headers['Reply-to'] = 'noreply@bikedoot.com';
        $headers['To'] = $to_email;
        $headers['CC'] = 'info.bikedoot@gmail.com,abhishek@beckonglobal.com';
        $headers['Subject'] = '[BikedooT] Registration Successful by '.$name;
        $headers['Date'] = date('r');
        $headers['Message-Id'] = '<'.uniqid().'@beckonglobal.com>';
        $headers['Content-Type'] = 'text/plain; charset=utf-8';
            
        $body = '
        Thank you for registering with us.
                Personal Details
                Name: '.$name.'
                Phone Number: '.$mobile.'
                Email: '.$email.'
                City: '.$city_name;
        // echo serialize(array_values($_POST));
    
        // Create a Mail class instance with the above parameters, and then send the message:
            $message = Mail::factory('smtp', $smtp_params);
            $res = $message->send($recipient, $headers, $body);
        return $res;
    }

    function insert_notif($user_id,$message) {
        $conn = $GLOBALS['conn'];
        $current_date = $GLOBALS['current_date'];
        $query_insert_notif = "INSERT INTO `notification`(`user_id`, `message`, `create_date`) VALUES ('$user_id','$message','$current_date')";
        mysqli_query($conn, $query_insert_notif);
    }

    // GET NOTIFICATIONS
        function get_notif($user_id) {
            $conn = $GLOBALS['conn'];
            $query_get_notif = "SELECT `id`, `user_id`, `message`, `create_date` FROM `notification` WHERE `user_id`='$user_id' ORDER BY `create_date` DESC";
            $res = mysqli_query($conn,$query_get_notif);
            $notifs = mysqli_fetch_all($res,MYSQLI_ASSOC);
            return $notifs;
        }

        $count_notif = 0;        
    // GET NOTIFICATIONS

    // STOP FORM RESUBMIT PHP
        function stop_form_resubmit() {
            ?>
                <script>
                    if ( window.history.replaceState ) {
                        window.history.replaceState( null, null, window.location.href );
                    }

                    function setCookie(cname, cvalue, exdays) {
                        const d = new Date();
                        d.setTime(d.getTime() + (exdays*24*60*60*1000));
                        let expires = "expires="+ d.toUTCString();
                        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
                    }
                </script>
            <?php
        }
    // STOP FORM RESUBMIT PHP

    $unique_id = uniqid();

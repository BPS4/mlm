<?php // Check if action parameter is set and call respective function

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






                if (isset($_POST['action'])) {
                   
                    $action = $_POST['action'];

                    $user_id = $_POST['user_id']; 
                    // echo $user_id;

                       // Prepare SQL statement
                $sql = "SELECT wallets.wallet_roi, wallets.wallet_commission,wallets.wallet_investment, users.name 
                FROM wallets 
                INNER JOIN users ON wallets.user_id = users.user_id 
                WHERE wallets.user_id = $user_id";

                            // Execute query
                        $result = $conn->query($sql);

                    // Initialize an empty array to store data
                    $data = array();

                    // Check if any rows are returned
                    if ($result->num_rows > 0) {
                        // Loop through each row and store the data in the array
                        while($row = $result->fetch_assoc()) {
                            $data[] = array(
                                'wallet_investment' => $row["wallet_investment"],
                                'wallet_roi' => $row["wallet_roi"],
                                'wallet_commission' => $row["wallet_commission"],
                                'name' => $row["name"]
                            );
                        }
                    } else {
                        // If no rows are returned, store a message in the array
                        $data['message'] = "No results found for user ID: $user_id";
                    }

                    // Close connection
                    $conn->close();

                    // Encode the data array as JSON
                    $json_response = json_encode($data);

                    // Set proper content type header for JSON
                    header('Content-Type: application/json');

                    // Return the JSON response
                    echo $json_response;

                                        
                                    
                }


                if (isset($_POST['do_action'])) {
                    
                



                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

                        // Extracting form data

                        $do_action = $_POST['do_action'];
                        $formData = $_POST['formData'];
    
                        $formDataString = $_POST['formData'];
                        parse_str($formDataString, $formDataArray);
    
                                            // Extract the user_id
                        $user_id = $formDataArray['user_id'];
    
                        // Output the user_id
                        
                        
                       
                        $user_name = $formDataArray['user_name'];
                        $wallet_type = $formDataArray['Wallet_Type'];
                        $available_amount = $formDataArray['Amount'];
                        $withdrawal_amount = $formDataArray['withdrawal_amount'];
                        $deduction = $formDataArray['Deduction'];
                        $net_amount = $formDataArray['Net_Amount'];
                        $balance_amount = $formDataArray['Balance_Amount'];
                        
                      
                       
                    
                       // Get today's date
                       $update_date = date("Y-m-d H:i:s"); // Current date and time in the format Y-m-d H:i:s
                       $create_date = date("Y-m-d H:i:s");
                    
                    // Prepare SQL statement to update wallet table
                    $sql = "UPDATE wallets SET ";
                    if ($wallet_type == 'Investment Wallet') {
                        $sql .= "wallet_investment = '$balance_amount', ";
                    } elseif ($wallet_type == 'ROI Wallet') {
                        $sql .= "wallet_roi = '$balance_amount', ";
                    } elseif ($wallet_type == 'Comission Wallet') {
                        $sql .= "wallet_commission = '$balance_amount', ";
                    }
                    $sql .= "update_date = '$update_date' WHERE user_id = '$user_id'";
                    
                    
                        // Execute update query
                        if ($conn->query($sql) === TRUE) {
                           
                            // Prepare SQL statement to insert into withdrawal table
                        $sql = "INSERT INTO withdrawal (user_id, withdrawal_amount, status,  tds, total_withdraw, create_date, update_date, from_wallet) VALUES ('$user_id', '$withdrawal_amount', 'pending',   '$deduction', '$net_amount', '$create_date', '$update_date', '$wallet_type')";
                    
                        $response = array();

                        // Execute insert query
                        if ($conn->query($sql) === TRUE) {
                           $response['success'] = true;
                           $response['message'] = "Withdrawal request submitted successfully.";
                        } else {
                            $response['success'] = false;
                            $response['message'] = "Error: " . $sql . "<br>" . $conn->error;
                        }

                        header('Content-Type: application/json');

                        // Encode the response array to JSON format and output it
                        echo json_encode($response);

                        
                    
                        } else {
                            echo "Error updating wallet: " . $conn->error;
                        }
                    
                        // Close connection
                        $conn->close();
                    }

                }

                if (isset($_POST['package_action'])) {
                   
                    $package_action = $_POST['package_action'];

                    $user_id = $_POST['user_id']; 
                    // echo $user_id;

                       // Prepare SQL statement
                $sql = "SELECT fund_transaction.transaction_type, fund_transaction.create_date,fund_transaction.transaction_amount,fund_transaction.status, users.name 
                FROM fund_transaction 
                INNER JOIN users ON fund_transaction.user_id = users.user_id 
                WHERE fund_transaction.user_id = $user_id";

                            // Execute query
                        $result = $conn->query($sql);

                    // Initialize an empty array to store data
                    $data = array();

                    // Check if any rows are returned
                    if ($result->num_rows > 0) {
                        // Loop through each row and store the data in the array
                        while($row = $result->fetch_assoc()) {
                            $data[] = array(
                                'transaction_type' => $row["transaction_type"],
                                'transaction_date' => $row["create_date"],
                                'transaction_amount' => $row["transaction_amount"],
                                'status' => $row["status"],
                                'name' => $row["name"],
                             
                            );
                        }
                    } else {
                        // If no rows are returned, store a message in the array
                        $data['message'] = "No results found for user ID: $user_id";
                    }

                    // Close connection
                    $conn->close();

                    // Encode the data array as JSON
                    $json_response = json_encode($data);

                    // Set proper content type header for JSON
                    header('Content-Type: application/json');

                    // Return the JSON response
                    echo $json_response;

                                        
                                    
                }

              
                                    
             
                    
                 
                
<?php
    require '../config/db_config.php';


    if(isset($_POST['submit'])){
        $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $confirm_password = filter_var($_POST['Cpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        $password_token = $_POST['password_token'];
        $password_token = mysqli_escape_string($connection, $password_token);

        if(!empty($password_token)){

            if(!empty($password) && !empty($confirm_password)){
                // Check if valid token
                $check_password_token_query = " SELECT * FROM users WHERE password_token = '$password_token' LIMIT 1";
                $check_password_token_result = mysqli_query($connection, $check_password_token_query);

                if(mysqli_num_rows($check_password_token_result) > 0){
                    $row = mysqli_fetch_assoc($check_password_token_result);
                    $expires_at = $row['expires_at'];

                    // Check if the token has expired
                    if(strtotime($expires_at) >= time()){

                        // Token is valid and not expired
                        if(strlen($password) > 8 || strlen($confirm_password) > 8){

                            if($password == $confirm_password){
                                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                                $update_password_query = "UPDATE users SET password='$hashed_password', password_token=NULL, expires_at=NULL WHERE password_token='$password_token' LIMIT 1";
                                $update_password_result = mysqli_query($connection, $update_password_query);

                                if($update_password_result){
                                    $_SESSION['login-success'] = "Successfully change password";
                                    header("location: ../login.php");
                                    die();
                                }else{
                                    $_SESSION['password_err'] = "Something went wrong. can not change password";
                                    header("location: ../password_change.php?token=" . $password_token . "");
                                    die();
                                }
                            }else{
                                $_SESSION['password_err'] = "Password did not match";
                                header("location: ../password_change.php?token=" . $password_token . "");
                                die();
                            }                        
                        }else{
                            $_SESSION['password_err'] = "Password should be more than 8 characters";
                            header("location: ../password_change.php?token=" . $password_token . "");
                            die();
                        }
                        
                    }else{
                        // $_SESSION['password_err'] = "The password reset link has expired. <br> Request for another reset password.";
                        header("location: ../password_change.php?token=" . $password_token);
                        die();
                    }
                }else{
                    $_SESSION['login'] = "Invalid. Please request for reset password";
                    header("location: ../login.php");
                    die();
                }
            }else{
                $_SESSION['password_err'] = "Fields are required";
                header("location: ../password_change.php?token=" . $password_token . "");
                die();
            }
        }else{
            $_SESSION['login'] = "Invalid. Please request for reset password";
            header("location: ../login.php");
            die();
        }
    }else{
        header('location: ../login.php');
        die();
    }



?>

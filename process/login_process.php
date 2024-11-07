<?php
    ob_start(); // Start output buffering
    require '../config/db_config.php';
    ob_end_clean(); // Clean (erase) the output buffer and turn off output buffering

    
    if(isset($_POST['submit'])){
        $username = filter_var($_POST['username'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Validate if there is an empty field
        if(!$username){
            $_SESSION['login'] = "Username is required";
        }elseif(!$password){
            $_SESSION['login'] = "Password is required";
        }else{
            // Fetch the user from the users database
            $fetch_user_query = "SELECT * FROM users WHERE username='$username'";
            $fetch_user_result = mysqli_query($connection, $fetch_user_query);

            if(mysqli_num_rows($fetch_user_result) == 1){
                // proceed to password verification
                $user_record = mysqli_fetch_assoc($fetch_user_result);
                $user_db_password = $user_record['password'];


                // Check if the email is verified
                if ($user_record['is_verify'] == 0) {
                    // Email not verified, set session to show resend button
                    $_SESSION['login'] = "Your email is not verified. Please check your inbox or resend the verification email.";
                    $_SESSION['login-data'] = $_POST;
                    header('location: ../login.php');
                    die();
                }
                
                // Compare the user password input and db password
                if(password_verify($password, $user_db_password)){
                    // Set the id
                    $_SESSION['user_id'] = $user_record['id'];
                    
                    // var_dump($_SESSION['user_id']);
                    // redirect the user to profile section
                    header('location: ../profile.php');
                }else{
                    $_SESSION['login'] = "Username or Password is incorrect";
                }
            }else{
                $_SESSION['login'] = "User not found";
            }
        }
        // If credentials is incorrect
        if(isset($_SESSION['login'])){
            $_SESSION['login-data'] = $_POST;
            header('location: ../login.php');
            die();
        }
    }else{
        //echo"hello";
        // If you user did not click the submit button
        header('location: ../login.php');
        die();
        
    }


?>

<?php
    require '../config/db_config.php';

    if(isset($_GET['token'])){
        $token = htmlspecialchars($_GET['token']);

        if (preg_match('/^[a-f0-9]{64}$/', $token)) {
            $verify_token_query = "SELECT verify_token, is_verify FROM users WHERE verify_token = '$token'";
            $verify_token_result = mysqli_query($connection, $verify_token_query);

            if(mysqli_num_rows($verify_token_result) > 0){
                $row = mysqli_fetch_array($verify_token_result);

                if($row['is_verify'] == 0 ){
                    $is_click = $row['verify_token'];
                    $update_email_query = "UPDATE users SET is_verify='1' WHERE verify_token='$is_click' LIMIT 1";
                    $update_email_result = mysqli_query($connection,$update_email_query);

                    if($update_email_result){
                        $_SESSION['register-successful'] = "Your account has been verified!";
                        header('location: ../login.php');
                        die();
                    }else{
                        $_SESSION['register'] = "Account verification failed";
                        header('location: ../login.php');
                        die();
                    }
                }else{
                    $_SESSION['register'] = "Your account is already verified!";
                    header('location: ../login.php');
                    die();
                }
            }

        } else {
            $_SESSION['register'] = "Invalid Verficication Link";
            header('location: ../login.php');
            die();
        }


    }else{
        header('location: ../login.php');
        die();
    }

?>
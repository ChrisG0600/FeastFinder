<?php
    require '../config/db_config.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require '../vendor/autoload.php';



    function send_password_link($fullname, $email, $password_token){
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication

            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->Username   = 'feastfinder.team@gmail.com';                     //SMTP username
            $mail->Password   = 'hnhuyzwtfhwknaoo';                               //SMTP password

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //ENCRYPTION_SMTPS | Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; default465 | use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('feastfinder.team@gmail.com', 'IT Department');                                          // from the form on website
            $mail->addAddress($email, $fullname);                 // Where to give the email

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Password Reset Request';

            $mail->Body = '
            <div style="font-family: Arial, sans-serif; color: #333;">
                <h2 style="color: #0000FF;">Password Reset Request</h2>
                <p style="font-weight: bold;">Dear ' . htmlspecialchars($fullname) . ',</p>
                <p>
                    We received a request to reset your password for your <b>FEASTFINDER</b> account. Please click the link below to reset your password. <br> 
                    The link will expire in <b> 1 hour </b>.
                </p>
                <p>
                    <a href="http://localhost:3000/password_change.php?token=' . $password_token . '" style="color: #0000FF; text-decoration: underline;">Reset Password</a>
                </p>
                <p>If you did not request a password reset, please ignore this email or contact support if you have questions.</p>
                <p>Best regards,<br>Your <b>FEASTFINDER</b> Team</p>
                <hr style="border: 0; border-top: 1px solid #ddd;">
                <p style="font-size: 12px; color: #777;">This is an automated message, please do not reply.</p>
            </div>
            ';
            $mail->Body;
            $mail->send();
        }catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }



    if(isset($_POST['submit'])){

        if(!empty(trim($_POST['email']))){

            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $email = mysqli_escape_string($connection, $email);
            $password_token = hash('sha256', rand());
            
            
            $current_time = date("Y-m-d H:i:s");
            $expires_at = (new DateTime())->modify('+1 hour')->format('Y-m-d H:i:s');

            $user_password_query = "SELECT * FROM users WHERE email='$email' LIMIT 1 ";
            $user_password_result = mysqli_query($connection, $user_password_query);

            if(mysqli_num_rows($user_password_result) > 0){

                $row = mysqli_fetch_assoc($user_password_result);
                $fullname = $row['fullname'];
                $email = $row['email'];

                $update_password_query = "UPDATE users SET password_token='$password_token', expires_at='$expires_at' WHERE email ='$email' LIMIT 1 ";
                $update_password_result = mysqli_query($connection, $update_password_query);

                if($update_password_result){
                    send_password_link($fullname, $email, $password_token);
                    $_SESSION['login-success'] = "Password reset link has been send to your Email."; 
                    header('location: ../login.php');
                    die();
                }

                
            }else{
                $_SESSION['login'] = "Email is not registered. Please register an account."; 
                header('location: ../register.php');
                die();
            }
        }else{
            $_SESSION['login'] = "Enter your registered email address"; 
            header('location: ../password_reset.php');
            die();
        }
    }else{
        header('location: ../login.php');
        die();        
    }



?>
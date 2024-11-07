<?php
    require '../config/db_config.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require '../vendor/autoload.php';


    function resend_email_verification($fullname, $email, $verify_token){
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication

            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->Username   = 'feastfinder.team@gmail.com';                    //SMTP username
            $mail->Password   = 'hnhuyzwtfhwknaoo';                               //SMTP password

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //ENCRYPTION_SMTPS | Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; default465 | use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('feastfinder.team@gmail.com', 'IT Department');                                          // from the form on website
            $mail->addAddress($email, $fullname);                 // Where to give the email

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'RE: Email Verification from FEASTFINDER';

            $mail->Body = '
            <div style="font-family: Arial, sans-serif; color: #333;">
                <h2 style="color: #0000FF;">Email Verification</h2>
                <p style="font-weight: bold;">Dear ' . htmlspecialchars($fullname) . ',</p>
                <p>Thank you for registering with <b> FEASTFINDER </b>. Please verify your email address by clicking the link below:</p>
                <p>
                    <a href="http://localhost:3000/process/verify_email.php?token=' . $verify_token .'" style="color: #0000FF; text-decoration: underline;">Verify Email</a>
                </p>
                <p>If you did not register for an account, please ignore this email.</p>
                <p>Best regards,<br>Your <b> FEASTFINDER </b> Team</p>
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
            
            $user_verification_query = "SELECT * FROM users WHERE email='$email' LIMIT 1 ";
            $user_verification_result = mysqli_query($connection, $user_verification_query);

            if(mysqli_num_rows($user_verification_result) > 0){
                $row = mysqli_fetch_assoc($user_verification_result);
                
                if($row['is_verify'] == 0){
                    $fullname = $row['fullname'];
                    $email = $row['email'];
                    $verify_token = $row['verify_token'];
                    resend_email_verification($fullname, $email, $verify_token);

                    $_SESSION['login-success'] = "Verification link has been send to your Email."; 
                    header('location: ../login.php');
                    die();
                }else{
                    $_SESSION['login'] = "Email is already verified."; 
                    header('location: ../login.php');
                    die();
                }
            }else{
                $_SESSION['register'] = "Email is not registered. Please register an account."; 
                header('location: ../register.php');
                die();
            }
        }else{
            $_SESSION['login'] = "Enter your registered email address"; 
            header('location: ../resend_verification.php');
            die();
        }
    }else{
        header('location: ../login.php');
        die();        
    }

    
?>
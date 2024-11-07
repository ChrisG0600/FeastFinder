<?php
    require '../config/db_config.php';
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';

if(isset($_POST['submit'])){
    // Get value from the form fields
    $fullname = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT);
    $subject = filter_var($_POST['subject'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);



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
        $mail->setFrom($email,'Client Concern');                                          // from the form on website
        $mail->addAddress('feastfinder.team@gmail.com', 'IT Department');                 // Where to give the email

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Important Notification';

        $mail->Body    = '
            <h2>Contact Form Submission</h2>
            <p>Dear Team,</p>
            <p>You have received a new message from the contact form on your website. Below are the details:</p>
            <table style="border-collapse: collapse; width: 100%;">
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;"><strong>Full Name:</strong></td>
                    <td style="border: 1px solid #ddd; padding: 8px;">'. htmlspecialchars($fullname) .'</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;"><strong>Email:</strong></td>
                    <td style="border: 1px solid #ddd; padding: 8px;">'. htmlspecialchars($email) .'</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;"><strong>Phone Number:</strong></td>
                    <td style="border: 1px solid #ddd; padding: 8px;">'. htmlspecialchars($phone) .'</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;"><strong>Subject:</strong></td>
                    <td style="border: 1px solid #ddd; padding: 8px;">'. htmlspecialchars($subject) .'</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;"><strong>Message:</strong></td>
                    <td style="border: 1px solid #ddd; padding: 8px;">'. nl2br(htmlspecialchars($message)) .'</td>
                </tr>
            </table>
            <p>Best regards,<br><b>'. $fullname . '</b></p>
        ';
        
        if($mail->send()){
            $_SESSION['status-success'] = "Your message has been send";
            header('location: ../contact.php');
            die();
        }else{
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            $_SESSION['status'] = "Message could not be sent";
            header('location: ../contact.php');
            die();
        }
        
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        $_SESSION['status'] = "Message could not be sent";
        header('location: ../contact.php');
        die();
    }
}else{
    header('location: ../index.php');
    die();
}

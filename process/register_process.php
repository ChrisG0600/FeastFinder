<?php
    ob_start(); // Start output buffering
    require '../config/db_config.php';
    ob_end_clean(); // Clean (erase) the output buffer and turn off output buffering

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require '../vendor/autoload.php';

    function send_email_verify($fullname, $email, $verify_token){
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
            $mail->Subject = 'Email Verification from FEASTFINDER';

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
        $fullname = filter_var($_POST['fullname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $Cpassword = filter_var($_POST['Cpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $avatar = $_FILES['avatar'];
        $verify_token = hash('sha256', rand());

        // Validation of Input data from form
        if(!$fullname){
            $_SESSION['register'] = "Please enter your fullname";
        }elseif(!$username){
            $_SESSION['register'] = "Please enter you username";
        }elseif(!$email){
            $_SESSION['register'] = "Please enter a Valid Email";
        }elseif(!isset($_POST['gender'])){
            $_SESSION['register'] = "Please select your gender";
        }else {
            $gender = filter_var($_POST['gender'], FILTER_SANITIZE_SPECIAL_CHARS);
            $allowed_genders = ['male', 'female', 'other'];

            if (!in_array($gender, $allowed_genders)) {
                $_SESSION['register'] = "Invalid gender selection";
            } elseif (strlen($password) < 8 || strlen($Cpassword) < 8) {
                $_SESSION['register'] = "Password should be more than 8 characters";
            } elseif (!$avatar['name']) {
                $_SESSION['register'] = "Please add an avatar";
            } else {
                // var_dump($avatar);
                // Validate if passwords match
                if ($password !== $Cpassword) {
                    $_SESSION['register'] = "Passwords do not match";
                } else {
                    // If passwords match, hash the password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Validate  email and username if already taken
                    $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
                    $user_check_result = mysqli_query($connection, $user_check_query);

                    if(mysqli_num_rows($user_check_result) > 0){
                        $_SESSION['register'] = "Username or Email is already taken";
                    }else{
                        // Make the avatar name to be unique using time
                        $time = time();
                        $avatar_name = $time . $avatar['name'];
                        $avatar_tmp_name = $avatar['tmp_name'];
                        $avatar_file_path = '../images/Avatar/' . $avatar_name;

                        // Validate allowed files
                        $allowed_files = ['png', 'jpeg', 'jpg'];
                        $extension = explode('.', $avatar_name);
                        $extension = end($extension);

                        if(in_array($extension, $allowed_files)){
                            // Check if file is in allowed size before saving
                            // should be less 2mb or 2000000
                            if($avatar['size'] < 2000000){
                                move_uploaded_file($avatar_tmp_name, $avatar_file_path);
                            }else {
                                $_SESSION['register'] = "File is to big";
                            }
                        }else{
                            $_SESSION['register'] = "File should be png, jpeg, jpg";
                        }

                    }
                }
            }
        }
        
        // Redirect to register if there is a problem
        if(isset($_SESSION['register'])){
            // var_dump($_SESSION['register']);
            $_SESSION['register-data'] = $_POST;
            header('Location: ../register.php');
            die();
        }else{
            
            // no error presenet proceed to saving in DB
            $insert_user_query = "INSERT INTO users (fullname, username, email,gender, password, avatar, verify_token) VALUES('$fullname', '$username', '$email','$gender', '$hashed_password','$avatar_name', '$verify_token')";
            $insert_user_result = mysqli_query($connection, $insert_user_query);
            
            if ($insert_user_result) {
                send_email_verify("$fullname", "$email", "$verify_token");
                $_SESSION['register-successful'] = "Registration successful. Please verify your Email Address";
                header('Location: ../login.php');
                die();
            } else {
                // Output the error message
                echo "Error: " . mysqli_error($connection);
            }
        }
    }else{
        header('Location: ../register.php');
        die();
    }
?>

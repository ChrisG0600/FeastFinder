<?php
    require '../config/db_config.php';
    
    if(isset($_POST['submit'])){
        
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $fullname = filter_var($_POST['fullname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $caption = filter_var($_POST['caption'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $old_avatar = filter_var($_POST['old_avatar'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $avatar = $_FILES['avatar'];

        if(!$fullname || !$caption){
            $_SESSION['edit_user'] = "Invalid inputs, could not update.";
        }else{
            // Delete current avatar
            if($avatar['name']){
                $old_avatar_path = '../images/Avatar/' . $old_avatar;
                if($old_avatar_path){
                    unlink($old_avatar_path);
                }

                // Proceed to get a new avatar
                $time = time();
                $new_avatar_name = $time . $avatar['name'];
                $new_avatar_tmp_name = $avatar['tmp_name'];
                $new_avatar_path = '../images/Avatar/' . $new_avatar_name;

                // Check for allowed files
                $allowed_files = ['png', 'jpg', 'jpeg'];
                $extension = explode('.',$new_avatar_name);
                $extension = end($extension);
                if(in_array($extension,$allowed_files)){
                    // Check the file size
                    if($avatar['size'] < 2000000){
                        move_uploaded_file($new_avatar_tmp_name,$new_avatar_path);
                    }else{
                        $_SESSION['edit_user'] = "File is too big";
                    }
                }else{
                    $_SESSION['edit_user'] = "File should be png, jpg, and jpeg only";
                }
            } 
        }

        if(isset($_SESSION['edit_user'])){
            // Redirect if invalid input occurs
            header('location: ../profile.php');
            die();
        }else{
            // Set the new avatar name if there is a new avatar, else keep the old one if none
            $new_avatar_insert = isset($new_avatar_name) ? $new_avatar_name : $old_avatar;

            // Update the DB
            $edit_user_query = "UPDATE users SET fullname='$fullname', caption='$caption', avatar='$new_avatar_insert' WHERE id=$id LIMIT 1";
            $edit_user_result = mysqli_query($connection, $edit_user_query);
        }

        // Session success
        // Check if query was success
        if($edit_user_result){
            $_SESSION['edit_user-success'] = "Profile information has been edited";
        }
    }
    header('location: ../profile.php');
    die();
?>
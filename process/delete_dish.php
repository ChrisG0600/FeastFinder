<?php
    require '../config/db_config.php';

    if(isset($_GET['id'])){
        $id = filter_var($_GET['id'],FILTER_SANITIZE_NUMBER_INT);

        // Get query
        $query = "SELECT * FROM postdish WHERE id = '$id'";
        $result = mysqli_query($connection, $query);

        if(mysqli_num_rows($result) == 1){
            $dish = mysqli_fetch_assoc($result);
            $image_name = $dish['image_path'];
            $video_name = $dish['video_path'];

            // Get Path
            $image_path = '../images/dishImage/' . $image_name;
            $video_path = '../foodVideo/' . $video_name;

            // Check if the image file exists and is not a directory
            if (!empty($image_name) && file_exists($image_path) && !is_dir($image_path)) {
                unlink($image_path);
            } else {
                echo "Image file does not exist, is a directory, or path is empty.<br>";
            }

            // Check if the video file exists and is not a directory
            if (!empty($video_name) && file_exists($video_path) && !is_dir($video_path)) {
                unlink($video_path);
            } else {
                echo "Video file does not exist, is a directory, or path is empty.<br>";
            }

            // Delete DB
            $delete_query = "DELETE FROM postdish WHERE id = $id LIMIT 1";
            $delete_result = mysqli_query($connection, $delete_query);
            
            if(!mysqli_errno($connection)){
                $_SESSION['delete_dish-success'] = " Post Dish has been deleted successfully";

            }
        }
    }
    header('Location: ../profile.php');
    die();



?>
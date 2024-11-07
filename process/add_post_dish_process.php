<?php
    require '../config/db_config.php';

    if(isset($_POST['submit'])){
        //var_dump($_POST);
        $user_id = $_SESSION['user_id'];
        $dish_name = filter_var($_POST['dish_name'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cuisine_type = filter_var($_POST['cuisine_type'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dish_ingredients = $_POST['dish_ingredients'];
        $dish_directions = filter_var($_POST['dish_directions'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dish_prep_time = filter_var($_POST['dish_prep_time'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dish_image = $_FILES['dish_image'];
        $dish_video = $_FILES['dish_video'];

        
        $has_ingredients = json_encode($dish_ingredients);

        // var_dump($hasIngredients);
        // Validate the inputs
        if(!$dish_name){
            $_SESSION['post_dish'] = "Dish Name is required";
        }elseif(!$cuisine_type){
            $_SESSION['post_dish'] = "Select a cuisine type";
        }elseif(!$has_ingredients){
            $_SESSION['post_dish'] = "Ingredients are required";
        }elseif(!$dish_directions){
            $_SESSION['post_dish'] = "Instruction for the dish is required";
        }elseif(!$dish_prep_time){
            $_SESSION['post_dish'] = "Add a time on how long will it make";
        }else{            
            // Debugging: Sanitize the dish name and escape special characters
            $sanitized_dish_name = mysqli_real_escape_string($connection, strtolower($dish_name));

            // Debugging: Print the sanitized dish name
            // echo "Sanitized Dish Name: " . $sanitized_dish_name . "<br>";

            // Check for a matching dish name in the database (case-insensitive)
            $dish_check_query = "SELECT * FROM postdish WHERE LOWER(dish_name) = '$sanitized_dish_name'";
            // echo "Dish Check Query: " . $dish_check_query . "<br>";  // Debugging: Print the query

            $dish_check_result = mysqli_query($connection, $dish_check_query);

            if ($dish_check_result && mysqli_num_rows($dish_check_result) > 0) {
                $_SESSION['post_dish'] = "Dish Name already exist";
            }

            // // Convert the ingredients array to a comma-separated string
            // $dish_ingredients_str = implode(',', $dish_ingredients);

            // Handle the Dish Image
            $time = time();
            $dish_image_name = $time . $dish_image['name'];
            $dish_image_tmp_name = $dish_image['tmp_name'];
            $dish_image_path = '../images/dishImage/' . $dish_image_name;

            // Check allowed files
            $image_allowedFiles = ['png', 'jpg', 'jpeg'];
            $image_extension = explode('.', $dish_image_name);
            $image_extension = end($image_extension);

            // Check for Image size
            if(in_array($image_extension, $image_allowedFiles)){
                if($dish_image['size'] < 2000000){
                    move_uploaded_file($dish_image_tmp_name, $dish_image_path);
                }else{
                    $_SESSION['post_dish'] = "File is to big";
                }
            }else{
                $_SESSION['post_dish'] = "File should be png, jpg, and jpeg only";
            }

            // Handle the Dish Video
            if(!empty($dish_video['name'])){
                $dish_video_name = $time . $dish_video['name'];
                $dish_video_tmp_name = $dish_video['tmp_name'];
                $dish_video_path = '../foodVideo/' . $dish_video_name;

                // Check allowed files
                $video_allowedFiles = ['mp4', 'avi', 'mov'];
                $video_extension = explode('.',$dish_video_name);
                $video_extension = end($video_extension);

                // Check for Video size
                if(in_array($video_extension, $video_allowedFiles)){
                    // 50MB file size
                    if($dish_video['size'] < 50000000){
                        move_uploaded_file($dish_video_tmp_name, $dish_video_path);
                    }else{
                        $_SESSION['post_dish'] = "File is to big ";
                    }
                }else{
                    $_SESSION['post_dish'] = "File should be mp4, avi, and mov";
                }
            }else {
                // If no video is uploaded, set dish_video_name to NULL for database
                $dish_video_name = null;
            }
        }

        // Check if there is error before proceeding to Database
        if(isset($_SESSION['post_dish'])){
            $_SESSION['post_dish-data'] = $_POST;
            header('location: ../profile.php');
            die();
        }else{
            // Save to Database
            $insert_dish_query = "INSERT INTO postdish (user_id, dish_name, cuisine_type, ingredients, directions, prep_time, video_path, image_path) VALUES ('$user_id', '$dish_name', '$cuisine_type', '$has_ingredients', '$dish_directions', '$dish_prep_time', " . ($dish_video_name ? "'$dish_video_name'" : "NULL") . ", '$dish_image_name')";
            echo "SQL Query: " . $insert_dish_query;
            $insert_dish_result = mysqli_query($connection, $insert_dish_query);

            // Handle the error 
            if($insert_dish_result){
                $_SESSION['post_dish-success'] = "Dish has been added";
                header('location: ../profile.php');
                die();
            }else{
                echo "Error Ocurred: ". mysqli_error($connection);
            }
        }

    }else{
        header('location: ../profile.php');
        die();
    }
?>
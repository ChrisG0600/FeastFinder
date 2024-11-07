<?php
    require '../config/db_config.php';
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $dish_id = filter_var($_POST['dish_id'], FILTER_SANITIZE_NUMBER_INT);
        $dish_name = filter_var($_POST['edit_dish_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cuisine_type = filter_var($_POST['edit_cuisine_type'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $ingredients = $_POST['edit_dish_ingredients'];
        $dish_directions = filter_var($_POST['edit_dish_directions'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dish_prep_time = filter_var($_POST['edit_dish_prep_time'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dish_video = $_FILES['edit_dish_video'];
        $dish_image = $_FILES['edit_dish_image'];

        // Sanitize Ingredients
        $sanitized_ingredients = array_map(function ($ingredient) {
            return filter_var($ingredient, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }, $ingredients);
        $dish_ingredients = json_encode($sanitized_ingredients);

        // Validate input
        if (!$dish_name) {
            $_SESSION['edit_dish'] = "Dish Name is required";
        } elseif (!$cuisine_type) {
            $_SESSION['edit_dish'] = "Select a cuisine type";
        } elseif (!$dish_ingredients) {
            $_SESSION['edit_dish'] = "Ingredients are required";
        } elseif (!$dish_directions) {
            $_SESSION['edit_dish'] = "Instruction for the dish is required";
        } elseif (!$dish_prep_time) {
            $_SESSION['edit_dish'] = "Add a time on how long will it take";
        } else {
            
            // Check for duplicate dish name
            $dish_check_query = "SELECT * FROM postdish WHERE LOWER(dish_name) = LOWER('$dish_name') AND id != '$dish_id'";
            $dish_check_result = mysqli_query($connection, $dish_check_query);

            if ($dish_check_result && mysqli_num_rows($dish_check_result) > 0) {
                $_SESSION['edit_dish'] = "Dish Name already exists";
                die();
                // Stop script execution
            }

            // Handle the Dish Image
            $dish_image_name = '';
            if (!empty($dish_image['name'])) {
                $time = time();
                $dish_image_name = $time . $dish_image['name'];
                $dish_image_tmp_name =  $dish_image['tmp_name'];
                $dish_image_path = '../images/dishImage/' . $dish_image_name;

                $image_allowedFiles = ['png', 'jpg', 'jpeg'];
                $image_extension = strtolower(pathinfo($dish_image_name, PATHINFO_EXTENSION));

                if (in_array($image_extension, $image_allowedFiles)) {
                    if ($dish_image['size'] < 2000000) {
                        move_uploaded_file($dish_image_tmp_name, $dish_image_path);
                    } else {
                        $_SESSION['edit_dish'] = "File is too big";
                    }
                } else {
                    $_SESSION['edit_dish'] = "File should be png, jpg, or jpeg only";
                }
            }

            // Handle Video
            $dish_video_name = '';
            if (!empty($dish_video['name'])) {
                $time = time();
                $dish_video_name = $time . $dish_video['name'];
                $dish_video_tmp_name = $dish_video['tmp_name'];
                $dish_video_path = '../foodVideo/' . $dish_video_name;

                $video_allowedFiles = ['mp4', 'avi', 'mov'];
                $video_extension = strtolower(pathinfo($dish_video_name, PATHINFO_EXTENSION));

                if (in_array($video_extension, $video_allowedFiles)) {
                    if ($dish_video['size'] < 50000000) {
                        move_uploaded_file($dish_video_tmp_name, $dish_video_path);
                    } else {
                        $_SESSION['edit_dish'] = "File is too big";
                    }
                } else {
                    $_SESSION['edit_dish'] = "File should be mp4, avi, or mov";
                }
            }

            // Prepare to update the dish
            $i_dish_name = mysqli_real_escape_string($connection, $dish_name);
            $i_cuisine_type = mysqli_real_escape_string($connection, $cuisine_type);
            $i_dish_directions = mysqli_real_escape_string($connection, $dish_directions);
            $i_dish_prep_time = mysqli_real_escape_string($connection, $dish_prep_time);

            // Check error before saving to Database

            if(isset($_SESSION['edit_dish'])){
                $_SESSION['edit_dish-data'] = $_POST;
                
            }else{
                // Build the update query
                $update_dish_query = "UPDATE postdish SET 
                    dish_name='$i_dish_name', 
                    cuisine_type='$i_cuisine_type', 
                    ingredients='$dish_ingredients', 
                    directions='$i_dish_directions', 
                    prep_time='$i_dish_prep_time'";

                if (!empty($dish_image_name)) {
                    $update_dish_query .= ", image_path='$dish_image_name'";
                }

                if (!empty($dish_video_name)) {
                    $update_dish_query .= ", video_path='$dish_video_name'";
                }

                $update_dish_query .= " WHERE id='$dish_id'";

                //var_dump($update_dish_query);
                
                // Execute the update query
                $update_dish_result = mysqli_query($connection, $update_dish_query);

                if ($update_dish_result) {
                    $_SESSION['edit_dish-success'] = "Dish has been edited";
                } else {
                    
                    echo "Error: " . mysqli_error($connection);
                    error_log(mysqli_error($connection), 3, 'errors.log');
                }                
            }

        }
    }else{
        header('location: ../profile.php');
        die();
    }
?>


<?php
    ob_start(); // Start output buffering
    include '../config/db_connect.php';
    ob_end_clean(); // Clean (erase) the output buffer and turn off output buffering
    include '../config/db_config.php';
    

    // check if there is table user existed
    $post_dish_query = "SHOW TABLES LIKE 'postDish' ";
    $post_dish_result = mysqli_query($connection, $post_dish_query);

    if(mysqli_num_rows($post_dish_result) > 0 ){
        echo "<br> database 'postDish' is already exist";
    }else{
        $query = "CREATE TABLE postDish(
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) UNSIGNED,
        dish_name VARCHAR(255) NOT NULL,
        cuisine_type VARCHAR(50) NOT NULL,
        ingredients TEXT NOT NULL,
        directions TEXT NOT NULL,
        prep_time VARCHAR(50)NOT NULL,
        video_path VARCHAR(255),
        image_path VARCHAR(255)NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE)";
        
        $result = mysqli_query($connection,$query);

        // Check if table is successfully created
        if(!$result){
            echo "Error creating table " . mysqli_error($connection);
        }else {
            echo "<br>Table postDish created successfully";
        }
    }
    mysqli_close($connection);
?>
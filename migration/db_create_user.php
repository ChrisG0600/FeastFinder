<?php
    session_start();
    ob_start(); // Start output buffering
    include '../config/db_connect.php';
    ob_end_clean(); // Clean (erase) the output buffer and turn off output buffering
    include '../config/db_config.php';
    

    // check if there is table user existed
    $user_query = "SHOW TABLES LIKE 'users' ";
    $user_result = mysqli_query($connection, $user_query);

    if(mysqli_num_rows($user_result) > 0 ){
        echo "<br> database 'users' is already exist";
    }else{
        $query = "CREATE TABLE users(
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        fullname VARCHAR(50) NOT NULL,
        username VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL,
        gender ENUM('Male', 'Female', 'Other') NOT NULL,
        password VARCHAR(255) NOT NULL,
        caption VARCHAR(255) DEFAULT NULL,
        avatar VARCHAR(255) NOT NULL,
        verify_token VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";
        $result = mysqli_query($connection,$query);

        // Check if table is successfully created
        if(!$result){
            echo "Error creating table " . mysqli_error($connection);
        }else {
            echo "<br>Table users created successfully";
        }
    }
    mysqli_close($connection);
?>
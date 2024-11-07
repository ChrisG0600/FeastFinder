<?php
    $servername = "localhost";
    $username = "echopost";
    $password = "admin12345";
    
    // Create connection
    $connection = mysqli_connect($servername, $username, $password);
    
    // Check connection
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
    echo "Connected successfully";
    
    // Check if there is database
    $db_check_query = "SHOW DATABASES LIKE 'foodlover'";
    $db_check_result = mysqli_query($connection, $db_check_query);


    if (mysqli_num_rows($db_check_result) > 0) {
        // If result return > 0 db exists
        echo " <br> Database 'foodlover' already exists.<br>";
    } else {
        // Create the database
        $query = "CREATE DATABASE foodlover";
        $result = mysqli_query($connection, $query);

        if ($result) {
            echo "Database created successfully<br>";
        } else {
            echo "Error creating database: " . mysqli_error($connection) . "<br>";
        }
    }

    // Close the connection
    mysqli_close($connection);
?>


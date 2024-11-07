<?php

    session_start();

    $servername = "localhost";
    $username = "echopost";
    $password = "admin12345";
    $dbname = "foodlover";

    date_default_timezone_set("Asia/Manila");
    // Create connection
    $connection = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }else{
        // echo "connected to the server";
    }
?>
<?php
    include './config/db_config.php';

    // // Get the session user-id to be able to get avatar of current user
    if(isset($_SESSION['user_id'])){
        // Get ID
        $id = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);
        $user_id_query = "SELECT avatar FROM users WHERE id='$id' ";
        $user_id_result = mysqli_query($connection, $user_id_query);
        $user_avatar = mysqli_fetch_assoc($user_id_result);
        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../image/Screenshot 2024-09-12 000121.png" type="image/png">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>FEASTFINDER</title>
</head>

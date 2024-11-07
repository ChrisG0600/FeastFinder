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
    <link rel="icon" href="../images/Screenshot 2024-09-12 000121.png" type="image/png">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>FEASTFINDER</title>
</head>
<body>
    <nav class="navbar shadow sticky-top navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand ps-lg-5" href="../index.php"><img src="../images/Screenshot 2024-09-12 000121.png" alt="Logo" width="200" height="80"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav mb-2 mb-lg-0 fs-5 fw-semibold pe-5 text-sm-end">
                    <li class="nav-item">
                        <a class="nav-link text-primary mx-2 my-2" href="/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary mx-2 my-2" href="/contact.php">Contact Us</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link text-primary mx-2 my-2" href="/cuisine.php">Recipes</a>
                        </li>
                        <li class="nav-item">
                            <a href="../profile.php" class="nav-link mx-2 my-2">
                                <img src="<?= '../images/Avatar/' . $user_avatar['avatar']?>" class="profile_avatar" alt="">
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link text-primary mx-2 my-2" href="/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-primary mx-2 my-2" href="/register.php">Sign Up</a>
                        </li>
                    <?php endif?>
                </ul>
            </div>
        </div>
    </nav>
    
<!-- Navbar End-->
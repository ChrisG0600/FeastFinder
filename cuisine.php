<?php
    include 'partials/header.php';
    include './process/auth_check.php';
    // Fetch current user_id
    $user_ID = $_SESSION['user_id'];
    
    $dish_query = "SELECT * FROM postdish WHERE user_id = '$user_ID'";
    $dish_result = mysqli_query($connection, $dish_query);
?>
<header id="cuisine_type" class="pointer bg-primary cuisine_page cuisine d-flex align-items-center justify-content-center">
    <div class="container text-center text-white">
        <h2 class="head_title">Dish Recipes</h2>
    </div>
</header>
<!-- Content -->

<?php if(mysqli_num_rows($dish_result) > 0): ?>
    <div class="wrapper">
        <div class="container mt-5">
            <div class="row">
                <?php while($dishes = mysqli_fetch_assoc($dish_result)): ?>
                    <?php
                        $user_id = $dishes['user_id'];
                        $user_query = "SELECT * FROM users WHERE id = '$user_id'";
                        $user_result = mysqli_query($connection, $user_query);
                        $user = mysqli_fetch_assoc($user_result);
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card recipe-card shadow-lg">
                            <img src="<?= './images/dishImage/' . htmlspecialchars($dishes['image_path']) ?>" class="card-img-top img-fluid rounded" alt="Dish Image">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= htmlspecialchars($dishes['dish_name']) ?></h5>
                                <p class="card-text">By: <?= htmlspecialchars($user['fullname']) ?></p>
                                <a href="./single-dish.php?id=<?= $dishes['id'] ?>&dish_name=<?= urlencode($dishes['dish_name']) ?>" class="btn btn-outline-primary">View Dish</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile ?>        
            </div>
        </div>        
    </div>

<?php else: ?>
    <div class="wrapper">
        <div class="container no_post">
            <h5 class="text-center">You do not have any posts. Go to <a class="text-decoration-underline text-dark" href="./profile.php">Profile</a> to add a post.</h5>
        </div>        
    </div>

    
<?php endif ?>

<?php
    include 'partials/footer.php';
?>

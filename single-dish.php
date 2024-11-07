<?php
    include 'partials/header.php';
    include './process/auth_check.php';
    
    // Get the session user-id to be able to get avatar of current user
    if(isset($_SESSION['user_id'])){
        // Get ID
        $id = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);
        $user_id_query = "SELECT * FROM users WHERE id='$id' ";
        $user_id_result = mysqli_query($connection, $user_id_query);
        $users = mysqli_fetch_assoc($user_id_result);
        
    }
    // Check if the user has a post
    if (!isset($id)) {
        header('Location: ./profile.php'); // Redirect if no session or invalid user ID
        exit();
    }
    
    // Check if the user has a valid post
    $userID = $id;
    $dish_name = urldecode($_GET['dish_name']);
    $post_dish_query = "SELECT * FROM postdish WHERE user_id='$userID' and dish_name ='$dish_name'";
    $post_dish_result = mysqli_query($connection, $post_dish_query);
    
    // If no post is found, prevent access
    if (mysqli_num_rows($post_dish_result) === 0) {
        header('Location: ./profile.php'); // Redirect to a different page if no post exists
        exit();
    }
    
    // Fetch the post data
    $postDish = mysqli_fetch_assoc($post_dish_result);
?>

    <!-- Content -->
    <div class="container p-0 bg-body-tertiary my-5 shadow-lg">
        <!-- Image and Ingredients Container -->
        <div class="row">
            <!-- Post Image -->
            <div class="col-lg-6 ">
                <img src="<?= './images/dishImage/' . $postDish['image_path']?>" class="post_images" alt="ImagePost">
            </div>
            <!-- Contents -->
            <div class="col-lg-6 px-3">
                <div class="mb-2">
                    <h3 class="text-start text-primary-emphasis post_food-title"><?= $postDish['dish_name']?></h3>
                    <span class="badge bg-success single_dish-badge"><?= $postDish['prep_time']?></span>
                    <span class="badge bg-success single_dish-badge"><?= $postDish['cuisine_type']?></span>
                </div>
                <div class="mb-2 single_dish-ingredients">
                    <?php
                        $assoc_array = json_decode($postDish['ingredients'], true);
                        $stringIngredients = array_values($assoc_array);
                    ?>
                    <ul>
                        <?php foreach ($stringIngredients as $ingredient): ?>
                            <li><?= htmlspecialchars($ingredient) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Instruction and Video(optional) Container -->
    <div class="container bg-body-tertiary shadow-lg mb-5">
        <div class="px-3 py-3">
            <h3 class="text-start text-primary-emphasis post_food-title">Directions</h3>
            <span class="badge bg-success single_dish-badge">Instructions</span>
            <span class="badge bg-success single_dish-badge"><?= isset($postDish['video_path']) && !empty($postDish['video_path']) ? 'Video' : 'Image' ?></span>
            <p>
                <?= $postDish['directions']?>
            </p>
                <?php if(isset($postDish['video_path']) && !empty($postDish['video_path'])) :?>
                    <div class="embed-responsive embed-responsive-16by9">
                        <video class="embed-responsive-item w-100" controls>
                            <source src="./foodVideo/<?= $postDish['video_path'] ?>" type="video/mp4">
                        </video>
                    </div>
                <?php else : ?>
                    <div class="col-lg-6 ">
                        <img src="<?= './images/dishImage/' . $postDish['image_path']?>" class="post_images" alt="ImagePost">
                    </div>
                <?php endif ?>
                <div class="row">
                    <!-- Avatar Image -->
                    <div class="col-lg-2 col-4 mt-3 px-3 avatar_container">
                        <img src="<?= './images/Avatar/' . $users['avatar']?>" class=" avatar" alt="Avatar">
                    </div>
                    <!-- Avatar Information -->
                    <div class="col-lg-10 col-8 mt-3 px-3">
                        <h5 class="text-start"><?= $users['fullname']?></h5>
                        <p><?= isset($users['caption']) && !empty($users['caption']) ? $users['caption'] : 'No caption' ?></p>
                    </div>
                </div>
                <div class="col-lg-1 mt-3 heart-container">
                    <i class="fa-regular fa-heart"><p class="text-dark ms-2">2</p></i>
                </div>
        </div>
    </div>


<?php
    include 'partials/footer.php';
?>
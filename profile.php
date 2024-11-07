<?php
    include 'partials/header.php';
    include './process/auth_check.php';
    // Get the session user-id to be able to get avatar of current user
    $edit_dish_query = "SELECT * FROM postdish";
    $edit_dish_result = mysqli_query($connection,$edit_dish_query);
    $dishName = mysqli_fetch_assoc($edit_dish_result);
    if(isset($_SESSION['user_id'])){
        // Get ID
        $id = filter_var($_SESSION['user_id'], FILTER_SANITIZE_NUMBER_INT);
        $user_id_query = "SELECT avatar FROM users WHERE id='$id' ";
        $user_id_result = mysqli_query($connection, $user_id_query);
        $user_avatar = mysqli_fetch_assoc($user_id_result);
        
    }
?>
<!-- Message -->

<!-- If not Successfull on Edit profile-->
<?php if(isset($_SESSION['edit_user'])) : ?>
    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
        <?= $_SESSION['edit_user']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['edit_user']); ?>

<!-- If Successfull on editing profile-->
<?php elseif(isset($_SESSION['edit_user-success'])) : ?>
    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
        <?= $_SESSION['edit_user-success']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['edit_user-success']); ?>

<!-- If Successfull on Adding Dish-->
<?php elseif(isset($_SESSION['post_dish-success'])) : ?>
    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
        <?= $_SESSION['post_dish-success']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['post_dish-success']); ?>

<!-- If not Successfull on Adding Dish-->
<?php elseif(isset($_SESSION['post_dish'])) : ?>
    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
        <?= $_SESSION['post_dish']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['post_dish']); ?>

<!-- If not Successfull on Edit dish -->
<?php elseif(isset($_SESSION['edit_dish'])) : ?>
    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
        <?= $_SESSION['edit_dish']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['edit_dish']); ?>

<!-- If Successfull on editing dish -->
<?php elseif(isset($_SESSION['edit_dish-success'])) : ?>
    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
        <?= $_SESSION['edit_dish-success']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['edit_dish-success']); ?>

<!-- If Successfull on deleting dish -->
<?php elseif(isset($_SESSION['delete_dish-success'])) : ?>
    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
        <?= $_SESSION['delete_dish-success']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['delete_dish-success']); ?>
<?php endif; ?>



<!-- Contents -->
<div class="container-fluid px-5 my-5 bg-body-tertiary">
    <div class="row">
        <!-- Profile Section -->
        <div class="col-lg-3 col-md-4 col-12 profile-bg shadow-lg h-100 d-flex flex-column align-items-center mb-3 mb-md-0">
            <div class="top-border text-center mb-3 w-100">
                <?php if(isset($_SESSION['user_id'])) : ?>
                    <img src="<?= './images/Avatar/' . $user_avatar['avatar']?>" alt="Avatar" class="mt-2 rounded img-fluid">
                <?php endif?>
                <div class="text-start px-1 mt-2">
                    <?php
                        $user_info = $_SESSION['user_id'];
                        $user_info_query = "SELECT * FROM users WHERE id='$user_info'";
                        $user_info_result = mysqli_query($connection, $user_info_query);
                        $users = mysqli_fetch_assoc($user_info_result);
                    ?>
                        <p><?= $users['username']?> </p>
                        <p><?= isset($users['caption']) && !empty($users['caption']) ? $users['caption'] : 'No caption' ?></p>
                        <div class="container pt-2 profile-status">
                            <?php
                                $count_post_query = "SELECT COUNT(*) AS total_posts FROM postdish WHERE user_id ='$user_info'";
                                $count_post_result = mysqli_query($connection, $count_post_query);
                                $count = mysqli_fetch_assoc($count_post_result);
                            ?>
                            <div class="d-flex justify-content-between">
                                <p> Number of Post</p>
                                <?php if($count['total_posts'] > 0)  :?>
                                    <p class="btn btn-primary btn-sm"><?= $count['total_posts'] ?></p>
                                <?php else: ?>
                                    <p>None</p>
                                <?php endif?>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p>Member Since</p>
                                <p class="text-end"><?= date("M d, Y", strtotime($users['created_at']))?></p>
                            </div>
                        </div>
                    
                </div>
                <a href="logout.php">
                    <button type="button" class="btn btn-primary mt-3">Log Out</button>
                </a>
            </div>
        </div>
        <!-- Main Section -->
        <div class="col-lg-9 col-md-8 col-12">
            <div class="edit-bg shadow-lg mb-4">
                <div class="row px-4">
                    <!-- Icon and Header Row -->
                    <div class="col-12 d-flex align-items-center text-primary-emphasis my-2">
                        <i class="fa-regular fa-user fs-4"></i>
                        <h5 class="fs-5 mb-0 ms-2">About</h5>
                    </div>
                    <!-- Content Rows -->
                    <div class=" mt-2 col-12 d-flex flex-wrap text-primary-emphasis">
                        <div class="col-md-6 col-6 fw-bold">
                            <p>Full Name</p>
                            <p>Username</p>
                            <p>Gender</p>
                            <p>Email</p>
                        </div>
                        <div class="col-md-6 col-6">
                            <?php
                                $user_info = $_SESSION['user_id'];
                                $user_info_query = "SELECT * FROM users WHERE id='$user_info'";
                                $user_info_result = mysqli_query($connection, $user_info_query);
                                $users = mysqli_fetch_assoc($user_info_result);
                            ?>
                            <p> <?= $users['fullname']?> </p>
                            <p> <?= $users['username']?> </p>
                            <p> <?= $users['gender']?> </p>
                            <p> <?= $users['email']?> </p>
                        </div>
                    </div>
                    <div class="text-center my-4">
                        <button class="btn btn-outline-primary col-12" data-bs-toggle="modal" data-bs-target="#updateProfile-Modal">Edit Profile</button>
                    </div>
                </div>
            </div>
            
            <!-- Posts Section -->
            <div class="col-12">
                <div class="edit-bg shadow-lg mb-5">
                    <div class="row px-4 table-responsive pb-5">
                        <!-- Icon, Header, and Button Row -->
                        <div class="col-12 col-lg-12 mt-3 mb-4 d-flex align-items-center justify-content-between text-primary-emphasis">
                            <div class="d-flex align-items-center">
                                <i class="fa-regular fa-file-lines fs-4 me-2"></i>
                                <h5 class="fs-5 mb-0">Post</h5>
                            </div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_post_dish">Add Post</button>
                        </div>
                        <!-- Content Post Here -->
                        <table class="table table-bordered align-items-center justify-content-center mb-0 bg-white col-6">
                            <thead>
                                <tr>
                                    <th class="text-start">Name</th>
                                    <th class="text-start">Food Recipe Title</th>
                                    <th class="text-start">Cuisine Type</th>
                                    <th class="text-start">Actions</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $user_id = $_SESSION['user_id'];
                                    $post_dish_query = "SELECT * FROM postdish WHERE user_id='$user_id'";
                                    $post_dish_result = mysqli_query($connection, $post_dish_query);
                                    
                                ?>
                                <?php if(mysqli_num_rows($post_dish_result) > 0) : ?>
                                    <?php while($postdish = mysqli_fetch_assoc($post_dish_result)) : ?>    
                                        <tr>
                                            <td class="text-start">
                                                <!-- Name -->
                                                <div class="">
                                                    <p><?= $users['fullname']?></p>
                                                </div>
                                            </td>
                                            <td class="text-start">
                                                <!-- Food Recipe Title -->
                                                <p class="mb-0"><?= $postdish['dish_name']?></p>
                                            </td>
                                            <td class="text-start">
                                                <!-- Cuisine Type -->
                                                <span class="badge ingredients_badge bg-secondary me-2 mb-2 d-inline-flex align-items-center"><?= $postdish['cuisine_type']?></span>
                                            </td>
                                            <td class="text-start">
                                                <!-- Actions -->
                                                <div class="btn-group gap-1" role="group">
                                                    <a href="./single-dish.php?=<?= $postdish['user_id'] ?>&dish_name=<?= urlencode($postdish['dish_name']) ?>" class="text-decoration-none ">
                                                        <button type="button" class="btn btn-sm btn-primary">View</button>
                                                    </a>
                                                    <button type="button"  class="btn btn-sm btn-success "  data-bs-toggle="modal"  data-user-id="<?= $postdish['user_id'] ?>" data-dish-name="<?= htmlspecialchars($postdish['dish_name']) ?>"  data-bs-target="#edit_post_dish">
                                                        Edit
                                                    </button>
                                                    <a href="./process/delete_dish.php?id=<?= $postdish['id']?>" class="btn btn-sm btn-danger  "> Delete</a>                                      
                                                </div>  
                                            </td>
                                        </tr>
                                    <?php endwhile ?>   
                                <?php else : ?> 
                                    <tr>
                                        <td colspan="4" class="text-center text-primary fs-5 ">No Post Found</td>
                                    </tr>
                                <?php endif ?>  
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
<!-- Content End -->

<!-- Edit Profile Modal -->
<div class="modal fade" id="updateProfile-Modal" tabindex="-1" aria-labelledby="updateProfile-Modal-lLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- This will be the form -->
            <form action="process/edit_user_process.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <?php
                            $user_info = $_SESSION['user_id'];
                            $user_info_query = "SELECT * FROM users WHERE id='$user_info'";
                            $user_info_result = mysqli_query($connection, $user_info_query);
                            $users = mysqli_fetch_assoc($user_info_result);
                        ?>
                        <!-- Profile Picture Column -->
                        <div class="col-lg-4 col-md-4 col-sm-12 d-flex justify-content-center align-items-start">
                            <div class="profile-pic-container">
                                <img id="profilePic" src="<?= './images/Avatar/' . $users['avatar']?>" alt="Profile Picture" class="img-fluid rounded-circle">
                                <button type="button" class="btn btn-outline-primary edit-pic-btn" title="Change Profile Picture" onclick="document.getElementById('fileInput').click();">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <input type="hidden" name="old_avatar" value="<?= $users['avatar']?>">
                                <input type="file" name="avatar" id="fileInput" style="display: none;" accept="image/*" onchange="previewImage(event)">
                            </div>
                        </div>

                        <!-- Form Column -->
                        <div class="col-lg-8 col-md-8 col-sm-12">
                            <div class="d-flex justify-content-between align-items-center text-primary mb-4">
                                <!-- Header on the left -->
                                <p class="fs-5 mb-0">Edit Profile</p>
                                <!-- Button on the right -->
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <input type="hidden" name="id" value="<?= $users['id']?>">
                            <!-- Full Name input -->
                            <div class="form-outline mb-3">
                                <label for="fullname" class="mb-3">Fullname:</label>
                                <input type="text" id="fullname" class="form-control" name="fullname" placeholder="Full Name" value="<?= $users['fullname']?>" />
                            </div>
            
                            <!-- Caption input -->
                            <div class="form-outline mb-3">
                                <label for="caption" class="mb-3">Caption:</label>
                                <textarea id="caption" name="caption" class="form-control" placeholder="Caption"><?= isset($users['caption']) && !empty($users['caption']) ? $users['caption'] : 'No caption' ?></textarea>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!-- Actions -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>  
        </div>
    </div>
</div>
<!-- Edit Profile Modal End -->

<!-- Add Post Modal -->
<div class="modal fade" id="add_post_dish" tabindex="-1" aria-labelledby="post_dishLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="post_dishLabel">Post New Dish</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="process/add_post_dish_process.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- File Input -->
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Upload New Dish</label>
                        <input class="form-control" type="file" name="dish_image" id="formFile">
                        <p class="text-start note_file-title">Picture of the final dish.</p>
                    </div>
                    <!-- Dish Name -->
                    <div class="mb-3">
                        <label for="dish_name" class="form-label">Dish Name</label>
                        <input type="text" class="form-control" name="dish_name" placeholder="Enter Dish Name" required>
                    </div>
                    <!-- Select Cuisine Type -->
                    <div class="mb-3">
                        <label for="cuisine_type" class="form-label">Cuisine Type</label>
                        <select class="form-select" aria-label="Cuisine Type" name="cuisine_type" required>
                            <option value="Asian" selected>Asian</option>
                            <option value="European">European</option>
                            <option value="Middle Eastern">Middle Eastern</option>
                            <option value="African">African</option>
                            <option value="North American">North American</option>
                            <option value="South American">South American</option>
                            <option value="Oceanian">Oceanian</option>
                        </select>                    
                    </div>

                    <!-- Ingredients -->
                    <div class="mb-3">
                        <label for="ingredientInput" class="form-label">Ingredients</label>
                        <div id="ingredientsContainer">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" name="dish_ingredients[]" placeholder="Add an ingredient" required>
                                <button class="btn btn-danger removeButton" type="button">Remove</button>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="button" id="addButton">Add Ingredient</button>
                    </div>

                    <!-- Directions  -->
                    <label for="floatingTextarea" class="form-label">Directions</label>
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Directions.." name="dish_directions" id="floatingTextarea" style="font-size:18px; color:black; height: 100px" required></textarea>
                    </div>
                    <!-- Prep Time -->
                    <div class="mb-3">
                        <label for="dish_name" class="form-label">Preparation Time</label>
                        <input type="text" class="form-control" name="dish_prep_time" placeholder="30 Minutes 1 Hour 2 Hours etc.." required>
                    </div>
                    <!-- Vide Upload -->
                    <div class="mb-3">
                        <label for="videoUpload" class="form-label">Upload Video (Optional)</label>
                        <input class="form-control" type="file" id="videoUpload" name="dish_video" accept="video/*">
                        <p class="text-start note_file-title">Video of how it will be process would be a great help.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="submit">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add Post Modal End-->

<!-- Edit Post Modal -->
<div class="modal fade" id="edit_post_dish" tabindex="-1" aria-labelledby="edit_post_dishLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="edit_post_dishLabel">Edit Dish</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDishForm" method="POST" enctype="multipart/form-data">
                <?php
                    $user_id = $_SESSION['user_id'];
                    $post_query = "SELECT * FROM postdish WHERE user_id='$user_id'";
                    $post_result = mysqli_query($connection, $post_query);
                    $post_dish = mysqli_fetch_assoc($post_result)
                ?>
                <div class="modal-body">
                    <!-- Hidden Input to match the corresponding data on the Database -->
                    <input type="hidden" name="dish_id" id="edit_id" value="<?= $post_dish['id']?>">
                    <!-- <input type="hidden" name="dish_id" id="edit_user_id"> -->
                    <!-- File Input -->
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Upload New Dish</label>
                        <input class="form-control" type="file" name="edit_dish_image" id="edit_dish_image">
                        <p class="text-start note_file-title">Picture of the final dish.</p>
                    </div>
                    <!-- Dish Name -->
                    <div class="mb-3">
                        <label for="dish_name" class="form-label">Dish Name</label>
                        <input type="text" class="form-control" value="" name="edit_dish_name" id="edit_dish_name" placeholder="Enter Dish Name">
                    </div>
                    <!-- Select Cuisine Type -->
                    <div class="mb-3">
                        <label for="cuisine_type" class="form-label">Cuisine Type</label>
                        <select class="form-select" aria-label="Cuisine Type" value="" id="edit_cuisine_type" name="edit_cuisine_type">
                            <option value="Asian" selected>Asian</option>
                            <option value="European">European</option>
                            <option value="Middle Eastern">Middle Eastern</option>
                            <option value="African">African</option>
                            <option value="North American">North American</option>
                            <option value="South American">South American</option>
                            <option value="Oceanian">Oceanian</option>
                        </select>                    
                    </div>

                    <!-- Ingredients -->
                    <div class="mb-3">
                        <label for="ingredientInput" class="form-label">Ingredients</label>
                        <div id="edit_ingredientsContainer">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" id="edit_dish_ingredients" name="edit_dish_ingredients[]" placeholder="Add an ingredient">
                                <button class="btn btn-danger removeButton" type="button">Remove</button>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="button" id="editButton">Add Ingredient</button>
                    </div>

                    <!-- Directions  -->
                    <label for="floatingTextarea" class="form-label">Directions</label>
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Directions.." name="edit_dish_directions" id="edit_dish_directions" style="font-size:18px; color:black; height: 100px"></textarea>
                    </div>
                    <!-- Prep Time -->
                    <div class="mb-3">
                        <label for="dish_name" class="form-label">Preparation Time</label>
                        <input type="text" class="form-control" name="edit_dish_prep_time" id="edit_dish_prep_time" placeholder="30 Minutes 1 Hour 2 Hours etc..">
                    </div>
                    <!-- Vide Upload -->
                    <div class="mb-3">
                        <label for="videoUpload" class="form-label">Upload Video (Optional)</label>
                        <input class="form-control" type="file" id="edit_dish_video" name="edit_dish_video" accept="video/*">
                        <p class="text-start note_file-title">Video of how it will be process would be a great help.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="submit">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Post Modal End-->

<?php
    include 'partials/footer.php';
?>

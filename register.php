<?php
    include 'partials/header.php';
    //Get data back in order for the user on not entering again and again their details when they have an error on the registration
    $fullname = $_SESSION['register-data']['fullname'] ?? null;
    $username = $_SESSION['register-data']['username'] ?? null;
    $email = $_SESSION['register-data']['email'] ?? null;
    $gender = $_SESSION['register-data']['gender'] ?? null;

    // Delete data session after successful
    unset($_SESSION['register-data']);
?>
<?php if(isset($_SESSION['register'])) : ?>
    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
        <?= $_SESSION['register']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['register']); ?>
<?php endif; ?>
<!-- Form -->
<div class="container shadow-lg form-bg my-5 px-5 pt-5 col-lg-4 col-md-6 col-sm-8 col-12 fw-bold">
    <form action="process/register_process.php" method="POST" enctype="multipart/form-data">
        <div class="text-center text-primary">
            <p class="fs-2">Sign up</p>
        </div>
        <!-- Fullname input -->
        <div class="form-outline mb-2">
            <label class="form-label" for="fullname">Full name</label>
            <input type="text" name="fullname" value="<?= htmlspecialchars($fullname) ?>" class="form-control" placeholder="Full Name"  />
        </div>

        <!-- Username input -->
        <div class="form-outline mb-2">
            <label class="form-label" for="username">Username</label>
            <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" class="form-control" placeholder="Username"  />
        </div>

        <!-- Email input -->
        <div class="form-outline mb-2">
            <label class="form-label" for="email">Email address</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" class="form-control" placeholder="Email"  />
        </div>

        <!-- Gender -->
        <div class="form-check gender-selection mb-3">
            <label for="gender">Gender</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" value="male" id="male" <?= $gender === 'male' ? 'checked' : '' ?>>
                <label class="form-check-label" for="male">Male</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" value="female" id="female" <?= $gender === 'female' ? 'checked' : '' ?>>
                <label class="form-check-label" for="female">Female</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" value="other" id="other" <?= $gender === 'other' ? 'checked' : '' ?> checkeds>
                <label class="form-check-label" for="other">Other</label>
            </div>
        </div>

        <!-- Password input -->
        <div class="form-outline mb-2">
            <label class="form-label" for="password">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Password"  />
        </div>

        <!-- Confirm Password input -->
        <div class="form-outline mb-2">
            <label class="form-label" for="Cpassword">Confirm Password</label>
            <input type="password" name="Cpassword" class="form-control" placeholder="Confirm Password"  />
        </div>

        <!-- Avatar -->
        <div class="form-outline mb-2">
            <label for="formFile" class="form-label">Select Avatar</label>
            <input class="form-control" type="file" name="avatar" id="formFile">
        </div>
        <div class="text-center">
            <p>By signing up, you agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></p>
        </div>
        <!-- Submit button -->
        <button type="submit" name="submit" class="btn btn-primary btn-block mt-2 px-2 mb-5 text-center col-lg-12 col-md-12 col-sm-12 col-12 mx-auto">Create an Account</button>
    </form>
</div>
<div class="text-center mb-5">
    <p>Already have an account? <a href="/login.php" class="text-primary">Log in.</a></p>
</div>

<!-- Form end -->

<?php
    include 'partials/footer.php';
?>
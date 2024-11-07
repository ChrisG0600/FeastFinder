<?php
    include 'partials/header.php';

    $username = $_SESSION['login-data']['username'] ?? null;
    unset($_SESSION['login-data']);

?>
<!-- Success Message -->
<?php if(isset($_SESSION['register-successful'])) : ?>
    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
        <?= $_SESSION['register-successful']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['register-successful']); ?>
<!-- Error Message on login -->
<?php elseif(isset($_SESSION['login'])) : ?>
    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
        <?= $_SESSION['login']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['login']); ?>
<?php elseif(isset($_SESSION['login-success'])) : ?>
    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
        <?= $_SESSION['login-success']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['login-success']); ?>
<?php endif; ?>

<!-- Form -->
    <div class="container shadow-lg form-bg my-5 px-5 py-5 col-lg-4 col-md-6 col-sm-8 col-12 fw-bold">
        <form class="" action="process/login_process.php" method="POST">
            <div class="text-center text-primary">
                <p class="fs-2">Log In</p>
            </div>

            <!-- Username input -->
            <div class="form-outline mb-2">
                <label class="form-label" for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= $username ?>" class="form-control" placeholder="Username" />
            </div>

            <!-- Password input -->
            <div class="form-outline mb-2">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" />
            </div>
            <!-- Forgot Password -->
            <div class="my-2">
                <a href="/password_reset.php" class="reset-link">Reset Password?</a>  
            </div>

            <!-- Submit button -->
            <button type="submit" name="submit" class="btn btn-primary btn-block mt-2 px-2 text-center col-lg-12 col-md-12 col-sm-12 col-12 mx-auto">Log In</button>

            <div class="divider">
                <span>OR</span>
            </div>
            <a href="./resend_verification.php">
                <button type="button" class="btn btn-outline-primary btn-block mt-2 px-2 text-center col-lg-12 col-md-12 col-sm-12 col-12 mx-auto">Verify your Account here</button>
            </a>

        </form>
    </div>
    <div class="text-center mb-5">
        <p>Need an account? <a href="./register.php" class="text-primary">Sign up.</a> Here.</p>
    </div>
<!-- Form end -->

<?php
    include 'partials/footer.php';
?>
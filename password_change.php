<?php
    include './partials/private/header.php';

    //Check if token is present in the URL
    if (!isset($_GET['token']) || empty($_GET['token'])) {
        // Redirect the user if no token is provided
        $_SESSION['login'] = "Inavlid link";
        header('Location: ./login.php');
        die();
    }

    $token = $_GET['token'];

    $query = "SELECT * FROM users WHERE password_token = '$token' AND expires_at >= NOW()";
    $result = mysqli_query($connection, $query);

    // Check if the token is valid and not expired
    if (mysqli_num_rows($result) === 0) {
        // Token is invalid or expired, redirect to login page
        $_SESSION['login'] = "Expired Link or Invalid Token";
        header('Location: ./login.php');
        die();
    }
?>
<?php if(isset($_SESSION['password_err'])) : ?>
    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
        <?= $_SESSION['password_err']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['password_err']); ?>
<?php endif; ?>

<div class="wrapper">
    <div class="container-fluid shadow-lg my-5 p-5 col-lg-6">
        <h2>Change Password</h2>
        <hr style="border: 1px solid black;">
        <form action="./process/change_password-process.php" method="post">
            <div class="row">
                <div class="col-lg-12">
                    <input type="hidden" name="password_token" value="<?php echo $_GET['token'] ?? ''; ?>">
                    <div class="form-outline mb-2">
                        <input type="password" name="password" class="form-control border-primary" placeholder="Password" required />
                    </div>
                    <div class="form-outline mb-4">
                        <input type="password" name="Cpassword" class="form-control border-primary" placeholder="Confirm Password" required />                    </div>
                    </div>
                <div class="col-lg-6">
                    <button type="submit" name="submit" class="btn btn-success btn-md">Update Password</button>
                </div>
            </div>
        </form>
    </div>
</div>




<?php
    include './partials/private/footer.php';
?>
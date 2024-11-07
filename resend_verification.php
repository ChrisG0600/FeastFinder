<?php
    include 'partials/header.php';
    // Check if the user is verified


?>
<div class="wrapper">
    <div class="container-fluid shadow-lg my-5 p-5 col-lg-6">
        <h2>Resend Email Verification</h2>
        <hr style="border: 1px solid black;">
        <form action="./process/re_verify-process.php" method="post">
            <div class="row">
                
                <div class="col-lg-12">
                    <div class="form-outline mb-4">
                        <input type="email" name="email" class="form-control border-primary" placeholder="Email" />
                    </div>
                </div>
                <div class="col-lg-6">
                    <button type="submit" name="submit" class="btn btn-primary btn-md">Resend Email</button>
                </div>
            </div>
        </form>
    </div>
</div>



<?php
    include 'partials/footer.php';

?>
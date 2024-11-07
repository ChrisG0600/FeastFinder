<?php
    include 'partials/header.php';
?>
<!-- Contact Form -->
<?php if(isset($_SESSION['status-success'])) : ?>
    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
        <?= $_SESSION['status-success']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['status-success']); ?>
    
<?php elseif(isset($_SESSION['status'])) : ?>
    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
        <?= $_SESSION['status']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['status']); ?>

<?php endif ?>
<div class="container pointer contact-form shadow-lg">
    <div class="contact-image">
        <img src="https://image.ibb.co/kUagtU/rocket_contact.png" alt="rocket_contact"/>
    </div>
    <form action="./process/contact_process.php" method="POST" enctype="multipart/form-data">
        <h3>Drop Us a Message</h3>
        <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <input type="text" name="name" class="form-control" placeholder="Your Name *" value="" required />
                    </div>
                    <div class="form-group mb-4">
                        <input type="email" name="email" class="form-control" placeholder="Your Email *" value="" required />
                    </div>
                    <div class="form-group mb-4">
                        <input type="text" name="phone" class="form-control" placeholder="Your Phone Number *" value="" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <input type="text" name="subject" class="form-control" placeholder="Your Subject *" value="" required />
                    </div>
                    <div class="form-group">
                        <textarea name="message" class="form-control" placeholder="Your Message *" style="width: 100%; height: 200px;" required></textarea>
                    </div>
                </div>
                <div class="mx-auto form-group col-md-6 mt-4">
                    <button type="submit" name="submit" class="btnContact" required >Send Message</button>
                </div>
        </div>
    </form>
</div>
<!-- Contact Form End-->

<!-- For swaljs -->

<?php
    include 'partials/footer.php';
?>
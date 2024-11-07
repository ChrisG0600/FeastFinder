<?php
    require './config/db_config.php';

    // Destroy all session to logout the user
    session_destroy();
    header('Location: ../');
    die();

?>
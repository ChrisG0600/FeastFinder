<?php
    require '../config/db_config.php';

    $user_id = $_GET['user_id'];
    $dish_name = $_GET['dish_name'];

    $fetch_modal_query = "SELECT * FROM postdish WHERE user_id='$user_id' AND dish_name = '$dish_name'";
    $fetch_modal_result = mysqli_query($connection, $fetch_modal_query);

    if($row = mysqli_fetch_assoc($fetch_modal_result)){
        $row['ingredients'] = json_decode($row['ingredients']);
        // If dish found
        $response = array(
            'id' => $row['id'],
            'user_id' => $row['user_id'],
            'dish_name' => $row['dish_name'],
            'cuisine_type' => $row['cuisine_type'],
            'ingredients' => $row['ingredients'],
            'directions' => $row['directions'],
            'prep_time' => $row['prep_time'],
            'video_path' => $row['video_path'],
            'image_path' => $row['image_path'],
        );

        echo json_encode($response);
    }else{
        echo json_encode(array('error'=> 'Dish not found'));
        header('location: ../profile.php');
        die();
    }

?>
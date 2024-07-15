<?php
require_once '../Includes/dbConnection.php';
require_once '../Includes/generalFunc.php';


// handle post request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $user_id = get_user_id();
    $image_link = $_POST['image_link'];
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $biography = $_POST['biography'];
    $birthday = $_POST['birthday'];
    $state = $_POST['state'];
    $phone = $_POST['phone'];
    $twitter = $_POST['twitter'];
    $facebook = $_POST['facebook'];
    $linkedin = $_POST['linkedin'];
    $instagram = $_POST['instagram'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // check if the user is trying to change the password
    if ($current_password != '' && $new_password != '' && $confirm_password != '') {
        $sql = "SELECT password FROM user WHERE userid = $user_id";
        $result = execSql($sql);
        $row = mysqli_fetch_assoc($result);
        if ($current_password == $row['password']) {
            if ($new_password == $confirm_password) {
                $new_password = $new_password;
                $sql = "UPDATE user SET password = '$new_password' WHERE userid = $user_id";
                $result = execSql($sql);
                if (!$result) {
                    echo json_encode(array('status' => 'error', 'message' => 'Failed to update password'));
                    exit();
                }
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Passwords do not match'));
                exit();
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Incorrect password'));
            exit();
        }
    }

    $sql = "UPDATE user SET image = '$image_link', username = '$username', name = '$name', email = '$email', biography = '$biography', birthday = '$birthday', state = '$state', phone = '$phone', twitter = '$twitter', facebook = '$facebook', linkedin = '$linkedin', instagram = '$instagram' WHERE userid = $user_id";
    $result = execSql($sql);
    if ($result) {
        echo json_encode(array('status' => 'success', 'message' => 'Profile updated successfully'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Failed to update profile'));
    }
}

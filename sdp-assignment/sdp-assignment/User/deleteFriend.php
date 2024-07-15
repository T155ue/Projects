<?php
require_once '../Includes/dbConnection.php';
require_once '../Includes/generalFunc.php';

// accept post request and delete friend from database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $friend_id = $_POST['friend_id'];

    $query = "DELETE FROM user_friend 
    WHERE (userid1 = $user_id AND userid2 = $friend_id)
   OR (userid1 = $friend_id AND userid2 = $user_id);";
    $result = execSql($query);
    if ($result) {
        echo json_encode(array('status' => 'success'));
    } else {
        echo json_encode(array('status' => 'error'));
    }
}

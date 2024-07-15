<?php
require_once '../../Includes/dbConnection.php';
require_once '../../Includes/generalFunc.php';
// get post request with user id
$userId = $_POST['userid'];
// fake a success response with user id
// update the database by increase the stage integer +1
$sql = "UPDATE user SET learn_stage = learn_stage + 1 WHERE userid = $userId";
$result = execSql($sql);
if ($result === false) {
    echo json_encode(array('error' => 'Error updating stage'));
    return;
}

echo json_encode(array('success' => 'update stage for user ' . $userId));

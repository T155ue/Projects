<?php
// a POST request with userid,html css topic id will send here add it into submission database
require_once '../../Includes/dbConnection.php';
require_once '../../Includes/generalFunc.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = getConn();
    $userid = $_POST['userid'];
    // escape html
    $html = $conn->real_escape_string($_POST['html']);
    $css = $conn->real_escape_string($_POST['css']);
    // replace html " into ' 
    $html = str_replace('"', "'", $html);
    $css = str_replace('"', "'", $css);
    $topicid = $_POST['topicid'];
    $description = $_POST['description'];
    $sql = "INSERT INTO drawing_submission (topicID,authorID,description,html,css) VALUES ('$topicid','$userid','$description','$html','$css')";
    // generate another $sql prevent sql injection
    $result = $conn->query($sql);
    $conn->close();
    if (!$result) {
        echo json_encode(array('error' => '404'));
        return;
    }
    echo json_encode(array('success' => 'submission addedd successfully'));
} else {
    echo json_encode(array('error' => '404'));
}

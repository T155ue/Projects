<?php

require_once '../../Includes/dbConnection.php';
require_once '../../Includes/generalFunc.php';

// handle post request with submission of component id, user id and description
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // query if the user already reported the component
    $component_id = $_POST['submissionID'];
    $user_id = $_POST['userID'];
    $description = $_POST['description'];
    // get topic id from submission id
    $sql = "SELECT topicID FROM drawing_submission WHERE submissionID = '$component_id'";
    $result = execSql($sql);
    $row = $result->fetch_assoc();
    $topic_id = $row['topicID'];

    $sql = "SELECT * FROM report WHERE submissionID = '$component_id' AND userID = '$user_id'";
    $result = execSql($sql);
    if ($result->num_rows > 0) {
        header("Location: ../../gameMode/drawingChallenge/topicDetail.php?topicID=$topic_id&report=alreadyReported");
        return;
    } else {
        $sql = "INSERT INTO report (submissionID, userID, description) VALUES ('$component_id', '$user_id', '$description')";
        $result = execSql($sql);
        if ($result) {
            header("Location: ../../gameMode/drawingChallenge/topicDetail.php?topicID=$topic_id&report=success");
        } else {
            header("Location: ../../gameMode/drawingChallenge/topicDetail.php?topicID=$topic_id&report=failed");
        }
    }
}

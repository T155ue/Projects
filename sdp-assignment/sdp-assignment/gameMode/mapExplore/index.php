<?php

require_once '../../Includes/dbConnection.php';

$sql = '
    SELECT 
    c.ComponentID, 
    c.component_css, 
    c.component_html,
    c.component_name, 
    c.component_type, 
    c.PlayerID, 
    u.userid, 
    u.username,
    u.mainComponentID,
    u.`image` 
    FROM component c 
    JOIN user u ON c.PlayerID = u.userid
    JOIN component c2 ON u.mainComponentID = c2.componentID
    ';

$result = execSql($sql);

$userData = array();

while ($row = mysqli_fetch_assoc($result)) {
    $row['component_html'] = str_replace("<", "&lt;", $row['component_html']);
    $row['component_html'] = str_replace(">", "&gt;", $row['component_html']);
    $userData[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="index.css" />
    <script src="index.js" defer></script>
    <title>Document</title>
</head>

<body>
    <script>
        var data = <?php echo json_encode($userData); ?>;
    </script>

    <div class="topSection">
        <button class="back" back>Back</button>
        <h1>CSSTopia Map Exploration</h1>
    </div>
    <div class="mainSection">
        <div class="antiOverflow">
            <iframe id="mapIframe" src="innerMap.php"></iframe>
        </div>
        <div class="sideSection">
            <div class="infoSection" imageSection></div>
            <div class="infoSection" usernameSection>Designer: </div>
            <div class="infoSection">
                <button class="userButton" userButton>User Profile</button>
            </div>
            <div class="infoSection" componentNameSection>Component Name: </div>
            <div class="infoSection">
                <button class="leanrMoreButton" learnMoreButton>Component Builds</button>
            </div>
            <div class="dragMapHint show">
                Hold down your mouse and drag around the map to explore other people's build!<br><br>
                If you found one that piques your interest, Double Click it to learn more!
            </div>
        </div>
    </div>
    <div class="learnMorePopUp">
        <div id="leftSidePopUp">
            <div id="houseLeftSide"></div>
        </div>
        <div id="rightSidePopUp">
            <div id="htmlRightSide"></div>
            <div id="cssRightSide"></div>
        </div>
        <button id="popUpBackButton">back</button>
    </div>
    <div class="bottomSection">
        <button class="zoomIn" zoomIn>+</button>
        <button class="refresh" refresh>Refresh Map</button>
        <button class="zoomOut" zoomOut>-</button>
    </div>
</body>

</html>
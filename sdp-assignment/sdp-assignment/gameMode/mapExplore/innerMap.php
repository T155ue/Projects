<?php

require_once '../../Includes/dbConnection.php';

//session_start();
//$username = $_SESSION['username'];

// fetch data from database
$query = '
    SELECT  
    c.ComponentID,
    c.component_css, 
    c.component_html,
    c.component_type,
    c.PlayerID,
    u.mainComponentID 
    FROM component c 
    JOIN user u ON c.ComponentID = u.mainComponentID
    ';

$result = execSql($query);

$componentData = array();

while ($row = mysqli_fetch_array($result)) {
  $row['component_html'] = str_replace("<", "&lt;",  $row['component_html']);
  $row['component_html'] = str_replace(">", "&gt;",  $row['component_html']);
  $componentData[] = $row;
}
//organize component
/*if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_array($result)) {
    $component['id'] = $row['ComponentID'];
    $component['player_id'] = $row['PlayerID'];
    $component['css'] = $row['component_css'];
    $component['html'] = $row['component_html'];
    $component['html'] = str_replace("<", "&lt;", $component['html']);
    $component['html'] = str_replace(">", "&gt;", $component['html']);

    $components[] = $component;
  }
} else {
  echo "lol";
}*/

//test print
//print_r($component['id']);
//print_r($component['css']);
//print_r($component['html']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="innerMap.css" />
  <script src="innerMap.js"></script>
  <title>Document</title>
</head>

<body>
  <script>
    var componentData = <?php echo json_encode($componentData); ?>;
    //console.log("data got")
  </script>
  <div class="mapContainer">
    <div id="moveArea">
    </div>
  </div>
</body>

</html>
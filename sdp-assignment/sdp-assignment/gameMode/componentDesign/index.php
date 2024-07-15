<?php
require_once '../../Includes/dbConnection.php';

session_start();
$username = $_SESSION['username'];

$useridquery = "SELECT c.PlayerID FROM component c JOIN user u ON c.PlayerID = u.userid WHERE u.username = '$username'";

$PlayerID = execSql($useridquery);
$playerData = $PlayerID->fetch_assoc();
$playeridreal = $_SESSION['id']; // Assuming 'player_id' is the column name

if (isset($_POST['submit'])) {
  $component_name = $_POST['component_name'];
  $component_css = $_POST['component_css'];
  $component_html = $_POST['component_html'];
  $component_type = isset($_POST['houseComponent']) ? 2 : 1; // Adjusted line
  $playeridsubmit = $_POST['playerid'];
  if (isset($_POST['compid']) && $_POST['compid'] != '') {
    $componentidsubmit = $_POST['compid'];
    $updateQuery = execSql("UPDATE `component` SET 
                `component_name` = '$component_name', 
                `component_css` = '$component_css', 
                `component_html` = '$component_html', 
                `component_type` = '$component_type', 
                `PlayerID` = '$playeridsubmit' 
                WHERE `ComponentID` = '$componentidsubmit'");
    if ($updateQuery) {
      header('Location: componentshowcase.php');
    }
  } else {
    $insertQuery = execSql("INSERT INTO `component` (`component_name`, `component_css`, `component_html`, `component_type`, `PlayerID`) VALUES ('$component_name', '$component_css', '$component_html', '$component_type', '$playeridsubmit')");






    if ($insertQuery) {
      header('Location: componentshowcase.php');
      exit;
    }
  }
}

if (isset($_POST['uid']) && isset($_POST['cid'])) {
  $uid = $_POST['uid'];
  $cid = $_POST['cid'];
  if ($uid !== $playeridreal) {
    echo '<script>alert("You are not authorized to edit the component. Redirecting...");</script>';
    echo '<script>window.location.href = "componentshowcase.php";</script>';
    exit;
  } else {
    $componentQuery = "SELECT * FROM component WHERE ComponentID = '$cid' AND PlayerID = '$playeridreal'";
    $result = execSql($componentQuery);
    $component = $result->fetch_assoc();

    if ($component) {
      // Assign component details to variables
      $component_name = $component['component_name'];
      $component_css = $component['component_css'];
      $component_html = $component['component_html'];
      $component_type = $component['component_type'];
      $componentid = $component['ComponentID'];
    } else {
      // Handle if component is not found or unauthorized access
      echo '<script>alert("Component not found or unauthorized access.");</script>';
      echo '<script>window.location.href = "componentshowcase.php";</script>';
      exit;
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Live HTML and CSS Editor</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <header class="bg-blue-500 text-white p-3 text-center font-bold relative mb-3">
    <div class="flex justify-between items-center"> <!-- Flex container to align header content -->
      <span class="inline-block">Component Design Page</span> <!-- Header title -->
      <button id="backButton" onclick="window.location.href = 'componentshowcase.php'" class="bg-white text-blue-500 rounded-md px-4 py-2 border border-blue-500 hover:bg-blue-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
        Back
      </button>

    </div>
  </header>
</head>

<body>

  <form id="myForm" method="post" action="index.php">



    <div class="flex flex-row">
      <div class="w-[90%] h-[90vh] flex flex-col">
        <textarea id="htmlInput" class="border-2 border-black h-[50%] rounded-lg" name="component_html" placeholder="html..."><?php echo isset($component_html) ? htmlspecialchars($component_html) : ''; ?></textarea>
        <textarea id="cssInput" class="border-2 border-black h-[50%] rounded-lg mt-2" name="component_css" placeholder="css...."><?php echo isset($component_css) ? htmlspecialchars($component_css) : ''; ?></textarea>
      </div>
      <iframe id="iframe" class="w-[90%] border border-gray-400 border-5 rounded-lg p-4 h-[90vh] ml-2 mt-1"></iframe>
    </div>
    <div class="border p-2 flex justify-between items-center">
      <div class="flex items-center w-5/6">
        <input type="text" id="nameInput" placeholder="enter the name of the component..." class="w-full border p-2 mr-2" name="component_name" value="<?php echo isset($component_name) ? htmlspecialchars($component_name) : ''; ?>">
        <label for="houseComponent" class="text-sm mr-2 px-1" name="component_type">House Component?</label>
        <div class="house-component">
          <input type="checkbox" id="component_type" class="mx-2" name="houseComponent" <?php echo isset($component_type) && $component_type == 2 ? 'checked' : ''; ?>>
          <input type="hidden" id="playeridinput" name="playerid" value="<?php echo $playeridreal; ?>">
          <input type="hidden" name="compid" value="<?php echo isset($componentid) ? htmlspecialchars($componentid) : ''; ?>">
        </div>
      </div>
      <button type="button" id="saveButton" class="h-30 w-1/6 bg-blue-500 text-white">Save your component</button>
    </div>

    <div id="confirmationScreen" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
      <div class="bg-white p-8 rounded shadow-lg flex flex-col overflow-hidden" style="width: 500px; height: 500px;">
        <h2 class="text-xl font-semibold mb-4">Confirmation</h2>
        <div id="confirmationData" class="mb-4 overflow-y-auto flex-grow"></div>
        <div class="mt-auto flex justify-end">
          <button id="confirmButton" class="bg-blue-500 text-white px-4 py-2 rounded mr-4 w-36" name="submit">Confirm</button>
          <button id="cancelButton" class="bg-gray-500 text-white px-4 py-2 rounded w-36">Cancel</button>
        </div>
      </div>
    </div>
  </form>
  <script src="index.js"></script>

</body>


</html>
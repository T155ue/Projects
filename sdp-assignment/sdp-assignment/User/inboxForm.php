<?php
require_once '../Includes/dbConnection.php';
require_once '../Includes/generalFunc.php';

// Check if user is logged in
if (!is_login()) {
  header("Location: ../index.php");
  exit();
}

$id = get_user_id();

// Handle sending a new inbox message
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['send_message'])) {
    $recipient_username = $_POST['username'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Get recipient user ID
    $sql_recipient = "SELECT userid FROM user WHERE username = '$recipient_username'";
    $result_recipient = execSql($sql_recipient);
    $recipient = mysqli_fetch_assoc($result_recipient);

    if ($recipient) {
      $userid2 = $recipient['userid'];
      $datetime = date('Y-m-d H:i:s');

      // Insert new message into inbox table
      $sql_insert = "INSERT INTO inbox (userid1, userid2, inbox_title, inbox_content, datetime) VALUES ($id, $userid2, '$title', '$content', '$datetime')";
      $result_insert = execSql($sql_insert);

      if ($result_insert) {
        $_SESSION['success_message'] = "Message sent successfully!";
        header("Location: inboxForm.php");
        exit();
      } else {
        echo "Error sending message: " . mysqli_error($conn);
      }
    } else {
      echo "Recipient not found.";
    }
  } elseif (isset($_POST['delete_message'])) {
    $inbox_id = $_POST['inbox_id'];

    // Delete message from inbox table
    $sql_delete = "DELETE FROM inbox WHERE InboxID = $inbox_id AND userid2 = $id";
    $result_delete = execSql($sql_delete);

    if ($result_delete) {
      $_SESSION['success_message'] = "Message deleted successfully!";
      header("Location: inboxForm.php");
      exit();
    } else {
      echo "Error deleting message: " . mysqli_error($conn);
    }
  }
}

// Fetch user data
$sql_user = "SELECT * FROM user WHERE userid = $id";
$result_user = execSql($sql_user);
$user = mysqli_fetch_assoc($result_user);
if ($user == null) {
  header("Location: ../index.php");
  exit();
}

// Fetch recent messages for the current user
$sql_messages = "SELECT * FROM inbox WHERE userid2 = $id ORDER BY InboxID DESC";
$result_messages = execSql($sql_messages);
if (!$result_messages) {
  echo "Error fetching messages: " . mysqli_error($conn);
  exit();
}

// Fetch recent warnings for the current user
$sql_warnings = "SELECT * FROM warninginbox WHERE userID = $id ORDER BY warningID DESC";
$result_warnings = execSql($sql_warnings);
if (!$result_warnings) {
  echo "Error fetching warnings: " . mysqli_error($conn);
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inbox</title>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="/sdp-assignment/User/inboxForm.css" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex flex-col min-h-screen items-center bg-gray-900 p-4 text-white">
  <div class="w-full overflow-hidden rounded-lg bg-gray-800 shadow-md flex-1">
    <header class="flex items-center justify-between bg-gray-800 p-4 text-white">
      <h1 class="text-2xl font-bold">CSSTOPIA</h1>
      <button class="rounded bg-blue-500 px-4 py-2 transition hover:bg-blue-600" onclick="location.href ='/sdp-assignment/index.php'">Back</button>
    </header>
    <div class="p-4 overflow-y-auto">
      <?php if (isset($_SESSION['success_message'])) : ?>
        <div class="mb-4 p-4 rounded bg-green-600 text-white">
          <?php
          echo $_SESSION['success_message'];
          unset($_SESSION['success_message']);
          ?>
        </div>
      <?php endif; ?>

      <!-- Warnings tab content -->
      <div id="warnings" class="tab-content">
        <div class="space-y-4">
          <!-- Display recent warnings -->
          <?php while ($warning = mysqli_fetch_assoc($result_warnings)) : ?>
            <div class="flex-row flex items-center justify-between rounded-lg bg-gray-700 p-4 shadow-md">
              <div class="flex items-center">
                <div class="ml-4">
                  <h2 class="text-xl font-semibold">Admin</h2>
                  <p class="text-gray-300"><?php echo $warning['warning_title']; ?></p>
                  <p class="text-red-500">Warning from Admin</p>
                </div>
              </div>
              <div class="flex items-center">
                <p class="text-gray-400"><?php echo $warning['datetime']; ?></p>
                <button class="ml-4 rounded bg-red-600 px-4 py-2 transition hover:bg-red-700" onclick="openPopup('warning_popup_<?php echo $warning['warningID']; ?>')">View Warning</button>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>

      <!-- Add spacing between sections -->
      <div class="section-gap"></div>

      <div class="space-y-4">
        <!-- Display recent messages -->
        <?php while ($message = mysqli_fetch_assoc($result_messages)) : ?>
          <?php
          $sender_id = $message['userid1'];
          $sql_sender = "SELECT * FROM user WHERE userid = $sender_id";
          $result_sender = execSql($sql_sender);
          $sender = mysqli_fetch_assoc($result_sender);
          ?>
          <div class="flex-row flex items-center justify-between rounded-lg bg-gray-700 p-4 shadow-md">
            <div class="flex items-center">
              <img class="h-16 w-16 rounded-full" src="<?php echo $sender['image']; ?>" alt="Profile picture of the user" />
              <div class="ml-4">
                <h2 class="text-xl font-semibold"><?php echo $sender['username']; ?></h2>
                <p class="text-gray-300"><?php echo $message['inbox_title']; ?></p>
              </div>
            </div>
            <div class="flex items-center">
              <p class="text-gray-400"><?php echo $message['datetime']; ?></p>
              <button class="ml-4 rounded bg-green-600 px-4 py-2 transition hover:bg-green-700" onclick="openPopup('popup_<?php echo $message['InboxID']; ?>')">View Inbox</button>
              <form method="post" class="ml-4">
                <input type="hidden" name="inbox_id" value="<?php echo $message['InboxID']; ?>">
                <button type="submit" name="delete_message" class="rounded bg-red-600 px-4 py-2 transition hover:bg-red-700">Delete</button>
              </form>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>

    <!-- Popup containers for viewing warnings -->
    <?php mysqli_data_seek($result_warnings, 0); ?>
    <?php while ($warning = mysqli_fetch_assoc($result_warnings)) : ?>
      <div class="popup" id="warning_popup_<?php echo $warning['warningID']; ?>">
        <button class="close-btn" onclick="closePopup('warning_popup_<?php echo $warning['warningID']; ?>')"><i class="bx bx-x"></i></button>
        <h2 class="text-2xl font-bold mb-6 text-center">Warning Message</h2>
        <form class="space-y-4">
          <div>
            <label for="viewUsername" class="block font-semibold mb-2">Admin Username</label>
            <input type="text" id="viewUsername" name="username" class="border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" readonly value="Admin">
          </div>
          <div>
            <label for="viewTitle" class="block font-semibold mb-2">Title</label>
            <input type="text" id="viewTitle" name="title" class="border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" readonly value="<?php echo $warning['warning_title']; ?>">
          </div>
          <div>
            <label for="viewContent" class="block font-semibold mb-2">Content</label>
            <textarea id="viewContent" name="content" rows="4" class="border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" readonly><?php echo $warning['warning_content']; ?></textarea>
          </div>
          <p class="text-xs text-gray-400">Warning ID: <?php echo $warning['warningID']; ?></p>
          <p class="text-xs text-gray-400">Date & Time: <?php echo $warning['datetime']; ?></p>
        </form>
      </div>
    <?php endwhile; ?>

    <!-- Popup containers for viewing inbox -->
    <?php mysqli_data_seek($result_messages, 0); ?>
    <?php while ($message = mysqli_fetch_assoc($result_messages)) : ?>
      <div class="popup" id="popup_<?php echo $message['InboxID']; ?>">
        <button class="close-btn" onclick="closePopup('popup_<?php echo $message['InboxID']; ?>')"><i class="bx bx-x"></i></button>
        <h2 class="text-2xl font-bold mb-6 text-center">Inbox Message</h2>
        <form class="space-y-4">
          <div>
            <label for="viewUsername" class="block font-semibold mb-2">Username</label>
            <input type="text" id="viewUsername" name="username" class="border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" readonly value="<?php echo $sender['username']; ?>">
          </div>
          <div>
            <label for="viewTitle" class="block font-semibold mb-2">Title</label>
            <input type="text" id="viewTitle" name="title" class="border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" readonly value="<?php echo $message['inbox_title']; ?>">
          </div>
          <div>
            <label for="viewContent" class="block font-semibold mb-2">Content</label>
            <textarea id="viewContent" name="content" rows="4" class="border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" readonly><?php echo $message['inbox_content']; ?></textarea>
          </div>
          <p class="text-xs text-gray-400">Inbox ID: <?php echo $message['InboxID']; ?></p>
          <p class="text-xs text-gray-400">Date & Time: <?php echo $message['datetime']; ?></p>
        </form>
      </div>
    <?php endwhile; ?>

    <!-- Popup container for sending new message -->
    <div class="popup" id="newMessagePopup">
      <button class="close-btn" onclick="closePopup('newMessagePopup')"><i class="bx bx-x"></i></button>
      <h2 class="text-2xl font-bold mb-6 text-center">Send Inbox Message</h2>
      <form class="space-y-4" id="newMessageForm" method="post" action="">
        <div>
          <label for="username" class="block font-semibold mb-2">Recipient Username</label>
          <input type="text" id="username" name="username" class="border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter username" required>
        </div>
        <div>
          <label for="title" class="block font-semibold mb-2">Title</label>
          <input type="text" id="title" name="title" class="border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter title" required>
        </div>
        <div>
          <label for="content" class="block font-semibold mb-2">Content</label>
          <textarea id="content" name="content" rows="4" class="border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter message content" required></textarea>
        </div>
        <div class="text-center">
          <button type="submit" name="send_message" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">Send</button>
        </div>
      </form>
    </div>

    <div class="footer-bar">
      <button class="rounded bg-yellow-500 px-4 py-2 transition hover:bg-yellow-600" onclick="openPopup('newMessagePopup')">Send New Inbox</button>
    </div>
  </div>

  <!-- JavaScript to handle popup toggle -->
  <script>
    function openPopup(popupId) {
      const popup = document.getElementById(popupId);
      popup.classList.add("active");
    }

    function closePopup(popupId) {
      const popup = document.getElementById(popupId);
      popup.classList.remove("active");
    }
  </script>
</body>

</html>
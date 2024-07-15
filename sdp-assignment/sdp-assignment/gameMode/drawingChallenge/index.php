<?php

require_once '../../Includes/dbConnection.php';

// get the search value if have
$search = isset($_GET['search']) ? $_GET['search'] : '';
if ($search) {
  // if something search
  $sql = "SELECT * FROM drawing_topic WHERE title LIKE '%$search%' OR description LIKE '%$search%'";
  $result = execSql($sql);
  $topicArr = mysqli_fetch_all($result, MYSQLI_ASSOC);
  if (count($topicArr) == 0) {
    // if no result found
    $topicArr = [];
  }
} else {
  // no search
  // get all drawing topics
  $sql = "SELECT * FROM drawing_topic";
  $result = execSql($sql);
  $topicArr = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Drawing Challenge</title>
</head>

<body class="bg-[url('../../Asset/bg.gif')] bg-cover backdrop-brightness-50 h-full min-h-screen pt-1 p-2 pb-10">
  <nav class="border-black border-2 p-2 rounded-md bg-white">
    <!-- make 3 items first is logo, and one is h1 writing learning and another is a back button -->
    <div class="flex flex-row justify-between">
      <div class="flex flex-row">
        <a href="/sdp-assignment/">
          <h1 class="text-4xl font-bold text-red-500">CSSTOPIA</h1>
        </a>

      </div>
      <h1 class="text-2xl">Drawing Challenge</h1>
      <a href="../../index.php">
        <div class="bg-blue-500 p-2 flex text-white hover:cursor-pointer hover:bg-blue-400 flex items-center justify-center">
          <h1>Back</h1>
        </div>
      </a>
  </nav>
  <!-- Search bar -->
  <div class="flex justify-center m-4">
    <input id="searchBox" value="<?php echo $search; ?>" type="text" class="border-2 border-black p-2 rounded-md" placeholder="Search for a topic" />
    <button onclick="searchClick()" class="bg-blue-500 p-2 text-white rounded-md ml-2">Search</button>
  </div>

  <!-- listings -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mx-8 " id=" topic-list-div">
    <!-- for each topic in $topicArr  -->
    <?php foreach ($topicArr as $topic) : ?>
      <a href="./topicDetail.php?topicID=<?php echo $topic['topicid'] ?>">
        <div class="bg-white rounded-lg shadow-md p-6 hover:translate-y-[-10px] ease-in-out transition-all">
          <h3 class="text-xl font-bold mb-2"><?php echo $topic['title'] ?></h3>
          <p class="text-gray-700 mb-4"><?php echo $topic['description'] ?></p>
          <div class="flex justify-between items-center">
            <?php echo $topic['status'] ? '<span class="text-green-500"> Open' : '<span class="text-red-500"> Ended' ?></span>
            <span class="text-gray-500"><?php echo $topic['expertise'] ?></span>
          </div>
        </div>
      </a>
    <?php
    endforeach
    ?>
  </div>
  <script src="./index.js"></script>
</body>

</html>
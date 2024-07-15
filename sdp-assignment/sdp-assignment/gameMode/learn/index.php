<?php
require_once '../../Includes/dbConnection.php';
require_once '../../Includes/generalFunc.php';

$is_logged = is_login();
$userid = null;
if (!$is_logged) {
  $stage = 1;
} else {
  $userid = get_user_id();
  $sql = "SELECT learn_stage FROM user WHERE userid = $userid";
  $result = execSql($sql);
  $row = mysqli_fetch_assoc($result);
  $stage = $row['learn_stage'];
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Parent HTML</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    .backbtnstyle {
      display: inline-block;
      padding: 10px 20px;
      margin-bottom: 0%;
      margin-left: 2%;
      background-color: #fff;
      color: #000;
      border: 2px solid rgba(255, 255, 255, 0.2);
      border-radius: 20px;
      text-decoration: none;
      font-size: 16px;
      font-weight: 600;
      text-align: center;
      transition: background-color 0.3s, color 0.3s;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(10px);
      user-select: none;
    }
  </style>


</head>

<body class="bg-[url('../../Asset/bg.gif')] bg-cover backdrop-brightness-50 h-screen pt-2">

  <nav class="border-black border-2 p-2 rounded-md  bg-white mx-2">
    <!-- make 3 items first is logo, and one is h1 writing learning and another is a back button -->
    <div class="flex flex-row justify-between">
      <div class="flex flex-row">
        <a href="/sdp-assignment/" class="flex justify-center items-center text-2xl text-red-500 font-bold">
          CSSTOPIA
        </a>

      </div>
      <h1 class="text-2xl flex justify-center items-center">Learning &nbsp<span id="stage-text">1</span></h1>

      <a class="bg-blue-500 px-3 rounded-full flex text-white hover:cursor-pointer hover:bg-blue-600 flex items-center justify-center" href="/sdp-assignment/">
        <h1>Back</h1>
      </a>
    </div>

  </nav>
  <div class="flex flex-row p-2">
    <div class="w-full h-[80vh] flex flex-col m-2">
      <h1 class="border border-black p-2 rounded-md m-1 bg-white">
        Question: <span id="question-text"></span>
      </h1>

      <label for="html-input" class="font-black text-white">HTML</label>
      <textarea id="html-input" class="border-2 border-black h-[30%] rounded-lg"></textarea>

      <label for="html-input" class="font-black text-white">CSS</label>
      <textarea id="style-input" class="border-2 border-black h-[30%] rounded-lg"></textarea>

      <a id="verify-button" class="mt-4">

        <div class="bg-blue-500 p-2 rounded-full flex text-white hover:cursor-pointer hover:bg-blue-600 flex items-center justify-center">
          <h1>Verify</h1>
        </div>
      </a>
    </div>

    <iframe id="iframe" class="w-full border border-gray-400 border-5 rounded-lg p-4 h-[80vh] ml-2 mt-1 bg-white"></iframe>
  </div>
  <!-- JavaScript libraries -->
  <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
  <script type="module">
    import LearnGameManager from "../../Classes/LearnGameManager.js";

    const learnGameManager = new LearnGameManager(<?php echo $stage ?>, <?php echo $userid ?>);

    learnGameManager.startGame();
    console.log(learnGameManager.gameStage);
    console.log(learnGameManager.gameStage - 1);
  </script>
</body>

</html>
<?php

require_once '../../Includes/dbConnection.php';
require_once '../../Includes/generalFunc.php';

if (!isset($_GET['topicID'])) {
    header("Location: /sdp-assignment");
}
$topicId = $_GET['topicID'];
$sql = "SELECT * FROM drawing_topic WHERE topicid = $topicId";
$result = execSql($sql);
$topicDetail = $result->fetch_assoc();

if (!is_login()) {
    header("Location: /sdp-assignment/Login&SignUp/login.php");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Parent HTML</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[url('../../Asset/bg.gif')] bg-cover backdrop-brightness-50 min-h-screen h-full pb-10 pt-2 p-2 ">
    <nav class="border-black border-2 p-2 rounded-md m-1 bg-white">
        <!-- make 3 items first is logo, and one is h1 writing learning and another is a back button -->
        <div class="flex flex-row justify-between">
            <div class="flex flex-row">
                <a href="/sdp-assignment/" class="text-3xl font-bold">
                    <h1 class="text-red-500">CSSTOPIA</h1>

                </a>

            </div>
            <h1 class="text-2xl"><?php echo $topicDetail['title'] ?></h1>
            <a href="/sdp-assignment/gamemode/drawingChallenge/index.php">
                <div class="bg-blue-500 p-2 flex text-white hover:cursor-pointer hover:bg-blue-400 flex items-center justify-center">
                    <h1>Back</h1>
                </div>
            </a>
    </nav>
    <div class="flex flex-row">
        <div class="w-full h-[80vh] flex flex-col text-white">


            HTML
            <textarea id="html-input" class="border-2 border-black h-[45%] rounded-lg text-black" placeholder="HTML goes here..."></textarea>

            CSS
            <textarea id="style-input" class="border-2 border-black h-[45%] rounded-lg text-black" placeholder="CSS goes here..."></textarea>
            <a id="verify-button">
                <div class="bg-blue-500 p-2 rounded-full flex text-white hover:cursor-pointer hover:bg-blue-600 flex items-center justify-center mt-2">
                    <h1>submit</h1>
                </div>
            </a>
        </div>

        <iframe id="iframe" class="bg-white w-full h-[80vh] border border-gray-400 border-5 rounded-lg p-4 ml-2 mt-1"></iframe>
    </div>
    <!-- JavaScript libraries -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>

    <script type="module">
        import GameManager from "../../Classes/GameManager.js";

        const gameManager = new GameManager();

        gameManager.init();
        // target verify button
        const verifyButton = document.getElementById("verify-button");
        verifyButton.addEventListener("click", () => {
            const htmlInput = document.getElementById("html-input").value;
            const styleInput = document.getElementById("style-input").value;
            const userid = <?php echo get_user_id() ?>;
            const topicid = <?php echo $topicId ?>;
            if (htmlInput === "" || styleInput === "") {
                alert("HTML or CSS cannot be empty!");
                return;
            }
            let description = prompt("Please enter a description of your submission", "Description");
            console.log(htmlInput, styleInput, userid, topicid, description);
            if (description === null) {
                return;
            }
            $.ajax({
                type: "POST",
                url: "submitSubmission.php",
                data: {
                    html: htmlInput,
                    css: styleInput,
                    userid: userid,
                    topicid: topicid,
                    description: description
                },
                success: function(response) {
                    console.log(response);
                    if (response === "success") {
                        window.location.href = `/sdp-assignment/gamemode/drawingChallenge/topicDetail.php?topicID=${topicid}`;
                    } else {
                        window.location.href = `/sdp-assignment/gamemode/drawingChallenge/topicDetail.php?topicID=${topicid}`;
                    }
                }
            });
        });
    </script>
</body>

</html>
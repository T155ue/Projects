<?php
require_once '../../Includes/dbConnection.php';
require_once '../../Includes/generalFunc.php';
$is_logged = is_login();
if (isset($_GET['topicID']) !== true || empty($_GET['topicID'])) {
    header("Location: ./index.php");
}
// get request handler
$topicID = $_GET['topicID'];
$report_status = $_GET['report'] ?? null;
if ($report_status == 'success') {
    echo "<script>alert('Report has been sent')</script>";
} else if ($report_status == 'failed') {
    echo "<script>alert('Report failed to send')</script>";
} else if ($report_status == 'alreadyReported') {
    echo "<script>alert('Already Reported')</script>";
}

$is_drawed = false;
// if logged then sql with is the user voted the submission
if ($is_logged) {
    $userid = get_user_id();
    $sql = "SELECT ds.*, u.*, 
       (SELECT COUNT(*) 
        FROM user_submission_upvote usu 
        WHERE usu.submissionid = ds.submissionID) AS upvote_count,
       (EXISTS (SELECT 1 
                FROM user_submission_upvote usu 
                WHERE usu.submissionid = ds.submissionID 
                  AND usu.userid = $userid)) AS isVote
FROM drawing_submission ds
JOIN user u ON ds.authorID = u.userid
WHERE ds.topicID = $topicID;";

    $result = execSql($sql);
    $topicDrawingArr = mysqli_fetch_all($result, MYSQLI_ASSOC);
    // check if the user already draw the submission
    $sql = "SELECT * FROM drawing_submission WHERE topicID = $topicID AND authorID = " . get_user_id();
    $result = execSql($sql);

    if (mysqli_num_rows($result) > 0) {
        // drawed
        $is_drawed = true;
    }
} else {
    // if no logged then all isvote will be false to not break the code ( will warning if not logged and click upvote)
    $sql = "SELECT ds.*, u.*, 
       (SELECT COUNT(*) 
        FROM user_submission_upvote usu 
        WHERE usu.submissionid = ds.submissionID) AS upvote_count,
       false AS isVote
FROM drawing_submission ds
JOIN user u ON ds.authorID = u.userid
WHERE ds.topicID = $topicID;";

    $result = execSql($sql);
    $topicDrawingArr = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$sql = "SELECT * FROM drawing_topic WHERE topicid = $topicID";
$result = execSql($sql);
$topicDetail = mysqli_fetch_assoc($result);

// POST HANDLER FOR UPVOTE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submissionID = $_POST['submissionID'];
    $userID = $_POST['userID'];


    $sql = "SELECT * FROM user_submission_upvote WHERE submissionid = $submissionID AND userid = $userID";
    $result = execSql($sql);
    $upvote = mysqli_fetch_assoc($result);

    if ($upvote) {
        $sql = "DELETE FROM user_submission_upvote WHERE submissionid = $submissionID AND userid = $userID";
    } else {
        $sql = "INSERT INTO user_submission_upvote (submissionid, userid) VALUES ($submissionID, $userID)";
    }
    if (execSql($sql)) {
        header("Location: ./topicDetail.php?topicID=$topicID");
    } else {
        echo "Error";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Document</title>
</head>

<style>
    dialog::backdrop {
        backdrop-filter: blur(20px);
    }
</style>

<body class="bg-[url('../../Asset/bg.gif')] bg-cover backdrop-brightness-50 min-h-screen h-full pb-10 pt-2 ">

    <dialog id="dialog" class="bg-none rounded-xl flex-col w-[35vw] h-[35vw] drop-shadow-lg">
        <div class="w-full justify-end flex p-5">
            <button class="text-red-500 font-bold text-3xl" onclick=" document.getElementById('dialog').close(); ">
                X
            </button>
        </div>
        <h1 class="text-2xl font-bold text-center mb-5">Report For <span id="authorName">Author</span> Component</h1>
        <form method="POST" action="./report.php" class="flex flex-col justify-center items-center gap-y-3">
            <input type="hidden" id="reportSubmissionID" name="submissionID" value="0">
            <input type="hidden" name="userID" value="<?php echo get_user_id() ?>">
            <label for="reason" class="text-lg font-semibold">Reason</label>
            <textarea class="resize-none w-[25vw] h-[15vw] border-gray-300 border-2 rounded-xl " placeholder="Write your reason here...." name="description"></textarea>
            <button class="bg-blue-500 px-6 py-3 text-white text-lg hover:bg-blue-300 rounded-xl drop-shadow-lg">Submit</button>
        </form>

    </dialog>

    <div class="p-3">
        <a class="text-5xl drop-shadow-xl hover:text-6xl transition-all absolute" href="./">
            ⬅️
        </a>
    </div>
    <div class="container mx-auto max-w-4xl p-8 bg-gray-200 rounded-xl border-2 border-black mb-10">

        <h1 class="text-3xl font-bold mb-4"><?php echo $topicDetail['title'] ?></h1>
        <div class="flex items-center space-x-4 mb-6">
            <p class="text-gray-700"><?php echo $topicDetail['description'] ?></p>
            <?php echo $topicDetail['status'] ?
                '<span class="px-2 py-1 rounded-md bg-green-100 text-green-800 text-xs font-medium"> Open </span>' :
                '<span class="px-2 py-1 rounded-md bg-red-100 text-red-800 text-xs font-medium"> Ended </span>'


            ?>
            <span class="px-2 py-1 rounded-md bg-yellow-100 text-yellow-800 text-xs font-medium"><?php echo $topicDetail['expertise'] ?></span>
        </div>
        <?php if ($is_logged && ($topicDetail['status']) && !$is_drawed) : ?>
            <a href="topicDraw.php?topicID=<?php echo $topicDetail['topicid'] ?>" class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-md shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                Join Challenge</a>
        <?php else : ?>
            <a onclick="alert('Cant join event!')" class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-md shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                Join Challenge</a>
        <?php endif; ?>

        <h2 class="text-2xl font-semibold mt-8 mb-4">Participant Showcases</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <?php foreach ($topicDrawingArr as $drawing) : ?>
                <?php if ($drawing['IsCompany']) : ?>
                    <div class="bg-white rounded-lg shadow-md p-4 border-4 border-purple-500">
                    <?php else : ?>
                        <div class="bg-white rounded-lg shadow-md p-4">
                        <?php endif ?>

                        <a class="text-lg font-medium mb-2" href="/sdp-assignment/User/?id=<?php echo $drawing['userid'] ?>"><?php echo $drawing['name'] ?></a>
                        <p class="text-gray-700 text-sm mb-4"><?php echo $drawing['description'] ?></p>

                        <iframe srcdoc="<html lang=' en'>
                        <style>
                            <?php echo $drawing['css'] ?>
                            
                        </style>
                        <body>
                            <?php echo $drawing['html'] ?>
                        </body>
</html>" class="w-full h-64" frameborder="0">
                        </iframe>

                        <div class="flex items-center justify-between">
                            <?php if ($is_logged) : ?>
                                <button onclick="reportBtnClick(<?php echo $drawing['submissionID'] ?>,
                            '<?php echo $drawing['name'] ?>')" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                    Report
                                </button>
                            <?php endif; ?>
                            <div class="justify-center items-center flex gap-x-2">

                                <span class="text-gray-500"><?php echo $drawing['upvote_count'] ?> Upvotes</span>


                                <?php if ($is_logged) : ?>
                                    <form method="POST" action="" class="justify-center items-center flex gap-x-2">
                                        <input name="submissionID" type="hidden" value="<?php echo $drawing['submissionID'] ?>">
                                        <input name="userID" type="hidden" value="<?php echo get_user_id() ?>">

                                        <button type="submit" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                            <svg class="w-6 h-6" fill="none" stroke="<?php echo $drawing['isVote'] ?  "blue" : "currentColor"   ?>" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11l7-7 7 7M5 19l7-7 7 7"></path>
                                            </svg>
                                        </button>
                                    </form>



                                <?php endif; ?>

                            </div>
                        </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
        </div>
        <script>
            function reportBtnClick(submissionID, submissionAuthor) {
                document.getElementById('dialog').showModal();
                document.getElementById('reportSubmissionID').value = submissionID;
                document.getElementById('authorName').innerText = submissionAuthor;

            }
        </script>
</body>


</html>
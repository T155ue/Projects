<?php
require_once './Includes/dbConnection.php';
require_once './Includes/generalFunc.php';
session_start();
if (isset($_SESSION['id'])) {
    $logged = true;
    // select image from user
    $sql = "SELECT image,isCompany FROM user WHERE userid = " . $_SESSION['id'];
    $result = execSql($sql);
    $user = $result->fetch_assoc();
} else {
    $logged = false;
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    $logged = false;
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>MODE | CSSTOPIA</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>
<style>
    .hidden {
        display: none;
    }
</style>

<body class="bg-[url('./Asset/bg.gif')] bg-cover backdrop-brightness-50 h-screen bg-no-repeat ">
    <header class="flex justify-between items-center px-8 py-4 bg-opacity-40 bg-black drop-shadow-sm">
        <h1 class="text-4xl font-bold text-red-500">CSSTOPIA</h1>
        <audio id="bgAudio">
            <source src="./Asset/bit-beats-6-5-171021.mp3" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
        <div class="flex items-center gap-4">

            <?php if (!$logged) : ?>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-md" onclick="location.href ='/sdp-assignment/Login_SignUp/signup.php'">Sign Up</button>
                <button class="px-4 py-2 bg-green-600 text-white rounded-md" onclick="location.href ='/sdp-assignment/Login_SignUp/login.php'">Login</button>
            <?php endif; ?>
            <h1 class="text-white"><?php
                                    if ($logged) {
                                        echo $_SESSION['username'];
                                    } else {
                                        echo "Guest";
                                    }
                                    ?>
            </h1>
            <?php if ($logged) : ?>

                <form method="post" action="" style="display:inline;">
                    <div class="relative inline-block text-left">
                        <div>
                            <button type="button" class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700" id="menu-button" aria-expanded="false" aria-haspopup="true">
                                <img src="<?php echo $user['image'] ?>" alt="Profile Picture" class="h-8 w-8 rounded-full">
                                <svg class="-mr-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <div id="menu-dropdown" class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                            <div class="py-1" role="none">
                                <a href="/sdp-assignment/User/?id=<?php echo $_SESSION['id'] ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-500 transition-all dark:text-white" role="menuitem" tabindex="-1" id="menu-item-0">View Main Profile</a>
                                <a href="/sdp-assignment/Login_SignUp/profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-500 transition-all dark:text-white" role="menuitem" tabindex="-1" id="menu-item-0">Settings</a>
                                <a href="/sdp-assignment/User/friends.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-500 transition-all dark:text-white" role="menuitem" tabindex="-1" id="menu-item-1">Friends</a>

                                <a href="/sdp-assignment/User/inboxForm.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-500 transition-all dark:text-white" role="menuitem" tabindex="-1" id="menu-item-1">Inboxes</a>
                                <?php if ($user["isCompany"]) : ?>

                                    <a href="/sdp-assignment/Admin/managechallenge.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-500 transition-all dark:text-white" role="menuitem" tabindex="-1" id="menu-item-1">Challenge Manage</a>
                                <?php endif; ?>
                                <form method="POST" action="#" role="none">
                                    <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-500 transition-all dark:text-white" role="menuitem" tabindex="-1" id="menu-item-3" name="logout">Log Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
            <button onclick="toggleAudio()">
                <span id="speakerIcon" class="text-5xl">🔇</span>

            </button>
        </div>

    </header>
    <main class="flex flex-col items-center justify-center  h-[80vh] gap-8">
        <a href="./gamemode/learn/index.php" class="bg-yellow-500 hover:bg-yellow-200 transition-all text-white font-bold py-4 px-8 rounded-lg flex items-center gap-2">
            📖Learn Now!
        </a>
        <a href="./gamemode/componentDesign/componentshowcase.php" class="bg-blue-500 hover:bg-blue-200 transition-all text-white font-bold py-4 px-8 rounded-lg flex items-center gap-2">
            🎨Creative Mode
            <a href="./gamemode/drawingChallenge/index.php" class="bg-red-500 hover:bg-red-200 transition-all text-white font-bold py-4 px-8 rounded-lg flex items-center gap-2">
                🏆Drawing Challenge
            </a>
            <a href="./gamemode/mapExplore/index.php" class="bg-green-500 hover:bg-green-200 transition-all  text-white font-bold py-4 px-8 rounded-lg flex items-center gap-2">
                🌎Explore Builds
            </a>
    </main>
</body>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const menuButton = document.getElementById("menu-button");
        const menuDropdown = document.getElementById("menu-dropdown");

        // Toggle dropdown visibility on button click
        menuButton.addEventListener("click", function(event) {
            const expanded = this.getAttribute("aria-expanded") === "true" || false;
            this.setAttribute("aria-expanded", !expanded);

            // Toggle the 'hidden' class to show/hide the dropdown
            menuDropdown.classList.toggle("hidden");
        });

        // Close the dropdown when clicking outside
        document.addEventListener("click", function(event) {
            if (!menuButton.contains(event.target) && !menuDropdown.contains(event.target)) {
                menuButton.setAttribute("aria-expanded", "false");
                menuDropdown.classList.add("hidden");
            }
        });
    });


    window.onload = function() {
        let audio = document.getElementById("bgAudio");
        audio.volume = 0.35;

    }

    function toggleAudio() {
        let audio = document.getElementById("bgAudio");
        let speakerIcon = document.getElementById("speakerIcon");
        if (audio.paused) {
            audio.play();
            speakerIcon.innerHTML = "📢";
        } else {
            audio.pause();

            speakerIcon.innerHTML = "🔇";
        }
    }
</script>

</html>
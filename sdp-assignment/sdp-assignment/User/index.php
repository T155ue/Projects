<?php
require_once '../Includes/dbConnection.php';
require_once '../Includes/generalFunc.php';

// Handle get request; if no 'id' parameter, redirect to home page
if (!isset($_GET['id'])) {
    header("Location: ../index.php");
    exit();
} else {
    $id = $_GET['id'];

    // Get user data
    $sql = "SELECT * FROM user WHERE userid = $id";
    $result = execSql($sql);
    $user = mysqli_fetch_assoc($result);
    if ($user == null) {
        header("Location: ../index.php");
        exit();
    }

    // Get user projects
    $sql = "SELECT * FROM component WHERE PlayerID = $id";
    $result = execSql($sql);
    $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Handle POST request to update mainComponentID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['component_id'])) {
    $componentId = $_POST['component_id'];

    $sql = "UPDATE user SET mainComponentID = $componentId WHERE userid = $id";
    $result = execSql($sql);
    if ($result) {
        echo "Main component updated successfully";
        exit();
    } else {
        echo "Failed to update main component";
        exit();
    }
}

// if user is viewing own profile
$isHim = false;
if (get_user_id() == $id) {
    $isHim = true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UserPage</title>
    <meta name="title" content="UserPage">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500&family=Open+Sans:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="/sdp-assignment/User/viewUser.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .toggle-main-btn.main-component {
            background-color: #FDE047;
            color: black;
        }

        .toggle-main-btn.main-component:hover {
            background-color: #e6b800;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleButtons = document.querySelectorAll('.toggle-main-btn');
            toggleButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const componentId = this.getAttribute('data-component-id');

                    // AJAX request
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            console.log(xhr.responseText);
                            alert(xhr.responseText);
                            if (xhr.responseText.includes('successfully')) {
                                toggleButtons.forEach(btn => btn.classList.remove('main-component'));
                                button.classList.add('main-component');
                            }
                        } else {
                            console.error('Error occurred while updating main component');
                        }
                    };
                    xhr.send(`component_id=${componentId}`);
                });
            });
        });
    </script>
</head>

<body>
    <main>
        <header class="flex pt-5 pl-5">
            <?php if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'friends.php') !== false) { ?>
                <a href="../User/friends.php" class="back-btn">Back</a>
            <?php } else { ?>
                <a href="../" class="back-btn">Back</a>
            <?php } ?>
        </header>
        <article>
            <section class="">
                <div class="flex flex-row mt-[10rem] ml-[20rem] items-center gap-x-10 h-[20vh]">
                    <div class="">
                        <!-- Gets the user image -->
                        <img src="<?php echo $user['image'] ?>" width="300" height="300" alt="Profile Pics" class=" rounded-full">
                    </div>
                    <div class="w-[30%] ml-[5rem]">
                        <?php if ($user['IsCompany'] == 1) { ?>
                            <span class="label-large section-subtitle">CSS Topia Company</span>
                        <?php } else { ?>
                            <span class="label-large section-subtitle">CSS Topia User</span>
                        <?php } ?>
                        <div class="flex flex-row gap-x-5 items-center">
                            <h1 class="display-small"><?php echo $user['username'] ?></h1>
                            <h1 class="text-2xl mt-6 text-gray-500"><?php echo $user['name'] ?></h1>
                            <h1 class="text-xl mt-6 text-gray-500">ID: <?php echo $user['userid'] ?></h1>
                        </div>
                        <p class="w-[50%]">
                            <?php echo $user['biography'] ?>
                        </p>
                    </div>
                    <div class="flex flex-row gap-x-10 gap-y-10">
                        <div class="flex flex-col gap-x-10 gap-y-10">
                            <a href="" class="chip">
                                <?php if ($user['IsCompany'] == 1) { ?>
                                    <i class='bx bxs-buildings' style="font-size: 25px;"></i>
                                    <span class="label-large">Company</span>
                                <?php } else { ?>
                                    <i class='bx bxs-user-rectangle' style="font-size: 25px;"></i>
                                    <span class="label-large">Player</span>
                                <?php } ?>
                                <div class="state-layer"></div>
                            </a>

                            <a href="" class="chip">
                                <i class='bx bx-mail-send' style="font-size: 20px;"></i>
                                <span class="label-large">Inbox</span>
                                <div class="state-layer"></div>
                            </a>
                        </div>
                        <div class="flex flex-col gap-x-10 gap-y-10">
                            <a href="mailto:<?php echo $user['email'] ?>" class="chip">
                                <span class="material-symbols-outlined" aria-hidden="true">Mail</span>
                                <span class="label-large"><?php echo $user['email'] ?></span>
                                <div class="state-layer"></div>
                            </a>
                            <a href="tel:+60111111111" class="chip">
                                <span class="material-symbols-outlined" aria-hidden="true">Call</span>
                                <span class="label-large"><?php echo $user['phone'] ?></span>
                                <div class="state-layer"></div>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
            <section class="mt-[10rem]">
                <div class="article">
                    <div class="about-card bg-gray-800 text-white rounded-lg p-6 shadow-md">
                        <h2 class="card-title text-4xl font-bold mb-6">About</h2>
                        <hr class="border-t-2 border-gray-500 mb-6">
                        <ul class="about-list space-y-6">
                            <li class="flex items-center">
                                <span class="material-symbols-outlined text-3xl mr-4" aria-hidden="true">location_on</span>
                                <span class="label-large"><?php echo $user['state'] ?></span>
                            </li>
                            <li class="flex items-center">
                                <i class='bx bxl-facebook-circle text-3xl mr-4'></i>
                                <span class="label-large"><?php echo $user['facebook'] ?></span>
                            </li>
                            <li class="flex items-center">
                                <i class='bx bxl-instagram-alt text-3xl mr-4'></i>
                                <span class="label-large"><?php echo $user['instagram'] ?></span>
                            </li>
                            <li class="flex items-center">
                                <i class='bx bxl-linkedin-square text-3xl mr-4'></i>
                                <span class="label-large"><?php echo $user['linkedIn'] ?></span>
                            </li>
                            <li class="flex items-center">
                                <i class='bx bxl-twitter text-3xl mr-4'></i>
                                <span class="label-large"><?php echo $user['twitter'] ?></span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <div class="project">
                            <button class="tab-btn active bg-gray-800 text-white border border-gray-600 rounded-lg py-2 px-4 transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-gray-400" data-tab-btn="project">
                                <span class="tab-text title-large">Projects</span>
                            </button>
                        </div>

                        <div>
                            <div class="grid grid-cols-2 gap-y-5 h-[50vh] px-5">
                                <?php foreach ($projects as $project) { ?>
                                    <div class="border-2 border-gray-500 rounded-md drop-shadow-md p-10 hover:bg-gray-700 transition">
                                        <iframe srcdoc="<html lang=' en'>
                                            <style>
                                                <?php echo $project['component_css'] ?>
                                            </style>
                                            <body>
                                                <?php echo $project['component_html'] ?>
                                            </body>
                                        </html>" class="w-full h-64" frameborder="0">
                                        </iframe>
                                        <div>
                                            <h1 class="text-3xl font-bold mt-3"><?php echo $project['component_name'] ?></h1>

                                            <?php if ($isHim and $project['component_type'] == 2) : ?>
                                                <button class="toggle-main-btn mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 transition <?php echo ($project['ComponentID'] == $user['mainComponentID']) ? 'main-component' : ''; ?>" data-component-id="<?php echo $project['ComponentID']; ?>">
                                                    <?php echo ($project['ComponentID'] == $user['mainComponentID']) ? 'Main' : 'Toggle as Main'; ?>
                                                </button>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </article>
    </main>
    <footer class="footer">

    </footer>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleButtons = document.querySelectorAll('.toggle-main-btn');
            toggleButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const componentId = this.getAttribute('data-component-id');

                    // AJAX request
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            console.log(xhr.responseText);
                            if (xhr.responseText.includes('successfully')) {
                                toggleButtons.forEach(btn => {
                                    if (btn !== button) {
                                        btn.innerText = 'Toggle as Main';
                                    }
                                });
                                button.innerText = 'Main';
                                toggleButtons.forEach(btn => btn.classList.remove('main-component'));
                                button.classList.add('main-component');
                            }
                        } else {
                            console.error('Error occurred while updating main component');
                        }
                    };
                    xhr.send(`component_id=${componentId}`);
                });
            });
        });
    </script>
</body>

</html>
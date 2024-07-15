<?php
session_start();
require_once '../Includes/dbConnection.php';
require_once '../Includes/generalFunc.php';

if (!is_login()) {
    header("Location: ../index.php");
    exit();
}
$id = get_user_id();

// Fetch current user's friends
$sql_friends = "SELECT CASE
                    WHEN userid1 = $id THEN userid2
                    ELSE userid1
                END AS friend_id
                FROM user_friend
                WHERE userid1 = $id OR userid2 = $id";
$result_friends = execSql($sql_friends);

$friend_ids = [];
while ($row = $result_friends->fetch_assoc()) {
    $friend_ids[] = $row['friend_id'];
}

$friends = [];
foreach ($friend_ids as $friend_id) {
    $sql_friend_details = "SELECT userid, username, image FROM user WHERE userid = $friend_id";
    $result_friend_details = execSql($sql_friend_details);
    $friend = $result_friend_details->fetch_assoc();
    if ($friend) {
        $friends[] = $friend;
    }
}

// Handle search for user by username
$searchedUser = null;
$friend_request_sent = false;
$already_friends_message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['searchedUser'])) {
    $searchedUsername = $_POST['searchedUser'];
    $sql_search_user = "SELECT userid, username, image FROM user WHERE username = '$searchedUsername'";
    $result_search_user = execSql($sql_search_user);
    $searchedUser = $result_search_user->fetch_assoc();
}

// Handle sending friend requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receiverID'])) {
    $senderID = $id;
    $receiverID = $_POST['receiverID'];

    // Check if they are already friends
    $sql_check_friends = "SELECT * FROM user_friend WHERE (userid1 = $senderID AND userid2 = $receiverID) OR (userid1 = $receiverID AND userid2 = $senderID)";
    $result_check_friends = execSql($sql_check_friends);

    if ($result_check_friends->num_rows == 0) {
        // They are not friends, send friend request
        $sql_insert_request = "INSERT INTO friend_request (senderID, receiverID) 
                              VALUES ($senderID, $receiverID)";
        $result_insert = execSql($sql_insert_request);

        if ($result_insert) {
            $friend_request_sent = true;
        }
    } else {
        // They are already friends, handle accordingly (e.g., show an alert)
        $already_friends_message = 'You are already friends with this user.';
    }
}

// Fetch pending friend requests for the current user
$sql_requests = "SELECT senderID FROM friend_request WHERE receiverID = $id";
$result_requests = execSql($sql_requests);

$pending_requests = [];
while ($row = $result_requests->fetch_assoc()) {
    $pending_requests[] = $row['senderID'];
}

// Handle confirming or rejecting friend requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirmRequest'])) {
        $senderID = $_POST['confirmRequest'];

        // Add sender and receiver as friends in user_friend table
        $sql_add_friend = "INSERT INTO user_friend (userid1, userid2) VALUES ($senderID, $id)";
        $result_add_friend = execSql($sql_add_friend);

        if ($result_add_friend) {
            // Delete the friend request
            $sql_delete_request = "DELETE FROM friend_request WHERE senderID = $senderID AND receiverID = $id";
            $result_delete_request = execSql($sql_delete_request);

            if ($result_delete_request) {
                header("Location: friends.php");
                exit();
            }
        }
    } elseif (isset($_POST['deleteRequest'])) {
        $senderID = $_POST['deleteRequest'];

        // Delete the friend request
        $sql_delete_request = "DELETE FROM friend_request WHERE senderID = $senderID AND receiverID = $id";
        $result_delete_request = execSql($sql_delete_request);

        if ($result_delete_request) {
            header("Location: friends.php");
            exit();
        }
    }
}

// Fetch users who sent pending friend requests to the current user
$requesting_users = [];
foreach ($pending_requests as $request_senderID) {
    $sql_user_details = "SELECT userid, username, image FROM user WHERE userid = $request_senderID";
    $result_user_details = execSql($sql_user_details);
    $user_details = $result_user_details->fetch_assoc();
    if ($user_details) {
        $requesting_users[] = $user_details;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/sdp-assignment/User/friends.css">
</head>

<body>
    <div id="container">
        <div class="header">
            <div class="search">
                <form method="post" action="" class="searchBar">
                    <div class="search-input">
                        <input type="text" name="searchedUser" placeholder="Enter a friend's username">
                        <button type="submit" class="searchbtn">Search</button>
                    </div>
                </form>
            </div>
            <a class="backbtn" href="../index.php"></i>Back</a>
        </div>
        <div class="options">
            <button class="tab-btn active" data-tab-btn="friends">
                <span class="tab-text">Friends</span>
                <div class="state-layer"></div>
            </button>
            <button class="tab-btn" data-tab-btn="request">
                <span class="tab-text">Requests</span>
                <div class="state-layer"></div>
            </button>
        </div>
        <section class="tab-content friends active">
            <div class="body">
                <div class="dark-section py-12 sm:py-16 lg:py-20">
                    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                        <div class="grid grid-cols-1 gap-y-12 sm:grid-cols-2 gap-x-10 lg:grid-cols-3 xl:grid-cols-4">
                            <?php foreach ($friends as $friend) : ?>
                                <div class="group relative">
                                    <div class="relative">
                                        <img class="w-full h-full rounded-lg" src="<?php echo $friend['image']; ?>" alt="Friend profile picture">
                                        <div class="absolute inset-0 rounded-lg bg-black opacity-5"></div>
                                    </div>
                                    <div class="mt-6">
                                        <h3 class="text-lg font-medium text-gray-200"><?php echo $friend['username']; ?></h3>
                                    </div>
                                    <div class="mt-6 flex items-center space-x-4">
                                        <a class="button-view" href="../User/index.php?id=<?php echo $friend['userid']; ?>&from=friends">View</a>
                                        <button class="button-inbox" onclick="location.href ='/sdp-assignment/user/inboxForm.php'">Inbox</button>
                                        <form id="deleteForm" method="POST" action="./deleteFriend.php">
                                            <input type="hidden" name="friend_id" value="<?php echo $friend['userid']; ?>">
                                            <input type="hidden" name="user_id" value="<?php echo get_user_id() ?>">
                                            <button class="button-inbox bg-red-500 hover:bg-red-700">Delete</button>
                                        </form>

                                    </div>

                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class=" tab-content request">
            <div class="body">
                <div class="dark-section py-12 sm:py-16 lg:py-20">
                    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                        <div class="grid grid-cols-1 gap-y-12 sm:grid-cols-2 gap-x-10 lg:grid-cols-3 xl:grid-cols-4">
                            <?php foreach ($requesting_users as $user) : ?>
                                <div class="group relative">
                                    <div class="relative">
                                        <img class="w-full h-full rounded-lg" src="<?php echo $user['image']; ?>" alt="Friend profile picture">
                                        <div class="absolute inset-0 rounded-lg bg-black opacity-5"></div>
                                    </div>
                                    <div class="mt-6">
                                        <h3 class="text-lg font-medium text-gray-200"><?php echo $user['username']; ?></h3>
                                    </div>
                                    <div class="mt-6 flex items-center space-x-4">
                                        <a class="button-view" href="../User/index.php?id=<?php echo $user['userid']; ?>&from=friends">View</a>
                                        <form method="post" action="">
                                            <input type="hidden" name="confirmRequest" value="<?php echo $user['userid']; ?>">
                                            <button type="submit" class="button-confirm">Confirm</button>
                                        </form>
                                        <form method="post" action="">
                                            <input type="hidden" name="deleteRequest" value="<?php echo $user['userid']; ?>">
                                            <button type="submit" class="button-reject">Reject</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="popup" id="searched" style="display: <?php echo isset($searchedUser) ? 'block' : 'none'; ?>;">
        <button class="close-btn"><i class="bx bx-x"></i></button>
        <form class="space-y-4" id="searchedUserForm" method="post" action="">
            <div style="text-align: center;">
                <img src="<?php echo isset($searchedUser) ? $searchedUser['image'] : ''; ?>" alt="profile pic" class="profile-img">
            </div>
            <div>
                <label for="username">Username: <?php echo isset($searchedUser) ? $searchedUser['username'] : ''; ?></label>
                <input type="hidden" name="receiverID" value="<?php echo isset($searchedUser) ? $searchedUser['userid'] : ''; ?>">
            </div>
            <div>
                <button type="submit" class="button-send-request">Send Friend Request</button>
            </div>
        </form>
    </div>
    <script>
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', () => {
                const tabBtns = document.querySelectorAll('.tab-btn');
                tabBtns.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                const tabContents = document.querySelectorAll('.tab-content');
                tabContents.forEach(content => content.classList.remove('active'));
                document.querySelector(`.tab-content.${button.dataset.tabBtn}`).classList.add('active');
            });
        });

        document.querySelector('.close-btn').addEventListener('click', () => {
            document.getElementById('searched').style.display = 'none';
        });

        // get element of delete friend form
        const deleteForm = document.getElementById('deleteForm');
        // add event listener to delete friend form
        deleteForm.addEventListener('submit', (e) => {
            // confirm if user wants to delete friend
            e.preventDefault();
            // post 
            const isDelete = confirm('Are you sure you want to delete this friend?');
            if (isDelete) {
                deleteForm.submit();
            }
        });

        <?php if (isset($already_friends_message)) : ?>
            alert('<?php echo $already_friends_message; ?>');
        <?php endif; ?>

        <?php if ($friend_request_sent) : ?>
            alert('Friend request sent successfully.');
        <?php endif; ?>
    </script>
</body>

</html>
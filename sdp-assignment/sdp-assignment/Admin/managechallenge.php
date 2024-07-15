<?php
require_once '../Includes/dbConnection.php';
require_once '../Includes/generalFunc.php';



$isCompany = is_company();

if (!$isCompany) {
    $query = 'SELECT * FROM drawing_topic';
    $result = execSql($query);
} else {
    $query = 'SELECT * FROM drawing_topic WHERE organizerid = ' . get_user_id();
    $result = execSql($query);
}
// Check if the query was successful
if (!$result) {
    die("Database query failed: " . mysqli_error($dbConnection));
}

if (isset($_POST['topicid'])) {

    $topicid = $_POST['topicid'];
    $deletequery = "DELETE From drawing_topic WHERE topicid='$topicid'";
    $deleteresult = execSql($deletequery);

    if ($deleteresult) {
        // Redirect back to the same page (assuming this is where the form is)
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        // Handle deletion failure
        echo "Error: " . mysqli_error($dbConnection);
        // You can redirect or display an error message as needed
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topics</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <header class="bg-blue-500 text-white p-4 text-center font-bold relative">
        <div class="flex justify-between items-center"> <!-- Flex container to align header content -->
            <span class="inline-block">Manage Drawing Challenge Page</span> <!-- Header title -->
            <div>
                <button id="addButton" class="bg-white text-blue-500 rounded-md px-4 py-2 border border-blue-500 hover:bg-blue-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" onclick="location.href ='editchallenge.php'">
                    Add drawing topic
                </button>
                <button id="backButton" class="bg-white text-blue-500 rounded-md px-4 py-2 border border-blue-500 hover:bg-blue-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" onclick="location.href ='/sdp-assignment/admin/adminmainpage.php'">
                    Back
                </button>
            </div>
    </header>
</head>

<body>

    <div class="container mx-auto p-4 grid gap-4 grid-cols-2">
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="bg-gray-100 rounded-lg p-4 relative">
                <?php
                foreach ($row as $key => $value) {
                    echo "<p><strong class='font-bold'>" . ucfirst($key) . ":</strong> " . $value . "</p>";
                }
                ?>
                <div class="flex justify-end mt-4">
                    <form id="<?php echo $row['topicid'] ?>" action="editchallenge.php" method="post">
                        <button class="editbutton bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2 w-20" value="<?php echo $row['topicid'] ?>">Edit</button>
                        <input type="hidden" name="topicid" value="<?php echo $row['topicid'] ?>">
                    </form>
                    <form id="<?php echo $row['topicid'] ?>" action="managechallenge.php" method="post">
                        <button class="deletebutton bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" value="<?php echo $row['topicid'] ?>">Delete</button>
                        <input type="hidden" name="topicid" value="<?php echo $row['topicid'] ?>">
                    </form>
                </div>
            </div>
        <?php
        }
        ?>
    </div>

</body>
<script>
    const deletebutton = document.querySelectorAll(".deletebutton");


    deletebutton.forEach(button => {
        button.addEventListener("click", function() {

            form = document.getElementById(button.value);

            if (confirm("Are you sure you want to delete this drawing topic?")) {
                alert("Successfully deleted drawing topic!");
                form.submit();


            }
        })
    })

    document.getElementById('backButton').addEventListener('click', function() {
        if (<?php echo $isCompany ?>) {
            window.location.href = '/sdp-assignment/';
        } else {
            window.location.href = 'adminmainpage.php';
        }
        // Redirect to adminmainpage.php

    });

    document.getElementById('addButton').addEventListener('click', function() {
        window.location.href = 'editchallenge.php'; // Redirect to adminmainpage.php
    });
</script>

</html>

<?php
mysqli_free_result($result);
?>
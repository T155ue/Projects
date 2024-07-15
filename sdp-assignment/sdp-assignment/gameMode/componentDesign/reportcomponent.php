<?php
require_once '../../Includes/dbConnection.php';
require_once '../../Includes/generalFunc.php';

session_start();
$userid = $_SESSION['id'];

if (isset($_POST['reportcomp'])) {
    $componentid = $_POST['reportcomp'];
}

if (isset($_POST['description'])) {
    // Fix the query (replace with your actual query)
    $description = $_POST['description']; // Assuming you have a description field
    $cid = $_POST['componentid'];
    $checkquery = execSql("SELECT * FROM report_component WHERE UserID = '$userid' AND ComponentID = '$cid'");
    if (mysqli_num_rows($checkquery) == 0) {
        $query = execSql("INSERT INTO report_component (UserID, ComponentID, Description) VALUES ('$userid', '$cid', '$description')");
        if ($query) {
            // Redirect to componentshowcase.php if successful
            header('Location: componentshowcase.php');
            exit;
        } else {
            // Handle error (e.g., display an error message)
            echo "Error executing query.";
        }
    } else {
        echo "<script>alert('A report for this component by this user already exists.')</script>";
        echo "<script>window.location.href = 'componentshowcase.php';</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Form</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <header class="bg-blue-500 text-white py-4 flex justify-between items-center px-4">
        <h1 class="text-2xl font-bold">Report Component</h1>

        <!-- Right-aligned links -->
        <div class="flex items-center">
            <!-- Bak Button -->
            <a href="componentshowcase.php" class="bg-white text-blue-500 px-3 py-1 rounded-md text-sm">Back</a>
        </div>
    </header>
</head>

<body class="bg-[url('../../Asset/bg.gif')] bg-cover backdrop-brightness-50 h-full pt-1 p-2 pb-10">
    <div class="max-w-lg mx-auto mt-12 p-8 bg-white rounded-lg shadow-md">
        <h1 class="text-4xl font-bold mb-6">Report Form</h1>
        <form action="reportcomponent.php" method="post">
            <!-- User ID (Autofilled) -->
            <div class="mb-6">
                <label for="userid" class="block text-lg font-semibold text-gray-700">User ID:</label>
                <span id="userid" class="text-lg"><?php echo $userid; ?></span>
                <input type="hidden" name="userid" value="<?php echo $userid; ?>">
            </div>

            <!-- Component ID (Autofilled) -->
            <div class="mb-6">
                <label for="componentid" class="block text-lg font-semibold text-gray-700">Component ID:</label>
                <span id="componentid" class="text-lg"><?php echo $componentid; ?></span>
                <input type="hidden" name="componentid" value="<?php echo $componentid; ?>">
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-lg font-semibold text-gray-700">Description</label>
                <textarea id="description" name="description" rows="6" class="mt-2 p-4 w-full border rounded-md focus:ring focus:ring-blue-200"></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-lg px-6 py-3 rounded-md">Submit Report</button>
        </form>
    </div>
</body>

</html>



</html>
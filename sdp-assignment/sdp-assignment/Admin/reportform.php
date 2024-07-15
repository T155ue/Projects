<?php
require_once '../Includes/dbConnection.php';
if (isset($_POST['reportuser']) && isset($_POST['reportadmin'])) {
    $adminid = $_POST['reportadmin'];
    $userid = $_POST['reportuser'];
}
// Check if all required fields are set
if (isset($_POST['warning_title']) && isset($_POST['warning_content']) && isset($_POST['datetime'])) {
    // Sanitize inputs (for security)
    $warning_title = htmlspecialchars($_POST['warning_title']);
    $warning_content = htmlspecialchars($_POST['warning_content']);
    $datetime = htmlspecialchars($_POST['datetime']);
    $userid = htmlspecialchars($_POST['userID']);
    $adminID = htmlspecialchars($_POST['adminID']);

    $reportinboxquery = execSql("INSERT INTO warninginbox (adminID, userID, datetime, warning_title, warning_content) 
                VALUES ('$adminID', '$userid', '$datetime', '$warning_title', '$warning_content')");

    if ($reportinboxquery) {
        header('Location: adminmainpage.php');
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
    <title>Warning Form</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <header class="bg-blue-500 text-white p-4 text-center font-bold relative">
        <div class="flex justify-between items-center"> <!-- Flex container to align header content -->
            <span class="inline-block">Report Inbox Page</span> <!-- Header title -->
            <button id="backButton" class="bg-white text-blue-500 rounded-md px-4 py-2 border border-blue-500 hover:bg-blue-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Back
            </button>
        </div>
    </header>
</head>

<body class="bg-gray-100">
    <div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-lg mt-3">
        <h2 class="text-3xl font-bold mb-6">Warning Form for User ID <?php echo $userid ?></h2>
        <form id="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <!-- Hidden inputs for adminID and userID -->
            <input type="hidden" name="adminID" value="<?php echo $adminid; ?>"> <!-- Replace with actual adminID if available -->
            <input type="hidden" name="userID" value="<?php echo $userid ?>"> <!-- Assuming you have this value from somewhere -->

            <!-- Input fields for warning title, content, and datetime -->
            <div class="mb-6">
                <label for="warning_title" class="block text-lg font-medium text-gray-800 mb-2">Warning Title:</label>
                <input type="text" id="warning_title" name="warning_title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-lg px-4 py-2" required>
            </div>

            <div class="mb-6">
                <label for="warning_content" class="block text-lg font-medium text-gray-800 mb-2">Warning Content:</label>
                <textarea id="warning_content" name="warning_content" rows="6" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-lg px-4 py-2" required></textarea>
            </div>

            <div class="mb-6">
                <label for="datetime" class="block text-lg font-medium text-gray-800 mb-2">Datetime:</label>
                <input type="datetime-local" id="datetime" name="datetime" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-lg px-4 py-2" required>
            </div>

            <!-- Submit button -->
            <button id="submitbutton" type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">Submit</button>
        </form>
    </div>
</body>

<script>
    // Get references to the form and submit button
    const form = document.getElementById("form");
    const submitButton = document.getElementById("submitbutton");

    // Add event listener to the submit button
    submitButton.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default form submission

        // Get all input elements within the form
        const formInputs = form.querySelectorAll('input, textarea');

        let isEmpty = false;

        // Iterate over each input element
        formInputs.forEach(input => {
            // Check if the input value is empty or only whitespace
            if (!input.value.trim()) {
                isEmpty = true;
                return; // Exit loop early if any input is empty
            }
        });

        // If any input is empty, show alert and stop further action
        if (isEmpty) {
            alert("Please enter all values before submitting.");
            return;
        }

        // If all inputs are filled, submit the form
        alert("warning has been sent to user.")
        form.submit();
    });

    const backbutton = document.getElementById("backButton");
    backbutton.addEventListener('click', function() {
        window.location.href = 'adminmainpage.php'; // Redirect to adminmainpage.php


    })
</script>


</html>
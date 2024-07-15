<?php


require_once '../Includes/dbConnection.php';
$query = 'SELECT * FROM companyrequest';
$result = execSql($query);


if (isset($_POST['username'])) {

    // Retrieve form data
    $companyrequestid = $_POST['CompanyRequestID'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Assuming you are hashing the password properly
    $name = $_POST['name'];
    $email = $_POST['email'];
    $biography = $_POST['biography'];
    $birthday = $_POST['birthday'];
    $state = $_POST['state'];
    $phone = $_POST['phone'];
    $twitter = $_POST['twitter'];
    $facebook = $_POST['facebook'];
    $linkedIn = $_POST['linkedIn'];
    $instagram = $_POST['instagram'];
    $image = $_POST['image'];


    // Example update query (replace with your actual SQL query)
    $insertQuery = "INSERT INTO user (username, password, name, email, biography, birthday, state, phone, twitter, facebook, linkedIn, instagram, image, IsCompany) 
                    VALUES ('$username', '$password', '$name', '$email', '$biography', '$birthday', '$state', '$phone', '$twitter', '$facebook', '$linkedIn', '$instagram', '$image', 1)";

    $resultupdate = execSql($insertQuery);

    $deleteQuery = "DELETE FROM companyrequest WHERE CompanyRequestID = '$companyrequestid'";
    $resultDelete = execSql($deleteQuery);


    // Handle success or error based on the result of the query
    if ($resultupdate) {
        // Success - Redirect to the current page
        header("Location: " . $_SERVER['PHP_SELF']);
        exit; // Important: Stop further execution
    } else {
        // Error - Display an error message
        echo "<p class='error'>An error occurred while processing your request. Please try again later.</p>";
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
            <span class="inline-block">Company To Be Approved Page</span> <!-- Header title -->
            <button id="backButton" class="bg-white text-blue-500 rounded-md px-4 py-2 border border-blue-500 hover:bg-blue-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Back
            </button>
        </div>
    </header>
</head>

<body class="bg-gray-100">

    <div class="container mx-auto p-4 grid gap-4 grid-cols-2">
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="relative bg-blue-100 rounded-lg p-4">
                <form id="<?php echo $row['CompanyRequestID'] ?>" action="approvecompany.php" method="POST">
                    <?php
                    foreach ($row as $key => $value) {
                        if (ucfirst($key) == "Company_reqdescription") {
                            echo "<p><strong class='font-bold'>" . "Reason to join" . ":</strong> " . $value . "</p>";
                        } elseif (ucfirst($key) !== "Password") {
                            echo "<p><strong class='font-bold'>" . ucfirst($key) . ":</strong> " . $value . "</p>";
                        }

                        echo "<input type='hidden' name='$key' value='$value'>";
                    }
                    ?>
                </form>
                <div class="flex justify-end">
                    <button class=" approvedbutton bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" value="<?php echo $row['CompanyRequestID'] ?>">
                        Approve Company
                    </button>
                </div>
            </div>
        <?php
        }
        ?>
    </div>

    <script>
        // JavaScript code for event listener can be added here if needed
        // Note: IDs should be unique within the page, so you may want to handle click events differently for each button.
        const approveButtons = document.querySelectorAll(".approvedbutton");
        approveButtons.forEach(button => {
            button.addEventListener("click", function(Event) {
                form = document.getElementById(button.value);
                form.submit();
                alert("success!")



            });
        });
        document.getElementById('backButton').addEventListener('click', function() {
            window.location.href = 'adminmainpage.php'; // Redirect to adminmainpage.php
        });
    </script>

</body>

</html>

<?php
mysqli_free_result($result);
?>
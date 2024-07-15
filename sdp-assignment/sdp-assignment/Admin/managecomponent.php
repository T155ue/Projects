<?php
session_start();
require_once '../Includes/dbConnection.php';
$adminid = $_SESSION['adminid'];
$query = 'SELECT * FROM component';
$reportquery = 'SELECT * FROM report';
$paging = "";

if (isset($_POST['componentid'])) {
    $componentid = $_POST['componentid'];
    $deletereportquery = "DELETE FROM report_component WHERE ComponentID = '$componentid'";
    $deletequery = "DELETE FROM component WHERE ComponentID = '$componentid'";
    $deletereport = execSql($deletereportquery);
    $delete = execSql($deletequery);

    if ($delete && $deletereport) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        // Handle deletion failure
        echo "Error: " . mysqli_error($dbConnection);
        // You can redirect or display an error message as needed
    }
}

if (isset($_POST['reportcomponent'])) {
    $componentid = $_POST['reportcomponent'];
    $selectquery = "SELECT * FROM component WHERE ComponentID = '$componentid'";
    $select = execSql($selectquery);
    $paging = 'report';
} else {
    $result = execSql($query);
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
            <span class="inline-block">Manage Component Page</span> <!-- Header title -->
            <button id="backButton" class="bg-white text-blue-500 rounded-md px-4 py-2 border border-blue-500 hover:bg-blue-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Back
            </button>
        </div>
    </header>
</head>

<body>

    <div class="container mx-auto p-4 grid gap-4 grid-cols-2">
        <?php
        if ($paging === 'report') {
            $result = execSql($selectquery);
        }
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="bg-gray-100 rounded-lg p-4 relative">
                <?php
                foreach ($row as $key => $value) {
                    if ($key == "component_html" || $key == "component_css") {
                        // echo "<p><strong class='font-bold'>" . ucfirst($key) . ":</strong><pre><code class='language-html'>" . htmlspecialchars($value, ENT_QUOTES) . "</code></pre></p>";
                    } else {
                        echo "<p><strong class='font-bold'>" . ucfirst($key) . ":</strong> <pre>" . $value . "</pre></p>";
                    }
                }

                ?>
                <div class="result-frame-container mt-4">
                    <iframe class="result-frame w-full h-64" frameborder="0" srcdoc='
                    <html>
                        <head>
                            <style><?php echo htmlspecialchars($row['component_css'], ENT_QUOTES); ?></style>
                        </head>
                        <body>
                            <?php echo htmlspecialchars($row['component_html'], ENT_QUOTES); ?>
                        </body>
                    </html>
                '></iframe>
                </div>
                <div class="flex justify-end mt-4">
                    <form action="reportform.php" method="post">
                        <button class="warningbutton bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 mr-1.5 rounded" value="<?php echo $row['PlayerID'] ?>">Warning User</button>
                        <input type="hidden" name="reportadmin" value="<?php echo $adminid ?>">
                        <input type="hidden" name="reportuser" value="<?php echo $row['PlayerID'] ?>">
                    </form>
                    <form id="<?php echo $row['ComponentID'] ?>" action="managecomponent.php" method="post">
                        <button class="deletebutton bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" value="<?php echo $row['ComponentID'] ?>">Delete</button>
                        <input type="hidden" name="componentid" value="<?php echo $row['ComponentID'] ?>">
                    </form>
                </div>
            </div>
        <?php
        }
        ?>
    </div>

    <script>
        const deletebutton = document.querySelectorAll(".deletebutton");
        const backbutton = document.getElementById("backButton");

        deletebutton.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                form = document.getElementById(button.value);
                if (confirm("Are you sure you want to delete this component?")) {
                    alert("Successfully deleted component.");
                    form.submit();


                }

            })




        });

        backbutton.addEventListener('click', function() {
            window.location.href = 'adminmainpage.php'; // Redirect to adminmainpage.php


        })
    </script>
</body>
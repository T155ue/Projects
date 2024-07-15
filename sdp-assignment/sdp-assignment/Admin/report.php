<?php
session_start();
require_once '../Includes/dbConnection.php';

$adminid = $_SESSION['adminid'];
$query = 'SELECT report_component.*, component.component_css, component.component_html 
FROM report_component 
LEFT JOIN component ON report_component.componentid = component.ComponentID

';

$submissionquery = 'SELECT * FROM report';
$subexec = execSql($submissionquery);
$result = execSql($query);

if (isset($_POST['userid']) && isset($_POST['componentid'])) {
    $userid = $_POST['userid'];
    $componentid = $_POST['componentid'];
    $deletequery = "DELETE FROM report_component WHERE UserID = '$userid' AND ComponentID = '$componentid'";
    $delete = execSql($deletequery);

    if ($delete) {

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        // Handle deletion failure
        echo "Error: " . mysqli_error($dbConnection);
        // You can redirect or display an error message as needed
    }
}


if (isset($_POST['userid']) && isset($_POST['submissionid'])) {
    $userid = $_POST['userid'];
    $submissionid = $_POST['submissionid'];
    $deletequeryrep = "DELETE FROM report WHERE UserID = '$userid' AND submissionID = '$submissionid'";
    $deleterep = execSql($deletequeryrep);

    if ($deleterep) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        // Handle deletion failure
        echo "Error: " . mysqli_error($dbConnection);
        // You can redirect or display an error message as needed
    }
}

if (isset($_POST['subdelete'])) {
    $submissiondelete = $_POST['subdelete'];
    $deletequerysub1 = "DELETE FROM report WHERE submissionID = '$submissiondelete'";
    $deletequerysub2 = "DELETE FROM user_submission_upvote WHERE submissionid = '$submissiondelete'";
    $deletequerysub = "DELETE FROM drawing_submission WHERE submissionID = '$submissiondelete'";

    $deletesub1 = execSql($deletequerysub1);
    $deletesub2 = execSql($deletequerysub2);
    $deletesub = execSql($deletequerysub);


    if ($deletesub && $deletesub1 && $deletesub2) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        // Handle deletion failure
        echo "Error: " . mysqli_error($dbConnection);
        // You can redirect or display an error message as needed
    }
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <header class="bg-blue-500 text-white p-4 text-center font-bold relative">
        <div class="flex justify-between items-center"> <!-- Flex container to align header content -->
            <span class="inline-block">Report Page</span> <!-- Header title -->
            <button id="backButton" class="bg-white text-blue-500 rounded-md px-4 py-2 border border-blue-500 hover:bg-blue-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
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
                        <button class="warningbutton bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 mr-1.5 rounded" value="<?php echo $row['UserID'] ?>">Warning User</button>
                        <input type="hidden" name="reportadmin" value="<?php echo $adminid ?>">
                        <input type="hidden" name="reportuser" value="<?php echo $row['UserID'] ?>">
                    </form>
                    <form id="<?php echo $row['UserID'] + $row['ComponentID'] ?>" action="report.php" method="POST">
                        <button class="reportbutton bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-1.5" value="<?php echo $row['UserID'] + $row['ComponentID'] ?>">Remove Report</button>
                        <input type="hidden" name="userid" value="<?php echo $row['UserID'] ?>">
                        <input type="hidden" name="componentid" value="<?php echo $row['ComponentID'] ?>">
                    </form>
                    <form action="managecomponent.php" method="post" id="<?php echo $row['ComponentID'] ?>">
                        <button class="componentbutton bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-1.5" value="<?php echo $row['ComponentID'] ?>">Delete Component</button>
                        <input type="hidden" name="reportcomponent" value="<?php echo $row['ComponentID'] ?>">
                    </form>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
    <div></div>
    <div class="container mx-auto p-4 grid gap-4 grid-cols-2">
        <?php
        while ($row2 = mysqli_fetch_assoc($subexec)) {
        ?>
            <div class="bg-gray-100 rounded-lg p-4 relative">
                <?php
                foreach ($row2 as $key => $value) {


                    echo "<p><strong class='font-bold'>" . ucfirst($key) . ":</strong> <pre>" . $value . "</pre></p>";
                }

                ?>

                <div class="flex justify-end mt-4">

                    <form id="s<?php echo $row2['UserID'] + $row2['submissionID'] ?>" action="report.php" method="POST">
                        <button class="reportbutton bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-1.5" value="s<?php echo $row2['UserID'] + $row2['submissionID'] ?>">Remove Report</button>
                        <input type="hidden" name="userid" value="<?php echo $row2['UserID'] ?>">
                        <input type="hidden" name="submissionid" value="<?php echo $row2['submissionID'] ?>">
                    </form>
                    <form action="report.php" method="post" id="r<?php echo $row2['submissionID'] ?>">
                        <button class="componentbutton bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-1.5" value="r<?php echo $row2['submissionID'] ?>">Delete Component</button>
                        <input type="hidden" name="subdelete" value="<?php echo $row2['submissionID'] ?>">
                    </form>
                </div>
            </div>
        <?php
        }
        ?>
    </div>

    <script>
        const backbutton = document.getElementById("backButton");
        const deletebutton = document.querySelectorAll(".deletebutton")
        backbutton.addEventListener('click', function() {
            window.location.href = 'adminmainpage.php'; // Redirect to adminmainpage.php


        })

        const reportbutton = document.querySelectorAll(".reportbutton");

        reportbutton.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                form = document.getElementById(button.value);
                if (confirm("Are you sure you want to delete this report?")) {
                    alert("Successfully deleted report.");
                    form.submit();


                }

            })






        });

        deletebutton.forEach(button => {
            button.addEventListener('click', function() {
                form = document.getElementById(button.value);
                form.submit();




            })






        });
    </script>
</body>
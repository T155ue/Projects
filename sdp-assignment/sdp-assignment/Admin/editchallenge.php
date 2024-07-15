<?php
require_once '../Includes/dbConnection.php';
require_once '../Includes/generalFunc.php';

// Initialize variables
$topicid = '';
$title = '';
$description = '';
$expertise = '';
$organizerid = '';
$status = '';

$isCompany = is_company();
// Check if topicid is provided via POST
if (isset($_POST['topicid'])) {
    $topicid = $_POST['topicid'];
    $query = "SELECT * FROM drawing_topic WHERE topicid = '$topicid'";
    $result = execSql($query);

    // Fetch the row if result is valid
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $title = htmlspecialchars($row['title']);
        $description = htmlspecialchars($row['description']);
        $expertise = htmlspecialchars($row['expertise']);
        $organizerid = htmlspecialchars($row['organizerid']);
        $status = $row['status']; // Keep status as-is for initial value

        // Redirect back to the same page (assuming this is where the form is)

    }
}

if (
    isset($_POST['title']) &&
    isset($_POST['description']) &&
    isset($_POST['expertise']) &&
    isset($_POST['organizerid']) &&
    isset($_POST['status']) &&
    isset($_POST['topicid'])
) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $expertise = $_POST['expertise'];
    $organizerid = $_POST['organizerid'];
    $status = $_POST['status'];
    $topicid = $_POST['topicid'];

    $updatequery = "UPDATE drawing_topic SET title = '$title', description = '$description', expertise = '$expertise', organizerid = '$organizerid', status = '$status' WHERE topicid = '$topicid'";

    $updated = execSql($updatequery);

    if ($updated) {
        // Redirect back to the same page (assuming this is where the form is)
        header('Location: managechallenge.php');
        exit;
    } else {
        // Handle deletion failure
        echo "Error: " . mysqli_error($dbConnection);
        // You can redirect or display an error message as needed
    }
} elseif (
    isset($_POST['title']) &&
    isset($_POST['description']) &&
    isset($_POST['expertise']) &&
    isset($_POST['organizerid']) &&
    isset($_POST['status'])

) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $expertise = $_POST['expertise'];
    $organizerid = $_POST['organizerid'];
    $status = $_POST['status'];


    $insertquery = "INSERT drawing_topic SET title = '$title', description = '$description', expertise = '$expertise', organizerid = '$organizerid', status = '$status'";

    $insert = execSql($insertquery);

    if ($insert) {
        echo "<script>
        alert('deletion successful.');

    </script>";
        // Redirect back to the same page (assuming this is where the form is)
        header('Location: managechallenge.php');
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
    <title>Edit Drawing Topic</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <header class="bg-blue-500 text-white p-4 text-center font-bold">
        Edit Drawing Topic
    </header>

    <div class="container mx-auto p-4">
        <?php if (isset($row)) : ?>
            <div class="bg-white rounded-lg shadow-md p-4 mb-4">
                <form id="editform" action="editchallenge.php" method="POST">
                    <input type="hidden" name="topicid" value="<?php echo $topicid; ?>">

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2" for="title">Title:</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="title" type="text" name="title" value="<?php echo $title; ?>">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2" for="description">Description:</label>
                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description" rows="4"><?php echo $description; ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Expertise:</label>
                        <div style="width:750px;" class="flex w-400 h-45">
                            <button type="button" id="beginner" class="expertisebutton border border-gray-300 px-4 py-2 rounded-l-lg focus:outline-none w-1/2" name="Beginner" value="<?php echo $expertise ?>">
                                Beginner
                            </button>
                            <button type="button" id="amateur" class="expertisebutton border border-gray-300 px-4 py-2 rounded-r-lg focus:outline-none w-1/2" name="Amateur" value="<?php echo $expertise ?>">
                                Amateur
                            </button>
                            <button type="button" id="intermediate" class="expertisebutton border border-gray-300 px-4 py-2 rounded-r-lg focus:outline-none w-1/2" name="Intermediate" value="<?php echo $expertise ?>">
                                Intermediate
                            </button>
                            <button type="button" id="advanced" class="expertisebutton border border-gray-300 px-4 py-2 rounded-r-lg focus:outline-none w-1/2" name="Advanced" value="<?php echo $expertise ?>">
                                Advanced
                            </button>
                            <button type="button" id="master" class="expertisebutton border border-gray-300 px-4 py-2 rounded-r-lg focus:outline-none w-1/2" name="Master" value="<?php echo $expertise ?>">
                                Master
                            </button>
                            <input type="hidden" id="expertise" name="expertise">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2" for="organizerid">Organizer ID:</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="organizerid" type="text" name="organizerid" value="<?php echo $organizerid; ?>">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Status:</label>
                        <div style="width:300px;" class="flex w-400 h-45" id="statusdiv">
                            <button type="button" id="statusup" class="statusbutton border border-gray-300 px-4 py-2 rounded-l-lg focus:outline-none w-1/2" name="1" value="<?php echo $status ?>">
                                Ongoing
                            </button>
                            <button type="button" id="statusdown" class="statusbutton border border-gray-300 px-4 py-2 rounded-r-lg focus:outline-none w-1/2" name="0" value="<?php echo $status ?>">
                                Ended
                            </button>
                        </div>
                        <input type="hidden" name="status" id="status">
                    </div>

                    <div class="flex">
                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" id="submitbutton">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        <?php else : ?>
            <div class="bg-white rounded-lg shadow-md p-4 mb-4">
                <form id="editform" action="editchallenge.php" method="POST">

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2" for="title">Title:</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="title" type="text" name="title" value="">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2" for="description">Description:</label>
                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description" rows="4"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Expertise:</label>
                        <div style="width:750px;" class="flex w-400 h-45">
                            <button type="button" id="beginner" class="expertisebutton border border-gray-300 px-4 py-2 rounded-l-lg focus:outline-none w-1/2" name="Beginner" value="">
                                Beginner
                            </button>
                            <button type="button" id="amateur" class="expertisebutton border border-gray-300 px-4 py-2 rounded-r-lg focus:outline-none w-1/2" name="Amateur" value="">
                                Amateur
                            </button>
                            <button type="button" id="intermediate" class="expertisebutton border border-gray-300 px-4 py-2 rounded-r-lg focus:outline-none w-1/2" name="Intermediate" value="">
                                Intermediate
                            </button>
                            <button type="button" id="advanced" class="expertisebutton border border-gray-300 px-4 py-2 rounded-r-lg focus:outline-none w-1/2" name="Advanced" value="">
                                Advanced
                            </button>
                            <button type="button" id="master" class="expertisebutton border border-gray-300 px-4 py-2 rounded-r-lg focus:outline-none w-1/2" name="Master" value="">
                                Master
                            </button>
                            <input type="hidden" id="expertise" name="expertise">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2" for="organizerid">Organizer ID:</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="organizerid" type="text" name="organizerid" value="">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Status:</label>
                        <div style="width:300px;" class="flex w-400 h-45" id="statusdiv">
                            <button type="button" id="statusup" class="statusbutton border border-gray-300 px-4 py-2 rounded-l-lg focus:outline-none w-1/2" name="1" value="">
                                Ongoing
                            </button>
                            <button type="button" id="statusdown" class="statusbutton border border-gray-300 px-4 py-2 rounded-r-lg focus:outline-none w-1/2" name="0" value="">
                                Ended
                            </button>
                        </div>
                        <input type="hidden" name="status" id="status">
                    </div>

                    <div class="flex">
                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" id="submitbutton">
                            Add Challenge
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById("editform");
                    const statusbutton = document.querySelectorAll('.statusbutton')
                    const expertisebutton = document.querySelectorAll('.expertisebutton');
                    const expertiseinput = document.getElementById("expertise");
                    const statusinput = document.getElementById("status");
                    const titleinput = document.getElementById("title");
                    const descriptioninput = document.getElementById("description");
                    const organizerinput = document.getElementById("organizerid");
                    const submitbutton = document.getElementById("submitbutton");
                    <?php if ($isCompany) : ?>
                        organizerinput.value = <?php echo $_SESSION['id'] ?>;
                        organizerinput.readOnly = true;
                    <?php endif; ?>


                    const colorindi = "bg-blue-200";
                    let status = "";
                    let expertise = "";

                    statusbutton.forEach(button => {
                        if (button.value == button.name) {
                            button.classList.add(colorindi);
                            status = button.value;
                        }

                    });
                    statusbutton.forEach(button => {
                        button.addEventListener('click', function(event) {
                            event.preventDefault();
                            statusbutton.forEach(button => {
                                if (button.classList.contains(colorindi)) {
                                    button.classList.remove(colorindi);
                                }
                            });
                            status = button.name;
                            if (button.name === status) {
                                button.classList.add(colorindi);
                            }




                        })
                    })

                    expertisebutton.forEach(button => {
                        if (button.value == button.name) {
                            button.classList.add(colorindi);
                            expertise = button.value;
                        }

                    });
                    expertisebutton.forEach(button => {
                        button.addEventListener('click', function(event) {
                            event.preventDefault();
                            expertisebutton.forEach(button => {
                                if (button.classList.contains(colorindi)) {
                                    button.classList.remove(colorindi);
                                }
                            });
                            expertise = button.name;
                            if (button.name === expertise) {
                                button.classList.add(colorindi);
                            }




                        })
                    })


                    submitbutton.addEventListener('click', function(event) {
                        event.preventDefault();

                        // Validate form fields
                        const title = document.getElementById('title').value.trim();
                        const description = document.getElementById('description').value.trim();
                        const organizerid = document.getElementById('organizerid').value.trim();

                        if (title === '') {
                            alert('Please enter a title.');
                            return;
                        }

                        if (description === '') {
                            alert('Please enter a description.');
                            return;
                        }


                        if (organizerid === '' || !/^\d+$/.test(organizerid)) {
                            alert('Please enter an valid organizer ID.');
                            return;
                        }


                        let expertiseformat = expertise.charAt(0).toUpperCase() + expertise.slice(1);


                        expertiseinput.value = expertiseformat;
                        statusinput.value = status;
                        alert("success");
                        form.submit();
                        // Submit  the form if all validations pass





                    });


                });
            </script>

</body>

</html>

<?php
// Free the result set
if (isset($result)) {
    mysqli_free_result($result);
}
?>
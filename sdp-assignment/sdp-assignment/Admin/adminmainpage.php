<?php
session_start();

if (!$_SESSION['adminusername']) {
    echo "<script>
    alert('Please login first.');
    window.location.href = '../login_signup/adminlogin.php';
</script>";
}

if (isset($_POST['logout'])) {
    session_destroy();
    echo "<script>
        alert('You have been logged out.');
        window.location.href = '../login_signup/adminlogin.php';
    </script>";
    exit();
}
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Main Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <!-- Header -->
    <header class="bg-blue-500 text-white p-4 text-center font-bold relative">
        <div class="flex justify-between items-center">
            <span class="absolute left-1/2 transform -translate-x-1/2 text-2xl">Admin Main Page</span> <!-- Centered Header title -->
            <form method="POST" class="ml-auto">
                <button type="submit" name="logout" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Log out
                </button>
            </form>
        </div>
    </header>

    <!-- Main Content -->
    <div class="flex justify-center items-center h-screen">

        <div class="bg-white w-96 mt-8 p-6 rounded-lg shadow-lg">

            <!-- Vertical Menu -->
            <a href="approvecompany.php" class="block py-3 px-6 text-white hover:bg-gray-700 bg-blue-500 rounded-lg mb-2 text-lg font-semibold">Manage
                Companies</a>

            <a href="managechallenge.php" class="block py-3 px-6 text-white hover:bg-gray-700 bg-green-500 rounded-lg mb-2 text-lg font-semibold">Manage
                Drawing Challenges</a>

            <a href="managecomponent.php" class="block py-3 px-6 text-white hover:bg-gray-700 bg-yellow-500 rounded-lg mb-2 text-lg font-semibold">Manage
                Components</a>

            <a href="report.php" class="block py-3 px-6 text-white hover:bg-gray-700 bg-red-500 rounded-lg mb-2 text-lg font-semibold">Report</a>

        </div>

    </div>

</body>

</html>
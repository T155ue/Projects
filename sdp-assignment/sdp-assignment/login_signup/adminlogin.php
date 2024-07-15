<?php

require_once '../Includes/dbConnection.php';
session_start();
// if isset _POST then db search for password and username
if (isset($_SESSION['id'])) {
    header('Location: /sdp-assignment/');
}


if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE admin_username = '$username' AND admin_password= '$password'";
    $result = execSql($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $_SESSION['adminusername'] = $row['admin_username'];
        $_SESSION['adminid'] = $row['admin_ID'];
        header('Location: /sdp-assignment/Admin/adminmainpage.php');
    } else {
        echo "<script>alert('Invalid Username or Password')</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="/sdp-assignment/Login_SignUp/userlogsin.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>
<style>
    .back-link {
        position: absolute;
        top: 20px;
        right: 20px;
        text-decoration: none;
        color: white;
        font-size: 16px;
        font-weight: bold;
        background-color: rgb(12, 68, 164);
        padding: 15px;
        border-radius: 20px;
    }

    .back-link:hover {
        text-decoration: underline;
        background-color: rgb(50, 98, 190);
    }
</style>

<body id="body" style="background-image: url(../Img/house.jpg)">
    <a href="/sdp-assignment/index.php" class="back-link">Back</a>
    <div class="wrapper">
        <form action="" method="POST">
            <img src="../Img/CSSTopia.png" alt="" />
            <h1>Admin Login</h1>
            <div class="inputBox">
                <input name="username" type="text" placeholder="Username" required />
                <i class="bx bxs-user"></i>
            </div>
            <div class="inputBox">
                <input name="password" type="password" placeholder="Password" required />
                <i class="bx bxs-lock-alt"></i>
            </div>
            <p style="color:white; margin-bottom:10px;">Login As User<a href="/sdp-assignment/Login_SignUp/login.php" style="color: rgb(0, 135, 232); font-size: 1.2em">Login Now</a></p>
            <button type="submit" class="btn">Login</button>
            <div class="register">
            </div>
        </form>
    </div>
</body>

</html>
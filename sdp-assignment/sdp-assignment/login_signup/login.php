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

  $sql = "SELECT * FROM user WHERE username='$username' AND password='$password' AND IsCompany = 0";
  $result = execSql($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $_SESSION['username'] = $row['username'];
    $_SESSION['id'] = $row['userid'];
    header('Location: /sdp-assignment/');
  } else {
    echo "<script>alert('Invalid Username or Password')</script>";
  }
}
?>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="/sdp-assignment/Login_SignUp/userlogsin.css" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
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

    .admin-button {
      position: absolute;
      top: 20px;
      right: 100px;
      display: inline-block;
      text-decoration: none;
      color: rgb(0, 135, 232);
      font-size: 1.2em;
      background-color: #fff;
      border: 2px solid rgb(0, 135, 232);
      padding: 10px 20px;
      border-radius: 20px;
      margin-right: 10px;
    }

    .admin-button:hover {
      background-color: rgb(0, 135, 232);
      color: #fff;
    }
  </style>
</head>

<body id="body" style="background-image: url(../Img/house.jpg)">
  <a href="/sdp-assignment/index.php" class="back-link">Back</a>
  <a href="/sdp-assignment/Login_SignUp/adminlogin.php" class="admin-button">Admin Login</a>

  <div class="wrapper">
    <form action="" method="POST">
      <img src="../Img/CSSTopia.png" alt="" />
      <h1>User Login</h1>
      <div class="inputBox">
        <input name="username" type="text" placeholder="Username" required />
        <i class="bx bxs-user"></i>
      </div>
      <div class="inputBox">
        <input name="password" type="password" placeholder="Password" required />
        <i class="bx bxs-lock-alt"></i>
      </div>
      <div id="organizer">
        <p>
          Login as Company
          <a href="/sdp-assignment/Login_SignUp/orglogin.php" style="color: rgb(0, 135, 232); font-size: 1.2em">Login Now</a>
        </p>
      </div>
      <button type="submit" class="btn">Login</button>
      <div class="register">
        <p>
          Don't have an account?
          <a href="/sdp-assignment/Login_SignUp/signup.php">Sign Up</a>
        </p>
      </div>

    </form>
  </div>
</body>

</html>
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

  $sql = "SELECT * FROM user WHERE username='$username' AND password='$password' AND IsCompany = '1'";
  $result = execSql($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $_SESSION['username'] = $row['username'];
    $_SESSION['id'] = $row['userid'];
    $_SESSION['isCompany'] = true;
    header('Location: /sdp-assignment/');
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
  <link rel="stylesheet" href="/sdp-assignment/Login_SignUp/companylogsin.css" />
  <link rel="stylesheet" href="" />
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
  <div class="orgwrapper">
    <form action="" method="POST">
      <img src="../Img/CSSTopia.png" alt="" />
      <h1>Company Login</h1>
      <div class="inputBox">
        <input name="username" type="text" placeholder="Company Username" required />
        <i class="bx bxs-user"></i>
      </div>
      <div class="inputBox">
        <input name="password" type="password" placeholder="Password" required />
        <i class="bx bxs-lock-alt"></i>
      </div>
      <div id="organizer">
        <p style="margin-top:10px;">
          Login as a User
          <a href="/sdp-assignment/Login_SignUp/login.php" style="color: #589cfc; font-size: 1.2em">Login Now</a>
        </p>
      </div>
      <button type="submit" class="btn">Login</button>
      <div class="register">
        <p>
          Don't have an Company account?
          <a href="/sdp-assignment/Login_SignUp/orgregister.php">Register</a>
        </p>
      </div>
    </form>
  </div>
</body>

</html>
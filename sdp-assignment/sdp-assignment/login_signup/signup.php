<?php
require_once '../Includes/dbConnection.php';
session_start();

if (isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password'])) {
  $email = $_POST['email'];
  $username = $_POST['username'];
  $password = $_POST['password']; // Store the password as plaintext

  // Check if the username or email already exists
  $sql = "SELECT * FROM user WHERE username='$username' OR email='$email'";
  $result = execSql($sql);

  if ($result->num_rows > 0) {
    echo "<script>alert('Username or Email already exists')</script>";
  } else {
    // Insert the new user into the database
    $sql = "INSERT INTO user (email, username, password) VALUES ('$email', '$username', '$password')";
    if (execSql($sql)) {
      echo "<script>alert('Registration Successful')</script>";
      header('Location: /sdp-assignment/Login_SignUp/login.php');
    } else {
      echo "<script>alert('Error: Could not register')</script>";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up</title>
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
      <h1>User Sign Up</h1>
      <div class="inputBox">
        <input type="email" name="email" placeholder="Email" required />
        <i class="bx bx-envelope"></i>
      </div>
      <div class="inputBox">
        <input type="text" name="username" placeholder="Username" required />
        <i class="bx bxs-user"></i>
      </div>
      <div class="inputBox">
        <input type="password" name="password" placeholder="Password" required />
        <i class="bx bxs-lock-alt"></i>
      </div>
      <div id="organizer">
        <p>
          Register as Company
          <a href="/sdp-assignment/Login_SignUp/orgregister.php" style="color: rgb(0, 135, 232); font-size: 1.2em">Register</a>
        </p>
      </div>
      <button type="submit" class="btn">Sign Up</button>
      <div class="register">
        <p>Already have an account? <a href="/sdp-assignment/Login_SignUp/login.php">Login Now</a></p>
      </div>
    </form>
  </div>
</body>

</html>
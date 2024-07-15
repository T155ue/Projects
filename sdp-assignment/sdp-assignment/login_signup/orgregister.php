<?php
require_once '../Includes/dbConnection.php';
session_start();

// Check if all required fields are set
if (
  isset($_POST['name']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']) &&
  isset($_POST['biography']) && isset($_POST['birthday']) && isset($_POST['state']) && isset($_POST['phone']) &&
  isset($_POST['twitter']) && isset($_POST['facebook']) && isset($_POST['linkedin']) && isset($_POST['instagram']) &&
  isset($_POST['image']) && isset($_POST['company_reqdescription'])
) {

  $name = $_POST['name'];
  $username = $_POST['username'];
  $password =  $_POST['password'];
  $email =  $_POST['email'];
  $biography =  $_POST['biography'];
  $birthday =  $_POST['birthday'];
  $state =  $_POST['state'];
  $phone =  $_POST['phone'];
  $twitter =  $_POST['twitter'];
  $facebook =  $_POST['facebook'];
  $linkedin =  $_POST['linkedin'];
  $instagram =  $_POST['instagram'];
  $image =  $_POST['image'];
  $company_reqdescription =  $_POST['company_reqdescription'];

  // Check if the username or email already exists
  $sql_check = "SELECT * FROM companyrequest WHERE name='$name' OR username='$username'";
  $result_check = execSql($sql_check);

  if ($result_check->num_rows > 0) {
    echo "<script>alert('Company Name or Username already exists')</script>";
  } else {
    // Insert data into companyrequest table
    $sql_insert = "INSERT INTO companyrequest (name, username, password, email, biography, birthday, state, phone, twitter, facebook, linkedin, instagram, image, company_reqdescription) 
                   VALUES ('$name', '$username', '$password', '$email', '$biography', '$birthday', '$state', '$phone', 
                   '$twitter', '$facebook', '$linkedin', '$instagram', '$image', '$company_reqdescription')";

    if (execSql($sql_insert)) {
      echo "<script>alert('Company Registration Successful')</script>";
      header('Location: /sdp-assignment/index.php');
      exit;
    } else {
      echo "<script>alert('Error: Could not register company')</script>";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Company Registration</title>
  <link rel="stylesheet" href="/sdp-assignment/Login_SignUp/companylogsin.css" />
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
      <h1>Company Registration</h1>
      <div class="input-group">
        <div class="inputBox">
          <input type="text" name="name" placeholder="Company Name" required />
          <i class='bx bxs-buildings'></i>
        </div>
        <div class="inputBox">
          <input type="text" name="username" placeholder="Username" required />
          <i class="bx bxs-user"></i>
        </div>
        <div class="inputBox">
          <input type="password" name="password" placeholder="Password" required />
          <i class="bx bxs-lock-alt"></i>
        </div>
        <div class="inputBox">
          <input type="email" name="email" placeholder="Email" required />
          <i class='bx bxs-envelope'></i>
        </div>
        <div class="inputBox">
          <textarea name="biography" placeholder="Biography" required></textarea>
        </div>
        <div class="inputBox">
          <input type="date" name="birthday" placeholder="Birthday" required />
          <i class='bx bxs-calendar'></i>
        </div>
        <div class="inputBox">
          <input type="text" name="state" placeholder="State" required />
          <i class='bx bxs-map'></i>
        </div>
        <div class="inputBox">
          <input type="text" name="phone" placeholder="Phone" required />
          <i class='bx bxs-phone'></i>
        </div>
        <div class="inputBox">
          <input type="text" name="twitter" placeholder="Twitter" required />
          <i class='bx bxl-twitter'></i>
        </div>
        <div class="inputBox">
          <input type="text" name="facebook" placeholder="Facebook" required />
          <i class='bx bxl-facebook'></i>
        </div>
        <div class="inputBox">
          <input type="text" name="linkedin" placeholder="LinkedIn" required />
          <i class='bx bxl-linkedin'></i>
        </div>
        <div class="inputBox">
          <input type="text" name="instagram" placeholder="Instagram" required />
          <i class='bx bxl-instagram'></i>
        </div>
        <div class="inputBox">
          <input type="text" name="image" placeholder="Image Link" required />
          <i class='bx bxs-image'></i>
        </div>
        <div class="inputBox">
          <textarea name="company_reqdescription" placeholder="Company Request Description" required></textarea>
        </div>
      </div>
      <div id="organizer">
        <p style="margin-top:10px">
          Sign Up as a User
          <a href="/sdp-assignment/Login_SignUp/signup.php" style="color: rgb(0, 135, 232); font-size: 1.2em">Sign Up</a>
        </p>
      </div>
      <button type="submit" class="btn">Register</button>
      <div class="register">
        <p>
          Already have a company account?
          <a href="/sdp-assignment/Login_SignUp/orglogin.php">Login Now</a>
        </p>
      </div>
    </form>
  </div>
</body>

</html>
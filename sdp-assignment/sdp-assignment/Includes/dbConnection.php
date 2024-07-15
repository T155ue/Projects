<?php


// Create connection


// Check connection


function execSql($sql)
{
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "sdp_db";
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  $result = mysqli_query($conn, $sql);
  $conn->close();
  return $result;
}

function getConn()
{
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "sdp_db";
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  return $conn;
}
/* 
-- example code for using sql query --

$sql = "SELECT * FROM user"; -> direct input sql code
$result = mysqli_query($conn,$sql); -> execute, and store the result in $result

if(mysqli_num_rows($result)>0){
  while($row = mysqli_fetch_assoc($result)){
    echo "id: " . $row["userid"]. " - Name: " . $row["username"]." - Realname: ". $row["user_realname"]. "<br>";
  }}; */


<?php
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
function get_connection()
{
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "basicCTF";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
return $conn;
}
function gethash($data)
{
  $hash=hash("sha256",$data);
  //Generate a Hash for the Input
  return $hash;
}
?>

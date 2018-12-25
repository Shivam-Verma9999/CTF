<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"]))
{
  $flag=1;
if(!isset($_POST["username"]) || $_POST["username"] =="")
{$uerr="USERNAME CANNOT BE EMPTY";$flag=0;
}
if(!isset($_POST["pass"]) || $_POST["pass"] == "")
{
  $perr="PASSWORD CANNOT BE EMPTY";
  $flag=0;
}
$uname=test_input($_POST["username"]);
$pass=test_input($_POST["pass"]);
$uid=getUser($uname);
if($uid ==-1)
{
  $uerr="INVALID USERNAME";
}
else
{
$pass=verify_pass($uid,$pass);
if($pass)
{
  //Valid User LOGIN AND SET THE COOKIE
  $_SESSION["id"]=$uid;
}
else {
  $perr="INVALID PASSWORD";
}
}
}//End of Login
?>

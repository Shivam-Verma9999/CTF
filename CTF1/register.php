<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"]))
{
//Check If UserName Already Taken
  $flag=1;//Everything is OKAY
  $runame=test_input($_POST["username"]);
  if($runame==""||$runame==NULL)
  {
    $flag=0;
    $ruerr="USERNAME CANNOT BE EMPTY";
  }
  $remail=test_input($_POST["email"]);
  if($remail=="" || $remail==NULL)
  {
    $flag=0;
    $emerr="EMAIL CANNOT BE EMPTY";
  }
  else if (!filter_var($remail, FILTER_VALIDATE_EMAIL)) {
    $flag=0;
    $emerr = "INVALID EMAIL FORMAT";
  }
  $pass=test_input($_POST["pass"]);
  if($pass=="" || $pass==NULL)
  {
    $flag=0;
    $perr="PASSWORD CANNOT BE BLANK";
  }
  $cpass=test_input($_POST["cpass"]);
  if($cpass != $pass)
  {
    $perr="ENTER THE PASSWORD AGAIN";
    $cperr="PASSWORD DONNOT MATCH";
    $flag=0;
    $pass="";
  }
  $uid=getUser($runame);//Check UserName is already taken
  if($uid!=-1)
  {
    $ruerr="USERNAME ALREADY EXIST";
    $flag=0;
  }
  $uid=getEmail($remail);//Check if the User is Already Registered
  if($uid !=-1)
  {
    $emerr="EMAIL ALREADY TAKEN";
    $flag=0;
  }
  //If Everything is Okay add the User to the DataBase
  if($flag)
  {
    $r=adduser($runame,$remail,$pass);//ADD TO DATABASE
    if($r)
    $cperr="USER REGISTRATION SUCCESSFUL";
    else {
      $cperr="ERROR WHILE ADDING THE USER";
    }
  }
}
 ?>

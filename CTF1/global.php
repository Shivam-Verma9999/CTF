<?php
include 'userfunctions.php';
include 'database.php';
include 'ctffunction.php';
include 'loadquestion.php';
include 'load.php';
session_start();
//Initalize
$uerr="";
$ruerr="";
$perr="";
$rperr="";
$cperr="";
$emerr="";
$uname=$pass=$cpass=$email="";
$runame="";
//Ans Verification
include 'post.php';
//Login
include 'login.php';
//Register
include 'register.php';
?>

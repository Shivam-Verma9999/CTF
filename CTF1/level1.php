<?php include 'global.php';?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
  <meta name="description" content="A CTF Website for Novices">
  <meta name="keywords" content="Web Exploitation,Forensics,Reverse Engineering ,Cryptography">
  <meta name="author" content="Aman Anand">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="./css/bootstrap.min.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <title>Novictf</title>
</head>
<body>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
     <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
       <span class="sr-only">Toggle navigation</span>
       <span class="icon-bar"></span>
       <span class="icon-bar"></span>
       <span class="icon-bar"></span>
     </button>
     <a class="navbar-brand" href="#">Novictf</a>
   </div>

   <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
         <li class="active"><a href="#"> Home <span class="sr-only">(current)</span></a></li>
        <li><a href="leaderboard.php">Leaderboard</a></li>
      </ul>
<?php
if(isset($_SESSION["id"]))    {?>
    <ul class="nav navbar-nav navbar-right">
     <li><a href="logout.php">Logout</a></li>
   </ul>
   <div class="form-group">
   <ul class="nav navbar-nav navbar-right">
    <li><a href="#">Your Score is  <?php $score=getscore($_SESSION["id"]); echo " ".$score;?></a></li>
  </ul>
  </div>
  <?php } ?>
</div>
</div>
</nav>
<?php

if(isset($_SESSION["id"]))
{
  display();
 
}
?>

 </body>
 </html>

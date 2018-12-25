<?php
function getUser($username)
{
  $username=test_input($username);
  $conn=get_connection();//Get Database connection
  $sql="SELECT `uid` FROM `user` WHERE `username`='$username'";
  $result = $conn->query($sql);
  $conn->close();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    return $row["uid"];
    }
else {
    return -1;
}
}
function verify_pass($uid,$password)
{
  $uid=test_input($uid);
  $password=gethash(test_input($password));
  $conn=get_connection();//Get Database connection
  $sql="SELECT `uid` FROM `user` WHERE `uid`='$uid' and `hash`='$password'";
  $result = $conn->query($sql);
$conn->close();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    return 1;
    }
  else {
    return 0;
  }


}

//Get Score for the USERNAME
function getscore($uid)
{
  $uid=test_input($uid);
  $conn=get_connection();
  $sql ="SELECT `score` FROM `user` WHERE `uid`='$uid'";
  $result=$conn->query($sql);
$conn->close();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $r=$row["score"];
    return $r;
    }
  else {
    return -1;
  }
}
function getEmail($email)
{
  $email=test_input($email);
  $conn=get_connection();
  $sql="SELECT `uid` FROM `user` WHERE `email`='$email'";
  $result=$conn->query($sql);
  $conn->close();
  if($result->num_rows >0)
  {
    return $row["uid"];
  }
  else {
    return -1;
  }
}
function adduser($uname,$email,$pass)
{
  $uname=test_input($uname);
  $email=test_input($email);
  $pass=gethash(test_input($pass));
  $conn=get_connection();
  $sql = "INSERT INTO `user` (`username`,`hash`,`email`) VALUES ('$uname','$pass','$email')";
  if(($result=$conn->query($sql))==TRUE)
  {
    $conn->close();
    return 1;
  }
  else {
    $conn->close();
    return 0;
  }
}
// Login form
function get_loginform()
{
?>
<h3 "text-center">Login </h3>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
  <div class="form-group">
  <input class="form-control" type="text" placeholder="USERNAME" name="username" value="<?php if(isset($uname)) echo $uname; ?>"
  />
  <label style="color:red;"><?php  echo $uerr; ?> </label>
</div><div class="form-group">
  <input class="form-control" type="password" placeholder="PASSWORD" name="pass"/>
  <label style="color:red;"><?php  echo $perr; ?> </label>
</div><div class="form-group">
<input class="btn btn-primary btn-block" type="submit" name="login" value="Login"/>
</div>
</form><!--LOGIN FORM -->
</div><!--Login-->
<?php
}
function get_registerform()
{
?>
<h3 "text-center">Register   </h3>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
  <div class="form-group">
  <input class="form-control" type="text" placeholder="USERNAME" name="username" value="<?php if(isset($runame)) echo $runame; ?>"/>
  <label style="color:red;"><?php  echo  $ruerr; ?> </label>
</div>
<div class="form-group">
<input class="form-control" type="email" placeholder="EMAIL" name="email"value="<?php if(isset($remail)) echo $remail; ?>"/>
<label style="color:red;"><?php  echo $emerr; ?> </label>
</div>
<div class="form-group">
  <input class="form-control" type="password" placeholder="PASSWORD" name="pass"/>
  <label style="color:red;"><?php  echo $rperr; ?> </label>
</div>
<div class="form-group">
  <input class="form-control" type="password" placeholder="CONFIRM PASSWORD" name="cpass"/>
  <label style="color:red;"><?php  echo $cperr; ?> </label>
</div>
<div class="form-group">
<input class="btn btn-primary btn-block" type="submit" name="register" value="Register"/>
</div><!--Register -->
</form>
<?php
}
?>

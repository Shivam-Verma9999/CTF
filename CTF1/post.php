<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Submit"]))
{
  $uid=test_input($_SESSION["id"]);
  $qid=test_input($_POST["qid"]);
  $flag=test_input($_POST["flag"]);
  $result=verify($qid,$flag);
  if($result==1)
  {
    questinanswered($uid,$qid);
    $emerr="WOW YOU GOT IT RIGHT";
  }
  else {
    $emerr="INVALID FLAG";
  }
}
?>

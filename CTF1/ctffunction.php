<?php
function alreadyans($u,$q)
{
  $conn=get_connection();
  $u=test_input($u);
  $q=test_input($q);
  $sql="SELECT `uid` from `uidqid` where `uid`='$u' and `qid`='$q'";
  $result=$conn->query($sql);
  $conn->close();
  if($result->num_rows >0)
  return 1;
  else {
    return 0;
  }

}
function verify($qid,$flag)
{
  $conn=get_connection();
  $qid=test_input($qid);
  $flag=gethash(test_input($flag));
  $sql="SELECT `qid` FROM `question` Where `qid`='$qid' and `correctflag`='$flag'";
  $result=$conn->query($sql);
  $conn->close();
  if($result->num_rows >0)
  return 1;
  else {
     return 0;
  }
}
function questinanswered($uid,$qid)
{
    $conn=get_connection();
    $uid=test_input($uid);
    $qid=test_input($qid);
    $sql="SELECT `uid` from `uidqid` where `uid`='$uid' and `qid`='$qid'";
    $result=$conn->query($sql);
    if($result->num_rows ==0)
    {
    $sql="INSERT INTO `uidqid`(`uid`, `qid`) VALUES ('$uid','$qid')";
    $result=$conn->query($sql);
    $sql="SELECT `score` from `question` where `qid`='$qid'";
	//echo $sql;
    $result=$conn->query($sql);
	//echo $result;
    $row = $result->fetch_assoc();
    $score=$row["score"];
    $sql="UPDATE `user` SET `score`=`score`+'$score' WHERE `uid`='$uid'";
    $result=$conn->query($sql);
    }
    $conn->close();
    return;
  }
?>

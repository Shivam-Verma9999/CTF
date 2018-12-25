

<?php

function display()
	{
$xml=simplexml_load_file("question.xml") or die("Error: Cannot create object");
?>
	<center><h2 style="color:teal;">LEVEL 1</h2></center>
	
	


<?php
foreach($xml->children() as $q)
{
  $qid=$q->qid;
  $title=$q->title;
  $data=$q->data;
  $score=$q->score;
  $category=$q->category;
 

//For div and style
?>


<div class="container  col-sm-6" style="margin-top: 5px;margin-bottom: 5 px;">


<?php	$f=alreadyans($_SESSION["id"],$qid);
if(!$f)
{
	?><div class="panel panel-default">
  <div class="panel-heading" style="background:#54b4eb;color:white;"><center><?php echo $category." (".$score.")"?></center></div>
  <div class="panel-body " style="background:#e3f2fd ;" ><center>
  <button type="button" class="btn btn-warning btn-md" data-toggle="modal" data-target="#<?php echo $qid;?>"><?php echo $title;?></button>
  </center>
  </div>
</div>
	
<?php }else{
?>
<div class="panel panel-default">
  <div class="panel-heading" style="background:#54b4eb;color:white;"><center><?php echo $category." (".$score.")"?></center></div>
  <div class="panel-body "style="background:#e3f2fd ;"><center>
  <button type="button" class="btn btn-success btn-md " data-toggle="modal" data-target="#<?php echo $qid;?>"><?php echo $title;?></button></center>
  </div>
</div>
<?php }?>
	
 
 <div id="<?php echo $qid;?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo $title;?></h4>
      </div>
      <div class="modal-body">
        <p class="text-center" style="word-wrap:break-word;"><?php echo $data."<br>";?></p>
      </div>
	  <?php
$f=alreadyans($_SESSION["id"],$qid);
if(!$f)
{
  ?>
      <div class="modal-footer">
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
<input class="form-control" type="text" placeholder="Enter the Flag Here" name="flag"/>
<input type="hidden" value="<?php echo $qid;?>" name="qid"/>
<input type="submit" class="btn  btn-block" value="Submit" name="Submit"/>
</form>
<?php
}
else {
  ?>
  <div class="alert alert-success">
    <strong> Correct Answer Submitted</strong>
  </div>
<?php
}
?>       

	   <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

</div>
<?php
}
 }
?>




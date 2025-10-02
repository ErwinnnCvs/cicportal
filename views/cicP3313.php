<?php

$conn = mysqli_connect("10.250.111.80", "evaluser", 'xc&m9rSCkuXY2', "evaluation");

 
        // Check connection
        if (mysqli_connect_errno())
          {
          echo "Failed to connect to MySQL: " . mysqli_connect_error();
          }
 
  $trainingdateone=$_POST['trainingdateone'];
  $firsttrainer=$_POST['firsttrainer'];
  $secondtrainer=$_POST['secondtrainer'];
  
 
  mysqli_query($conn,"insert into `trainer` (trainingdateone,firsttrainer,secondtrainer) values ('$trainingdateone','$firsttrainer','$secondtrainer')");
  //header('location:cicP3310.php');
  //echo "<script>window.location.href='cicP3310.php';</script>";
  //echo "<script>window.location.href='main.php?nid=33&sid=1&rid=0';</script>";
  echo "<script>window.location.href='../main.php?nid=33&sid=1&rid=0';</script>";
  exit;
 
?>



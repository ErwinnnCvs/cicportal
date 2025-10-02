<?php

//import.php

if(isset($_POST["trainingdateone"]))
{
 $connect = new PDO("mysql:host=10.250.111.80;dbname=evaluation", "evaluser", "xc&m9rSCkuXY2");
 $trainingdateone = $_POST["trainingdateone"];
 $firsttrainer = $_POST["firsttrainer"];
 $secondtrainer = $_POST["secondtrainer"];
 for($count = 0; $count < count($trainingdateone); $count++)
 {
  $query .= "
  INSERT INTO trainer(trainingdateone, firsttrainer, secondtrainer) 
  VALUES ('".$trainingdateone[$count]."', '".$firsttrainer[$count]."', '".$secondtrainer[$count]."');
  
  ";
 }
 $statement = $connect->prepare($query);
 $statement->execute();
}

?>

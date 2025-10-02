<?php

//fetch.php

if(!empty($_FILES['csv_file']['name']))
{
 $file_data = fopen($_FILES['csv_file']['tmp_name'], 'r');
 //$column = fgetcsv($file_data);
 while($row = fgetcsv($file_data))
 {
  $row_data[] = array(
   'trainingdateone'  => $row[0],
   'firsttrainer'  => $row[1],
   'secondtrainer'  => $row[2]
  );
 }
 $output = array(
  'column'  => [],
  'row_data'  => $row_data
 );

 echo json_encode($output);

}

?>
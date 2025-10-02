<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Untitled</title>
</head>
<body>

		
		
<?php
// echo date("Y-m-d H:i:s", 1518336000);
// echo '</br>gil';
// echo date("Y-m-d H:i:s");
$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
$txt = "John Doe\n";
fwrite($myfile, $txt);
$txt = "Jane Doe\n";
fwrite($myfile, $txt);
fclose($myfile);
?>
</body>
</html>

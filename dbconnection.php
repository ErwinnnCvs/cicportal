 <!-- <?php
$servername = "10.250.111.80";
$username = "newportal80";
$password = "cR3d1tInf0rm4t!on80";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?> 
 -->

<?php
$con = mysqli_connect("10.250.106.33","newportal","cR3d1tInf0rm4t!on","cicportal");

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
} else {
	echo "Connected successfully";
}
?> 

<!-- CREATE USER 'newportal80'@'10.250.106.27' IDENTIFIED BY 'cR3d1tInf0rm4t!on80';

GRANT ALL PRIVILEGES ON * . * TO 'newportal80'@'10.250.106.27';

FLUSH PRIVILEGES; 

CREATE USER 'newportal80' IDENTIFIED BY 'cR3d1tInf0rm4t!on80';

GRANT USAGE ON *.* TO 'newportal80'@'10.250.106.27' IDENTIFIED BY 'cR3d1tInf0rm4t!on80';

GRANT ALL privileges ON *.* TO 'newportal80'@'10.250.106.27';

FLUSH PRIVILEGES;


-->
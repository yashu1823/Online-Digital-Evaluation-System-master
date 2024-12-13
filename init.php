<?php

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "odes";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql="SELECT DISTINCT Name,CourseId FROM courses where Sem=1 and BranchId='{$branchid}'";

$result = $conn->query($sql);

	if($result->num_rows >0){
		while($row = $result->fetch_assoc()) {
			echo '<option value="'.$row["CourseId"].'">'.$row["Name"].'</option>';
		}
		echo "<option value=\"All\">All</option>";
	}else{
	 	echo "0 results";
	
	}
    

$conn->close();



?>
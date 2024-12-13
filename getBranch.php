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
//$factId=$_SESSION["FactId"];
$factId='f29';

//SELECT * FROM `branches` WHERE `HoD`=$factId;
$sql="SELECT * FROM `branches` WHERE `HoD`={$factId}";
$result = $conn->query($sql);
if ($result->num_rows > 0){
if($row = $result->fetch_assoc())
{
	$branchid =$row['BranchId'];
}
}else {echo "0 results";}
	

        	

//$conn->close();

?>
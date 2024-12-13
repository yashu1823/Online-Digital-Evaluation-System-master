<?php

	//fetch hidid from post
	$hidid = $_POST['hidid'];
	//$hidid = 'hid1';
	//create and check connection
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "odes";

	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	$str = '';
	//fetch imgsrcs 
	$result = $conn->query("SELECT * FROM `ansscripts` WHERE `HidId`='{$hidid}'");
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$str = $str . $row['ImagePath'] . ",";
		}
	} else {
		echo "0 results";
	}
	
	$str = substr($str,0,-1);
	
	echo $str;


?>
<?php
	
	
	
	
	//fetch session var: FactId
	//$HidId = $_SESSION['HidId'];

	
	//fetch from db and set session vars:QpId, studid
	
	//create and check connection
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "odes";

	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	?>
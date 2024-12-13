<?php
	//this php preloads the marks and remarks from the db, if any
	//session_start();
	
	//fetch session vars
	$studid = $_SESSION['studid'];
	$QpId = $_SESSION['QpId'];
	
	//create idData for the table
	//idDataM[qid]=marksTBid;
	//idDataR[qid]=remarksTBid;
	
	//fetch ids from hidden TB: 
	//$courseid = $_POST['courseid'];
	
	echo "<script>console.log(\"entered preloadMarks.php\");</script>";
	
	$marksids = explode('&',$_POST['marksids']);
	$remarksids = explode('&',$_POST['remarksids']);
	
	//assign ids
	foreach( $marksids as $marksTBid ){
		$marksTBid = trim($marksTBid);
		$temp = explode('_',$marksTBid);
		$qid = substr($temp[3],3);
		$idDataM[$qid]=$marksTBid;
	}
	
	//assign ids
	foreach( $remarksids as $remarksTBid ){
		$remarksTBid = trim($remarksTBid);
		$temp = explode('_',$remarksTBid);
		$qid = substr($temp[3],3);
		$idDataR[$qid]=$remarksTBid;
	}
	
	//now get data from marks table
	
	//create and check connection
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "odes";

	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	//update all non -1 marks
	$result = $conn->query("SELECT * FROM `marks` WHERE `StudId`='{$studid}' AND `QpId`='{$QpId}' AND `MarksPerQ`!='-1'");
	if ($result->num_rows > 0) {
		echo "<script>";
		while($row = $result->fetch_assoc()) {
			//echo "\$('#{idDataM[$row['QId']]}').val({$row['MarksPerQ']})";
		}
		echo "</script>";
	} else {
		echo "0 results";
	}
	
	//update all non empty marks
	$result = $conn->query("SELECT * FROM `marks` WHERE `StudId`='{$studid}' AND `QpId`='{$QpId}' AND `Remarks`!=''");
	if ($result->num_rows > 0) {
		echo "<script>";
		while($row = $result->fetch_assoc()) {
		//	echo "\$('#{idDataR[$row['QId']]}').val({$row['Remarks']})";
		}
		echo "</script>";
	} else {
		echo "0 results";
	}






?>
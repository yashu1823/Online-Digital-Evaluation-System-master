<?php

	session_start();
	
	//get session vars required
	$studid = $_SESSION['studid'];
	$QpId = $_SESSION['QpId'];
	/**************************************************************************************/
	
	//create and check connection
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "odes";

	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	
	
	//fetch courseid,marksids and remarksids from hidden text-fields
	$courseid = $_POST['courseid'];
	$marksids = explode('&',$_POST['marksids']);
	$remarksids = explode('&',$_POST['remarksids']);
	
	//update marks in db
	foreach($marksids as $marksTBid){
		$marksTBid = trim($marksTBid);
		$idData = explode('_',$marksTBid);
		$qno = 	substr($idData[1],1);
		$sqno = substr($idData[2],2);
		$qid = substr($idData[3],3);
		
		if($_POST[$marksTBid] == ''){
			//do nothing
		} else {
			//update in db 
			if ($conn->query("UPDATE `marks` SET `MarksPerQ`='{$_POST[$marksTBid]}' WHERE `StudId`='{$studid}' AND `QpId`='{$QpId}' AND `QId`='{$qid}';") === TRUE) {
				//ok
				//echo "{$marksTBid}:{$_POST[$marksTBid]}";
			} else {
				echo "Error updating record: " . $conn->error;
			}
		}
		
	}
	
	//update remarks in db
	foreach($remarksids as $remarksTBid){
		$remarksTBid = trim($remarksTBid);
		$idData = explode('_',$remarksTBid);
		$qno = 	substr($idData[1],1);
		$sqno = substr($idData[2],2);
		$qid = substr($idData[3],3);
		
		//if($_POST[$remarksTBid] == ''){
			//do nothing
		//} else {
			//update in db 
			if ($conn->query("UPDATE `marks` SET `Remarks`='{$_POST[$remarksTBid]}' WHERE `StudId`='{$studid}' AND `QpId`='{$QpId}' AND `QId`='{$qid}';") === TRUE) {
				//ok
			} else {
				echo "Error updating record: " . $conn->error;
			}
		//}
		
	}
	
	$conn->close();
	
	//assign session vars and redirect page to HomePage of evaluator
	
	/*assign here***************************************************************************/
	
	$url = 'selectionpage.php';
	if (headers_sent()){
		die('<script type="text/javascript">window.location.href="' . $url . '";</script>');
	}else{
		header('Location: selectionpage.php');
		die();
	}













?>
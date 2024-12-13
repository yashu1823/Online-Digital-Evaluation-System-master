<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "odes";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn -> connect_error) {
	die("Connection failed: ".$conn -> connect_error);
}
$sem = $_GET["sem"];
$sec = $_GET["sec"];
$course = $_GET["course"];
$branchid = $_GET["branch"];

//echo $sem.",".$sec.",".$course.",";
//$sem=1;$sec='a';$course='CS120';$branchid='cse';
//$echo $sec;

if ($sec == "All") {
	/*To find no of students from that sem and sec*/
	$sql1 = "SELECT StudId FROM students where Sem='{$sem}' and BranchId = '{$branchid}'";
	//SELECT StudId FROM students where Sem='{$sem}' and BranchId = '{$branchid}'
	$result1 = $conn -> query($sql1);
	while ($row1 = $result1 -> fetch_assoc()) {
		$studIds[] = $row1['StudId'];

	}
	$studentCount = sizeof($studIds);
	//echo "<br>".$studentCount."<br>";

	/*To find no of quests from the qPaper*/
	/*$sql2='SELECT count(*) FROM qpapers WHERE CourseId='."'$course'";
	$result2 = $conn->query($sql2);
	$row2 = $result2->fetch_assoc();
	$questCount=$row2["count(*)"];*/
	//echo $questCount;

	/*Total no of questions */

	/*$TotQuests=$studentCount*$questCount;*/
	//echo $TotQuests;


	/*To find no of corrected questions*/
	/*$sql3='SELECT count(*) FROM marks WHERE CourseId='."'$course'".' and MarksPerQ>-1';
	$result3 = $conn->query($sql3);
	$row3 = $result3->fetch_assoc();
	$corrected=$row3["count(*)"];*/
	//echo $corrected;

	/*$uncorrected=$TotQuests-$corrected;*/


	//echo json_encode($data);




	//echo '{"corrected":'.$corrected.',"uncorrected":'.$uncorrected.'}';
	$corrected = 0;
	$uncorrected = 0;
	
	
	if($course != "All"){
		foreach($studIds as $singlestud) {
			$resultt = $conn -> query("SELECT count(*) from marks where StudId = '{$singlestud}' and CourseId = '{$course}' and MarksPerQ>-1");
			$rowt = $resultt -> fetch_assoc();
			$corrected += $rowt['count(*)'];

			$resultt = $conn -> query("SELECT count(*) from marks where StudId = '{$singlestud}' and CourseId = '{$course}' and MarksPerQ=-1");
			$rowt = $resultt -> fetch_assoc();
			$uncorrected += $rowt['count(*)'];
		}
	} elseif ($course == "All"){
		$result = $conn -> query ("SELECT CourseId from courses where Sem = '{$sem}' and BranchId = '{$branchid}'");
		while($row = $result -> fetch_assoc()){
			$courses[] = $row['CourseId'];
		}
		foreach($studIds as $singlestud) {
			foreach($courses as $c){
			$resultt = $conn -> query("SELECT count(*) from marks where StudId = '{$singlestud}' and CourseId = '{$c}' and MarksPerQ>-1");
			$rowt = $resultt -> fetch_assoc();
			$corrected += $rowt['count(*)'];

			$resultt = $conn -> query("SELECT count(*) from marks where StudId = '{$singlestud}' and CourseId = '{$c}' and MarksPerQ=-1");
			$rowt = $resultt -> fetch_assoc();
			$uncorrected += $rowt['count(*)'];
			}
		}
	}


	echo $corrected.
	",".$uncorrected;

} else {

	/*To find no of students from that sem and sec*/
	$sql1 = 'SELECT StudId FROM students where Sem=\''.$sem.'\' and SecId='."'$sec'".' and BranchId='."'$branchid'";
	$result1 = $conn -> query($sql1);
	while ($row1 = $result1 -> fetch_assoc()) {
		$studIds[] = $row1['StudId'];

	}
	$studentCount = sizeof($studIds);
	//echo "<br>".$studentCount."<br>";

	/*To find no of quests from the qPaper*/
	/*$sql2='SELECT count(*) FROM qpapers WHERE CourseId='."'$course'";
	$result2 = $conn->query($sql2);
	$row2 = $result2->fetch_assoc();
	$questCount=$row2["count(*)"];*/
	//echo $questCount;

	/*Total no of questions */

	/*$TotQuests=$studentCount*$questCount;*/
	//echo $TotQuests;


	/*To find no of corrected questions*/
	/*$sql3='SELECT count(*) FROM marks WHERE CourseId='."'$course'".' and MarksPerQ>-1';
	$result3 = $conn->query($sql3);
	$row3 = $result3->fetch_assoc();
	$corrected=$row3["count(*)"];*/
	//echo $corrected;

	/*$uncorrected=$TotQuests-$corrected;*/


	//echo json_encode($data);




	//echo '{"corrected":'.$corrected.',"uncorrected":'.$uncorrected.'}';
	$corrected = 0;
	$uncorrected = 0;
	
	if($course != "All"){
		foreach($studIds as $singlestud) {
			$resultt = $conn -> query("SELECT count(*) from marks where StudId = '{$singlestud}' and CourseId = '{$course}' and MarksPerQ>-1");
			$rowt = $resultt -> fetch_assoc();
			$corrected += $rowt['count(*)'];

			$resultt = $conn -> query("SELECT count(*) from marks where StudId = '{$singlestud}' and CourseId = '{$course}' and MarksPerQ=-1");
			$rowt = $resultt -> fetch_assoc();
			$uncorrected += $rowt['count(*)'];
		}
	}elseif ($course == "All"){
		$result = $conn -> query ("SELECT CourseId from courses where Sem = '{$sem}' and BranchId = '{$branchid}'");
		while($row = $result -> fetch_assoc()){
			$courses[] = $row['CourseId'];
		}
		foreach($studIds as $singlestud) {
			foreach($courses as $c){
				$resultt = $conn -> query("SELECT count(*) from marks where StudId = '{$singlestud}' and CourseId = '{$c}' and MarksPerQ>-1");
				$rowt = $resultt -> fetch_assoc();
				$corrected += $rowt['count(*)'];

				$resultt = $conn -> query("SELECT count(*) from marks where StudId = '{$singlestud}' and CourseId = '{$c}' and MarksPerQ=-1");
				$rowt = $resultt -> fetch_assoc();
				$uncorrected += $rowt['count(*)'];
			}
		}
	}


	echo $corrected.
	",".$uncorrected;


}

/*if($result1->num_rows >0){
		 while($row = $result1->fetch_assoc()) 
	
	 echo $row["count(*)"];
}*/


/* else
	 	{	
	 		echo " 0 results";

  		}*/


$conn -> close();


?>

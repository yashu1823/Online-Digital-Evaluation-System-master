
<?php 
include_once 'dbconfig.php';

console.log("loadcourse.php");
$sem=$_GET["sem"];
$sec= $_GET["sec"];
$FactId = $_GET["fac"];
echo '<option value="nil">Select Course</option>'; 
$sql="SELECT  distinct CourseId FROM `course_faculty` WHERE `CourseId` IN (SELECT `courses`.`CourseId` from courses WHERE `courses`.`Sem`='{$sem}') AND `FactId` = '{$FactId}' AND `SecId`='{$sec}'";
$result = $conn->query($sql);

	if($result->num_rows >0){
		
	 while($row = $result->fetch_assoc()) 


   	echo '<option value="'.$row["CourseId"].'">'.$row["CourseId"].'</option>';

	}

	else
	 	{	
	 		echo " 0 results";

  		}
        	

$conn->close();

?>
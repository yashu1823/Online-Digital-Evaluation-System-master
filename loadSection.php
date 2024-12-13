
<?php 
include_once 'dbconfig.php';

console.log("loadsection.php");
$sem = $_GET["sem"];
$FactId = $_GET["fac"];
echo '<option value="nil" >Select Section</option>'; 
$sql="SELECT * FROM `course_faculty` WHERE `CourseId` IN (SELECT `courses`.`CourseId` from courses WHERE `courses`.`Sem`='{$sem}') AND `FactId` = '{$FactId}'";
$result = $conn->query($sql);

	if($result->num_rows >0){
		
	 while($row = $result->fetch_assoc()) 

    	 
   	echo '<option value="'.$row["SecId"].'">'.$row["SecId"].'</option>';

	}

	else
	 	{	
	 		echo " 0 results";

  		}
        	

$conn->close();

?>
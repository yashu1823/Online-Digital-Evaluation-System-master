
<?php
include_once 'dbconfig.php';

echo '<input type="text" class="transparent" value="Corrected Answer-Scripts" readonly style="" />';

$sem = $_GET["sem"];
$sec = $_GET["sec"];
$FactId = $_GET["fac"];
$course = $_GET["course"];
$sql1 = "SELECT distinct HidId FROM `ansscripts` WHERE `CourseId`='{$course}' AND `SecId`='{$sec}'";
$result1 = $conn->query($sql1);

if ($result1->num_rows > 0)
	{
	$c = 0;
	$HidIds = array();
	while ($hidid = $result1->fetch_assoc())
		{
		$HidIds[] = $hidid['HidId'];
		}

	for ($i = 0; $i < sizeof($HidIds); $i++)
		{

		// id="HidIdButton'.$i.'"
		// $temp=	$HidIds[$i];
		// echo '<button   class="HidIdButton" >'.$temp.'</button>';

		$temp = $HidIds[$i];
		$sql2 = "SELECT * FROM `marks` WHERE HidId ='{$temp}'";
		$result2 = $conn->query($sql2);
		if ($result2->num_rows > 0)
			{
			$qno = 0;
			$correctedqno = 0;
			$percent = 0;
			while ($row = $result2->fetch_assoc())
				{
				if ($row["MarksPerQ"] == - 1)
					{ //for each row in result

					// if marksperq is -1
					//	qno++;
					// else correctedqno++; qno++;
					// percent= correctedqno/qno * 100;

					$qno++;
					}
				  else
					{
					$correctedqno++;
					$qno++;
					}

				
				}
				$percent = ($correctedqno / $qno) * 100;
				$tempPercent = "$HidIds[$i]";
				if($percent==100){
				echo '<a class="HidIdAnchor" href="">' . $tempPercent . '</a>';
				//echo '<br />';
			}
			}
		  else
			{
			echo " 0 results";
			}
		}
	}
  else
	{
	echo " 0 results";
	}

$conn->close();
?>
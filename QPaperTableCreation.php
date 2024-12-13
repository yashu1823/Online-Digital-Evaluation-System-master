<?php
	//get QpId from Session var
	$QpId = $_SESSION['QpId'];
	//$QpId="qp01";
	
	//create and check connection
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "odes";

	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	
	//get QNo,SubQNo,Statement and MaxMarks for all questions
	$result = $conn->query("SELECT `QNo`,`SubQNo`,`QStmt`,`MaxMarks` FROM `qpapers` WHERE `QpId`='$QpId'");
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$data[$row['QNo']][$row['SubQNo']] = array($row['QStmt'],$row['MaxMarks']);
		}
	} else {
		echo "0 results";
	}	


	//create table
		
	//header
	echo "<table>"
		. "<tr>"
		. "<th colspan='2'>Q. No.</th>"
		. "<th>Statement</th>"
		. "<th>Marks</th>"
		. "</tr>";

	//generate all rows
	foreach($data as $qno => $subqarr) {
		echo "<tr>"
			."<td rowspan='".sizeof($subqarr)."' > $qno </td>";
		
		$i = 1;
		foreach ($subqarr as $subqno => $arr) {
			if($i == 1){ //for first subquestion, shouldnt add <tr>
				echo "<td>$subqno</td>"
					."<td>$arr[0]</td>"
					."<td>$arr[1]</td>"
					."</tr>";
			} else {
				echo "<tr>"
					."<td>$subqno</td>"
					."<td>$arr[0]</td>"
					."<td>$arr[1]</td>"
					."</tr>";
			}
			$i++;
		}
		
	}
	
	echo "</table>";

?>
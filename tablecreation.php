<?php 
	//get session var for QpId
	$QpId = $_SESSION['QpId'];
	$studid = $_SESSION['studid'];
	
	//echo "studid:{$studid}<br>qpid:{$QpId}";
	
	//create and check connection
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "odes";

	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	
	$totalMaxMarks = 0;
	$courseid = "";
	$setter = "";
	
	$marksids="";
	$remarksids = "";
	
	
	//fetch maxmarks and other data from db
	$result = $conn->query("SELECT * FROM `qpapers` WHERE `QpId`='$QpId'");
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$marks[$row['QNo']][$row['SubQNo']] = $row['MaxMarks'];
			//$errFlags[$row['QNo']][$row['SubQNo']] = 0;
			$totalMaxMarks += $row['MaxMarks'];
			$courseid = $row['CourseId'];
			$setter = $row['Setter'];
		}
	} else {
		echo "1-0 results";
	}
	
	//fetch already entered marks and remarks
	$result = $conn->query("SELECT * FROM `marks` WHERE `StudId`='{$studid}' AND `QpId`='{$QpId}' AND `MarksPerQ`>='0'");
	if ($result->num_rows > 0) {
		
		while($row = $result->fetch_assoc()) {
			$preMarks[$row['QId']] = $row['MarksPerQ'];
		}
		
	} else {
		echo "";
	}
	$result = $conn->query("SELECT * FROM `marks` WHERE `StudId`='{$studid}' AND `QpId`='{$QpId}' AND `Remarks`!=''");
	if ($result->num_rows > 0) {
		
		while($row = $result->fetch_assoc()) {
			$preRemarks[$row['QId']] = $row['Remarks'];
		}
		
	} else {
		echo "";
	}
	
	
	
	
	
	
	//set totalMaxMarks in total-max-marks-text-field
	
	
	echo "<script>"
		."		totalMaxMarks = $totalMaxMarks;" 
		."</script>";
	
	// Number of questions: sizeof($marks)
	// Number of subquestions of question x : sizeof($marks['x'])
	
	//error maintenance
	/*
	function errCount() {
		$r = 0;
		foreach($errFlags as $a){
			foreach($a as $b){
				$r += $b;
			}
		}
		return $r;
	}*/
	
	
	/* unwanted data 
	echo "marks:<br>";
	foreach ($marks as $k2 => $q){
		foreach ($q as $k => $val){
			echo "$k2"."$k"."$val <br>";
		}
	}
	echo "<br>size:";
	echo sizeof($marks);
	echo "<br>ind sizes:<br>";
	foreach($marks as $q){
		echo sizeof($q);
		echo "<br>";
	}
	echo "<br>3rd ques: ",sizeof($marks['3']);
	echo "<br>";
	*/
	
	//create table 
	
	//table style, unwanted 
	/*
	echo "<style>"
		."table,tr,td,th {border: 1px solid black;text-align: center;}"
		."table input.marksTB {width:30px;}"
		."table input.remarksTB {width: 170px;}"
		."</style>";
	*/
	
	//keep courseid in a hidden text-field
	echo "<input type='text' name='courseid' style='display:none;' value='$courseid' />";
	
	//keep setter in a hidden text-field
	echo "<input type='text' name='setter' style='display:none;' value='$setter' />";
	
	
	
	//header row
	echo "<table>"
		. "<tr>"
		. "<th colspan='2'>Q. No.</th>"
		. "<th>Max</th>"
		. "<th>Marks</th>"
		. "<th>Remarks</th>"
		. "</tr>";
		
	
	//generate all rows
	foreach($marks as $qno => $subqarr) {
		echo "<tr>"
			."<td rowspan='".sizeof($subqarr)."' > $qno </td>";
		
		$i = 1;
		foreach ($subqarr as $subqno => $maxmarks) {
			
			//fetch qid for the question 
			$qid="";
			$result1 = $conn->query("SELECT `QId` FROM `qpapers` WHERE `QNo`='$qno' AND `SubQNo`='$subqno' AND `QpId`='$QpId'");
			if ($result1->num_rows > 0) {
				if($row = $result1->fetch_assoc()) {
					$qid = $row['QId'];
				}
			} else {
				echo "4-0 results";
			}
			
			$preMarksVal = '';
			$preRemarksVal = '';
			if(isset($preMarks[$qid])){
				$preMarksVal = $preMarks[$qid];
			}
			if(isset($preRemarks[$qid])){
				$preRemarksVal = $preRemarks[$qid];
			}
			
			if($i == 1){ //for first subquestion, shouldnt add <tr>
			
				echo "<td>$subqno</td>"
					."<td>$maxmarks</td>"
					."<td><input type='text' name='M_Q{$qno}_SQ{$subqno}_QID{$qid}' id='M_Q{$qno}_SQ{$subqno}_QID{$qid}' class='transparent marksTB enterBlur' width='25' onkeypress='if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;' value='{$preMarksVal}' /></td>"
					."<td><input type='text' name='R_Q{$qno}_SQ{$subqno}_QID{$qid}' id='R_Q{$qno}_SQ{$subqno}_QID{$qid}' class='transparent remarksTB enterBlur' width='170' value='{$preRemarksVal}' /></td>"
					."</tr>";
					
				//add ids to marksids and remarksids text-fields
				//echo "<script>"
				//	."	\$('#marksids').val(\$('#marksids').val()+\"M-Q$qno-SQ$subqno-QID$qid&\");"
				//	."	\$('#remarksids').val(\$('#remarksids').val()+\"R-Q$qno-SQ$subqno-QID$qid&\");"
				//	."</script>";
				
				$marksids = $marksids."M_Q{$qno}_SQ{$subqno}_QID{$qid}"."&";
				$remarksids = $remarksids."R_Q{$qno}_SQ{$subqno}_QID{$qid}"."&";
				
				//put the required jquery
				echo "<script>"
					."\$('#M_Q{$qno}_SQ{$subqno}_QID{$qid}').on('change',function() {"
					."		if(\$('#M_Q{$qno}_SQ{$subqno}_QID{$qid}').val() > $maxmarks || \$('#M_Q{$qno}_SQ{$subqno}_QID{$qid}').val()=='') {"
					."			dispErr('Invalid marks');"
					."			\$('#M_Q{$qno}_SQ{$subqno}_QID{$qid}').css('border','1px solid red');"
					."			\$('#M_Q{$qno}_SQ{$subqno}_QID{$qid}').addClass('err');"
					."			\$('#total-marks-text-field').val(calcTotalMarks());"
					."		} else {"
					."			\$('#M_Q{$qno}_SQ{$subqno}_QID{$qid}').css('border','none');"
					."			\$('#M_Q{$qno}_SQ{$subqno}_QID{$qid}').removeClass('err');"
					."			\$('#total-marks-text-field').val(calcTotalMarks());"
					."			updateProgressBar();"
					."		}"
					."});"
					."</script>";
			} else {
				echo "<tr>"
					."<td>$subqno</td>"
					."<td>$maxmarks</td>"
					."<td><input type='text' name='M_Q{$qno}_SQ{$subqno}_QID{$qid}' id='M_Q{$qno}_SQ{$subqno}_QID{$qid}' class='transparent marksTB enterBlur' width='25' onkeypress='if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;' value='{$preMarksVal}' /></td>"
					."<td><input type='text' name='R_Q{$qno}_SQ{$subqno}_QID{$qid}' id='R_Q{$qno}_SQ{$subqno}_QID{$qid}' class='transparent remarksTB enterBlur' width='170' value='{$preRemarksVal}' /></td>"
					."</tr>";
					
					
				//add ids to marksids and remarksids text-fields
				//echo "<script>"
				//	."	\$('#marksids').val(\$('#marksids').val()+\"M-Q$qno-SQ$subqno-QID$qid&\");"
				//	."	\$('#remarksids').val(\$('#remarksids').val()+\"R-Q$qno-SQ$subqno-QID$qid&\");"
				//	."</script>";
					
				
				$marksids = $marksids."M_Q{$qno}_SQ{$subqno}_QID{$qid}"."&";
				$remarksids = $remarksids."R_Q{$qno}_SQ{$subqno}_QID{$qid} "."&";
				
				//put the required jquery
				echo "<script>"
					."\$('#M_Q{$qno}_SQ{$subqno}_QID{$qid}').on('change',function() {"
					."		if(\$('#M_Q{$qno}_SQ{$subqno}_QID{$qid}').val() > $maxmarks || \$('#M_Q{$qno}_SQ{$subqno}_QID{$qid}').val()=='') {"
					."			dispErr('Invalid marks');"
					."			\$('#M_Q{$qno}_SQ{$subqno}_QID{$qid}').css('border','1px solid red');"
					."			\$('#M_Q{$qno}_SQ{$subqno}_QID{$qid}').addClass('err');"
					."			\$('#total-marks-text-field').val(calcTotalMarks());"
					."		} else {"
					."			\$('#M_Q{$qno}_SQ{$subqno}_QID{$qid}').css('border','none');"
					."			\$('#M_Q{$qno}_SQ{$subqno}_QID{$qid}').removeClass('err');"
					."			\$('#total-marks-text-field').val(calcTotalMarks());"
					."			updateProgressBar();"
					."		}"
					."});"
					."</script>";
			}
			$i++;
		}
		
	}
	
	echo "</table>";
	
	//text-field to keep all marks and remarks ids
	$marksids = substr($marksids,0,strlen($marksids)-1);
	$remarksids = substr($remarksids,0,strlen($remarksids)-1);
	
	
	echo "<input type='text' name='marksids' id='marksids' style='display:none;' value='$marksids' />"
		."<input type='text' name='remarksids' id='remarksids' style='display:none;' value='$remarksids' />";
	
	
	



	//echo "<script>dispErr(\"testing\");</script>"


	$conn->close();












?>
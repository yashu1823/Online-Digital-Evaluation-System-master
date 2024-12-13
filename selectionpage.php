<?php
session_start();



if((!isset($_SESSION['FactId']) ) || ($_SESSION['FactId']=="")){
	$url = 'login.php';
	if (headers_sent()){
		die('<script type="text/javascript">window.location.href="' . $url . '";</script>');
	}else{
		header('Location: login.php');
		die();
	}
}








include_once 'dbconfig.php';
$FactId = $_SESSION['FactId'];
//$FactId = 'f02';
//echo $FactId;
?>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script></script>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!--<script type="text/javascript" src="jquery-1.4.1.min.js"></script>-->
		<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
		
		<link type="text/css"  rel="stylesheet" href="progressbutton.css" >
		<link type="text/css"  rel="stylesheet" href="SelectionPageStyle.css" >
		<link type="text/css"  rel="stylesheet" href="CommonStyle.css" >
	</head>
	<body>
	
		<script>
		
		function dispErr(str,color){
			if(typeof color !== "undefined" && color == 'green') {
				$('#ErrorBar').css('color','#4caf50');
				$('#ErrorBar').css('border-color','#4caf50');
			}
			
			$('#ErrorBar').css('display','block');
			document.getElementById('ErrorBar').value = str;
			setTimeout("$('#ErrorBar').css('display','none');", 4000);
			//reset color after 4000ms
			setTimeout("$('#ErrorBar').css('color','red');", 4000);
			setTimeout("$('#ErrorBar').css('border-color','red');", 4000);
			
		}
		
		
		</script>
	
	
	
		<div id="header-div-id" style="font-size:30px;font-family:Verdana;float:left;margin:0px;padding:0s;height:50px;width:100%;background-color:#5f5f5f;color:white;">
			<p style="font-size:23px;margin-left:20px;display:inline-block;margin-top:9px;">ONLINE DIGITAL EVALUATION SYSTEM</p>
			<button class="round-edge-button header-button" id="logout-button" style="display:inline-block;margin-top:10px;padding:8px 16px;margin-right:15px;float:right;" >
			Logout
			</button>
			<p style="font-size:18px;float:right;margin-bottom:0px;margin-right:120px;display:inline-block;margin-top:15px;" >Hello <?php echo $_SESSION['FactName'];?>!</p>

		</div>
		
		<script>
			$('#logout-button').click(function(){
				window.location.href = "login.php";
			});
		</script>
		<!--<div  id="nav-div-id" style="float:left;margin:0px;padding:0;height:652px;width:200px;background-color:#cccccc;">
			<ul>
			  <li><a class="list" href="#home">Home</a></li>
			  <li><a class="list" href="#news">Top Ten</a></li>
			  <li><a class="list" href="#contact">Peformance</a></li>
			  <li><a class="list" href="#about">Logout </a></li>
			</ul>
			</div>-->
		<div id="left-div-id" style="float:left;margin:0px;padding:0px;height:652px;width:100%;background-color:#e6e6e6;">
			
			<div id="selections-div" style="">
				<div id="inner-selections-div" >
					<!--<div style="margin-left:10px;margin-top:10px;" >-->
					
						<select id="s1" name="semester" class="semester" onchange="loadSection(this.value);"  >
							<option selected="selected" value="nil" >Select Semester</option>
								<?php
									$sql="SELECT * FROM `courses` WHERE `CourseId` IN (SELECT `course_faculty`.`CourseId` from course_faculty WHERE `course_faculty`.`FactId` = '{$FactId}' )";

									$result = $conn->query($sql);

									if($result->num_rows >0){
										while($row = $result->fetch_assoc()) {
											echo '<option value="'.$row["Sem"].'">'.$row["Sem"].'</option>';
										}
									}
								?>
						</select>

  
					<!--</div>-->
  
				
					<!--<div class="styled-select rounded"  style="margin-left:40px;margin-top:10px;" >-->
  
						<select id="s2" name="section" class="section"  onchange="LoadCourse(this.value);">
							<option selected="selected" value="nil" >Select Section</option>
						</select>
   
	
 
    

					<!--</div>-->
				

					<!--<div class="styled-select rounded"  style="margin-left:30px;margin-top:10px;" >-->
  
						<select id="s3" name="course" class="course">
							<option selected="selected" value="nil" >Select Course</option>

						</select>
    
					<!--</div>-->
				
			
					<div id="button-div-id" style="float: left;width:100%">
    
						<button   id ="b1" class="green-button round-edge-button"  style=""  onclick="LoadAnswerscripts();">OK</button>
					</div>
					
					<input type="text" id="ErrorBar" class="transparent" value="" style="display:none;" readonly />
					
				</div>
			</div>
			
			<div id="corrected-uncorrected-outer" >
				<div id = "corrected-uncorrected-inner" >
					<div id="uncorrected"  style=""  >
						<input type="text" class="transparent" value="Pending Answer-Scripts" readonly style="" />
					</div>
  
					<div id="corrected"  style=""  >
						<input type="text" class="transparent" value="Corrected Answer-Scripts" readonly style="" />

					</div>
				</div>
			</div>
  
  
			
  
  
  
  
		</div>
		<script>
			function loadSection(value)
			{
				console.log("In loading sections script");
				$('#s3').val("nil");
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) 
    
						document.getElementById("s2").innerHTML=this.responseText;

				};

				xhttp.open("GET","loadSection.php?sem="+value+"&fac="+<?php echo "\"$FactId\""; ?>,true);
				xhttp.send(); 
  
			}
			function LoadCourse(value)
			{
				var sem=document.getElementById("s1").value;
				console.log("sem"+sem);
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) 
    
						document.getElementById("s3").innerHTML=this.responseText;

				};

				xhttp.open("GET","LoadCourse.php?sem="+sem+"&sec="+value+"&fac="+<?php echo "\"$FactId\""; ?>,true);
				xhttp.send(); 
  
			}
			function LoadAnswerscripts(){


				console.log("In LoadAnswerscripts");
				var sem=document.getElementById("s1").value;
				var sec=document.getElementById("s2").value;
				var course=document.getElementById("s3").value;
				//document.write(sem);
  
				if(sem !="nil" && sec!="nil" && course!="nil"){
					var xhttp = new XMLHttpRequest();
					xhttp.onreadystatechange = function() {
						if (this.readyState == 4 && this.status == 200) 
							
							document.getElementById("uncorrected").innerHTML=this.responseText;

					};
  
 

					xhttp.open("GET","AnswerScripts.php?sem="+sem+"&sec="+sec+"&course="+course+"&fac="+<?php echo "\"$FactId\""; ?>,true);
					xhttp.send();
  
					xhttp = new XMLHttpRequest();
					xhttp.onreadystatechange = function() {
						if (this.readyState == 4 && this.status == 200) 
    
   
							document.getElementById("corrected").innerHTML=this.responseText;

					};
					xhttp.open("GET","corrected.php?sem="+sem+"&sec="+sec+"&course="+course+"&fac="+<?php echo "\"$FactId\""; ?>,true);
					xhttp.send();
  
				} else {
					
					dispErr("Please select sem , sec and course");
					
				}
			}
		</script>
	</body>

</html>
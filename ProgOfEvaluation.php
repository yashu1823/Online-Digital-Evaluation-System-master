<?php
session_start();
?>
<?php 

if((!isset($_SESSION['FactId']) ) || ($_SESSION['FactId']=="")){
	$url = 'login.php';
	if (headers_sent()){
		die('<script type="text/javascript">window.location.href="' . $url . '";</script>');
	}else{
		header('Location: login.php');
		die();
	}
}





$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "odes";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$factId=$_SESSION["FactId"];
//$factId='f11';

//SELECT * FROM `branches` WHERE `HoD`=$factId;
$sql="SELECT * FROM `branches` WHERE `HoD`='{$factId}'";
$result = $conn->query($sql);
if ($result->num_rows > 0){
if($row = $result->fetch_assoc())
{
	$branchid =$row['BranchId'];
}
}else 
{//echo "0 results";
}






?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="ProgOfEvaluation.css">
	<link rel="stylesheet" type="text/css" href="CommonStyle.css">
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    
</head>
<body style="margin:0;">
	<div id="header-div-id" style="font-size:30px;font-family:Verdana;float:left;margin:0px;padding:0s;height:50px;width:100%;background-color:#5f5f5f;color:white;">
	<p style="font-size:23px;margin-left:20px;display:inline-block;margin-top:9px;">ONLINE DIGITAL EVALUATION SYSTEM</p>
	<button class="round-edge-button header-button" id="logout-button" style="display:inline-block;margin-top:10px;padding:8px 25px;margin-right:15px;float:right;">
		Logout
	</button>
	<p style="font-size:18px;float:right;margin-bottom:0px;margin-right:120px;display:inline-block;margin-top:15px;" >Hello <?php echo $_SESSION['FactName'];?>!</p>

	<script>
		//script for logout-button
		document.getElementById('logout-button').onclick= function(){
			
			
			window.location.href = 'login.php';
				//return false;
		};
	
	
	</script>
	
	</div>
<!--
<div  id="nav-div-id" style="float:left;margin:0px;padding:0;height:652px;width:200px;background-color:#cccccc;">
<ul>
  <li><a class="list" href="#">Home</a></li>
  <li><a class="list" href="#">Subject-Wise Progress</a></li>
  <li><a class="list" href="#">Semester-Wise Progress</a></li>
  <li><a class="list" href="#">Logout </a></li>
</ul>
</div>
-->
<!-- div for subject wise analysis-->
<div id="left-div-id1" style="float:left;margin:0px;padding:0;height:652px;width:50%;background-color:#cccccc;display: block ;">
<div   id="select-container-div-id" style="">

<div class="select1">
  <label id="select-label1" >Select Semester:</label>
  <select id="selectId1" onchange="loadCourseContents(this.value);" style="" >
    
    <option value="1">Sem 1</option>
    <option value="3">Sem 3</option>
    <option value="5">Sem 5</option>
    <option value="7">Sem 7</option>
  </select>
 
</div>

<div class="select2">
 
    <label id="select-label2" >Select Section:</label>
    <select id="selectId2" style="" >
    
	
	<?php
	
	if(strcmp($branchid,'cse')==0)
		echo '<option value="a">Sec A</option>
			  <option value="b">Sec B</option>
			  <option value="All">All</option>';
			  
	elseif ($branchid == 'ise')
		echo '<option value="a">Sec A</option>';
	
	?>

  </select>
 
</div>

<div class="select3" >
   <label id="select-label3" >Select Course:</label>
   <select id="selectId3" style="" >
    <?php include 'init.php';?>
   </select>
  
</div>


</div>
  
  <div id="button-div-id" style="">
    <button id="showResults-button" class="green-button round-edge-button"  onclick="fetchResult();">Show Results</button>
  </div>


</div>

<!--Div for sem wise analysis-->

<!--<div id="left-div-id2" style="float:left;margin:0px;padding:0;height:652px;width:50%;background-color:#cccccc;display: none;">
<div   id="select-container-div-id" style="float:left;margin:0px;padding:0;height:100px;width:100%;display: inline-block;">

<div class="select1">
 <div class="styled-select green rounded" >
  <select id="selectId1" onchange="loadSecCourseContents(this.value);">
    
    <option value="s1">Sem 1</option>
    <option value="s3">Sem 3</option>
    <option value="s5">Sem 5</option>
    <option value="s7">Sem 7</option>
  </select>
 </div>
</div>-->

<!--<div class="select2">
 <div class="styled-select green rounded" >
    <select id="selectId2">
    <option value="A">Sec A</option>
    <option value="B">Sec B</option>
    <option value="All">All</option>

  </select>
 </div>
</div>

<div class="select3">
  <div class="styled-select rounded"  style="margin-left:10px;margin-top:10px;" >
   <select id="selectId3">
      <option value="c1">DS</option>
      <option value="c2">COA</option>
      <option value="c3">System sw</option>
      <option value="c4">DBMS</option>
      <option value="c5">MP</option>
   </select>
  </div>
</div>


</div>
  
  <div id="button-div-id" style="float: left;top: 500px;left:300px;">
    <button onclick="fetchResult();">Show Results</button>
  </div>


</div>-->

<div id="msgdisp-div" style="float:left;margin:0px;padding:0px;height:652px;width:50%;background-color:#f3f3f3;" >
	<label id="msgdispTB" >Select semester, section and course and click on Show Results button</label>
</div>
<div id="chart-div-id" style="float:left;margin:0px;padding:0px;height:652px;width:34%;background-color:#FFF;display: none;">
<div id="piechart_3d" style="width: 100%; height: 100%;background-color:#FFF;"></div>

</div>
<script type="text/javascript">
function loadCourseContents(value){
  console.log("In loadCourseContents");
  var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) 
    
    document.getElementById("selectId3").innerHTML=this.responseText;

  };

  xhttp.open("GET","setCourseContent.php?sem="+value+"&branch="+<?php echo "'$branchid'";?>,true);
  xhttp.send();




}
function fetchResult(){


  console.log("In fetchResult");
  var sem=document.getElementById("selectId1").value;
  var sec=document.getElementById("selectId2").value;
  var course=document.getElementById("selectId3").value;
  console.log("course:"+course);
  //document.write(sem);
  var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    
      //var data1 = JSON.parse(this.responseText);
		var datatemp = this.responseText;
		console.log("responseText:"+this.responseText);
	
		var data1 = datatemp.split(",");
    //document.write(this.responseText);
    corrected=data1[0];
    uncorrected=data1[1];
    console.log("corrected:"+corrected);
	}
  };

  xhttp.open("GET","showResults.php?sem="+sem+"&sec="+sec+"&course="+course+"&branch="+<?php echo "\"$branchid\""; ?>,true);
  xhttp.send();


  /*chart*/
google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Course', '% of completion'],
          ['Corrected', Number(corrected)],
          ['Not Corrected', Number(uncorrected) ],
                  
        ]);

        var options = {
          title: 'Progress of evaluation',
          is3D: false,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }

document.getElementById("msgdisp-div").style.display="none";
document.getElementById("chart-div-id").style.display="block";


}



</script>

</body>

</html>

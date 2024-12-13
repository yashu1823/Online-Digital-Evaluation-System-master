<?php

session_start();
$_SESSION['FactId']="";

?>

<!DOCTYPE html>
<html>
<head>

<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>


<link rel="stylesheet" type="text/css" href="CommonStyle.css" >
<link rel="stylesheet" type="text/css" href="login.css" >

<title>ODES Login</title>
</head>
<body>
 
<?php 
$usernameInput="";
$passwordInput="";

?>

<div id="login-page-div">

	
	
	<div id="form-div">
	
		<!--<div id="title-div" style="display: table-cell;" >
			<!--<div style="display: table-cell;" >
			<ul>
				<li>On-line Digital Evaluation System</li>
			</ul>
			<!--</div>
	
		</div>-->
		
		<input type="text" value="ON-LINE DIGITAL EVALUATION SYSTEM" id="title-box" readonly />
	
		<form id="login-form" method="post" action="" >
			<input type="text" id="usernameTB" name="usernameTB" placeholder="Faculty ID" value="<?php if(isset($_POST['usernameTB'] )) echo $_POST['usernameTB'];?>" />
			<input type="password" id="passwordTB" name="passwordTB" onClick="this.select();" placeholder="Password" value="<?php if(isset($_POST['passwordTB'] )) echo $_POST['passwordTB'];?>" />
			<select name="login-as" id="login-as" >
				<option value="none" <?php if (isset($_POST['login-as']) && $_POST['login-as']=="none") echo "selected";?> >Login as</option>
				<option value="HOD" <?php if (isset($_POST['login-as']) && $_POST['login-as']=="HOD") echo "selected";?> >HOD</option>
				<option value="Evaluator" <?php if (isset($_POST['login-as']) && $_POST['login-as']=="Evaluator") echo "selected";?> >Evaluator</option>
			</select>
			<button type="submit" value="submit" name="SubmitButton" class="header-button" style="border-radius:3px; padding: 13px 60px; margin-top: 40px;" >Login</button>
	  
		</form>
		
		<input type="text" value="" id="status-box" readonly />
		
	</div>
</div>

 



<?php



function errorDisplay($elemId,$errstr){
	echo "<script>document.getElementById(\"status-box\").value = \"",$errstr,"\" ;";
	echo "document.getElementById(\"",$elemId,"\").style.borderColor = \"red\";</script>";
}

function getHoD($conn,$branchId){
	$sql = "SELECT HoD FROM branches WHERE BranchId=\"".$branchId."\";";
	$result = $conn->query($sql);
	
	if ($result->num_rows == 0){
		return "error";
	} elseif ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		return $row["HoD"];
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




if(isset($_POST['SubmitButton'])){ //check if form was submitted
	$usernameInput = $_POST['usernameTB']; 
	$passwordInput = $_POST['passwordTB'];
	$loginas = $_POST['login-as'];
	
	if($loginas == "none" || $usernameInput == "" || $passwordInput == "" ){
		
		if($loginas == "none")
			errorDisplay("login-as","Required");
		if($usernameInput == "")
			errorDisplay("usernameTB","Required");
		if ($passwordInput == "")
			errorDisplay("passwordTB","Required");
	} /*elseif (!ctype_digit($usernameInput)){ // if usernameInput contains non-digits
		errorDisplay("usernameTB","Invalid Username");
		
	}*/ elseif ($loginas == "Evaluator"){
		
		
		
		$sql = "SELECT FactId,Password FROM faculty WHERE FactId='".$usernameInput."';";
		$result = $conn->query($sql);
		
		if ($result->num_rows == 0){
			//echo "inavlid username or login-as 10d";
			
			errorDisplay("usernameTB","");
			errorDisplay("login-as","Inavlid username or login-as");
		} elseif ($result->num_rows > 0) {
			// output data of each row
			if($row = $result->fetch_assoc()) {
				if($row["Password"]==$passwordInput) {
					$_SESSION["FactId"]=$usernameInput;
					//fetch fact-name
					$resultT = $conn->query("SELECT `Name` FROM `faculty` WHERE `FactId`='{$_SESSION['FactId']}'");
					if ($resultT->num_rows > 0) {
						if($rowT = $resultT->fetch_assoc()){
							$_SESSION['FactName'] = $rowT['Name'];
							
						}
					}
					//redirect("localhost/ODES/temp.php");
					$url = 'selectionpage.php';
					if (headers_sent()){
						die('<script type="text/javascript">window.location.href="' . $url . '";</script>');
					}else{
						header('Location: selectionpage.php');
						die();
					}

					break;
				} else {
					//echo "invalid password";
					errorDisplay("passwordTB","Inavlid password");
				}
			}

		} else {
			echo "Not found";
		}
	} elseif ($loginas == "HOD") {
		
		$sql = "SELECT FactId,Password,BranchId FROM faculty WHERE FactId='".$usernameInput."';";
		$result = $conn->query($sql);
		
		if ($result->num_rows == 0){
			//echo "inavlid username or login-as";
			errorDisplay("usernameTB","");
			errorDisplay("login-as","Inavlid username or login-as");
		} elseif ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$BranchId = $row["BranchId"];
			
			$HoD = getHoD($conn,$BranchId);
			if ($HoD == "error"){
				echo "db data error";
			} else {
				
				if($HoD==$usernameInput ){
					//check for passw$ord
					if($row["Password"]==$passwordInput) {
						//assign session variables 
						$_SESSION["FactId"]=$usernameInput;
						//fetch fact-name
						$resultT = $conn->query("SELECT `Name` FROM `faculty` WHERE `FactId`='{$_SESSION['FactId']}'");
						if ($resultT->num_rows > 0) {
							if($rowT = $resultT->fetch_assoc()){
								$_SESSION['FactName'] = $rowT['Name'];
								
							}
						}
						//redirect("localhost/ODES/temp.php");
						$url = 'ProgOfEvaluation.php';
						if (headers_sent()){
							die('<script type="text/javascript">window.location.href="' . $url . '";</script>');
						}else{
							header('Location: ProgOfEvaluation.php');
							die();
						}
						
					} else {
						//echo "invalid password";
						errorDisplay("passwordTB","Inavlid password");
					}
				} else {
					//echo "you're not the hod";
					errorDisplay("login-as","No access for non HODs");
				}
			}
			
			
			
			
			
			
			
		}
		
		
	}

}



$conn->close();
?> 



</body>
</html>


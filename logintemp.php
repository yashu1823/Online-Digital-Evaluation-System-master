<?php

session_start();


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

function errorDisplay($elemId,$errstr){
	//call errorDisplayJS
	echo "<script>errorDisplayJS(\"".$elemId."\",\"".$errstr."\");</script>";
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
	
	if($loginas == "none"){
		echo "select login as";
	} elseif ($loginas == "Evaluator"){
		
		$sql = "SELECT FactId,Password FROM faculty WHERE FactId=".$usernameInput.";";
		$result = $conn->query($sql);
		
		if ($result->num_rows == 0){
			echo "inavlid username or login-as 10d";
			//echo "<script>document.getElementById(\"status-box\").value = 'inavlid username or login-as 2';</script>";
			echo "<script>$(\"#status-box\").val('inavlid username or login-as 2');</script>";
			
			//errorDisplay("usernameTB","");
			//errorDisplay("passwordTB","inavlid username or login-as");
		} elseif ($result->num_rows > 0) {
			// output data of each row
			if($row = $result->fetch_assoc()) {
				if($row["Password"]==$passwordInput) {
					$_SESSION["user"]=$usernameInput;
					//redirect("localhost/ODES/temp.php");
					if (headers_sent()){
						die('<script type="text/javascript">window.location.href="' . $url . '";</script>');
					}else{
						header('Location: temp.php');
						die();
					}

					break;
				} else {
					echo "invalid password";
				}
			}

		} else {
			echo "Not found";
		}
	} elseif ($loginas == "HOD") {
		
		$sql = "SELECT FactId,Password,BranchId FROM faculty WHERE FactId=".$usernameInput.";";
		$result = $conn->query($sql);
		
		if ($result->num_rows == 0){
			echo "inavlid username or login-as";
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
						$_SESSION["user"]=$usernameInput;
						
						//redirect("localhost/ODES/temp.php");
						if (headers_sent()){
							die('<script type="text/javascript">window.location.href="' . $url . '";</script>');
						}else{
							header('Location: temp.php');
							die();
						}
						
					} else {
						echo "invalid password";
					}
				} else {
					echo "you're not the hod";
				}
			}
			
			
			
			
			
			
			
		}
		
		
	}

}



$conn->close();
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
			<input type="text" id="usernameTB" name="usernameTB" placeholder="username" value="<?php echo $usernameInput;?>" />
			<input type="password" id="passwordTB" name="passwordTB" placeholder="password"/>
			<select name="login-as" id="login-as" >
				<option value="none" <?php if (isset($loginas) && $loginas=="none") echo "selected";?> >Login as</option>
				<option value="HOD" <?php if (isset($loginas) && $loginas=="HOD") echo "selected";?> >HOD</option>
				<option value="Evaluator" <?php if (isset($loginas) && $loginas=="Evaluator") echo "selected";?> >Evaluator</option>
			</select>
			<button type="submit" value="submit" name="SubmitButton" class="header-button" style="border-radius:3px; padding: 13px 60px; margin-top: 40px;" >Login</button>
	  
		</form>
		
		<input type="text" value="" id="status-box" readonly />
		
	</div>
</div>

 







</body>
</html>

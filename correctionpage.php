<?php
	session_start();
	
	//header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	//header('Pragma: no-cache');
	//header('Cache-Control: no-store, no-cache, must-revalidate');
	
	//fetch session var: HidId
	//$HidId = $_GET['hidid'];
	//$HidId = 'GRSSEAWW';
	
	if((!isset($_SESSION['FactId']) ) || ($_SESSION['FactId']=="")){
		$url = 'login.php';
		if (headers_sent()){
			die('<script type="text/javascript">window.location.href="' . $url . '";</script>');
		}else{
			header('Location: login.php');
			die();
		}
	}
	
	
	
	
	
	
	
	
	
	if ( isset($_GET['hidid']) ){
		$HidId = $_GET['hidid'];
	} elseif ( isset( $_SESSION['hidid'] ) ) {
		$HidId = $_SESSION['hidid'];
	}else {
		$HidId = 'GRSSEAWW';
	}
	
	
	//fetch from db and set session vars:QpId, studid
	
	//create and check connection
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "odes";

	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	//fetch courseid and studid
	$result = $conn->query("SELECT `StudId`,`CourseId` FROM `ansscripts` WHERE `HidId`='{$HidId}' LIMIT 1");
	if ($result->num_rows > 0) {
		if($row = $result->fetch_assoc()){
			$courseid = $row['CourseId'];
			//echo "courseid:{$courseid}<br>";
			$studid = $row['StudId'];
		}
	}
	
	//fetch coursename
	$result = $conn->query("SELECT `Name` FROM `courses` WHERE `CourseId`='{$courseid}'");
	if ($result->num_rows > 0) {
		if($row = $result->fetch_assoc()){
			$coursename = $row['Name'];
			//echo "courseid:{$courseid}<br>";
			//$studid = $row['StudId'];
		}
	}
	
	//fetch QpId: SELECT `QpId` FROM `qpapers` WHERE `CourseId`=''
	$result = $conn->query("SELECT `QpId` FROM `qpapers` WHERE `CourseId`='{$courseid}'");
	if ($result->num_rows > 0) {
		if($row = $result->fetch_assoc()){
			$QpId = $row['QpId'];
		}
	}
	
	
	
	//set session vars
	$_SESSION['QpId']=$QpId;
	$_SESSION['studid']=$studid;
	
	//close conn
	//$conn->close();
?>
<html>
	<head>
		<title>
			ODES Correction Page
		</title>
		
		<!-- Style sheets -->
		<link rel="stylesheet" type="text/css" href="modalStyle.css" />
		<link rel="stylesheet" type="text/css" href="teacherStyle.css" />
		<link rel="stylesheet" type="text/css" href="CommonStyle.css" />
		
		<!-- Scripts -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	
		<script type="text/javascript" src="canvasScript.js" ></script>	
		<script type="text/javascript" src="zoomScript.js" ></script>
		<script type="text/javascript" src="mysqlSafe.js" ></script>
		<script>
			$(document).keydown(
				function(e)
				{    
					// Down key
					if (e.keyCode == 40 || e.keyCode == 39) {      
						$(".move:focus").next().focus();   
					}
				
					// Up key
					if (e.keyCode == 38 || e.keyCode == 37) {      
						$(".move:focus").prev().focus();   
					}
				}
			);
			
			
		</script>
		
		<script>
			//script for the func:dispErr()
			function dispErr(str,color){
				if(typeof color !== "undefined" && color == 'green') {
					$('#err-disp-text-field').css('background-color','#4caf50');
				}
				
				$('#err-disp-text-field').css('display','block');
				document.getElementById('err-disp-text-field').value = str;
				setTimeout("$('#err-disp-text-field').css('display','none');", 4000);
				//reset background-color after 4000ms
				setTimeout("$('#err-disp-text-field').css('background-color','#ff4d4d');", 4000);
				
				
			}
			
		</script>
		
		<script>
			//script for maintenance of error flag
			var errorFlag = 0;
			function setErrorFlag(){
				errorFlag = 1;
			}
			function unsetErrorFlag(){
				errorFlag = 0;
			}
			function isError(){
				if(errorFlag == 1){
					return true;
				} else {
					return false;
				}
			}
		
		</script>
		
		<script>
			// script to blur a text field on pressing enter key
			$('.enterBlur').keypress(function(e){
				if(e.which == 13){
					$(this).blur();    
				}
			});
		</script>
		
		<script>
			//function to calculate total-marks; based on the class:marksTB
			function calcTotalMarks(){
				$total = 0;
				$('.marksTB').each(function(){
					if($(this).val() != '' && !$(this).hasClass('err')){
						$total += parseFloat($(this).val());
					}
				});
				return $total;
			}
		</script>
		
		
	
	</head>
	<body >
	
		<input type="text" id="err-disp-input-text-field" style="display:none;" value="No error" readonly />
	
		<!-- Modal Div -->
		<div id="myModal" style="display:none;" >
			<div id="modal-content">
				<div id="modal-header">
					<span id="close">&times;</span>
					<h2>Question Paper:-- </h2>
					<p style="font-size:large;">Course: <?php echo "{$courseid}  {$coursename}";?></p>
				</div>
				<div id="modal-body">
					<?php
						//set session var:QpId
						//$_SESSION['QpId']="qp01";
						include 'QPaperTableCreation.php';
					?>
				</div>
				<!--
					<div class="modal-footer">
					<h3>Modal Footer</h3>
					</div>
					-->
			</div>
		</div>
		<!-- Modal div - end -->
		
		
		
		<!-- Header div - start -->
		<div class="header-div" style="">
			<p style="font-size:23px;margin-left:20px;display:inline-block;margin-top:9px;" >ONLINE DIGITAL EVALUATION SYSTEM</p>
			<!--<form action="selectionpage.php" >-->
				<button class="round-edge-button header-button" id="back-page-button" style="display:inline-block;margin-top:10px;padding:8px 16px;margin-right:15px;float:right;" >
				&lt; Back
				</button>
			<!--</form>-->
			<button class="round-edge-button header-button" id="view-qp-button" style="display:inline-block;margin-top:10px;padding:8px 16px;margin-right:15px;float:right;" >
			View Question Paper
			</button>
			<p style="font-size:18px;float:right;margin-bottom:0px;margin-right:120px;display:inline-block;margin-top:15px;" >Hello <?php echo $_SESSION['FactName'];?>!</p>
		</div>
		
		<script>
			$('#back-page-button').click(function(){
				if(confirm("Are you sure you want to go back without submitting?"))
					window.location.href = "selectionpage.php";
				
			});
		</script>
		<!-- Header div - end -->
		
		<!-- modal script -->
		<script type="text/javascript" src="modalScript.js"></script>
		
		<!-- Answer Script Div - start -->
		<div id="ans-script-div" style="">
		
			<script>
				//script to fetch ans script images
				var imgsrcs = [];
				var imgids = [];
				var originalimgsrcs = [];
				var curImgIndex = 0;
				var numOfImages = 0;
			</script>
			<?php //php to fetch imgsrcs from db
				
				//fetch imgsrcs from db and add to array
				$result = $conn->query("SELECT `ImgId`,`ImagePath`,`OriginalImagePath` FROM `ansscripts` WHERE `HidId`='{$HidId}'");
				if ($result->num_rows > 0) {
					echo "<script>"
						."";
					while($row = $result->fetch_assoc()) {
						echo "imgsrcs.push('{$row['ImagePath']}');";
						echo "originalimgsrcs.push('{$row['OriginalImagePath']}');";
						echo "imgids.push('{$row['ImgId']}');";
					}
					echo "</script>";
				} else {
					echo "0 results";
				}

			?>
			
			<script>
				//script to preload images to increase speed of loading-part1
				
				
				//create hidden images
				for(var i=0;i<originalimgsrcs.length;i++){
					document.write("<img id='cache"+i+"' style='display:none' >");
				}
				
			</script>
			<script>
				//script to preload images to increase speed of loading-part2
				//add srcs to hidden images 
				for(var i=0;i<originalimgsrcs.length;i++){
					document.getElementById("cache"+i).src= originalimgsrcs[i];
				}
			</script>
			
			<script>
				// AJAX function to update imgsrcs
				
				function updateImgsrcs(){
					
					$.post("fetchImgSrcsAJAX.php",{hidid : '<?php echo $HidId; ?>'},function(arrstr){
						imgsrcs = arrstr.split(",");
						console.log("From ajax "+imgsrcs);
						
					});
					
				}
				
				//call updateImgsrcs the first time
				//updateImgsrcs();
				
				//console.log("line 231 "+imgsrcs);
				
				//set numOfImages
				numOfImages = imgids.length;
				
				
			
			</script>
		
			<div id="ans-script-image-div" style="display:inline-block;" >
				<img id="ans-script-img" src="images/img3.jpg" 
					style="width:429px;height:572px;"
					onclick=zoomIn(this); />
				
				<script>
					//set the ans script img to first image 
					//d = new Date();
					console.log("line: 246"+imgsrcs[curImgIndex]);
					$('#ans-script-img').attr('src',imgsrcs[curImgIndex]/*+"?"+d.getTime()*/);
					console.log("qwer"+$('#ans-script-img').attr('src') );
				</script>
				<!--
				<script type="text/javascript">
					$("#ans-script-img").mousedown(function(ev){
					      if(ev.which == 3)
					      {
					            alert("Right mouse button clicked on element with id myId");
				    	  }
					});

				</script>
				-->
			</div>
			
			<div id="original-ans-script-image-div" style="display:none;" >
				<img id="original-ans-script-img" src="images/img3.jpg" 
					style="width:429px;height:572px;" />
					
				<script>
					//set the ans script img to first image 
					//d = new Date();
					$('#original-ans-script-img').attr('src',originalimgsrcs[curImgIndex]/*+"?"+d.getTime()*/);
				</script>
				
			
			</div>
			
			<div id="ans-script-canvas-div" style="display:none" >
				<script>
				//console.log(document.getElementById("canvas-id"));
				//init(document.getElementById("canvas-id"));
				
				</script>
				<img id= "img-for-canvas" src="images/img3.jpg" style="width:30px;height:40px;visibility:hidden;" />
				
				<script>
					//set the img to first image 
					//d = new Date();
					$('#img-for-canvas').attr('src',imgsrcs[curImgIndex]/*+"?"+d.getTime()*/);
				</script>
				
				
				<canvas id="canvas-id" width="426" height="568" style="margin-left:15%;border:2px solid;display:inline-block;" >
				
			
				</canvas>
				
				<!--
				<script>
					console.log(document.getElementById('canvas-id').toDataURL());
				</script>
				-->
				
				<button id="canvas-clear-button" class="round-edge-button red-button" style="padding:8px 16px;margin-right: 20px;margin-top: 70.2%;float: right;" onclick=erase() >
					clear
				</button>
				<script>
					$(document).ready(function(){
						init(document.getElementById("canvas-id"),document.getElementById("img-for-canvas"));
					});
					
					
					
					//init(document.getElementById("canvas-id"),document.getElementById("img-for-canvas"));
				</script>
				
			</div> 
			
			
			
			<div id="ansScript-controls-div" style="overflow:auto;" >
				<button id="canvas-toggle-button" class="round-edge-button red-button move" style="margin-left:10px;margin-top:10px;padding:8px 16px;" >
				draw
				</button>
				<input id="pg-num-text-field" class="move enterBlur" type="text" size="2" style="margin-left:7%;" maxlength="2" onkeypress="if ( isNaN(String.fromCharCode(event.keyCode) )) return false;" /> 
				<b id="divider-symbol" >/</b>
				<input id="total-pages-text-field" type="text" size="2" readonly />
				<button id="prev-img-button" class="round-button green-button move" style="margin-left:11%;margin-top:10px;padding:8px 16px;"> &#x3c; </button>
				<button id="next-img-button" class="round-button green-button move" style="margin-top:10px;padding:8px 16px;"> > </button>                   
				<button id="view-original-button" class="round-edge-button green-button move" style="margin-left:7%;margin-top:10px;padding:8px 16px;" >View Original</button>
				<button id="zoomOut-button" class="round-edge-button green-button move"  style="" onclick=zoomOut(document.getElementById('ans-script-img')) >
				zoom out
				</button>
				
				<script>
					//script for next-img-button , prev-img-button 
					//               , pg-num-text-field , total-pages-text-field
					
					//initialize
					$('#total-pages-text-field').val(numOfImages);
					$('#pg-num-text-field').val(curImgIndex+1);
					
					$('#next-img-button').on('click',function(){
						if(curImgIndex+1 < numOfImages){
							//d = new Date();
							updateImgsrcs();
							curImgIndex++;
							$('#ans-script-img').attr('src',imgsrcs[curImgIndex]/*+"?"+d.getTime()*/);
							$('#original-ans-script-img').attr('src',originalimgsrcs[curImgIndex]/*+"?"+d.getTime()*/);
							$('#img-for-canvas').attr('src',imgsrcs[curImgIndex]/*+"?"+d.getTime()*/);
							$('#pg-num-text-field').val(curImgIndex+1);
						}
					});
					
					$('#prev-img-button').on('click',function(){
						if(curImgIndex > 0){
							//d = new Date();
							updateImgsrcs();
							curImgIndex--;
							$('#ans-script-img').attr('src',imgsrcs[curImgIndex]/*+"?"+d.getTime()*/);
							$('#original-ans-script-img').attr('src',originalimgsrcs[curImgIndex]/*+"?"+d.getTime()*/);
							$('#img-for-canvas').attr('src',imgsrcs[curImgIndex]/*+"?"+d.getTime()*/);
							$('#pg-num-text-field').val(curImgIndex+1);
						}
					});
					
					$('#pg-num-text-field').on('change',function(){
						if( $(this).val() > 0 && $(this).val() <= numOfImages ){
							//d = new Date();
							curImgIndex = $(this).val() - 1;
							$('#ans-script-img').attr('src',imgsrcs[curImgIndex]/*+"?"+d.getTime()*/);
							$('#original-ans-script-img').attr('src',originalimgsrcs[curImgIndex]/*+"?"+d.getTime()*/);
							$('#img-for-canvas').attr('src',imgsrcs[curImgIndex]/*+"?"+d.getTime()*/);
						} else {
							dispErr("Invalid page number");
							$('#pg-num-text-field').val(curImgIndex+1);
						}
					});
					
					//call updateImgsrcs the first time
					//updateImgsrcs();
				
					//console.log("line 231 "+imgsrcs);
				
					//set numOfImages
					//numOfImages = imgids.length;
				
				</script>
				
				
			</div>
			
		</div>
		<!-- Answer Script Div - end -->
		
		<!-- Answer Script - canvas or zoom toggle JS- start -->
		<script>
					//console.log($('#ans-script-image-div').css('display'));
					$('#canvas-toggle-button').on('click',function(){
						if($('#ans-script-image-div').css('display') != 'none' ){
							//console.log("in toggle jquery");
							init(document.getElementById("canvas-id"),document.getElementById("img-for-canvas"));
							
							$('#ans-script-image-div').hide();
							$('#original-ans-script-image-div').hide();
							$('#ans-script-canvas-div').show();
							$('#canvas-toggle-button').html('save');
							
							//hide buttons
							$('#pg-num-text-field,#divider-symbol,#total-pages-text-field,#prev-img-button,#next-img-button,#view-original-button,#zoomOut-button').hide();
							
							
							
						}else if($('#ans-script-canvas-div').css('display')!='none'){
							$('#ans-script-canvas-div').hide();
							$('#original-ans-script-image-div').hide();
							$('#ans-script-image-div').show();
							$('#canvas-toggle-button').html('draw');
							
							//show buttons
							$('#pg-num-text-field,#divider-symbol,#total-pages-text-field,#prev-img-button,#next-img-button,#view-original-button,#zoomOut-button').show();
							
							
							var pixeldata = document.getElementById('canvas-id').toDataURL('image/jpeg',1);
							
							
							//ajax to save drawing in db 
							
							var cursrc =$('#ans-script-img').attr('src');
							console.log("cursrc: "+cursrc+"\n");
							$.post("createImgAJAX.php",
								{	imgDataURL: pixeldata,
									hidid: '<?php echo $HidId;?>',
									imgid: imgids[curImgIndex],
									cursrc: cursrc
								},function(a){
									//d = new Date();
									
									console.log("from ajax: a: "+a );
									$('#ans-script-img').attr('src',a/*+"?"+d.getTime()*/);
									$('#img-for-canvas').attr('src',a/*+"?"+d.getTime()*/);
									
									
							});
							
							//$('#ans-script-img').attr('src',imgsrc/*+"?"+d.getTime()*/);
							//$('#img-for-canvas').attr('src',imgsrc/*+"?"+d.getTime()*/);
							
							console.log("checking dafaq\n "
										+"imgsrc: "  +"\n"
										+"ans-script-img src: "+ $('#ans-script-img').attr('src') );
							
							
							//update imgsrcs array 
							updateImgsrcs();
							
							
							
							
						}
						/*
						if( $('#canvas-toggle-button').html() == 'save' ){
							
							var pixeldata = document.getElementById('canvas-id').toDataURL('image/jpeg');
							
							$('#ans-script-img').attr('src',pixeldata);
						}*/
						
					});
					
					//toggle for viewing original img / drawing img
					$('#view-original-button').on('click',function(){
						
						if($('#ans-script-image-div').css('display') != 'none' ){
							$('#ans-script-image-div').hide();
							$('#ans-script-canvas-div').hide();// just in case
							$('#original-ans-script-image-div').show();
							$('#view-original-button').html("< back");
							
							//hide buttons
							$('#canvas-toggle-button').hide();
							//$('#prev-img-button').hide();
							//$('#next-img-button').hide();
							$('#zoomOut-button').hide();
							
							
						} else if ($('#original-ans-script-image-div').css('display') != 'none'){
							$('#original-ans-script-image-div').hide();
							$('#ans-script-canvas-div').hide();// just in case
							$('#ans-script-image-div').show();
							$('#view-original-button').html("View Original");
							
							//show buttons
							$('#canvas-toggle-button').show();
							//$('#prev-img-button').show();
							//$('#next-img-button').show();
							$('#zoomOut-button').show();
							
						}
						
					});
					
					
		</script>
		<!-- Answer Script - canvas or zoom toggle JS- end -->
		
		
		<!-- Marks entry Div - start -->
		<div id="marks-entry-div" style="">
		
			<script>
				var totalMaxMarks=0;
			</script>
			
			<script>
				//script to count num of errors in marks entry
				// its counted by using the num of marksTBs with class: 'err'
				function errCount (){
					return $('.err').length;
				}
				
			</script>
		
			<div id="marks-entry-table-div" style="" >
				
				
				<table> <!-- Table to display courseid and HidId -->
					<tr>
						<td>Hidden Id: <b><?php echo $HidId; ?></b></td>
						<td>Course Id: <b><?php echo $courseid; ?></b></td>
					</tr>
				</table>
				
				<form id="marks-form" action="submitMarks.php" method="post" >
				<?php 
					// set session variable: QpId
					//$_SESSION['QpId']="qp01";
					//$_SESSION['studid']="stud1";
					include 'tablecreation.php';
				?>
				
				
				<!--
				<script>
					$(document).ready(function(){
						$.post("preloadMarks.php",{
							marksids: $('#marksids').val() ,
							remarksids : $('#remarksids').val()
						});
					});
				</script>
				-->
				</form>
			
			
			</div>
			
			<div id="marks-entry-controls-div" style="" >
				<p style="margin-left:10px;margin-top:10px;display:inline-block;" >TOTAL:</p>
				<input id="total-marks-text-field" type="text" size="2" style="margin-left:7px;" maxlength="3" readonly />
				<b>/</b>
				<input id="total-max-marks-text-field" type="text" size="2" style="margin-left:7px;" maxlength="3" readonly />
				<button id="marks-entry-submit-button" class="round-edge-button green-button move" style="float:right;margin-right:10px;margin-top:10px;padding:8px 16px;">submit</button>
				
			
			</div>
			
			<script src="totalMarksCalculation.js" >
				//jquery for calculating total marks for the preloaded marks
			</script>
			
			<script>
				//display the total-max-marks-text-field
				document.getElementById('total-max-marks-text-field').value = totalMaxMarks;
			</script>
			
			<script>
				//script to take care of submit
				
				$('#marks-entry-submit-button').on('click', function(){
					
					//check for errors
					if(errCount() > 0){
						dispErr("Cannot submit! Please rectify all errors.");
					} else {
						//set session vars
						<?php
							//$_SESSION['studid'] = 'stud1';
							//$_SESSION['QpId'] = 'qp01';
						?>
						$('#marks-form').submit();
					}
					
				});
			
			
			</script>
			
			
		</div>
		<!-- Marks entry Div - end -->
		
		<!-- Status Bar Div - start -->
		<div id="status-div" style="float:left;width:100%;height:30px;background-color:#a6a6a6;overflow:auto;">
			<div id="progress-bar" style="margin-left:10px;margin-right:10px;background-color:#a6a6a6;height:3px;" >
				<div style="width:10%;background-color:#3e8e41;height:3px;display:inline-block;">
				</div>
			</div>
			
			<input type="text" id="err-disp-text-field" class="transparent" value="Hello" readonly />
			
			<input type="text" id="correction-percent-text-field" class="transparent" value="0% corrected" readonly />
			
			<script src="progressBarJS.js">
			</script>
			
		</div>
		
		
		<!-- Status Bar Div - end -->
		
		<!-- Display Welcome message -->
		<script>dispErr("Welcome!",'green')</script>
		
		<!-- Initial call for updateProgressBar() -->
		<script>
			updateProgressBar();
		</script>
		
		
	</body>
</html>
</body>
</html>
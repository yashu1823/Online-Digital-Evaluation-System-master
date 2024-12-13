var canvas, ctx, flag = false,
	prevX = 0,
	currX = 0,
	prevY = 0,
	currY = 0,
	dot_flag = false;

var x = "red",
	y = 2;
/*
	body.onload = function() {
    var canvas_ = document.getElementById("canvas-id");
	document.write("<br /><br /><br /><br /><br /><br /><br />Helloqwertyuiopasdfghjklzxcvbnmqwertyuiopasdfghjklzxcvbnm");
    var ctx_ = canvas_.getContext("2d");
    var img_ = document.getElementById("img-for-canvas");
    ctx_.drawImage(img_, 10, 10, 100, 100);
    };
	
	function temp_init() {
    
    var img_ = document.getElementById("img-for-canvas");
    ctx.drawImage(img_, 10, 10, 200, 100);
    }
*/
var img_;
function init(canv,img_t) {
	console.log("in init - canvasScript.js \n");
	//canvas = document.getElementById('canvas-id');
	canvas=canv;
	ctx = canvas.getContext("2d");
	w = canvas.width;
	h = canvas.height;

	canvas.addEventListener("mousemove", function(e) {
		findxy('move', e)
	}, false);
	canvas.addEventListener("mousedown", function(e) {
		findxy('down', e)
	}, false);
	canvas.addEventListener("mouseup", function(e) {
		findxy('up', e)
	}, false);
	canvas.addEventListener("mouseout", function(e) {
		findxy('out', e)
	}, false);

	//temp_init();
	img_ = img_t;
	
	
	
	ctx.drawImage(img_, 0, 0, 426, 568);
	console.log("done draw");
}



function color(obj) {
	console.log("in color\n");
	switch (obj.id) {
		case "green":
			x = "green";
			break;
		case "blue":
			x = "blue";
			break;
		case "red":
			x = "red";
			break;
		case "yellow":
			x = "yellow";
			break;
		case "orange":
			x = "orange";
			break;
		case "black":
			x = "black";
			break;
		case "white":
			x = "white";
			break;
	}
	if (x == "white") y = 14;
	else y = 2;

}

function draw() {
	console.log("in draw\n");
	ctx.beginPath();
	ctx.moveTo(prevX, prevY);
	ctx.lineTo(currX, currY);
	ctx.strokeStyle = x;
	ctx.lineWidth = y;
	ctx.stroke();
	ctx.closePath();
}

function erase() {
	//var m = confirm("Want to clear");
	//if (m) {
		//console.log(document.getElementById('canvas-id').toDataURL('image/jpeg'));
		ctx.clearRect(0, 0, w, h);
		//document.getElementById("canvasimg").style.display = "none";
		//var img_ = document.getElementById("img-for-canvas");
		ctx.drawImage(img_, 0, 0, 426, 568);
		
		//init(canvas,img_);
	//}
}

function save() {
	console.log("in save\n");
	document.getElementById("canvasimg").style.border = "2px solid";
	document.getElementById("canvasimg").crossOrigin = "Anonymous";
	//document.write("Helloqwertyuiopasdfghjklzxcvbnmqwertyuiopasdfghjklzxcvbnm");
	var dataURL = canvas.toDataURL();
	//document.write("123Helloqwertyuiopasdfghjklzxcvbnmqwertyuiopasdfghjklzxcvbnm");
	//document.write(dataURL);
	document.getElementById("canvasimg").src = dataURL;
	document.getElementById("canvasimg").style.display = "block";
}

function findxy(res, e) {
	if (res == 'down') {
		prevX = currX;
		prevY = currY;
		currX = e.clientX - canvas.offsetLeft;
		currY = e.clientY - canvas.offsetTop;

		flag = true;
		dot_flag = true;
		if (dot_flag) {
			ctx.beginPath();
			ctx.fillStyle = x;
			ctx.fillRect(currX, currY, 2, 2);
			ctx.closePath();
			dot_flag = false;
		}
	}
	if (res == 'up' || res == "out") {
		flag = false;
	}
	if (res == 'move') {
		if (flag) {
			prevX = currX;
			prevY = currY;
			currX = e.clientX - canvas.offsetLeft;
			currY = e.clientY - canvas.offsetTop;
			draw();
		}
	}
}
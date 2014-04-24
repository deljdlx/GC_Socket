<!doctype html>



<html>

<head>

<link rel="stylesheet" href="style.css"/>

<script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="jquery-mousewheel/jquery.mousewheel.min.js"></script>
<script src="block.js"></script>

<script src="../library/websocket/client.js"></script>
<script src="../library/websocket/message.js"></script>


</head>




<body>





<div class="viewport">


	<div style="position: absolute; top:50%; left:0; width:100%; border-top:solid#A00 2px; height:1px"></div>
	<div style="position: absolute; left:50%; top:0; width:1px; border-left:solid#A00 2px; height:100%"></div>
	<div style="position: absolute; top:50%; left:50%; height:32px; width:32px; border:solid #A00 2px; margin-left:-16px; margin-top:-16px;"></div>


	<div class="universe">

		<div class="assembly"></div>

		<div class="camera"></div>
	</div>

</div>




</body>

<script>




Element.prototype.transform=function(value) {
	this.style.transform=value;
	this.style.WebkitTransform=value;
	this.style.MozTransform=value;
}




function rotateAssembly(x, y) {
	jQuery('.assembly').css({
		'-moz-transform': 'rotateY('+x+'deg) rotateX('+y+'deg)',
		'-webkit-transform': 'rotateY('+x+'deg) rotateX('+y+'deg)',
	});
}




var universeOffsetX=jQuery('.viewport').width()/2;
var universeOffsetY=jQuery('.universe').height()/8;



//=====================================================================================================
//websocket event handling
//=====================================================================================================

var client=new GC_Client('ws://127.0.0.1:8000/test');
client.connect();


client.on('synchronize', function(data) {
	
	if(data.data.x!==null) {
		rotateAssembly(data.data.x, data.data.y);
	}
	else if(data.data.z!==null){
		var universe=jQuery('.universe').get(0);
		universe.transform('translateZ('+data.data.z+'px)');
	}
});

//=====================================================================================================
//block initialisation
//=====================================================================================================

var block=new Block(300,150,150, 0,0, 0);
block.render('.assembly');
block.rotateY(30);


//=====================================================================================================
//event handling
//=====================================================================================================

document.onmousemove=function(event) {
	if(!drag) {
		return false;
	}

	rotateAssembly(event.clientX, event.clientY);

	client.send('synchronise', {
		'x': event.clientX,
		'y': event.clientY,
		'z':null
	});
	return false;
}



$(document).on('mousewheel', function(event) {
	
	var universe=jQuery('.universe').get(0)

	if(universe.translateZ) {
		universe.translateZ+=event.deltaY*30;
	}
	else {
		universe.translateZ=event.deltaY*30;
	}

	universe.transform('translateZ('+universe.translateZ+'px)');

	client.send('synchronise', {
		'x': null,
		'y': null,
		'z':universe.translateZ
	});


});







document.onmousedown=function(event) {
	if(!drag) {
		drag=true;
		xStart=event.clientX;
		yStart=event.clientY;
	}
}

document.onmouseup=function(event) {
	drag=false;
	xStart=0;
	yStart=0;
}








var drag=false;
var xStart=0;
var yStart=0;

var currentX=0;
var currentY=0;








</script>


</html>



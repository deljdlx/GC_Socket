<?php
	
	if(!empty($_FILES)) {
		foreach($_FILES as $file) {
		
			$fileId=uniqid(true);
			
			$uploadFilepath=__DIR__.'/uploaded';
			$filepath=$uploadFilepath.'/'.$fileId;
			mkdir($filepath);
			
			$result=move_uploaded_file($file['tmp_name'], $filepath.'/file.data');
			file_put_contents($filepath.'/meta.json', json_encode($file));
			
			echo $fileId;
			
		}
		exit();
	}

?>
<!doctype html>
<html>
<head>

<script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="../library/websocket/client.js"></script>
<script src="../library/websocket/message.js"></script>


<style>

.webDropUser {
	width:100px;
	height: 100px;
	background-color:#CCC;
	border-radius:100%;
	border: solid 1px #666;
	display: inline-block;
	margin: 10px;
	position: absolute;
	top: 50px;
	z-index:100%

	top:25%;
	left:50%;
	margin-left:-50px;

}

.webDropUser:hover {
	background-color:#FFC;
}



html, body {
	height:100%;
	width:100%;
	margin: 0;
	overflow: hidden;
}


.main {
	height:100%;
	width:100%;
}

.circle {
	border: solid 1px #999;
	border-radius:100%;
	position: absolute;
	left:50%;
	top:100%;
}

.circle_0 {width:50px; height:50px; margin-top:-25px; margin-left:-25px;}
.circle_1 {width:100px; height:100px; margin-top:-50px;margin-left:-50px;}
.circle_2 {width:150px; height:150px; margin-top:-75px;margin-left:-75px;}
.circle_3 {width:200px; height:200px; margin-top:-100px;margin-left:-100px;}



.scanner {
	position: absolute;
	height:100%;
	left:50%;
	width:3px;

	box-shadow: -0px 0 10px #0A0;


	background-color:#999;
	top:0;
}


.scanner {
	transform-origin: 0 100%;

   animation-name:rotation;
	-webkit-animation-name:rotation;
   -moz-animation-name:rotation;
	-o-animation-name:rotation;
   
	animation-duration:3s;
	-webkit-animation-duration:3s;
   -moz-animation-duration:3s;
	-o-animation-duration:3s;

   animation-iteration-count:infinite;
	-webkit-animation-iteration-count:infinite;
   -moz-animation-iteration-count:infinite;
	-o-animation-iteration-count:infinite;
   animation-timing-function:linear;
	-webkit-animation-timing-function:linear;
   -moz-animation-timing-function:linear;
	-o-animation-timing-function:linear; 
}


@keyframes rotation{
   from {transform:rotate(0deg);} to {transform:rotate(360deg);}
}
@-webkit-keyframes rotation{
   from {-webkit-transform:rotate(0deg);} to {-webkit-transform:rotate(360deg);}
}
@-moz-keyframes rotation{
   from {-moz-transform:rotate(0deg);} to {-moz-transform:rotate(360deg);}
}
@-o-keyframes rotation{
   from {-o-transform:rotate(0deg);} to {-o-transform:rotate(360deg);}
} 


</style>


</head>
<script src="webdropuser.js"></script>
<body>

<div class="main">
	<div class="scanner"></div>
</div>








<div class="userList">

</div>



<iframe id="download" style="display:none"></iframe>

</body>


<script>

var containerCircle=jQuery('.main');
var increment=80;
for(var i=1; i<30; i++) {
	containerCircle.append('<div class="circle" style="width:'+(i*increment)+'px; height:'+(i*increment)+'px; margin-top:-'+(i*increment/2)+'px;margin-left:-'+(i*increment/2)+'px;"></div>');
}

var client=new GC_Client('ws://192.168.1.4:8000/test');

client.on('connect', function(data) {
	jQuery('#input').show();
});

client.on('disconnect', function(data) {
	console.debug(data);
	jQuery('.webDropUser[data-id='+data.data.id+']').remove();
});


client.on('message', function(data) {
	console.debug(data.data.id);
});

client.on('userList', function(data) {

	var container=jQuery('.userList');

	var userlist=data.data;

	for(var i=0; i<userlist.length; i++) {
		if(!jQuery(container).find('.webDropUser[data-id='+userlist[i].id+']').length) {
			var user=new WebDropUser(userlist[i].id);
			jQuery(container).append(user.getElement());
		}
	}
});


client.on('downloadFile', function(data) {
	var iframe=document.getElementById('download');
	iframe.src=data.data.file
});


client.connect();




function FileUpload(file, clientId) {
	var reader = new FileReader();  
	var xhr = new XMLHttpRequest();
	this.xhr = xhr;
  
	var self = this;
	this.xhr.upload.addEventListener("progress", function(e) {
		if (e.lengthComputable) {
			var percentage = Math.round((e.loaded * 100) / e.total);
		}
	}, false);


	xhr.addEventListener("load", function(e) {

		client.send('fileUploaded', {
			fileId: xhr.responseText,
			userId:clientId
		});
	}, true);


	
	xhr.open("POST", "?");
	xhr.overrideMimeType(file.type);

	var formData = new FormData();
	formData.append(file.name, file, file.name);

	reader.onload = function(evt) {
		xhr.send(formData);
	};
	reader.readAsBinaryString(file);
}



function sendFiles() {
  var inputs = document.querySelectorAll(".obj");
  for (var i = 0; i < inputs.length; i++) {
    new FileUpload(inputs[i].files[0]);
  }
}

jQuery(function() {

	jQuery('#input').submit(function() {
		var message=jQuery('#input input').val();
		jQuery('#input input').val('');
		client.send('message', {message: message});
		return false;
	});

});









</script>


</html>
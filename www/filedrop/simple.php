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
	width:200px;
	height: 200px;
	background-color:#CCC;
	border-radius:100%;
	border: solid 1px #666;
	display: inline-block;
	margin: 10px;
}

.webDropUser:hover {
	background-color:#FFC;
}


</style>


</head>
<script src="webdropuser.js"></script>
<body>

<div class="userList">

</div>



<iframe id="download" style="display:none"></iframe>

</body>


<script>

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
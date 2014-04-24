<!doctype html>
<html>
<head>

<script src="http://code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="library/websocket/client.js"></script>
<script src="library/websocket/message.js"></script>


</head>
<body>



<div id="content"></div>


<form id="input" style="display:none">
<input/>
</form>

</body>


<script>


var client=new GC_Client('ws://172.21.9.113:8000/test');

client.on('connect', function(data) {
	jQuery('#input').show();
});

client.on('message', function(data) {
	var content='<div>'+data.data.message+'</div>';
	jQuery('#content').append(content);
});


client.connect();

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
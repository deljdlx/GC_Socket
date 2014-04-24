<!doctype html>
<html>
<head>


<script src="library/websocket/client.js"></script>
<script src="library/websocket/message.js"></script>


</head>
<body>



</body>


<script>


var client=new GC_Client('ws://172.21.9.113:8000/test');

client.on('connect', function(data) {
	client.send('echo', {
		'message1': 'hello',
		'message2': 'world'
	});
});




client.connect();


</script>


</html>
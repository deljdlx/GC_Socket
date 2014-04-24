<?php

/* This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://sam.zoy.org/wtfpl/COPYING for more details. */

ini_set('display_errors', 1);
error_reporting(E_ALL);

require(__DIR__ . '/../library/wrench/lib/SplClassLoader.php');



//echo gethostname();
//die();



//$host= gethostname();
//$ip = gethostbyname($host);
//echo $ip;
//die();


$ip='172.21.9.113';

$classLoader = new SplClassLoader('Wrench', __DIR__ . '/../library/wrench/lib');
$classLoader->register();

$server = new \Wrench\Server('ws://'.$ip.':8000/', array(
    'check_origin'               => false,
	/*
    'allowed_origins'            => array(
        '*'
    ),
	*/

// Optional defaults:
//     'check_origin'               => true,
//     'connection_manager_class'   => 'Wrench\ConnectionManager',
//     'connection_manager_options' => array(
//         'timeout_select'           => 0,
//         'timeout_select_microsec'  => 200000,
//         'socket_master_class'      => 'Wrench\Socket\ServerSocket',
//         'socket_master_options'    => array(
//             'backlog'                => 50,
//             'ssl_cert_file'          => null,
//             'ssl_passphrase'         => null,
//             'ssl_allow_self_signed'  => false,
//             'timeout_accept'         => 5,
//             'timeout_socket'         => 5,
//         ),
//         'connection_class'         => 'Wrench\Connection',
//         'connection_options'       => array(
//             'socket_class'           => 'Wrench\Socket\ServerClientSocket',
//             'socket_options'         => array(),
//             'connection_id_secret'   => 'asu5gj656h64Da(0crt8pud%^WAYWW$u76dwb',
//             'connection_id_algo'     => 'sha512'
//         )
//     )
));

include(__DIR__.'/websocketserver.php');


$application=new WebsocketServer();

$application->on('disconnect', function($application, $client) {
	echo "\n===================\nDisconnected : ".$client->getId().''."\n===================\n";
	return true;
});

$application->on('connect', function($application, $client) {
	echo "\n===================\nConnection : ".$client->getId().''."\n===================\n";
	return true;	
});


/*
$application->on('update', function($application, $client) {
	echo "\n===================\nUpdate : ".microtime(true).''."\n===================\n";
	return true;	
});
*/



$application->on('echo', function($application, $client, $message1, $message2=null) {

	return include(__DIR__.'/service/echo.php');

	/*
	echo "\n===================\nEcho : ".$client->getId().' : '.$message1.' - '.$message2."\n===================\n";
	*/
	//return true;		
});







$server->registerApplication('test', $application);
$server->run();





















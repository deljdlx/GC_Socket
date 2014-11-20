<?php

$fileId=$_GET['fileId'];




$filepath='uploaded/'.$fileId;
$data=json_decode(file_get_contents($filepath.'/meta.json'));
header("Content-Type: application/force-download; name=\"" .$data->name. "\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: ".$data->size);
header("Content-Disposition: attachment; filename=\"" . $data->name . "\"");
header("Expires: 0");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
readfile("./" . $filepath.'/file.data');
exit(); 




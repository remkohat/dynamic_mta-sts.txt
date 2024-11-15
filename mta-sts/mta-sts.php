<?php

if ($_SERVER['HTTPS']!="on") {
	$redirect= "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	header("Location:$redirect");
}

$headers = getallheaders();

header('Content-Type: text/plain');

include_once 'snippet/mta-sts.php';

echo $mta_sts;

?>
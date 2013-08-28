<?php

$headers = apache_request_headers();
$header = "";
foreach ($headers as $k => $v) {
	$header .= "$k: $v \n";
}

$body = @file_get_contents('php://input');
file_put_contents('lastmessage.txt',$header . "\n\n" . $body);

echo "Done";
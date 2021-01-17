<?php
	$url = 'http://localhost/yeelight_api/products/';
	$data = array('name' => $_POST['name'], 'ip_address' => $_POST['ip_address'], 'type' => $_POST['type']);

	$options = array(
		'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	if ($result === FALSE) {  }

	echo $result;
?>
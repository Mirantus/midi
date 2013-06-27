<?php	
	function read_file($source)
	{	
		global curl_connect;		
		curl_setopt($curl_connect, CURLOPT_URL, $source);		
		return curl_exec($curl_connect);
	}	
	
	$curl_connect = curl_init();	
	curl_setopt($curl_connect, CURLOPT_RETURNTRANSFER, 1);
?>
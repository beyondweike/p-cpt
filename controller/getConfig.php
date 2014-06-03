<?php
/*
	getConfig.php discard or for 1.0
*/

	include_once("../common/request.function.php");
	include_once("service.class.php");
	
    $headers=getAllHeadersLowerCase();
    $productCode=$headers["productcode"];
	$dir=Service::getDir($productCode);
	
	//print_r($headers);

	//add response header
	header("encryptKey: 123456");

	if($dir)
	{
		$path="../".$dir."/config/config.json";
		echo getLocalContents($path);
	}
	else
	{
		echo "haha";
	}
?>

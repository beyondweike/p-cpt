<?php
    include_once("../common/file.function.php");
	//include_once("service.class.php");
	
	//$headers=getAllHeadersLowerCase();
    //$productCode=$headers["productcode"];
	//$dir=Service::getDir($productCode);
	
	//print_r($headers);
	
	if (isset($_GET['imageName']))
	{
		$imageName=$_GET['imageName'];
		$filePathName="../userImages/".$imageName;
		
		//header("Content-type: application/octet-stream");
		header("Content-type: image/jpg");
    	header("Content-Disposition: attachment; filename=\"$imageName\"");
		
    	echo getLocalContents($filePathName);
	}
	else
	{
		echo "haha";
	}
	
	
?>

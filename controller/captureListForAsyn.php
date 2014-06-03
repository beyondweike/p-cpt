<?php
    include_once("service.class.php");
    include_once("properties.class.php");
	//include_once("db.function.php");
	
	//call this php url for example
	//http://www.brogrammer.cn/reading/controller/captureListForAsyn.php?categoryCode=0&serviceCode=0&lastCptStep=0
	
    if (isset($_GET['categoryCode']))
	{
		set_time_limit(60*5);
		
		$serviceCode=$_GET['serviceCode'];
		$categoryCode=$_GET['categoryCode'];
		$lastCptStep=$_GET['lastCptStep'];

		//$con=dbConnect();

		$service=Service::getService($serviceCode);
        $categoriesPath="../".$service->dir."/".Properties::getRelativeCategoriesPath();
		$service->captureListObject->recordAndCaptureListPages($categoryCode,$categoriesPath,$lastCptStep);
		
		//dbClose($con);
	}
?>

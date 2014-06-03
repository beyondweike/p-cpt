<?php
    include_once("service.class.php");
	include_once("db.function.php");
    include_once("properties.class.php");
	
	//used replaced by captureListForAsyn.php
	
	//call this php url for example
	//http://192.168.0.103/reading/controller/captureListForAsyn.php?categoryCode=0&serviceCode=0

    if (isset($_GET['categoryCode']))
	{
		set_time_limit(60*5);
		
		$serviceCode=$_GET['serviceCode'];
		$categoryCode=$_GET['categoryCode'];
        
        //echo "categoryCode: ".$categoryCode." abc";
        
        $con=dbConnect();
        
		$service=Service::getService($serviceCode);
        $categoriesPath="../".$service->dir."/".Properties::getRelativeCategoriesPath();
		$service->captureListObject->checkToCaptureListPages($categoryCode,$categoriesPath);
	
        dbClose($con);
	}
?>

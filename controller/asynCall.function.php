<?php
    include_once("../common/request.function.php");
    include_once("../common/captureRecord.function.php");
	include_once("../common/file.function.php");
	
	/*
    function callAsynCheckCaptureList($serviceCode,$categoryCode)
    {
        $uri="/reading/controller/checkCaptureListForAsyn.php?serviceCode=".$serviceCode."&categoryCode=".$categoryCode;
        asynGetRequestUri($uri);
    }
	*/
	
	function checkToCallAsynCaptureList($serviceCode,$categoryCode)
    {
		$lastCptStep=0;
		$capture=isTimeToCapture($serviceCode,$categoryCode,$lastCptStep);
	
		//test
		//$capture=true;
		
		if($capture)
		{
			//update immediately use a const value
			$cptPeriodHour=6;
			updateCaptureTime($serviceCode,$categoryCode,$cptPeriodHour,$lastCptStep);
			
        	$uri="/reading/controller/captureListForAsyn.php?"."serviceCode=$serviceCode&categoryCode=$categoryCode&lastCptStep=$lastCptStep";
        	asynGetRequestUri($uri);
		}
		
		return $capture;
    }
 
?>

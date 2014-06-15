<?php
    include_once("db.function.php");
    include_once("asynCall.function.php");
	include_once("service.class.php");
	
	//for <=1.11
	
	//format example  "0,11;1,12;2,13;3,14;4,15;5,16;";
	
	$headers=getAllHeadersLowerCase();
	$productCode=$headers["productcode"];
	$tableName=Service::getTableName($productCode);
	
	$typeTopIdsStr=NULL;
	$typeNewCountsStr=NULL;
	
	if (isset($_GET['param']))
	{
		$typeTopIdsStr=$_GET['param'];
	}
	
	if($typeTopIdsStr)
	{
		$typeTopIdStrArray = explode(";",$typeTopIdsStr);
		
		$con=dbConnect();
		
		$capture=false;

		foreach ($typeTopIdStrArray as $typeTopIdStr)
		{
			$typeTopIdArray = explode(",",$typeTopIdStr);
			if(count($typeTopIdArray)>=2)
			{
				$categoryCode=$typeTopIdArray[0];
				$topItemId=$typeTopIdArray[1];
				
				$count=Item::queryNewestCount($topItemId,$categoryCode,$tableName);
				
				$typeNewCountsStr=$typeNewCountsStr.$categoryCode.",".$count.";";
				
				if(!$capture)
				{
					$capture=checkToCallAsynCaptureList($productCode,$categoryCode);
				}
			}
		}

		dbClose($con);
	}
	
	echo $typeNewCountsStr;
?>

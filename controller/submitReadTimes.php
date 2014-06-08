<?php
    include_once("db.function.php");
    include_once("asynCall.function.php");
	include_once("service.class.php");
	
	//format example  "0,11;1,12;2,13;3,14;4,15;5,16;";
	
	$headers=getAllHeadersLowerCase();
	$productCode=$headers["productcode"];
	$tableName=Service::getTableName($productCode);
	
	$ret=0;
	$articleIdReadTimesStr=NULL;
	
	if (isset($_POST['param']))
	{
		$articleIdReadTimesStr=$_POST['param'];
	}
	
	if($articleIdReadTimesStr)
	{
		$pairsArray = explode(";",$articleIdReadTimesStr);

		$con=dbConnect();
		
		foreach ($pairsArray as $pairStr)
		{
			$arr = explode(",",$pairStr);
			if(count($arr)>=2)
			{
				$articleId=$arr[0];
				$readTimes=$arr[1];
				
				$ret=Item::addReadTimes($articleId,$readTimes,$tableName);
			}
		}

		dbClose($con);
	}
	
	echo json_encode(array('success'=>$ret?1:0));
?>

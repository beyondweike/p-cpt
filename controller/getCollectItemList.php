<?php
    include_once("db.function.php");
	include_once("properties.class.php");
    include_once("service.class.php");
    include_once("../common/collectItem.class.php");
	
	$valide=FALSE;
	
    $headers=getAllHeadersLowerCase();
    $encrypt=$headers["encrypt"];//must use lower case
	$productCode=$headers["productcode"];//must use lower case
	
	//print_r($headers);

	if($encrypt)
	{
		$properties=Properties::getProperties();
		$encrypt1=$properties->lastEncrypt;
		$encrypt2=$properties->encrypt;
		
		if($encrypt==$encrypt1 || $encrypt==$encrypt2)
		{
			$valide=TRUE;
		}
	}
    
    //test
    //$valide=true;
    //$productCode=0;
	
	if(!$valide)
	{
		return NULL;
	}

	$dataArray=NULL;

	$pageSize=20;
	if (isset($_GET['pageSize']))
	{
		$pageSize=$_GET['pageSize'];
	}
	
	$userId=0;
	if (isset($_GET['userId']))
	{
		$userId=$_GET['userId'];
	}
	
	if($userId>0)
	{
		$con=dbConnect();
		
		$service=Service::getService($productCode);
		
		if (isset($_GET['lastCollectId']))
		{
			$lastCollectId=$_GET['lastCollectId'];
	
			$dataArray=CollectItem::queryMore($lastCollectId,$userId,$productCode,$pageSize,$service->tableName);
		}
		else if (isset($_GET['topCollectId']))
		{
			$topCollectId=$_GET['topCollectId'];
	
			if($topCollectId>0)
			{
				$pageSize=$pageSize*5;
			}
			$dataArray=CollectItem::queryNewest($topCollectId,$userId,$productCode,$pageSize,$service->tableName);
		}
	
		dbClose($con);
	}

	echo json_encode(array('success'=>true,'pageSize'=>$pageSize,'dataArray'=>$dataArray));
?>

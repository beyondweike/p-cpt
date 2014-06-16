<?php
    include_once("db.function.php");
	include_once("properties.class.php");
    include_once("service.class.php");
    include_once("../common/extractItem.class.php");
	
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
		
		if (isset($_GET['lastExtractId']))
		{
			$lastExtractId=$_GET['lastExtractId'];
	
			$dataArray=ExtractItem::queryMore($lastExtractId,$userId,$productCode,$pageSize);
		}
		else if (isset($_GET['topExtractId']))
		{
			$topExtractId=$_GET['topExtractId'];
	
			if($topExtractId)
			{
				$pageSize=$pageSize*5;
			}
			$dataArray=ExtractItem::queryNewest($topExtractId,$userId,$productCode,$pageSize);
		}
	
		dbClose($con);
	}

	echo json_encode(array('success'=>true,'pageSize'=>$pageSize,'dataArray'=>$dataArray));
?>

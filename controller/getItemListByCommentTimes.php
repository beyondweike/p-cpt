<?php
    include_once("db.function.php");
	include_once("properties.class.php");
    include_once("service.class.php");
    include_once("../common/comment.class.php");
	include_once("../common/visitor.class.php");
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
	
	$con=dbConnect();
	
	//add one visit times
	Visitor::updateVisitTimes($headers);
    
    $service=Service::getService($productCode);

	if (isset($_GET['lastItemId']))
	{
		$lastItemId=$_GET['lastItemId'];

		////$dataArray=Comment::queryMoreHotCommentItem($lastItemId,$pageSize,$service->tableName);
		$dataArray=CollectItem::queryMoreHotCollectItem($lastItemId,$pageSize,$service->tableName);
	}
	else if (isset($_GET['topItemId']))
	{
		$topItemId=$_GET['topItemId'];

		if($topItemId>0)
		{
			$pageSize=$pageSize*5;
		}
		////$dataArray=Comment::queryNewestHotCommentItem($topItemId,$pageSize,$service->tableName);
		$dataArray=CollectItem::queryNewestHotCollectItem($topItemId,$pageSize,$service->tableName);
	}

	dbClose($con);

	echo json_encode(array('success'=>true,'pageSize'=>$pageSize,'dataArray'=>$dataArray));
?>

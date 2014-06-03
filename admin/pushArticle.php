<?php
	include_once("../common/device.class.php");
	include_once("../common/item.class.php");
	include_once("../common/push.class.php");
	include_once("../common/request.function.php");
	include_once("../common/file.function.php");
	include_once("../controller/db.function.php");
    include_once("push.function.php");
	
	//http://1679554191.iteye.com/blog/1740055

 	$pushRecordId=0;
	$deviceRecordId=0;//query from the id
	$articleId=0;
	$pageSize=25;
	$serviceCode=0;
	$tableName="list_table";
	$pushTurn=1;
	$MaxPushTurn=2;
	
	if (isset($_GET['articleId']))
	{
		$articleId=$_GET['articleId'];
	}
	
	if($articleId<=0)
	{
		return;
	}
	
	if (isset($_GET['pushTurn']))
	{
		$pushTurn=$_GET['pushTurn'];
	}
	
	if($pushTurn>$MaxPushTurn)
	{
		return;
	}
	
	if (isset($_GET['deviceRecordId']))
	{
		$deviceRecordId=$_GET['deviceRecordId'];
	}
	if (isset($_GET['pushRecordId']))
	{
		$pushRecordId=$_GET['pushRecordId'];
	}

	$con=dbConnect();
	//test
	//$devices=Device::queryTestRecordsFromRecordId($deviceRecordId,$pageSize,$serviceCode);
	$devices=Device::queryFromRecordId($deviceRecordId,$pageSize,$serviceCode);
	$item=Item::queryItemById($articleId,$tableName);
	dbClose($con);

	$ids=NULL;
	$deviceCount=count($devices);
	$pushable=$deviceCount>0 && $item!=NULL;
	$successCount=0;
	
	if($pushable)
	{
		$successCount=pushArticle($item,$devices);
		if($successCount>0)
		{
			foreach($devices as $device)
			{
				if(1==$device->pushState)
				{
					$ids.=$device->id.',';
				}
			}
			
			if(strlen($ids)>0)
			{
				$ids=substr($ids,0,strlen($ids)-1);
			}
		}
	}
	
	$con=dbConnect();
	if($ids)
	{
		Device::updatePushState(1,$ids);
	}
	Push::updatePushProgress($pushRecordId,$successCount,$pushTurn);
	dbClose($con);
				
	sleep(5);//seconds

	if(!$pushable)
	{
		$pushTurn++;
	}
		
	if($pushTurn<=$MaxPushTurn)
	{
		$deviceRecordId=$devices[$deviceCount-1]->id+1;
		$uri="/reading/admin/pushArticle.php?".
				"articleId=$articleId&deviceRecordId=$deviceRecordId&pushRecordId=$pushRecordId&pushTurn=$pushTurn";
		asynGetRequestUri($uri);
	}
?>

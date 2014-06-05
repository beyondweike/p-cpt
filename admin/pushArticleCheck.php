<?php
	include_once("../common/item.class.php");
	include_once("../common/push.class.php");
	include_once("../common/device.class.php");
	include_once("../common/request.function.php");
	include_once("../common/string.function.php");
	include_once("../controller/db.function.php");
	include_once("push.function.php");
	
	//echo "让用户们再睡一会吧";
	//return;
	
	$articleId=0;
	$pushRecordId=0;
	$tableName="list_table";
	$pushable=false;
	$message="";
	$hours=6;
	$item=NULL;
	
	if (isset($_POST['articleId']))
	{
		$articleId=$_POST['articleId'];
	}
	
	if($articleId<=0)
	{
		echo "非法articleId";
		return;
	}
	
	date_default_timezone_set('Asia/Shanghai');
	$hour=date("H",time());//"Y-m-d H:i:s"
	$pushable=$hour>=6 && $hour<=21;
	if($pushable)
	{		 
		$con=dbConnect();
		$pushable=Push::checkPushable($articleId,$hours,$message);
		if($pushable)
		{
			$item=Item::queryItemById($articleId,$tableName);
			$msgLength=0;
			$pushable=isItemPushable($item,$msgLength);
			if(!$pushable)
			{
				$message="文章推送验证失败 ".$msgLength;
			}
		}
		
		if($pushable)
		{
			$pushRecordId=Push::insertPushRecordToDatabase($articleId);
			if($pushRecordId>0)
			{
				Device::updateAllPushState(0);
			}
			else
			{
				$pushable=false;
			}
		}
		
		dbClose($con);
	}
	else
	{
		$message="未在推送时间段（6:00-21:00）";
	}
	
	if($pushable)
	{
		$uri="/reading/admin/pushArticle.php?articleId=$articleId&pushRecordId=$pushRecordId";
    	asynGetRequestUri($uri);
		
		$message="已开始推送";
	}
	
	echo $message;
?>

<?php
    include_once("db.function.php");
	include_once("properties.class.php");
    include_once("service.class.php");
    include_once("../common/item.class.php");
	include_once("../common/push.class.php");
	
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
	
	$list_table_name="list_table";

	$con=dbConnect();
	
	$pushItem=Push::queryLastPushRecord();
	$item=Item::queryItemById($pushItem->articleId,$list_table_name);

	dbClose($con);
	
	$body['type'] = 0 ;
	$body['article'] = array('id' => $item->id,'catecode' => $item->categoryCode,'time' => $item->datetime,'title' => $item->title,'brief' => $item->briefDesc,'readTimes' => $item->readTimes,'shareTimes' => $item->shareTimes,'commentCount' => $item->commentCount);
			
	$payload = json_encode($body,JSON_UNESCAPED_UNICODE);

	echo $payload;
?>

<?php
    include_once("db.function.php");
	include_once("properties.class.php");
    include_once("service.class.php");
    include_once("../common/comment.class.php");
	include_once("../common/share.class.php");
	include_once("../common/collectItem.class.php");
	include_once("../common/item.class.php");
    include_once("../common/visitor.class.php");
	include_once("../common/user.class.php");
	
	$valide=FALSE;
	
    $headers=getAllHeadersLowerCase();
    $encrypt=$headers["encrypt"];//must use lower case
	$productCode=$headers["productcode"];//must use lower case
	$version=$headers["version"];
	
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

	$articleId=0;
	if (isset($_GET['articleId']))
	{
		$articleId=$_GET['articleId'];
	}
	
	if($articleId<=0)
	{
		return NULL;
	}
	
	$readTimes=0;
	$commentCount=0;
	$shareTimes=0;
	$collected=false;
	$list_table_name="list_table";

	$con=dbConnect();
	
	//add one visit times
	Visitor::updateVisitTimes($headers);
	
	if($version<2.0)
	{
		//add one read times
		Item::addOneReadTimes($articleId,$list_table_name);
	}
	
	//comment count
	//$commentCount=Comment::queryCommentCount($articleId,$productCode);
	//share count
	//$shareCount=Share::queryShareCount($articleId,$productCode);
	
	Item::queryStatistics($articleId,$list_table_name,$readTimes,$commentCount,$shareTimes);
	
	//query collected
	if (isset($_GET['userId']))
	{
		$userId=$_GET['userId'];
		if($userId>0)
		{
			$collected=CollectItem::queryArticleCollected($articleId,$userId,$productCode);
			User::updateAddReadCount($userId,1);
		}
	}

	dbClose($con);

	echo json_encode(array('readTimes'=>$readTimes,'commentCount'=>$commentCount,'shareTimes'=>$shareTimes,'collected'=>$collected));
?>

<?php
    include_once("db.function.php");
	include_once("properties.class.php");
    include_once("service.class.php");
	include_once("../common/item.class.php");
    include_once("../common/comment.class.php");
	include_once("../common/share.class.php");

    header("Content-Type:text/html; charset=utf-8");
    
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

	$ret=0;
	$code=0;
	$content=NULL;

	if (isset($_POST['content']))
	{
		$content=$_POST['content'];
	}
	
	if(!$content)
	{
		return NULL;
	}

	$listTableName="list_table";
	$articleId=$_POST['articleId'];
	$userId=0;
	if (isset($_POST['userId']))
	{
		$userId=$_POST['userId'];
	}		
	$deviceId=$headers["deviceid"];//must use lower case,可有助于统计匿名时情况
	
	if($content=="分享到微信" || $content=="分享到新浪微博" || $content=="分享到腾讯微博" || $content=="转发分享")
	{
		$con=dbConnect();
		
		$share=new Share();
		$share->userId=$userId;
		$share->articleId=$articleId;
		$share->shareWay=$content;
		$share->serviceCode=$productCode;
		$share->deviceId=$deviceId;
		
		$ret=$share->insertToDatabase();
		
		Item::addOneShareTimes($articleId,$listTableName);
		
		dbClose($con);
	}
	else
	{
		$content=emoji_unified_to_softbank($content);

		$imageName=NULL;
		if (empty($_FILES) === false)
		{
			$imageName=time().".jpg";
			
			//http://www.5idev.com/p-php_file_upload.shtml
			$filePathName="../userImages/".$imageName;
			//将文件移动到存储目录下
			move_uploaded_file($_FILES["image"]["tmp_name"],$filePathName);
		}
	
		$con=dbConnect();
		
		$comment=new Comment();
		$comment->userId=$userId;
		$comment->articleId=$articleId;
		$comment->content=$content;
		$comment->serviceCode=$productCode;
		$comment->deviceId=$deviceId;
		$comment->imageUrl=$imageName;
		
		$ret=$comment->insertToDatabase();
		
		Item::addOneCommentCount($articleId,$listTableName);
		
		dbClose($con);
	}
	
	echo json_encode(array('success'=>$ret,'code'=>$code,'message'=>""));
?>

<?php
    include_once("db.function.php");
	include_once("properties.class.php");
    include_once("service.class.php");
    include_once("../common/item.class.php");
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
	$shareWay=NULL;
	$userId=0;
	$articleId=$_POST['articleId'];
	if (isset($_POST['shareWay']))
	{
		$shareWay=$_POST['shareWay'];
	}
	if (isset($_POST['userId']))
	{
		$userId=$_POST['userId'];
	}		
	$deviceId=$headers["deviceid"];//must use lower case,可有助于统计匿名时情况
	
	$con=dbConnect();
		
	$share=new Share();
	$share->userId=$userId;
	$share->articleId=$articleId;
	$share->shareWay=$shareWay;
	$share->serviceCode=$productCode;
	$share->deviceId=$deviceId;
	
	$ret=$share->insertToDatabase();
	
	$listTableName="list_table";
	Item::addOneShareTimes($articleId,$listTableName);
	
	dbClose($con);
	
	echo json_encode(array('success'=>$ret,'code'=>$code,'message'=>""));
?>

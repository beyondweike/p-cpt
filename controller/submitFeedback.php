<?php
    include_once("db.function.php");
	include_once("properties.class.php");
    include_once("service.class.php");
    include_once("../common/feedback.class.php");
	
    header("Content-Type:text/html; charset=utf-8");
    
	$valide=FALSE;
	
    $headers=getAllHeadersLowerCase();
    $encrypt=$headers["encrypt"];//must use lower case
	$productCode=$headers["productcode"];//must use lower case
    $deviceId=$headers["deviceid"];//must use lower case
	
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
	
	if(!$valide)
	{
		return NULL;
	}

	$ret=0;
    
    $content=NULL;
    $userId=0;
    
	if (isset($_POST['content']))
	{
        $content=$_POST['content'];
    }
    
    if (isset($_POST['userId']))
	{
        $userId=$_POST['userId'];
    }
    
    $content.="\r\nversion:".$headers["version"];
    $content.="\r\ndeviceModel:".$headers["devicemodel"];
    $content.="\r\ndeviceName:".$headers["devicename"];
    $content.="\r\ndeviceOS:".$headers["deviceos"];
    $content.="\r\ndeviceOSVersion:".$headers["deviceosversion"];
    $content.="\r\nchannel:".$headers["channel"];

    $con=dbConnect();
    
    $feedback=new Feedback();
    $feedback->deviceId=$deviceId;
    $feedback->userId=$userId;
    $feedback->serviceCode=$productCode;
    $feedback->content=$content;

    $ret=$feedback->insertToDatabase();
    
    $message="";
    if($ret)
    {
        $message="感谢您的反馈，请等待我们给您回复邮件。";
    }

    dbClose($con);

	echo json_encode(array('success'=>$ret,'code'=>0,'message'=>$message));
?>

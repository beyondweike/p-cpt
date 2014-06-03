<?php
    include_once("db.function.php");
	include_once("properties.class.php");
    include_once("service.class.php");
    include_once("../common/device.class.php");
	
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
    
    $deviceToken=NULL;
    $apnsEnable=0;
    
	if (isset($_POST['deviceToken']))
	{
        $deviceToken=$_POST['deviceToken'];
    }
    
    if (isset($_POST['apnsEnable']))
	{
        $apnsEnable=$_POST['apnsEnable'];
    }
    
    $con=dbConnect();
    
    $device=new Device();
    $device->deviceId=$deviceId;
    $device->apnsEnable=$apnsEnable;
    $device->serviceCode=$productCode;
    if($deviceToken)
    {
        $device->deviceToken=$deviceToken;
    }

    $ret=$device->insertToDatabase();

    dbClose($con);

	echo $ret;
?>

<?php
	include_once("../common/device.class.php");
	include_once("../common/item.class.php");
	include_once("../common/push.class.php");
	include_once("../common/file.function.php");
    include_once("push.function.php");

	$devices = array();

	// Put your device token here (without spaces):
	//$deviceid='A4BED515-D83C-4754-8A75-96A63AA6E7CD';
	//$deviceToken = '912bb4c23a6d82324df9f5baf6c51392dc635f32466e272ed64e05c1d96cdce3';//development
	$deviceToken = '4923c70e422b4c70c48f5a36da0c3baca51af9bc4c79c447d2e2f8629399714f';//product
	$device=new Device();
	$device->deviceToken = $deviceToken;
	////$devices[]=$device;
	
	//touch5
	//$deviceid='38BBC094-B2D7-47C2-A0C5-9FB0E24714DF';
	$deviceToken = 'd030ee7aabed3807920b301615a366ca42583167c00b10188e45579ffc213348';//development
	//$deviceToken = '104718d1fd9961c7f9c84c734a155ec4d66f0223f5c16af53a9bed6aaf60fd22';//product
    $device=new Device();
	$device->deviceToken = $deviceToken;
	$devices[]=$device;
    
	$item=new Item();
    $item->articleId=3;
    $item->title="下波App机会在哪里";
    $item->categoryCode=0;
    $item->datetime="2013-10-15";
    
	$successCount=pushArticle($item,$devices,true);
	
	echo "success count: ".$successCount;
?>

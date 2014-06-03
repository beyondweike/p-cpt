<?php
    include_once("../controller/db.function.php");
	include_once("../controller/service.class.php");
	include_once("../common/item.class.php");
?>


<?php
	$serviceCode=0;
	$service=Service::getService($serviceCode);

	$categoryCode=0;
	$pageSize=20;
	$lastItemId=0;
	if(isset($_GET['lastItemId']))
	{
		$lastItemId=$_GET['lastItemId'];
	}
	if(isset($_GET['categoryCode']))
	{
		$categoryCode=$_GET['categoryCode'];
	}
	
	$con=dbConnect();
	$itemArray=Item::queryMore($lastItemId,$categoryCode,$pageSize,$service->tableName);

	foreach($itemArray as $item)
	{
		echo $item->title." - ".$item->datetime." - <br><br>";
	}

	dbClose($con);
?>

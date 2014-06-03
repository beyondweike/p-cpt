<?php
    include_once("../common/extractItem.class.php");
	include_once("../controller/db.function.php");

	$con=dbConnect();

	$extracts=ExtractItem::queryAllExtracts();
	foreach($extracts as $extract)
	{
		echo $extract->content." - ".$extract->extractTime." - ".$extract->userId." - ".$extract->id."<br><br>";
	}

	dbClose($con);
?>

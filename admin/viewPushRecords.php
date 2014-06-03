<?php
    include_once("../common/push.class.php");
	include_once("../controller/db.function.php");
	
	
	echo "ID - ArticleID - PushCount - Finished - CreateTime - FinishTime <br><br>";

	$con=dbConnect();

	$records=Push::queryAllPushRecord();
	foreach($records as $record)
	{
		echo $record->id." - ".$record->articleId." - ".$record->pushCount." - ".$record->finished." - ".$record->createTime." - ".$record->finishTime."<br><br>";
	}

	dbClose($con);
?>

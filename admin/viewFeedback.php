<?php
    include_once("../common/item.class.php");
	include_once("../controller/db.function.php");

	$con=dbConnect();

	$sql="select content,feedBackTime,deviceId from feedback_table";
	 $result = mysql_query($sql);
	 while($row = mysql_fetch_array($result))
	 {
		 echo $row['content']." - ".$row['feedBackTime']." - ".$row['deviceId']."<br><br>";
	 }

	dbClose($con);
?>

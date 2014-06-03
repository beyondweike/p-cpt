<?php
    include_once("../common/item.class.php");
	include_once("../controller/db.function.php");
	
	$productCode=0;
	$service=Service::getService($productCode);

	$con=dbConnect();

	
	$sql="select content from feedback_table";
		 $result = mysql_query($sql);
		 while($row = mysql_fetch_array($result))
		 {
			 echo $row['content']."<br><br>";

		 }
		 
		 
	dbClose($con);
?>

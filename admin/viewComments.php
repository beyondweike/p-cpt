<?php
    include_once("../common/comment.class.php");
	include_once("../controller/db.function.php");

	$con=dbConnect();

	$sql="select id,content from comment_table";
	 $result = mysql_query($sql);
	 while($row = mysql_fetch_array($result))
	 {
		 echo $row['id']." - ".$row['content']."<br><br>";
	 }

	dbClose($con);
?>

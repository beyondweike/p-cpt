<?php
    include_once("db.function.php");
    include_once("../common/capture.function.php");
	
	$con=dbConnect();
    resetAllCaptureTime();
	dbClose($con);

	echo "reseted";
?>

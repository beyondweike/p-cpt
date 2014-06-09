<?php
    include_once("../common/user.class.php");
	include_once("../controller/db.function.php");

	$con=dbConnect();

	$users=User::queryAllUser();
	foreach($users as $user)
	{
		echo $user->username." - ".$user->email." - ".$user->registerTime." - ".$user->deviceId."<br><br>";
	}

	dbClose($con);
?>

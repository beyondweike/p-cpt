<?php
    include_once("../common/user.class.php");
	include_once("../controller/db.function.php");
	include_once("../common/Category.class.php");
	include_once("../controller/service.class.php");
?>


<?php
	$serviceCode=0;
	$service=Service::getService($serviceCode);
    $categoriesPath="../".$service->dir."/".Properties::getRelativeCategoriesPath();
		
	$rootCategory=new Category();
	$rootCategory->parsePath($categoriesPath);

	foreach ($rootCategory->categories as $firstSubCategory)
	{
		foreach ($firstSubCategory->categories as $secondSubCategory)
		{
			echo "<a href='articleList.php?categoryCode=$secondSubCategory->code' target='_blank'>";
			echo $firstSubCategory->name.'  '.$secondSubCategory->name;
			echo "</a>";
			echo "</br>";
		}
		echo '<br>';
	}
			
	//$con=dbConnect();

	//$users=User::queryAllUser();
	//foreach($users as $user)
	//{
	//	echo $user->username." - ".$user->email." - ".$user->registerTime."<br><br>";
	//}

	//dbClose($con);
?>

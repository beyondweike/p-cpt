<?php
    include_once("../controller/db.function.php");
	include_once("../controller/service.class.php");
	
	$headers=getAllHeadersLowerCase();
	$productCode=$headers["productcode"];
	$tableName=Service::getTableName($productCode);
	
	$articleIds=NULL;
    $categoryCodes=NULL;
	
	if (isset($_POST['articleIds']))
	{
		$articleIds=$_POST['articleIds'];
	}
    if (isset($_POST['categoryCodes']))
	{
		$categoryCodes=$_POST['categoryCodes'];
	}

    $ret=FALSE;
    
    //if("0")的问题 
    if($articleIds && $categoryCodes!==NULL)
    {
		$articleIdArray = explode(",",$articleIds);
        $categoryCodeArray = explode(",",$categoryCodes);
		
        $count=count($articleIdArray);
        
        //test
		//echo $articleIds.$categoryCodes."-".$count;
        
        $con=dbConnect();
        for ($i=0;$i<$count;$i++)
		{
            $ret=Item::updateCategory($articleIdArray[$i],$categoryCodeArray[$i],$tableName);
		}
		dbClose($con);
    }
    
	echo json_encode(array('success'=>$ret));
?>

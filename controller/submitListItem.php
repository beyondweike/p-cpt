<?php
    include_once("db.function.php");
	include_once("properties.class.php");
    include_once("service.class.php");
    include_once("../common/item.class.php");
	include_once("checkEncrypt.function.php");

    header("Content-Type:text/html; charset=utf-8");
    
	$valide=FALSE;
	
    $headers=getAllHeadersLowerCase();
    $encrypt=$headers["encrypt"];//must use lower case
	$productCode=$headers["productcode"];//must use lower case
	
	//print_r($headers);

	if(!checkEncrypt($encrypt))
	{
		return NULL;
	}

	$ret=0;
	$id=0;

	if (isset($_POST['href']))
	{
		$href=$_POST['href'];
        $title=$_POST['title'];	
		$categoryCode=$_POST['categoryCode'];
	
        $con=dbConnect();
        
		$tableName="list_table";
        $item=new Item();
        $item->href=$href;
        $item->title=$title;
        $item->serviceCode=$productCode;
		$valid=$item->preproccessValues();
		if($valid)
		{
			$service=Service::getService($productCode);
        	$categoriesPath="../".$service->dir."/".Properties::getRelativeCategoriesPath();
		
			$categoryPriorityDic=array();
			$rootCategory=new Category();
			$rootCategory->parsePath($categoriesPath);
			$rootCategory->getCategoryPriorityDic($categoryPriorityDic);
			
			$ret=$item->insertItemToDatabase($tableName,$categoryPriorityDic);
			$itemId=$item->id;
		}
        
        dbClose($con);
    }

	echo json_encode(array('success'=>$ret,'itemId'=>$itemId,'message'=>""));
?>

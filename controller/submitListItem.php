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
	$itemId=0;

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
		$item->categoryCode=$categoryCode;
		
		$itemId=Item::queryIdByHref($href,$tableName);
		if($itemId<=0)
		{
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
		}
		else
		{
			$ret=true;
		}
        
        dbClose($con);
    }

	echo json_encode(array('success'=>$ret?1:0,'itemId'=>$itemId,'message'=>""));
?>

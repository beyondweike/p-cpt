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

	$ret=false;
	$itemId=0;
	$message="";

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
		
		$valid=$item->preproccessValues();
		if($valid)
		{
			$ret=$item->checkItemExistsByHref($tableName,$itemId);
			if(!$ret)
			{
				$message="0";
			
				$service=Service::getService($productCode);
				$categoriesPath="../".$service->dir."/".Properties::getRelativeCategoriesPath();
			
				$categoryPriorityDic=array();
				$rootCategory=new Category();
				$rootCategory->parsePath($categoriesPath);
				$rootCategory->getCategoryPriorityDic($categoryPriorityDic);
				
				$ret=$item->insertOrUpdateItemToDatabase($tableName,$categoryPriorityDic);
				$itemId=$item->id;
				
				$message="1";
				
			}
			else
			{
				$message="2";
			}
		}
        
        dbClose($con);
    }

	echo json_encode(array('success'=>$ret?1:0,'itemId'=>$itemId,'message'=>$message));
?>

<?php
    include_once("db.function.php");
	include_once("properties.class.php");
    include_once("service.class.php");
    include_once("../common/collectItem.class.php");

	$valide=FALSE;
	
    $headers=getAllHeadersLowerCase();
    $encrypt=$headers["encrypt"];//must use lower case
	$productCode=$headers["productcode"];//must use lower case
	
	//print_r($headers);

	if($encrypt)
	{
		$properties=Properties::getProperties();
		$encrypt1=$properties->lastEncrypt;
		$encrypt2=$properties->encrypt;
		
		if($encrypt==$encrypt1 || $encrypt==$encrypt2)
		{
			$valide=TRUE;
		}
	}
    
    //test
    //$valide=true;
    //$productCode=0;
	
	if(!$valide)
	{
		return NULL;
	}

	$ret=0;
	$userId=0;
	if (isset($_POST['userId']))
	{
		$userId=$_POST['userId'];
	}
	
    $collectIdArray=NULL;
	
	if ($userId>0)
	{
        $con=dbConnect();

		if (isset($_POST['addArticleIds']))
		{
			$articleIds=$_POST['addArticleIds'];
			$collectIdArray=array();
			
			$articleIdArray = explode(",",$articleIds);
			foreach ($articleIdArray as $articleId)
			{
				if($articleId!="")
				{
					$item=new CollectItem();
					$item->userId=$userId;
					$item->articleId=$articleId;
					$item->serviceCode=$productCode;
					
					$ret=$item->insertItemToDatabase();
					if($ret)
					{
						$collectIdArray[]=$item->id;
					}
				}
			}
		}
        
		if (isset($_POST['deleteArticleIds']))
		{
			$articleIds=$_POST['deleteArticleIds'];
			if($articleIds!="")
			{
				$ret=CollectItem::deleteItems($articleIds,$userId,$productCode);
			}
		}
		
        dbClose($con);
    }

	echo json_encode(array('success'=>$ret,'collectIdArray'=>$collectIdArray));
?>

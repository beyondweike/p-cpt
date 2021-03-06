<?php
    include_once("db.function.php");
	include_once("properties.class.php");
    include_once("service.class.php");
    include_once("../common/comment.class.php");
	
	$valide=FALSE;
	
    $headers=getAllHeadersLowerCase();
    $encrypt=$headers["encrypt"];//must use lower case
	$productCode=$headers["productcode"];//must use lower case
	$version=$headers["version"];
	
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

	$commentArray=NULL;

	if (isset($_GET['userId']))
	{
		$userId=$_GET['userId'];
      
        $con=dbConnect();
        $commentArray=Comment::queryUserCommentArray($userId,$productCode);
        dbClose($con);
    }
	
	if($commentArray)
	{
		$tableName="list_table";
		
		$articleIds="";
		foreach($commentArray as $comment)
		{	
			$articleIds.=$comment->articleId;
			$articleIds.=",";
		}
		$articleIds=substr($articleIds,0,strlen($articleIds)-1);
		
		$con=dbConnect();
        $articleItemDic=Item::queryItemDicByIds($articleIds,$tableName);
        dbClose($con);
	
		$jsonObjects=array();
		foreach($commentArray as $comment)
		{	
			if($articleItemDic)
			{
				$comment->articleItem=$articleItemDic["".$comment->articleId];
			}
				
			$jsonObjects[]=$comment->jsonEncode();
		}
		
		$pair0=jsonEncodeKeyObjectsPair("commentArray",$jsonObjects);
		$json=jsonEncodePair($pair0);
		
		echo $json;
	}
	else
	{
		echo json_encode(array('commentArray'=>$commentArray));
	}
?>

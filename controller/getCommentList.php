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
	
	if (isset($_GET['articleId']))
	{
		$articleId=$_GET['articleId'];
      
        $con=dbConnect();
        $commentArray=Comment::queryCommentArray($articleId,$productCode);
        dbClose($con);
    }
	
	if($commentArray)
	{
		$jsonObjects=array();
		foreach($commentArray as $comment)
		{
			$jsonObjects[]=$comment->jsonEncode();
		}
		
		$pair0=jsonEncodeKeyObjectsPair("commentArray",$jsonObjects);
		$json=jsonEncodePair($pair0);
		
		echo $json;
	}
	else
	{
		echo json_encode(array('success'=>true,'commentArray'=>$commentArray));
	}
?>

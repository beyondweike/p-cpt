<?php
    include_once("db.function.php");
	include_once("properties.class.php");
    include_once("service.class.php");
    include_once("../common/extractItem.class.php");

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
	
    $extractIdArray=NULL;
	
	if ($userId>0)
	{
        $con=dbConnect();

		if (isset($_POST['addXmlString']))
		{
			$addXmlString=$_POST['addXmlString'];
            $extractIdArray=array();
            $extracts = simplexml_load_string($addXmlString);

			foreach($extracts->children() as $extract)
            {
                $item=new ExtractItem();
				$item->id=$extract->extractId;
                $item->articleId=$extract->articleId;
                $item->content=$extract->content;
                $item->userId=$userId;
                $item->serviceCode=$productCode;

                $ret=$item->insertItemToDatabase();
                if($ret)
                {
                    $extractIdArray[]="0".$item->id;
                }
			}
		}
        
		if (isset($_POST['deleteExtractIds']))
		{
			$extractIds=$_POST['deleteExtractIds'];
			if($extractIds!="")
			{
				$ret=ExtractItem::deleteItems($extractIds);
			}
		}
		
        dbClose($con);
    }

	echo json_encode(array('success'=>$ret,'extractIdArray'=>$extractIdArray));
?>

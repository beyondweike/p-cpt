<?php
    include_once("db.function.php");
    include_once("service.class.php");
    include_once("asynCall.function.php");
    include_once("../common/file.function.php");
	include_once("../common/visitor.class.php");
	include_once("../common/item.class.php");
	
	$valide=FALSE;
	
    $headers=getAllHeadersLowerCase();
	
    $encrypt=NULL;
	$productCode=-1;
	
	if(array_key_exists('encrypt',$headers)) 
	{
		$encrypt=$headers["encrypt"];//must use lower case
	}
	if(array_key_exists('productcode',$headers)) 
	{
		$productCode=$headers["productcode"];//must use lower case
	}
	
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

	$dataArray=NULL;

	$categoryCode=NULL;
	if (isset($_GET['categoryCode']))
	{
		$categoryCode=$_GET['categoryCode'];
	}

	$MaxPageSize=500;
	$pageSize=20;
	if (isset($_GET['pageSize']))
	{
		$pageSize=$_GET['pageSize'];
	}
	
	$con=dbConnect();
	
	//add one visit times
	Visitor::updateVisitTimes($headers);
    
    $service=Service::getService($productCode);
	
	if(isset($_GET['keywords']))//query keywords
	{
		$keywords=$_GET['keywords'];

		if (isset($_GET['lastItemId']))
		{
			$lastItemId=$_GET['lastItemId'];
			$dataArray=Item::queryKeywords($keywords,$lastItemId,$pageSize,$service->tableName);
		}
		else if (isset($_GET['topItemId']))
		{
			$topItemId=$_GET['topItemId'];
			$dataArray=Item::queryKeywords($keywords,$topItemId,$pageSize,$service->tableName,true);
		}
		        
        date_default_timezone_set('Asia/Shanghai');
        $filePathName="../logs/search_".date("Y-m",time()).".log";
        log2File($filePathName,"devicename:".$headers["devicename"]." keywords:".$keywords);
	}
	else if(isset($_GET['categoryCodeTopItemIds']))//for v1.0
	{
        $categoryCodeTopIds=$_GET['categoryCodeTopItemIds'];
        
        $dataArray=array();
        
        $categoryCodeTopIdArray = explode(";",$categoryCodeTopIds);
        foreach ($categoryCodeTopIdArray as $categoryCodeTopId)
        {
            $tempArray = explode(",",$categoryCodeTopId);
            if(count($tempArray)>=2)
            {
                $categoryCode=$tempArray[0];
                $topItemId=$tempArray[1];
				
				$pageSize=$topItemId>0?500:100;
                
                $tempArray=Item::queryNewest($topItemId,$categoryCode,$pageSize,$service->tableName);
                $dataArray["0".$categoryCode]=$tempArray;
            }
        }
	}
	else if(isset($_GET['categoryCodeTopBottomItemIds']))//download list
	{
        $categoryCodeTopBottomIds=$_GET['categoryCodeTopBottomItemIds'];
		$datetime=$_GET['datetime'];
        
        $dataArray=array();
        
        $categoryCodeTopBottomIdArray = explode(";",$categoryCodeTopBottomIds);
        foreach ($categoryCodeTopBottomIdArray as $categoryCodeTopBottomId)
        {
            $tempArray = explode(",",$categoryCodeTopBottomId);
			$arrayCount=count($tempArray);
            if($arrayCount>=2)
            {
                $categoryCode=$tempArray[0];
                $topItemId=$tempArray[1];
				$bottomItemId=$arrayCount>=3?$tempArray[2]:0;

                //refreshItemArray
                $refreshItemArray=NULL;
                if($topItemId>0)
                {
                    $pageSize=$MaxPageSize;
                    
                    $refreshItemArray=Item::queryNewest($topItemId,$categoryCode,$pageSize,$service->tableName);
                }
                
                //moreItemArray
				$moreItemArray=NULL;
				if($bottomItemId>0 || !$refreshItemArray)
				{
					$moreItemArray=Item::queryMoreSinceDate($bottomItemId,$categoryCode,$datetime,$service->tableName);
				}
				
                //
				$allArray=array();
				$allArray[0]=$refreshItemArray;
				if($moreItemArray)
				{
					$allArray[1]=$moreItemArray;
				}
                $dataArray["0".$categoryCode]=$allArray;
            }
        }
	}
	else if(isset($_GET['lastItemId']))
	{
		$lastItemId=$_GET['lastItemId'];

		$dataArray=Item::queryMore($lastItemId,$categoryCode,$pageSize,$service->tableName);

		checkToCallAsynCaptureList($productCode,$categoryCode);
	}
	else if(isset($_GET['topItemId']))
	{
		$topItemId=$_GET['topItemId'];

		if($topItemId>0)
		{
			$pageSize=$MaxPageSize;
		}
		$dataArray=Item::queryNewest($topItemId,$categoryCode,$pageSize,$service->tableName);

        checkToCallAsynCaptureList($productCode,$categoryCode);
	}

	dbClose($con);
	
	//for v1.0 列表显示重复临解决时办法
	$version=$headers["version"];
	if($version<=1.00 && $pageSize==20)
	{
		$pageSize+=1;
	}

	echo json_encode(array('pageSize'=>$pageSize,'dataArray'=>$dataArray));
	
?>

<?php
	include_once("csdnitem.class.php");
    include_once("csdnTagItem.class.php");
    
    //captureCsdnCocos2dxListPages(0,"",NULL);
	
    //news
	function captureCsdnListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;

		$captureCsdnListPageUrlFormat = "http://mobile.csdn.net/mobile"; 
		$newCount+=captureCsdnListPagesWithUrlFormat($captureCsdnListPageUrlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		$captureCsdnListPageUrlFormat = "http://sd.csdn.net/sd"; 
		$newCount+=captureCsdnListPagesWithUrlFormat($captureCsdnListPageUrlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
	function captureCsdnListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$pageCount=1;
		$fetchUrl=$urlFormat."/1";
		$firstPageContent=captureCsdnListPage($fetchUrl,$pageCount);

		$pageCount=min($pageCount,$maxCapturePageCount);

		for ($i = $pageCount; $i>1; $i--) 
		{
			$fetchUrl=$urlFormat."/".$i;
			$content=captureCsdnListPage($fetchUrl,$pageCount);
			$newCount+=CSDNItem::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
		}

		$newCount+=CSDNItem::parseItemList($firstPageContent,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
	function captureCsdnListPage($url,&$pageCount)
	{
		$results=fileGetContents( $url );

		$content = preg_match("/<div[^>]*class=\"content\"[^>]*>(.*?)<div[^>]*class=\"page_nav\"[^>]*>/s",$results,$temp) ? $temp[1]:""; 
		
		if($pageCount<=1)
		{
			$pageInfo = preg_match("/<div class=\"page_nav\">(.*)<\/div>/isU",$results,$temp) ? $temp[1]:"";  
			$pageCount = preg_match("/共(\\d+)页/isU",$pageInfo,$temp) ? $temp[1]:""; 
		}

		$content=preg_replace("/<div class=\"tag\">(.*)<\/div>/", "", $content);
		
		return $content;
	}
    
	/*
	//网页改了
    //cocos2d-x
    function captureCsdnCocos2dxListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;
        
		$captureCsdnListPageUrlFormat = "http://www.csdn.net/tag/cocos2d-x/%d";
		$newCount+=captureCsdnTagListPagesWithUrlFormat($captureCsdnListPageUrlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
    
    function captureCsdnTagListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;

		$fetchUrl = sprintf($urlFormat,1);
		$firstPageContent=captureCsdnTagListPage($fetchUrl);

        $pageCount=$maxCapturePageCount;
		for ($i = $pageCount; $i>1; $i--)
		{
			$fetchUrl = sprintf($urlFormat,$i);
			$content=captureCsdnTagListPage($fetchUrl,$pageCount);
			$newCount+=CSDNTagItem::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
		}
        
		$newCount+=CSDNTagItem::parseItemList($firstPageContent,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
    
    function captureCsdnTagListPage($url)
	{
		$results=fileGetContents($url);

		$content = preg_match("/<ul[^>]+class=\"list\"[^>]*>([\s\S]*?)<\/ul>/i",$results,$temp) ? $temp[1]:"";
		
		return $content;
	}
	*/
?>

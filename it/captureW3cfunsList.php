<?php
	include_once("w3cfunsItem.class.php");
	
	//test
	//captureW3cfunsNewsListPages(40);

    function captureW3cfunsNewsListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;
		
		$urlFormat = "http://www.w3cfuns.com/portal.php?mod=reading&pagesize=20&page=%d";
		$newCount+=captureW3cfunsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
    
	function captureW3cfunsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		//$pageCount=1;
		$pageCount=$maxCapturePageCount;
		
		$fetchUrl = sprintf($urlFormat,1);  
		$firstPageContent=captureW3cfunsListPage($fetchUrl,$pageCount);

		//test
		//echo $firstPageContent;
			
		if($pageCount>$maxCapturePageCount)
		{
			$pageCount=$maxCapturePageCount;
		}
		
		for ($i = $pageCount; $i>1; $i--) 
		{
			$fetchUrl = sprintf($urlFormat,$i);  
			$content=captureW3cfunsListPage($fetchUrl,$pageCount);
			$newCount+=W3cfunsItem::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
		}

		$newCount+=W3cfunsItem::parseItemList($firstPageContent,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
	function captureW3cfunsListPage($url,&$pageCount)
	{
		$results=fileGetContents( $url );
        
        $charset = preg_match("/<meta.+?charset=(.*?)\"/i",$results,$temp) ? strtolower($temp[1]):"";
		$pos=strpos($charset,"utf");
 		if(!$pos)
		{
			$results=iconv($charset,"UTF-8",$results);
			//echo "convert convert ".$pos;
		}
		else
		{
			//echo "no convert ".$pos;
		}
        
		$content = preg_match("/<div[^>]+id=\"content\"[^>]*>(.*)<div[^>]+id=\"pageBox\"[^>]*>/s",$results,$temp) ? $temp[1]:"";
		
		return $content;
	}
?>

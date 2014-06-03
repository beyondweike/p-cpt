<?php
	include_once("qianduanItem.class.php");
	
	//test
	//captureQianduanNewsListPages(40,"",NULL);

    //更新慢
    function captureQianduanNewsListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;

		$urlFormat = "http://www.qianduan.net/page/%d";
		$newCount+=captureQianduanListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
    
	function captureQianduanListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$pageCount=$maxCapturePageCount;
		
		//the first page is for get max page number 
		$fetchUrl = sprintf($urlFormat,1);  
		$firstPageContent=captureQianduanListPage($fetchUrl,$pageCount);

		//test
		//echo $pageCount;
		//echo $firstPageContent;
			
		if($pageCount>$maxCapturePageCount)
		{
			$pageCount=$maxCapturePageCount;
		}
		
		for ($i = $pageCount; $i>1; $i--) 
		{
			$fetchUrl = sprintf($urlFormat,$i);  
			$content=captureQianduanListPage($fetchUrl,$pageCount);
			$newCount+=QianduanItem::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
		}

		$newCount+=QianduanItem::parseItemList($firstPageContent,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
	function captureQianduanListPage($url,&$pageCount)
	{
		$results=fileGetContents( $url );

		$content = preg_match("/<div[^>]+id=\"content\"[^>]*>([\s\S]+)<div[^>]+class=\'wp-pagenavi\'/s",$results,$temp) ? $temp[1]:"";
		
		if($pageCount<=1)
		{
			$pageInfo = preg_match("/<span[^>]+class=\'pages\'>.*?<\/span>/i",$results,$temp) ? $temp[0]:"";
			$pageCount = preg_match("/共(\\d+)页/",$pageInfo,$temp)?$temp[1]:1;
		}
		
		return $content;
	}
?>

<?php
	include_once("yeskyitem.class.php");
	
	//test
	//captureYeskyIosNewsListPages(0,"",NULL);
	
	//更新慢
	function captureYeskyIosNewsListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;
        
		//ios新闻
		$urlFormat = "http://dev.yesky.com/more/412_46412_ios_%d.shtml"; 
		$newCount+=captureYeskyListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
	//更新慢
	function captureYeskyAndroidNewsListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;
	
		//android新闻
		$urlFormat = "http://dev.yesky.com/more/412_46412_andr_%d.shtml"; 
		$newCount+=captureYeskyListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}

	function captureYeskyListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		//这里不抓取总页数了
		//$pageCount=1;
		$pageCount=$maxCapturePageCount;
		
		$fetchUrl = sprintf($urlFormat,1);  
		$firstPageContent=captureYeskyListPage($fetchUrl,$pageCount);

		//test
		//echo $firstPageContent;
			
		if($pageCount>$maxCapturePageCount)
		{
			$pageCount=$maxCapturePageCount;
		}
		
		for ($i = $pageCount; $i>1; $i--) 
		{
			$fetchUrl = sprintf($urlFormat,$i);  
			$content=captureYeskyListPage($fetchUrl,$pageCount);
			$newCount+=YeskyItem::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
			
			//test
			//echo $content;
		}

		$newCount+=YeskyItem::parseItemList($firstPageContent,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
	function captureYeskyListPage($url,&$pageCount)
	{
		//$snoopy = new Snoopy; 
		//$snoopy->fetch($url);
		//$results=$snoopy->results;
		$results=fileGetContents( $url );
		$results=iconv("GBK","UTF-8",$results);

		$content = preg_match("/<ul[^>]*class=\"ltwd\"[^>]*>([\s\S]+?)<\/ul>/",$results,$temp) ? $temp[1]:"";
		
		if($pageCount<=1)
		{
			//这里不抓取总页数了
			//$pageInfo = preg_match("/<div class=\"page\">(.*)<\/div>/isU",$results,$temp) ? $temp[1]:"";  
			//$pageCount = preg_match("/共 <strong>(\\d+)<\/strong>页/isU",$pageInfo,$temp) ? $temp[1]:""; 
		}

		return $content;
	}
?>

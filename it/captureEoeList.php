<?php
	include_once("eoeitem.class.php");
	
	//test
	//captureEoeNewsListPages(5);
	
	function captureEoeNewsListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;
		
		//全部资讯
		$urlFormat = "http://news.eoe.cn/cat-0-%d.html"; 
		$newCount+=captureEoeListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}

	function captureEoeListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		//这里不抓取总页数了
		//$pageCount=1;
		$pageCount=$maxCapturePageCount;
		
		$fetchUrl = sprintf($urlFormat,1);  
		$firstPageContent=captureEoeListPage($fetchUrl,$pageCount);

		//test
		//echo $firstPageContent;
			
		if($pageCount>$maxCapturePageCount)
		{
			$pageCount=$maxCapturePageCount;
		}
		
		for ($i = $pageCount; $i>1; $i--) 
		{
			$fetchUrl = sprintf($urlFormat,$i);  
			$content=captureEoeListPage($fetchUrl,$pageCount);
			$newCount+=EoeItem::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
			
			//test
			//echo $content;
		}

		$newCount+=EoeItem::parseItemList($firstPageContent,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
	function captureEoeListPage($url,&$pageCount)
	{
		//$snoopy = new Snoopy; 
		//$snoopy->fetch($url);
		//$results=$snoopy->results; 
		$results=fileGetContents( $url );

		$content = preg_match("/<div[^>]*class=\"ue-body-new-box\"[^>]*>([\s\S]+?)<div class=\"ue-pagination pagination-left\"/s",$results,$temp) ? $temp[1]:""; 
		
		if($pageCount<=1)
		{
			//这里不抓取总页数了
			//$pageInfo = preg_match("/<div class=\"page\">(.*)<\/div>/isU",$results,$temp) ? $temp[1]:"";  
			//$pageCount = preg_match("/共 <strong>(\\d+)<\/strong>页/isU",$pageInfo,$temp) ? $temp[1]:""; 
		}

		return $content;
	}
?>

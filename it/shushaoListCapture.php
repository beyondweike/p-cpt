<?php
	include_once("shushaoItem.class.php");
    include_once("../common/request.function.php");
	
	//test
	//captureShuShaoIOSNewsListPages(0,"");
	
	function captureShuShaoIOSNewsListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;
		
		//ios新闻
		$urlFormat = "http://www.shushao.com/apple?start=%d"; 
		$newCount+=captureShuShaoListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}

	function captureShuShaoListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;

        $pageCount=$maxCapturePageCount;
        $pageSize=20;//shushao.com defined
        
		$fetchUrl = sprintf($urlFormat,0*$pageSize);
		$firstPageContent=captureShuShaoListPage($fetchUrl,$pageCount);

		//test
		//echo $firstPageContent;
			
		if($pageCount>$maxCapturePageCount)
		{
			$pageCount=$maxCapturePageCount;
		}
		
		for ($pageNum = $pageCount; $pageNum>1; $pageNum--)
		{
			$fetchUrl = sprintf($urlFormat,($pageNum-1)*$pageSize);
			$content=captureShuShaoListPage($fetchUrl,$pageCount);
			$newCount+=ShuShaoItem::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
			
			//test
			//echo $content;
		}

		$newCount+=ShuShaoItem::parseItemList($firstPageContent,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
	function captureShuShaoListPage($url,&$pageCount)
	{
		//$results=fileGetContents( $url );
        $results=curlexec($url);
		//$results=iconv("GBK","UTF-8",$results);

		$content = preg_match("/<div[^>]+class=\"itemListpage\"[^>]*>([\s\S]*)<div[^>]+id=\"Paging\"/i",$results,$temp) ? $temp[1]:""; 
		
		if($pageCount<=1)
		{
			$pageInfo = preg_match("/<div[^>]+class=\"Paging\"[^>]*>([\s\S]*)<div[^>]+class=\"ContentR\"/i",$results,$temp) ? $temp[1]:"";  
			$pageCount = preg_match("/共(\\d+)页/",$pageInfo,$temp) ? $temp[1]:""; 
		}

		return $content;
	}
?>

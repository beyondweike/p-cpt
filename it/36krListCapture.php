<?php
	include_once("36krItem.class.php");
    include_once("../common/request.function.php");
	include_once("../common/file.function.php");
	
	//test
	//capture36krNewsListPages(2,"list_table",NULL);
	
	function capture36krNewsListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;

		$urlFormat = "http://www.36kr.com/?page=%d";
		$newCount+=capture36krListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
    
    function capture36krListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;

        $pageCount=1;
		$fetchUrl = sprintf($urlFormat,$pageCount);
		$firstPageContent=capture36krListPage($fetchUrl,$pageCount);

        $pageCount=min($pageCount,$maxCapturePageCount);

		for ($pageNum = 2; $pageNum<=$pageCount; $pageNum++)
		{
			$fetchUrl = sprintf($urlFormat,$pageNum);
			$content=capture36krListPage($fetchUrl,$pageCount);
			$newCount+=S36krItem::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
		}

		$newCount+=S36krItem::parseItemList($firstPageContent,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
	function capture36krListPage($url,&$pageCount)
	{
		$results=fileGetContents($url);
		
		if($results=="")
		{
			return $results;
		}
				
		$content = preg_match("/<div class=\"articles\">([\s\S]+)<\/div>\s*<div class=\"pagination cf\"/i",$results,$temp) ? $temp[1]:"";

		return $content;
	}
?>

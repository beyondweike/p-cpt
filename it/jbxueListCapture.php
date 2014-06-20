<?php
	include_once("jbxueItem.class.php");
    include_once("../common/request.function.php");
	include_once("../common/string.function.php");
    
	//test
	//captureJbxuePhpDevListPages(0,"list_table",NULL);
	
    //
	function captureJbxuePhpDevListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;

        //http://www.jbxue.com/wb/php/index_2.html
		$urlFormat = "http://www.jbxue.com/wb/php/index.html";
		$newCount+=captureJbxueListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
    function captureJbxueListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        $pageCount=$maxCapturePageCount;
        
        //若需要获取页数，则先取第一页内容

		for ($pageNum = $pageCount; $pageNum>=1; $pageNum--)
		{
			$fetchUrl = $urlFormat;//sprintf($urlFormat,$pageNum);
			$content=captureJbxueItemListPage($fetchUrl,$pageCount);
			$newCount+=JbxueItem::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
		}

		return $newCount;
	}
	
	function captureJbxueItemListPage($url,&$pageCount)
	{
		$results=fileGetContents( $url );
		
		if($results=="")
		{
			date_default_timezone_set('Asia/Shanghai');
			$filePathName="../logs/captureList_error_".date("Y-m-d",time()).".log";
			log2File($filePathName,"captureJbxueItemListPage fileGetContents empty\n".$url."\n");
			
			return "";
		}

        $results=checkConvertHtmlToCharsetUtf8($results);
		
		if($results=="")
		{
			date_default_timezone_set('Asia/Shanghai');
			$filePathName="../logs/captureList_error_".date("Y-m-d",time()).".log";
			log2File($filePathName,"captureJbxueItemListPage checkConvertHtmlToCharsetUtf8 empty\n".$url."\n");
			
			return "";
		}
        
		$content = preg_match("/<div[^>]+class=\"sub-list\"[^>]*>\s*<ul>([\s\S]*?)<\/ul>/i",$results,$temp) ? $temp[1]:"";
        
		if($content=="")
		{
			date_default_timezone_set('Asia/Shanghai');
			$filePathName="../logs/captureList_error_".date("Y-m-d",time()).".log";
			log2File($filePathName,"captureJbxueItemListPage preg_match empty\n".$url."\n");
		}
		
        return $content;
	}
?>

<?php
	include_once("woshipmItem.class.php");
    include_once("../common/request.function.php");
	include_once("../common/string.function.php");
	
	//test
	//captureWoshipmITNewsListPages(0,"list_table",NULL);
	
    //it news
	function captureWoshipmITNewsListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
        
		$urlFormat = "http://www.woshipm.com/category/it/page/%d";
		$newCount+=captureWoshipmListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
    
    //Interview
	function captureWoshipmInterviewListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;

		$urlFormat = "http://www.woshipm.com/category/zhichang/page/%d";
		$newCount+=captureWoshipmListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
    
    //Product&Design
    function captureWoshipmProductDesignListPages1($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
        
		$urlFormat = "http://www.woshipm.com/category/pd/page/%d";
		$newCount+=captureWoshipmListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
        $urlFormat = "http://www.woshipm.com/category/ucd/page/%d";
		$newCount+=captureWoshipmListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
	function captureWoshipmProductDesignListPages2($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
		
		$urlFormat = "http://www.woshipm.com/category/pmd/page/%d";
		$newCount+=captureWoshipmListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
        //更新很慢
		$urlFormat = "http://www.woshipm.com/category/xiazai/page/%d";
				  $newCount+=captureWoshipmListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}

	//market operation
	function captureWoshipmMarketOperationListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
        
        $urlFormat = "http://www.woshipm.com/category/operate/page/%d";
		$newCount+=captureWoshipmListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
		$urlFormat = "http://www.woshipm.com/category/discuss/page/%d";
		$newCount+=captureWoshipmListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
    function captureWoshipmListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;

        $pageCount=1;
		$fetchUrl = sprintf($urlFormat,$pageCount);
		$firstPageContent=captureWoshipmItemListPage($fetchUrl,$pageCount);

        //test
        //echo $pageCount;
        //echo $firstPageContent;
        
        $pageCount=min($pageCount,$maxCapturePageCount);

		for ($pageNum = 2; $pageNum<=$pageCount; $pageNum++)
		{
			$fetchUrl = sprintf($urlFormat,$pageNum);
			$content=captureWoshipmItemListPage($fetchUrl,$pageCount);
			$newCount+=WoshipmItem::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
		}
        
		$newCount+=WoshipmItem::parseItemList($firstPageContent,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
	function captureWoshipmItemListPage($url,&$pageCount)
	{
		$results=fileGetContents( $url );
        //$results=curlexec($url);
		//$results=iconv("GBK","UTF-8",$results);
		
		//echo $results;
		//echo "$url<br>----------";
		
		if($results=="")
		{
			date_default_timezone_set('Asia/Shanghai');
			$filePathName="../logs/captureList_error_".date("Y-m-d",time()).".log";
			log2File($filePathName,"captureWoshipmItemListPage fileGetContents empty\n".$url."\n");
			
			return "";
		}

		$content = preg_match("/<div[^>]+class=\"content_box bor_cor\"[^>]*>([\s\S]*?)<div[^>]+id=\"pagenavi\"/i",$results,$temp) ? $temp[1]:"";
        if($pageCount<=1)
		{
			$pageInfo = preg_match("/<div[^>]+id=\"pagenavi[^>]*>([\s\S]*)<\/div>/i",$results,$temp) ? $temp[1]:"";
			$pageCount = preg_match("/<span class=\"page-numbers\">1\/(\\d+)\s*<\/span>/",$pageInfo,$temp)?$temp[1]:$pageCount;
		}
		
		if($content=="")
		{
			date_default_timezone_set('Asia/Shanghai');
			$filePathName="../logs/captureList_error_".date("Y-m-d",time()).".log";
			log2File($filePathName,"captureWoshipmItemListPage preg_match empty\n".$url."\n".$results."\n");
		}
        
        return $content;
	}
?>

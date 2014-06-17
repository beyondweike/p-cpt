<?php
	include_once("ItemGamerboom.class.php");
	include_once("../common/request.function.php");
	include_once("../common/string.function.php");
	
	//test
	//captureGamerboomNewsListPages(40,"");

    function captureGamerboomNewsListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;
		
		$urlFormat = "http://gamerboom.com/page/%d";
		$newCount+=captureGamerboomListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
    
	function captureGamerboomListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$pageCount=$maxCapturePageCount;
		
		$fetchUrl = sprintf($urlFormat,1);  
		$firstPageContent=captureGamerboomListPage($fetchUrl,$pageCount);

		//test
        //echo "pageCount".$pageCount;
		//echo $firstPageContent;
			
		if($pageCount>$maxCapturePageCount)
		{
			$pageCount=$maxCapturePageCount;
		}
		
		for ($i = $pageCount; $i>1; $i--) 
		{
			$fetchUrl = sprintf($urlFormat,$i);  
			$content=captureGamerboomListPage($fetchUrl,$pageCount);
			$newCount+=ItemGamerboom::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
		}

		$newCount+=ItemGamerboom::parseItemList($firstPageContent,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
	function captureGamerboomListPage($url,&$pageCount)
	{
		$results=fileGetContents( $url );
		
		if($results=="")
		{
			date_default_timezone_set('Asia/Shanghai');
			$filePathName="../logs/captureList_error_".date("Y-m-d",time()).".log";
			log2File($filePathName,"captureGamerboomListPage fileGetContents empty\n".$url."\n");
			
			return "";
		}

		$content = preg_match("/<div class=\"c02 margin-top\">([\s\S]+)<div class=\'wp-pagenavi\'/i",$results,$temp) ? $temp[1]:"";

		if($pageCount<=1)
		{
            $pageInfo = preg_match("/<div class=\'wp-pagenavi\'>([\s\S]+?)<\/div>/i",$results,$temp) ? $temp[1]:"";
			$pageCount = preg_match("/共\s*(\\d+)\s*页/isU",$pageInfo,$temp) ? $temp[1]:"";
		}
		
		if($content=="")
		{
			date_default_timezone_set('Asia/Shanghai');
			$filePathName="../logs/captureList_error_".date("Y-m-d",time()).".log";
			log2File($filePathName,"captureGamerboomListPage preg_match empty\n".$url."\n");
		}
		
		return $content;
	}
?>

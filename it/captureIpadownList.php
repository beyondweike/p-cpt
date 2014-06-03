<?php
	include_once("ItemIpadown.class.php");
	
	//test
	//captureIpadownNewsListPages(40,"");

    function captureIpadownNewsListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;
		
		$urlFormat = "http://news.ipadown.com/p-%d";
		$newCount+=captureIpadownListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
	function captureIpadownGameListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;
		
		$urlFormat = "http://game.ipadown.com/p-%d";
		$newCount+=captureIpadownListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
    
	function captureIpadownListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$pageCount=$maxCapturePageCount;
		$startPageNum=1;
		
		$fetchUrl = sprintf($urlFormat,$startPageNum);  
		$firstPageContent=captureIpadownListPage($fetchUrl,$pageCount);

		//test
        //echo "pageCount".$pageCount;
		//echo $firstPageContent;
			
		if($pageCount>$maxCapturePageCount)
		{
			$pageCount=$maxCapturePageCount;
		}
		
		for ($i = $pageCount; $i>$startPageNum; $i--) 
		{
			$fetchUrl = sprintf($urlFormat,$i);  
			$content=captureIpadownListPage($fetchUrl,$pageCount);
			$newCount+=ItemIpadown::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
		}

		$newCount+=ItemIpadown::parseItemList($firstPageContent,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
	function captureIpadownListPage($url,&$pageCount)
	{
		$results=fileGetContents( $url );

		$content = preg_match("/<div[^>]+class=\"news\"[^>]*?>([\s\S]*)<div[^>]+class=\"pagelist\"[^>]*?>/i",$results,$temp) ? $temp[1]:"";

		if($pageCount<=1)
		{
            $pageInfo = preg_match("/<div[^>]+class=\"pagelist\"[^>]*?>([\s\S]+?)<\/div>/i",$results,$temp) ? $temp[1]:"";
			$pageCount = preg_match("/<a[^>]+p-(\\d+)\">末页<\/a>/isU",$pageInfo,$temp) ? $temp[1]:"";
		}
		
		return $content;
	}
?>

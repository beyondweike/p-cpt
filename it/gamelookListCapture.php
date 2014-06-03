<?php
	include_once("gamelookItem.class.php");
    include_once("../common/request.function.php");
	
	//test
	//captureGamelookNewsListPages(2,"list_table",NULL);
	
	function captureGamelookNewsListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;

		$urlFormat = "http://www.gamelook.com.cn/category/news/page/%d";
		$newCount+=captureGamelookListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
    
    function captureGamelookListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;

        $pageCount=1;
		$fetchUrl = sprintf($urlFormat,$pageCount);
		$firstPageContent=captureGamelookListPage($fetchUrl,$pageCount);

        //test
        //echo $pageCount."<br>";
        
        $pageCount=min($pageCount,$maxCapturePageCount);
        
        //test
        //echo $pageCount."<br>";
		//echo $firstPageContent;
		
		for ($pageNum = 2; $pageNum<=$pageCount; $pageNum++)
		{
			$fetchUrl = sprintf($urlFormat,$pageNum);
			$content=captureGamelookListPage($fetchUrl,$pageCount);
			$newCount+=GamelookItem::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
			
			//test
            //echo $pageNum."<br>";
			//echo $content;
		}

		$newCount+=GamelookItem::parseItemList($firstPageContent,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
	function captureGamelookListPage($url,&$pageCount)
	{
		//$results=fileGetContents( $url );
        $results=curlexec($url);
		//$results=iconv("GBK","UTF-8",$results);
		
		//echo $results;
		//echo "$url<br>----------";

		$content = preg_match("/<div[^>]+class=\"list-content\"[^>]*>([\s\S]*?)<div[^>]+id=\"sidebar\"/i",$results,$temp) ? $temp[1]:"";
        
        if($pageCount<=1)
		{
			$pageInfo = preg_match("/<div[^>]+class=\"pagenavi[^>]*>([\s\S]*)<\/div>/i",$results,$temp) ? $temp[1]:"";
			$pageCount = preg_match("/<span>.*?\/\s*(\\d+)\s*<\/span>/",$pageInfo,$temp)?$temp[1]:$pageCount;
		}
        
        return $content;
	}
?>

<?php
	include_once("cocos2devItem.class.php");
    include_once("../common/request.function.php");
	
	//test
	//captureCocos2devListPages(0,"list_table",NULL);
	
    //更新慢,网页有修改
	function captureCocos2devListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;

        //11-12之后不更新了
		$urlFormat = "http://www.cocos2dev.com/index.php?paged=%d";
		$newCount+=captureCocos2devListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
    function captureCocos2devListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        $pageCount=$maxCapturePageCount;
        
        //若需要获取页数，则先取第一页内容

		for ($pageNum = $pageCount; $pageNum>=1; $pageNum--)
		{
			$fetchUrl = sprintf($urlFormat,$pageNum);
			$content=captureCocos2devItemListPage($fetchUrl,$pageCount);
			$newCount+=Cocos2devItem::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
		}

		return $newCount;
	}
	
	function captureCocos2devItemListPage($url,&$pageCount)
	{
		$results=fileGetContents( $url );
        //$results=curlexec($url);
		//$results=iconv("GBK","UTF-8",$results);

		//echo $results;
		//echo "$url<br>----------";
        
		$content = preg_match("/<div[^>]+id=\"main\"[^>]*>([\s\S]*?)<div[^>]+class=\"flip\"/i",$results,$temp) ? $temp[1]:"";
        
        return $content;
	}
?>

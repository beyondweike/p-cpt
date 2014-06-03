<?php
	include_once("tuicoolItem.class.php");
    include_once("../common/request.function.php");
	include_once("../common/string.function.php");
    
	//test
	//captureTuicoolCocos2dListPages(0,"list_table",NULL);
	
    //
	function captureTuicoolCocos2dListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;

        //http://www.jbxue.com/wb/php/index_2.html
		$urlFormat = "http://www.tuicool.com/topics/11080017?st=0&lang=0&pn=0";
		$newCount+=captureTuicoolListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
    function captureTuicoolListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        $pageCount=$maxCapturePageCount;
        
        //若需要获取页数，则先取第一页内容

		//for ($pageNum = $pageCount; $pageNum>=1; $pageNum--)
		{
			$fetchUrl = $urlFormat;//sprintf($urlFormat,$pageNum);
			$content=captureTuicoolItemListPage($fetchUrl,$pageCount);
			$newCount+=TuicoolItem::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
		}

		return $newCount;
	}
	
	function captureTuicoolItemListPage($url,&$pageCount)
	{
		$results=fileGetContents( $url );

        //$results=checkConvertHtmlToCharsetUtf8($results);

		$content = preg_match("/<div[^>]+class=\"list_article\"[^>]*>([\s\S]*?)<div[^>]+class=\"read-later-alert\"/i",$results,$temp) ? $temp[1]:"";
        
        return $content;
	}
?>

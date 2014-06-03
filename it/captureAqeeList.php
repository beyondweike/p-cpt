<?php
	include_once("aqeeItem.class.php");
	
	//test
	//captureAqeeCommentListPages(5,"",NULL);
	
	function captureAqeeCommentListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
        $url = "http://www.vaikan.com/";
		$newCount+=captureAqeeCommentListPagesWithUrl($url,$categoryCode,$tableName,$categoryPriorityDic);
        
		/*
		$url = "http://www.aqee.net/top-share/";
		$newCount+=captureAqeeCommentListPagesWithUrl($url,$categoryCode,$tableName,$categoryPriorityDic);
        
        $url = "http://www.aqee.net/top-comment/";
		$newCount+=captureAqeeCommentListPagesWithUrl($url,$categoryCode,$tableName,$categoryPriorityDic);
*/

		return $newCount;
	}

	function captureAqeeCommentListPagesWithUrl($fetchUrl,$categoryCode,$tableName,$categoryPriorityDic)
	{ 
		$newCount=0;
		
		$firstPageContent=captureAqeeCommentListPage($fetchUrl);

		$newCount+=AqeeItem::parseItemList($firstPageContent,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
	function captureAqeeCommentListPage($url)
	{
		$results=fileGetContents( $url );
        
		$content = preg_match("/<div[^>]+id=\"content\"[^>]*>([\s\S]+)<div[^>]+id=\"primary\"/i",$results,$temp) ? $temp[1]:"";

		return $content;
	}
?>

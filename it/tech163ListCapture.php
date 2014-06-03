<?php
	include_once("tech163Item.class.php");
    include_once("../common/request.function.php");
	
	//test
	//captureTech163NewsListPages(0,"list_table",NULL);
	
	function captureTech163NewsListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;

		$urlFormat = "http://tech.163.com/";
		$newCount+=captureTech163ListPagesWithUrlFormat($urlFormat,0,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
    
    function captureTech163ListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;

        $fetchUrl=$urlFormat;
		$pageContentArray=captureTech163ItemListPage($fetchUrl);

		$newCount+=Tech163Item::parseItemList($pageContentArray,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
	function captureTech163ItemListPage($url)
	{
		$results=fileGetContents( $url );
		$results=iconv("GBK","UTF-8",$results);
		
		$contentArray=array();

		$contentArray[] = preg_match("/<div class=\"hot-title border-top-bold clearfix\">([\s\S]+)<a class=\"morebtn hidden\"/i",$results,$temp) ? $temp[1]:"";

        $contentArray[] = preg_match("/<div[^>]+id=\"slide_items\"[^>]*>([\s\S]+?)<div[^>]+class=\"right w300\"/i",$results,$temp) ? $temp[1]:"";
		
        return $contentArray;
	}
?>

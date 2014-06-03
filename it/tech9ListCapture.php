<?php
	include_once("tech9Item.class.php");
    include_once("../common/request.function.php");
	
	//test
	//captureTech9Cocos2dListPages(0,"list_table",NULL);
	
	function captureTech9Cocos2dListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
        
		$urlFormat = "http://cocos2d.9tech.cn/";
		$newCount+=captureTech9ListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
    
	function captureTech9Unity3dListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
        
		$urlFormat = "http://unity3d.9tech.cn/";
		$newCount+=captureTech9ListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
	function captureTech9Genesis3dListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
        
		$urlFormat = "http://genesis-3d.9tech.cn/";
		$newCount+=captureTech9ListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
	
    function captureTech9ListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;

        $pageCount=1;
		$fetchUrl = $urlFormat;
		$firstPageContent=captureTech9ItemListPage($fetchUrl,$pageCount);

        //test
        //echo $fetchUrl;
        //echo $firstPageContent;
        
        $pageCount=min($pageCount,$maxCapturePageCount);
        
		$newCount+=Tech9Item::parseItemList($firstPageContent,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
	function captureTech9ItemListPage($url,&$pageCount)
	{
		$results=fileGetContents( $url );
        //$results=curlexecMobile($url);
		//$results=iconv("GBK","UTF-8",$results);
		
		//echo $results;
		//echo "$url<br>----------";

		$content = preg_match("/<ul[^>]+class=\"topNews\"[^>]*>([\s\S]*?)<\/ul/i",$results,$temp) ? $temp[1]:"";
        if($pageCount<=1)
		{
			//$pageInfo = preg_match("/<div[^>]+id=\"pagenavi[^>]*>([\s\S]*)<\/div>/i",$results,$temp) ? $temp[1]:"";
			//$pageCount = preg_match("/<span class=\"page-numbers\">1\/(\\d+)\s*<\/span>/",$pageInfo,$temp)?$temp[1]:$pageCount;
		}
        
        return $content;
	}
?>

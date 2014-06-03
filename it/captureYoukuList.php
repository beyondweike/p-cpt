<?php
	include_once("youkuitem.class.php");
	
	//test
	//captureYoukuYdhlListPages(100);
	
	function captureYoukuListXXXPages($categoryCode,$tableName)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;
		
		//移动互联
		//$urlFormat = "http://tech.youku.com/ydhl/index/_page12383_%d.html"; 
		//科技-App-今日-最新发布
		$urlFormat="http://www.youku.com/v_showlist/t1c105g3056d1p%d.html";
		$newCount+=captureYoukuListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName);
		
		$urlFormat="http://www.youku.com/v_showlist/t1c105g2334d1p%d.html";
		$newCount+=captureYoukuListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName);
		
		return $newCount;
	}

	function captureYoukuListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName)
	{
		$newCount=0;
		
		//这里不抓取总页数了
		//$pageCount=1;
		$pageCount=$maxCapturePageCount;
		
		$fetchUrl = sprintf($urlFormat,1);  
		$firstPageContent=captureYoukuListPage($fetchUrl,$pageCount);

		//test
		//echo $firstPageContent;
			
		if($pageCount>$maxCapturePageCount)
		{
			$pageCount=$maxCapturePageCount;
		}
		
		for ($i = $pageCount; $i>1; $i--) 
		{
			$fetchUrl = sprintf($urlFormat,$i);  
			$content=captureYoukuListPage($fetchUrl,$pageCount);
			$newCount+=YoukuItem::parseItemList($content,$categoryCode,$tableName);
			
			//test
			//echo $content;
		}

		$newCount+=YoukuItem::parseItemList($firstPageContent,$categoryCode,$tableName);
		
		return $newCount;
	}
	
	function captureYoukuListPage($url,&$pageCount)
	{
		$results=fileGetContents( $url );

/*
		$snoopy = new Snoopy; 
		$snoopy->fetch($url);
		$results=$snoopy->results; 
*/

		//form ydlh
		//$content = preg_match("/<div class=\"items\">(.*?)<\/div><!--items end-->/s",$results,$temp) ? $temp[1]:"";
		
		$content = preg_match("/<div class=\"items\">(.*?)<!--vdata_list end-->/s",$results,$temp) ? $temp[1]:"";
		
		if($pageCount<=1)
		{
			//这里不抓取总页数了
			//$pageInfo = preg_match("/<div class=\"page\">(.*)<\/div>/isU",$results,$temp) ? $temp[1]:"";  
			//$pageCount = preg_match("/共 <strong>(\\d+)<\/strong>页/isU",$pageInfo,$temp) ? $temp[1]:""; 
		}

		return $content;
	}
?>

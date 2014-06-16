<?php
	include_once("androidStudyItem.class.php");
	include_once("../common/request.function.php");
	
	//test
	//captureAndroidStudyInterviewListPages(81);
	
	function captureAndroidStudyHealthyListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		//大于第1页后要求post
		$maxCapturePageCount=1;
		
		//healthy
		$url = "http://www.android-study.net/list.aspx"; 
		$newCount+=postCaptureAndroidStudyListPagesWithUrl($url,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}

	function captureAndroidStudyInterviewListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		//interview，/大于第1页后要求post,但是测试不成功，所以只用get方法取第一页
		$url = "http://www.android-study.net/list.aspx?fid=40"; 
		$newCount+=getCaptureAndroidStudyListPagesWithUrl($url,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
	function captureAndroidStudyAndroidDevListPages1($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		//大于第1页后要求post
		$maxCapturePageCount=1;

		//develope
		$url = "http://www.android-study.net/listInfo.aspx"; 
		$newCount+=postCaptureAndroidStudyListPagesWithUrl($url,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
	
		return $newCount;
	}
	
	function captureAndroidStudyAndroidDevListPages2($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		//大于第1页后要求post
		$maxCapturePageCount=1;
		
		//视频
		$url = "http://www.android-study.net/listAvi.aspx"; 
		$newCount+=postCaptureAndroidStudyListPagesWithUrl($url,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		//解决方案
		$url = "http://www.android-study.net/listErr.aspx"; 
		$newCount+=postCaptureAndroidStudyListPagesWithUrl($url,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}

	function getCaptureAndroidStudyListPagesWithUrl($fetchUrl,$categoryCode,$tableName,$categoryPriorityDic)
	{ 
		$newCount=0;
		
		$firstPageContent=getCaptureAndroidStudyListPage($fetchUrl);
		$newCount+=AndroidStudyItem::parseItemList($firstPageContent,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
	function postCaptureAndroidStudyListPagesWithUrl($url,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$pageCount=1;
		$fetchUrl = $url;  
		$firstPageContent=postCaptureAndroidStudyListPage($fetchUrl,$pageCount,1);

		if($pageCount>$maxCapturePageCount)
		{
			$pageCount=$maxCapturePageCount;
		}
		$pageCount=$maxCapturePageCount;
		
		for ($i = $pageCount; $i>1; $i--) 
		{
			$fetchUrl = $url; 
			$content=postCaptureAndroidStudyListPage($fetchUrl,$pageCount,$i);
			$newCount+=AndroidStudyItem::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
		}
		
		$newCount+=AndroidStudyItem::parseItemList($firstPageContent,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
	function getCaptureAndroidStudyListPage($url)
	{
		$results=fileGetContents( $url );

		$content = preg_match("/<ul[^>]*class=\"TOP_ARTICLES_info\"[^>]*>([\s\S]*?)<ul[^>]*class=\"TOP_ARTICLES_page\">/i",$results,$temp) ? $temp[1]:""; 

		return $content;
	}
	function postCaptureAndroidStudyListPage($url,&$pageCount,$pageNum)
	{
		$params = array();
		$params['__EVENTTARGET'] = "AspNetPager1";
		$params['__EVENTARGUMENT'] = $pageNum;
		$results=postCurlExec($url,$params);

		$content = preg_match("/<ul[^>]*class=\"TOP_ARTICLES_info\"[^>]*>([\s\S]*?)<ul[^>]*class=\"TOP_ARTICLES_page\">/i",$results,$temp) ? $temp[1]:""; 
		
		if($pageCount<=1)
		{
			//$pageInfo = preg_match("/<div class=\"page\">(.*)<\/div>/isU",$results,$temp) ? $temp[1]:"";  
			//$pageCount = preg_match("/共 <strong>(\\d+)<\/strong>页/isU",$pageInfo,$temp) ? $temp[1]:""; 
		}

		return $content;
	}
?>

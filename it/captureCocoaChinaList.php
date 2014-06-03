<?php
	include_once("cocoachinaitem.class.php");
    
    //captureCocoachinaGameNewsListPages(30);

	function captureCocoachinaGameNewsListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;
		
		//游戏开发
		$urlFormat = "http://www.cocoachina.com/gamedev/list_6_%d.html"; 
		$newCount+=captureCocoachinaListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
	//更新慢
	function captureCocoachinaIOSDevListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;
	
		//基础知识
		$urlFormat = "http://www.cocoachina.com/newbie/basic/list_21_%d.html";
		$newCount+=captureCocoachinaListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

/*
		//开发环境
		$urlFormat = "http://www.cocoachina.com/newbie/env/list_22_%d.html"; 
		$newCount+=captureCocoachinaListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		//起步教学
		$urlFormat = "http://www.cocoachina.com/newbie/tutorial/list_23_%d.html"; 
		$newCount+=captureCocoachinaListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		*/
        
		return $newCount;
	}
	
	function captureCocoachinaDevNewsListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;
		
        //资讯频道开发相关
		$urlFormat = "http://www.cocoachina.com/applenews/devnews/list_11_%d.html";
		$newCount+=captureCocoachinaListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
        //资讯频道苹果相关，
		$urlFormat = "http://www.cocoachina.com/applenews/apple/list_10_%d.html";
		$newCount+=captureCocoachinaListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		//开发综合
		$urlFormat = "http://www.cocoachina.com/gamedev/misc/list_37_%d.html";
		$newCount+=captureCocoachinaListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

        /*  
        //更新慢
		//ipad开发
		$urlFormat = "http://www.cocoachina.com/iphonedev/iPadkaifa/list_50_%d.html"; 
		$newCount+=captureCocoachinaListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		//iPhone开发官方sdk
		$urlFormat = "http://www.cocoachina.com/iphonedev/sdk/list_24_%d.html"; 
		$newCount+=captureCocoachinaListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		//iPhone开发开源sdk
		$urlFormat = "http://www.cocoachina.com/iphonedev/toolthain/list_25_%d.html"; 
		$newCount+=captureCocoachinaListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
         
         //会员作品
         $urlFormat = "http://www.cocoachina.com/appreview/list_51_%d.html";
         $newCount+=captureCocoachinaListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
         */
        
		return $newCount;
	}
	
	//更新慢 1页
	function captureCocoachinaMarketNewsListPages1($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;
		
        //市场推广
		$urlFormat = "http://www.cocoachina.com/appstore/market/list_27_%d.html";
		$newCount+=captureCocoachinaListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
		return $newCount;
	}
	
	//更新慢 4页
	function captureCocoachinaMarketNewsListPages2($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;
        
        //案例分析
		$urlFormat = "http://www.cocoachina.com/appstore/case/list_30_%d.html";
		$newCount+=captureCocoachinaListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
		//appstore研究
		$urlFormat = "http://www.cocoachina.com/appstore/top/list_28_%d.html"; 
		$newCount+=captureCocoachinaListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		//软件销售
		$urlFormat = "http://www.cocoachina.com/appstore/sales/list_26_%d.html"; 
		$newCount+=captureCocoachinaListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		//上线经验
		$urlFormat = "http://www.cocoachina.com/appstore/exp/list_29_%d.html"; 
		$newCount+=captureCocoachinaListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
		return $newCount;
	}
	
	function captureCocoachinaListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$pageCount=1;
		$fetchUrl = sprintf($urlFormat,1);  
		$firstPageContent=captureCocoachinaListPage($fetchUrl,$pageCount);

		if($pageCount>$maxCapturePageCount)
		{
			$pageCount=$maxCapturePageCount;
		}
		
		for ($i = $pageCount; $i>1; $i--) 
		{
			$fetchUrl = sprintf($urlFormat,$i);  
			$content=captureCocoachinaListPage($fetchUrl,$pageCount);
			$newCount+=CocoaChinaItem::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
		}

		$newCount+=CocoaChinaItem::parseItemList($firstPageContent,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
	function captureCocoachinaListPage($url,&$pageCount)
	{
		$results=fileGetContents( $url );

		$content = preg_match("/<ul[^>]*class=\"lists\"[^>]*>(.*?)<\/ul>/s",$results,$temp) ? $temp[1]:""; 
		
		if($pageCount<=1)
		{
			$pageInfo = preg_match("/<div class=\"page\">(.*)<\/div>/isU",$results,$temp) ? $temp[1]:"";  
			$pageCount = preg_match("/共 <strong>(\\d+)<\/strong>页/isU",$pageInfo,$temp) ? $temp[1]:""; 
		}

		return $content;
	}
?>

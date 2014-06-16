<?php
	include_once("cnblogsItem.class.php");
    include_once("cnblogsNewsItem.class.php");
	include_once("cnblogsKbItem.class.php");
	include_once("../common/request.function.php");
	
	//test
	//captureCnblogsWebDevListPages(40,"",NULL);

    function captureCnblogsNewsListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
        $newCount=0;
        
		$maxCapturePageCount=3;
		
		$urlFormat = "http://news.cnblogs.com/n/page/%d/";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic,"news");
        
        return $newCount;
	}
    
    function captureCnblogsIOSDevListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
        $newCount=0;
        
		$maxCapturePageCount=1;
		
		$urlFormat = "http://www.cnblogs.com/cate/ios/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
        return $newCount;
	}
    
	function captureCnblogsAndroidDevListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
        $newCount=0;
        
		$maxCapturePageCount=1;
		
		$urlFormat = "http://www.cnblogs.com/cate/android/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
        return $newCount;
	}
    
	function captureCnblogsCppListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
		$maxCapturePageCount=1;
		
		$urlFormat = "http://www.cnblogs.com/cate/cpp/%d"; 
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		$urlFormat = "http://www.cnblogs.com/cate/algorithm/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
        return $newCount;
	}
    
    function captureCnblogsJavaListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
		$maxCapturePageCount=1;
		
		$urlFormat = "http://www.cnblogs.com/cate/java/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
        return $newCount;
	}
    
    function captureCnblogsPhpListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
		$maxCapturePageCount=1;
		
		$urlFormat = "http://www.cnblogs.com/cate/php/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
        return $newCount;
	}
	
    function captureCnblogsWebDevListPages1($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
		$maxCapturePageCount=1;
        
        $urlFormat = "http://www.cnblogs.com/cate/web/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		$urlFormat = "http://www.cnblogs.com/cate/javascript/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
        return $newCount;
	}
	
	function captureCnblogsWebDevListPages2($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
		$maxCapturePageCount=1;
        
        $urlFormat = "http://www.cnblogs.com/cate/html5/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
        $urlFormat = "http://www.cnblogs.com/cate/jquery/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
        //更新慢
		//$urlFormat = "http://kb.cnblogs.com/list/1002/%d";
		//$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic,"kb");
        
        return $newCount;
	}
    
    function captureCnblogsProgramDesignListPages1($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
		$maxCapturePageCount=1;
		
		$urlFormat = "http://www.cnblogs.com/cate/dp/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
        $urlFormat = "http://www.cnblogs.com/cate/design/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
        return $newCount;
	}
	
	function captureCnblogsProgramDesignListPages2($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
		$maxCapturePageCount=1;
        
        $urlFormat = "http://www.cnblogs.com/cate/108702/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

        $urlFormat = "http://www.cnblogs.com/cate/agile/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
        return $newCount;
	}
	
	function captureCnblogsProgramDesignListPages3($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
		$maxCapturePageCount=1;
		
		//更新慢
		$urlFormat = "http://kb.cnblogs.com/list/1008/%d/";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic,"kb");
		
		$urlFormat = "http://kb.cnblogs.com/list/1004/%d/";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic,"kb");

        
        return $newCount;
	}
    
    function captureCnblogsProductDesignListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
		$maxCapturePageCount=1;
		
		$urlFormat = "http://www.cnblogs.com/cate/pm/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
        //更新慢
		/*
		$urlFormat = "http://kb.cnblogs.com/list/1009/%d/";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic,"kb");
		*/
		
        return $newCount;
	}
    
    /*
    function captureCnblogsMysqlListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
		$maxCapturePageCount=2;
		
		$urlFormat = "http://www.cnblogs.com/cate/mysql/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
        return $newCount;
	}
    
    function captureCnblogsOracleListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
		$maxCapturePageCount=2;
		
		$urlFormat = "http://www.cnblogs.com/cate/oracle/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
        return $newCount;
	}
    
    function captureCnblogsSqlserverListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
		$maxCapturePageCount=2;
		
		$urlFormat = "http://www.cnblogs.com/cate/sqlserver/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
        return $newCount;
	}
     */
    
    function captureCnblogsProgramLifeListPages1($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
		$maxCapturePageCount=1;

        $urlFormat = "http://www.cnblogs.com/cate/life/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
        $urlFormat = "http://www.cnblogs.com/cate/codelife/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
  
        return $newCount;
	}
	
	function captureCnblogsProgramLifeListPages2($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
		$maxCapturePageCount=1;
        
        $urlFormat = "http://www.cnblogs.com/cate/book/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		$urlFormat = "http://www.cnblogs.com/cate/job/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
        return $newCount;
	}
	
	function captureCnblogsProgramLifeListPages3($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
		$maxCapturePageCount=1;
        
        //update slow
        $urlFormat = "http://www.cnblogs.com/cate/translate/%d";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		$urlFormat = "http://kb.cnblogs.com/list/1011/%d/";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic,"kb");

        
        return $newCount;
	}
	
	function captureCnblogsProgramLifeListPages4($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
		$maxCapturePageCount=1;

        //update slow
		$urlFormat = "http://kb.cnblogs.com/list/1012/%d/";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic,"kb");
		
		$urlFormat = "http://kb.cnblogs.com/list/1015/%d/";
		$newCount+=captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic,"kb");
        
        return $newCount;
	}
    
	function captureCnblogsListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic,$type="")
	{
		$newCount=0;
        
		//$pageCount=1;
		$pageCount=$maxCapturePageCount;
		
		$fetchUrl = sprintf($urlFormat,1);
        if($type=="news")
        {
            $firstPageContent=captureCnblogsNewsListPage($fetchUrl,$pageCount);
        }
		else if($type=="kb")
        {
            $firstPageContent=captureCnblogsKbListPage($fetchUrl,$pageCount);
        }
        else
        {
            $firstPageContent=captureCnblogsListPage($fetchUrl,$pageCount);
        }

		if($pageCount>$maxCapturePageCount)
		{
			$pageCount=$maxCapturePageCount;
		}
        
        //test
        //echo $pageCount;
        //echo $firstPageContent;
		
		for ($i = $pageCount; $i>1; $i--) 
		{
			$fetchUrl = sprintf($urlFormat,$i);  
            if($type=="news")
            {
                $content=captureCnblogsNewsListPage($fetchUrl,$pageCount);
                $newCount+=CnblogsNewsItem::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
            }
			else if($type=="kb")
            {
                $content=captureCnblogsKbListPage($fetchUrl,$pageCount);
                $newCount+=CnblogsKbItem::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
            }
            else
            {
                $content=captureCnblogsListPage($fetchUrl,$pageCount);
                $newCount+=CnblogsItem::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
            }
		}

        if($type=="news")
        {
            $newCount+=CnblogsNewsItem::parseItemList($firstPageContent,$categoryCode,$tableName,$categoryPriorityDic);
        }
		else if($type=="kb")
        {
            $newCount+=CnblogsKbItem::parseItemList($firstPageContent,$categoryCode,$tableName,$categoryPriorityDic);
        }
        else
        {
            $newCount+=CnblogsItem::parseItemList($firstPageContent,$categoryCode,$tableName,$categoryPriorityDic);
        }
        
        return $newCount;
	}
	
	function captureCnblogsListPage($url,&$pageCount)
	{
		$results=fileGetContents( $url );

		$content = preg_match("/<div[^>]+id=\"post_list\"[^>]*>([\s\S]*)<div[^>]+id=\"pager_bottom\"[^>]*>/s",$results,$temp) ? $temp[1]:""; 
		
		return $content;
	}
    
    function captureCnblogsNewsListPage($url,&$pageCount)
	{
		$results=fileGetContents( $url );

		$content = preg_match("/<div[^>]+id=\"news_list\"[^>]*>([\s\S]*)<div[^>]+id=\"pages\"/i",$results,$temp) ? $temp[1]:"";
		
		return $content;
	}
	
	function captureCnblogsKbListPage($url,&$pageCount)
	{
		$results=fileGetContents( $url );

		$content = preg_match("/<div[^>]+id=\"list_block\"[^>]*>([\s\S]*)<div[^>]+id=\"pager_block\"[^>]*>/s",$results,$temp) ? $temp[1]:""; 
		
		return $content;
	}
?>

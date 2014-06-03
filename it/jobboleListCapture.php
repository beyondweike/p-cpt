<?php
	include_once("jobboleItem.class.php");
    include_once("../common/request.function.php");
	
	//test
	//captureJobboleProgrammerLifeListPages(2,"list_table",NULL);
	
	function captureJobboleProgrammerLifeListPages1($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;

        //it技术
		$urlFormat = "http://blog.jobbole.com/category/it-tech/page/%d/";
		$newCount+=captureJobboleListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
        //程序员
		$urlFormat = "http://blog.jobbole.com/category/programmer/page/%d/";
		$newCount+=captureJobboleListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
		return $newCount;
	}
	
	function captureJobboleProgrammerLifeListPages2($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;
        
        //创业
		$urlFormat = "http://blog.jobbole.com/category/startup/page/%d/";
		$newCount+=captureJobboleListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
        //书籍与教程
		$urlFormat = "http://blog.jobbole.com/category/books/page/%d/";
		$newCount+=captureJobboleListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
	function captureJobboleProgrammerLifeListPages3($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;

        //更新慢
		//自由职业
		$urlFormat = "http://blog.jobbole.com/category/freelance/page/%d/";
		$newCount+=captureJobboleListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
		//人力资源
		$urlFormat = "http://blog.jobbole.com/category/humanresource/page/%d/";
		$newCount+=captureJobboleListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

        //工具与资源
		$urlFormat = "http://blog.jobbole.com/category/resources/page/%d/";
		$newCount+=captureJobboleListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
		return $newCount;
	}
    
    function captureJobboleProductManagementListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;
		
		//设计
		$urlFormat = "http://blog.jobbole.com/category/design/page/%d/";
		$newCount+=captureJobboleListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
        //职场分享
		$urlFormat = "http://blog.jobbole.com/category/career/page/%d/";
		$newCount+=captureJobboleListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
        //更新慢
        /*
		//营销
		$urlFormat = "http://blog.jobbole.com/category/marketing/page/%d/";
		$newCount+=captureJobboleListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
        
		//管理
		$urlFormat = "http://blog.jobbole.com/category/management/page/%d/";
		$newCount+=captureJobboleListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		//运营
		$urlFormat = "http://blog.jobbole.com/category/operation/page/%d/";
		$newCount+=captureJobboleListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		*/

		return $newCount;
	}
    
    //轶事
    function captureJobboleAnecdoteListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;
        
		//趣文漫画
		$urlFormat = "http://blog.jobbole.com/category/humor-comic/page/%d/";
		$newCount+=captureJobboleListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
        //更新慢
        /*
		//在国外
		$urlFormat = "http://blog.jobbole.com/category/overseas/page/%d/";
		$newCount+=captureJobboleListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		*/
        
		return $newCount;
	}
    

	function captureJobboleListPagesWithUrlFormat($urlFormat,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;

        $pageCount=1;
		$fetchUrl = sprintf($urlFormat,$pageCount);
		$firstPageContent=captureJobboleListPage($fetchUrl,$pageCount);

        $pageCount=min($pageCount,$maxCapturePageCount);
        
        //test
        //echo $pageCount."<br>";
        //echo "1"."<br>";
		//echo $firstPageContent;
		
		for ($pageNum = 2; $pageNum<=$pageCount; $pageNum++)
		{
			$fetchUrl = sprintf($urlFormat,$pageNum);
			$content=captureJobboleListPage($fetchUrl,$pageCount);
			$newCount+=JobboleItem::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
			
			//test
            //echo $pageNum."<br>";
			//echo $content;
		}

		$newCount+=JobboleItem::parseItemList($firstPageContent,$categoryCode,$tableName,$categoryPriorityDic);
		
		return $newCount;
	}
	
	function captureJobboleListPage($url,&$pageCount)
	{
		$results=fileGetContents( $url );
        //$results=curlexec($url);
		//$results=iconv("GBK","UTF-8",$results);

		$content = preg_match("/<div[^>]+class=\"grid-8\"[^>]*>([\s\S]*)<div[^>]+class=\"navigation margin-20\"/i",$results,$temp) ? $temp[1]:"";
		if($pageCount<=1)
		{
			$pageInfo = preg_match("/<div[^>]+class=\"navigation margin-20\"[^>]*>([\s\S]*)<\/div>/i",$results,$temp) ? $temp[1]:"";
			$ret = preg_match_all("/\/page\/(\\d+)\//",$pageInfo,$temp);
            foreach($temp[1] as $number)
            {
                $pageCount=max($pageCount,$number);
            }
		}

		return $content;
	}
?>

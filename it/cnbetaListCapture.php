<?php
	include_once("cnbetaItem.class.php");
    include_once("../common/request.function.php");
	
	//test
	//captureCnbetaScienceNewsListPagesStep1(0,"list_table",NULL);
	
    //apple
	function captureCnbetaAppleNewsListPages1($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
		$firstPageSplitCount=3;//固定
		
		//查看网页，http://www.cnbeta.com/topics/9.htm

		//iPhone
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=379&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		//ipad
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=464&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
	//apple
	function captureCnbetaAppleNewsListPages2($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
		$firstPageSplitCount=3;//固定
		
		//查看网页，http://www.cnbeta.com/topics/9.htm

		//apple
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=9&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		//slow
		/*
		$maxCapturePageCount=1;
		$firstPageSplitCount=2;//固定
		
		//iPod
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=343&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		*/

		return $newCount;
	}
	
	//android
	function captureCnbetaAndroidNewsListPages1($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
		$firstPageSplitCount=3;//固定

		//android
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=444&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
	
		return $newCount;
	}
	
	//android
	function captureCnbetaAndroidNewsListPages2($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
		
		$maxCapturePageCount=1;
		$firstPageSplitCount=3;//固定

		//小米
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=487&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		//华为
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=331&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
	
		return $newCount;
	}
	
	//android
	function captureCnbetaAndroidNewsListPages3($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;

		$maxCapturePageCount=1;
		$firstPageSplitCount=3;//固定

		//HTC
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=439&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		//三星
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=371&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
	//game
	function captureCnbetaGameNewsListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
		$firstPageSplitCount=3;//固定

		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=39&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
	//php //更新慢
	function captureCnbetaPhpNewsListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
		$firstPageSplitCount=1;//固定

		//http://www.cnbeta.com/topics/21.htm
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=21&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
	//ProgramLife
	function captureCnbetaProgramLifeNewsListPages($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
		$firstPageSplitCount=3;//固定
		//博文精选
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=311&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		$maxCapturePageCount=1;
		$firstPageSplitCount=3;//固定
		//原创独家选
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=125&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
	//science and technology
	function captureCnbetaScienceNewsListPagesStep1($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
		$firstPageSplitCount=3;//固定
		
		//手机
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=243&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		//视点观察
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=305&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
	function captureCnbetaScienceNewsListPagesStep2($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
		$firstPageSplitCount=3;//固定
		
		//科学探索
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=448&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		//3D
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=469&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		//谷歌
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=52&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
	//science and technology
	function captureCnbetaScienceNewsListPagesStep3($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
		$firstPageSplitCount=3;//固定
		
		//硬件
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=70&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		//诺基亚
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=147&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
	function captureCnbetaScienceNewsListPagesStep4($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
		$firstPageSplitCount=3;//固定
		
		//警告
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=45&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		//美国
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=422&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		//人物
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=453&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
	//science and technology
	function captureCnbetaScienceNewsListPagesStep5($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
		$firstPageSplitCount=3;//固定
		
		//B2C
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=353&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		//通信技术
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=138&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
	function captureCnbetaScienceNewsListPagesStep6($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
		$firstPageSplitCount=3;//固定
		
		//google glass
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=326&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		//yahoo
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=91&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		//intel
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=32&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
	//science and technology
	function captureCnbetaScienceNewsListPagesStep7($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
		$firstPageSplitCount=3;//固定
		
		//中国移动
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=348&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		//中国联通
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=391&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		//中国电信
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=316&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	function captureCnbetaScienceNewsListPagesStep8($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
		$firstPageSplitCount=3;//固定
		
		//通信运营商
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=372&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		//通信技术 4G
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=437&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
	//science and technology
	function captureCnbetaScienceNewsListPagesStep9($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
		$firstPageSplitCount=3;//固定
		
		//最新消息
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=260&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		//软件新闻
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=8&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
	function captureCnbetaScienceNewsListPagesStep10($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
		$firstPageSplitCount=3;//固定

		//IT与铁路
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=421&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		//360
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=300&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
	//science and technology
	function captureCnbetaScienceNewsListPagesStep11($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
		$firstPageSplitCount=3;//固定
		
		//访客互动
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=306&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		//即时通信
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=403&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
	function captureCnbetaScienceNewsListPagesStep12($categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;
        
        $maxCapturePageCount=1;
		$firstPageSplitCount=3;//固定
		
		//中国
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=424&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);
		
		//评测室
		$urlFormat = "http://www.cnbeta.com/topics/more.htm?id=479&page=%d&split_page=%d";
		$newCount+=captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic);

		return $newCount;
	}
	
    function captureCnbetaListPagesWithUrlFormat($urlFormat,$firstPageSplitCount,$maxCapturePageCount,$categoryCode,$tableName,$categoryPriorityDic)
	{
		$newCount=0;

        $pageCount=$maxCapturePageCount;

		for ($pageNum = $pageCount; $pageNum>=2; $pageNum--)
		{
			$fetchUrl = sprintf($urlFormat,$pageNum,1);
			$content=captureCnbetaItemListPage($fetchUrl);
			$newCount+=CnbetaItem::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
		}

		for ($splitPageNum = $firstPageSplitCount; $splitPageNum>=1; $splitPageNum--)
		{
			$fetchUrl = sprintf($urlFormat,1,$splitPageNum);
			$content=captureCnbetaItemListPage($fetchUrl);
			$newCount+=CnbetaItem::parseItemList($content,$categoryCode,$tableName,$categoryPriorityDic);
		}
		
		return $newCount;
	}
	
	function captureCnbetaItemListPage($url)
	{
		$results=fileGetContents( $url );

        //date_default_timezone_set('Asia/Shanghai');//'Asia/Shanghai'   亚洲/上海
        //$filePathName="../logs/capture_cnbeta_".date("Y-m-d",time()).".log";
        //log2File($filePathName,$url." ");

		$arr=json_decode($results, true);

        return $arr["result"]["list"];;
	}
?>

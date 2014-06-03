<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php"); 

	class ShuShaoItem extends Item
	{

		/*
         <div class="Listpage_Text">
         <h3><a target="_blank" href="/apple/item/210502-ios7" title="iOS7 Beta5与Beta4通话、设置界面对比 ">iOS7 Beta5与Beta4通话、设置界面对比 </a></h3>
         <div class="Listpage_Mixed">
         <div class="Content_imge">
         <a href="/apple/item/210502-ios7" title="iOS7 Beta5与Beta4通话、设置界面对比 ">
         <img src="http://www.shushao.com/images/stories/2013/201308/0807/00021550.jpg" alt="iOS7 Beta5与Beta4通话、设置界面对比 "></a>
         </div>
         <div class="Listpage_intro">
         <p>
         iOS7 Beta5或许是最后一个iOS7的测试版，正式版iOS7很快就会到来。iOS7 Beta5的改变有如下：1、设置功能的颜色变了；2、控制中心有变化；3关机滑块变化，增加了箭头； 4、+键更名九宫格，布局更好看了 ；5、开机背景有变化，白色机子黑苹果，黑色机子白苹果； 6、植物2不闪退了 ；7、解锁没声音。锁屏有，锁屏声音没有延迟，响应速度变快。 8、facetime音频取消了 ；9、下拉多了天气股票提供商 ；1 0、线控问题解决。</p>                    </div>
         </div>
         <div class="Listpage_lable">

	
		*/

		function parse($itemHtmlContent)
		{
			$ret=preg_match("/<img[^>]+src=\"(.*?)\"[^>]*>/i", $itemHtmlContent, $temp);//(\s\S)
			if($ret)
			{
				$this->thumbnailUrl = $temp[1];

				$baseurl = 'http://www.shushao.com/';
				$this->thumbnailUrl=format_url($this->thumbnailUrl, $baseurl);
			}

			$ret=preg_match("/<a.*?href=\"(.*?)\".*?>(.*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->title = $temp[2];
				$this->href = $temp[1];

				$baseurl = 'http://www.shushao.com/';
				$this->href=format_url($this->href, $baseurl);
			}

			$ret=preg_match("/<p[^>]*>([\s\S]*)<\/p>/i", $itemHtmlContent, $temp);
			if($ret)
			{
                $this->briefDesc=preg_replace("/<a.*?<\/a>/i","", $temp[1]);
			}

			//debug
            //echo $itemHtmlContent."<br><br><br><br><br><br><br><br>";
			//echo $this->title." ".$this->href." ".$this->thumbnailUrl." ".$this->briefDesc." ".$this->datetime."<br><br><br><br><br><br><br><br>";
		}
		 
		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName,$categoryPriorityDic)
		 {
			$regString="/<div[^>]+class=\"Listpage_Text\"[^>]*>([\s\S]*?)<div[^>]+class=\"Listpage_lable\"[^>]*>/i";
			$captureNum=1;
			$itemClassName="ShuShaoItem";
			return Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
		}
	}
?>
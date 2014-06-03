<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php");
    include_once("../common/string.function.php");

	class CnblogsNewsItem extends Item
	{

		/*
         <div class="content">
         <h2 class="news_entry">
         <a href="/n/184219/" target="_blank">新浪尝试门户变革 转型“媒体+社交+电商”复合模式</a>
         </h2>
         <div class="entry_summary" style="display: block;">
         <a href="/n/topic_10.htm" title="新浪"><img src="/images/logo/sina.gif" class="topic_img" alt=""/></a>
         新浪正在率先尝试一场门户变革。昨天上午，新浪与 NBA 达成战略合作。新浪拿下的权益是 NBA 互联网赛事的视频直播、NBA 中国官方在线社区的合作运营、休闲游戏、电商运营，以及移动端的直播和点播。此次框架合作的时间是，从 2013 年~2014 年，连续两个赛季。事实上，早在 ...
         </div>
         <div class="entry_footer">
	
		*/

		function parse($itemHtmlContent)
		{
			$ret=preg_match("/<img[^>]+src=\"(.*?)\"[^>]*>/i", $itemHtmlContent, $temp);//(\s\S)
			if($ret)
			{
				$this->thumbnailUrl = $temp[1];

				$baseurl = 'http://news.cnblogs.com/';
				$this->thumbnailUrl=format_url($this->thumbnailUrl, $baseurl);
			}

			$ret=preg_match("/<a.*?href=\"(.*?)\".*?>(.*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->title = $temp[2];
				$this->href = $temp[1];

				$baseurl = 'http://news.cnblogs.com/';
				$this->href=format_url($this->href, $baseurl);
			}

			$ret=preg_match("/<div[^>]+class=\"entry_summary\"[^>]*>([\s\S]*)<\/div>/i", $itemHtmlContent, $temp);
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
			$regString="/<div[^>]+class=\"content\"[^>]*>([\s\S]*?)<div[^>]+class=\"entry_footer\"[^>]*>/i";
			$captureNum=1;
			$itemClassName="CnblogsNewsItem";
			return Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
		}
	}
?>
<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php");  
    include_once("../common/string.function.php");

	class TuicoolItem extends Item
	{
		function parse($itemHtmlContent)
		{
			$ret=preg_match("/<a[^>]+href=\"(.*?)\"[^>]*>(.*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
                $this->href = $temp[1];
				$this->title = $temp[2];

				$baseurl = 'http://www.tuicool.com/';
				$this->href=format_url($this->href, $baseurl);
			}
			
			$ret=preg_match("/<div class=\"article_cut\">([\s\S]*?)<\/div>/i", $itemHtmlContent, $temp);
			if($ret)
			{
                $this->briefDesc=$temp[1];
			}
			
			$ret=preg_match("/<img[^>]+src=\"(.*?)\"[^>]*>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->thumbnailUrl = $temp[1];
			}
		}
		 
		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName,$categoryPriorityDic)
		 {
			$newCount=0;
			$regString="/<div[^>]+class=\"single_fake\"[^>]*>([\s\S]*?)<div[^>]+class=\"clear\"/i";
			$captureNum=1;
			$itemClassName="TuicoolItem";
			$newCount += Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
             
			return $newCount;
		}
	}
?>
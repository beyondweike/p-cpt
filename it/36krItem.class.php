<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php");  
    include_once("../common/string.function.php");

	class S36krItem extends Item
	{
		function parse($itemHtmlContent)
		{
			$ret=preg_match("/<img[^>]+data-src=\"(.*?)\"/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->thumbnailUrl = $temp[1];

				$baseurl = 'http://www.36kr.com/';
				$this->thumbnailUrl=format_url($this->thumbnailUrl, $baseurl);
			}

			$ret=preg_match("/<h1><a[^>]+href=\"(.*?)\"[^>]*>(.*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->title = $temp[2];
				$this->href = $temp[1];

				$baseurl = 'http://www.36kr.com/';
				$this->href=format_url($this->href, $baseurl);
			}

			$ret=preg_match("/<p[^>]*>([\s\S]*?)<\/p>/i", $itemHtmlContent, $temp);
			if($ret)
			{
                $this->briefDesc=$temp[1];
			}
		}
		 
		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName,$categoryPriorityDic)
		 {
			$regString="/<article[^>]*>([\s\S]+?)<\/article>/i";
			$captureNum=1;
			$itemClassName="S36krItem";
			return Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
		}
	}
?>
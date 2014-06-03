<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php"); 

	class ItemGamerboom extends Item
	{
		function parse($itemHtmlContent)
		{
			$ret=preg_match("/<img[^>]+src=\"(.*?)\"/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->thumbnailUrl = $temp[1];

				$baseurl = 'http://www.gamerboom.com/';
				$this->thumbnailUrl=format_url($this->thumbnailUrl, $baseurl);
			}

			$ret=preg_match("/<a[^>]+href=\"(.*?)\"[^>]*>(.*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->title = $temp[2];
				$this->href = $temp[1];

				$baseurl = 'http://www.gamerboom.com/';
				$this->href=format_url($this->href, $baseurl);
			}

			$ret=preg_match("/<dd\s*?>([\s\S]*?)\.{3}/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->briefDesc = $temp[1];
			}
		}
		 
		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName,$categoryPriorityDic)
        {
            $regString="/<div class=\"news\">([\s\S]+?)<div class=\"clear\"/i";
			$captureNum=1;
			$itemClassName="ItemGamerboom";
			return Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
		}
	}
?>
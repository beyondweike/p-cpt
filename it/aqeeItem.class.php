<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php"); 
    include_once("../common/string.function.php");
    
	class AqeeItem extends Item
	{
		function parse($itemHtmlContent)
		{
			$ret=preg_match("/<img.*?data-original=\"(.*?)\".*?>/i", $itemHtmlContent, $temp);//(\s\S)
			if($ret)
			{
				$this->thumbnailUrl = $temp[1];

				$baseurl = 'http://www.vaikan.com/';
				$this->thumbnailUrl=format_url($this->thumbnailUrl, $baseurl);
			}

			$ret=preg_match_all("/<a[^>]+href=\"(.*?)\"[^>]*>(.*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
                $this->href = $temp[1][0];
				$this->title = $temp[2][0];
	
				$find = array("<b>","</b>"," <span class='title-arra'>&rarr;</span>");
				$this->title=str_ireplace($find,"",$this->title);

				$baseurl = 'http://www.vaikan.com/';
				$this->href=format_url($this->href, $baseurl);
			}

			$ret=preg_match("/<p>(.*?)<a/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->briefDesc = $temp[1];
			}
		}
		 
		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName,$categoryPriorityDic)
		 { 
			$regString="/<h2 class=\"entry-title\">([\s\S]+?)<span class=\"meta-nav\"/i";
			$captureNum=1;
			$itemClassName="AqeeItem";
			return Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
		}
	}
?>
<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php"); 

	class EoeItem extends Item
	{
		function parse($itemHtmlContent)
		{ 
			$ret=preg_match("/<a[^>]+><img src=\"(.*?)\"/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->thumbnailUrl = $temp[1];

				$baseurl = 'http://www.eoe.cn';
				$this->thumbnailUrl=format_url($this->thumbnailUrl, $baseurl);
			}

			$ret=preg_match("/<a href=\'(.*?)\'[^>]*>(.*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->title = $temp[2];
				$this->href = $temp[1];

				$find = array("<h2>","</h2>","&nbsp;");
				$this->title=str_ireplace($find,"",$this->title);
				$this->title=preg_replace("/<img[^>]+>/i", "", $this->title);
				
				$baseurl = 'http://www.eoe.cn';
				$this->href=format_url($this->href, $baseurl);
			}

			$ret=preg_match("/<div class=\"ue-body-new-list-desc-text\"([\s\S]+?)<br/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->briefDesc = $temp[1];
			}
		}

		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName,$categoryPriorityDic)
		 { 
			$regString="/<div class=\"ue-body-new-list-t\">([\s\S]+?)<div class=\"ue-body-new-list-other\"/i";
			$captureNum=1;
			$itemClassName="EoeItem";
			return Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
		}
	}
?>
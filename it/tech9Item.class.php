<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php");  
    include_once("../common/string.function.php");

	class Tech9Item extends Item
	{
		function parse($itemHtmlContent)
		{            
			$ret=preg_match("/<img[^>]+src=\'(.*?)\'/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->thumbnailUrl = $temp[1];

				$baseurl = 'http://cms.9tech.cn/';
				$this->thumbnailUrl=format_url($this->thumbnailUrl, $baseurl);
			}

			$ret=preg_match("/<a[^>]+href=\"(.*?)\"[^>]+title=\"(.*?)\"/i", $itemHtmlContent, $temp);
			if($ret)
			{
                $this->href = $temp[1];
				$this->title = $temp[2];

				$baseurl = 'http://cms.9tech.cn/';
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
            $newCount=0;
			$regString="/<li>([\s\S]*?)<\/li>/i";
			$captureNum=1;
			$itemClassName="Tech9Item";
			$newCount += Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
             
			return $newCount;
		}
	}
?>
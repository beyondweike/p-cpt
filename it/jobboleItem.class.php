<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php");  
    include_once("../common/string.function.php");

	class JobboleItem extends Item
	{
		function parse($itemHtmlContent)
		{
			$ret=preg_match("/<img[^>]+src=\"(.*?)\"[^>]*>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->thumbnailUrl = $temp[1];

				$baseurl = 'hhttp://blog.jobbole.com/';
				$this->thumbnailUrl=format_url($this->thumbnailUrl, $baseurl);
			}

			$ret=preg_match("/<a[^>]+class=\"meta-title\"[^>]+href=\"(.*?)\"[^>]*>(.*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->title = $temp[2];
				$this->href = $temp[1];

				$baseurl = 'hhttp://blog.jobbole.com/';
				$this->href=format_url($this->href, $baseurl);
			}

			$ret=preg_match("/<span[^>]+class=\"excerpt\"><p>([\s\S]*)<\/p>/i", $itemHtmlContent, $temp);
			if($ret)
			{
                $this->briefDesc=$temp[1];
			}

			//debug
            //echo $itemHtmlContent."<br><br><br><br><br><br><br><br>";
			//echo $this->title." ".$this->href." ".$this->thumbnailUrl." ".$this->briefDesc."<br><br><br><br><br><br><br><br>";
		}
		 
		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName,$categoryPriorityDic)
		 {
			$regString="/<div[^>]+class=\"post-thumb\"[^>]*>([\s\S]*?)<p[^>]+class=\"align-right\"[^>]*>/i";
			$captureNum=1;
			$itemClassName="JobboleItem";
			return Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
		}
	}
?>
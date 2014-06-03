<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php");  
    include_once("../common/string.function.php");

	class Tech163Item extends Item
	{
		function parse($itemHtmlContent)
		{
			$ret=preg_match("/<img[^>]+src=\"(.*?)\"[^>]*>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->thumbnailUrl = $temp[1];

				$baseurl = 'http://tech.163.com/';
				$this->thumbnailUrl=format_url($this->thumbnailUrl, $baseurl);
			}

			$ret=preg_match("/<a[^>]+href=\"(.*?)\">((?!<img)[\s\S]*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
                $this->href = $temp[1];
				$this->title = $temp[2];

				$baseurl = 'http://tech.163.com/';
				$this->href=format_url($this->href, $baseurl);
                
                $poss=strpos($this->href,"http://tech.163.com/photoview");
                if($poss!==false)
                {
                    $this->href="";
                }
			}

			$ret=preg_match("/<p[^>]*><a[^>]*>([\s\S]*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
                $this->briefDesc=$temp[1];
			}
		}
		 
		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName,$categoryPriorityDic)
		 {
			$newCount=0;
			 
			$regString="/<div[^>]+class=\"clearfix\"[^>]*>([\s\S]+?)<\/div>\s*<\/div>/i";
			$captureNum=1;
			$itemClassName="Tech163Item";
			$newCount += Item::parseItems($srcItemsHtmlContent[0],$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
			
			//没图片的不要了
            if(count($srcItemsHtmlContent)>1)
            {
                $regString="/<div[^>]+class=\"color-link clearfix\">([\s\S]+?<\/a>)\s*<\/div>/i";
                $newCount += Item::parseItems($srcItemsHtmlContent[1],$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
			}
             
			return $newCount;
		}
	}
?>
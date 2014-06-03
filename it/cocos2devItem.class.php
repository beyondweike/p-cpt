<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php");  
    include_once("../common/string.function.php");

	class Cocos2devItem extends Item
	{
		function parse($itemHtmlContent)
		{
            /*
             <h2 class="post-title"><a href="http://www.cocos2dev.com/?p=478">ios判断摇晃手机状态</a></h2>
             */
            
            /*
			$ret=preg_match("/<img[^>]+src=\"(.*?)\"/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->thumbnailUrl = $temp[1];

				$baseurl = 'http://www.woshipm.com/';
				$this->thumbnailUrl=format_url($this->thumbnailUrl, $baseurl);
			}
             */

			$ret=preg_match("/<a[^>]+href=\"(.*?)\"[^>]*>(.*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
                $this->href = $temp[1];
				$this->title = $temp[2];

				$baseurl = 'http://www.cocos2dev.com/';
				$this->href=format_url($this->href, $baseurl);
			}

            /*
			$ret=preg_match("/<p[^>]*>([\s\S]*?)<\/p>/i", $itemHtmlContent, $temp);
			if($ret)
			{
                $this->briefDesc=$temp[1];
			}
             */
            
			//debug
			//echo $this->title." ".$this->href." ".$this->thumbnailUrl." ".$this->briefDesc."<br><br><br><br><br><br><br><br>";
		}
		 
		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName,$categoryPriorityDic)
		 {
			$newCount=0;
			$regString="/<h2 class=\"post-title\">([\s\S]*?)<\/h2>/i";
			$captureNum=1;
			$itemClassName="Cocos2devItem";
			$newCount += Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
             
			return $newCount;
		}
	}
?>
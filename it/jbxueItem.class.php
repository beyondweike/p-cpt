<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php");  
    include_once("../common/string.function.php");

	class JbxueItem extends Item
	{
		function parse($itemHtmlContent)
		{
            /*
             
             <li>·<a href="/article/13647.html" title="php inc文件的风险分析" target="_blank">php inc文件的风险分析</a> <span>(2013-11-15 11:42:51)</span></li>
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

				$baseurl = 'http://www.jbxue.com/';
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
			$regString="/<li>([\s\S]*?)<\/li>/i";
			$captureNum=1;
			$itemClassName="JbxueItem";
			$newCount += Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
             
			return $newCount;
		}
	}
?>
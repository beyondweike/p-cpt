<?php
    include_once("../common/item.class.php"); 
	include_once("../common/string.function.php"); 
	
	class CSDNTagItem extends Item
	{

		/*
         <a class="tit_list" href="http://blog.csdn.net/riqzhu/article/details/11730793" target="_blank" onclick="LogClickCount(this,389);">vs2012 + cocos2d-x 2.1.5 + win7开发环境搭建步骤</a><br/>
         <span class="tag_summary">摘要：先要让vs具备cocos2d-x项目的模板，以此可以创建新的项目(1-5步),然后把相关的源码库文件和动态连接库都拷贝到自己的项目中，以使项目可以正常运行(6-7步...</span>
		*/

		function parse($itemHtmlContent)
		{ 
			$ret=preg_match("/<a.*?href=\"(.*?)\".*?>(.*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->title = $temp[2];
				$this->href = $temp[1];
			}

			$ret=preg_match("/<span.*?>(.*?)<\/span>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->briefDesc = $temp[1];
			}

			//debug
			//echo $this->title." ".$this->href." ".$this->thumbnailUrl." ".$this->briefDesc."<br><br><br>";
		}
		 
		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName,$categoryPriorityDic)
		 {
			$regString="/<div[^>]+class=\"line_list\"[^>]*>([\s\S]*?)<div[^>]+class=\"dwon_words\"/i";
			$captureNum=1;
			$itemClassName="CSDNTagItem";
			return Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
		}
	}
?>
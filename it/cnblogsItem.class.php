<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php"); 
    include_once("../common/string.function.php"); 

	class CnblogsItem extends Item
	{

		/*
         <div class="post_item_body">
         <h3><a class="titlelnk" href="http://www.cnblogs.com/xuybin/p/3166904.html" target="_blank">调不尽的内存泄漏，用不完的Valgrind</a></h3>
         <p class="post_item_summary">
         调不尽的内存泄漏，用不完的ValgrindValgrind 安装1. 到www.valgrind.org下载最新版valgrind-X.X.X.tar.bz22. 解压安装包：tar –jxvf valgrind-3.2.3.tar.bz23. 解压后生成目录valgrind-3.2.34. cd ...
         </p>
         <div class="post_item_foot">
	
		*/

		function parse($itemHtmlContent)
		{
			$ret=preg_match("/<img.*?src=\"(.*?)\".*?\/>/i", $itemHtmlContent, $temp);//(\s\S)
			if($ret)
			{
				$this->thumbnailUrl = $temp[1];

				$baseurl = 'http://www.cnblogs.com/';
				$this->thumbnailUrl=format_url($this->thumbnailUrl, $baseurl);
			}

			$ret=preg_match("/<a.*?href=\"(.*?)\".*?>(.*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->title = $temp[2];
				$this->href = $temp[1];

				$baseurl = 'http://www.cnblogs.com/';
				$this->href=format_url($this->href, $baseurl);
			}

			$ret=preg_match("/<p[^>]*>([\s\S]+)<\/p>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->briefDesc = $temp[1];
			}
		}
		 
		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName,$categoryPriorityDic)
		 { 
			$regString="/<div.*?class=\"post_item_body\">([\s\S]*?)<div.*?class=\"post_item_foot\">/i";
			$captureNum=1;
			$itemClassName="CnblogsItem";
			return Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
		}
	}
?>
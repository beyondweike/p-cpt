<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php"); 

	class CnblogsKbItem extends Item
	{

		/*
          <div class="list_title"><img src="/images/icons/arrow.png" alt="" class="list_img"/> <a href="/page/183895/" target="_blank">两程序员不同境遇：少抱怨 多解决问题</a></div>
                            <div class="listinfo">很久以前有两个程序，当时的水准都差不多，现在A是上市公司的技术总监，B还在不停的跳槽，反反复复在“小团队主程”和“大公司打杂”的两种岗位之间不停切换。B一直把这些不同归咎于自己没有遇到A那样子的机遇，经常在群里和微博抱怨自己的运气。 那天我终于忍不住了，在他再一次抱怨之后，我开始喷他，我说你就...</div>                            
                            <div class="listfooter">

	
		*/

		function parse($itemHtmlContent)
		{
			/*
			$ret=preg_match_all("/<img[^>]+src=\"(.*?)\"[^>]*>/i", $itemHtmlContent, $temp);//(\s\S)
			if($ret)
			{
				$this->thumbnailUrl = $temp[1][0];

				$baseurl = 'http://news.cnblogs.com/';
				$this->thumbnailUrl=format_url($this->thumbnailUrl, $baseurl);
			}
			*/

			$ret=preg_match("/<a.*?href=\"(.*?)\".*?>(.*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->title = $temp[2];
				$this->href = $temp[1];

				$baseurl = 'http://kb.cnblogs.com/';
				$this->href=format_url($this->href, $baseurl);
			}

			$ret=preg_match("/<div[^>]+class=\"listinfo\"[^>]*>([\s\S]*)<\/div>/i", $itemHtmlContent, $temp);
			if($ret)
			{
                $this->briefDesc=$temp[1];
			}

			//debug
            //echo $itemHtmlContent."<br><br><br><br><br><br><br><br>";
			//echo $this->title." ".$this->href." ".$this->thumbnailUrl." ".$this->briefDesc." ".$this->datetime."<br><br><br><br><br><br><br><br>";
		}
		 
		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName,$categoryPriorityDic)
		 {
			$regString="/<div[^>]+class=\"list_title\"[^>]*>([\s\S]*?)<div[^>]+class=\"listfooter\"[^>]*>/i";
			$captureNum=1;
			$itemClassName="CnblogsKbItem";
			return Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
		}
	}
?>
<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php"); 

	class W3cfunsItem extends Item
	{

		/*
         <div class="articleList cl">
         <div class="titlePlate">
         
         
         
         <h2><a href="article-1080-1.html" target="_blank">用来简化开发任务的20个JavaScript类库</a></h2>
         <p>
         <a class="bold" href="home.php?mod=space&amp;uid=3" target="_blank">Alice</a>
         发布于 <span title="2013-7-10 20:01">昨天&nbsp;20:01</span>                    <a href="caturl"target>前端资源</a>
         </p>
         <span class="clickNum css3 css3_br3">198<i></i></span>
         </div>
         <div class="pic"><a href="article-1080-1.html" target="_blank"><img alt="用来简化开发任务的20个JavaScript类库" src="data/attachment/portal/201307/10/195617k7a7zan7f87pq2lb.jpg"/></a></div>
         <em>所谓JS库就是预先写好的JS程序库，用于简化以JS为基础的开发程序，尤其是对AJAX和其他以Web为中心技术的JS代码集。JS的首要用途是将编写的功能内嵌在HTML页面，并与页面的对象模型(DOM)进行互动。很多JS库很容易和其 ...
         
         
         
         <a class="more" href="portal.php?mod=view&amp;aid=1080&amp;page=1" target="_blank">阅读全文&raquo;</a></em>
         </div>

	
		*/

		function parse($itemHtmlContent)
		{
			$ret=preg_match("/<img.*?src=\"(.*?)\".*?\/>/i", $itemHtmlContent, $temp);//(\s\S)
			if($ret)
			{
				$this->thumbnailUrl = $temp[1];

				$baseurl = 'http://www.w3cfuns.com/';
				$this->thumbnailUrl=format_url($this->thumbnailUrl, $baseurl);
			}

			$ret=preg_match("/<a.*?href=\"(.*?)\".*?>(.*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->title = $temp[2];
				$this->href = $temp[1];

				$baseurl = 'http://www.w3cfuns.com/';
				$this->href=format_url($this->href, $baseurl);
			}

			$ret=preg_match("/<em>([\s\S]*)/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->briefDesc = $temp[1];
			}

			//debug
			//echo $this->title." ".$this->href." ".$this->thumbnailUrl." ".$this->briefDesc." ".$this->datetime."<br><br><br><br><br><br><br><br>";
		}
		 
		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName,$categoryPriorityDic)
		 { 
			$regString="/<div[^>]+class=\"titlePlate\">([\s\S]*?)<a[^>]+class=\"more\"/i";
			$captureNum=1;
			$itemClassName="W3cfunsItem";
			return Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
		}
	}
?>
<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php"); 

	class ItemIpadown extends Item
	{

		/*
         <div class="unit">
         <h1><a href="http://news.ipadown.com/24814" target="_blank">从2013Chinajoy事件 窥六大手游行业新趋势</a></h1>
         
         <div class="ct cf">
         <ul>
         <li>2013-07-30 | 匿名投稿</li>
         <li><a href="http://news.ipadown.com/cid-4">移动互联网</a> </li>
         <li>热度:5℃</li>
         </ul>
         </div>
         <dl>
         <dt>
         <a href="http://news.ipadown.com/24814" target="_blank"><img src="http://file.ipadown.com/uploads/news_thumb/13751446463504.jpg"  alt="从2013Chinajoy事件 窥六大手游行业新趋势"/><div class="mask-img"></div></a>
         </dt>
         <dd><p>2013年chinajoy刚刚在上海落下帷幕。随着各大端游巨头的进入和新兴手游厂商的不断壮大，在本届展会期间，可以很明显看到手机游戏的展位比重和规模都和去年有了质的飞跃，从CJ期间的各类事件和大佬的演讲和专访言辞中，带大家一窥目前手游行业的新趋势：</p><p>国外的很多手机游戏进入中国，国内的游戏也在不断走出去，目前的国内手机游戏行业，可以用全球化来概括，而这种全球化分为带进来和走出去两种。</p></dd>
         </dl>
         </div>
		*/

		function parse($itemHtmlContent)
		{
			$ret=preg_match("/<img[^>]+src=\"(.*?)\"[^>]*\/>/i", $itemHtmlContent, $temp);//(\s\S)
			if($ret)
			{
				$this->thumbnailUrl = $temp[1];

				$baseurl = 'http://www.ipadown.com/';
				$this->thumbnailUrl=format_url($this->thumbnailUrl, $baseurl);
			}

			$ret=preg_match("/<a[^>]+href=\"(.*?)\"[^>]*>(.*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->title = $temp[2];
				$this->href = $temp[1];

				$baseurl = 'http://www.ipadown.com/';
				$this->href=format_url($this->href, $baseurl);
			}

			$ret=preg_match("/<dd>([\s\S]*?)<\/dd>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->briefDesc = $temp[1];
			}

			//debug
			//echo $this->title." ".$this->href." ".$this->thumbnailUrl." ".$this->briefDesc." ".$this->datetime."<br><br><br><br><br><br><br><br>";
		}
		 
		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName,$categoryPriorityDic)
        {
            $regString="/<div[^>]+class=\"unit\"[^>]*>([\s\S]*?)<\/dl>\s*?<\/div>/i";
			$captureNum=1;
			$itemClassName="ItemIpadown";
			return Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
		}
	}
?>
<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php");  
    include_once("../common/string.function.php");

	class GamelookItem extends Item
	{
		function parse($itemHtmlContent)
		{
            /*
             <div class="entry-thumb">
             <a href="http://www.gamelook.com.cn/2013/08/127875" rel="bookmark"><img src="http://www.gamelook.com.cn/wp-content/themes/weekly_1.0.3/timthumb.php?src=http://www.gamelook.com.cn/wp-content/uploads/auto_save_image/2013/08/0109448NU.jpg&amp;h=80&amp;w=80&amp;zc=1" alt="腾讯获MOBA竞技网游SMITE全球代理权" /></a>	</div> <!--end .entry-thumb-->
             
             <h2 class="entry-title"><a href="http://www.gamelook.com.cn/2013/08/127875" title="Permalink to 腾讯获MOBA竞技网游SMITE全球代理权" rel="bookmark">腾讯获MOBA竞技网游SMITE全球代理权</a></h2>
             
             <div class="entry-meta">
             <span class="meta-date">2013.08.22</span>
             <span class="meta-sep">|</span>
             <span class="meta-comments"><a href="http://www.gamelook.com.cn/2013/08/127875#comments" title="腾讯获MOBA竞技网游SMITE全球代理权 上的评论"><span class="ds-thread-count" data-thread-key="127875" data-replace="1">7 Comments</span></a></span>
             </div> <!--end .entry-meta-->
             
             <div class="entry-excerpt">
             <p>腾讯获取3D动作团队竞技网游《SMITE》的全球代理权并正式公布游戏中文名“神之浩劫”，预计在 10月份进行中国大陆地区首测，随后再在中国大陆以外的国家和地区陆续启动全球运营，游戏官网（sm.qq.com）也同步上线。</p>
             */
            
			$ret=preg_match("/<img[^>]+src=\"(.*?)\"[^>]*>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->thumbnailUrl = $temp[1];

				$baseurl = 'http://www.gamelook.com.cn/';
				$this->thumbnailUrl=format_url($this->thumbnailUrl, $baseurl);
			}

			$ret=preg_match_all("/<a[^>]+href=\"(.*?)\"[^>]*>(.*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->title = $temp[2][1];
				$this->href = $temp[1][1];

				$baseurl = 'http://www.gamelook.com.cn/';
				$this->href=format_url($this->href, $baseurl);
			}

			$ret=preg_match("/<p[^>]*>([\s\S]*?)<\/p>/i", $itemHtmlContent, $temp);
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
			$regString="/<div[^>]+class=\"entry-thumb\"[^>]*>([\s\S]*?<\/p>)/i";
			$captureNum=1;
			$itemClassName="GamelookItem";
			return Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
		}
	}
?>
<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php"); 

	class QianduanItem extends Item
	{

		/*
         <!--post item-->
				<div class="postItem clearfix">
					<h2 class="h1" id="post-14377"><a href="http://www.qianduan.net/metamorphosis-webrebuild-2013-year-end-exchange-will-invite.html" rel="bookmark" title="永久链接到 蜕变·WebRebuild 2013 前端年度交流会邀请">蜕变·WebRebuild 2013 前端年度交流会邀请</a></h2>
					<div class="post-meta-top"><a href="http://www.qianduan.net/author/mienflying" title="Posts by 神飞" rel="author">神飞</a> 发表于 16. Oct, 2013, 分类: <a href="http://www.qianduan.net/category/news-of-designs" title="View all posts in Front News" rel="category tag">Front News</a>, <a href="http://www.qianduan.net/metamorphosis-webrebuild-2013-year-end-exchange-will-invite.html#comments" title="Comment on 蜕变·WebRebuild 2013 前端年度交流会邀请">3 条评论 &#187;</a></div>
					<!-- post-meta-top #end -->
					<p>互联网web前端设计行业通过一段时期的茧封或焰炼，web技术使行业、企业及自身发生质的改变。痛苦的蜕变是成长的契机，在彼此互相冲击、交流、融合的对话下，将以尊重包容互助合作同... <!--read more-->
					<a href="http://www.qianduan.net/metamorphosis-webrebuild-2013-year-end-exchange-will-invite.html" title="查看 蜕变·WebRebuild 2013 前端年度交流会邀请 的全部内容" class="moreLink">阅读全文 &gt;</a>
				</div>
				<!-- post bottom #end -->

		*/

		function parse($itemHtmlContent)
		{
			/*
			$ret=preg_match_all("/<img.*?src=\"(.*?)\".*?\/>/i", $itemHtmlContent, $temp);//(\s\S)
			if($ret)
			{
				$this->thumbnailUrl = $temp[1][0];

				$baseurl = 'http://www.w3cfuns.com/';
				$this->thumbnailUrl=format_url($this->thumbnailUrl, $baseurl);
			}
			*/

			$ret=preg_match("/<a.*?href=\"(.*?)\".*?>(.*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->title = $temp[2];
				$this->href = $temp[1];

				$baseurl = 'http://www.qianduan.net/';
				$this->href=format_url($this->href, $baseurl);
			}

			$ret=preg_match("/<p>([\s\S]*)(<\/p>|... <!--read more-->)/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->briefDesc = $temp[1];
			}

			//debug
			//echo $this->title." ".$this->href." ".$this->thumbnailUrl." ".$this->briefDesc." ".$this->datetime."<br><br><br><br><br><br><br><br>";
		}
		 
		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName,$categoryPriorityDic)
		 { 
			$regString="/<!--post item-->([\s\S]*?)<!-- post bottom #end -->/i";
			$captureNum=1;
			$itemClassName="QianduanItem";
			return Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
		}
	}
?>
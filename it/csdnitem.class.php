<?php
    include_once("../common/item.class.php"); 
	
	class CSDNItem extends Item
	{

		/*
				<div class="unit">
					<h1>
						<a href="http://www.csdn.net/article/2013-04-23/2815005-Leap-Motion" target="_blank" >Leap Motion：500元体感，精确到0.01毫米</a>
					</h1>
					<h4>
						发表于<span class="ago">16小时前</span>|<span class="view_time">4962次阅读</span>|<span class="num_recom">35条评论</span>
					</h4>
					<dl>				
						<dt>
								<a href="http://www.csdn.net/article/2013-04-23/2815005-Leap-Motion" target="_blank">
									<img src="http://cms.csdnimg.cn/article/201304/23/5176516ea2765.jpg" alt="" />
								</a>
						</dt>
						<dd>
							Leap Motion的体感控制技术能让人通过手指直接控制电脑，系统的感应区间能够精确到0.01毫米，这种精度要远高于微软的Kinect。你现在就可以通过SDK开发它的应用了。
						</dd>
					</dl>
				</div>
 			
		*/

		function parse($itemHtmlContent)
		{ 
			$ret=preg_match("/<a.*?href=\"(.*?)\".*?>(.*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->title = $temp[2];
				$this->href = $temp[1];
			}

			$ret=preg_match("/<img.*?src=\"(.*?)\"/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->thumbnailUrl = $temp[1];
			}

			$ret=preg_match("/<dd.*?>(.*?)<\/dd>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->briefDesc = $temp[1];
			}

			//debug
			//echo $this->title." ".$this->href." ".$this->thumbnailUrl." ".$this->briefDesc."<br>";
		}
		 
		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName,$categoryPriorityDic)
		 { 
			$regString="/<div.*?class=\"unit\"[^>]*?>([\s\S]*?)<\/div>/i";
			$captureNum=1;
			$itemClassName="CSDNItem";
			return Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
		}
	}
?>
<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php"); 

	class YeskyItem extends Item
	{
		/*
         <li><a class="wdpc" href="http://dev.yesky.com/490/35460490.shtml"><img src="http://image.tianjimedia.com//uploadImages/2013/340/RCYP6349QIE3_L.jpg" alt="iOS开发建议" /></a><p><span><a href="http://dev.yesky.com/490/35460490.shtml">iOS开发：从新手到专家的一些建议</a>12-06</span>
         众所周知，产品经理都是很苦逼的角色，苦逼到要去迎合任何角色，这边协调，那边哀求，我想很多人都有深切的体会。但是，大家都是一个产品团队里面的 成员，要想融洽的配合，以完成产品目标，就需要相互之间可以亲密…
         </p></li>
		*/

		function parse($itemHtmlContent)
		{ 
			$ret=preg_match("/<img src=\"(.*?)\"(.*?)\/>/i", $itemHtmlContent, $temp);//(\s\S)
			if($ret)
			{
				$this->thumbnailUrl = $temp[1];

				$baseurl = 'http://www.yesky.com';
				$this->thumbnailUrl=format_url($this->thumbnailUrl, $baseurl);
			}

			$ret=preg_match_all("/<a[^>]*href=\"(.*?)\"[^>]*>(.*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->title = $temp[2][1];
				$this->href = $temp[1][1];

				//$find = array("<b>","</b>");
				//$this->title=str_ireplace($find,"",$this->title);

				$baseurl = 'http://www.yesky.com';
				$this->href=format_url($this->href, $baseurl);
			}

			$ret=preg_match("/<\/span>([\s\S]*?)<\/p>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->briefDesc = $temp[1];
			}
		}
		 
		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName,$categoryPriorityDic)
		 {
			$regString="/<li>([\s\S]*?)<\/li>/i";
			$captureNum=1;
			$itemClassName="YeskyItem";
			return Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
		}
	}
?>
<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php"); 

	class AndroidStudyItem extends Item
	{

		/*
                                        <ul>
                                            <li><a title="智能手机罪过之“短信脖”" href="listxx.aspx?id=494" target="_blank">
                                                智能手机罪过之“短信脖”</a> </li>
                                            <li class="time">
                                                2013-06-29</li></ul>
                                    </div>
                                    <div class="intro_info">
                                        继制造大量“短信拇指”之后，手机又有了一大罪状——制造“短信脖”。背部和颈部问题专家表示，由于将大量时间花在手机和电脑上，饱受颈痛折磨的患者越来越多。他们指出智能手机和平板电脑的风靡让这一问题更加严重.... <a href="listxx.aspx?id=494" target="_blank">
                                            [阅读全文]</a></div>
		*/

		function parse($itemHtmlContent)
		{ 
			/*
			$ret=preg_match_all("/<img src=\"(.*?)\"(.*?)\/>/i", $itemHtmlContent, $temp);//(\s\S)
			if($ret)
			{
				$this->thumbnailUrl = $temp[1][0];

				$baseurl = 'http://www.yesky.com';
				$this->thumbnailUrl=format_url($this->thumbnailUrl, $baseurl);
			}
			*/

			$ret=preg_match("/<a[^>]*href=\"(.*?)\"[^>]*>([\s\S]*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->title = $temp[2];
				$this->href = $temp[1];
				$ret=preg_match_all("/^\s*(.+?)\s*$/i", $this->title, $temp);
				if($ret)
				{
					$this->title = $temp[1];
				}

				//$find = array("<b>","</b>");
				//$this->title=str_ireplace($find,"",$this->title);

				$baseurl = 'http://www.android-study.net/';
				$this->href=="/".$this->href;
				$this->href=format_url($this->href, $baseurl);
			}

			$ret=preg_match("/<div[^>]*class=\"intro_info\">([\s\S]*?)<a/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->briefDesc = $temp[1];
			}

			//debug
			//echo "title:".$this->title."<br>href:".$this->href."<br>thumbnailUrl:".$this->thumbnailUrl."<br>briefDesc:".$this->briefDesc."<br>datetime:".$this->datetime."<br><br>";
		}
		 
		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName,$categoryPriorityDic)
		 {
			$regString="/<div[^>]*class=\"title_info\">([\s\S]*?)<div[^>]*class=\"info_info\">/i";
			$captureNum=1;
			$itemClassName="AndroidStudyItem";
			return Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
		}
	}
?>
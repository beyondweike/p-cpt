<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php"); 

	class YoukuItem extends Item
	{

		/*
       		<ul class="v">
        <li class="v_link"><a  href="http://v.youku.com/v_show/id_XMjQwOTc0MTYw.html" charset="102039-12383-29-1" target="video"></a></li>
    <li class="v_thumb">
        <img src="http://g3.ykimg.com/01270F1F464D474BDE3C9A000000003EB48B0F-C899-80E5-8C34-981837B67632" alt="中国联通增收不增利 预告业绩下滑50%" title="中国联通增收不增利 预告业绩下滑50%" />
    </li>
    							<li class="v_ishd"><span class="ico__HD" title="高清"></span> </li>
						<li class="v_menu" id="PlayListFlag_XMjQwOTc0MTYw"></li>
								<li class="v_title"><a href="http://v.youku.com/v_show/id_XMjQwOTc0MTYw.html" charset="102039-12383-29-2" target="video" title="中国联通增收不增利 预告业绩下滑50%">中国联通增收不增利 预告业绩下滑50%</a></li>
														<li class="v_user"><span class="ico__useroffical" title="官网"></span>  <a href="http://i.youku.com/u/UMTA5NTc5NzY=" charset="102039-12383-29-3" target="_blank" >北京电视台</a></li>
						<li class="v_stat"><span class="ico__statplay" title="播放"></span><span class="num">4,109</span> <span title="评论" class="ico__statcomment"></span><span class="num">18</span></li>
						<li class="v_stat"><span class="ico__statupdown" title="顶踩"></span><span class="num"><em class="up">38</em>/<em class="down">9</em></span></li>
			</ul>

		*/

		function parse($itemHtmlContent)
		{ 
			$ret=preg_match_all("/<img src=\"(.*?)\"(.*?)\/>/i", $itemHtmlContent, $temp);//(\s\S)
			if($ret)
			{
				$this->thumbnailUrl = $temp[1][0];

				$baseurl = 'http://www.youku.com';
				$this->thumbnailUrl=format_url($this->thumbnailUrl, $baseurl);
			}

			$itemHtmlContent = preg_match("/<li[^>]*class=\"v_title\"[^>]*>(.*?)<\/li>/s",$itemHtmlContent,$temp) ? $temp[1]:""; 
			//<a href="http://v.youku.com/v_show/id_XMjM4NDcwMDc2.html" charset="102039-12383-30-2" target="video" title="3G鐢ㄦ埛澧為噺鎸佺画鎵╁ぇ TD鐢ㄦ埛棣栫牬2000涓�">3G鐢ㄦ埛澧為噺鎸佺画鎵╁ぇ TD鐢ㄦ埛棣栫牬2000涓�</a>
		
			$ret=preg_match_all("/<a[^>]*href=\"(.*?)\"[^>]*>(.*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->title = $temp[2][0];
				$this->href = $temp[1][0];

				//$find = array("<b>","</b>");
				//$this->title=str_ireplace($find,"",$this->title);

				$movieUrlFormat="http://v.youku.com/player/getRealM3U8/vid/%s/type//video.m3u8";
				$movieId = preg_match("/id_(.*?)\\./s",$this->href,$temp) ? $temp[1]:""; 
				$this->href = sprintf($movieUrlFormat,$movieId);  
				//$baseurl = 'http://www.youku.com';
				//$this->href=format_url($this->href, $baseurl);
			}

			/*
			$ret=preg_match_all("/<br(.*?)\/>(.*?)<\/p>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->briefDesc = $temp[2][0];
			}
			*/

			$this->datetime=date("Y-m-d",time());

			//debug
			//echo $this->title." ".$this->href." ".$this->thumbnailUrl." ".$this->briefDesc." ".$this->datetime."<br><br>";
		}
		 
		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName)
		 {
			$regString="/<ul class=\"v\">([\s\S]*?)<\/ul>/i";
			$captureNum=1;
			$itemClassName="YoukuItem";
			return Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName);
		}
	}
?>
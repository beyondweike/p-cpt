<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php");  
    include_once("../common/string.function.php");

	class WoshipmItem extends Item
	{
		function parse($itemHtmlContent)
		{
            /*
             <a href="http://www.woshipm.com/ms/44884.html" title="应届生产品经理求职简历怎么写？" target="_blank">
             <img src="http://www.woshipm.com/wp-content/uploads/2013/09/116-300x191.jpg" alt="应届生产品经理求职简历怎么写？" width="200" height="154" />
             </a>
             </div>
             <div class="fr box_content">
             <h2><a href="http://www.woshipm.com/ms/44884.html" title="应届生产品经理求职简历怎么写？" target="_blank">应届生产品经理求职简历怎么写？</a></h2>        		<div class="info">
             <span>2013年09月24日 18:17更新 </span>
             <span>标签：<a href="http://www.woshipm.com/tag/pmd" rel="tag">产品经理</a>, <a href="http://www.woshipm.com/tag/%e6%b1%82%e8%81%8c" rel="tag">求职</a>, <a href="http://www.woshipm.com/tag/%e7%ae%80%e5%8e%86" rel="tag">简历</a></span>
             </div>
             <p class="intro">
             [embed]自从写了上篇文章后，收到很多孩子的简历，有点意外，没想到今年校园招聘产品经理如此之火爆。但是承诺还是要履行的，这次我把这段时间修改简历时发现的一些问题汇总下，供各位想求职产品经理的孩子参考!
             
             想让我帮...					</p>
             
             ////////
             <div class="ft clearfix " id="post-57284">
             <div class="f_img_box">
             <a target="_blank" href="http://www.woshipm.com/it/57284.html" title="淘宝是低成本业态？对不起，你被忽悠了！">
             <img width="180" height="134" src="http://image.woshipm.com/wp-files/2013/12/2012060111273930436-180x134.jpg" class="attachment-gallery180cc134 wp-post-image" alt="淘宝是低成本业态？对不起，你被忽悠了！" title="淘宝是低成本业态？对不起，你被忽悠了！" />          </a>
             </div>
             <div class="f_con_box">
             <h2 class="clx"><a href="http://www.woshipm.com/it/57284.html" target='_blank' title="淘宝是低成本业态？对不起，你被忽悠了！">淘宝是低成本业态？对不起，你被忽悠了！</a><span><a href="http://www.woshipm.com/it/57284.html#respond" class="ds-thread-count" data-thread-key="57284" title="《淘宝是低成本业态？对不起，你被忽悠了！》上的评论">0</a></span></h2>
             <div class="f_info">
             <span class="f_c_time"><i>12/13</i><i>9:27</i>更新</span>
             
             <span class="f_c_tag"><a href="http://www.woshipm.com/tag/%e6%b7%98%e5%ae%9d" rel="tag">淘宝</a></span>
             
             
             </div><!--f_info end-->
             <p class="f_content">
             
             
             淘宝是低成本业态？对不起，你被忽悠了！
             有时候就像趴在窗户上的苍蝇，外面的风景很好，美食很多，但就是飞不过去。
             这用来形容很多淘宝人，......		</p>
             <div class="f_in"><span class="f_c_view">围观<i>27</i>次</span></div>
             </div>
             </div><!--ft end-->
             */
            
			$ret=preg_match("/<img[^>]+src=\"(.*?)\"/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->thumbnailUrl = $temp[1];

				$baseurl = 'http://www.woshipm.com/';
				$this->thumbnailUrl=format_url($this->thumbnailUrl, $baseurl);
			}

			$ret=preg_match("/<a[^>]+href=\"(.*?)\"[^>]+title=\"(.*?)\"/i", $itemHtmlContent, $temp);
			if($ret)
			{
                $this->href = $temp[1];
				$this->title = $temp[2];

				$baseurl = 'http://www.woshipm.com/';
				$this->href=format_url($this->href, $baseurl);
			}

			$ret=preg_match("/<p[^>]*>([\s\S]*?)<\/p>/i", $itemHtmlContent, $temp);
			if($ret)
			{
                $this->briefDesc=$temp[1];
			}

			//debug
			//echo $this->title." ".$this->href." ".$this->thumbnailUrl." ".$this->briefDesc."<br><br><br><br><br><br><br><br>";
		}
		 
		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName,$categoryPriorityDic)
		 {
            $newCount=0;
			$regString="/<div[^>]+class=\"ft clearfix\s*\"[^>]*>([\s\S]*?)<\/div>\s*<!--ft end-->/i";
			$captureNum=1;
			$itemClassName="WoshipmItem";
			$newCount += Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
             
			return $newCount;
		}
	}
?>
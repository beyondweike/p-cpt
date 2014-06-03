<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php"); 
	include_once("../common/string.function.php");
	
	class CocoaChinaItem extends Item
	{

		/*
                	<ul class="lists">
                    	
                            <li>
                        	<table>
                            	<tr>
                                	<td class="col1">
                                    	<a href="/gamedev/2013/0513/6180.html"><img src="/cms/uploads/allimg/130513/4196-1305130932010-L.jpg" alt="Cocos2D-X屏幕适配新解" /></a>
                                    </td>
                                    <td class="col2">
                                    	<h3><a href="/gamedev/2013/0513/6180.html">Cocos2D-X屏幕适配新解</a></h3>
                                        <p class="arc-info"><span>2013-05-13</span><span>阅读数：<script src="/cms/plus/count.php?view=no&aid=6180&mid=4196" type='text/javascript' language="javascript"></script></span></p>
                                        <p class="arc-des">为了适应移动终端的各种分辨率大小，各种屏幕宽高比，在 Cocos2D-X（当前稳定版：2.0.4） 中，提供了相应的解决方案，以方便我们在设计游戏时，能够更好的适应不同的环境。 而在设计游戏之...</p>
                                    </td>
                                </tr>
                            </table>
                        </li>
                            
                            <li class="even">
                        	<table>
                            	<tr>
                                	<td class="col1">
                                    	<a href="/gamedev/2013/0503/6120.html"><img src="/cms/uploads/allimg/130503/4196-1305030932410-L.png" alt="Rovio社交题材游戏Angry Birds Friends上架App Store" /></a>
                                    </td>
                                    <td class="col2">
                                    	<h3><a href="/gamedev/2013/0503/6120.html">Rovio社交题材游戏Angry Birds Friends上架App Store</a></h3>
                                        <p class="arc-info"><span>2013-05-03</span><span>阅读数：<script src="/cms/plus/count.php?view=no&aid=6120&mid=4196" type='text/javascript' language="javascript"></script></span></p>
                                        <p class="arc-des">上个月Rovio曾表示要将Facebook社交游戏Angry Birds Friends移植至iPad和iPhone平台。今早时候，Angry Birds Friends终于上架App Store。iOS版Angry Birds Friends把经典的Angry Birds游戏机制和Facebook的社交功能很好地结...</p>
                                    </td>
                                </tr>
                            </table>
                        </li> 			
		*/

		function parse($itemHtmlContent)
		{ 
			$ret=preg_match("/<img.*?src=\"(.*?)\"/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->thumbnailUrl = $temp[1];

				$baseurl = 'http://www.cocoachina.com';
				$this->thumbnailUrl=format_url($this->thumbnailUrl, $baseurl);
			}

			$itemHtmlContent = preg_match("/<td[^>]*class=\"col2\"[^>]*>(.*?)<\/td>/s",$itemHtmlContent,$temp) ? $temp[1]:""; 
			$ret=preg_match("/<a.*?href=\"(.*?)\".*?>(.*?)<\/a>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->title = $temp[2];
				$this->href = $temp[1];

				$find = array("<b>","</b>");
				$this->title=str_ireplace($find,"",$this->title);

				$baseurl = 'http://www.cocoachina.com';
				$this->href=format_url($this->href, $baseurl);
			}

			$ret=preg_match("/<p.*?class=\"arc-des\">(.*?)<\/p>/i", $itemHtmlContent, $temp);
			if($ret)
			{
				$this->briefDesc = $temp[1];
			}

			//debug
			//echo $this->title." ".$this->href." ".$this->thumbnailUrl." ".$this->briefDesc." ".$this->datetime."<br>";
		}
		 
		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName,$categoryPriorityDic)
		 { 			
			$regString="/<li.*?[^>]*?>([\s\S]*?)<\/li>/i";
			$captureNum=1;
			$itemClassName="CocoaChinaItem";
			return Item::parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
		}
	}
?>
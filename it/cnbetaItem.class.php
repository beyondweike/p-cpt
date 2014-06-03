<?php
    include_once("../common/item.class.php"); 
	include_once("../common/file.function.php");  
    include_once("../common/string.function.php");

	class CnbetaItem extends Item
	{
		function parse($itemArrayContent)
		{            
			$this->thumbnailUrl = $itemArrayContent["logo"];//htmlspecialchars_decodex($temp[1]);
			
			$this->title = $itemArrayContent["title_show"];
			
			$this->href = $itemArrayContent["url_show"];
			$baseurl = 'http://www.cnbeta.com/';
			$this->href=format_url($this->href, $baseurl);
			
			$this->briefDesc = $itemArrayContent["hometext_show_short2"];

			//debug
			//echo $this->title." ".$this->href." ".$this->thumbnailUrl." ".$this->briefDesc."<br><br><br><br><br><br><br><br>";
		}
		 
		 public static function parseItemList($srcItemsHtmlContent,$categoryCode,$tableName,$categoryPriorityDic)
		 {
			$newCount=0;
			
			$itemHtmlContentArray=array_reverse($srcItemsHtmlContent);
			$itemClassName="CnbetaItem";
			
			$newCount=Item::parseForeachItems($itemHtmlContentArray,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
             
			return $newCount;
		}
	}
?>
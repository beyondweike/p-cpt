<?php
    include_once("string.function.php");
    include_once("json.function.php");
	include_once("../controller/db.function.php");
	
	class Item
	{
		var $id=0;
		var $articleId=0;
		var $title="";
		var $datetime="";
		var $href="";
		var $thumbnailUrl="";
		var $briefDesc="";
		var $categoryCode=0;
		var $readTimes=0;
		var $commentCount=0;
		var $shareTimes=0;
		
		//abstract protected function parse($htmlContent);

		function parseRow($row)
		{ 
			$this->id = $row['id'];
			$this->articleId = $this->id;
			$this->datetime = $row['datetime'];
			$this->title =$row['title'];
			$this->href = stripslashes($row['href']);
			$this->thumbnailUrl = $row['thumbnailUrl'];
			$this->categoryCode = $row['categoryCode'];
            $this->briefDesc = $row['briefDesc'];
			$this->commentCount = $row['commentCount'];
			$this->shareTimes = $row['shareTimes'];
			
			//used for json
			$trans = array( "..."  => "",
							"\r\n" => "",
							"\r"  => "",
							"\n"  => "");
        	$this->briefDesc = strtr($this->briefDesc, $trans);
		}

		function jsonEncode()
		{
			$paris=array();
			$paris[]=jsonEncodeKeyNumberPair("id",$this->id);
			$paris[]=jsonEncodeKeyNumberPair("articleId",$this->articleId);
			$paris[]=jsonEncodeKeyStringPair("title",$this->title);
			$paris[]=jsonEncodeKeyStringPair("datetime",$this->datetime);
			$paris[]=jsonEncodeKeyStringPair("href",$this->href);
			$paris[]=jsonEncodeKeyStringPair("thumbnailUrl",$this->thumbnailUrl);
			$paris[]=jsonEncodeKeyStringPair("briefDesc",$this->briefDesc);
			$paris[]=jsonEncodeKeyNumberPair("categoryCode",$this->categoryCode);
			$paris[]=jsonEncodeKeyNumberPair("readTimes",$this->readTimes);
			$paris[]=jsonEncodeKeyNumberPair("commentCount",$this->commentCount);
			$paris[]=jsonEncodeKeyNumberPair("shareTimes",$this->shareTimes);
	
			$json=jsonEncodePairs($paris);
			
			return $json;
		}
		
		function preproccessValues()
		{
             $this->title = htmlspecialchars_decodex($this->title);
             $this->href = htmlspecialchars_decodex($this->href);
			 //$this->href=addslashes($this->href);
             $this->thumbnailUrl = htmlspecialchars_decodex($this->thumbnailUrl);
             $this->briefDesc = htmlspecialchars_decodex($this->briefDesc);
			 $trans = array( "..."  => "");
        	 $this->briefDesc = strtr($this->briefDesc, $trans);
			
			 if($this->datetime=="")
			 {
				 date_default_timezone_set('Asia/Shanghai');
				 $this->datetime=date("Y-m-d",time());//"Y-m-d H:i:s"
			 }
			 
			 $isValuesValid=true;
			 
			 if(!$this->title || !$this->href || $this->title=="" || $this->href=="")
             {
                 $isValuesValid=false;
             }
			 
			 return $isValuesValid;
		 }
	
		 function checkItemExists($tableName,&$existsId=NULL,&$existsCategoryCode=NULL)
		 {
			 $exists=false;
			 
			 $sql="select id,categoryCode from ".$tableName." where href='".$this->href."'";
             
			 //test
			 //echo $sql;
			 //echo " <br><br>";
			 
			 $result=mysql_query($sql);
			 
			 if($result!==false)
			 {
				if($rows = mysql_fetch_array($result))
				{
					 $exists=true;
					 $existsId=$rows[0];
					 $existsCategoryCode=$rows[1];
				}
			 }
			 else
			 {
				date_default_timezone_set('Asia/Shanghai');
			 	$filePathName="../logs/sql_error_".date("Y-m-d",time()).".log";
				log2File($filePathName,$sql."\n".mysql_error());
			 }
			 
             //test
			 //echo "existsId: ".$existsId." title: ".$this->title." categoryCode: ".$this->categoryCode." existsCategoryCode: ".$existsCategoryCode."<br>";
			 
			 return $exists;
		 }

		 function insertItemToDatabase($tableName,$categoryPriorityDic)
		 {
			//warning. make sure href,title field enough long.   vchar 300
			 $existsId=-1;
             $existsCategoryCode=-1;
			 $result=false;
			 
			 $exists=$this->checkItemExists($tableName,$existsId,$existsCategoryCode);
			 
			 if(!$exists)
			 {
				 date_default_timezone_set('Asia/Shanghai');
				 $theDate=date("Y-m-d",strtotime("-1 week"));//only require date,"Y-m-d H:i:s"
				 
				 $sql="select id,categoryCode from ".$tableName." where datetime>'".$theDate."' and title='".$this->title."'";
				 $result=mysql_query($sql);
			 
				 if($result===false)
				 {
					date_default_timezone_set('Asia/Shanghai');
					$filePathName="../logs/sql_error_".date("Y-m-d",time()).".log";
					log2File($filePathName,$sql."\n".mysql_error());
				 }
				 
				 if($rows = mysql_fetch_array($result))
				 {
					 $existsId=$rows[0];
					 $existsCategoryCode=$rows[1];
					 
					 $this->id=$existsId;
					 $this->categoryCode=$existsCategoryCode;
				 }
			 }
			 else
			 {
				 $this->id=$existsId;
				 $this->categoryCode=$existsCategoryCode;
			 }

			 if($existsId<0)
			 {
                 $sql="INSERT INTO ".$tableName."(title,href,datetime,thumbnailUrl,briefDesc,categoryCode,tags) ".
				 " VALUES('".$this->title."', '".$this->href."', '".$this->datetime."', '".$this->thumbnailUrl."', '".$this->briefDesc."', ".$this->categoryCode.", '')";
                 
				 $result=mysql_query($sql);
                 
                 if($result===false)
                 {
					date_default_timezone_set('Asia/Shanghai');//'Asia/Shanghai'   亚洲/上海
			 		$filePathName="../logs/sql_error_".date("Y-m-d",time()).".log";
					log2File($filePathName,$sql."\n".mysql_error());//die(mysql_error());
                 }
				 else
                 {
					$this->id=mysql_insert_id();
                 }
			 }
             else if($existsCategoryCode!=$this->categoryCode && $categoryPriorityDic
                     && $categoryPriorityDic["0".$this->categoryCode]>$categoryPriorityDic["0".$existsCategoryCode])
             {
                 $sql="update ".$tableName." set title='".$this->title."',".
                                                 "href='".$this->href."',".
                                                 "datetime='".$this->datetime."',".
                                                 "thumbnailUrl='".$this->thumbnailUrl."',".
                                                 "briefDesc='".$this->briefDesc."',".
                                                 "categoryCode=".$this->categoryCode." where id=".$existsId;
                 $result=mysql_query($sql);
                 
                 //test
                 //echo $sql." ret:".$ret."<br>";
                 
                 if($result===false)
                 {
					date_default_timezone_set('Asia/Shanghai');
			 		$filePathName="../logs/sql_error_".date("Y-m-d",time()).".log";
					log2File($filePathName,$sql."\n".mysql_error());
                 }
             }
			 /*
			 else if($existsCategoryCode==$this->categoryCode)
             {
				 //临时的
                 $sql="update ".$tableName." set title='".$this->title."',".
                                                 "href='".$this->href."',".
                                                 "datetime='".$this->datetime."',".
                                                 "thumbnailUrl='".$this->thumbnailUrl."',".
                                                 "briefDesc='".$this->briefDesc."',".
                                                 "categoryCode=".$this->categoryCode." where id=".$existsId;
                 $result=mysql_query($sql);                 
                 if($result===false)
                 {
                     die(mysql_error());
                 }
             }
			 */
			 
			 return $result;
		 }

		 public static function setTags($url,$tagArray,$tableName)
		 {
			$ret=false;
			 
			$sql="SELECT id,tags FROM ".$tableName." where href='".$url."'";

			$result=mysql_query($sql);
			if($rows = mysql_fetch_array($result))
			{
				$id=$rows[0];
				$tempTagsStr=$rows[1];
				
				$tagsStr=$tempTagsStr;
				$tempTagArray=explode(',',$tempTagsStr);
				
				foreach ($tagArray as $tag)
    			{
					if(!in_array($tag, $tempTagArray))
					{
						if(strlen($tagsStr)==0)
						{
							$tagsStr=$tag;
						}
						else
						{
							$tagsStr.=",".$tag;
						}
					}
				}
				
				if($tagsStr!=$tempTagsStr)
				{
					$sql="update ".$tableName." set tags='".$tagsStr."' where id=".$id;
					$ret=mysql_query($sql);
				}
			}
			
			//return $ret;
		 }
        
		public static function addReadTimes($articleId,$readTimes,$tableName)
        {
            $sql="update ".$tableName." set readTimes=readTimes+".$readTimes." where id=".$articleId;
            $ret=mysql_query($sql);
            
            return $ret;
        }
		
        public static function addOneReadTimes($articleId,$tableName)
        {
            return Item::addReadTimes($articleId,1,$tableName);
        }
		
		public static function addOneCommentCount($articleId,$tableName)
        {
            $sql="update ".$tableName." set commentCount=commentCount+1 where id=".$articleId;
            $ret=mysql_query($sql);
            
            return $ret;
        }
		
		public static function addOneShareTimes($articleId,$tableName)
        {
            $sql="update ".$tableName." set shareTimes=shareTimes+1 where id=".$articleId;
            $ret=mysql_query($sql);
            
            return $ret;
        }
        
        public static function updateCategory($articleId,$categoryCode,$tableName)
        {
            $sql="update ".$tableName." set categoryCode=".$categoryCode." where id=".$articleId;
            $ret=mysql_query($sql);
            
            return $ret;
        }
		  
		 public static function queryHrefByTitle($title,$tableName)
		 {
            $href="";
			
			$sql="SELECT href FROM ".$tableName." where title='".$title."'";

			$result=mysql_query($sql);
			if($rows = mysql_fetch_array($result))
			{
				$href=$rows[0];
			}

			return $href;
		 }
		 
		 public static function queryIdByHref($href,$tableName)
		 {
            $id=0;
			
			$sql="SELECT id FROM ".$tableName." where href='".$href."'";

			$result=mysql_query($sql);
			if($rows = mysql_fetch_array($result))
			{
				$id=$rows[0];
			}

			return $id;
		 }
		 
		 public static function queryStatistics($articleId,$tableName,&$readTimes,&$commentCount,&$shareTimes)
		 {
			$sql="SELECT readTimes,commentCount,shareTimes FROM ".$tableName." where id=".$articleId;
			$ret=mysql_query($sql);
			if($ret)
			{
				if($row = mysql_fetch_array($ret))
				{
					$readTimes=$row[0];
					$commentCount=$row[1];
					$shareTimes=$row[2];
				}
			}

			return $ret;
		 }
		 
		 public static function queryItemById($articleId,$tableName)
		 {
            $item=NULL;
			
			$sql="SELECT * FROM ".$tableName." where id=".$articleId;
			$result=mysql_query($sql);
			if($row = mysql_fetch_array($result))
			{
				$item=new Item();
			 	$item->parseRow($row);
			}

			return $item;
		 }
		 
		 public static function queryNewestCount($topItemId,$categoryCode,$tableName)
		 {
            $count=0;

			$sql="";
			if($topItemId>0)
			{
				$sql="SELECT count(*) FROM ".$tableName." where categoryCode=".$categoryCode." and id>".$topItemId;
			}
			else
			{
				$sql="SELECT count(*) FROM ".$tableName." where categoryCode=".$categoryCode;
			}
			
			$result=mysql_query($sql);
			if($rows = mysql_fetch_array($result))
			{
				$count=$rows[0];
			}

			return $count;
		 }

		 public static function queryNewest($topItemId,$categoryCode,$pageSize,$tableName)
		 {
			$arr = NULL;

			$sql="";
			if($topItemId>0)
			{
				$sql="SELECT * FROM ".$tableName." where categoryCode=".$categoryCode." and id>".$topItemId." order by id desc limit 0,".$pageSize;
			}
			else
			{
				$sql="SELECT * FROM ".$tableName." where categoryCode=".$categoryCode." order by id desc limit 0,".$pageSize;
			}

			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result))
			{
			  //echo $row['id'] . " " .$row['title'] . " " . $row['href']. " " . $row['datetime'];
			  //echo "<br />";

			  $item=new Item();
			  $item->parseRow($row);

			  if(!$arr)
			  {
				$arr = array();
			  }

			  $arr[] = $item;
			}

			return $arr;
		 }

		 public static function queryMore($lastItemId,$categoryCode,$pageSize,$tableName)
		 {
			$arr = NULL;

			$sql="";
			if($lastItemId>0)
			{
				$sql="SELECT * FROM ".$tableName." where categoryCode=".$categoryCode." and id<".$lastItemId." order by id desc limit 0,".$pageSize;
			}
			else
			{
				$sql="SELECT * FROM ".$tableName." where categoryCode=".$categoryCode." order by id desc limit 0,".$pageSize;
			}

			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result))
			{
			  //echo $row['id'] . " " .$row['title'] . " " . $row['href']. " " . $row['time'];
			  //echo "<br />";
	
			  $item=new Item();
			  $item->parseRow($row);

			  if(!$arr)
			  {
				$arr = array();
			  }

			  $arr[] = $item;
			}

			return $arr;
		 }
		 
		 public static function queryMoreSinceDate($bottomId,$categoryCode,$datetime,$tableName)
		 {
			$arr = NULL;

			$sql="SELECT * FROM ".$tableName." where categoryCode in (".$categoryCode.") ";
             if($bottomId>0)
             {
                 $sql.=" and id<".$bottomId;
             }
             $sql.=" and datetime>='".$datetime."' order by id desc";

			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result))
			{
			  $item=new Item();
			  $item->parseRow($row);

			  if(!$arr)
			  {
				$arr = array();
			  }

			  $arr[] = $item;
			}

			return $arr;
		 }
        
        /***************hot read ForV10***************/
        public static function queryNewestOrderByReadTimesForV10($topItemId,$pageSize,$tableName)
        {
			$arr = NULL;
            
			$sql="";
			if($topItemId>0)
			{
				$sql="SELECT * FROM ".$tableName." where readTimes>0 and id>".$topItemId." order by id desc limit 0,".$pageSize;
			}
			else
			{
				$sql="SELECT * FROM ".$tableName." where readTimes>0 order by id desc limit 0,".$pageSize;
			}
            
			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result))
			{
                $item=new Item();
                $item->parseRow($row);
                
                if(!$arr)
                {
                    $arr = array();
                }
                
                $arr[] = $item;
			}
            
			return $arr;
        }
        
        public static function queryMoreOrderByReadTimesForV10($lastItemId,$pageSize,$tableName)
        {
			$arr = NULL;
            
			$sql="";
			if($lastItemId>0)
			{
				$sql="SELECT * FROM ".$tableName." where readTimes>0 and id<".$lastItemId." order by id desc limit 0,".$pageSize;
			}
			else
			{
				$sql="SELECT * FROM ".$tableName." where readTimes>0 order by id desc limit 0,".$pageSize;
			}
            
			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result))
			{
                $item=new Item();
                $item->parseRow($row);
				$item->readTimes = $row['readTimes'];
                
                if(!$arr)
                {
                    $arr = array();
                }
                
                $arr[] = $item;
			}
            
			return $arr;
        }
        
        /***************hot read***************/
        public static function queryNewestOrderByReadTimes($topItemId,$pageSize,$tableName)
        {
			$arr = NULL;
            
			date_default_timezone_set('Asia/Shanghai');
			$theDate=date("Y-m-d",strtotime("-1 week"));
			
			$sql="";
			if($topItemId>0)
			{
                $readTimes=1;
                $sql="SELECT readTimes FROM ".$tableName." where id=".$topItemId;
                $result=mysql_query($sql);
                if($rows = mysql_fetch_array($result))
                {
                    $readTimes=$rows[0];
                }
                
                //readTimes>=".$readTimes." and
				$sql="SELECT * FROM ".$tableName." where datetime>='".$theDate."' and readTimes*10000000+id>".($readTimes*10000000+$topItemId)." order by readTimes desc,id desc limit 0,".$pageSize;
			}
			else
			{
				$sql="SELECT * FROM ".$tableName." where datetime>='".$theDate."' and readTimes>0 order by readTimes desc,id desc limit 0,".$pageSize;
			}
            
			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result))
			{
                $item=new Item();
                $item->parseRow($row);
				$item->readTimes = $row['readTimes'];
                
                if(!$arr)
                {
                    $arr = array();
                }
                
                $arr[] = $item;
			}
            
			return $arr;
        }

        public static function queryMoreOrderByReadTimes($lastItemId,$pageSize,$tableName)
        {
			$arr = NULL;
			
			date_default_timezone_set('Asia/Shanghai');
			$theDate=date("Y-m-d",strtotime("-1 week"));
            
			$sql="";
			if($lastItemId>0)
			{
                $readTimes=1;
                $sql="SELECT readTimes FROM ".$tableName." where id=".$lastItemId;
                $result=mysql_query($sql);
                if($rows = mysql_fetch_array($result))
                {
                    $readTimes=$rows[0];
                }
                
                //readTimes between 1 and ".$readTimes." and
				$sql="SELECT * FROM ".$tableName." where datetime>='".$theDate."' and readTimes*10000000+id<".($readTimes*10000000+$lastItemId)." order by readTimes desc,id desc limit 0,".$pageSize;
			}
			else
			{
				$sql="SELECT * FROM ".$tableName." where datetime>='".$theDate."' and readTimes>0 order by readTimes desc,id desc limit 0,".$pageSize;
			}

			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result))
			{
                $item=new Item();
                $item->parseRow($row);
				$item->readTimes = $row['readTimes'];
                
                if(!$arr)
                {
                    $arr = array();
                }
                
                $arr[] = $item;
			}
            
			return $arr;
        }
		 
		 public static function queryKeywords($keywords,$lastItemId,$pageSize,$tableName)
		 {
			$arr = NULL;
			
			//str_replace("world","John","Hello world!");
			$keywords=trim($keywords);
			$keywords=preg_replace("/\s+/"," ",$keywords);
			$keywords=preg_replace("/\s/","%",$keywords);

			$sql="";
			if($lastItemId>0)
			{
				$sql="SELECT * FROM ".$tableName." where (tags like '%".$keywords."%' or title like '%".$keywords."%' or briefDesc like '%".$keywords."%') and id<".$lastItemId." order by id desc limit 0,".$pageSize;
			}
			else
			{
				$sql="SELECT * FROM ".$tableName." where  tags like '%".$keywords."%' or title like '%".$keywords."%' or briefDesc like '%".$keywords."%'  order by id desc limit 0,".$pageSize;
			}
			
			//test
			//$sql="SELECT * FROM ".$tableName." where  tags like '%".$keywords."%' order by id desc limit 0,".$pageSize;
			
			//test
			//echo $sql;
			//echo "<br>";

			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result))
			{
			  $item=new Item();
			  $item->parseRow($row);

			  if(!$arr)
			  {
				$arr = array();
			  }

			  $arr[] = $item;
			}

			return $arr;
		 }
		 
		public static function parseItems($srcItemsHtmlContent,$regString,$captureNum,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic)
		{
			$newCount=0;

		 	$ret=preg_match_all($regString, $srcItemsHtmlContent, $temp);
			if($ret)
			{
				$itemHtmlContentArray=$temp[$captureNum];
				//array_reverse是因为最晚的在列表的上部分，后插入到数据库表。
				//$itemHtmlContentArray=array_reverse($itemHtmlContentArray);

				$newCount=Item::parseForeachItems($itemHtmlContentArray,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic);
			}
			else
			{
				date_default_timezone_set('Asia/Shanghai');
			 	$filePathName="../logs/captureList_error_".date("Y-m-d",time()).".log";
				log2File($filePathName,$itemClassName.": items section not found \n".$regString."\n".$srcItemsHtmlContent);
			}
			
			return $newCount;
		}
		
		public static function parseForeachItems($itemHtmlContentArray,$itemClassName,$categoryCode,$tableName,$categoryPriorityDic)
		{
			$newCount=0;
			
			$items=array();
			
			$con=dbConnect();

			foreach($itemHtmlContentArray as $itemHtmlContent)
			{
				//test
				//echo $itemHtmlContent;
				//echo "<br>";
				
				$item=new $itemClassName();
				$item->categoryCode=$categoryCode;
				$item->parse($itemHtmlContent);
				$valid=$item->preproccessValues();
				if($valid)
				{
					$exists=$item->checkItemExists($tableName);
					if($exists)
					{
						//test
						//$filePathName="../logs/test.log";
						//log2File($filePathName,$item->jsonEncode());
				
						break;
					}
				
					$items[]=$item;
				}
			}
			
			if(count($items)>0)
			{
				$items=array_reverse($items);
				
				foreach($items as $item)
				{
					$ret=false;
					$ret=$item->insertItemToDatabase($tableName,$categoryPriorityDic);
					if($ret)
					{
						$newCount++;
					}
	
					//test
					//echo $item->title." ".$item->href." ".$item->thumbnailUrl." ".$item->briefDesc." ".$item->datetime."<br><br><br><br><br><br>";
				}
				
				if($newCount==0)
				{
					date_default_timezone_set('Asia/Shanghai');
			 		$filePathName="../logs/captureList_error_".date("Y-m-d",time()).".log";
					log2File($filePathName,$itemClassName." parse Item failed");
				}
			}
			
			dbClose($con);
	
			return $newCount;
		}
	}
?>

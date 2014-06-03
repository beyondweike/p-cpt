<?php
	include_once("user.class.php");
	include_once("item.class.php");
    include_once("emoji.php");
	include_once("json.function.php");
	
	class Comment
	{
		var $id=0;
		var $content="";
		var $datetime="";
		
        var $serviceCode="";
        var $userId=0;
        var $username="";
		
		var $articleId=0;
		var $articleItem=NULL;
		
		var $deviceId="";
		var $imageUrl="";
		
		//$useUrlencodeContent used for emoji when json_encode
		function parseRow($row,$useUrlencodeContent=false)
		{ 
			$this->id = $row['id'];
			$this->datetime = $row['datetime'];
			$this->content =$row['content'];
			$this->userId = $row['userId'];
            $this->serviceCode = $row['serviceCode'];
            $this->articleId = $row['articleId'];
			$this->imageUrl = $row['imageUrl'];
            
			$this->content = emoji_softbank_to_unified($this->content);
			if($useUrlencodeContent)
			{
				$this->content=urlencode($this->content);
			}
		}
		
		function jsonEncode()
		{
			$paris=array();
			$paris[]=jsonEncodeKeyNumberPair("id",$this->id);
			$paris[]=jsonEncodeKeyStringPair("content",$this->content);
			$paris[]=jsonEncodeKeyStringPair("datetime",$this->datetime);
			$paris[]=jsonEncodeKeyStringPair("serviceCode",$this->serviceCode);
			$paris[]=jsonEncodeKeyNumberPair("userId",$this->userId);
			$paris[]=jsonEncodeKeyStringPair("username",$this->username);
			$paris[]=jsonEncodeKeyNumberPair("articleId",$this->articleId);
			$paris[]=jsonEncodeKeyStringPair("deviceId",$this->deviceId);
			$paris[]=jsonEncodeKeyStringPair("imageUrl",$this->imageUrl);
			if($this->articleItem)
			{
				$articleItemJsonObject=$this->articleItem->jsonEncode();
				$paris[]=jsonEncodeKeyObjectPair("articleItem",$articleItemJsonObject);
			}
			
			$json=jsonEncodePairs($paris);
			
			return $json;
		}

		 function insertToDatabase()
		 {
			//warning. make sure href,title field enough long.   vchar 300
			
			 if($this->datetime=="")
			 {
				 date_default_timezone_set('Asia/Shanghai');//'Asia/Shanghai'   亚洲/上海
				 $this->datetime=date("Y-m-d H:i:s",time());//only require date,"Y-m-d H:i:s"
			 }

			 $ret=false;
			 
             $sql="INSERT INTO comment_table (content, datetime, articleId ,serviceCode ,userId, deviceId, imageUrl) ".
				 " VALUES('".$this->content."', '".$this->datetime."', ".$this->articleId.", ".$this->serviceCode.", ".$this->userId.", '".$this->deviceId."', '".$this->imageUrl."')";
             $ret=mysql_query($sql);
			 
			 if($ret===false)
			 {
				date_default_timezone_set('Asia/Shanghai');//'Asia/Shanghai'   亚洲/上海
			 	$filePathName="../logs/sql_error_".date("Y-m-d",time()).".log";
				log2File($filePathName,$sql);
			
				die(mysql_error());
			 }
			 else
			 {
			 	$this->id=mysql_insert_id();
			 }
			 
			 return $ret;
		 }
			
		/*
		 public static function queryCommentCount($articleid,$serviceCode)
		 {
            $count=0;

			$sql="SELECT count(*) FROM comment_table where articleId=".$articleid." and serviceCode=".$serviceCode;
			
			$result=mysql_query($sql);
			if($rows = mysql_fetch_array($result))
			{
				$count=$rows[0];
			}

			return $count;
		 }
		*/
		
		 public static function queryCommentArray($articleId,$serviceCode,$useUrlencodeContent=false)
		 {
			$arr = NULL;

			$sql="SELECT * FROM comment_table where articleId=".$articleId." and serviceCode=".$serviceCode." order by id asc";

			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result))
			{
			  $item=new Comment();
			  $item->parseRow($row,$useUrlencodeContent);
			  
			  if($item->userId>0)
			  {
				  $user=User::queryUser($item->userId);
				  if($user)
				  {
				  	$item->username=$user->username;
				  }
			  }
			  if($item->username=="")
			  {
				  $item->username="[匿名]";
			  }
			  
			  if(!$arr)
			  {
                  $arr = array();
			  }

			  $arr[] = $item;
			}

			return $arr;
		 }
        
        public static function queryUserCommentArray($userId,$serviceCode,$useUrlencodeContent=false)
        {
			$arr = NULL;
            
			$sql="SELECT * FROM comment_table where userId=".$userId." and serviceCode=".$serviceCode." order by id desc";
            
			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result))
			{
                $item=new Comment();
                $item->parseRow($row,$useUrlencodeContent);
				
				$articleItem=Item::queryItemById($item->articleId,"list_table");
				$item->articleItem=$articleItem;
				
                if(!$arr)
                {
                    $arr = array();
                }
                
                $arr[] = $item;
			}
            
			return $arr;
        }
		
		 //hot comment
		 public static function queryNewestHotCommentItem($topItemId,$pageSize,$tableName)
		 {
			$arr = NULL;
			
			$sql="";
			
			if($topItemId>0)
			{
				$commentId=0;
                $sql="SELECT max(id) FROM comment_table where articleId=".$topItemId;
                $result=mysql_query($sql);
                if($rows = mysql_fetch_array($result))
                {
                    $commentId=$rows[0];
                }
				
				$sql="select A.*,max(B.id) as cId,count(A.id) as cCount from ".$tableName." as A join comment_table as B where A.id=B.articleId group by A.id having cId*10000000+A.id>".($commentId*10000000+$topItemId)." order by cId desc,A.id desc limit 0,".$pageSize;
			}
			else
			{
				$sql="select A.*,count(A.id) as cCount from ".$tableName." as A join comment_table as B where A.id=B.articleId group by A.id order by max(B.id) desc,A.id desc limit 0,".$pageSize;
			}

			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result))
			{
			  //echo $row['id'] . " " .$row['title'] . " " . $row['href']. " " . $row['datetime'];
			  //echo "<br />";

			  $item=new Item();
			  $item->parseRow($row);
			  $item->commentCount=$row["cCount"];

			  if(!$arr)
			  {
				$arr = array();
			  }

			  $arr[] = $item;
			}

			return $arr;
		 }
		 
		 //hot comment
		 public static function queryMoreHotCommentItem($lastItemId,$pageSize,$tableName)
		 {
			$arr = NULL;
			
			$sql="";
			
			if($lastItemId>0)
			{
				$commentId=0;
                $sql="SELECT min(id) FROM comment_table where articleId=".$lastItemId;
                $result=mysql_query($sql);
                if($rows = mysql_fetch_array($result))
                {
                    $commentId=$rows[0];
                }
				
				$sql="select A.*,max(B.id) as cId,count(A.id) as cCount from ".$tableName." as A join comment_table as B where A.id=B.articleId group by A.id having cId*10000000+A.id<".($commentId*10000000+$lastItemId)." order by cId desc,A.id desc limit 0,".$pageSize;
			}
			else
			{
				$sql="select A.*,count(A.id) as cCount from ".$tableName." as A join comment_table as B where A.id=B.articleId group by A.id order by max(B.id) desc,A.id desc limit 0,".$pageSize;
			}

			//echo "$lastItemId,$commentId";
			//echo $sql;

			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result))
			{
			  //echo $row['id'] . " " .$row['title'] . " " . $row['href']. " " . $row['datetime'];
			  //echo "<br />";

			  $item=new Item();
			  $item->parseRow($row);
			  $item->commentCount=$row["cCount"];
			  
			  if(!$arr)
			  {
				$arr = array();
			  }

			  $arr[] = $item;
			}

			return $arr;
		 }
	}
?>

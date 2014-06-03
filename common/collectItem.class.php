<?php
    include_once("item.class.php");
    
	class CollectItem
	{
		var $id=0;
		var $articleId=0;
		var $userId=0;
		var $collectTime="";
		var $serviceCode=0;
        
        var $listItem=NULL;
		
		//abstract protected function parse($htmlContent);

		function parseRow($row)
		{ 
			$this->id = $row['cId'];//联合查询，用别名cId
			$this->collectTime = $row['collectTime'];
			$this->articleId = $row['articleId'];
			$this->userId = $row['userId'];
			$this->serviceCode = $row['serviceCode'];
            
            $this->listItem=new Item();
            $this->listItem->parseRow($row);
            $this->listItem->id=$this->articleId;
		}

		 function insertItemToDatabase()
		 {
			 if($this->collectTime=="")
			 {
				 date_default_timezone_set('Asia/Shanghai');
				 $this->collectTime=date("Y-m-d H:i:s",time());
                 //$this->collectTime=time();
			 }

			 $sql="select id from collect_table where userId=".$this->userId." and articleId=".$this->articleId." and serviceCode=".$this->serviceCode;
			 $result=mysql_query($sql);
			 if($result===false)
			 {
				 die(mysql_error());
			 }
			 
			 if($rows = mysql_fetch_array($result))
			 {
                 $this->id=$rows[0];
			 }
		
			 $ret=true;
			 if($this->id<=0)
			 {
                 $sql="INSERT INTO collect_table(articleId, userId, collectTime, serviceCode) ".
				 " VALUES(".$this->articleId.", ".$this->userId.", '".$this->collectTime."', ".$this->serviceCode.")";
				 $ret=mysql_query($sql);
                 
                 if($ret)
                 {
                     $this->id=mysql_insert_id();
                 }
			 }

			 return $ret;
		 }

		 public static function deleteItems($articleIds,$userId,$serviceCode)
		 {
			$sql="delete FROM collect_table where userId=".$userId." and serviceCode=".$serviceCode." and articleId in (".$articleIds.")";
			$ret=mysql_query($sql);
			
			return $ret;
		 }
		 
		 public static function queryArticleCollected($articleId,$userId,$serviceCode)
		 {
			 $sql="select count(*) from collect_table where userId=".$userId." and articleId=".$articleId." and serviceCode=".$serviceCode;

			 $result=mysql_query($sql);
			 if($result===false)
			 {
				 die(mysql_error());
			 }
			 
			 $existsCount=0;
			 if($rows = mysql_fetch_array($result))
			 {
                 $existsCount=$rows[0];
			 }
		
			 return $existsCount>0;
		 }

        /////////////////////////收藏列表////////////////////////////////
		 public static function queryNewest($topItemId,$userId,$serviceCode,$pageSize,$listTableName)
		 {
			$arr = NULL;

			$sql="";
			if($topItemId>0)
			{
				$sql="SELECT *,A.id as cId FROM collect_table as A join ".$listTableName." as B where A.articleId=B.id and A.serviceCode=".$serviceCode." and A.id>".$topItemId." and A.userId=".$userId." order by A.id desc limit 0,".$pageSize;
			}
			else
			{
                $sql="SELECT *,A.id as cId FROM collect_table as A join ".$listTableName." as B where A.articleId=B.id and A.serviceCode=".$serviceCode." and A.userId=".$userId." order by A.id desc limit 0,".$pageSize;
			}

			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result))
			{
			  //echo $row['id'] . " " .$row['title'] . " " . $row['href']. " " . $row['datetime'];
			  //echo "<br />";

			  $item=new CollectItem();
			  $item->parseRow($row);

			  if(!$arr)
			  {
				$arr = array();
			  }

			  $arr[] = $item;
			}

			return $arr;
		 }

		 public static function queryMore($lastItemId,$userId,$serviceCode,$pageSize,$listTableName)
		 {
			$arr = NULL;

			$sql="";
			if($lastItemId>0)
			{
                $sql="SELECT *,A.id as cId FROM collect_table as A join ".$listTableName." as B where A.articleId=B.id and A.serviceCode=".$serviceCode." and A.id<".$lastItemId." and A.userId=".$userId." order by A.id desc limit 0,".$pageSize;
			}
			else
			{
                $sql="SELECT *,A.id as cId FROM collect_table as A join ".$listTableName." as B where A.articleId=B.id and A.serviceCode=".$serviceCode." and A.userId=".$userId." order by A.id desc limit 0,".$pageSize;
			}

			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result))
			{
			  //echo $row['id'] . " " .$row['title'] . " " . $row['href']. " " . $row['time'];
			  //echo "<br />";
	
			  $item=new CollectItem();
			  $item->parseRow($row);

			  if(!$arr)
			  {
				$arr = array();
			  }

			  $arr[] = $item;
			}

			return $arr;
		 } 
		 
		 
		 //////////////////hot collect////////////////////////////////
		 //返回的是Item类型
		 public static function queryNewestHotCollectItem($topItemId,$pageSize,$tableName)
		 {
			$arr = NULL;
			
			$sql="";
			
			//暂使用按id排序
			if($topItemId>0)
			{
				$sql="select A.* from ".$tableName." as A join collect_table as B where A.id=B.articleId and A.id>".$topItemId." group by A.id order by A.id desc limit 0,".$pageSize;
			}
			else
			{
				$sql="select A.* from ".$tableName." as A join collect_table as B where A.id=B.articleId group by A.id order by A.id desc limit 0,".$pageSize;//order by count(*)
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
		 
		 //hot comment,返回的是Item类型
		 public static function queryMoreHotCollectItem($lastItemId,$pageSize,$tableName)
		 {
			$arr = NULL;
			
			$sql="";
			
			if($lastItemId>0)
			{
				$sql="select A.* from ".$tableName." as A join collect_table as B where A.id=B.articleId and A.id<".$lastItemId." group by A.id order by A.id desc limit 0,".$pageSize;
			}
			else
			{
				$sql="select A.* from ".$tableName." as A join collect_table as B where A.id=B.articleId group by A.id order by A.id desc limit 0,".$pageSize;
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
	}
?>

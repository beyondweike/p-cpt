<?php
	class Push
	{
		var $id=0;
		var $articleId=0;
		var $pushCount=0;
        var $pushTurn=0;
        var $timestamp=0;
        var $createTime="";
		var $finishTime="";
		
		//abstract protected function parse($htmlContent);

		function parseRow($row)
		{ 
			$this->id = $row['id'];
			$this->articleId = $row['articleId'];
            $this->pushCount = $row['pushCount'];
			$this->pushTurn = $row['pushTurn'];
            $this->timestamp = $row['timestamp'];
            $this->createTime = $row['createTime'];
			$this->finishTime = $row['finishTime'];
		}

		public static function insertPushRecordToDatabase($articleId)
		{
			$id=0;
			
			 date_default_timezone_set('Asia/Shanghai');
			 $timestamp=time();
			 $createTime=date("Y-m-d H:i:s",$timestamp);
		
			 $sql="INSERT INTO push_table(articleId,pushCount,pushTurn,timestamp,createTime,finishTime) VALUES(".$articleId.",  0, 0, ".$timestamp.", '".$createTime."', '".$createTime."')";
			 $ret=mysql_query($sql);
			 if($ret)
			 {
				 $id=mysql_insert_id();
			 }
	
			 return $id;
		}
        
		public static function updatePushProgress($pushRecordId,$newPushCount,$pushTurn)
		{
			 date_default_timezone_set('Asia/Shanghai');
			 $finishTime=date("Y-m-d H:i:s",time());
		
			 $sql="update push_table set pushCount=pushCount+$newPushCount, pushTurn=".$pushTurn.", finishTime='".$finishTime."' where id=".$pushRecordId;
			 $ret=mysql_query($sql);
	
			 return $ret;
		}
		
		public static function checkPushable($articleId,$hours,&$message)
		{
			 $ret=false;
			 
			 //$sql="select timestamp from push_table order by id desc limit 0,1";
			 $sql="select max(timestamp) from push_table";
			 $result = mysql_query($sql);
			 if($row = mysql_fetch_array($result))
			 {
				 $lastTimestamp = $row[0];
				 date_default_timezone_set('Asia/Shanghai');
				 $secondsOffset=time()-$lastTimestamp;
				 $ret=$secondsOffset>=60*60*$hours;
				 
				 if(!$ret)
				 {
					 $message="未超过时间";
				 }
			 }
	
			 if($ret)
			 {
				 $sql="select count(*) from push_table where articleId=$articleId";
				 $result = mysql_query($sql);
				 if($row = mysql_fetch_array($result))
				 {
					 $ret = $row[0]<=0;
					 
					 if(!$ret)
					 {
						 $message="已推送过这篇文章";
					 }
				 }
			 }
	
			 return $ret;
		}

		 public static function queryAllPushRecord()
		 {
			$items = array();

			$sql="SELECT * FROM push_table order by id";
			
			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result))
			{
			  $item=new Push();
			  $item->parseRow($row);
			  
			  $items[]=$item;
			}

			return $items;
		 }
		 
		 public static function queryLastPushRecord()
		 {
			$sql="SELECT * FROM push_table order by id desc limit 0,1".
			
			$item=NULL;
			$result = mysql_query($sql);
			if($row = mysql_fetch_array($result))
			{
			  $item=new Push();
			  $item->parseRow($row);
			}

			return $item;
		 }
    }
?>

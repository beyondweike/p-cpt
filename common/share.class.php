<?php
	include_once("user.class.php");
	include_once("item.class.php");
    include_once("emoji.php");
	include_once("json.function.php");
	
	class Share
	{
		var $id=0;
		var $shareWay="";
		var $datetime="";
        var $serviceCode="";
        var $userId=0;
		var $articleId=0;
		var $deviceId="";

		 function insertToDatabase()
		 {
			 if($this->datetime=="")
			 {
				 date_default_timezone_set('Asia/Shanghai');
				 $this->datetime=date("Y-m-d H:i:s",time());
			 }

			 $ret=false;
			 
             $sql="INSERT INTO share_table(shareWay, datetime, articleId ,serviceCode ,userId, deviceId) ".
				 " VALUES('".$this->shareWay."', '".$this->datetime."', ".$this->articleId.", ".$this->serviceCode.", ".$this->userId.", '".$this->deviceId."')";
             $ret=mysql_query($sql);
			 
			 if($ret===false)
			 {
				date_default_timezone_set('Asia/Shanghai');
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
		 public static function queryShareCount($articleid,$serviceCode)
		 {
            $count=0;

			$sql="SELECT count(*) FROM share_table where articleId=".$articleid." and serviceCode=".$serviceCode;
			
			$result=mysql_query($sql);
			if($rows = mysql_fetch_array($result))
			{
				$count=$rows[0];
			}

			return $count;
		 }
		 */
	}
?>

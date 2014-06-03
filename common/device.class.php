<?php
    
	class Device
	{
		var $id=0;
		var $deviceId="";
		var $deviceToken="";
        var $lastVisitTime="";
		var $apnsEnable=0;
        var $serviceCode=0;
		var $pushState=0;
		
		//abstract protected function parse($htmlContent);

		function parseRow($row)
		{ 
			$this->id = $row['id'];
			$this->deviceId = $row['deviceId'];
			$this->deviceToken = $row['deviceToken'];
            $this->lastVisitTime = $row['lastVisitTime'];
            $this->apnsEnable = $row['apnsEnable'];
            $this->serviceCode = $row['serviceCode'];
		}

		 function insertToDatabase()
		 {
			 date_default_timezone_set('Asia/Shanghai');
             $this->lastVisitTime=date("Y-m-d H:i:s",time());

			 $ret=false;
             
             $sql="select id,deviceToken from device_table where deviceId='".$this->deviceId."' and serviceCode=".$this->serviceCode;
             $result = mysql_query($sql);
             if($row = mysql_fetch_array($result))
             {
                 $this->id=$row['id'];
                 if(!$this->deviceToken)
                 {
                     $this->deviceToken=$row['deviceToken'];
                 }
             }
             
             if($this->id>0)
			 {
				 $sql="update device_table set ".
                 "deviceToken='".$this->deviceToken."',".
                 "lastVisitTime='".$this->lastVisitTime."',".
                 "apnsEnable=".$this->apnsEnable.",".
                 "serviceCode=".$this->serviceCode.
                 " where id=".$this->id;
                 
				 $ret=mysql_query($sql);
			 }
			 else
             {
                 $sql="INSERT INTO device_table(deviceId,deviceToken,lastVisitTime,apnsEnable,serviceCode,pushState) ".
                     " VALUES('".$this->deviceId."', '".$this->deviceToken."', '".$this->lastVisitTime."', ".$this->apnsEnable.",".$this->serviceCode.",0)";
                 $ret=mysql_query($sql);
                 $this->id=mysql_insert_id();
             }
			 
			 //test
			 //echo $sql;
			 
			 return $ret;
		 }
		 
		 public static function queryFromRecordId($deviceRecordId,$pageSize,$serviceCode)
		 {
			 $arr = array();
			 
			 $sql="select id,deviceToken from device_table where id>=".$deviceRecordId." and serviceCode=".$serviceCode.
			      " and pushState=0 order by id asc limit 0,".$pageSize;
             $result = mysql_query($sql);
             while($row = mysql_fetch_array($result))
             {
                 $device=new Device();
				 
				 $device->id = $row['id'];
				 $device->deviceToken = $row['deviceToken'];
			
				 $arr[]=$device;
             }
			 
			 return $arr;
		 }
		 
		 public static function updatePushState($pushState,$ids)
		 {
			 $ret=false;
			 if(strlen($ids)>0)
			 {
			 	$sql="update device_table set pushState=".$pushState." where id in(".$ids.")";
             	$ret = mysql_query($sql);
			 }
			 
			 return $ret;
		 }
		 
		 public static function updateAllPushState($pushState)
		 {
			 $sql="update device_table set pushState=".$pushState;
             $ret = mysql_query($sql);

			 return $ret;
		 }
	}
?>

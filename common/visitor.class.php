<?php
	class Visitor
	{
		var $id=0;
		var $visitTimes=0;
        var $createTime="";
		var $lastVisitTime="";
		var $deviceId="";
        var $deviceInfo="";
        var $version="";
        var $serviceCode=0;
 
		private function parseRow($row)
		{ 
			$this->id = $row['id'];
			$this->createTime = $row['createTime'];
            $this->lastVisitTime = $row['lastVisitTime'];
			$this->visitTimes = $row['visitTimes'];
            $this->deviceId = $row['deviceId'];
            $this->deviceInfo = $row['deviceInfo'];
            $this->version = $row['version'];
            $this->serviceCode = $row['serviceCode'];
		}

		 private function insertToDatabase()
		 {
			date_default_timezone_set('Asia/Shanghai');
			$this->lastVisitTime=date("Y-m-d H:i:s",time());
			$this->createTime=$this->lastVisitTime;
				 
			$this->visitTimes+=1;
				 
            $sql="INSERT INTO visitor_table(visitTimes,createTime,lastVisitTime,deviceId,deviceInfo,version,serviceCode) ".
				 " VALUES(".$this->visitTimes.",'".$this->createTime."','".$this->lastVisitTime."','".$this->deviceId."','".$this->deviceInfo."','".$this->version."',".$this->serviceCode.")";
            $ret=mysql_query($sql);
             
            $this->id=mysql_insert_id();
			 
			return $ret;
		 }
		 
		public static function updateVisitTimes($headers)
		{
			$productCode=$headers["productcode"];
			$version=$headers["version"];
			$deviceId=$headers["deviceid"];
			$deviceInfo=$headers["deviceos"].","
						.$headers["deviceosversion"].","
						.$headers["devicemodel"].","
						.$headers["devicename"];
						
			$visitor=new Visitor();
			$visitor->deviceId=$deviceId;
			$visitor->deviceInfo=$deviceInfo;
			$visitor->version=$version;
			$visitor->serviceCode=$productCode;
			
			$visitTimes=$visitor->updateAddOneVisitTimes();
			
			return $visitTimes;
		}
        
        private function updateAddOneVisitTimes()
        {
			$sql="select * from visitor_table where deviceId='".$this->deviceId."' and serviceCode=".$this->serviceCode;
            $ret=mysql_query($sql);
			if($row = mysql_fetch_array($ret))
			{
			  	$this->id = $row['id'];
                $this->visitTimes = $row['visitTimes']+1;
				
				date_default_timezone_set('Asia/Shanghai');
				$this->lastVisitTime=date("Y-m-d H:i:s",time());
	
				$sql="update visitor_table set visitTimes=visitTimes+1,lastVisitTime='".$this->lastVisitTime."',deviceInfo='".$this->deviceInfo."',version='".$this->version."' where id=".$this->id;
				
				$ret=mysql_query($sql);
			}
			else
			{
				$this->insertToDatabase();
			}
            
            return $this->visitTimes;
        }
    }
?>

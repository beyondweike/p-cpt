<?php
    
	class Feedback
	{
		var $id=0;
		var $deviceId="";
		var $content="";
        var $feedbackTime="";
		var $userId=0;
        var $serviceCode=0;

		function parseRow($row)
		{ 
			$this->id = $row['id'];
			$this->deviceId = $row['deviceId'];
			$this->content = $row['content'];
            $this->feedbackTime = $row['feedbackTime'];
            $this->userId = $row['userId'];
            $this->serviceCode = $row['serviceCode'];
		}

		 function insertToDatabase()
		 {
			 date_default_timezone_set('Asia/Shanghai');//'Asia/Shanghai'   亚洲/上海
             $this->feedbackTime=date("Y-m-d H:i:s",time());//only require date,"Y-m-d H:i:s"

             $sql="INSERT INTO feedback_table(deviceId,content, feedbackTime,userId,serviceCode) ".
                     " VALUES('".$this->deviceId."', '".$this->content."', '".$this->feedbackTime."', ".$this->userId.",".$this->serviceCode.")";
                 
             $ret=mysql_query($sql);
             
             $this->id=mysql_insert_id();

			 return $ret;
		 }
	}
?>

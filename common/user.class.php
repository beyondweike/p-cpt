<?php
	class User
	{
		var $id=0;
		var $username="";
		var $password="";
        var $email="";
        var $registerTime="";
        var $lastUpdateTime="";
		var $deviceId="";
        var $authority=0;
		
		//abstract protected function parse($htmlContent);

		function parseRow($row)
		{ 
			$this->id = $row['id'];
			$this->registerTime = $row['registerTime'];
            $this->lastUpdateTime = $row['lastUpdateTime'];
			$this->password = $row['password'];
            $this->email = $row['email'];
            $this->username = $row['username'];
            $this->deviceId = $row['deviceId'];
			$this->authority = $row['authority'];
		}

		 function insertToDatabase()
		 {
			//warning. make sure href,title field enough long.   vchar 300
			
			 if($this->registerTime=="")
			 {
				 date_default_timezone_set('Asia/Shanghai');
				 $this->registerTime=date("Y-m-d H:i:s",time());
                 $this->lastUpdateTime=$this->registerTime;
			 }

             $sql="INSERT INTO user_table (username, password, email, registerTime, lastUpdateTime, deviceId) ".
				 " VALUES('".$this->username."', '".$this->password."', '".$this->email."', '".$this->registerTime."', '".$this->lastUpdateTime."', '".$this->deviceId."')";
             $ret=mysql_query($sql);
			 
			 if($ret===false)
			 {
				date_default_timezone_set('Asia/Shanghai');
				$filePathName="../logs/sql_error_".date("Y-m-d",time()).".log";
				log2File($filePathName,$sql."\n".mysql_error());
			 }
             else
			 {
             	$this->id=mysql_insert_id();
			 }
			 
			 return $ret;
		 }
        
        function updateToDatabase()
        {
            date_default_timezone_set('Asia/Shanghai');
            $this->lastUpdateTime=date("Y-m-d H:i:s",time());

            $sql="update user_table set password='".$this->password."',".
                        "email='".$this->email."',".
                        "lastUpdateTime='".$this->lastUpdateTime."' ".
                        " where id=".$this->id;
            
            $ret=mysql_query($sql);
            
            return $ret;
        }

		 public static function queryUser($userId)
		 {
			$item = NULL;

			$sql="SELECT * FROM user_table where id=".$userId;

			$result = mysql_query($sql);
			if($row = mysql_fetch_array($result))
			{
			  $item=new User();
			  $item->parseRow($row);
			}

			return $item;
		 }
		 
		  public static function queryUserByUserName($username)
		 {
			$item = NULL;

			$sql="SELECT * FROM user_table where username='".$username."'";

			$result = mysql_query($sql);
			if($row = mysql_fetch_array($result))
			{
			  $item=new User();
			  $item->parseRow($row);
			}

			return $item;
		 }
		 
		 public static function queryUserByEmail($email)
		 {
			$item = NULL;

			$sql="SELECT * FROM user_table where email='".$email."'";
			
			$result = mysql_query($sql);
			if($row = mysql_fetch_array($result))
			{
			  $item=new User();
			  $item->parseRow($row);
			}

			return $item;
		 }
		 
		  public static function queryUserCountByDeviceId($deviceId)
		 {
			$count=0;

			$sql="SELECT count(*) FROM user_table where deviceId='".$deviceId."'";

			$result = mysql_query($sql);
			if($row = mysql_fetch_array($result))
			{
			  	$count=$row[0];
			}

			return $count;
		 }
		 
		 public static function queryAllUser()
		 {
			$items = array();

			$sql="SELECT * FROM user_table order by registerTime";
			
			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result))
			{
			  $item=new User();
			  $item->parseRow($row);
			  
			  $items[]=$item;
			}

			return $items;
		 }
    }
?>

<?php
	include_once("mysqlConnectUtil.php"); 

	class Database
	{
		var $con=NULL;
		
		var $dbhost="";
		var $dbname="";
		var $username="";
		var $password="";
		
		function Database($dbhost,$dbname,$username,$password)
		{
			$this->dbhost=$dbhost;
			$this->dbname=$dbname;
			$this->username=$username;
			$this->password=$password;
		}
		
		function connect()
		{
			$this->con=mysqlConnect($this->dbhost,$this->dbname,$this->username,$this->password);
		}
		
		function close()
		{
			mysqlClose($this->con);
		}
	}
?>

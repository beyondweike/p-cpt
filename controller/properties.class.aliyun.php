<?php
	include_once("../common/string.function.php");
	
	class Properties
	{
		var $dbhost="";
		var $dbname="";
		var $username="";
		var $password="";
		
		//var $captureListUri="";
		
		var $lastEncrypt="";
		var $encrypt="";
		
		function loadProperties()
		{ 
			//$config = "server_config.property";
			//$ini_array = parse_ini_file($config);
			
			//consts
			$ini_array["local_username"]="root";
			$ini_array["local_password"]="";
			$ini_array["local_dbname"]="wwk_capture";
			$ini_array["local_dbhost"]="localhost";
			$ini_array["server_username"]="root";
			$ini_array["server_password"]="0855Sg0EVq7LNEq1DX4Uuup2GbeVvV1f3OxMzyjRHR0Us4Pns2yZ";
			$ini_array["server_dbname"]="wwk_capture";
			$ini_array["server_dbhost"]="localhost";
			$ini_array["lastEncrypt"]="e10adc3949ba59abbe56e057f20f883e";
			$ini_array["encrypt"]=    "e10adc3949ba59abbe56e057f20f883e";

			//db
			$httphost=$_SERVER ["HTTP_HOST"];
			if(strpos($httphost,"192")===FALSE)
			{
				$this->dbhost=$ini_array["server_dbhost"];
				$this->dbname=$ini_array["server_dbname"];
				$this->username=$ini_array["server_username"];
				$passwordCoded=$ini_array["server_password"];
				
				$this->password=decrypt($passwordCoded,"wangweike");
			}
			else
			{
				$this->dbhost=$ini_array["local_dbhost"];
				$this->dbname=$ini_array["local_dbname"];
				$this->username=$ini_array["local_username"];
				$this->password=$ini_array["local_password"];
			}
			
			//
			//$this->captureListUri=$ini_array["captureListUri"];
			$this->lastEncrypt=$ini_array["lastEncrypt"];
			$this->encrypt=$ini_array["encrypt"];
		}
		
		static function getProperties()
		{
			$properties=NULL;
			if(!$properties)
			{
				$properties=new Properties;
				$properties->loadProperties();
			}
	   
			return $properties;
		}
        
        static function getRelativeCategoriesPath()
		{
			return "config/resource/categories.json";
		}
	}
?>

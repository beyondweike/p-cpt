<?php
    include_once("properties.class.php");
	include_once("../common/mysqlConnectUtil.php"); 

	function dbConnect()
	{
		$properties=Properties::getProperties();

		$con=mysqlConnect($properties->dbhost,$properties->dbname,$properties->username,$properties->password);
		mysql_query("SET NAMES 'UTF8'");
		
		return $con;
	}
    
	function dbClose($con)
	{
		mysqlClose($con);
	}
?>

<?php
	include_once("../it/captureItList.class.php");
	include_once("properties.class.php");
	
	class Service
	{
		var $code=-1;
		var $dir="";
		var $tableName="";
		var $captureListObject=NULL;

		static function getService($productCode)
		{
			$service=new Service;
			$service->code=$productCode;

			switch($productCode)
			{
				case 0:
					{
						$service->dir="it";
						$service->tableName="list_table";
						$service->captureListObject=new CaptureItList;
					}
					break;
				case 1:
					{
						$service->dir="digimon";
						$service->tableName="digimon_list_table";
						$service->captureListObject=new CaptureItList;
					}
					break;
			}

			$service->captureListObject->serviceCode=$productCode;
			$service->captureListObject->tableName=$service->tableName;
			
			return $service;
		}
		
		static function getTableName($productCode)
		{
			$tableName="";

			switch($productCode)
			{
				case 0:
					{
						$tableName="list_table";
					}
					break;
				case 1:
					{
						$tableName="digimon_list_table";
					}
					break;
			}
			
			return $tableName;
		}
		
		static function getDir($productCode)
		{
			$dir="";

			switch($productCode)
			{
				case 0:
					{
						$dir="it";
					}
					break;
				case 1:
					{
						$dir="digimon";
					}
					break;
			}
			
			return $dir;
		}
	}
?>
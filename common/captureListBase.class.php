<?php
    include_once("captureRecord.function.php");
	include_once("Category.class.php");
	include_once("../controller/db.function.php");
	
	class CaptureListBase
	{
		var $serviceCode=-1;
		var $tableName="";
		
		/*
		function checkToCaptureListPages($categoryCode,$categoriesPath)
		{
			$lastCptStep=0;
			$capture=isTimeToCapture($this->serviceCode,$categoryCode,$lastCptStep);
	
			//test
			//$capture=true;
			
			if($capture)
			{
				$categoryPriorityDic=array();
				
				$rootCategory=new Category();
				$rootCategory->parsePath($categoriesPath);
				$rootCategory->getCategoryPriorityDic($categoryPriorityDic);
				
				$cptStepCount=1;
				$cptPeriodHour=6;
				$category=$rootCategory->getSubCategory($categoryCode);
				if($category)
				{
					$cptStepCount=$category->cptStepCount;
					$cptPeriodHour=$category->cptPeriodHour;
				}
	
				if($cptPeriodHour<=0)
				{
					$cptPeriodHour=6;
				}
				$cptStep=1;
				if($cptStepCount>1)
				{
					$cptPeriodHour=$cptPeriodHour/$cptStepCount;
					$cptStep = $lastCptStep<$cptStepCount? $lastCptStep+1 : 1;
				}
				
				//update immediately
				updateCaptureTime($this->serviceCode,$categoryCode,$cptPeriodHour,$cptStep);
				
				$capturedCount=$this->captureListPages($categoryCode,$this->tableName,$categoryPriorityDic,$cptStep);
				if($capturedCount==0)
				{
					$cptPeriodHour/=2;
					updateCaptureTime($this->serviceCode,$categoryCode,$cptPeriodHour,$cptStep);
				}
			}
			else
			{
				//test
				//updateCaptureTime($this->serviceCode,$categoryCode,5,6);
			}
		}
		*/
		
		function recordAndCaptureListPages($categoryCode,$categoriesPath,$lastCptStep)
		{
			$cptStepCount=1;
			$cptPeriodHour=6;
			$categoryPriorityDic=array();
			
			$rootCategory=new Category();
			$rootCategory->parsePath($categoriesPath);
			$rootCategory->getCategoryPriorityDic($categoryPriorityDic);
			$category=$rootCategory->getSubCategory($categoryCode);
			if($category)
			{
				$cptStepCount=$category->cptStepCount;
				$cptPeriodHour=$category->cptPeriodHour;
			}
	
			if($cptPeriodHour<=0)
			{
				$cptPeriodHour=12;
			}
			$cptStep=1;
			if($cptStepCount>1)
			{
				$cptPeriodHour=$cptPeriodHour/$cptStepCount;
				$cptStep = $lastCptStep<$cptStepCount? $lastCptStep+1 : 1;
			}
			
			//update immediately
			$con=dbConnect();
			updateCaptureTime($this->serviceCode,$categoryCode,$cptPeriodHour,$cptStep);
			dbClose($con);
			
			$capturedCount=$this->captureListPages($categoryCode,$this->tableName,$categoryPriorityDic,$cptStep);
			if($capturedCount==0)
			{
				//$cptPeriodHour/=2;
				//updateCaptureTime($this->serviceCode,$categoryCode,$cptPeriodHour,$cptStep);
			}
		}
		
		function captureListPages($categoryCode,$tableName,$categoryPriorityDic,$cptStep)
		{	
			$capturedCount=0;
			
			return $capturedCount;
		}
	}
	
?>

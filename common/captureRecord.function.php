<?php

	function isTimeToCapture($serviceCode,$categoryCode,&$lastStep)
	{
		$ret=false;

		$captureTimeSecondsOffset=-1;
		$period=-1;//use perios field in assist_table ,or use cptPeriodHour in categories.json 。

		$sql="SELECT value,period,step FROM assist_table where serviceCode=".$serviceCode." and categoryCode=".$categoryCode;
		$result = mysql_query($sql);
		if($row = mysql_fetch_array($result))
		{
		   $lastTimestamp=$row['value'];//value field is timestamp,seconds
		   $captureTimeSecondsOffset=time()-$lastTimestamp;
		   $period=$row['period'];//period field is timedelay,seconds
		   $lastStep=$row['step'];//last cptStepNum
		}
		
		if($captureTimeSecondsOffset<0 || $captureTimeSecondsOffset>=$period)
		{
		   $ret=true;
		}
			
		return $ret;
	}

	function updateCaptureTime($serviceCode,$categoryCode,$nextPeriodHours,$cptStep)
	{		
		$sql="SELECT * FROM assist_table where serviceCode=".$serviceCode." and categoryCode=".$categoryCode;
		$result = mysql_query($sql);
		
		$period=$nextPeriodHours*60*60;//seconds,6 hours
		
		date_default_timezone_set('Asia/Shanghai');
		$datetime=date("Y-m-d H:i:s",time());
		
		if($row = mysql_fetch_array($result))
		{
		   	$sql="update assist_table set datetime='".$datetime."', value=".time().", period=".$period.", step=".$cptStep." where serviceCode=".$serviceCode." and categoryCode=".$categoryCode;
		}
		else
		{
			$sql="insert into assist_table(serviceCode,categoryCode,datetime,value,period,step) values(".$serviceCode.",".$categoryCode.",'".$datetime."',".time().",".$period.",".$cptStep.")";
		}
		
		mysql_query($sql);
	}
	
	//for develope test
	function resetAllCaptureTime()
	{
		$sql="delete from assist_table";
		mysql_query($sql);
	}
?>

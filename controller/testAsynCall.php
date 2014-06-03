<?php
    include_once("../common/captureRecord.function.php");
	include_once("db.function.php");
	
	$con=dbConnect();
	checkToCallAsynCaptureList(0,11);
	dbClose($con);
	
	function checkToCallAsynCaptureList($serviceCode,$categoryCode)
    {
		$lastCptStep=0;
		$capture=isTimeToCapture($serviceCode,$categoryCode,$lastCptStep);
		
		if($capture)
		{
        	$uri="/reading/controller/captureListForAsyn.php?"."serviceCode=$serviceCode&categoryCode=$categoryCode&lastCptStep=$lastCptStep";
        	asynGetRequestUri($uri);
		}
    }
	
	function asynGetRequestUri($uri)
    {
        $host=$_SERVER ['HTTP_HOST'];
		$port=80;
		$arr = explode(":",$host);
		if(count($arr)>1)
		{
			$port=$arr[1];
		}
	
        $fp=fsockopen($host,$port,$errno,$errstr);
        if($fp)
        {
            $out = "GET ".$uri." HTTP/1.1\r\n";
            $out.= "Host: ".$host."\r\n";
            $out.= "Connection: Close\r\n\r\n";
			
			echo $out;
            
            fwrite($fp, $out);
            
             //测试查看执行结果
			 
             while (!feof($fp))
             {
                 echo fgets($fp, 128);
             }
			 

            fclose($fp);
        }
        else
        {
            //echo "$errstr ($errno)<br />\n";
        }
    }
?>

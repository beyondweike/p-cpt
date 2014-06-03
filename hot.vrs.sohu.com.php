<?php
    
	if(isset($_GET['vid']))
	{
		$vid=$_GET['vid'];
		
		$url="http://hot.vrs.sohu.com/vrs_flash.action?vid=$vid";
		$results=@file_get_contents($url);
		if($results=="")
		{
			$results="空";
		}
		echo $results;
	
		/*
		$host="hot.vrs.sohu.com";
		$uri="/vrs_flash.action?vid=$vid";
		
		$fp=fsockopen($host,80,$errno,$errstr);
		if($fp)
		{
			$out = "GET ".$uri." HTTP/1.1\r\n";
			$out.= "Host: ".$host."\r\n";
			$out.= "Connection: Close\r\n\r\n";
			
			fwrite($fp, $out);
			
			//执行结果
			while (!feof($fp))
			{
				echo fgets($fp, 128);
			}
			
			fclose($fp);
		}
		else
		{
			echo "出错了 $errstr ($errno)";
		}
		*/
	}
?>
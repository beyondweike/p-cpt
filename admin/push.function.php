<?php

    function pushArticle($item,$devices,$isDeveleop=false)
    {
        $payload=getJsonMsg($item);
		
		return pushContentToDevices($payload,$devices,$isDeveleop);
    }
	
	function pushContentToDevices($payload,$devices,$isDeveleop=false)
    {
        // Put your private key's passphrase here:
        $passphrase = '123456';
		
		$pemname="reading_it_aps_production_result.pem";
		$sslurl="ssl://gateway.push.apple.com:2195";
		if($isDeveleop)
		{
			$pemname="reading_it_aps_development_result.pem";
			$sslurl="ssl://gateway.sandbox.push.apple.com:2195";
		}
        
        $ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $pemname);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        
        // Open a connection to the APNS server
        $fp = stream_socket_client($sslurl, $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        
        if (!$fp)
        {
            //exit("Failed to connect: $err $errstr" . PHP_EOL);
			return -1;
        }
        
        $successCount=0;
        
        foreach($devices as $device)
        {
            // Build the binary notification
            $msg=buildPushMsg($payload,$device->deviceToken);
            // Send it to the server
            $ret = fwrite($fp, $msg, strlen($msg));
            if ($ret)
            {
				//date_default_timezone_set('Asia/Shanghai');
				//$filePathName="../logs/push_log_".date("Y-m-d",time()).".log";
				//log2File($filePathName,$ret." :: ".strlen($msg)." :: ".$payload." :: ".$msg);
                $successCount++;
				$device->pushState=1;
            }
        }
        
        // Close the connection to the server
        fclose($fp);
		
		return $successCount;
    }
	
	function isItemPushable($item,&$msgLength)
    {
		$ret=false;
		
		if($item && $item->id>0 && strlen($item->title)>0)
		{
			$deviceToken="104718d1fd9961c7f9c84c734a155ec4d66f0223f5c16af53a9bed6aaf60fd22";
	
			$msg = getPushMsg($item,$deviceToken);
			
			//date_default_timezone_set('Asia/Shanghai');
			 	//$filePathName="../logs/push_log_".date("Y-m-d",time()).".log";
				//log2File($filePathName,$ret." :: ".strlen($msg)." :: ".$payload." :: ".$msg);
			
			$msgLength=strlen($msg);
			$ret=strlen($msg)<=256;
		}
		
		return $ret;
    }
	
	function buildPushMsg($payload,$deviceToken)
    {
		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		
		return $msg;
    }
	
	function getPushMsg($item,$deviceToken)
    {
		$payload=getJsonMsg($item);
		$msg=buildPushMsg($payload,$deviceToken);
		
		return $msg;
    }
	
	function getJsonMsg($item)
    {
		// Create the payload body
		$body['aps'] = array('alert' => $item->title,'badge' => 1,'sound' => 'default');
		$body['type'] = 0 ;
		$body['article'] = array('id' => $item->id,'catecode' => $item->categoryCode,'time' => $item->datetime);
			
		 // Encode the payload as JSON
		$payload = json_encode($body,JSON_UNESCAPED_UNICODE);
		
		return $payload;
    }
	
?>

<?php
    include_once("file.function.php");
    
	function curlexec($url,$cookie=NULL,&$http_response_header=NULL)
    {
        $userAgent="Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1";
        return curlexecPrivate($url,$userAgent,$cookie,$http_response_header);
    }
	
	function curlexecMobile($url,$cookie=NULL)
    {
		$userAgent="Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Mobile/11A4449b";
        return curlexecPrivate($url,$userAgent,$cookie);
	}
	
	function curlexecPrivate($url,$userAgent,$cookie=NULL,&$http_response_header=NULL)
    {
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
		//设置是否要输出header
        curl_setopt($curl,CURLOPT_HEADER,1);
		//设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,30);//seconds
		curl_setopt($curl,CURLOPT_TIMEOUT,30);//seconds
		curl_setopt($curl,CURLOPT_USERAGENT,$userAgent);
      //curl_setopt($curl, CURLOPT_REFERER, 'http://blog.jobbole.com/');
        if($cookie)
        {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        
        $data = curl_exec($curl);
        //list() 函数用数组中的元素为一组变量赋值。
        $arr=explode("\r\n\r\n", $data, 2);
		
		list($header, $data) = $arr;
        
        //301,302
        $location=NULL;
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($http_code == 301 || $http_code == 302)
        {
            $location=preg_match("/Location:\s+(.*?)\r\n/", $header, $temp)?$temp[1]:"";
            $baseurl=preg_match("/(http:\/\/[^\/]*\/?)/", $url, $temp)?$temp[1]:"";
            $location=format_url($location, $baseurl);
            
            $hasCookie=preg_match("/Set-Cookie:\s+(.*?)\r\n/", $header,$temp);
            if($hasCookie)
            {
                $cookie=$temp[1];
            }
        }
        
        curl_close($curl);
        
        if($location && strlen($location)>0)
        {
            $data=curlexecPrivate($location,$userAgent,$cookie);
        }
		
		//for function funCaptureArticle
		$http_response_header=$header;
        
        return $data;
    }
	
	/*
	function curlexecMobile($url)
    {
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
		//设置是否要输出header
        curl_setopt($curl,CURLOPT_HEADER,0);
		//设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl,CURLOPT_TIMEOUT,60);//seconds
		curl_setopt($curl,CURLOPT_USERAGENT,"Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Mobile/11A4449b");
        //模拟苹果设备
        
        $data = curl_exec($curl);
        curl_close($curl);
        
        return $data;
    }
	*/

	function postCurlExec($url,$params,$timeoutSeconds=30,&$headersStr=NULL)
    {
		$str="";
		foreach ($params as $k=>$v)
		{
			$str.= "$k=".urlencode($v)."&";
		}
		$str=substr($str,0,-1);
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);//0 print result to screen, 1 save result into string
		curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,$timeoutSeconds);//seconds
		curl_setopt($curl,CURLOPT_TIMEOUT,$timeoutSeconds);//seconds
		curl_setopt($curl, CURLOPT_URL,$url);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $str);
		curl_setopt($curl, CURLOPT_REFERER, $url);
		
		//for https
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        
        $data = curl_exec($curl);
        
        //explode把第二个参数按第一个参数分组
        $arr=explode("\r\n\r\n", $data, 2);
		
		//list() 函数把右边的数组中的内容散列到参数中的变量中。
		if(count($arr)>=2)
		{
			list($headersStr, $data) = $arr;
		}
		else
		{
			$headersStr = $arr[0];
		}
        curl_close($curl);
        
        return $data;
	}
	
	function getAllHeadersLowerCase()
	{
        $headers=NULL;
        
		if (!function_exists('getallheaders'))
		{   
		   foreach ($_SERVER as $name => $value)   
		   {  
			   if (substr($name, 0, 5) == 'HTTP_')   
			   {  
                   $headers[substr($name, 5)] = $value;
			   }  
			   
			   //test
			   //$headers[$name] = $value;  
		   }  
		}
		else
		{
			$headers = getallheaders();
		}
		
		//test
		//print_r($headers);
        
        $headers=array_change_key_case($headers,CASE_LOWER);
        
        return $headers;
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
            
            fwrite($fp, $out);
            
			/*
             //测试查看执行结果,异步执行需要关闭下段代码.但有的服务器上不执行下段异步却无效！
             while (!feof($fp))
             {
                 //echo fgets($fp, 128);
				 //or
				 fgets($fp, 128);
             }
			 */
			 
			 sleep(1);//seconds

            fclose($fp);
        }
        else
        {
            //echo "$errstr ($errno)<br />\n";
        }
    }
	
	function fileGetContents($url,&$httpResponseHeader=NULL)
    {
		if(!$url || $url=="")
		{
			return NULL;
		}

		//连接超时时间和数据传输的最大允许时间均为设置的timeout
		//'header'=>"Cookie: $cookie\r\n"
		$timeout = 30; 
		$header='User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1\r\n\r\n';
		$options = array('http'=>array('method'=>"GET",
										'timeout'=>$timeout,
										'header' =>$header)); 
		$context = stream_context_create($options); 
		$contents = @file_get_contents($url, 0, $context);
		
		if(isset($http_response_header)) 
		{
			$httpResponseHeader=$http_response_header;
		}
		else
		{
			$httpResponseHeader=get_headers($url);
		}
		
		return $contents;
	}

?>

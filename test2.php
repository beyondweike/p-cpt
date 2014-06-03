<?php
    //include_once("common/Category.class.php");
    include_once("common/request.function.php");
    include_once("common/string.function.php");
    
    //$headers=getAllHeadersLowerCase();
	
	//set_time_limit(0);
	
	/*
	$host="my.tv.sohu.com";
	$uri="/us/110160765/56947510.shtml";
	
	$host="www.36kr.com";
	$uri="/?page=1";
	
	
	$fp=fsockopen($host,80,$errno,$errstr,5);
	if($fp)
	{
		$out = "GET ".$uri." HTTP/1.1\r\n";
		$out.= "Host: ".$host."\r\n";
		$out.= "Connection: keep-alive\r\n";
		$out.= "Accept-Encoding: gzip, deflate\r\n";
		$out.= "Accept: text/html,application/xhtml+xml,application/xml;\r\n";
		$out.= "Accept-Language: zh-cn\r\n";
		$out.= "Accept-Charset: GBK,utf-8;q=0.7,*;q=0.3\r\n";
		//$out.= "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Mobile/11A4449b\r\n\r\n";
		
		$out.= "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Mobile/11A4449b";
		
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
		echo "$errstr ($errno)<br />\n";
	}
	*/
		
    
    /*
    $path="it/config/resource/categories.json";
    $category=new Category();
    $category->parsePath($path);
    $arr=array();
    $category->getCategoryPriorityDic($arr);
    foreach($arr as $key=>$value)
    {
        echo $key."=>".$value."<br>";
    }
     */
    
    
    //test
    $url="http://share.vrs.sohu.com/my/v.swf&id=59387849";
    $url="http://my.tv.sohu.com/play/videonew.do?vid=59387849&af=1&out=0&g=8&referer=http://tv.sohu.com/upload/swf/20130815/PlayerShell.swf?id=59387849&shareBtn=1&likeBtn=1&topBarFull=1&topBarNor=1&sogouBtn=0";
    $url="http://my.tv.sohu.com/play/videonew.do?vid=59387849&af=1&out=0&g=8&referer=http://share.vrs.sohu.com/my/v.swf&amp;autoplay=false&amp;id=59387849&amp;skinNum=1&amp;topBar=1&amp;xuid=";
    
	//http://my.tv.sohu.com/play/videonew.do?vid=59387849
	$url="http://my.tv.sohu.com/play/videonew.do?vid=59387849";
	//$url="http://17173.tv.sohu.com/play/videonew.do?Flvid=1774369";
	//$url="http://my.tv.sohu.com/play/videonew.do?vid=56947510";
	$url="http://my.tv.sohu.com/us/110160765/56947510.shtml";
	//$url="http://www.36kr.com/p/207006.html";

    //$results=curlexec($url);
	//$results=@file_get_contents($url);
	//echo substr($http_response_header[0],9,3);
	//header($http_response_header[0]);
    //echo $results;
    
	/*
    $url="http://hot.vrs.sohu.com/vrs_flash.action?vid=1348311";
	$url="http://wangweike.yeskj.info/hot.vrs.sohu.com.php?vid=1348311";
    $ctx = stream_context_create(array('http' => array('timeout' => 60)));
    $results=file_get_contents($url, 0, $ctx);
    if($results=="")
    {
        $results="空";
    }
    echo $results;
	*/
    
    /*
    $host="hot.vrs.sohu.com";
    $uri="/vrs_flash.action?vid=1348311";
    
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
        echo "出错了 $errstr ($errno) <br/>\n";
    }
     */
    
    /*
    $loginUrl="http://passport.cnblogs.com/login.aspx";
  
        $params = array();
        $params['__EVENTTARGET'] = "";
        $params['__EVENTARGUMENT'] = "";
        $params['__VIEWSTATE'] = "/wEPDwULLTE1MzYzODg2NzZkGAEFHl9fQ29udHJvbHNSZXF1aXJlUG9zdEJhY2tLZXlfXxYBBQtjaGtSZW1lbWJlcm1QYDyKKI9af4b67Mzq2xFaL9Bt";
        $params['__EVENTVALIDATION'] = "/wEdAAUyDI6H/s9f+ZALqNAA4PyUhI6Xi65hwcQ8/QoQCF8JIahXufbhIqPmwKf992GTkd0wq1PKp6+/1yNGng6H71Uxop4oRunf14dz2Zt2+QKDEIYpifFQj3yQiLk3eeHVQqcjiaAP";
        $params['tbUserName'] = "wangweike";
        $params['tbPassword'] = "wangweike2011";
        $params['btnLogin'] = "登  录";
        $params['txtReturnUrl'] = "http://home.cnblogs.com/";
        
        $results=postCurlExec($loginUrl,$params);
        
        echo $results;
     */
	 
	 //test
				/*
				foreach($arr as $key=>$value)
				{
					echo $key."=>".$value."<br>";
				}
				 */
			 
				//method 1
				/*
				//update immediately set to default 1 hour delay
				$min_hours=1;
				updateCaptureTime($this->serviceCode,$categoryCode,$min_hours);
		
				$capturedCount=$this->captureListPages($categoryCode,$this->tableName,$categoryPriorityDic);
                
                if($capturedCount>0 || $lastPeriod<=$min_hours)
                {
                    $max_hours=3;
                    updateCaptureTime($this->serviceCode,$categoryCode,$max_hours);
                }
				*/
				
    
	/*
    echo htmlspecialchars_decodex("Failed to install *.apk on device 'emulator-5554': timeout .");
    
    $cptStep=8;
    $periodHours=4.0/$cptStep;
    echo "a periodHours:".$periodHours;
	*/
	
	/*
	$url = "http://cocos2d.9tech.cn/";
	//$url = "http://www.baidu.com/";
	$ch = curl_init();
	$timeout = 20;
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$userAgent="Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:27.0) Gecko/20100101 Firefox/27.0";
	curl_setopt($ch,CURLOPT_USERAGENT,$userAgent);
	$file_contents = curl_exec($ch);
	curl_close($ch);
	
	echo $file_contents;
	*/

/*
        $host="www.9tech.cn";
		$uri="/";
        $fp=fsockopen($host,80,$errno,$errstr);
        if($fp)
        {
            $out = "GET ".$uri." HTTP/1.1\r\n";
            $out.= "Host: ".$host."\r\n";
            $out.= "Connection: keep-alive\r\n\r\n";
            
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
            echo "$errstr ($errno)<br />\n";
        }
    */
	
	//$results = fileGetContents($url,$http_response_header);
	//$results = file_get_contents($url);
	//$results = curlexec($url);
	//$results=curlexec($url,NULL,$http_response_header);
	//var_dump($http_response_header);
	//echo $results;
	
	$port=80;
	$host=$_SERVER ['HTTP_HOST'];
	$arr = explode(":",$host);
	if(count($arr)>1)
	{
		$port=$arr[1];
	}
	
	echo $host;
	echo $port;
	
?>
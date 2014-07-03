<?php
    
    function encrypt($string,$key = "")
    {
        return authcode($string, 'ENCODE', $key, 0);
    }
    
    function decrypt($string,$key = "")
    {
        return authcode($string, 'DECODE', $key, 0);
    }
    
	function authcode($string, $operation = 'DECODE', $key = "", $expiry = 0)
    {
        //$key是自定义的一个密钥
        $ckey_length = 4;
        $key = md5($key ? $key : '123456');//若未指定key，则使用123456，可以改成自己的
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
        
        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);
        
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);
        
        $result = '';
        $box = range(0, 255);
        
        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
        
        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        
        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        
        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }
        
    }
    
    function htmlspecialchars_decodex($htmlString)
    {
        /*
         htmlspecialchars_decode() 函数把一些预定义的 HTML 实体转换为字符。
         
         会被解码的 HTML 实体是：
         &amp; 成为 & （和号）
         &quot; 成为 " （双引号）
         &#039; 成为 ' （单引号）
         &lt; 成为 < （小于）
         &gt; 成为 > （大于）
         
         quotestyle 可选。规定如何解码单引号和双引号。
         ENT_COMPAT - 默认。仅解码双引号。
         ENT_QUOTES - 解码双引号和单引号。
         ENT_NOQUOTES - 不解码任何引号。


         */
        $htmlString = trim($htmlString);
        $htmlString = htmlspecialchars_decode($htmlString,ENT_QUOTES);

        $trans = array( "&#038;"  => "&",
                        "&#039;"  => "''",
                        "&#39;"   => "''",
                        "&#x27;"  => "''",
                        "'"       => "''",
						"\\"       => "\\\\",
                        "&#160;"  => " ",
						"&#176;"  => "°",
						"&#183;"  => "·",
						"&#215;"  => "×",
						"&#216;"  => "Ø",
						"&#231;"  => "ç",
                        "&#237;"  => "í",
                        "&#243;"  => "ó",
						"&#247;"  => "÷",
                        "&#8211;" => "–",
                        "&#8212;" => "—",
                        "&#8217;" => "’",
						"&#8220;" => "“",
						"&#8221;" => "”",
                        "&#8230;" => "…",
						"&hellip;" => "…",
                        "&mdash;" => "—",
                        "&ndash;" => "–",
						"&ldquo;" => "“",
						"&quot;" => "\"",
						"&rdquo;" => "”",
						"&gt;"    => ">",
                        "\t"      => "",
                        "\r\n"    => "",
						"\r"    => "",
						"\n"    => ""
                      );
        $htmlString = strtr($htmlString, $trans);
        
        return $htmlString;
    }
    
    //未测试
    //http://blog.sina.com.cn/s/blog_62067a650100izag.html
    function containChineseCharactor($str)
    {
        return preg_match("/[\x7f-\xff]/", $str);
    }
    
    //for v1.0
    function bytesContentWithFilePath($path,$fileName)
    {
        $filePathName=$path.$fileName;
        $fileContents=file_get_contents($filePathName);
        
        //文件名，内容
        $retContent=pack("N",strlen($fileName));
        $retContent.=$fileName;
        $retContent.=pack("N",strlen($fileContents));
        $retContent.=$fileContents;
        
        return $retContent;
    }
	
	//自定义的数据结构
    function bytesContentWithString($string)
    {
        //字符串内容
		$retContent=pack("N",strlen($string));
        $retContent.=$string;
        
        return $retContent;
    }
	
	//自定义的数据结构
    function bytesContentWithFileType($path,$fileName)
    {
		$type="file";
        $filePathName=$path.$fileName;
        $fileContents=file_get_contents($filePathName);
        
        //类型，文件名，内容
		$retContent=pack("N",strlen($type));
        $retContent.=$type;
        $retContent.=pack("N",strlen($fileName));
        $retContent.=$fileName;
        $retContent.=pack("N",strlen($fileContents));
        $retContent.=$fileContents;
        
        return $retContent;
    }
	
	//自定义的数据结构
	function bytesContentWithType($type,$content)
    {
        //类型，内容
		$retContent=pack("N",strlen($type));
        $retContent.=$type;
        $retContent.=pack("N",strlen($content));
        $retContent.=$content;
        
        return $retContent;
    }
    
    function checkConvertHtmlToCharsetUtf8($content)
	{
		$charset = preg_match("/<meta.+?charset=\"(.*?)\"/i",$content,$temp) ? trim(strtolower($temp[1])):"";
        if($charset=="")
        {
            //有可能有两句
            $ret = preg_match_all("/<meta\s+http-equiv=\"Content-Type\"\s+content=\"text\/html;\s*charset=(.*?)\"/i",$content,$temp);
            if($ret)
            {
				$index=count($temp[1])-1;
                $charset=trim(strtolower($temp[1][$index]));
            }
        }
        
		//echo "charset:".$charset."-\n";
		$pos=strpos($charset,"gb");
 		if($pos!==FALSE)
		{
			//echo "iconv from ".$charset." to utf-8";
			$charset="gbk";
			$content=iconv($charset,"utf-8",$content);
		}
		
		return $content;
	}
	
	function subStringBetweenTag($content,$tag1,$tag2)
	{
		$subString=NULL;
		
		$pos=strpos($content,$tag1,0);
		if($pos!=false)
		{
			$pos2=strpos($content,$tag2,$pos+1);
			if($pos2!=false && $pos2>$pos)
			{
				$subString=substr($content,$pos+1,$pos2-$pos-1);
			}
		}
		
		return $subString;
	}

	function isStringStartWith($str, $theSubstr)
	{
		return !strncmp($str, $theSubstr, strlen($theSubstr));
	}
	
	function isStringEndWith($str, $theSubstr)
	{
		$length = strlen($theSubstr);
		if ($length == 0) 
		{
			return true;
		}
	
		return (substr($str, -$length) === $theSubstr);
	}

?>

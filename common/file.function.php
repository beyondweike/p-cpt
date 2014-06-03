<?php
	/**
	 * 保存文件
	 * 
	 * @param string $fileName 文件名（含相对路径）
	 * @param string $text 文件内容
	 * @return boolean 
	 */
	function funSaveFile($fileName, $text) 
	{
		if (!$fileName || !$text || strlen($text)==0)
		{
			return false;
		}
		
		$ret=false;
		
		if (funMakeDir(dirname($fileName))) 
		{
			if ($fp = fopen($fileName, "w")) 
			{
				$ret = @fwrite($fp, $text);
				fclose($fp);
			} 
		} 
		
		return $ret;
	} 

	/**
	 * 连续创建目录
	 * 
	 * @param string $dir 目录字符串
	 * @param int $mode 权限数字
	 * @return boolean 
	 */
	function funMakeDir($dir, $mode = "0777") 
	{
		if (!$dir) return false;

		if(!file_exists($dir)) 
		{
			return mkdir($dir,$mode,true);
		} 
		else 
		{
			return true;
		}
	}


	/**
	 * 将一个URL转换为完整URL
	 *
	 */
	function format_url($srcurl, $baseurl) 
	{
		if(preg_match("/^\s*http.*$/",$srcurl))
		{
			return $srcurl;
		}
        
        if( substr($baseurl,strlen($baseurl)-1)=="/" )
        {
            $url=$baseurl.$srcurl;
        }
        else
        {
            $url=$baseurl."/".$srcurl;
        }
        
        $url=preg_replace("/(?<!:)\/{2,}/","/",$url);//去除多个/
        
		return $url;
	}
	
	function getHost($absoluteUrl)
	{
		preg_match("/^(http:\/\/)?([^\/]+)/i", $absoluteUrl, $matches); 
		$host = $matches[2]; 
		//preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches); 
		//$domain=$matches[0];
		return $host;//
	}
    
    //properties file
    function get_data($filename)
    {
        if (file_exists($filename))
        {
            return unserialize(file_get_contents($filename));//unserialize
        }
        
        return "";
    }
    
    function get_option($filename, $key = "")
    {
        $data = get_data($filename);
        
        return $data[$key];
    }
    
    function set_option($filename, $key, $value)
    {
        $data = get_data($filename);
        $data[$key] = $value;
        
        // write to disk
        $fp = fopen($filename, 'w');
        fwrite($fp, serialize($data));
        fclose($fp);
    }

	function log2File($filepathname,$content)
	{
		date_default_timezone_set('Asia/Shanghai');
		file_put_contents($filepathname, date("Y-m-d H:i:s",time()). " " . $content.PHP_EOL."\r\n", FILE_APPEND | LOCK_EX);
	}
	
	function getLocalContents($path)
    {
		$contents = file_get_contents($path);
		return $contents;
	}

?>
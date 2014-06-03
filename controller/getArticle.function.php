<?php
	include_once("../common/file.function.php");
	include_once("captureArticle.function.php"); 
	
	function urlEncodeFormatUrl($url)
	{
		$url=preg_replace("/^(.*?)#.*?$/","\\1",$url);
		
		//$url="http://www.cnblogs.com/min-cj/p/php下载.html";
		$url=preg_replace("/^(.*)\/([^\?]*)(.*?)$/ie", "'\\1'.'/'.urlencode('\\2').'\\3'", $url);
		
		return $url;
	}
			
	function getContent($url,&$tagArray,$batch=false)
	{
        $url=urlEncodeFormatUrl($url);
        
		$find = array(":","/","?");
		$fileName=str_ireplace($find,"_",$url);
		$filePathName="../articles/".$fileName.".txt";

		$content="";
		
		if(file_exists($filePathName))
		{
			$content=getLocalContents($filePathName);//nl2br
		}
		else if(!$batch)
		{
            $content=funCaptureArticle($url,$fileName,$tagArray);
            
			funSaveFile($filePathName, $content);
		}
		
		return $content;
	} 
	
?>

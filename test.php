<?php
    include_once("common/string.function.php");
    include_once("common/file.function.php");
	include_once("common/request.function.php");
    
	/*
	//$password=decrypt("c300T3LDEJ83D8T+43KfpzYaWPGg02zJzxq5Gbb4ddDCEgcwvi4q11Y","wangweike");
	$passwordcoded=encrypt("d54fc9c399","wangweike");
	
	//echo $password;
	echo "<br>";
	echo $passwordcoded;
	echo "<br>";
	
	$theDate=date("Y-m-d",strtotime("-1 week"));
	
	echo $theDate;
	*/
	
	/*
	// Create the payload body
		$body['aps'] = array('alert' => "让ios推送(apns)内容长度从30个提升到70",'sound' => 'default');
		$body['type'] = 0 ;
		$body['article'] = array('id' => 1,'catecode' => 2,'time' => '33');//'title' => $item->title,
			
		 // Encode the payload as JSON
		$payload = json_encode($body,JSON_UNESCAPED_UNICODE);
		$payload2 = json_encode($body);
		
		echo  $payload;
		echo  "<br><br>".$payload2;
		*/
		
	/*
	if (function_exists('fwrite')) 
	{
    	//echo "<br>fwrite";
	} 
	else 
	{
    	//echo "<br>no fwrite";
	}
	
	$fileName="articles/a.txt";
	$text='aabbcc';
	
	if ($fp = fopen($fileName, "w")) 
			{
				echo " <br> open sccess";
				
				$ret = var_dump(fwrite($fp, $text));
				fclose($fp);
				
				echo " <br> fwrite ".$ret;
			} 
			
			
			//funSaveFile($fileName,$text);
	
	echo "<br>";
	
	$content=file_get_contents("articles/a.txt");
	echo $content;
    */
	
	$url="http://www.woshipm.com/category/xiazai/page/1";
	$results=fileGetContents( $url );
	
	$content = preg_match("/<div[^>]+class=\"content_box bor_cor\"[^>]*>([\s\S]*?)<div[^>]+id=\"pagenavi\"/i",$results,$temp) ? $temp[1]:"";
        if($pageCount<=1)
		{
			$pageInfo = preg_match("/<div[^>]+id=\"pagenavi[^>]*>([\s\S]*)<\/div>/i",$results,$temp) ? $temp[1]:"";
			$pageCount = preg_match("/<span class=\"page-numbers\">1\/(\\d+)\s*<\/span>/",$pageInfo,$temp)?$temp[1]:$pageCount;
		}
	
	
	echo $content;
?>
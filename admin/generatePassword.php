<?php
    include_once("../common/string.function.php");
    include_once("../common/file.function.php");
    
	//$password=decrypt("c300T3LDEJ83D8T+43KfpzYaWPGg02zJzxq5Gbb4ddDCEgcwvi4q11Y","wangweike");
	$passwordcoded=encrypt("d54fc9c399","wangweike");
	
	//echo $password;
	echo "<br>";
	echo $passwordcoded;
	echo "<br>";
	
	//$theDate=date("Y-m-d",strtotime("-1 week"));
	
	//echo $theDate;
	
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
?>
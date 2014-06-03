<?php
    include_once("../common/file.function.php");
	
    /*输入文章名称，或文章地址
    列出文章名称，地址
    
    然后
    查看文章内容；
    删除文章内容；
    
    */
    
    /*
	$valide=FALSE;
	
    $headers=getAllHeadersLowerCase();
    $encrypt=$headers["encrypt"];//must use lower case
	$productCode=$headers["productcode"];//must use lower case
	
	//print_r($headers);

	if($encrypt)
	{
		$properties=Properties::getProperties();
		$encrypt1=$properties->lastEncrypt;
		$encrypt2=$properties->encrypt;
		
		if($encrypt==$encrypt1 || $encrypt==$encrypt2)
		{
			$valide=TRUE;
		}
	}
    
    //test
    //$valide=true;
    //$productCode=0;
	
	if(!$valide)
	{
		return NULL;
	}
    */

	if (isset($_GET['url']))
	{
		$url=$_GET['url'];
        
        $find = array(":","/","?");
		$fileName=str_ireplace($find,"_",$url);
		$filePathName="../articles/".$fileName.".txt";
        
        if(file_exists($filePathName))
        {
            $ret=unlink($filePathName);
            if($ret)
            {
                echo "$url delete success".time();
            }
            else
            {
                echo "$url delete fail ".time();
            }
			
			for($i=0;$i<100;$i++)
			{
				$imageFilePathName=$filePathName."_$i.jpg";
				if(file_exists($imageFilePathName))
				{
					unlink($imageFilePathName);
				}
				else
				{
					break;
				}
			}
        }
        else
        {
            echo "$url delete fail, file not exists ".time();
        }
    }
?>

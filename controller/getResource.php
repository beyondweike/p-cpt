<?php
    include_once("../common/request.function.php");
	include_once("service.class.php");
	
	$headers=getAllHeadersLowerCase();
    $productCode=$headers["productcode"];
	$dir=Service::getDir($productCode);
    $version=$headers["version"];
    
	//print_r($headers);
    
    $resourceName=NULL;
	if (isset($_GET['resourceName']))
	{
		$resourceName=$_GET['resourceName'];
	}
	
	if($resourceName)
	{
		if($resourceName=="hotWords")
		{
			$resourceName="hotWords.txt";
		}
		else if($resourceName=="quotations")
		{
			$resourceName="quotations.txt";
		}
		
        header("Content-type: application/octet-stream");
        
        if($version<=1.0)
        {
            $resourceZipFilePathName="../".$dir."/config/resource/$resourceName";

            header("Content-Disposition: attachment; filename=\"$resourceName\"");
            
            echo getLocalContents($resourceZipFilePathName);
        }
        else
        {
            $retContent="";
            
			$retContent.=bytesContentWithString("configData");//头标记
            $retContent.=bytesContentWithFileType("../$dir/config/resource/",$resourceName);
            
            //quotations.txt
            //$retContent.=bytesContentWithFile("../$dir/config/resource/","quotations.txt");
            
            //categories.json
            //$retContent.=bytesContentWithFile("../$dir/config/resource/","categories.json");
            
            echo $retContent;
        }
	}
	else
	{
		echo "haha";
	}
	
	
?>

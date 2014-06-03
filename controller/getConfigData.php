<?php
	include_once("../common/request.function.php");
    include_once("../common/string.function.php");
	include_once("../common/visitor.class.php");
	include_once("service.class.php");
	include_once("db.function.php");
	
    $headers=getAllHeadersLowerCase();
    $productCode=$headers["productcode"];
	$dir=Service::getDir($productCode);
	//print_r($headers);

	if($dir)
	{
		header("Content-type: application/octet-stream");

		//get
		$version=$_GET['version'];
		$resourceVersion=$_GET['resourceVersion'];
		
		//server
		$path="../".$dir."/config/config.json";
		$content = getLocalContents($path);
		$arr = json_decode($content,true); //解析为数组
		$serverVersion=$arr["version"];
		$serverResourceVersion=$arr["resourceVersion"];
        
        //防止数据出错
        if($version<1.0)
        {
            $version=1.0;
        }
        if($resourceVersion<1.0)
        {
            $resourceVersion=1.0;
        }
        if($serverVersion<1.0)
        {
            $serverVersion=$version;
        }
        if($serverResourceVersion<1.0)
        {
            $serverResourceVersion=$resourceVersion;
        }
        
        //test
        //echo "request:".$version.",".$resourceVersion." server:".$serverVersion.",".$serverResourceVersion;
        //return;
        
        $con=dbConnect();
		$visitTimes=Visitor::updateVisitTimes($headers);
		dbClose($con);
		
		//retContent
		$retContent="";

		//config.json
		if($version<=1.0)
		{
			header("encryptKey: 123456");
			$retContent.=bytesContentWithFilePath("../$dir/config/","config.json");
		}
		else
		{
			$retContent.=bytesContentWithString("configData");//头标记
			$retContent.=bytesContentWithFileType("../$dir/config/","config.json");
		}
		
		//resource.zip
		if($serverVersion>=$version && $serverResourceVersion>$resourceVersion)
		{
			if($version<=1.0)
			{
				//$retContent.=bytesContentWithFilePath("../$dir/config/resource/","resource.zip");
			}
			else
			{
				//$retContent.=bytesContentWithFileType("../$dir/config/resource/","resource.zip");
			}
		}
		
		if($version<=0.5)
		{
			//$alertContent='{"message":"您好久未更新版本了"}';
            //$retContent.=bytesContentWithType("alert",$alertContent);
		}

		if($version<=1.0)
		{
            //if($visitTimes<=1)// && $visitTimes<=20 卸载后再安装，visitTimes继续,所以等1.0后面的版本在客户端要记录启动次数。
            $retContent.=bytesContentWithFilePath("../$dir/config/resource/","resource.zip");
            
            //hotWords.txt
			//$retContent.=bytesContentWithFilePath("../$dir/config/resource/","hotWords.txt");
			
			//quotations.txt
			//$retContent.=bytesContentWithFilePath("../$dir/config/resource/","quotations.txt");
   
            //$deviceId=$headers["deviceid"];
            //if($deviceId=="042C5C6D-E70D-46AA-B294-74504ABCCFD6")
            //{
                //categories.json
                //$retContent.=bytesContentWithFilePath("../$dir/config/resource/","categories.json");
            //}
            
            //$alertContent='{"message":"尊敬的读者，由于服务器出现不稳定情况，影响了更新和下载功能。很抱歉对您的阅读体验造成了影响，我们正紧急优化完善。请持续关注《程序员读》"}';
            //$retContent.=bytesContentWithType("alert",$alertContent);
		}
        else
		{
			//quotations.txt
			$retContent.=bytesContentWithFileType("../$dir/config/resource/","quotations.txt");
			
			//categories.json
            //$retContent.=bytesContentWithFileType("../$dir/config/resource/","categories.json");
			
			//serverSetting.json
            //$retContent.=bytesContentWithFileType("../$dir/config/resource/","serverSetting.json");
            
            //error.html
            //$retContent.=bytesContentWithFileType("../$dir/config/resource/","error.html");
		}
		
		echo $retContent;
	}
	else
	{
		echo "haha";
	}
?>

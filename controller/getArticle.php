<?php
	include_once("getArticle.function.php"); 
	include_once("db.function.php"); 
	include_once("../common/item.class.php");
	
    $headers=getAllHeadersLowerCase();
    $version=$headers["version"];
	
	$tableName="list_table";
	$batch=false;
	if($version<=1.0)
	{
		$batch=true;
	}
	
	$urlArray=null;
	
	if (isset($_POST['articleIds']))
	{
        $articleIds=$_POST['articleIds'];
		$con=dbConnect();
		$urlArray=Item::queryHrefArrayByIds($articleIds,$tableName);
		dbClose($con);
	}
	else if (isset($_POST['urls']))
	{
        $urls=$_POST['urls'];
        
		$ret=preg_match_all("/\[(.*?)\]/i", $urls, $temp);
		if($ret)
		{
			$urlArray=$temp[1];
		}
	}
	
	if($urlArray)
	{
		//set timeout seconds
		set_time_limit(30*count($urlArray));
		
		$contents="";		 
		
		foreach($urlArray as $url)
		{
			$contents.="<article>";
			$tagArray=array();
			$content = getContent($url,$tagArray,$batch);
			if(count($tagArray)>0)
			{
				$con=dbConnect();
				Item::setTags($url,$tagArray,$tableName);
				dbClose($con);
			}
				
			if($content=="")
			{
				date_default_timezone_set('Asia/Shanghai');
				$filePathName="../logs/article_error_".date("Y-m-d",time()).".log";
				log2File($filePathName,"getArticle.php ".$url);
			}
			
			//test
			//echo count($content);
			//return;
		
			$pageUrlArray=NULL;
			$ret=preg_match_all("/<pageUrls>(.*?)<\/pageUrls>/i", $content, $temp);
			if($ret)
			{
				$str=$temp[1][0];
				$ret=preg_match_all("/<pageUrl>(.*?)<\/pageUrl>/i",$str , $temp);
				if($ret)
				{
					$pageUrlArray=$temp[1];
					$pageUrlArray=array_unique($pageUrlArray);
				}
			}
			
			$contents.="<page>".
							"<url>".base64_encode($url)."</url>".
							"<content>".base64_encode($content)."</content>".
						"</page>";

			if($pageUrlArray)
			{
				$i=0;
				foreach($pageUrlArray as $pageUrl)
				{
					if($i>=1)
					{
						$tagArray=array();
						$content = getContent($pageUrl,$tagArray,$batch);
						if(count($tagArray)>0)
						{
							$con=dbConnect();
							Item::setTags($pageUrl,$tagArray,$tableName);
							dbClose($con);
						}
				
						if($content=="")
						{
							date_default_timezone_set('Asia/Shanghai');
							$filePathName="../logs/article_error_".date("Y-m-d",time()).".log";
							log2File($filePathName,"getArticle.php pageUrl ".$pageUrl);
						}
						
						$contents.="<page>".
										"<url>".base64_encode($pageUrl)."</url>".
										"<content>".base64_encode($content)."</content>".
									"</page>";
					}
					
					$i++;
				}
			}

			$contents.="</article>";
			
			echo $contents;

			$contents="";
		}
	}
	else
	{
		set_time_limit(30);
		
		$url=NULL;
		$urlGet=NULL;
		if(isset($_GET['url']))
		{
			$urlGet=$_GET['url'];
			$url=$urlGet;
		}

		/*有的文章原地址已不存在原服器上会存在跳转，本服务器会以302返给客户端，
		客户端的FHTTPRequest可能处理为错误。但本服务器还会继续执行下面的代码，所以可能保存两份内容，供客户端刷一下。
		*/
		$content=getContentWithUrl($url);		
		if(!$content || $content=="")
		{
			$url=NULL;
			$articleId=0;
			if(isset($_GET['articleId']))
			{
				$articleId=$_GET['articleId'];
				if($articleId>0)
				{
					$con=dbConnect();
					$item=Item::queryItemById($articleId,$tableName);
					$url=$item->href;
					dbClose($con);
				}
			}
			
			$content=getContentWithUrl($url);
			if(!$content || $content=="")
			{
				$url=NULL;
				$title=NULL;
				if(isset($_GET['title']))
				{
					$title=$_GET['title'];
					if($title)
					{
						$con=dbConnect();
						$url=Item::queryHrefByTitle($title,$tableName);
						dbClose($con);
					}
				}
				
				$content=getContentWithUrl($url);
				if($content && $content!="")
				{
					$filePathName=getLocalFilePathNameWithArticleUrl($urlGet);
					funSaveFile($filePathName, $content);
				}
			}
			else
			{
				$filePathName=getLocalFilePathNameWithArticleUrl($urlGet);
				funSaveFile($filePathName, $content);
			}			
		}
		
		if($content && $content!="" && $url!=$urlGet)
		{
			$content="<url>".$url."</url>".$content;
		}

		echo $content;
	}
	
	function getContentWithUrl($url)
	{
		$tableName="list_table";
		$content=NULL;
		
		if($url && $url!="")
		{
			$tagArray=array();
			$content=getContent($url,$tagArray);
	
			if(count($tagArray)>0)
			{
				$con=dbConnect();
				Item::setTags($url,$tagArray,$tableName);
				dbClose($con);
			}
			
			if(!$content || $content=="")
			{
				date_default_timezone_set('Asia/Shanghai');
				$filePathName="../logs/article_error_".date("Y-m-d",time()).".log";
				log2File($filePathName,"getArticle.php ".$url);
			}
		}
		
		return $content;
	}
?>

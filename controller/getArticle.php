<?php
	include_once("getArticle.function.php"); 
	include_once("db.function.php"); 
	include_once("../common/item.class.php");
	
    $headers=getAllHeadersLowerCase();
    $version=$headers["version"];
	
	$batch=false;
	if($version<=1.0)
	{
		$batch=true;
	}
	
	if (isset($_POST['urls']))
	{
        $urls=$_POST['urls'];
        
		$ret=preg_match_all("/\[(.*?)\]/i", $urls, $temp);
		if($ret)
		{
			$urlArray=$temp[1];
	
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
					$tableName="list_table";
					$con=dbConnect();
					Item::setTags($url,$tagArray,$tableName);
					dbClose($con);
				}
					
                if($content=="")
                {
					date_default_timezone_set('Asia/Shanghai');
					$filePathName="../logs/article_error_".date("Y-m-d",time()).".log";
					log2File($filePathName,$url);
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
								$tableName="list_table";
								$con=dbConnect();
								Item::setTags($pageUrl,$tagArray,$tableName);
								dbClose($con);
							}
					
                            if($content=="")
                            {
								date_default_timezone_set('Asia/Shanghai');
								$filePathName="../logs/article_error_".date("Y-m-d",time()).".log";
								log2File($filePathName,$pageUrl);
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
            
            echo $contents;
		}
	}
	else
	{
		set_time_limit(30);
		
		$url=NULL;
		$articleId=0;
		
		if(isset($_GET['articleId']))
		{
			$articleId=$_GET['articleId'];
			
			$con=dbConnect();
			$item=Item::queryItemById($articleId,"list_table");
			$url=$item->href;
			dbClose($con);
		}
		else
		{
			$url=$_GET['url'];
		}
		
		$tagArray=array();
		$content=getContent($url,$tagArray);

		if(count($tagArray)>0)
		{
			$tableName="list_table";
			$con=dbConnect();
			Item::setTags($url,$tagArray,$tableName);
			dbClose($con);
		}
		
		if($content=="")
		{
			date_default_timezone_set('Asia/Shanghai');
			$filePathName="../logs/article_error_".date("Y-m-d",time()).".log";
			log2File($filePathName,$url);
            
            //test
            //$tempurl=urlEncodeFormatUrl($url);
            //log2File($filePathName,"urlEncodeFormatUrl: ".$tempurl);
            
			//second time search by title
			if (isset($_GET['title']))
			{
				$title=$_GET['title'];
				
				$tableName="list_table";
				$con=dbConnect();
				$url=Item::queryHrefByTitle($title,$tableName);
				dbClose($con);
				
                if(strlen($url)>0)
                {
                    $tagArray=array();
					$content=getContent($url,$tagArray);
					
					if(count($tagArray)>0)
					{
						$tableName="list_table";
						$con=dbConnect();
						Item::setTags($url,$tagArray,$tableName);
						dbClose($con);
					}
		
                    if($content=="")
                    {
                        log2File($filePathName,$url);
                    }
                }
			}
		}
		
		if($content!="" && $articleId>0)
		{
			$content="<url>".$url."</url>".$content;
		}

		echo $content;
	}
?>

<?php
	include_once("../common/file.function.php");
    include_once("../common/request.function.php"); 
    include_once("../common/string.function.php");
    include_once("captureArticle.common.function.php");
    
    function funCaptureArticle($url,$fileName,&$tagArray,$recursionCount=0,$lastResponseStatus=NULL,$cookie=NULL)
    {
        $results=NULL;
		
		if($recursionCount>=3)
		{
			return $results;
		}
 
        if($cookie && $cookie!="")
        {
            //http://www.cnblogs.com/hecool/p/3386344.html
            $results=curlexec($url,$cookie,$http_response_header);
        }
        else
        {
			if(strpos($url,"blog.csdn.net")!=false || strpos($url,"www.cocoachina.com")!=false)
			{
				$results=curlexec($url,NULL,$http_response_header);
			}
			else
			{
				$results=fileGetContents($url,$http_response_header);
			}
        }

		$http_response_header0="";
        $statusCode=200;
		if($http_response_header)
		{
			$http_response_header0=$http_response_header[0];
			$statusCode=substr($http_response_header[0],9,3);
		}
		
		if($statusCode>=500)
		{
			//http://blog.jobbole.com/65086/
			$results=curlexec($url,NULL,$http_response_header);
			if($http_response_header)
			{
				$http_response_header0=$http_response_header[0];
				$statusCode=substr($http_response_header[0],9,3);
			}
		}
		
		if($statusCode!=200)
		{
            $location=NULL;
            if($statusCode==301 || $statusCode==302 || $statusCode==303)
            {
                foreach($http_response_header as $header)
                {
                    if(substr($header,0,8)=="Location")
                    {
						$http_response_header0=$http_response_header[0];
                        $lastResponseStatus=$http_response_header[0];
                        $location=substr($header,10);
                        break;
                    }
                }
            }
			else
			{
				date_default_timezone_set('Asia/Shanghai');
				$filePathName="../logs/article_error_".date("Y-m-d",time()).".log";
				log2File($filePathName,$url."\nhttp_response_header[0]: ".$http_response_header0);
			}
            
            if(!$location || $location==$url)
            {
                header($http_response_header[0]);//response error to client
            }
            else
            {
				if(strpos($url,"cnblogs.com")!=false)
				{
					$ReturnUrl=NULL;
					$cnblogsLoginUrl="http://passport.cnblogs.com/login.aspx?ReturnUrl=";
					$cnblogsLoginUrl2="https://passport.cnblogs.com/login.aspx?ReturnUrl=";
					$pos=strpos($location,$cnblogsLoginUrl);
					
					if($pos===FALSE)
					{
						$ReturnUrl=urldecode(substr($location,$pos+strlen($cnblogsLoginUrl2)));
						$pos=strpos($location,$cnblogsLoginUrl2);
					}
					
					if($pos!==FALSE)
					{
						if(!$ReturnUrl)
						{
							$ReturnUrl=urldecode(substr($location,$pos+strlen($cnblogsLoginUrl)));
						}
	
						$retArr=loginCnblogs($location,$ReturnUrl);
						list($cookie,$location)=$retArr;
						if(!$cookie || $cookie=="")
						{
							$retArr=loginCnblogs($location,$ReturnUrl);
							list($cookie,$location)=$retArr;
						}
	
						return funCaptureArticle($url,$fileName,$tagArray,$recursionCount+1,$lastResponseStatus,$cookie);
					}
				}
				else if(strpos($url,"tuicool.com")!=false)
				{
					$LoginUrl="http://www.tuicool.com/login";
					$pos=strpos($location,$LoginUrl);
					
					if($pos!==FALSE)
					{
						$retArr=loginTuicool($location);
						list($cookie,$location)=$retArr;
						
						return funCaptureArticle($url,$fileName,$tagArray,$recursionCount+1,$lastResponseStatus,$cookie);
					}
				}
				
                return funCaptureArticle($location,$fileName,$tagArray,$recursionCount+1,$lastResponseStatus);
            }
		}
		
		if($results=="")
		{
            if($lastResponseStatus)
            {
                header($lastResponseStatus);
            }

			return $results;
		}
		
		$results=checkConvertHtmlToCharsetUtf8($results);
        
        //title
        $title=getTitle($results);
	
		$tempUrl=$url;
        $contentArray = parseArticleContent($tempUrl,$results);
		if($tempUrl!=$url)
		{
			return funCaptureArticle($tempUrl,$fileName,$tagArray,$recursionCount+1,$lastResponseStatus,$cookie);
		}
		
        $content0=$contentArray[0];
		
		//replaceDataImage
		$content0=replaceDataImage($content0,$fileName);
        
        //embed replace
		$content0=embedReplace($content0,$tagArray,$url);
		
        //general replace
        $baseUrl="http://".getHost($url)."/";
        $content0=generalRreplace($content0,$baseUrl);
        
        $contentArray[0]=$content0;
        
		if($content0=="")
		{
            if($lastResponseStatus)
            {
                header($lastResponseStatus);
            }
			return "";
		}
        
        $content="";
        
        foreach($contentArray as $subContent)
        {
            $content.=$subContent;
        }
		
        return $title.$content;
    }
    
    function loginCnblogs($cnblogsLoginUrl,$ReturnUrl)
    {
        $params = array();
        $params['__EVENTTARGET'] = "";
        $params['__EVENTARGUMENT'] = "";
        $params['__VIEWSTATE'] = "/wEPDwULLTE1MzYzODg2NzZkGAEFHl9fQ29udHJvbHNSZXF1aXJlUG9zdEJhY2tLZXlfXxYBBQtjaGtSZW1lbWJlcm1QYDyKKI9af4b67Mzq2xFaL9Bt";
        $params['__EVENTVALIDATION'] = "/wEdAAUyDI6H/s9f+ZALqNAA4PyUhI6Xi65hwcQ8/QoQCF8JIahXufbhIqPmwKf992GTkd0wq1PKp6+/1yNGng6H71Uxop4oRunf14dz2Zt2+QKDEIYpifFQj3yQiLk3eeHVQqcjiaAP";
        $params['tbUserName'] = "wangweike";
        $params['tbPassword'] = "cnblogscom";
        $params['btnLogin'] = "登  录";
        $params['txtReturnUrl'] = $ReturnUrl;//"http://home.cnblogs.com/";
        
        $headersStr="";
        postCurlExec($cnblogsLoginUrl,$params,30,$headersStr);
		
        $cookie=preg_match("/Set-Cookie:\s+(.*?)\r\n/", $headersStr, $temp)?$temp[1]:"";
		$location=preg_match("/Location:\s+(.*?)\r\n/", $headersStr, $temp)?$temp[1]:"";
        
        return array($cookie,$location);
    }
	
	//http://www.tuicool.com/articles/EBzqYnM
	function loginTuicool($LoginUrl)
    {
        $params = array();
        $params['email'] = "brogrammer@qq.com";
        $params['password'] = "tuicool";
        
        $headersStr="";
        postCurlExec($LoginUrl,$params,30,$headersStr);
		
        $cookie=preg_match("/Set-Cookie:\s+(.*?)\r\n/", $headersStr, $temp)?$temp[1]:"";
		$location=preg_match("/Location:\s+(.*?)\r\n/", $headersStr, $temp)?$temp[1]:"";
        
        return array($cookie,$location);
    }
    
	//http://www.cnblogs.com/nerohwang/p/3500645.html
		//data:image\/png;base64,
		//http://www.cnblogs.com/xiaoxuetu/p/3467613.html
		//$ret=preg_match_all("/<img[^>]{1,100}src=\"data:image\/(.*?);base64,(.*?)\"[^>]+>/",$content,$temps);//正则解析不正
		//http://www.cnblogs.com/chenjungang/p/Raindrop.html
	function replaceDataImage($content,$fileName)
	{
		$i=0;
		
		$beginStr="<img src=\"data:";
		$endStr="/>";
		
		$beginPos=strpos($content,$beginStr,0);
		while($beginPos!=false)
		{
			$endPos=strpos($content,$endStr,$beginPos+1);
			if($endPos!=false && $endPos>$beginPos)
			{
				$endPos+=strlen($endStr);
				$imgTagStr=substr($content,$beginPos,$endPos-$beginPos);
				$type=subStringBetweenTag($imgTagStr,"/",";");
				$type=$type?$type:"jpg";
				$base64Data=subStringBetweenTag($imgTagStr,",","\"");
				
				$imgFilePathName="articles/".$fileName."_".$i.".".$type;
				
				$host=$_SERVER ['HTTP_HOST'];
				$imgUrl="http://".$host."/reading/".$imgFilePathName;
				$newImgTagStr="<img src=\"$imgUrl\">";
				$content=substr_replace($content,$newImgTagStr,$beginPos,$endPos-$beginPos);

				$imgFilePathName="../".$imgFilePathName;
				$imgData=base64_decode($base64Data);
				funSaveFile($imgFilePathName,$imgData);
				
				$i++;
				
				$endPos=$beginPos+strlen($newImgTagStr);
				
				$beginPos=strpos($content,$beginStr,$endPos);
			}
			else
			{
				break;
			}
		}
		
		return $content;
	}
	
    function parseArticleContent(&$url,$results)
    {
        $content=NULL;
        
		if(strpos($url,"9tech.cn")!=false)
        {
            $content=funCapture9TechArticle($url,$results);
        }
        else if(strpos($url,"aqee.net")!=false || strpos($url,"vaikan.com")!=false)
        {
            $content=funCaptureAqeeArticle($url,$results);
        }
		else if(strpos($url,"www.tuicool.com")!=false)
        {
            $content=funCaptureTuicoolArticle($url,$results);
        }
		else if(strpos($url,"www.cnblogs.com")!=false)
        {
            $content=funCaptureCnblogsArticle($url,$results);
        }
        else if(strpos($url,"news.cnblogs.com")!=false)
        {
            $content=funCaptureCnblogsNewsArticle($url,$results);
        }
        else if(strpos($url,"kb.cnblogs.com")!=false)
        {
            $content=funCaptureCnblogsKbArticle($url,$results);
        }
        else if(strpos($url,"cocos2dev.com")!=false)
        {
            $content=funCaptureCocos2devArticle($url,$results);
        }
        else if(strpos($url,"ifanr.com")!=false)
        {
            $content=funCaptureIfanrArticle($url,$results);
        }
        else if(strpos($url,"itsoku.com")!=false)
        {
            $content=funCaptureItsokuArticle($url,$results);
        }
        else if(strpos($url,"www.csdn.net")!=false)
        {
            $content=funCaptureCSDNArticle($url,$results);
        }
        else if(strpos($url,"blog.csdn.net")!=false)//curlexec
        {
            $content=funCaptureCSDNBlogArticle($url,$results);
        }
        else if(strpos($url,"cocoachina.com")!=false)
        {
            $content=funCaptureCocoaChinaArticle($url,$results);
        }
        else if(strpos($url,"eoe.cn")!=false)
        {
            $content=funCaptureEoeArticle($url,$results);
        }
        else if(strpos($url,"yesky.com")!=false)
        {
            $content= funCaptureYeskyArticle($url,$results);
        }
        else if(strpos($url,"apkway.com")!=false)
        {
            $content=funCaptureApkwayArticle($url,$results);
        }
        else if(strpos($url,"android-study.net")!=false)
        {
            $content=funCaptureAndroidStudyArticle($url,$results);
        }
        else if(strpos($url,"w3cfuns.com")!=false)
        {
            $content=funCaptureW3cfunsArticle($url,$results);
        }
        else if(strpos($url,"ipadown.com")!=false)
        {
            $content=funCaptureIpadownArticle($url,$results);
        }
        else if(strpos($url,"gamerboom.com")!=false)
        {
            $content=funCaptureGamerboomArticle($url,$results);
        }
        else if(strpos($url,"jobbole.com")!=false)
        {
            $content=funCaptureJobboleArticle($url,$results);
        }
        else if(strpos($url,"shushao.com")!=false)//curlexec
        {
            $content=funCaptureShushaoArticle($url,$results);
        }
        else if(strpos($url,"36kr.com")!=false)
        {
            $content=funCapture36krArticle($url,$results);
        }
        else if(strpos($url,"gamelook.com.cn")!=false)
        {
            $content=funCaptureGamelookArticle($url,$results);
        }
        else if(strpos($url,"163.com")!=false)
        {
			//http://tech.163.com/special/chuangyehui11/
			//http://tech.163.com/14/0425/08/9QLPP0VR00094O8M.html
			//http://tech.163.com/special/aliyt/
			if(!isStringEndWith($url,"html"))
			{
				$url=preg_match("/<a\s+href=\"(.*?)\".*?>\[详细\]<\/a>/i",$results,$temp) ? $temp[1]:"";
			}
			else
			{
            	$content=funCaptureTech163Article($url,$results);
			}
        }
        else if(strpos($url,"woshipm.com")!=false)
        {
            $content=funCaptureWoshipmArticle($url,$results);
        }
        else if(strpos($url,"cnbeta.com")!=false)
        {
            $content=funCaptureCnbetaArticle($url,$results);
        }
        else if(strpos($url,"qianduan.net")!=false)
        {
            $content=funCaptureQiandunArticle($url,$results);
        }
        else if(strpos($url,"jbxue.com")!=false)
        {
            $content=funCaptureJbxueArticle($url,$results);
        }
        
        return $content;
    }
	
    function funCaptureAqeeArticle($url,$results)
    {
		//notice: [\s\S]+? makes result corect. and [\s\S]+ not work.
		$content = preg_match("/<div[^>]+class=\"entry-content\">([\s\S]+?)<\/div><!-- .entry-content -->/i",$results,$temp) ? $temp[1]:"";
        $content=preg_replace("/<img[^>]+?data-original=\"([^\"]+)\"[^>]*>/is","<img src=\"\\1\"/>", $content);
		//$content0 = preg_match("/<div[^>]+class=\"post_source\">([\s\S]+?)<\/div>/i",$results,$temp) ? $temp[1]:"";
		//$content=$content.$content0;
		$content=preg_replace("/<div class=\"note\">[\s\S]+?<\/div>/i","", $content);

		return array($content);
    }

	function funCaptureCSDNArticle($url,$results)
	{
		//content
		$content = preg_match("/<div class=\"con news_content\">([\s\S]+)<\/div>\s*<div class=\"(share|guide)\"/i",$results,$temp) ? $temp[1]:"";
		
        //firest replace the special texts,then replace empty tags
		//$content=preg_replace("/本文为CSDN[^<]*?</i","<",$content);
		//http://www.csdn.net/article/2014-03-24/2818940-xunyiwenyao-webapp-interview
		$content=preg_replace("/<p class=\"copyright\">.*?<\/p>/i","",$content);
		
		//$content=preg_replace("/<hr><p>.*?\"http:\/\/club\.csdn\.net\/cmdn\/\".*?\"http:\/\/weibo\.com\/cmdnclub\".*?<\/p>/i","",$content);

        $content=preg_replace("/(（|\()(编译|文|翻译)(\/|：).*?(责编|审核|校审|审校)(\/|：).*?(）|\))/","",$content);
		$content=preg_replace("/（责编.*?）/","",$content);
        $content=preg_replace("/（.*?\/文）/","",$content);

		//special business page_nav,每一篇都会尝试解析导航
		$pageUrlXml="";
		$pageNavReg="/<div(.*?)class=\"page_nav\"[^>]*?>([\s\S]*?)<\/div>/i";
		$ret=preg_match_all($pageNavReg, $content, $temp);
		if($ret)
		{
			$content=preg_replace($pageNavReg, "", $content);
			$ret=preg_match_all("/<a(.*?)href=\"(.*?)\"(.*?)>(.*?)<\/a>/i", $temp[2][0], $value);
			if($ret)
			{
				$pageUrlXml="<pageUrls>";
				foreach($value[2] as $pageUrl)
				{
					$pageUrlXml=$pageUrlXml."<pageUrl>".$pageUrl."</pageUrl>";
				}
				$pageUrlXml=$pageUrlXml."</pageUrls>";
			}
		}
		
		return array($content,$pageUrlXml);
	}

	function funCaptureCSDNBlogArticle($url,$results)
	{
		//content
		$content = preg_match("/<div[^>]+class=\"article_content\"[^>]*>([\s\S]+?)<\/div>\s*<!-- Baidu Button BEGIN -->/i",$results,$temp) ? $temp[1]:"";

		//http://blog.csdn.net/reili/article/details/12244987
		$content = preg_replace("/<img[^>]+src=\"http:\/\/common\.cnblogs\.com\/images\/copycode\.gif\"[^>]*>/i","",$content);
        
        //其中的链接是相对链接，需要被处理
        $content2 = preg_match("/<div class=\"article_next_prev\">([\s\S]+?)<\/div>/i",$results,$temp) ? $temp[1]:"";

        return array($content.$content2);
	}
	
	function funCaptureCocoaChinaArticle($url,$results)
	{
		$content = preg_match("/(<div[^>]+id=\"article\"[^>]*>[\s\S]*)<div[^>]+class=\"arc-opt\"/i",$results,$temp) ? $temp[1]:"";
        if($content=="")
        {
            //the second article style
            $content = preg_match("/(<div[^>]+class=\"text_font\"[^>]*>[\s\S]*)<div[^>]+class=\"text_top_step\"/i",$results,$temp) ? $temp[1]:"";
        }
		
		//http://www.cocoachina.com/gamedev/designer/2014/0508/8348.html
		$content=preg_replace("/<div style=\"border-top:1px solid #ccc;margin-top:30px;line-height:1.8;padding-top:10px\">[\s\S]+?<\/div>/i","", $content);
			
		$provenance="";
		$originUrl="";
        $originName="";
		//$ret=preg_match("/<(p|div)>(来源：|转自)(<a href=\"(.*?)\"[^>]*>)?(<span[^>]*>)?(.*?)(<\/span>)?(<\/a>)?<\/\\1>/i",$content,$temp);
		$ret=preg_match("/<(p|div)>(来源：|转自)(<a href=\"(.*?)\"[^>]*>)?(<span[^>]*>)?(.*?)(<\/span>)?(<\/a>)?<\/\\1>/i",$content,$temp);
        if($ret)
        {
			//http://www.cocoachina.com/gamedev/designer/2014/0508/8348.html
			//http://www.cocoachina.com/gamedev/misc/2013/1129/7442.ht
			//http://www.cocoachina.com/gamedev/misc/2014/0525/8540.html
			//http://www.cocoachina.com/gamedev/misc/2014/0522/8512.html
			
			$originUrl=$temp[4];
            $originName=$temp[6];
			
			//http://www.cocoachina.com/gamedev/misc/2014/0530/8617.html
			$originName=preg_replace("/<span style[^>]+>/i", "<span>", $originName);
		}
		
		if($originName!="" || $originUrl!="")
        {
			if($originUrl=="")
			{
				$originUrl=$url;
			}
            $provenance="<provenance url='".$originUrl."' name='".$originName."'/>";
			$content=preg_replace("/<(p|div)>(来源：|转自).*?<\/\\1>/i", "", $content);
        }
		
        return array($content,$provenance);
	}
	
	function funCaptureEoeArticle($url,$results)
	{
		//http://news.eoe.cn/17893.html
		$content = preg_match("/<div class=\"ue-new-body-cont\"[^>]*>([\s\S]*?)<div class=\"support_collect\">/i",$results,$temp) ? $temp[1]:"";
		
        //$content=preg_replace("/（编译.*?）/i","", $content);
        //广告图片 http://a1.eoe.cn/www/home/201309/11/f7fb/522fc26435eee.jpg
        //$content=preg_replace("/<img[^>]+src=\".*?f7fb\/522fc26435eee\.jpg\"[^>]*>/i","", $content);

        return array($content);
	}
	
	function funCaptureYeskyArticle($url,$results)
	{
        //http://dev.yesky.com/167/35358167.shtml
        $content = preg_match("/<div[^>]+class=\"article\">([\s\S]*)<div[^>]+class=\"editor/i",$results,$temp) ? $temp[1]:"";
        if($content=="")
        {
            $content = preg_match("/<div[^>]+class=\"article\">([\s\S]*)<div[^>]+id=\"numpage\">/i",$results,$temp) ? $temp[1]:"";
        }

        //删除一些不需要的内容 http://image.tianjimedia.com/uploadImages/2013/214/56JYSY7GQ2P6.jpg
        $content=preg_replace("/<img[^>]{1,300}src=\"(?!data:image)http:\/\/image\.tianjimedia\.com\/uploadImages[^\"]+56JYSY7GQ2P6\.jpg\"[^>]*\/?>/i","", $content);
        $content=preg_replace("/<a[^>]+href=\"http:\/\/e\.weibo\.com\/yeskyep\".*?<\/a>/i","", $content);
        $content=preg_replace("/<p[^>]*><br><strong><\/strong><\/p>/i","", $content);
        
        //多页,每一页地址都组装进来,只要顺序对，客户端会排重.先只保证第一页中的分页顺序是地的。
        $pageUrlXml="";
		$pageNavReg="/<div[^>]+class=\"pages\"[^>]*>([\s\S]*?)<\/div>/i";
		$ret=preg_match($pageNavReg, $results, $temp);
		if($ret)
		{
            $pageNavContent=$temp[1];
			$ret = preg_match_all("/<a[^>]+href=\"([^\"]+)\"[^>]*?>\\d+<\/a>/i", $pageNavContent, $temp);
			if($ret)
			{
                $pos=strrpos($url,"/");
                $urlBegin=substr($url,0,$pos+1);
                
                $pageUrlXml="<pageUrls>";
                $pageUrlXml=$pageUrlXml."<pageUrl>".$url."</pageUrl>";
				foreach($temp[1] as $uri)
				{
                    $pageUrl = $urlBegin.$uri;
					$pageUrlXml=$pageUrlXml."<pageUrl>".$pageUrl."</pageUrl>";
				}
				$pageUrlXml=$pageUrlXml."</pageUrls>";
			}
            
            $content=preg_replace("/<div[^>]+class=\"(pages|jumpall)\">[\s\S]*?<\/div>/i","", $content);
		}
		
        return array($content,$pageUrlXml);
	}

	/*
	function funCaptureApkwayArticle($url,$results)
	{
		$content = preg_match("/<div id=\"diycontenttop\" class=\"area\"><\/div>([\s\S]*?)<div id=\"diycontentbottom\" class=\"area\"><\/div>/isU",$results,$temp) ? $temp[1]:"";
        $content=preg_replace("/<ignore_js_op>/","",$content);
		$content=preg_replace("/<div class=\"tip tip_4 aimg_tip[\s\S]*?<\/ignore_js_op>/","",$content);
        
        $content=preg_replace("/<img[\s\S]*?file=\"([^\"]+)\"[\s\S]*?>/is","<img src=\"\\1\"/>", $content);

		return array($content);
	}
	*/

	function funCaptureAndroidStudyArticle($url,$results)
	{
		$content = preg_match("/<ul class=\"TOP_ARTICLES_info\">([\s\S]*?)<table[^>]*class=\"midtop\">/isU",$results,$temp) ? $temp[1]:"";
		
		$content=preg_replace("/<b>本文为Android开发学习网.*?<\/b>/i", "", $content);
		$content=preg_replace("/<table[\s\S]*<td>/i", "", $content);
		$content=preg_replace("/<\/td>[\s\S]*<\/table>/i", "", $content);
		$content=preg_replace("/<p>(<br\/?>)*(&nbsp;)*(<br\/?>)*<\/[p]>/i", "", $content);
		
		return array($content);
	}

    function funCaptureCnblogsArticle($url,$results)
    {
        $content = preg_match("/<div[^>]+id=\"cnblogs_post_body\"[^>]*?>([\s\S]*)<\/div>\s*<div[^>]+id=\"MySignature\"/i",$results,$temp) ? $temp[1]:"";
        
        //删除尾部无用的内容<p><span id="blog_ad_off" class="pub_index">hide</span></p>
        $content=preg_replace("/<p[^>]*?><span[^>]+id=\"blog_ad_off\"[^>]*?>.*?<\/span><\/p>/i", "", $content);

        //删除特有的但客户端不需要的内容
        $content=preg_replace("/<img[^>]{1,300}class=\"(code_img_opened|code_img_closed)\"[^>]*>/i", "", $content);
        $content=preg_replace("/<span[^>]+class=\"cnblogs_code_collapse\".*?<\/span>/i", "", $content);
        $content=preg_replace("/<div[^>]+class=\"(cnblogs_code_toolbar|diggit|buryit|contents_info)\".*?<\/div>/i", "", $content);
        $content=preg_replace("/<p[^>]+id=\"PSignature\".*?<\/p>/i", "", $content);
 
        //删除代码段头部的工具栏
        //http://www.cnblogs.com/xyzlmn/p/3418856.html
        $content=preg_replace("/<div class=\"bar\">[\s\S]+?(<\/div>\s*)+/i", "", $content);
		
		//影响了http://www.cnblogs.com/nerohwang/p/3500645.html
        //src="data:image/png;base64 may appear error
        //$content=preg_replace("/<img[^>]{1,300}src=\"(?!data:image)[^\"]+(None|ExpandedBlockStart|ContractedBlock|ExpandedBlockEnd|dot|InBlock|ExpandedSubBlockStart|ContractedSubBlock|ExpandedSubBlockEnd|ContractedSubBlockEnd)\.gif\"[^>]*>/i", "", $content);
		
        //http://www.cnblogs.com/viczhang/p/3306949.html
        $content=preg_replace("/<img[^>]{1,300}src=\"(?!data:image)http:\/\/haroldphp\.iteye\.com\/images\/(icon_copy\.gif|icon_star\.png|spinner\.gif)\"[^>]*>/i", "", $content);

        $content=preg_replace("/<p>请尊重作者的劳动成果.*?<\/p>/i", "", $content);
        
        //http://www.cnblogs.com/HKUI/p/3244779.html 需要style
        //$content=preg_replace("/<style>[\s\S]*?<\/style>/i", "", $content);
        $content=preg_replace("/>本文版权归作者所有.*?</i", "><", $content);
        //view-source:http://www.cnblogs.com/xdream86/p/3309345.html 本文由海水的味道编译整理，转载请注明译者和出处，请勿用于商业用途！
        $content=preg_replace("/>[^<]*转载请注明.*?</i", "><", $content);
		
		//http://www.cnblogs.com/jhzhu/p/3672001.html 内容中嵌入了<html></html>
		$content=preg_replace("/<link[^<]*>/i", "", $content);
		
        
        //http://www.cnblogs.com/scorpiozj/p/3361162.html 内容中带了一个iframe广告
		
		//http://www.cnblogs.com/wangxingliu/p/3489893.html
		$content=preg_replace("/<object id=\"ZeroClipboardMovie[\s\S]+?<\/object>/i", "", $content);
		
        return array($content);
    }

    function funCaptureCnblogsNewsArticle($url,$results)
    {
        $content = preg_match("/<div[^>]+id=\"news_body\"[^>]*?>([\s\S]*)<\/div><!--end: news_body -->/i",$results,$temp) ? $temp[1]:"";

        //去掉不耐看的logo
        $content = preg_replace("/<a[^>]+><img[^>]+class=\"topic_img\"[^>]*><\/a>/i","",$content);
        
        //去掉尾部的签名  。(竹子)</p>
        $content = preg_replace("/\(.*?\)<\/p>\s*$/i","</p>",$content);
        
        /*
        $comefrom = preg_match("/(<div[^>]+id=\"come_from\"[^>]*?>[\s\S]*<\/div>)<!--end: come_from -->/i",$results,$temp) ? $temp[1]:"";
        $script = preg_match("/(<script[^>]+>[^<]+(\.attr[^<]+)+<\/script>)/i",$results,$temp) ? $temp[1]:"";
        */
        
        $provenance="";
        $ret=preg_match("/<div[^>]+id=\"come_from\"[^>]*>[\s\S]*?<a[^>]+id=\"(.*?)\"[^>]+>(.*?)<\/a>/i",$results,$temp);
        if($ret)
        {
            $tempId=$temp[1];
            $originName=$temp[2];
            
            /*
             $("#link_source1").attr("href", "http://www.ifanr.com/343394?utm_source=feedly");
             */
            $originUrl = preg_match("/\(\"#".$tempId."\"\)\.attr\(\"href\", \"(.*?)\"\);/i",$results,$temp) ? $temp[1]:"";
            
            $provenance="<provenance url='".$originUrl."' name='".$originName."'/>";
        }
        else
        {
            /*
             <div id="come_from">
             来自: 扬子晚报
             </div><!--end: come_from -->
             */
            $provenance="";
            $ret=preg_match("/<div[^>]+id=\"come_from\"[^>]*>\s*来自:\s*(.*?)\s*<\/div>/i",$results,$temp);
            if($ret)
            {
                $originName=$temp[1];
                $provenance="<provenance url='".$url."' name='".$originName."'/>";
            }
        }

        return array($content,$provenance);//.$comefrom.$script;
    }
	
	function funCaptureCnblogsKbArticle($url,$results)
	{
        $content = preg_match("/<div[^>]+id=\"ArticleCnt\"[^>]*?>([\s\S]*)<\/div>\s*<\/div>\s*<div[^>]+id=\"content_bottom\"[^>]*>/i",$results,$temp) ? $temp[1]:"";
        
        return array($content);
    }

    function funCaptureW3cfunsArticle($url,$results)
    {
        $content = preg_match("/<div[^>]+class=\"d\"[^>]*?>([\s\S]*<\/table>)<\/div>/isU",$results,$temp) ? $temp[1]:"";
		
        //http://www.w3cfuns.com/article-1139-1.html
        $content=preg_replace("/<img[^>]+?zoomfile=\"([^\"]+)\"[^>]*>/is","<img src=\"\\1\"/>", $content);
        
        //special business page_nav
        /*
         <div class="pg"><strong>1</strong><a href="article-1097-2.html">2</a><a href="article-1097-3.html">3</a><label><input type="text" name="custompage" class="px" size="2" title="输入页码，按回车快速跳转" value="1" onkeydown="if(event.keyCode==13) {window.location='portal.php?mod=view&aid=1097&amp;page='+this.value; doane(event);}" /><span title="共 3 页"> / 3 页</span></label><a href="article-1097-2.html" class="nxt">下一页</a></div>
         */
		$pageUrlXml="";
		$pageNavReg="/<div[^>]+class=\"pg\"[^>]*?>([\s\S]*?)<\/div>/i";
		$ret=preg_match_all($pageNavReg, $results, $temp);
		if($ret)
		{
			$pageCount = preg_match("/<span[^>]*?> \/ (\\d+) 页<\/span>/i", $temp[1][0], $temp)?$temp[1]:0;
			if($pageCount>0)
			{
                $urlFormat=preg_replace("/^(.*?)-\d+\.(.*?)$/i", "\\1-%d.\\2", $url);
                
                $pageUrlXml="<pageUrls>";
				for($i=1;$i<=$pageCount;$i++)
				{
                    $pageUrl = sprintf($urlFormat,$i);
					$pageUrlXml=$pageUrlXml."<pageUrl>".$pageUrl."</pageUrl>";
				}
				$pageUrlXml=$pageUrlXml."</pageUrls>";
			}
		}
        
		return array($content,$pageUrlXml);
    }

    function funCaptureIpadownArticle($url,$results)
    {
        $content = preg_match("/<div[^>]+class=\"detail\"[^>]*?>([\s\S]*)<\/div>\s*<div[^>]+class=\"fr\"/i",$results,$temp) ? $temp[1]:"";
		
		//http://news.ipadown.com/36893
		/*
		<script type="text/javascript">var cpro_id = "u1508567";</script>
		<script src="http://cpro.baidustatic.com/cpro/ui/c.js" type="text/javascript"></script>		*/
		//尾部无用的script
        $content=preg_replace("/<script.*?script>/i", "", $content);
		
		//http://news.ipadown.com/31867
		//$content=preg_replace("/data-ke-src=\".*?\"/i", "", $content);

        //删除尾部无用的内容
        $content=preg_replace("/(<br\s*\/>)*\(完\)\s*$/i", "", $content);
        $content=preg_replace("/\([^\)]+\)<\/p>$/i", "</p>", $content);
		//去掉尾部的签名   （文/枣枣）</p>
        $content = preg_replace("/（[^）]*）<\/p>\s*$/i","</p>",$content);
		
		//http://news.ipadown.com/32468
		//http://news.ipadown.com/37498
		$provenance="";
        $ret=preg_match("/<p>来源：<a[^>]+href=\"(.*?)\"[^>]*>(.*?)<\/a><\/p>/i",$results,$temp);
        if($ret)
        {
            $originUrl=$temp[1];
            $originName=$temp[2];
            $provenance="<provenance url='".$originUrl."' name='".$originName."'/>";
			$content=preg_replace("/<p>来源：<a[^>]+>.*?<\/a><\/p>/i", "", $content);
        }

        return array($content,$provenance);
    }

    function funCaptureGamerboomArticle($url,$results)
    {
        //（<span style="color: #ff0000;">本文为游戏邦/gamerboom.com编译，拒绝任何不保留版权的转载，如需转载请联系：游戏邦</span>）</p>
        $content = preg_match("/<div[^>]+class=\"cnt_body\"[^>]*?>([\s\S]*?本文为游戏邦.*?<\/p>)/i",
                              $results,$temp) ? $temp[1]:"";
        
        //删除尾部无用的内容
        $content=preg_replace("/（.*?本文为游戏邦\/gamerboom.com编译，拒绝任何不保留版权的转载，如需转载请联系：游戏邦.*?）/i", "", $content);
        
		return array($content);
    }

    function funCaptureJobboleArticle($url,$results)
    {
        /*
         //http://blog.jobbole.com/48832/  在第一个p后面插入了一个div
         $content = preg_match("/<div class=\"entry\">[\s\S]*?(<p>[\s\S]*?)<br\/>\s*<(!-- )?div([^>]+id=\"ad1\")?/i",$results,$temp) ? $temp[1]:"";
         if($content=="")
         {
         $content = preg_match("/div[^>]+id=\"ad1\">[\s\S]*?<\/div( --)?>([\s\S]*?)<(!-- )?div([^>]+id=\"ad1\")?/i",$results,$temp) ? $temp[2]:"";
         }
         
         $content=preg_replace("/<br\/( --)?>/i", "", $content);
         */
        
        //http://blog.jobbole.com/51135/
        $match="/<!-- BEGIN \.entry -->\s+<div class=\"entry\">([\s\S]*?)<\/div>\s+<!-- END \.entry -->/i";
        $content = preg_match($match,$results,$temp)?$temp[1]:"";

        //http://blog.jobbole.com/47726/
        $content=preg_replace("/<!--[\s\S]*?-->/i", "", $content);
        
        //本文由 伯乐在线 - Lex Lian 翻译自 Will Oremus。欢迎加入技术翻译小组。转载请参见文章末尾处的要求。
        $content=preg_replace("/^\s*<b>[\s\S]*?<\/b>(<br\/>\s*)*/i", "", $content);
        
        //【感谢 lexlian 的热心翻译。如果其他朋友也有不错的原创或译文，可以尝试提交到伯乐在线。】
        $content=preg_replace("/^\s*<p>【[\s\S]*?】<\/p>/i", "", $content);
        
        //广告图片
        $content=preg_replace("/<p><a target=\"_blank\" rel=\"nofollow\"[^>]*><img[^>]*><\/img><\/a><\/p>/i", "", $content);
        
        //还有一些广告 http://blog.jobbole.com/52069/
        $content=preg_replace("/<div id=\"ad1\">[\s\S]*?<\/div>/i", "", $content);
        
        
        // 转载必须在正文中标注并保留原文链接、译文链接和译者等信息。]
        $content=preg_replace("/\[[^\]]*?[^\]]*?\](<br\/>\s*)*/i", "", $content);
        $content=preg_replace("/(<br\/>\s*)*$/i", "", $content);

		return array($content);
    }

    function funCaptureShushaoArticle($url,$results)
    {
        $content = preg_match("/<div[^>]+class=\"Articles_Ctext\"[^>]*>([\s\S]*)<div[^>]+class=\"Articles_Notes\"/i",
                              $results,$temp) ? $temp[1]:"";

        $content=preg_replace("/<img[^>]+src=\".*?weixin.*?\"[^>]*>/i", "", $content);
        
        $content=preg_replace("/<div[^>]+class=\"Bottom_text_title\"[^>]*>[\s\S]*?<\/div>/i", "", $content);
        $content=preg_replace("/<div[^>]+class=\"Bottom_text\"[^>]*>[\s\S]*?<\/div>/i", "", $content);
        
        //special business page_nav
        /*
         <div id="pageBreakNavigation">
         <span class="pagination"><span>首页</span><span>上页</span><strong><span>1</span></strong><strong><a href="/articles/item/210724-app?start=1" title="2">2</a></strong><a href="/articles/item/210724-app?start=1" title="下页">下页</a><a href="/articles/item/210724-app?start=1" title="末页">末页</a></span>
             <div class="pageBreakCounter">
             (第1页 共2页)
             </div>
         </div>
         */
		$pageUrlXml="";
		$pageNavReg="/<div[^>]+id=\"pageBreakNavigation\"[^>]*>([\s\S]*?)<\/div>/i";
		$ret=preg_match($pageNavReg, $content, $temp);
		if($ret)
		{
            $pageNavContent=$temp[1];
			$content=preg_replace($pageNavReg, "", $content);
			$ret=preg_match_all("/href=\"(.*?)\"/i", $pageNavContent, $temp);
			if($ret)
			{
				$pageUrlXml="<pageUrls>";
                //因为在正文中没有第一页的地址，这里加上
                $pageUrlXml=$pageUrlXml."<pageUrl>".$url."</pageUrl>";
				foreach($temp[1] as $pageUrl)
				{
                    $pageUrl=format_url($pageUrl,"http://www.shushao.com/");
					$pageUrlXml=$pageUrlXml."<pageUrl>".$pageUrl."</pageUrl>";
				}
				$pageUrlXml=$pageUrlXml."</pageUrls>";
			}
		}
        
        return array($content,$pageUrlXml);
    }

    function funCapture36krArticle($url,$results)
    {
        $content = preg_match("/<section[^>]+class=\"article\"[^>]*>([\s\S]*)<\/section>\s*<section class=\"single-post-share cf\">/i",$results,$temp) ? $temp[1]:"";
        
		if($content=="")
		{
 			//http://www.36kr.com/p/205823.html 转 http://www.36kr.com/topics/889
			$content = preg_match("/<div[^>]+class=\"body\"[^>]*>([\s\S]*?)<\/div>\s*<div[^>]+class=\"tools\"/i",
									  $results,$temp) ? $temp[1]:"";
		}
  
        return array($content);
    }

    function funCaptureGamelookArticle($url,$results)
    {
        $content = preg_match("/<div[^>]+class=\"entry entry-content\"[^>]*>([\s\S]*?)<h3[^>]+class=\"related_post_title\"/i",
                              $results,$temp) ? $temp[1]:"";
        
        $content=preg_replace("/<div[^>]+id=\"ckepop\"[^>]*>[\s\S]*?<\/div><\/br>/i", "", $content);
        
		return array($content);
    }
	
	function funCaptureTech163Article($url,$results)
    {
		$content = preg_match("/<div[^>]+id=\"endText\"[^>]*>([\s\S]*?)<div[^>]+class=\"ep-source cDGray\"/i",
                              $results,$temp) ? $temp[1]:"";
        if($content!="")
        {
			//http://tech.163.com/14/0428/06/9QTA4CB6000915BF.html
			//空视频 <embed id="xunlei_com_thunder_helper_plugin_d462f475-c18e-46be-bd10-327458d045bd" type="application/thunder_download_plugin" height="0" width="0" />
			$content=preg_replace("/<embed id=\"xunlei_com_thunder_helper_plugin_d462f475-c18e-46be-bd10-327458d045bd\" type=\"application\/thunder_download_plugin\" height=\"0\" width=\"0\" \/>/i", "", $content);
						
            //广告
            $content=preg_replace("/<div[^>]+class=\"gg200x300\"[^>]*>[\s\S]*?<\/div>/i", "", $content);
            //去掉尾部的签名   （文/枣枣）</p>
            $content = preg_replace("/（[^）]*）<\/p>\s*$/i","</p>",$content);
            $content = preg_replace("/(&nbsp;)*\([^\)]+\)<\/p>(<p>)?\s*$/i","</p>",$content);
            $content = preg_replace("/(&nbsp;)*（[^）]+）<\/p>(<p>)?\s*$/i","</p>",$content);

            $content = preg_replace("/<p><strong>.*?转载请注明出处<\/strong><\/p>/i","",$content);
            
            //http://tech.163.com/13/0910/06/98D1POBF000915BD.html
            $ret=preg_match("/<!-- 图集开始.*?-->([\s\S]*)<!-- 图集结束.*?-->/i",$content,$temp);
            if($ret)
            {
                $find = array(":","/","?");
                $fileName=str_ireplace($find,"_",$url);
                $filePathName="../articles/".$fileName."_attach.html";
                funSaveFile($filePathName, $temp[1]);
                
                $host=$_SERVER ['HTTP_HOST'];
                $tempStr="<p style=\"text-align:center;\"><a href=\"http://".$host."/reading/articles/".$fileName."_attach.html\">点击查看图集</a></p>";
                $content = preg_replace("/<!-- 图集开始 [^>]+ -->([\s\S]*)<!-- 图集结束 [^>]+ -->/i",$tempStr,$content);
            }
            
            $content = preg_replace("/<p><\/div>/i","<p>",$content);
        }
        
        /*
        else
        {
            //http://tech.163.com/special/iphone5s/
            $content = preg_match("/<table[^>]+class=\"bg_white\"[^>]*>[\s\S]*?<\/table>/i",$results,$temp) ? $temp[0]:"";
            if($content!="")
            {
                $content = preg_replace("/<tr>\s*<\/table>/i","</table>",$content);
            }
            else
            {
                $ret = preg_match("/<!-- 图集主体 start -->([\s\S]*)<!-- 图集主体 end -->/i",$results,$temp);
                if($ret)
                {
                    $find = array(":","/","?","#","=");
                    $fileName=str_ireplace($find,"_",$url);
                    $filePathName="../articles/".$fileName."_attach.html";
                    funSaveFile($filePathName, $temp[1]);
                    
                    $host=$_SERVER ['HTTP_HOST'];
                    $tempStr="<p style=\"text-align:center;\"><a href=\"http://".$host."/reading/articles/".$fileName."_attach.html\">点击查看图集</a></p>";
                    $content = $tempStr;
                }
            }
        }
         */
        
        return array($content);
    }

    function funCaptureWoshipmArticle($url,$results)
    {
		//http://www.woshipm.com/pd/1398.html
		$content = preg_match("/<div[^>]+class=\"con_txt clx\"[^>]*>([\s\S]*?)<div[^>]+class=\"wumii-hook\"/i",
                              $results,$temp) ? $temp[1]:"";

		if($content=="")
		{
			$content = preg_match("/<div[^>]+class=\"article_content\"[^>]*>([\s\S]*?)<div[^>]+class=\"wumii-hook\"/i",
                              $results,$temp) ? $temp[1]:"";
			$content = preg_replace("/<div[^>]+class=\"article-tag\"[^>]*>[\s\S]*?<\/div>/i", "", $content);
		}
		
		$provenance="";
		$originUrl="";
        $originName="";
		$ret=preg_match("/<p[^>]*>(原文地址：|文章来源：|原文来自：)<a href=\"(.*?)\"[^>]*>(.*?)<\/a><\/p>/i",$content,$temp);
        if($ret)
        {
			//http://www.woshipm.com/ucd/87526.html
			$originUrl=$temp[2];
			$originName=$temp[3];
		}
		else
		{
			$ret=preg_match("/<p[^>]*>原文地址：(.*?)<\/p>/i",$content,$temp);
			if($ret)
			{
				//http://www.woshipm.com/ucd/86870.html
				$originUrl=$temp[1];
			}
			else
			{
				$ret=preg_match("/<p[^>]*>(转自：|文章来自：|来源:|来自：|来源：)(.*?)<\/p>/i",$content,$temp);
				if($ret)
				{
					//http://www.woshipm.com/pmd/87046.html
					$originName=$temp[2];
				}
			}
		}
		
		if($originName!="" || $originUrl!="")
        {
			if($originUrl=="")
			{
				$originUrl=$url;
			}
            $provenance="<provenance url='".$originUrl."' name='".$originName."'/>";
			$content=preg_replace("/<p[^>]*>(转自|文章来自|来源|来自|原文地址|原文来自|文章来源).*?<\/p>/i", "", $content);
        }
		
		
		$content2 = preg_match("/<div[^>]+class=\"c_news_list\"[^>]*>([\s\S]*?)<\/div>/i",
                              $results,$temp) ? $temp[1]:"";
		if($content2!="")
		{
			$content2="<h2>相关阅读</h2>".$content2;
		}
		
        return array($content,$content2,$provenance);
    }
	
	function funCaptureCnbetaArticle($url,$results)
    {
        $content0 = preg_match("/<div[^>]+class=\"introduction\"[^>]*>([\s\S]*?)<\/div>\s*<div[^>]+class=\"content\"/i",
                              $results,$temp) ? $temp[1]:"";
        
        //去掉不耐看的类别图片
        $content0 = preg_replace("/<div[^>]*><a[^>]*><img[^>]*src=\"http:\/\/static\.cnbetacdn\.com\/topics\/.+?\.(gif|png)\"\s*\/><\/a><\/div>/i","",$content0);
		
        //去掉感谢x的投递
        //http://www.cnbeta.com/articles/260085.htm
        $content0 = preg_replace("/<p><strong>感谢[\s\S]*?的投递<\/strong><br\/><\/p>/i","",$content0);
        

        $content = preg_match("/<div[^>]+class=\"content\"[^>]*>([\s\S]*?)<div[^>]+id=\"googleAd_afc\"/i",
                              $results,$temp) ? $temp[1]:"";
							  
		//http://www.cnbeta.com/articles/266494.htm
		$content = preg_replace("/<p[^>]+weiphone_src=\"(.*?)\"[\s\S]*?<\/p>/i","<video src='\\1'></video>",$content);

        //http://www.cnbeta.com/articles/255936.htm
		//http://www.cnbeta.com/articles/257431.htm
        //$content=preg_replace("/<script[^>]+src=\"http:\/\/player\.ooyala\.com\/iframe\.js#pbid=dcc84e41db014454b08662a766057e2b&amp;ec=(.*?)\"><\/script>/i", "<video src=\"http://player.ooyala.com/player/iphone/\\1.m3u8\"></video>", $content);
		$content=preg_replace("/<script[^>]+src=\"http:\/\/player\.ooyala\.com\/iframe\.js#.*?ec=(.+?)(\"|&).*?<\/script>/i", "<video src=\"http://player.ooyala.com/player/iphone/\\1.m3u8\"></video>", $content);
		
		//http://www.cnbeta.com/articles/287965.htm
		$content=preg_replace("/<script[^>]+src=\"http:\/\/v.ku6vms.com\/phpvms\/player\/js\/vid[^>]+>/i", "", $content);

        return array($content0.$content);
    }
	
	function funCaptureQiandunArticle($url,$results)
    {
        $content = preg_match("/<div[^>]+class=\"inner_promot\"[^>]*>[\s\S]*?<\/div>([\s\S]*?)<!-- JiaThis Button BEGIN -->/i",
                              $results,$temp) ? $temp[1]:"";
	
		$content2 = preg_match("/<h3 class=\"related_post_title\">[\s\S]*?<\/ul>/i",$results,$temp) ? $temp[0]:"";
   
        return array($content,$content2);
    }

    function funCaptureCocos2devArticle($url,$results)
    {
        $content = preg_match("/<div[^>]+class=\"entry\"[^>]*>[\s\S]*?<\/div>([\s\S]*?)<\/div>\s*<p class=\"infobottom\"/i",$results,$temp) ? $temp[1]:"";
        
        return array($content);
    }

    function funCaptureJbxueArticle($url,$results)
    {
        $content0 = preg_match("/<div class=\"summary\"[^>]*>(.*?)<\/div>/i",$results,$temp) ? $temp[1]:"";
        
        $content = preg_match("/<div[^>]+id=\"c_info\"[^>]*>([\s\S]*?)<div id=\"pages\"/i",$results,$temp) ? $temp[1]:"";
        
        $content=preg_replace("/<div class=\"codetitle\">[\s\S]*?<\/div>/i","",$content);
        
        return array($content0.$content);
    }

    function funCaptureItsokuArticle($url,$results)
    {
        /*
         "videoId":"uSsveRjznsc","videoImgUrl":"http://i2.tdimg.com/179/464/231/p.jpg","videoUrl":"http://www.tudou.com/programs/view/uSsveRjznsc/"
         */
        $content="";
        
        $ret = preg_match("/\"videoId\":\"(.*?)\",\"videoImgUrl\":\"(.*?)\"/i",$results,$temp);
        if($ret)
        {
            $videoId=$temp[1];
            $videoImgUrl=$temp[2];
            $videoUrl="";
            $posterImageSrc="";
            parseTudouVideo($videoId,$videoUrl,$posterImageSrc);
            
            $content="<video src=\"".$videoUrl."\" poster=\"".$videoImgUrl."\"></video>";
        }

        return array($content);
    }

    function funCaptureIfanrArticle($url,$results)
    {
        $content = preg_match("/<div id=\"entry-content\"[^>]*>([\s\S]*?)<\/div>\s*<div id=\"ifr-article-component\"/i",$results,$temp)?$temp[1]:"";
        return array($content);
    }

	function funCapture9TechArticle($url,$results)
    {
        $content = preg_match("/<div class=\"articleCot\"[^>]*>([\s\S]*?)<div class=\"newsTip\"/i",$results,$temp)?$temp[1]:"";
        return array($content);
    }

	function funCaptureTuicoolArticle($url,$results)
    {
        $content = preg_match("/<div class=\"article_body\"[^>]*>([\s\S]*?)<div class=\"span4\"/i",$results,$temp)?$temp[1]:"";
        return array($content);
    }
	
/*
 $loginUrl="http://passport.cnblogs.com/login.aspx";
 if(strpos($location,$loginUrl)>=0)
 {
 $params = array();
 $params['__EVENTTARGET'] = "";
 $params['__EVENTARGUMENT'] = "";
 $params['__VIEWSTATE'] = "/wEPDwULLTE1MzYzODg2NzZkGAEFHl9fQ29udHJvbHNSZXF1aXJlUG9zdEJhY2tLZXlfXxYBBQtjaGtSZW1lbWJlcm1QYDyKKI9af4b67Mzq2xFaL9Bt";
 $params['__EVENTVALIDATION'] = "/wEdAAUyDI6H/s9f+ZALqNAA4PyUhI6Xi65hwcQ8/QoQCF8JIahXufbhIqPmwKf992GTkd0wq1PKp6+/1yNGng6H71Uxop4oRunf14dz2Zt2+QKDEIYpifFQj3yQiLk3eeHVQqcjiaAP";
 $params['tbUserName'] = "wangweike";
 $params['tbPassword'] = "wangweike2011";
 //$params['btnLogin'] = "%E7%99%BB++%E5%BD%95";
 $params['txtReturnUrl'] = "http://home.cnblogs.com/";
 $results=postCurlExec($loginUrl,$params);
 */
 
 //音频示例 http://www.cnbeta.com/articles/258234.htm
 
?>

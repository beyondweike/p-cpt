<?php
	include_once("../common/file.function.php");
    include_once("../common/request.function.php"); 
    include_once("../common/string.function.php");
	
	function generalRreplace($content,$baseUrl)
	{
		if($content=="")
		{
			return "";
		}
		
        $content=preg_replace("/^<blockquote>([\s\S]*)<\/blockquote>$/i", "\\1", $content);
        $content=preg_replace("/<iframe[^>]+src=(\"|\')(.*?)\\1[\s\S]+?<\/iframe>/i","<p style=\"text-align:center;\"><a href=\"\\2\">点击查看详细内容</a></p>", $content);
        
        //process p in td, http://blog.jobbole.com/46457/
        //width/% is useful
        $content=preg_replace("/(<table.*?) width=\s*[^%>]+(\s*.*?>)/i","\\1\\2", $content);
        $content=preg_replace("/(<td.*?) width=\s*[^%>]+(\s*.*?>)/i","\\1\\2", $content);

        //hr
		$content=preg_replace("/(<hr.*?) size=\s*[^>]+(\s*.*?>)/i","\\1\\2", $content);

        //http://www.cnblogs.com/forthxu/p/3301009.html
        $content=preg_replace("/<colgroup>.*?<\/colgroup>/i","", $content);//delete width in td

		//image,video,a
        //replace ../ to nullspace in src,href
        $content=preg_replace("/(src|href)=([\"\'])(\.\.\/)*((?!(http|data:image)).*?)\\2/i","\\1=\"\\4\"", $content);
        //complemented src in tag img.
        //$content=preg_replace("/(<img[^>]+)src=([\"\'])((?!(http|data:image)).*?)\\2/i","\\1src=\"".$baseUrl."\\3\"", $content);
        $content=preg_replace("/(<img[^>]{1,500})src=([\"\'])((?!(http|data:image)).*?)\\2/ie",
                              "'\\1'.'src=\"'.preg_replace('/(?<!:)\/{2,}/','/','".$baseUrl."'.'\\3').'\"'", $content);
        //complemented href in tag a,excepte begain with http or #
        //$content=preg_replace("/(<a[^>]+)href=([\"\'])((?!http).*?)\\2/i","\\1href=\"".$baseUrl."\\3\"", $content);
        $content=preg_replace("/(<a[^>]+)href=([\"\'])((?!(http|#)).*?)\\2/ie",
                              "'\\1'.'href=\"'.preg_replace('/(?<!:)\/{2,}/','/','".$baseUrl."'.'\\3').'\"'", $content);

        //font
        $content=preg_replace("/<\/?font[^>]*>/i", "", $content);//delete font tag,which restricts the textcolor

        //space line,some space line appearance is bacause of spaces at the end of section.
        //replace multi br to one,which must in tag.because also some multi br is in texts,they make space lines in texts to easily reading.
        $content=preg_replace("/>\s*(<br\s*\/?>\s*){2,}\s*</i", "><br><", $content);
        //remove space line behind img tag,because Boundary between img and text is obvious
        $content=preg_replace("/(<img[^>]*>)(\s*<br[^>]*>){1,}\s*/i", "\\1", $content);

        //remove br which beyond or behind video tag
        $content=preg_replace("/(<br\s*?\/?>|\s)*?(<\/?video[^>]*?>)(<br\s*?\/?>|\s)*?/i", "\\2", $content);

        //remove needless br in or outsize of div p tags.not all tags,such as strong,which be used in section,closely connection with texts.notice may some full-width space near br.
        $content=preg_replace("/(<br\s*\/?>)*(\s|&nbsp;)*?<\/(div|p|li|ul|pre)>\s*(<br\s*\/?>)*[\s　]*?(<br\s*\/?>[\s　]*?)*?/i", "</\\3>", $content);
        $content=preg_replace("/(<br\s*\/?>)*<\/(div|p|pre)>(<br\s*\/?>)*/i", "</\\2>", $content);
        //make count of continued br limited to 2
        $content=preg_replace("/(<br\s*\/?>\s*){3,}/i", "<br><br>", $content);
        //delete empty tags.except audio,img,video,td tag,and br tag may be used for format program codes.
        //notice full-width space also be treated as empty.notice do not to delete video tag,which is also emtpy tag.then the effect of Generate space line is replaced by margin of div.\xc2\xa0.temporary not to regex the full-width space(&nbsp;||　)
        $content=preg_replace("/<([^aistv]\w*)[^>]*?>(\s|<br\s*\/?>|&nbsp;|&#160;||\xe3\x80\x80|\xc2\xa0)*?<\/\\1>/i", "", $content);

        //no use delete the nested empty tags.more troubles
        //delete the nested empty tags.
        //notice do not to delete video img tag,they are also emtpy tags.
        //and br tag may be used for format program codes.
        /*$content=preg_replace("/<([a-h,j-u,w-z][a-z]+)[^>]*?>\s*(<\/?[a,c-h,j-u,w-z][a-z]+?[^>]*?>\s*)*?<\/\\1>/i", "", $content);*/

        //remove space at begin of section,or at begin of the tag which closely behind the section tag
        //do not do this for some inner tag,may be they need space.
        //(　|　　),special spaces.temporary not to regex the full-width space(\s|　|　　),\xe3\x80\x80
        //use {} instead of +,avoid server crash. .　. .
        $content=preg_replace("/(<(p|div)[^>]*>)((<[^>]*>)*?)(<br\s*\/?>|\s|&nbsp;|&#160;|\xe3\x80\x80|\xc2\xa0|&#160;){1,300}/i", "\\1\\3", $content);
		
		//replace much more &nbsp;
		//view-source:http://www.cnblogs.com/simman/p/3373226.html
        $content=preg_replace("/(&nbsp;\s*){3,300}/i", "", $content);

        //add text-indent:0em for which is text-align:center
        $content=preg_replace("/style=\"[^\"]*?(text-align:\s*center)[^\"]*?\"/i","style=\"\\1;text-indent:0em;\"", $content);

        //delete style for which is not text-align:center
        $content=preg_replace("/style=\"(?!(text-align:\s*?center;|display: none;))[^\"]*\"/i", "", $content);

        //make image,video section center layout
        $content=preg_replace("/<p>(<a[^>]*>)?<img/i", "<p style=\"text-align:center;text-indent:0em;\">\\1<img", $content);
        $content=preg_replace("/(<p[^>]*?)>((<\/?[^p]+[^>]*>)?<video)/i", "\\1 style=\"text-align:center;text-indent:0em;\">\\2", $content);

        //delete class attribute of pre tag,why?
        //in http://www.cnblogs.com/lslvxy/p/3274332.html
        ////$content=preg_replace("/<pre([^>]+)class=\".*?\"([^>]*)>/i","<pre\\1\\2>",$content);

        //delete style tag
        //in http://www.cnblogs.com/softidea/p/3452042.html
        $content=preg_replace("/<style type=\"text\/css\">[\s\S]*?<\/style>/i","",$content);

        $content=preg_replace("/<!--[\s\S]*?-->/i","",$content);
        $content=trim($content);

        return $content;
	}

    function getYoukuVideoInfo($vid,&$videoSrc,&$posterImageSrc)
    {
        if($vid && $vid!="")
        {
            $videoSrc="http://v.youku.com/player/getRealM3U8/vid/".$vid."/type//video.m3u8";
            
            $url="http://v.youku.com/player/getPlayList/VideoIDS/".$vid;
            $jsonContent=curlexec($url);
            $posterImageSrc=preg_match("/\"logo\":\"(.*?)\"/i",$jsonContent,$temp)?$temp[1]:"";
            $posterImageSrc=preg_replace("/\\\\\//","/",$posterImageSrc);
        }
    }

    function embedReplace($content,&$tagArray,$url)
    {
		if($content=="")
		{
			return "";
		}
        
        $originUrl=$url;
        
        //未解决的视频
        //http://www.cnbeta.com/articles/255811.htm,http://c.brightcove.com
		
        //http://www.cnblogs.com/umlonline/p/3374422.html,
		//http://www.duobei.com/apps/f2ba919e18808c59150da220b2af5f3e/cid/2561722573,
		////http://www.woshipm.com/discuss/59608.html
		//duobei.com 不提供mp4,m3u8
		
		//http://www.cnbeta.com/articles/256931.htm,http://www.acfun.tv/v/ac854738
		//http://www.cnbeta.com/articles/257394.htm,http://edge.media-server.com/version/1382019060/m/flapl/fbqsmu6b/p/fbqsmu6b/
        //http://www.cnbeta.com/articles/259361.htm,http://www.msnbc.msn.com/id/32545640
        //http://www.cbsnews.com/, http://www.36kr.com/p/208107.html
		//http://www.cnbeta.com/articles/268285.htm,http://www.cnbeta.com/articles/274448.htm，
		//http://static.hdslb.com/
		
		//http://www.cnbeta.com/articles/269157.htm,http://creativity-online.com/video/player.swf" 
		//http://www.cbsnews.com/common/video/cbsnews_player.swf,http://www.cnbeta.com/articles/269776.htm
		//http://news.cnblogs.com/n/202708/，http://cloud.video.taobao.com/play/u/1067522205/e/1/t/1/p/1/10523609.swf
		//http://www.cnbeta.com/articles/274448.htm,http://www.cnbeta.com/articles/275941.htm,
		//http://video.cnbc.com/gallery/?video=3000254596
		//http://www.cnbeta.com/articles/282765.htm 中有pdf阅读问题
		
		$hasVideo=false;
		
        //get <embed></embed> in <object></object>
        $content=preg_replace("/<object[\s\S]+?(<embed.*?(\/>|<\/embed>))[\s\S]*?<\/object>/i", "\\1",$content);		
		$reg="/<object[^>]+data=\"(.*?)\"[\s\S]*?value=\"([^\"]*?)\"\s+name=\"flashvars\"[\s\S]*?(<\/object>)+/i";
		$ret=preg_match($reg,$content,$temp);
		if($ret)
		{
			//http://www.cnbeta.com/articles/267138.htm
			$content=preg_replace($reg, "<embed src=\"\\1\" flashvars=\"\\2\"/>",$content);
		}
		else
		{
			$reg="/<object[^>]+data=\"(.*?)\"([\s\S]((?<=\"flashvars\")\s*value=\"([^\"]*?)\")?)+?(<\/object>)+/i";
			$content=preg_replace($reg, "<embed src=\"\\1\" flashvars=\"\\4\"/>",$content);//4 is right
		}
        
        //http://www.cnbeta.com/articles/262739.htm 存在object 嵌套
		
        /*
		<object width="600" height="450" data="http://player.youku.com/player.php/sid/XNTkyODI5NzE2/v.swf" type="application/x-shockwave-flash">
<param name="src" value="http://player.youku.com/player.php/sid/XNTkyODI5NzE2/v.swf" />
</object>

         <object width="620" height="517" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0">
             <param name="src" value="http://www.tudou.com/v/mqw47w3OAJ0/&amp;rpid=69000640&amp;resourceId=69000640_05_05_99&amp;bid=05/v.swf" />
             <param name="allowscriptaccess" value="always" />
             <param name="allowfullscreen" value="true" />
             <param name="wmode" value="opaque" />
             <embed width="620" height="517" type="application/x-shockwave-flash" src="http://www.tudou.com/v/mqw47w3OAJ0/&amp;rpid=69000640&amp;resourceId=69000640_05_05_99&amp;bid=05/v.swf" allowscriptaccess="always" allowfullscreen="true" wmode="opaque" />
         </object>
         
         <object width="600" height="450" data="http://v.163.com/swf/video/NetEaseFlvPlayerV3.swf?pltype=7&amp;topicid=0031  &amp;vid=V94JI3OCA&amp;sid=V6FERU4MT" type="application/x-shockwave-flash">
            <param name="src" value="           http://v.163.com/swf/video/NetEaseFlvPlayerV3.swf?pltype=7&amp;topicid=0031&amp;vid=V94JI3OCA&amp;sid=V6FERU4MT" />
         </object>
         
         http://news.cnblogs.com/n/184888/  youku视频
         <object width="600" height="450" data="http://v.youku.com/v_show/id_XNTkzNjM5MTE2.html" type="application/x-shockwave-flash">
         <param name="src" value="http://v.youku.com/v_show/id_XNTkzNjM5MTE2.html" />
         </object>
         */
		 
		 		
		//http://news.ipadown.com/25035
		//http://www.36kr.com/p/205624.html
		//<iframe height=420 width=640 src="http://player.youku.com/embed/XNTk5MzM1ODQ0" frameborder=0 allowfullscreen></iframe>
		//<iframe src="http://player.youku.com/embed/XNTkwNjQ1NzM2" allowfullscreen="" style="margin-left:-10px" frameborder="0" height="460" width="737"></iframe>
		$ret=preg_match_all("/<iframe[^>]+src=(\"|\')(.*?)\\1[^<]+<\/iframe>/i",$content,$temp);
		if($ret)
		{
			foreach($temp[2] as $src)
            {
				if(strpos($src,"youku.com")!==FALSE
                   || strpos($src,"tudou.com")!==FALSE)
                {
					$content=preg_replace("/<iframe[\s\S]+?<\/iframe>/i","<embed src=\"".$src."\"/>", $content,1);
				}
                else if(strpos($src,"56.com")!==FALSE)
                {
                    //http://www.36kr.com/p/206387.html
					$content=preg_replace("/<iframe[\s\S]+?<\/iframe>/i","<embed src=\"".$src."\"/>", $content,1);
				}
                else if(strpos($src,"kickstarter.com")!==FALSE)
                {
                    //http://www.cnbeta.com/articles/250141.htm
					$content=preg_replace("/<iframe[\s\S]+?<\/iframe>/i","<embed src=\"".$src."\"/>", $content,1);
				}
                else if(strpos($src,"embed.newsinc.com")!==FALSE)
                {
                    //http://www.cnbeta.com/articles/250141.htm
					$content=preg_replace("/<iframe[\s\S]+?<\/iframe>/i","<embed src=\"".$src."\"/>", $content,1);
				}
                else if(strpos($src,"teamcoco.com/embed")!==FALSE)
                {
                    //http://www.cnbeta.com/articles/256601.htm
					$content=preg_replace("/<iframe[\s\S]+?<\/iframe>/i","<embed src=\"".$src."\"/>", $content,1);
				}
                else if(strpos($src,"duobei.com/apps/")!==FALSE)
                {
                    //http://www.cnblogs.com/umlonline/p/3374422.html
                    //http://www.duobei.com/apps/f2ba919e18808c59150da220b2af5f3e/cid/2561722573
					$content=preg_replace("/<iframe[\s\S]+?<\/iframe>/i","<embed src=\"".$src."\"/>", $content,1);
				}
                else if(strpos($src,"video.msn.com/embed/")!==FALSE)
                {
                    //http://news.cnblogs.com/n/194234/
					$content=preg_replace("/<iframe[\s\S]+?<\/iframe>/i","<embed src=\"".$src."\"/>", $content,1);
				}
				else if(strpos($src,"engadget.com/embed-5min/")!==FALSE)
                {
                    //http://www.cnbeta.com/articles/262577.htm
					$content=preg_replace("/<iframe[\s\S]+?<\/iframe>/i","<embed src=\"".$src."\"/>", $content,1);
				}
				else if(strpos($src,"www.washingtonpost.com/posttv/c/embed/")!==FALSE)
                {
                    //http://www.cnbeta.com/articles/284489.htm
				}
			}
		}

        //$ret=preg_match_all("/<embed[^>]+src=\"(.*?)\".*?(\s*flashvars=\"(.*?)\")?.*?(\/>|<\/embed>)/",$content,$temps);
        $ret=preg_match_all("/<embed([\s\S]+?)(\/>|<\/embed>)/i",$content,$temps);
        if($ret)
        {
			$hasVideo=true;
			
            foreach($temps[1] as $embedContent)
            {
				$src=preg_match("/src=\"(.*?)\"/i",$embedContent,$temp)?$temp[1]:"";
				if($src=="")
				{
					$src=preg_match("/src=(.*?)\s/i",$embedContent,$temp)?$temp[1]:"";
				}
                $flashvars=preg_match("/flashvars=\"(.+?)\"/i",$embedContent,$temp)?$temp[1]:$src;
	
                $replaced=FALSE;
				$videoSrc=NULL;
                $posterImageSrc="";
                
                if(strpos($src,"embed.wistia.com")!==FALSE)
                {
                    //http://www.cnbeta.com/articles/258414.htm
                    //http://www.techspot.com/news/54501-transporter-sync-promises-dropbox-like-file-syncing-on-your-own-terms.html
                    //http://fast.wistia.net/embed/iframe/e7g5bwsjk
                    $ret=preg_match("/referrer=(.*?)&/i",$flashvars,$temp);
                    if($ret)
                    {
                        $url=urldecode($temp[1]);
                        $pageContent=curlexec($url);
                        $ret=preg_match("/<div class=\"video-container\">\s*<iframe[^>]+src=\"(.*?)\"/i",$pageContent,$temp);
                        if($ret)
                        {
                            $url=$temp[1];
                            $pageContent=curlexec($url);
                            $ret=preg_match("/<div id=\'wistia_video\'>\s*<a href=\'(.*?)\' id='wistia_fallback'>\s*<img[^>]+src=\'(.*?)\'>/i",$pageContent,$temp);
                            if($ret)
                            {
                                $videoSrc=$temp[1];
                                $posterImageSrc=$temp[2];
                            }
                        }
                    }
                    
                    if($posterImageSrc=="")
                    {
                        $posterImageSrc=preg_match("/stillUrl=(.*?)&/i",$flashvars,$temp)?$temp[1]:"";
                        $posterImageSrc=urldecode($posterImageSrc);
                    }
                }
				else if(strpos($src,"cnet.com")!==FALSE)
                {
                    //http://www.cnbeta.com/articles/260336.htm
					//http://www.cnbeta.com/articles/261280.htm  
                    $vid=preg_match("/value=(.*?)(&|$)/i",$flashvars,$temp)?$temp[1]:"";
                    $url="http://m.cnet.com/videos/-/$vid?ds=1";
                    $cookie="__utma=134239277.824268910.1385001900.1385001900.1385001900.1; __utmb=134239277.1.10.1385001900; __utmz=134239277.1385001900.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); espromo=1%2C1385001895798; grd=m3%2C320%2C568%2C2; LDCLGFbrowser=a841c9b0-9279-4964-bd37-d1cda4a73ea3; __gads=ID=03b8ff84713b3bd6:T=1385001899:S=ALNI_MZ6kd29l3aXNDrZsesNRbB5N6GdwQ; s_fid=390ED60B35398788-0E484C3FD6A6959A; s_getNewRepeat=1385001901272-New; s_invisit=true; s_lv_cnet=1385001901272; s_lv_cnet_s=First%20Visit; s_vnum=1387593901270%26vn%3D1";
                    $jsonContent=curlexecMobile($url,$cookie);
					//$videoSrc=$jsonContent;
                    $videoSrc=preg_match("/data-src-mp4=\"(.*?)\"/i",$jsonContent,$temp)?$temp[1]:"";
                    //$videoSrc=preg_match("/data-src-m3u8=\"(.*?)\"/i",$jsonContent,$temp)?$temp[1]:"";
                    $posterImageSrc=preg_match("/<img src=\"(.*?)\">/i",$jsonContent,$temp)?$temp[1]:"";
                }
                else if(strpos($src,"cntv.cn")!==FALSE)
                {
					//http://www.shushao.com/apple/item/211191-apple
					$vid==NULL;
					$ret=preg_match("/videoCenterId=(.*?)(&|$)/i",$src,$temp);
                    if($ret)
                    {
						$vid=$temp[1];
					}
					else
					{
						$vid=preg_match("/videoCenterId=(.*?)(&|$)/i",$flashvars,$temp)?$temp[1]:"";
					}
                    
                    $url="http://vdn.apps.cntv.cn/api/getIpadVideoInfo.do?tai=ipad&from=html5&pid=".$vid;
                    $jsonContent=curlexec($url);
                    $videoSrc=preg_match("/\"hls_url\":\"(.*?)\"/i",$jsonContent,$temp)?$temp[1]:"";
                    $posterImageSrc=preg_match("/\"image\":\"(.*?)\"/i",$jsonContent,$temp)?$temp[1]:"";
                }
                else if(strpos($src,"msnbc.msn.com")!==FALSE)
                {
					//http://www.cnbeta.com/articles/259361.htm
                    $vid=preg_match("/launch=(.*?)(&|$)/i",$flashvars,$temp)?$temp[1]:"";
                    $url="http://www.nbcnews.com/video/nightly-news/".$vid;
                    $jsonContent=curlexecMobile($url);
                    $videoSrc=preg_match("/<a class=\"videolink\" href=\"(.*?)\">/i",$jsonContent,$temp)?$temp[1]:"";
                    $posterImageSrc=preg_match("/<img[^>]+src=\"(.*?)\" class=\"photo\"/i",$jsonContent,$temp)?$temp[1]:"";
                }
				else if(strpos($src,"brightcove.com")!==FALSE)
                {
					//http://www.cnbeta.com/articles/258766.htm
					//http://www.cnbeta.com/articles/256423.htm
					//http://c.brightcove.com
                    $vid=preg_match("/videoId=(.*?)&/i",$flashvars,$temp)?$temp[1]:"";
					$pid=preg_match("/playerID=(.*?)&/i",$flashvars,$temp)?$temp[1]:"";
					if($pid=="")
					{
						$url=preg_match("/linkBaseURL=(.*?)&/i",$flashvars,$temp)?$temp[1]:"";
                    	$pageContent=curlexec(urldecode($url));
                    	$pid=preg_match("/<param name=\"playerID\" value=\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
						$posterImageSrc=preg_match("/<div class=\"special-box\">\s*<img[^>]+src=\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
					}

                    $videoSrc="http://c.brightcove.com/services/mobile/streaming/index/master.m3u8?videoId=$vid&playerId=$pid";
                }
                else if(strpos($src,"oschina.net")!==FALSE 
					|| strpos($src,"ahalei.com")!==FALSE 
					|| strpos($src,"opengg.me")!==FALSE 
					|| strpos($src,"googlecode.com")!==FALSE)
                {
                    //http://www.cnbeta.com/articles/259457.htm
					//http://www.cnblogs.com/ahalei/p/3577792.html
					//http://www.cnbeta.com/articles/275972.htm
					//http://www.cnbeta.com/articles/275145.h
                    $vid=preg_match("/VideoIDS=(.*?)(\/|\?|$)/i",$src,$temp)?$temp[1]:NULL;
                    
                    getYoukuVideoInfo($vid,$videoSrc,$posterImageSrc);
                }
				else if(strpos($src,"ali213.net")!==FALSE)
                {
                    //http://www.cnbeta.com/articles/276681.htm
					//flashvars="type=youku&amp;vid=XNjg2MjQ1OTky&amp;aid=" 
					//src="http://so.v.ali213.net/plus/MukioPlayer.swf" 
                    $vid=preg_match("/vid=(.*?)&/i",$flashvars,$temp)?$temp[1]:NULL;
					$type=preg_match("/type=(.*?)&/i",$flashvars,$temp)?$temp[1]:NULL;
					if($type=="youku")
                    {
                    	getYoukuVideoInfo($vid,$videoSrc,$posterImageSrc);
					}
                }
                else if(strpos($src,"youku.com")!==FALSE)
                {
					//http://player.youku.com/player.php/sid/XMzA1NTQ2NjM2/v.swf
                    $vid=NULL;
                    $ret=preg_match("/.*?\/sid\/(\w+)(\/v\.swf)?/i",$src,$temp);
                    if($ret)
                    {
						$vid=$temp[1];
                    }
                    else
                    {
                        //http://v.youku.com/v_show/id_XNTkzNjM5MTE2.html
						//http://www.cnbeta.com/articles/259185.htm
                        $ret=preg_match("/.*?v_show\/id_(\w+)\.htm/i",$src,$temp);
                        if($ret)
                        {
                            $vid=$temp[1];
                        }
						else
						{
							//http://www.woshipm.com/operate/65514.html
							//http://player.youku.com/embed/XNTkwNjQ1NzM2
							$ret=preg_match("/embed\/(\w+)(\?|$)/i",$src,$temp);
                        	if($ret)
                        	{
                            	$vid=$temp[1];
                        	}
						}
                    }
                    
                    getYoukuVideoInfo($vid,$videoSrc,$posterImageSrc);
                }
				else if(strpos($src,"v.163.com")!==FALSE)
                {
					//http://www.cnbeta.com/articles/262739.htm
                    //http://v.163.com/zixun/V9DLRMSIL/V9E4GHRSB.html
					
					if(!$flashvars)
					{
						$flashvars=$src;
					}
					
                    $sid=preg_match("/sid=(.*?)(&|$)/i",$flashvars,$temp)?$temp[1]:"";
					$vid=preg_match("/vid=(.*?)(&|$)/i",$flashvars,$temp)?$temp[1]:"";
					$topicid=preg_match("/topicid=(.*?)(&|$)/i",$flashvars,$temp)?$temp[1]:"";
                    $posterImageSrc=preg_match("/coverpic=(.*?)(&|$)/i",$flashvars,$temp)?$temp[1]:"";
                    
                    $url="http://v.163.com/zixun/".$sid."/".$vid.".html";
                    $pageContent=curlexec($url);
                    $videoSrc=preg_match("/<source[^>]+src=\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
					if($videoSrc!="")
					{
						if($posterImageSrc=="")
						{
							$posterImageSrc=preg_match("/coverpic : \"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
						}
					}
					else
					{
						//http://www.cnbeta.com/articles/264593.htm
						//http://vimg2.ws.126.net/image/snapshot/2013/12/A/3/V9F6C7TA3.jpg
						//http://v.news.163.com/video/2013/12/A/2/V9F6C7TA2.html
						//http://www.cnbeta.com/articles/276317.htm
						//http://xml.ws.126.net/video/M/1/0031_V9M6SHHM1.xml
						//http://www.cnbeta.com/articles/281761.htm 异常
						$url="http://xml.ws.126.net/video/".substr($vid,strlen($vid)-2,1)."/".substr($vid,strlen($vid)-1,1)."/".$topicid."_".$vid.".xml";
                   		$pageContent=curlexec($url);
                    	$url=preg_match("/<pageUrl>(.*?)<\/pageUrl>/i",$pageContent,$temp)?$temp[1]:"";
						$pageContent=curlexec($url);
						$videoSrc=preg_match("/<source[^>]+src=\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
						
						if($posterImageSrc=="")
						{
							//coverpic : "http://vimg2.ws.126.net/image/snapshot/2014/3/M/2/V9M6SHHM2.jpg"
							$posterImageSrc=preg_match("/coverpic : \"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
						}
					}
                }
				else if(strpos($src,"cnbc.com")!==FALSE)
                {
					//http://www.cnbeta.com/articles/275941.htm
					//src="http://plus.cnbc.com/rssvideosearch/action/player/id/3000254596/code/cnbcplayershare" 
                    $vid=preg_match("/id\/(.*?)\//i",$src,$temp)?$temp[1]:"";
					$url="http://video.cnbc.com/gallery/?video=".$vid;
					
					$pageContent=curlexec($url);
					$posterImageSrc=preg_match("/<meta property=\"og:image\" content=\"(.*?)\">/i",$pageContent,$temp)?$temp[1]:"";
					$videoSrc=preg_match("/fmLinkArr.push\(\'mpeg4_500000_Download\|(.*?)\'\)/i",$pageContent,$temp)?$temp[1]:"";
                }
                else if(strpos($src,"albinoblacksheep.com")!==FALSE)
                {
					//http://www.cnbeta.com/articles/111038.htm
                    //测试视频地址不可访问
                    $videoSrc=preg_match("/videoUrl=(.*)$/i",$src,$temp)?$temp[1]:"";
                }
                else if(strpos($src,"teamcoco.com")!==FALSE)
                {
                    //http://www.cnbeta.com/articles/256601.htm
                    //src="http://teamcoco.com/embed/v/71261"
                    $vid=preg_match("/\/v\/(.*?)$/i",$src,$temp)?$temp[1]:"";
					$url="http://teamcoco.com/x9/xhr/video/data/1,4/$vid.json";
                    $pageContent=curlexec($url);
                    $videoSrc=preg_match("/\"text\":\"([^\"]*?\.mp4)\"/i",$pageContent,$temp)?$temp[1]:"";
                    $posterImageSrc=preg_match("/\"text\":\"([^\"]*?\.jpg)\"/i",$pageContent,$temp)?$temp[1]:"";
				}
                else if(strpos($src,"kickstarter.com")!==FALSE)
                {
					//http://www.cnbeta.com/articles/250141.htm
                    //http://www.cnbeta.com/articles/255826.htm
                    //http://www.36kr.com/p/208051.html
                    $url=$src;
                    $pageContent=curlexec($url);
					$posterImageSrc=preg_match("/<img.*?class=\"has_played_hide poster\".*?src=\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
                    $ret=preg_match("/(<video[\s\S]+<\/video>)/i",$pageContent,$temp);
                    if($ret)
                    {
                        $videoTagContent=$temp[1];
						$videoTagContent=preg_replace("/^<video/i", "<video poster=\"$posterImageSrc\"", $videoTagContent);
                        $content=preg_replace("/<embed[\s\S]+?(\/>|\s*<\/embed>)/i", $videoTagContent, $content,1);
                        
                        $replaced=TRUE;
                    }
                }
                else if(strpos($src,"embed.newsinc.com")!==FALSE)
                {
					//http://www.cnbeta.com/articles/255781.htm
                    
                    //http://embed.newsinc.com/Single/iframe.html?WID=1&amp;VID=25237730&amp;freewheel=69016&amp;sitesection=seorlandosentinel&amp;width=620&amp;height=371

                    $ret=preg_match("/VID=(.*?)&amp;freewheel=(.*?)&amp;/i",$src,$temp);
                    if($ret)
                    {
                        $url="http://lt.ndnps.newsinc.com/player/show/$temp[2]/1/0/$temp[1]/2?";
                        $pageContent=curlexec($url);
                        
                        $videoSrc=preg_match("/\"AssetLocation\":\"(.*?\.mp4)\"/i",$pageContent,$temp)?$temp[1]:"";
                        $posterImageSrc=preg_match("/\"AssetLocation\":\"(.*?\.jpg)\"/i",$pageContent,$temp)?$temp[1]:"";
                    }
                }
                else if(strpos($src,"necn.com")!==FALSE)
                {
					//http://www.cnbeta.com/articles/256744.htm
                    $ret=preg_match("/playerURL=(.*?)(&amp;|$)/i",$flashvars,$temp);
                    if($ret)
                    {
                        $url=$temp[1];
                        $pageContent=curlexecmobile($url);
                        $url=preg_match("/(http:.*?zone=video)/i",$pageContent,$temp)?$temp[1]:"";
                    $pageContent=curlexec($url."&format=Script&callback=tpJSONLoaderCallback");
                        $posterImageSrc=preg_match("/\"defaultThumbnailUrl\": \"(.*?)\",/i",$pageContent,$temp)?$temp[1]:"";
                        
                        $pageContent=curlexecmobile($url."&format=SMIL");
                        $videoSrc=preg_match("/<video[^>]+src=\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
                    }
                }
                else if(strpos($src,"56.com")!==FALSE)
                {
					//http://news.cnblogs.com/n/188398/, http://player.56.com/v_ODI2NjcxNTc.swf
                    //http://www.36kr.com/p/206387.html,http://www.56.com/iframe/OTY3OTY4MzQ
					//http://www.cnbeta.com/articles/252862.htm, http://player.56.com/cpm_OTY5NDYzMDE.swf
                    $vid=preg_match("/(v|cpm)_(.*?)\.swf/i",$src,$temp)?$temp[2]:"";
                    if($vid=="")
                    {
                        $vid=preg_match("/iframe\/(.*?)$/i",$src,$temp)?$temp[1]:"";
                    }
					
                    if($vid!="")
                    {
						//http://www.cnbeta.com/articles/284891.htm
						$url="http://m.56.com/view/id-".$vid.".html";
						$pageContent=curlexec($url);
						$url=preg_match("/player\.loadVxml\(\'(.*?)\'\)/i",$pageContent,$temp)?$temp[1]:"";
						
						$pageContent=curlexec($url."&callback=jsonp_dfInfo");
						$posterImageSrc=preg_match("/\"bimg\":\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
						$videoSrc=preg_match("/\"url\":\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
						if($videoSrc=="")
						{
							$videoSrc="http://vxml.56.com/m3u8/".$vid."/";

                        	$url="http://vxml.56.com/json/".$vid."/?src=out";
                        	$pageContent=curlexec($url);
                        	$posterImageSrc=preg_match("/\"img\":\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
						}
                    }
                }
                else if(strpos($src,"ifeng.com")!==FALSE)
                {
					//http://news.ipadown.com/26311
                    //http://v.ifeng.com/include/exterior.swf?guid=01b92d8a-e76c-464b-9b17-6cd9dc5b1625&amp;pageurl=http://www.ifeng.com&amp;fromweb=other&amp;AutoPlay=false
                    $ret=preg_match("/guid=(.*?)(&|$)/i",$src,$temp);
					if($ret)
					{
                        $vid=$temp[1];
                        $url="http://dyn.v.ifeng.com/cmpp/video_msg_ipad.js?msg=".$vid;
                    	$pageContent=curlexec($url);
                        //"videoplayurl": "http://video19.ifeng.com/video07/2013/08/26/36723-102-055-1208.mp4",
                        $videoSrc=preg_match("/\"videoplayurl\":\s*\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
                    }
                }
				else if(strpos($src,"ku6.com")!==FALSE)
                {
					//http://www.aqee.net/hotel-wifi-javascript-injection/
                    //http://player.ku6.com/refer/kuzSr3zQSzxwqVgFTCm-jg../v.swf
                    $ret=preg_match("/\/([^\/]+)\/v\.swf/i",$src,$temp);
					if($ret)
					{
                        $vid=$temp[1];

                        //http://v.ku6.com/fetchwebm/kuzSr3zQSzxwqVgFTCm-jg...m3u8
                        $videoSrc="http://v.ku6.com/fetchwebm/".$vid.".m3u8";
						//http://v.ku6.com/show/kuzSr3zQSzxwqVgFTCm-jg...html
						$url="http://v.ku6.com/show/".$vid.".html";
                    	$pageContent=curlexec($url);
                        $posterImageSrc=preg_match("/cover:\s*\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
                    }
                }
				else if(strpos($src,"ku6vms.com")!==FALSE)
                {
					$vid=NULL;
					$style=NULL;
						
					//http://www.cnbeta.com/articles/281557.htm
                    $ret=preg_match("/vid\/(.*?)\/style\/(.*?)\//i",$src,$temp);
					if($ret)
					{
						$vid=$temp[1];
						$style=$temp[2];
					}
					else
					{
						//http://www.cnbeta.com/articles/287615.htm
						$ret=preg_match("/vid=(.*?)&(amp;)?style=(.*?)&/i",$flashvars,$temp);
						if($ret)
						{
							$vid=$temp[1];
							$style=$temp[3];
						}
					}
					
					if($ret)
					{
						$url="http://v.ku6vms.com/phpvms/player/forplayer/vid/$vid/style/$style/sn/";
                    	$pageContent=curlexec($url);
                        $posterImageSrc=preg_match("/\"picpath\":\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
						$posterImageSrc=preg_replace("/\\\\\//","/",$posterImageSrc);
						$vid=preg_match("/\"ku6vid\":\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
                        $videoSrc="http://v.ku6.com/fetchwebm/".$vid.".m3u8";
                    }
                }
                else if(strpos($src,"qq.com")!==FALSE)
                {
					$vid=NULL;
					
                    //http://news.ipadown.com/27353
					//http://news.ipadown.com/25748
                    //http://v.qq.com/page/f/4/p/f0012sohs4p.html?start=4
                    //http://static.video.qq.com/TPout.swf?vid=f0012sohs4p&amp;auto=1
					//http://static.video.qq.com/TPout.swf?vid=c001397nbcd&amp;auto=0
					//http://news.cnblogs.com/n/189212/
					//http://www.woshipm.com/pd/61305.html
                    $ret=preg_match("/vid=(.*?)(&|$)/i",$src,$temp);
					if($ret)
					{
                        $vid=$temp[1];
					}
					else if($flashvars)
					{
						//vid=c0013nio5rr&amp;autoplay=undefined&amp;list=2&amp;adplay=0&amp;showcfg=1&amp;tpid=0&amp;pic=http://images.cnitblog.com/news/157064/201309/28085916-1c980bdfcac94dee9e55629ee79c94e3.png&amp;share=1&amp;title=%E6%AF%94%E5%B0%94-%E7%9B%96%E8%8C%A8%E5%9C%A8%E5%93%88%E4%BD%9B%E5%A4%A7%E5%AD%A6%E6%8E%A5%E5%8F%97%E8%AE%BF%E8%B0%88
						$ret=preg_match("/vid=(.*?)&/i",$flashvars,$temp);
						if($ret)
						{
							$vid=$temp[1];
						}
						$ret=preg_match("/pic=(.*?)&/i",$flashvars,$temp);
						if($ret)
						{
							$posterImageSrc=$temp[1];
						}
					}
					
					if($vid)
					{
						//或http://vv.video.qq.com/getmind?otype=xml&platform=1&vids=j0123bz2r6y
                        $url="http://vv.video.qq.com/geturl?otype=json&vid=".$vid;
                    	$jsonContent=curlexec($url);
                        $ret=preg_match("/\"url\":\"(http:\/\/video.store.qq.com.*?)\"/i",$jsonContent,$temp);
                        if($ret)
                        {
                            //http://video.store.qq.com/2977802/f0012sohs4p.mp4?vkey=58CA7355515E3FA8CC0C0D34EB1C13B3B08798B19737E21593DDC2D894D1DF58569A784B591B91AF&br=66&platform=0&fmt=mp4&level=3
                            $videoSrc=$temp[1];
                            //http://vpic.video.qq.com/2977802/f0012sohs4p.png
                            $posterImageUri=preg_match("/video.store.qq.com\/(.*?)\./i",$jsonContent,$temp)?$temp[1]:"";
                            $posterImageSrc="http://vpic.video.qq.com/".$posterImageUri.".png";
                        }
                        else
                        {
                            $videoSrc=preg_match("/\"url\":\"(.*?)\"/i",$jsonContent,$temp)?$temp[1]:"";
							$posterImageUri=preg_match("/\/\/.*?\/(.*)\./i",$videoSrc,$temp)?$temp[1]:"";
							$posterImageSrc="http://vpic.video.qq.com/".$posterImageUri.".png";
                        }
                    }
                }
                else if(strpos($src,"tv.sohu.com")!==FALSE)
                {
					//http://www.cnbeta.com/articles/255650.htm
					$ret=preg_match("/vid=(.*?)&/i",$flashvars,$temp);
					if($ret)
					{
						$vid=$temp[1];
                        parseSohuVideo($vid,$videoSrc,$posterImageSrc);
					}
                }
                else if(strpos($src,"vrs.sohu.com")!==FALSE)
                {
					//http://news.ipadown.com/22182
                    //http://news.cnblogs.com/n/185724/
					//http://share.vrs.sohu.com/my/v.swf&amp;autoplay=false&amp;id=59387849&amp;skinNum=1&amp;topBar=1&amp;xuid=
                    $vid=NULL;
					$ret=preg_match("/id=(\d+)/i",$src,$temp);
					if($ret)
					{
						$vid=$temp[1];
                        
                        $url="http://my.tv.sohu.com/play/videonew.do?vid=".$vid;
                    	$jsonContent=curlexec($url);
                    	$posterImageSrc=preg_match("/\"coverImg\":\"(.*?)\"/i",$jsonContent,$temp)?$temp[1]:"";
						$videoUri=preg_match("/\"su\":\[\"(.*?)\"\]/i",$jsonContent,$temp)?$temp[1]:"";
                        if($videoUri!="")
                        {
                            $videoSrc="http://data.vod.itc.cn/?new=".$videoUri;
                        }
                        else
                        {
                            $videoSrc=preg_match("/\"clipsURL\":\[\"(.*?)\"\]/i",$jsonContent,$temp)?$temp[1]:"";
                            $videoSrc=format_url($videoSrc,"http://");
                        }
                    }
                    else
                    {
                        //http://news.cnblogs.com/n/192903/
                        $ret=preg_match("/\/(\d+)\/v\.swf/i",$src,$temp);
                        if($ret)
                        {
                            $vid=$temp[1];
                            parseSohuVideo($vid,$videoSrc,$posterImageSrc);
                        }
                    }
                }
				else if(strpos($src,"17173cdn.com")!==FALSE)
                {
					//http://news.ipadown.com/32202
                    $videoBase64Id=preg_match("/\/([^\/]*?)\.swf/i",$src,$temp)?$temp[1]:"";
                    $vid=base64_decode($videoBase64Id);
					//怎么样根据NjUwMTY5NQ找6501695，然后到http://v.17173.com/api/video/vInfo/id/6501695
                    $url="http://v.17173.com/api/video/vInfo/id/".$vid;
                    $jsonContent=curlexec($url);
                    $videoSrc=preg_match("/\"url\":\[\"(.*?)\"/i",$jsonContent,$temp)?$temp[1]:"";
                    $posterImageSrc=preg_match("/\"picUrl\":\"(.*?)\"/i",$jsonContent,$temp)?$temp[1]:"";
                    $trans = array("\\/"  => "/");
                    $posterImageSrc = strtr($posterImageSrc, $trans);
                    $videoSrc = strtr($videoSrc, $trans);
				}
				else if(strpos($src,"17173.tv.sohu.com")!==FALSE)
                {
                    //http://news.ipadown.com/25918
					//http://17173.tv.sohu.com/playercs2008.swf?Flvid=1774369
					$ret=preg_match("/Flvid=(\d+)/i",$src,$temp);
					if($ret)
					{
						$vid=$temp[1];
						$url="http://17173.tv.sohu.com/port/pconfig_r.php?id=".$vid;
                    	$jsonContent=curlexec($url);
						/*
						<data><item><Filehost>cdn.v.17173.com/cy1</Filehost><Flvid>1774369</Flvid><Username>cy5550</Username><Datetime>20130819</Datetime><Flvtitle>当上帝玩天天爱消除</Flvtitle><Link>http://17173.tv.sohu.com/v/1/0/177/MTc3NDM2OQ==</Link><bclass>1</bclass><sclass>0</sclass><flvurl>cy5550_1774369.mp4</flvurl><Allnumber>1</Allnumber><Alltime>233</Alltime><Lasttime>233</Lasttime><clicknum>170</clicknum><Splittime>233</Splittime><FirstScreen/><vote>n</vote><MD5>http://data.vod.itc.cn/?prot=1&new=/9/148/unhBYfFJIDqZGhFVf5SiM2.mp4</MD5><MD51>http://cdn1.v.17173.com/162c00203864bee50015a702d80b5200f89ee09176009cd6/cy1/FLVList/20130819/cy5550_1774369_1.mp4</MD51><MD52>http://cdn.v.17173.com/cy1/000ba16f597b6c6ab06f68a75fcd67a352157620/FLVList/20130819/cy5550_1774369_1.mp4?hits=4</MD52></item></data>
                         */
                    	$videoSrcsStr=preg_match("/<MD5>(.*?)<\/MD5>/i",$jsonContent,$temp)?$temp[1]:"";
                        $videoSrcArray=explode('||',$videoSrcsStr);
                        $videoSrc=$videoSrcArray[0];
						$ret=preg_match("/FLVList\/(.*?)_\d\./i",$jsonContent,$temp);
						if($ret)
						{
							//http://i1.17173.itc.cn/2013/vlog/20130819/cy5550_1774369_0.jpg
							$posterImageSrc="http://i1.17173.itc.cn/".substr($temp[1],0,4)."/vlog/".$temp[1]."_0.jpg";
						}
					}
                }
				else if(strpos($src,"video.sina.com")!==FALSE)
                {
				//http://news.cnblogs.com/n/187924/
				//http://news.cnblogs.com/n/188532/
								//http://you.video.sina.com.cn/api/sinawebApi/outplayrefer.php/vid=114500373_1_a0i1HSMxBm7K+l1lHz2stqkM7KQNt6nknynt71+iJwdZVQ6OYorfO4kK4SjSBc9C8mtO/s.swf
                    /*
					$ret=preg_match("/vid=(.*?)_(.*?)_(.*?)\//i",$src,$temp);
					if($ret)
					{
						$id1=urlencode($temp[1]);
						$id2=urlencode($temp[2]);
						$token=urlencode($temp[3]);
						//http://video.sina.com.cn/api/sinaVideoInfo.php?pid=1012&token=a0i1HSMxBm7K%2Bl1lHz2stqkM7KQNt6nknynt71%2BiJwdZVQ6OYorfO4kK4SjSBc9C8mtO
						$url="http://video.sina.com.cn/api/sinaVideoInfo.php?pid=1012&token=".$token;
						$sinaPageContent=fileGetContents($url);

						$ret=preg_match("/<url><!\[CDATA\[(.*?)\]\]><\/url>/i",$sinaPageContent,$temp);
						if($ret)
						{
						 	$src=$temp[1];
						}
						if($src=="")
						{
							$src="http://video.sina.com.cn/v/b/".$id1."-".$id2.".html";
						}
						
						//from http://www.shushao.com/apple/item/210633
						//example: src=http://video.sina.com.cn/p/tech/i/v/2013-06-22/022262576733.html
						$url="http://dp.sina.cn/dpool/video/pad/play.php?url=".$src;
						$sinaPageContent=curlexec($url);
						$ret=preg_match("/(<video[\s\S]+<\/video>)/i",$sinaPageContent,$temp);
						if($ret)
						{
							 $videoTagContent=$temp[1];
							 $content=preg_replace("/<embed[\s\S]+?(\/>|\s*<\/embed>)/i", $videoTagContent, $content,1);
	
							 $replaced=TRUE;
						}
					}
					*/
                    
                    //http://news.cnblogs.com/n/189679/
                    $vid=preg_match("/vid=(.*?)_(.*?)_(.*?)\//i",$src,$temp)?$temp[1]:"";
                    if($vid=="")
                    {
                        $vid=preg_match("/vid=(.*?)(&|$)/i",$flashvars,$temp)?$temp[1]:"";
                    }
                    $url="http://video.sina.com.cn/interface/video_ids/video_ids.php?v=".$vid;
                    $jsonContent=curlexec($url);
                    $vid=preg_match("/\"ipad_vid\":(\\d+)/i",$jsonContent,$temp)?$temp[1]:"";
                    $videoSrc="http://v.iask.com/v_play_ipad.php?vid=".$vid;
                    
                    $url="http://interface.video.sina.com.cn/interface/common/getVideoImage.php?vid=".$vid;
                    $jsonContent=curlexec($url);
                    $posterImageSrc=preg_match("/^imgurl=(.*)$/i",$jsonContent,$temp)?$temp[1]:"";
                }
                else if(strpos($src,"tudou.com")!==FALSE)
                {
                    $icode="";
                    $iid="";
                    
                    $ret=preg_match_all("/^.*?tudou.com\/v\/(.*?)\/.*?$/",$src,$icodes);
                    if(!$ret)
                    {
                        $ret=preg_match_all("/^.*?tudou.com\/v\/(.*?)$/",$src,$icodes);
                        if(!$ret)
                        {
                            //http://www.cnbeta.com/articles/256788.htm
                            //http://www.tudou.com/l/LRaSqrqZ4ao/&amp;iid=170728288/v.swf
                            $iid=preg_match("/iid=(\d+)/",$src,$temp)?$temp[1]:"";
                        }
                    }
                    
                    if($ret)
                    {
                        $icode=$icodes[1][0];
                        parseTudouVideo($icode,$videoSrc,$posterImageSrc);
                    }
                    else
                    {
                        //http://news.cnblogs.com/n/187525/
                        //http://www.tudou.com/programs/view/html5embed.action?code=Inqp2MXOys0&amp;resourceId=0_06_05_99
                        $ret=preg_match("/^.*?tudou.com\/programs\/view\/html5embed\.action\?code=.*?/i",$src);
                        if($ret)
                        {
                            $url=$src;
                            $tudouPageContent=curlexec($url);
                            $ret=preg_match("/.*?iid\s*=\s*(.*?);/i",$tudouPageContent,$iids);
                            if($ret)
                            {
                                $iid=$iids[1];
                                $iid=trim($iid);
                            }
                            //http://i4.tdimg.com/164/277/137/p.jpg
                            $posterImageSrc=preg_match("/poster=\"(.*?)\"/i",$tudouPageContent,$temp)?$temp[1]:"";
                            
                            if($iid!="")
                            {
                                $videoSrc="http://vr.tudou.com/v2proxy/v2.m3u8?it=".$iid;//."&st=2&pw=";
                            }
                        }
                    }
                }//end tudou.com
                else if(strpos($src,"video.msn.com")!==FALSE)
                {
                    //http://news.cnblogs.com/n/194234/
                    //http://hub.video.msn.com/embed/a39a6347-fc86-49cf-9e6d-405c3baa2438/?vars=...
                    $vid=preg_match("/embed\/(.*?)\//i",$src,$temp)?$temp[1]:"";
                    if($vid!="")
                    {
                        $posterImageSrc="http://img4.catalog.video.msn.com/Image.aspx?uuid=".$vid."&w=320&h=180";//&so=4
                        $url=$src;
                        $pageContent=curlexecMobile($url);
                        $videoSrc=preg_match("/MediaFiles: \[\'\', \'(.*?)\'\]/i",$pageContent,$temp)?$temp[1]:"";
                        //http\x3a\x2f\x2fcontent5.catalog.video.msn.com\x2fe2\x2fds\x2f292bc1a8-2d4b-4fad-9e0b-05ed126773ce.mp4
                        $trans = array("\\x3a"  => ":",
                                       "\\x2f"  => "/");
                        $videoSrc = strtr($videoSrc, $trans);
                    }
                }
                else if(strpos($src,"www.flickr.com")!==FALSE)
                {
                    //http://www.cnbeta.com/articles/262342.htm
                    $photo_id=preg_match("/photo_id=(.*?)&/i",$flashvars,$temp)?$temp[1]:"";
                    $videoSrc="http://m.flickr.com/photos/gsfc/".$photo_id."/play/";
                    $url="http://m.flickr.com/photos/gsfc/".$photo_id;
                    $pageContent=curlexecMobile($url);
                    $posterImageSrc=preg_match("/<img class=\"photo_img\" src=\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
                }
				else if(strpos($src,"engadget.com")!==FALSE)
                {
                    //http://www.cnbeta.com/articles/262577.htm
					//http://www.engadget.com/embed-5min/?sid=577&playList=518031877&sequential=1&shuffle=0
                    $params=preg_match("/\?(.*?)$/i",$src,$temp)?$temp[1]:"";
					$params=htmlspecialchars_decode($params);
                    $url="http://syn.5min.com/handlers/SenseHandler.ashx?func=GetResults&url=".$src."&".$params;
                    $pageContent=curlexec($url);
                    $posterImageSrc=preg_match("/\"ThumbURL\":\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
					$videoSrc=preg_match("/videoUrl=(.*?)&/i",$pageContent,$temp)?$temp[1]:"";
					$videoSrc=urldecode($videoSrc);
					$videoSrc=preg_replace("/(?<!:)\/\//i","/2/", $videoSrc,1);
                }
				else if(strpos($src,"acfun.tv")!==FALSE)
				{
					//http://www.cnblogs.com/alifriend/p/3494896.html
					$url=preg_match("/url=(.*?)(&|$)/i",$src,$temp)?$temp[1]:"";
					$pageContent=curlexec($url);
					$datavid=preg_match("/data-vid=\"(\d+)\".*?优酷源/i",$pageContent,$temp)?$temp[1]:"";
					$url="http://www.acfun.tv/video/getVideo.aspx?id=".$datavid;
					$pageContent=curlexec($url);
					$vid=preg_match("/\"sourceId\":\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
					getYoukuVideoInfo($vid,$videoSrc,$posterImageSrc);
				}
				else if(strpos($src,"chinanews.com")!==FALSE)
				{
					//http://www.cnbeta.com/articles/265450.htm
					$videoSrc=preg_match("/vInfo=(.*?)(&|$)/i",$flashvars,$temp)?$temp[1]:"";
					$posterImageSrc=preg_match("/vsimg=(.*?)(&|$)/i",$flashvars,$temp)?$temp[1]:"";
				}
				else if(strpos($src,"pcvideo.com.cn")!==FALSE)
				{
					//http://www.cnbeta.com/articles/266771.htm
					$videoSrc=preg_match("/flv=(.*?)(&|$)/i",$flashvars,$temp)?$temp[1]:"";
					$posterImageSrc=preg_match("/thumb=(.*?)(&|$)/i",$flashvars,$temp)?$temp[1]:"";
				}			
				else if(strpos($src,"player.video.qiyi.com")!==FALSE)
				{
					//http://news.cnblogs.com/n/198618/
					$vid=preg_match("/player.video.qiyi.com\/(.*?)\//i",$flashvars,$temp)?$temp[1]:"";
					$tvId=preg_match("/tvId=(.*?)(-|$)/i",$flashvars,$temp)?$temp[1]:"";
					$url="http://cache.video.qiyi.com/vi/$tvId/$vid/";
                    $pageContent=curlexec($url);
					$posterImageSrc=preg_match("/\"vpic\":\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
					$url="http://cache.m.iqiyi.com/dc/amt/$tvId/$vid/";
					$pageContent=curlexec($url);
					$videoSrc=preg_match("/\"mu\":\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
				}	
				else if(strpos($src,"mtime.cn")!==FALSE)
				{
					//http://www.cnbeta.com/articles/269268.htm
					$vid=preg_match("/vid=(.*?)(&|$)/i",$flashvars,$temp)?$temp[1]:"";
					$url="http://api.mtime.com/trailer/getvideo.aspx?vid=$vid";
                    $pageContent=curlexec($url);
					$videoSrc=preg_match("/\"mp4\":\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
				}
				else if(strpos($src,"tv.people.com.cn")!==FALSE)
				{
					//http://www.cnbeta.com/articles/279905.htm
					$path=preg_match("/xml=(.*?)(&|$)/i",$src,$temp)?$temp[1]:"";
					$url="http://tvplayer.people.com.cn/getXML.php?callback=playForMobile&ios=1&path=".$path;
                    $pageContent=curlexec($url);
					$ret=preg_match("/\'(.*?)\',\s*\'(.*?)\'/i",$pageContent,$temp);
					if($ret)
					{
						$posterImageSrc=$temp[2];
						$videoSrc=$temp[1];
					}
				}
				else if(strpos($src,"letv.com")!==FALSE)
				{
					//http://www.cnbeta.com/articles/283247.htm
					$ret=preg_match("/uu=(.*?)&vu=(.*?)&/i",$src,$temp);
					if($ret)
					{
						$uu=$temp[1];
						$vu=$temp[2];
						$url="http://api.letvcloud.com/gpc.php?page_url=-&ran=0.808693562168628&sign=726c0cdbce1fcb06a94a19cc625d5cb7&uu=$uu&cf=flash&auto_play=0&format=xml&ver=2.1&source=letv&gpcflag=1&vu=$vu";
						$pageContent=curlexec($url);
						
						$videoSrc=preg_match("/<m3u8><url><!\[CDATA\[(.*?)\]/i",$pageContent,$temp)?$temp[1]:"";
						$posterImageSrc=preg_match("/\"picStartUrl\":\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
						$trans = array("\/"  => "/");
                        $posterImageSrc = strtr($posterImageSrc, $trans);
					}
				}
				else if(strpos($src,"washingtonpost.com")!==FALSE)
                {
                    //http://www.cnbeta.com/articles/284489.htm
				}
				else if(strpos($src,"pps.tv")!==FALSE)
                {
                    //http://news.cnblogs.com/n/205997/ 
					//参在js中
				    //http://m.ipd.pps.tv/public/aj_html5_player.php?jsonp_callback=j&url_key=3ELV05&_=
					$vid=preg_match("/sid\/(.*?)\/v.swf/i",$src,$temp)?$temp[1]:"";
					
					$url="http://v.pps.tv/play_$vid.html";//m.pps.tv
                    $pageContent=curlexec($url);
					$posterImageSrc=preg_match("/\"sharepic\":\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
					$trans = array("\/"  => "/");
                    $posterImageSrc = strtr($posterImageSrc, $trans);
	
					/*//或者
					$url="http://api.ipd.pps.tv/flash/playend_recommend.php?url_key=".$vid;
                    $pageContent=curlexec($url);
					$posterImageSrc=preg_match("/\"video_face\":\"(.*?)\"/i",$pageContent,$temp)?$temp[1]:"";
					$trans = array("\/"  => "/");
                    $posterImageSrc = strtr($posterImageSrc, $trans);
					*/					
				}
	
                //add other here ...
                
                if(!$replaced)
                {
                    $content=preg_replace("/<embed[\s\S]+?(\/>|\s*<\/embed>)/i","<video src=\"".$videoSrc."\" poster=\"".$posterImageSrc."\"></video>", $content,1);
	
					if($videoSrc=="")
					{
						date_default_timezone_set('Asia/Shanghai');//'Asia/Shanghai'   亚洲/上海
						$filePathName="../logs/video_parse_error_".date("Y-m-d",time()).".log";
						log2File($filePathName,$originUrl);
					}
				}
            }
        }
       
	    //check object again
        $ret=preg_match("/<object[\s\S]+?<\/object>/i",$content);
        if($ret)
        {
			$hasVideo=true;
	
            $content=preg_replace("/<object[^>]*?(\sid=\".*?\")?[^>]*?>[\s\S]+?<\/object>/i", "<video \\1 src=\"\"></video>",$content);
			
			date_default_timezone_set('Asia/Shanghai');//'Asia/Shanghai'   亚洲/上海
			$filePathName="../logs/video_parse_error_".date("Y-m-d",time()).".log";
			log2File($filePathName,$originUrl);
        }
		
		if($hasVideo)
		{
			$tagArray[]="视频";
		}
        
        //delete useless <param>
        $content=preg_replace("/<param[^>]*>/i", "",$content);
		$content=preg_replace("/<object[^>]*>/i", "",$content);
        
        return $content;
    }

    function parseSohuVideo($vid,&$videoSrc,&$posterImageSrc)
    {
        $url="http://hot.vrs.sohu.com/vrs_flash.action?vid=".$vid;
        //$url="http://wangweike.yeskj.info/hot.vrs.sohu.com.php?vid=".$vid;
		$jsonContent=curlexec($url);
        //$jsonContent=fileGetContents($url);
        $posterImageSrc=preg_match("/\"coverImg\":\"(.*?)\"/i",$jsonContent,$temp)?$temp[1]:"";
        //clipsURL中的值不能访问
        $videoUri=preg_match("/\"su\":\[\"(.*?)\"\]/i",$jsonContent,$temp)?$temp[1]:"";
        if($videoUri!="")
        {
            $videoSrc="http://data.vod.itc.cn/?new=".$videoUri;
        }
        else
        {
            $videoSrc=preg_match("/\"clipsURL\":\[\"(.*?)\"\]/i",$jsonContent,$temp)?$temp[1]:"";
            $videoSrc=format_url($videoSrc,"http://");
        }
    }

    function parseTudouVideo($icode,&$videoSrc,&$posterImageSrc)
    {
        $iid="";
        
        $url="http://www.tudou.com/programs/view/".$icode."/";
        $tudouPageContent=curlexec($url);
        $ret=preg_match("/.*?iid:(.*?)\n.*/i",$tudouPageContent,$iids);
        if($ret)
        {
            $iid=$iids[1];
            $iid=trim($iid);
        }
        //,pic: 'http://i2.tdimg.com/174/677/671/p.jpg'
        $posterImageSrc=preg_match("/,pic:\s*\'(.*?)\'/i",$tudouPageContent,$temp)?$temp[1]:"";

        if($iid!="")
        {
            $videoSrc="http://vr.tudou.com/v2proxy/v2.m3u8?it=".$iid;//."&st=2&pw=";
        }
    }

    function getTitle($results)
    {
        $title=preg_match("/<title>(.*?)<\/title>/i",$results,$temp) ? $temp[1]:"";
        if($title!="")
        {
            $realTitle=preg_match("/(.*?)\s*[-\|_]/i",$title,$temp) ? trim($temp[1]):"";
            if($realTitle!="")
            {
                $title="<title>".$realTitle."</title>";
            }
            else
            {
                $title="<title>".$title."</title>";
            }
        }
        return $title;
    }
?>

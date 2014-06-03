<?php
    include_once("../controller/getArticle.function.php"); 
	include_once("../controller/db.function.php"); 
	include_once("../common/item.class.php"); 
	
	set_time_limit(30);
		
	$url=NULL;

	if(isset($_GET['id']))
	{
		$articleId=base64_decode($_GET['id']); 
		
		$tableName="list_table";
		$con=dbConnect();
		$item=Item::queryItemById($articleId,$tableName);
		$url=$item->href;
		//增加一次阅读数
        Item::addOneReadTimes($articleId,$tableName);
		dbClose($con);
	}
	else
	{
		$url=$_GET['url'];
		$url=base64_decode($url);
	}
	
	date_default_timezone_set('Asia/Shanghai');
	$filePathName="../logs/article_view_".date("Y-m-d",time()).".log";
	log2File($filePathName,$url);
	
	$title="";
	$content="";
	
	if($url!="")
	{
		$tagArray=array();
		$content=getContent($url,$tagArray);
		
		$title=preg_match("/<title>(.*?)<\/title>/i",$content,$temp)?$temp[1]:"";
		$content=preg_replace("/<title>.*?<\/title>/i", "", $content);
		
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
		else
		{
			//$content="<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>".$content;
		}
	}
?>

<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" lang="zh-CN">
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" lang="zh-CN">
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html lang="zh-CN">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width" />
<title>《程序员读》 | <?php echo $title; ?></title>
<!--[if lt IE 9]>
<script src="http://www.brogrammer.cn/wp-content/themes/twentytwelve/js/html5.js" type="text/javascript"></script>
<![endif]-->
<link rel='stylesheet' id='twentytwelve-style-css'  href='http://www.brogrammer.cn/wp-content/themes/twentytwelve/style.css?ver=3.9.1' type='text/css' media='all' />
<!--[if lt IE 9]>
<link rel='stylesheet' id='twentytwelve-ie-css'  href='http://www.brogrammer.cn/wp-content/themes/twentytwelve/css/ie.css?ver=20121010' type='text/css' media='all' />
<![endif]-->
<script type='text/javascript' src='http://www.brogrammer.cn/wp-includes/js/jquery/jquery.js?ver=1.11.0'></script>
<script type='text/javascript' src='http://www.brogrammer.cn/wp-includes/js/jquery/jquery-migrate.min.js?ver=1.2.1'></script>
<meta name="generator" content="WordPress 3.9.1" />
<meta name="google-publisher-plugin-pagetype" content="singlePost"><meta name="google-site-verification" content="roWRF305jjJDXD5YIqAZCn7OTooLqjXXfEaqPKfzxb0" />	<style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
	<style type="text/css" id="twentytwelve-header-css">
			.site-header h1 a,
		.site-header h2 {
			color: #e54016;
		}
		</style>
	<style type="text/css" id="custom-background-css">
body.custom-background { background-color: #507c9e; }
</style>
</head>

<body class="single single-post postid-258 single-format-standard custom-background custom-font-enabled single-author">
<div id="page" class="hfeed site">
	<header id="masthead" class="site-header" role="banner">
		<hgroup>
			<h1 class="site-title"><a href="http://www.brogrammer.cn/" title="程序员读" rel="home">程序员读</a></h1>
			<h2 class="site-description"><a href="http://www.brogrammer.cn/" title="程序员读" rel="home">移动开发 brogrammer.cn</a></h2>
		</hgroup>
	</header><!-- #masthead -->

	<div id="main" class="wrapper">
	<div id="primary" class="site-content">
	<div id="content" role="main">
	
	<article id="post-258" class="post-258 post type-post status-publish format-standard hentry category-ios">
	<header class="entry-header"><h1 class="entry-title"><?php echo $title; ?></h1></header><!-- .entry-header -->
	<div class="entry-content">
	<?php echo $content; ?>
	</div><!-- .entry-content -->
	</article><!-- #post -->
    
	</div><!-- #content -->
    
    <header id="masthead" class="site-header" role="banner">
	<h2 class="site-description"><a href="http://www.brogrammer.cn/" title="程序员读" rel="home">程序员读 brogrammer.cn</a></h2>
	</header><!-- #masthead -->
    
	</div><!-- #primary -->
    </div><!-- #main -->
</div><!-- #page -->
</body>
</html>
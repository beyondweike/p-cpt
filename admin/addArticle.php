<?php
    include_once("../common/item.class.php");
	include_once("../controller/db.function.php");
    
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

	if (isset($_GET['url'])
        && isset($_GET['title'])
        && isset($_GET['categoryCode']))
	{
		$url=$_GET['url'];
        $title=$_GET['title'];
        $categoryCode=$_GET['categoryCode'];
        $thumbnailUrl=$_GET['thumbnailUrl'];
        $briefDesc=$_GET['briefDesc'];

        $item=new Item();
        $item->href=$url;
        $item->title=$title;
        $item->categoryCode=$categoryCode;
        $item->thumbnailUrl=$thumbnailUrl;
        $item->briefDesc=$briefDesc;
        
        $tableName="list_table";
        $categoryPriorityDic=NULL;
        
        $con=dbConnect();
        $ret=$item->insertOrUpdateItemToDatabase($tableName,$categoryPriorityDic);
        dbClose($con);
        
        if($ret)
        {
            echo "添加成功 ".time();
        }
        else
        {
            echo "添加失败 ".time();
        }
    }
?>

<?php
    include_once("../common/Category.class.php");
    include_once("asynCall.function.php");

	$productCode=0;

    $path="../it/config/resource/categories.json";
    $category=new Category();
    $category->parsePath($path);
    
    $categoryCodeArray=array();
    $category->getCategoryCodeArray($categoryCodeArray);
    
    foreach($categoryCodeArray as $categoryCode)
    {
        callAsynCaptureList($productCode,$categoryCode);
    }
    
    echo "OK ".count($categoryCodeArray);
?>

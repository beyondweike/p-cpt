<?php

	include_once("../controller/db.function.php");
	include_once("../common/request.function.php");
	
	set_time_limit(60*10);
	
	$url="http://www.brogrammer.cn/reading/admin/updateFields.php";
	
	$srcTableName = "list_table";
	$destTableName = "list_table";
	$fieldNameArray = array("id","title","briefDesc","tags");
	$recordSeperator = "__brogrammer_wwk_capture_recordSeperator__";
	$fieldSeperator = "__brogrammer_wwk_capture_fieldSeperatorr__";
	
	$fieldCount=count($fieldNameArray);
	$fieldNames = "";
	foreach ($fieldNameArray as $fieldName)
    {
		if($fieldNames != "")
		{
			$fieldNames .= ",";
		}
		$fieldNames .= $fieldName;
	}
	
	$con=dbConnect();
	
	$records="";
	
	$sql="select $fieldNames from $srcTableName where id>=70001 and id<=74171";
	$result=mysql_query($sql);
	while($row = mysql_fetch_array($result))
	{
		if($records!="")
		{
			$records.=$recordSeperator;
		}
		
		for($i=0;$i<$fieldCount;$i++)
		{
			$records.=$row[$i];
			if($i<$fieldCount-1)
			{
				$records.=$fieldSeperator;
			}
		}		
	}
	
	dbClose($con);
	
	$params = array();
	$params['tableName'] = $destTableName;
	$params['fieldNames'] = $fieldNames;
	$params['records'] = $records;
	$params['recordSeperator'] = $recordSeperator;
	$params['fieldSeperator'] = $fieldSeperator;
	
	////$results=postCurlExec($url,$params,600);	
	
	echo $results;
	
?>

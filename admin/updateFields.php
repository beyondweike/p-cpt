<?php

	include_once("../controller/db.function.php");
	include_once("../common/string.function.php");
	
	return;
	
	set_time_limit(60*10);

	$tableName=$_POST['tableName'];
	$fieldNames=$_POST['fieldNames'];
	$records=$_POST['records'];
	$recordSeperator=$_POST['recordSeperator'];
	$fieldSeperator=$_POST['fieldSeperator'];
	
	$fieldNameArray = explode(",",$fieldNames);
	$recordArray = explode($recordSeperator,$records);

	$con=dbConnect();
	
	$result=false;
	$faildId=0;
	
	foreach ($recordArray as $record)
	{
		$fieldValueArray = explode($fieldSeperator,$record);
		
		if(count($fieldValueArray)>0)
		{
			$sql="update ".$tableName." set ";
			
			$count=count($fieldValueArray);
			for ($i=1;$i<$count;$i++)
			{
				$value = myReplace($fieldValueArray[$i]);
				$sql.=$fieldNameArray[$i]."='".$value."'";
				if($i<$count-1)
				{
					$sql.=",";
				}
			}
		
			$sql.=" where ".$fieldNameArray[0]."=".$fieldValueArray[0];
			
			$result = mysql_query($sql);
			if($result===FALSE)
			{
				echo $sql;
				
				die(mysql_error());
				
				$faildId=$fieldValueArray[0];
				break;
			}
		}
	}

	dbClose($con);
	
	echo json_encode(array('success'=>$result,'faildId'=>$faildId));
	
	function myReplace($content)
	{
		$trans = array("'"       => "\'",
						"\\'\'"       => "\'"
                      );
        $content = strtr($content, $trans);
		return $content;
	}

?>

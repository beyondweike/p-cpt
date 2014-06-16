<?php

	function jsonEncodeKeyStringPair($key,$value)
	{
		$trans = array(
                        "\n"    => "\\n",
						"\""    => "\\\""
						
				  );
		$value = strtr($value, $trans);
		
		$json="\"$key\":\"$value\"";
		
		return $json;
	}
	
	function jsonEncodeKeyNumberPair($key,$value)
	{
		$json="\"$key\":$value";
		
		return $json;
	}
	
	function jsonEncodePair($pari)
	{
		return jsonEncodePairs(array($pari));
	}
	
	function jsonEncodePairs($paris)
	{
		$json="{";
	
		foreach($paris as $pari)
		{
			$json.=$pari.",";
		}
		
		if(count($paris)>0)
		{
			$json=substr_replace($json,"",strlen($json)-1,1);
		}
		
		$json.="}";
		
		return $json;
	}
	
	function jsonEncodePairVariables()
	{
		//echo func_num_args();         //输出参数个数
		//echo func_get_arg;       //获取单个参数
                  
		$pairs = func_get_args();     //获取参数，返回参数数组
        return jsonEncodePairs($pairs);
	}
	
	function jsonEncodeKeyObjectPair($key,$object)
	{
		$json="\"$key\":";
		$json.=$object;
		
		return $json;
	}
	
	function jsonEncodeKeyObjectsPair($key,$objects)
	{
		$json="\"$key\":[";
		
		foreach($objects as $object)
		{
			$json.=$object.",";
		}
		
		if(count($objects)>0)
		{
			$json=substr_replace($json,"",strlen($json)-1,1);
		}
		
		$json.="]";
		
		return $json;
	}

?>

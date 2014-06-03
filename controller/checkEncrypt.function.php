<?php
    include_once("properties.class.php");

	function checkEncrypt($encrypt)
	{
		$valide=false;
		
		$properties=Properties::getProperties();
		$encrypt1=$properties->lastEncrypt;
		$encrypt2=$properties->encrypt;
		
		if($encrypt==$encrypt1 || $encrypt==$encrypt2)
		{
			$valide=TRUE;
		}
		
		return $valide;
	}
	
	
?>

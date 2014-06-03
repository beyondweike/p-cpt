<?php

    function mysqlConnect($dbhost,$dbname,$username,$password)
	{
        $con = mysql_connect($dbhost,$username,$password);
        if ($con)
        {
			mysql_select_db($dbname, $con);
        }
        else
        {
			die('Could not connect: ' . mysql_error());
        }
		
        return $con;
	}
	
	function mysqlClose($con)
	{
		mysql_close($con);
	}
    
?>

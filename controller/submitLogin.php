<?php
    include_once("db.function.php");
	include_once("properties.class.php");
    include_once("service.class.php");
    include_once("../common/user.class.php");
	
    header("Content-Type:text/html; charset=utf-8");
    
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
		return "非法登录";
	}

	$ret=0;
    $userId=-1;
    $email="";
    $authority=0;
    $message="登录失败";
    
	if (isset($_POST['username']))
	{
        $username=$_POST['username'];
        $password=$_POST['password'];
        
        $con=dbConnect();
        
        $user=User::queryUserByUserName($username);
        if($user)
        {
            if($user->password===$password)
            {
                $ret=1;
                
                $userId=$user->id;
                $email=$user->email;
                $authority=$user->authority;
                
                $message="登录成功";
            }
            else
            {
                $message="密码错误";
            }
        }
        else
        {
            $message="用户不存在";
        }

        dbClose($con);
    }

	echo json_encode(array('success'=>$ret,'message'=>$message,'userId'=>intval($userId),'email'=>$email,'authority'=>intval($authority)));
?>

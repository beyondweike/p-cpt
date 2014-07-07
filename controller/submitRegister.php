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
	
	if(!$valide)
	{
		return NULL;
	}

	$ret=false;
    $userId=-1;
	$message=NULL;

	$username=$_POST['username'];
	$password=$_POST['password'];
	$email=$_POST['email'];
	$deviceId=$headers["deviceid"];//must use lower case
	
	if(strlen($email)==0)
	{
		$message="邮箱不能为空";
	}
	else
	{
		$con=dbConnect();
		
		$maxRegisterCount=4;
		$registerCount=User::queryUserCountByDeviceId($deviceId);
		if($registerCount>=$maxRegisterCount)
		{
			$message="您注册的账号过多";
		}
		
		if(!$message)
		{
			$user=User::queryUserByUserName($username);
			if($user)
			{
				$message="用户名已存在";
			}
			
			if(!$message)
			{
				$user=User::queryUserByEmail($email);
				if($user)
				{
					$message="邮箱已存在";
				}
				
				if(!$message)
				{
					$user=new User();
					$user->username=$username;
					$user->password=$password;
					$user->email=$email;
					$user->deviceId=$deviceId;
					
					$ret=$user->insertToDatabase();
					if($ret)
					{
						$userId=$user->id;
						$message="注册成功";
					}
					else
					{
						$message="服务器忙";
					}
				}
				
			}
		}
		
		dbClose($con);
	}
	
    echo json_encode(array('success'=>$ret,"message"=>$message,'userId'=>intval($userId)));
?>

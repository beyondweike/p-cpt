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
		return NULL;
	}

	$ret=0;
    $userId=-1;
	$message="";

	$username=$_POST['username'];
	$password=$_POST['password'];
	$email="";
	if (isset($_POST['email']))
	{
		$email=$_POST['email'];
	}
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
		if($registerCount<$maxRegisterCount)
		{
			$user=User::queryUserByUserName($username);
			if(!$user)
			{
				if($email!="")
				{
					$user=User::queryUserByEmail($email);
				}
				
				if(!$user)
				{
					$user=new User();
					$user->username=$username;
					$user->password=$password;
					$user->email=$email;
					$user->deviceId=$deviceId;
					
					$ret=$user->insertToDatabase();
					if($ret)
					{
						$ret=1;
						$userId=$user->id;
					}
					
					$message="服务器忙";
				}
				else
				{
					$message="邮箱已存在";
				}
			}
			else
			{
				$message="用户名已存在";
			}
		}
		else
		{
			$message="您注册的账号过多";
		}
		
		dbClose($con);
	}
	
    echo json_encode(array('success'=>$ret,"message"=>$message,'userId'=>$userId));
?>

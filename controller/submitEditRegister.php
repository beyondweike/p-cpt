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
	$message=NULL;

	$userId=$_POST['userId'];
	$oldPassword=$_POST['oldPassword'];
	$password=$_POST['password'];
	$email=$_POST['email'];
	$username=NULL;
	if(isset($_POST['username']))
	{
		$username=$_POST['username'];
	}
	
	if(strlen($email)==0)
	{
		$message="邮箱不能为空";
	}
	else
	{
        $con=dbConnect();

        $user=User::queryUser($userId);
        if(!$user)
        {
			if($username)
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
					$message="资料修改成功";
				}
				else
				{
					$message="服务器忙";
				}
			}
			else
			{
				$message="用户不存在";
			}
        }
        
		if(!$message)
		{
            if($user->password!=$oldPassword)
            {
                $message="原密码错误";
            }
            
			if(!$message)
			{
                $user->email=$email;
                $user->password=$password;
                
                $ret=$user->updateToDatabase();
                if($ret)
                {
                    $message="资料修改成功";
                }
                else
                {
                    $message="资料修改失败";
                }
            }
        }
		
        dbClose($con);
    }

    echo json_encode(array('success'=>$ret,"message"=>$message,'code'=>0));
?>

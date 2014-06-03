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
	$message="";

	if (isset($_POST['userId']))
	{
        $userId=$_POST['userId'];
        $oldPassword=$_POST['oldPassword'];
        $password=$_POST['password'];
        $email="";
        if (isset($_POST['email']))
        {
            $email=$_POST['email'];
        }

        $con=dbConnect();

        $user=User::queryUser($userId);
        if(!$user)
        {
            $message="用户不存在";
        }
        else
        {
            if($user->password!=$oldPassword)
            {
                $message="原密码错误";
            }
            else
            {
                if (isset($_POST['email']))
                {
                    $email=$_POST['email'];
					if(strlen($email)>0)
					{
						$user->email=$email;
					}
                }
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

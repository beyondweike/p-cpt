<?php
    include_once("item.class.php");
    
	class ExtractItem
	{
		var $id=0;
        var $content="";
		var $articleId=0;
		var $userId=0;
		var $extractTime="";
		var $serviceCode=0;
		
		//abstract protected function parse($htmlContent);

		function parseRow($row)
		{ 
			$this->id = $row['id'];
            $this->content = $row['content'];
			$this->extractTime = $row['extractTime'];
			$this->articleId = $row['articleId'];
			$this->userId = $row['userId'];
			$this->serviceCode = $row['serviceCode'];
		}

		 function insertItemToDatabase()
		 {
			 if($this->extractTime=="")
			 {
				 date_default_timezone_set('Asia/Shanghai');//'Asia/Shanghai'   亚洲/上海
				 $this->extractTime=date("Y-m-d H:i:s",time());
			 }
			 
			 $ret=false;

			 if($this->id>0)
			 {
				 $sql="update extract_table set content='".$this->content."',".
												"articleId=".$this->articleId.",".
												"userId=".$this->userId.",".
												"extractTime='".$this->extractTime."',".
												"serviceCode=".$this->serviceCode." where id=".$this->id;
				 $ret=mysql_query($sql);
			 }
			 else
			 {
				  $sql="INSERT INTO extract_table(content, articleId, userId, extractTime, serviceCode) ".
				 " VALUES('".$this->content."',".$this->articleId.", ".$this->userId.", '".$this->extractTime."', ".$this->serviceCode.")";
				 $ret=mysql_query($sql);
				 
				 if($ret)
				 {
					 $this->id=mysql_insert_id();
				 }
			 }

			 return $ret;
		 }
        
        public static function deleteItems($ids)
        {
			$sql="delete FROM extract_table where id in (".$ids.")";
			$ret=mysql_query($sql);
			
			return $ret;
        }

		 public static function queryNewest($topItemId,$userId,$serviceCode,$pageSize)
		 {
			$arr = NULL;

			$sql="";
			if($topItemId>0)
			{
				$sql="SELECT * FROM extract_table where serviceCode=".$serviceCode." and id>".$topItemId." and userId=".$userId." order by id desc limit 0,".$pageSize;
			}
			else
			{
                $sql="SELECT * FROM extract_table where serviceCode=".$serviceCode." and userId=".$userId." order by id desc limit 0,".$pageSize;
			}

			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result))
			{
			  $item=new ExtractItem();
			  $item->parseRow($row);

			  if(!$arr)
			  {
				$arr = array();
			  }

			  $arr[] = $item;
			}

			return $arr;
		 }

		 public static function queryMore($lastItemId,$userId,$serviceCode,$pageSize)
		 {
			$arr = NULL;

			$sql="";
			if($lastItemId>0)
			{
                $sql="SELECT * FROM extract_table where serviceCode=".$serviceCode." and id<".$lastItemId." and userId=".$userId." order by id desc limit 0,".$pageSize;
			}
			else
			{
                $sql="SELECT * FROM extract_table where serviceCode=".$serviceCode." and userId=".$userId." order by id desc limit 0,".$pageSize;
			}

			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result))
			{
			  $item=new ExtractItem();
			  $item->parseRow($row);

			  if(!$arr)
			  {
				$arr = array();
			  }

			  $arr[] = $item;
			}

			return $arr;
		 } 
		 
		 public static function queryAllExtracts()
		 {
			$arr = NULL;

            $sql="SELECT * FROM extract_table where serviceCode=0 order by id";

			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result))
			{
			  $item=new ExtractItem();
			  $item->parseRow($row);

			  if(!$arr)
			  {
				$arr = array();
			  }

			  $arr[] = $item;
			}

			return $arr;
		 }
	}
?>

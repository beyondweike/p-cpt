<?php
	class Category
	{
		var $name="";
		var $code=-1;
		var $contentType=-1;
		var $priority=0;
        var $categories=NULL;
		var $cptStepCount=1;//抓取分几步完成
		var $cptPeriodHour=6;//抓取间隔
		
		public function parsePath($path)
		{ 
			$content = getLocalContents($path);
            $arr = json_decode($content,true); //解析为数组
            $this->parseArray($arr);
		}
        
        public function parseArray($arr)
        {
			if(isset($arr["name"]))
            {
                $this->name=$arr["name"];
            }
            if(isset($arr["code"]))
            {
                $this->code=$arr["code"];
            }
			if(isset($arr["contentType"]))
            {
                $this->contentType=$arr["contentType"];
            }
			if(isset($arr["priority"]))
            {
                $this->priority=$arr["priority"];
            }
  			if(isset($arr["cptStepCount"]))
            {
                $this->cptStepCount=$arr["cptStepCount"];
            }
  			if(isset($arr["cptPeriodHour"]))
            {
                $this->cptPeriodHour=$arr["cptPeriodHour"];
            }
			
            /*
             print_r($this->name);print_r("<br>");
             print_r($this->code);print_r("<br>");
             print_r($this->contentType);print_r("<br>");
             print_r($this->priority);print_r("<br>");
             print_r("-------<br><br>");*/
            
			if(isset($arr["categories"]))
            {
                $tempSubArray=$arr["categories"];

                $this->categories=array();
                foreach ($tempSubArray as $subItem)
                {
                    $category=new Category();
                    $category->parseArray($subItem);
                    $this->categories[]=$category;
                }
            }
        }
        
        /*
        public function getPriority($categoryCode)
        {
            $priority=-1;
            
            if($this->code==$categoryCode)
            {
                $priority=$this->priority;
            }
            else
            {
                foreach ($this->categories as $category)
                {
                    $priority=$category->getPriority($categoryCode);
                    if($priority>=0)
                    {
                        break;
                    }
                }
            }
            
            return $priority;
        }
        */
		
		public function getSubCategory($subCategoryCode)
		{
			$subCategory=NULL;
			
			if($this->categories)
            {
                foreach ($this->categories as $tempSubCategory)
                {
                    if($tempSubCategory->code==$subCategoryCode)
					{
						$subCategory=$tempSubCategory;
					}
					else
					{
						$subCategory=$tempSubCategory->getSubCategory($subCategoryCode);
					}
					
					if($subCategory)
					{
						break;
					}
                }
            }
			
			return $subCategory;
		}
        
        public function getCategoryPriorityDic(&$arr)
        {
            if($this->code>=0)
            {
                $arr["0".$this->code]=$this->priority;
            }
            
            if($this->categories)
            {
                foreach ($this->categories as $category)
                {
                    $category->getCategoryPriorityDic($arr);
                }
            }
        }
        
        public function getCategoryCodeArray(&$arr)
        {
            if($this->code>=0)
            {
                $arr[]=$this->code;
            }
            
            if($this->categories)
            {
                foreach ($this->categories as $category)
                {
                    $category->getCategoryCodeArray($arr);
                }
            }
        }
	}
?>

<?php
    include_once("../common/captureListBase.class.php");
    
    include_once("captureCsdnList.php");
	include_once("captureCocoaChinaList.php");
	include_once("captureEoeList.php");
	include_once("captureYoukuList.php");
	include_once("captureYeskyList.php");
	include_once("captureAndroidStudyList.php");//更新慢
	include_once("captureAqeeList.php");
    include_once("captureCnblogsList.php");
    include_once("captureW3cfunsList.php");
    include_once("captureIpadownList.php");
    include_once("captureGamerboomList.php");
    include_once("shushaoListCapture.php");
	include_once("jobboleListCapture.php");
    include_once("36krListCapture.php");
	include_once("gamelookListCapture.php");
    include_once("tech163ListCapture.php");
    include_once("woshipmListCapture.php");
    include_once("cnbetaListCapture.php");
	include_once("captureQianduanList.php");//更新慢
    include_once("cocos2devListCapture.php");
    include_once("jbxueListCapture.php");
	include_once("tech9ListCapture.php");
	include_once("tuicoolListCapture.php");

	class CaptureItList extends CaptureListBase
	{		
		function captureCategory00List($categoryCode,$tableName,$categoryPriorityDic,$cptStep)
		{
			$newCount=0;
			
			switch($cptStep)
			{
				case 1:
					{
						//1页
						$newCount+=capture36krNewsListPages($categoryCode,$tableName,$categoryPriorityDic);
						//1页
						$newCount+=captureTech163NewsListPages($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 2:
					{
						//2页
						$newCount+=captureCsdnListPages($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 3:
					{
						//1页
						$newCount+=captureWoshipmITNewsListPages($categoryCode,$tableName,$categoryPriorityDic);
						//1页
						$newCount+=captureEoeNewsListPages($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 4:
					{
						//3页
						$newCount+=captureCnblogsNewsListPages($categoryCode,$tableName,$categoryPriorityDic);//3
					}
					break;
				case 5:
					{
						//2页
						$newCount+=captureCnbetaScienceNewsListPagesStep1($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 6:
					{
						$newCount+=captureCnbetaScienceNewsListPagesStep2($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 7:
					{
						$newCount+=captureCnbetaScienceNewsListPagesStep3($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 8:
					{
						$newCount+=captureCnbetaScienceNewsListPagesStep4($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 9:
					{
						$newCount+=captureCnbetaScienceNewsListPagesStep5($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 10:
					{
						$newCount+=captureCnbetaScienceNewsListPagesStep6($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 11:
					{
						$newCount+=captureCnbetaScienceNewsListPagesStep7($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 12:
					{
						$newCount+=captureCnbetaScienceNewsListPagesStep8($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 13:
					{
						$newCount+=captureCnbetaScienceNewsListPagesStep9($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 14:
					{
						$newCount+=captureCnbetaScienceNewsListPagesStep10($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 15:
					{
						$newCount+=captureCnbetaScienceNewsListPagesStep11($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 16:
					{
						$newCount+=captureCnbetaScienceNewsListPagesStep12($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
			}
			
			return $newCount;
		}
		
		function captureCategory02List($categoryCode,$tableName,$categoryPriorityDic,$cptStep)
		{
			$newCount=0;
			
			switch($cptStep)
			{
				case 1:
					{
                        //1页
                        $newCount+=captureWoshipmInterviewListPages($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 2:
					{
						//2页
						$newCount+=captureJobboleProgrammerLifeListPages1($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 3:
					{
						//2页
						$newCount+=captureJobboleProgrammerLifeListPages2($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 4:
					{
						//3页
						$newCount+=captureJobboleProgrammerLifeListPages3($categoryCode,$tableName,$categoryPriorityDic);
                        
					}
					break;
				case 5:
					{
						//2页
						$newCount+=captureCnblogsProgramLifeListPages1($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 6:
					{
						//2页
						$newCount+=captureCnblogsProgramLifeListPages2($categoryCode,$tableName,$categoryPriorityDic);
						
					}
					break;
				case 7:
					{
						//2页
						$newCount+=captureCnblogsProgramLifeListPages3($categoryCode,$tableName,$categoryPriorityDic);
						
					}
					break;
				case 8:
					{
						//2页
						$newCount+=captureCnblogsProgramLifeListPages4($categoryCode,$tableName,$categoryPriorityDic);
                        
					}
					break;
				case 9:
					{
						//更新慢
						$newCount+=captureAndroidStudyHealthyListPages($categoryCode,$tableName,$categoryPriorityDic);
						$newCount+=captureAndroidStudyInterviewListPages($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 10:
					{
						//更新慢
						//2页		
						$newCount+=captureCnbetaProgramLifeNewsListPages($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
			}
	
			return $newCount;
		}
		
		function captureCategory10List($categoryCode,$tableName,$categoryPriorityDic,$cptStep)
		{
			$newCount=0;
			
			switch($cptStep)
			{
				case 1:
					{
						//2页
						$newCount+=captureCnbetaAppleNewsListPages1($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 2:
					{
						//1页
						$newCount+=captureCnbetaAppleNewsListPages2($categoryCode,$tableName,$categoryPriorityDic);
						 //1页
						$newCount+=captureShuShaoIOSNewsListPages($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 3:
					{
						//1页
						$newCount+=captureIpadownNewsListPages($categoryCode,$tableName,$categoryPriorityDic);
						//更新慢
						//1页
						$newCount+=captureYeskyIosNewsListPages($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
			}
	
			return $newCount;
		}
		
		function captureCategory20List($categoryCode,$tableName,$categoryPriorityDic,$cptStep)
		{
			$newCount=0;
			
			switch($cptStep)
			{
				case 1:
					{
                        //1页
						$newCount+=captureCnbetaAndroidNewsListPages1($categoryCode,$tableName,$categoryPriorityDic);

						//更新慢
						//1页
                        $newCount+=captureYeskyAndroidNewsListPages($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 2:
					{
						//2页
						$newCount+=captureCnbetaAndroidNewsListPages2($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 3:
					{
						//2页
						$newCount+=captureCnbetaAndroidNewsListPages3($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
			}
	
			return $newCount;
		}
		
		function captureCategory21List($categoryCode,$tableName,$categoryPriorityDic,$cptStep)
		{
			$newCount=0;
			
			switch($cptStep)
			{
				case 1:
					{
                        //1页
                        $newCount+=captureCnblogsAndroidDevListPages($categoryCode,$tableName,$categoryPriorityDic);
						
						//更新慢
						//1页
						$newCount+=captureAndroidStudyAndroidDevListPages1($categoryCode,$tableName,$categoryPriorityDic);
						
					}
					break;
				case 2:
					{
						//2页
						$newCount+=captureAndroidStudyAndroidDevListPages2($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
			}
	
			return $newCount;
		}
	
		function captureCategory30List($categoryCode,$tableName,$categoryPriorityDic,$cptStep)
		{
			$newCount=0;
			
			switch($cptStep)
			{
				case 1:
					{
                        //1页
                        $newCount+=captureGamelookNewsListPages($categoryCode,$tableName,$categoryPriorityDic);
						//1页
                        $newCount+=captureCocoachinaGameNewsListPages($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 2:
					{
						//1页
						$newCount+=captureCnbetaGameNewsListPages($categoryCode,$tableName,$categoryPriorityDic);
						//1页
                        $newCount+=captureGamerboomNewsListPages($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 3:
					{
						//1页
						$newCount+=captureIpadownGameListPages($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
			}
	
			return $newCount;
		}
		
		function captureCategory31List($categoryCode,$tableName,$categoryPriorityDic,$cptStep)
		{
			$newCount=0;
			
			switch($cptStep)
			{
				case 1:
					{
						//1页
						$newCount+=captureTuicoolCocos2dListPages($categoryCode,$tableName,$categoryPriorityDic);
						
						//1页
						$newCount+=captureTech9Cocos2dListPages($categoryCode,$tableName,$categoryPriorityDic);
						
                        //更新慢,网页有修改...
						//1页
                        //$newCount+=captureCocos2devListPages($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 2:
					{
						//1页
						$newCount+=captureTech9Unity3dListPages($categoryCode,$tableName,$categoryPriorityDic);
						//1页
						$newCount+=captureTech9Genesis3dListPages($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
			}
	
			return $newCount;
		}
		
		function captureCategory41List($categoryCode,$tableName,$categoryPriorityDic,$cptStep)
		{
			$newCount=0;
			
			switch($cptStep)
			{
				case 1:
					{
						//2页
                        $newCount+=captureCnblogsWebDevListPages1($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 2:
					{
						//2页
                        $newCount+=captureCnblogsWebDevListPages2($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
			}
	
			return $newCount;
		}
		
		function captureCategory60List($categoryCode,$tableName,$categoryPriorityDic,$cptStep)
		{
			$newCount=0;
			
			switch($cptStep)
			{
				case 1:
					{
						//2页
                        $newCount+=captureCnblogsProgramDesignListPages1($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 2:
					{
						//2页
                        $newCount+=captureCnblogsProgramDesignListPages2($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 3:
					{
						//2页
						$newCount+=captureCnblogsProgramDesignListPages3($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
			}
	
			return $newCount;
		}
		

		function captureCategory61List($categoryCode,$tableName,$categoryPriorityDic,$cptStep)
		{
			$newCount=0;
			
			switch($cptStep)
			{
				case 1:
					{
						//2页
                        $newCount+=captureWoshipmProductDesignListPages1($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 2:
					{
						//2页
                        $newCount+=captureWoshipmProductDesignListPages2($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
				case 3:
					{
						//2页
						$newCount+=captureJobboleProductManagementListPages($categoryCode,$tableName,$categoryPriorityDic);
						//1页
						$newCount+=captureCnblogsProductDesignListPages($categoryCode,$tableName,$categoryPriorityDic);
					}
					break;
			}
	
			return $newCount;
		}
		
		function captureCategory62List($categoryCode,$tableName,$categoryPriorityDic,$cptStep)
		{
			$newCount=0;
			
			switch($cptStep)
			{
				case 1:
					{
						//2页
						$newCount+=captureWoshipmMarketOperationListPages($categoryCode,$tableName,$categoryPriorityDic);
			
					}
					break;
				case 2:
					{
						//1页					
						$newCount+=captureCocoachinaMarketNewsListPages1($categoryCode,$tableName,$categoryPriorityDic);
			
					}
					break;
			}
		//4页，慢					//$newCount+=captureCocoachinaMarketNewsListPages1($categoryCode,$tableName,$categoryPriorityDic);
		
			return $newCount;
		}
	
		function captureListPages($categoryCode,$tableName,$categoryPriorityDic,$cptStep)
		{	
			$newCount=0;
			
			switch($categoryCode)
			{
				case 0:
					{
						$newCount+=$this->captureCategory00List($categoryCode,$tableName,$categoryPriorityDic,$cptStep);
					}
					break;
                case 1:
                    {
						//1页
						$newCount+=captureJobboleAnecdoteListPages($categoryCode,$tableName,$categoryPriorityDic);
						//1页
						$newCount+=captureAqeeCommentListPages($categoryCode,$tableName,$categoryPriorityDic);
                    }
                    break;
				case 2:
					{
						$newCount+=$this->captureCategory02List($categoryCode,$tableName,$categoryPriorityDic,$cptStep);
                        
					}
					break;
                    
				case 10:
					{
						$newCount+=$this->captureCategory10List($categoryCode,$tableName,$categoryPriorityDic,$cptStep);
					}
					break;
				case 11:
					{
						//3页
						$newCount+=captureCocoachinaDevNewsListPages($categoryCode,$tableName,$categoryPriorityDic);
                        
					}
					break;
				case 12:
					{
						//1页
                        $newCount+=captureCnblogsIOSDevListPages($categoryCode,$tableName,$categoryPriorityDic);
						
                        //更新慢
						//1页
						$newCount+=captureCocoachinaIOSDevListPages($categoryCode,$tableName,$categoryPriorityDic);
                        
					}
					break;

				case 20:
					{
						$newCount+=$this->captureCategory20List($categoryCode,$tableName,$categoryPriorityDic,$cptStep);
					}
					break;
				case 21:
					{
						$newCount+=$this->captureCategory21List($categoryCode,$tableName,$categoryPriorityDic,$cptStep);
					}
					break;
                    
                case 30:
                    {
						$newCount+=$this->captureCategory30List($categoryCode,$tableName,$categoryPriorityDic,$cptStep);
                    }
					break;
                case 31:
                    {
						$newCount+=$this->captureCategory31List($categoryCode,$tableName,$categoryPriorityDic,$cptStep);
                    }
					break;
                    
                case 40:
                    {
                        //更新慢
						//1页
						//$newCount+=captureQianduanNewsListPages($categoryCode,$tableName,$categoryPriorityDic);
						
                        //也不快
						//1页
                        $newCount+=captureW3cfunsNewsListPages($categoryCode,$tableName,$categoryPriorityDic);
                    }
					break;
				case 41:
                    {
                        $newCount+=$this->captureCategory41List($categoryCode,$tableName,$categoryPriorityDic,$cptStep);
                    }
					break;
                    
                case 50:
                    {
						//2页
                        $newCount+=captureCnblogsCppListPages($categoryCode,$tableName,$categoryPriorityDic);
                    }
					break;
                case 51:
                    {   
						//1页
                        $newCount+=captureCnblogsPhpListPages($categoryCode,$tableName,$categoryPriorityDic);
						//1页
						$newCount+=captureJbxuePhpDevListPages($categoryCode,$tableName,$categoryPriorityDic);
						
						//更新慢
						//1页
						//$newCount+=captureCnbetaPhpNewsListPages($categoryCode,$tableName,$categoryPriorityDic);

                    }
					break;
				case 52:
                    {   
						//1页
                        $newCount+=captureCnblogsJavaListPages($categoryCode,$tableName,$categoryPriorityDic);
						
                    }
					break;
					
				case 60:
                    {
						$newCount+=$this->captureCategory60List($categoryCode,$tableName,$categoryPriorityDic,$cptStep);
                    }
                    break;
				case 61:
                    {
						$newCount+=$this->captureCategory61List($categoryCode,$tableName,$categoryPriorityDic,$cptStep);
                    }
					break;
				case 62:
                    {		
						$newCount+=$this->captureCategory62List($categoryCode,$tableName,$categoryPriorityDic,$cptStep);
                    }
                    break;
					
			}
			
			return $newCount;
		}
	}
?>

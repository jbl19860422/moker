<?php
	function genRandomStr($length = 8) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		} 
		return $str;
	}

	function trimall($str)//删除空格
	{
	    $qian=array(" ","　","\t","\n","\r");$hou=array("","","","","");
	    return str_replace($qian,$hou,$str);    
	}
?>
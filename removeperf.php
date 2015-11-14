<?php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
if(empty($_SERVER['HTTP_REFERER']))
{
	header("location:home.php");
	$_SESSION["salesresult"]="ERROR:Invalid Entry";
	exit;
}
$host = getHost();
$glink = getLink();
$xdate=$_REQUEST["d"];
if(!empty($xdate))
{
	$idx="";
	$qx="select * from sales_report_real where ddate='".$xdate."' limit 1";
	if($result=mysql_query($qx))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
			$idx=$info["fileid"];
		}
	}
	if(!empty($idx))
	{
		$query="delete from sales_report_real where fileid='".$idx."'";
		if($result=mysql_query($query))
		{
			$_SESSION["salesresult"]="SUCCESS:All Selected Sales Deleted";
			$qx="delete from sales_report_real_m where id='".$idx."'";
			if($rex=mysql_query($qx))
				$_SESSION["salesresult"]="SUCCESS:All Selected Sales Deleted Completed";
		}
		else
			$_SESSION["salesresult"]="ERROR: Unable To Delete Selected Sales";
	}
	else
		$_SESSION["salesresult"]="ERROR: Unable To Delete Selected Sales";	
}
header("location:".$glink."viewgraph_r_t.php");
exit;
include "include/unconfig.php";
?>
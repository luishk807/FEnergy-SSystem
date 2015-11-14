<?php
session_start();
include "include/config.php";
include "include/function.php";
$users = array();
$listemails="";
$listphones="";
date_default_timezone_set('America/New_York');
$hx = date("H");
$xtoday = date("m-d-Y h:i:s");
$itoday = date("m-d-Y h:i:s");
$realtoday = date("m-d-Y");
if($hx >='21' && $hx<='22')
{
$query = "select * from task_users where type in('5','6') and status='1'";
//$query = "select * from task_users where id in('2','1')";
if($result = mysql_query($query))
{
	if(($num_rows = mysql_num_rows($result))>0)
	{
		while($rows = mysql_fetch_array($result))
		{
			$users[] = array('id'=>$rows["id"],'name'=>stripslashes($rows["name"]),'email'=>stripslashes($rows["email"]),'phone'=>$rows["phone"]);
			if(!empty($rows["phone"]))
			{
				if(empty($listphones))
					$listphones="1".$rows["phone"];
				else
					$listphones .=",1".$rows["phone"];
			}
			if(!empty($rows["email"]))
			{
				if(empty($listemails))
					$listemails=stripslashes($rows["email"]);
				else
					$listemails .=",".stripslashes($rows["email"]);
			}
		}
	}
}
if(!empty($listemails))
{
	$title = "Family Energy Sales Report System $xtoday Reminder: Sales Report Submission Required";
	$message = "Hello,<br/><br/>";
	$message .="This is a REMINDER that your sales for $itoday must be entered before 11:00pm to the Family Energy Sales Report System.<br/><br/>";
	$message .="The Family Energy Sales Report System is scheduled to send a set number of reminder from 9pm to 10:45pm every day.<br/><br/>";
	$message .="At 11:00pm, system will send report to selected emails of your reports.<br/><br/>";
	$message .="To use the Family Energy Sales Report system please log in to the system from any devices <a href='http://www.familyenergymap.com/salesreport/' target='_blank'>HERE</a> and use your exclusive username and password provided by the administrators<br/><br/>";
	$message .="Attn,<br/><br/>Family Energy Team<br/>";
	$resultemail = sendEmail($listemails,$title,$message);
}
if(!empty($listphones))
{
	$mmessage="Family Energy Sales Report $xtoday Reminder: Please Submit Sales Today Before 11:00pm http://www.familyenergymap.com/salesreport/";
	$result = sendSMSm($listphones,$mmessage);
}
}
include "include/unconfig.php";
?>
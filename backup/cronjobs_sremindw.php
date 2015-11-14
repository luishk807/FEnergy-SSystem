<?php
session_start();
include "include/config.php";
include "include/function.php";
date_default_timezone_set('America/New_York');
$weekday = date('l');
$today = date('Y-m-d');
$tday = getCalDate($weekday);
$todaypf = fixdate_comps('invdate_s',$tday);
$todaypt = fixdate_comps('invdate_s',$today);
$gtotal=0.00;
$thisyear=date("Y");
$fromy = $thisyear-50;
$rtotal = getRunTotalall($tday,$today);
if(empty($rtotal))
	$rtotal='0';
$users = array();
$listemails="";
$listphones="";
if($weekday=="Thursday")
{
$query = "select * from task_users where id in('3','22','14','4','71')";
//$query = "select * from task_users where id in('1','2')";
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
	$title = "Family Energy Sales Report System: $todaypt Sales Summary Report";
	$message = "<div style='font-size:20pt; text-align:center; font-weight:bold'>Report Date: ".$todaypt."<hr/></div><br/><br/>";
	$message .="<div style='font-size:16pt; text-align:center;'>Weekly Total From <br/>".$todaypf." to ".$todaypt.": <b>$rtotal</b><br/>Total Breakdown<hr/></div><br/>";
	$message .="<div style='text-align:center'>";
	//$query = "select * from sales_report where fromdate between '".$tday."' and '".$today."' order by fromdate desc";
	//list of breakdown per office
	$query = "select * from rec_office order by name";
	if($result = mysql_query($query))
	{
		if(($numrows = mysql_num_rows($result))>0)
		{
			$officegoal=0;
			while($rows = mysql_fetch_array($result))
			{
				$ototal = getRunTotalo($rows["id"],$tday,$today);
				$officegoal = getGoalox($rows["id"]);
				$grandototal=0;
				if($officegoal>0)
					$grandototal=$ototal."/".$officegoal;
				else
					$grandototal=$ototal;
				$omissing = $officegoal - $ototal;
				if($omissing>0)
					$mstr = "&nbsp;&nbsp;<span style='font-size:16pt; font-style:italic'>Missing: -".$omissing."</span>";
				else
					$mstr = "&nbsp;&nbsp;<span style='font-size:16pt; font-style:italic'>Goal Completed!</span>";
				$message .="<div><span style='font-size:15pt; font-family:Tahoma; text-align:center; text-decoration:underline;'>Family Energy ".stripslashes($rows["name"]).":&nbsp; ".$grandototal."</span><br/>".$mstr."</div><br/><br/>";
				$officegoal=0;
			}
		}
	}
	$message .="</div>";
	$mesaage .="<br/><br/><hr/><br/>";
	//get all offices
	$query = "select * from rec_office order by name";
	if($result = mysql_query($query))
	{
		if(($numrows = mysql_num_rows($result))>0)
		{
			$grandtotal =0;
			while($rows = mysql_fetch_array($result))
			{
				$getsalerow = getSalesRow($rows["id"],$tday,$today);
				$message .="<table width='100%' border='1' cellspacing='0' cellpadding='0'>";
				if($getsalerow >0)
				{
					$rgetsalerow = $getsalerow+1;
					$message .="<tr><td width='41%' rowspan='".$rgetsalerow."' align='left' valign='top' style='font-size:15pt; font-family:Tahoma'>Family Energy ".stripslashes($rows["name"])."</td><td width='36%' align='left' valign='middle'>&nbsp;</td><td width='23%' align='center' valign='middle' style='font-size:15pt'>Total Sales</td></tr>";
					$qx = "select distinct agentid from sales_report where office ='".$rows["id"]."' and (fromdate between '".$tday."' and '".$today."') order by fromdate desc";
					if($rx = mysql_query($qx))
					{
						while($rox = mysql_fetch_array($rx))
						{
							$datecompx="";
							$style ="style='background:#FF0;font-size:15pt;'";
							if(isLeader($rox["agentid"]))
								$style ="style='background:#FF0; font-size:15pt;'";
							else
								$style ="style='font-size:15pt;'";
							$datecompx="and (fromdate between '".$tday."' and '".$today."')";
							$agtotal = getRunTotal_search($rox["agentid"],$rows["id"],$rox["agentid"],$datecompx);
							$grandtotal +=$agtotal;
 							$message .="<tr><td ".$style." align='left' valign='middle'>".getAgent($rox["agentid"])."</td><td ".$style." align='center' valign='middle'>".$agtotal."</td></tr>";
						}
					}
					$message .="<tr><td style='font-weight:bold; font-size:15pt;' height='29' colspan='2' align='left' valign='middle'>Family Energy ".stripslashes($rows["name"])." Count</td><td style='font-weight:bold; font-size:15pt;' align='center' valign='middle'>".$grandtotal."</td></tr>";
				}
				else
				{
					$message .="<tr><td width='41%' rowspan='2' align='left' valign='top' style='font-size:15pt; font-family:Tahoma'>Family Energy ".stripslashes($rows["name"])."</td><td width='36%' align='left' valign='middle'>&nbsp;</td><td width='23%' align='center' valign='middle' style='font-size:15pt'>Total Sales</td></tr><tr><td height='32' colspan='2' align='center' valign='middle' style='font-size:15pt; font-style:italic'>No Sales Found</td></tr><tr style='font-weight:bold;font-size:15pt;'><td height='29' colspan='2' align='left' valign='middle'>Family Energy ".stripslashes($rows["name"])." Count</td><td align='center' valign='middle'>&nbsp;</td></tr>";
				}
				$grandtotal=0;
				$message .="</table>";
				$message .="<br/><br/>";
			}
		}
		else
			$message .="<div style='font-size:18pt; text-align:center; text-decoration:underline;'>**************************************************<br/>NO OFFICE FOUND IN SYSTEM<br/>**************************************************</div>";
	}
	else
		$message .="<div style='font-size:18pt; text-align:center; text-decoration:underline;'>**************************************************<br/>NO OFFICE FOUND IN SYSTEM<br/>**************************************************</div>";
	//$listemails='luishk807@hotmail.com';
	$resultemail = sendEmail_simple($listemails,$title,$message);
	//echo $message;
}
if(!empty($listphones))
{
	$mmessage="Family Energy Sales Report System Updates: Weekly Report Sent To Your Email";
	$result = sendSMSm($listphones,$mmessage);
}
if(!empty($tday) && !empty($today))
{
	$uquery = "insert ignore into sales_sent(pday,ptoday,date)values('".$tday."','".$today."',NOW())";
	$uresult = @mysql_query($uquery);
}
}
include "include/unconfig.php";
?>
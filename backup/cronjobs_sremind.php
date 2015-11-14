<?php
session_start();
include "include/config.php";
include "include/function.php";
date_default_timezone_set('America/New_York');
$weekday=date('l');
$today=date('Y-m-d');
$tday=getCalDate($weekday);
//$today="2012-04-23";
//$tday="2012-04-20";
$todaypf=fixdate_comps('invdate_s',$tday);
$todaypt=fixdate_comps('invdate_s',$today);
$gtotal=0.00;
$thisyear=date("Y");
$fromy=$thisyear-50;
$rtotal=getRunTotalall($tday,$today);
if(empty($rtotal))
	$rtotal='0';
$users=array();
$listemails="";
$listphones="";
if($weekday !="Thursday")
{
$query="select * from task_users where id in('3','22','14','4','71')";
//$query = "select * from task_users where id in('1','2')";
if($result=mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		while($rows=mysql_fetch_array($result))
		{
			$users[]=array('id'=>$rows["id"],'name'=>stripslashes($rows["name"]),'email'=>stripslashes($rows["email"]),'phone'=>$rows["phone"]);
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
	$title="Family Energy Sales Report System: $todaypt Sales Summary Report";
	$message="<div style='font-size:20pt; text-align:center; font-weight:bold'>Report Date: ".$todaypt."<br/><hr/></div><br/><br/>";
	$message .="<div style='font-size:16pt; text-align:center;'>Total From <br/>".$todaypf." to ".$todaypt.": <b>".$rtotal."</b><br/>Total Breakdown <br/><hr/></div>";
	$message .="<br/><div style='text-align:center'>";
	//$query = "select * from sales_report where fromdate between '".$tday."' and '".$today."' order by fromdate desc";
	//list of breakdown per office
	$query="select * from rec_office order by name";
	if($result=mysql_query($query))
	{
		if(($numrows=mysql_num_rows($result))>0)
		{
			$officegoal=0;
			while($rows=mysql_fetch_array($result))
			{
				$ototal=getRunTotalo($rows["id"],$tday,$today);
				$officegoal=getGoalox($rows["id"]);
				$grandototal=0;
				if($officegoal>0)
					$grandototal=$ototal."/".$officegoal;
				else
					$grandototal=$ototal;
				$omissing=$officegoal - $ototal;
				if($omissing>0)
					$mstr="&nbsp;&nbsp;<span style='font-size:16pt; font-style:italic'>Missing: -".$omissing."</span>";
				else
					$mstr="&nbsp;&nbsp;<span style='font-size:16pt; font-style:italic'>Goal Completed!</span>";
				$message .="<div><span style='font-size:15pt; font-family:Tahoma; text-align:center; text-decoration:underline;'>".stripslashes($rows["name"]).":  ".$grandototal."</span><br/>".$mstr."</div><br/><br/>";
				$officegoal=0;
			}
		}
	}
	$message .="</div>";
	$mesaage .="<br/><br/><hr/><br/>";
	//get all offices
	$query="select * from rec_office order by name";
	if($result=mysql_query($query))
	{
		if(($numrows=mysql_num_rows($result))>0)
		{
			while($rows=mysql_fetch_array($result))
			{
				$message .="<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td height='43' colspan='3' align='center' valign='middle' style='font-size:23pt; font-style:italic; text-decoration:underline; font-weight:bold'>".stripslashes($rows["name"])."</td></tr>";
				$massing="";
				$q="select distinct userid from sales_report where office='".$rows["id"]."' and fromdate='".$today."' order by userid";
				if($r=mysql_query($q))
				{
					if(($nq=mysql_num_rows($r))>0)
					{
						$atotal=0;
						while($rx=mysql_fetch_array($r))
						{
							$mtotal=getRunTotal_today_pmo($rx["userid"],$rows["id"],$today);
							$getgoal=getGoalx($rx["userid"],$rows["id"]);
							$grandtotal=0;
							if($getgoal>0)
								$grandtotal=$mtotal."/".$getgoal;
							else
								$grandtotal=$mtotal;
							if($getgoal !="na")
							{
								$mmissing=$getgoal-$mtotal;
								if($mmissing>0)
									$amstr="&nbsp;&nbsp;<span style=' font-size:12pt; font-style:italic'>Missing: -".$mmissing."</span>";
								else
									$amstr="&nbsp;&nbsp;<span style=' font-size:12pt; font-style:italic'>Goal Completed!</span>";
							}
							$mname="<span style='font-weight:bold'>".getName($rx["userid"])."</span>";
							$message .="<tr><td height='50' colspan='3' align='center' valign='middle' style='font-size:12pt;'>".$mname." Total: ".$grandtotal."<br/>".$amstr."</td></tr>";
							$amstr="";
							$qx="select * from sales_report where userid='".$rx["userid"]."' and office='".$rows["id"]."' and fromdate='".$today."' order by agentid, date desc";
							if($rxx=mysql_query($qx))
							{
								if(($nqx=mysql_num_rows($rxx))>0)
								{
									$cxx=1;
									while($rxxx=mysql_fetch_array($rxx))
									{
										$atotal +=$rxxx["stotal"];
										$anamex=getAgent($rxxx["agentid"]);
										$message .="<tr><td colspan='3' align='center' valign='middle'><hr/></td></tr>";
										$message .="<tr><td width='6%' align='center' valign='middle'>".$cxx."</td><td align='center'>".$anamex."</td><td width='25%' align='center' valign='middle'>".$rxxx["stotal"]."</td></tr>";
										$cxx++;
										$anamex="";
									}
									$message .="<tr><td colspan='3' align='right' valign='middle'><hr/></td></tr>";
   						  		    $message .="<tr><td colspan='2' width='75%' align='right' valign='middle'>Total: </td><td align='center' valign='middle' width='25%'>".$atotal."</td></tr>";
									$message .="<tr><td colspan='3' align='right' valign='middle'><hr/></td></tr>";
								}
								else
									$message .="<tr><td colspan='3' align='right' valign='middle' style='font-size:18pt; text-align:center; text-decoration:underline;'><br/>NO SALES FOUND FOR TODAY IN SYSTEM</td></tr>";
							}
							else
								$message .="<tr><td colspan='3' align='right' valign='middle' style='font-size:18pt; text-align:center; text-decoration:underline;'>NO AGENT FOUND IN SYSTEM</td></tr>";
							$atotal=0;
							$message .="<tr><td colspan='3' height='10' align='right' valign='middle'>&nbsp;</td></tr>";
						}
					}
					else
						$message .="<tr><td colspan='3' align='right' valign='middle' style='font-size:18pt; text-align:center; text-decoration:underline;'>NO SALES FOUND IN SYSTEM FOR TODAY</td></tr>";
				}
				else
					$message .="<tr><td colspan='3'align='right' valign='middle' style='font-size:18pt; text-align:center; text-decoration:underline;'>NO MANAGERS FOUND IN SYSTEM</td></tr>";
				$message .="</table>";
				$message .="<br/><br/><br/><br/>";
			}
		}
		else
			$message .="<div style='font-size:18pt; text-align:center; text-decoration:underline;'>NO OFFICE FOUND IN SYSTEM</div>";
	}
	else
		$message .="<div style='font-size:18pt; text-align:center; text-decoration:underline;'>NO OFFICE FOUND IN SYSTEM</div>";
	$resultemail=sendEmail_simple($listemails,$title,$message);
	//echo $message;
}
if(!empty($listphones))
{
	$mmessage="Family Energy Sales Report Updates: Report Has Been Sent To Your Email";
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
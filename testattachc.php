<?php
session_start();
require("include/phpMailer/class.phpmailer.php");
include "include/config.php";
include "include/function.php";
/****************PHPMailer Iinitial Configuration********************************/
$mail = new PHPMailer();
$mail->IsSMTP(); // set mailer to use SMTP
$mail->Host = "smtpout.secureserver.net";  // specify main and backup server
$mail->SMTPAuth = true;     // turn on SMTP authentication
$mail->Username = "hr@familyenergysales.com";  // SMTP username
$mail->Password = "hr1514"; // SMTP password
$mail->Port = 80;
$mail->SMTPSecure = "http";
$mail->SMTPDebug = 1; // 1 tells it to display SMTP errors and messages, 0 turns off all errors and messages, 2 prints 

$mail->From = "info@yourfamilyenergy.com";
$mail->FromName = "Family Energy Sales Report System";
//$mail->WordWrap = 50;                                 // set word wrap to 50 characters
$mail->IsHTML(true);                                  // set email format to HTML
//$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
/****************PHPMailer Iinitial Configuration********************************/

/****************Email Configuration And Variables*******************************/
date_default_timezone_set('America/New_York');
$weekday = date('l');
//$today = date('Y-m-d');
$today = date('2012-05-02');
$tday = getCalDate($weekday);
$todaypf = fixdate_comps('invdate_s',$tday);
$todaypt = fixdate_comps('invdate_s',$today);
$gtotal=0.00;
$thisyear=date("Y");
$fromy = $thisyear-50;
//$rtotal = getRunTotalall($tday,$today);
if(empty($rtotal))
	$rtotal='0';
$users = array();
$listemails="";
$listphones="";
$leadersinfo=array();
$listinfox=array();
$avoidid="";
/****************End Of Email Configuration And Variables*******************************/
//$query = "select * from task_users where type in('6')";
//$query = "select * from task_users where id in('58')";
$query = "select * from task_users where id in('10')";
if($result = mysql_query($query))
{
	if(($num_rows = mysql_num_rows($result))>0)
	{
		while($rows = mysql_fetch_array($result))
		{
			$users[] = array('id'=>$rows["id"],'name'=>stripslashes($rows["name"]),'email'=>stripslashes($rows["email"]),'phone'=>$rows["phone"],'office'=>$rows["office"],'type'=>$rows["type"],'report_to'=>$rows["report_to"]);
		}
	}
}
$aman=array();
if(sizeof($users)>0)
{
	//for($i=0;$i < sizeof($users);$i++)
	for($i=0;$i <sizeof($users);$i++)
	{
		$grandmessage="";
		$mmissingm="";
		$mmissingo="";
		$oname = getOfficeName($users[$i]["office"]);
		$rtotal = getRunTotalbyman($users[$i]["id"],$users[$i]["office"],$tday,$today);
		$officetotal = getRunTotalo($users[$i]["office"],$tday,$today);
		$getgoalo = getGoalox($users[$i]["office"]);
		$grandtotalm=0;
		if($getgoalo>0)
			$grandtotalo=$officetotal."/".$getgoalo;
		else
			$grandtotalo=$officetotal;
		if($getgoalo !="na")
		{
			$mmissingo = $getgoalo - $officetotal;
			if($mmissingo>0)
				$amstro = "&nbsp;&nbsp;<span style=' font-size:15pt; font-style:italic; color:#000;'>Missing: -".$mmissingo."</span>";
			else
				$amstro = "&nbsp;&nbsp;<span style=' font-size:15pt; font-style:italic;color:#000;'>Goal Completed!</span>";
		}
		$title = "Family Energy Sales Report System: $todaypt Sales Summary Report for $oname";
		$messageh = "<div style='font-size:20pt; text-align:center; font-weight:bold'>Report Date: ".$todaypt."<hr/></div><br/>";
		$messageh .="<div style='font-size:18pt; text-align:center;'>From Date:<br/><u><b>".$todaypf." to ".$todaypt."</b></u><br/><span style='text-decoration:underline;'>Grand Total For<br/>".$oname.": <b>".$grandtotalo." </b></span><br/>$amstro</div>";
		$messageh .="<br/><br/><hr/>";
		//show office
		if(!empty($oname)) //if there is office name
		{
			//show office name and total
			$getgoalm = getGoalmx($users[$i]["id"],$users[$i]["office"]);
			$grandtotalm=0;
			if($getgoalm>0)
				$grandtotalm=$rtotal."/".$getgoalm;
			else
				$grandtotalm=$rtotal;
			if($getgoalm !="na")
			{
				$mmissingm = $getgoalm - $rtotal;
				if($mmissingm>0)
					$amstrm = "&nbsp;&nbsp;<span style='font-size:13pt; font-style:italic'>Missing: -$mmissingm</span>";
				else
					$amstrm = "&nbsp;&nbsp;<span style='font-size:13pt; font-style:italic'>Goal Completed!</span>";
			}
			$message ="<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr style='font-size:19pt; font-style:italic; font-weight:bold;'><td height='43' colspan='3' align='center' valign='middle'>".stripslashes($users[$i]["name"])."'s Team:<br/><b>$grandtotalm $amstrm</b><hr/></td></tr>";
			$massing="";
			//search managers based on office
			//get total of managers from office during week
			$mtotal=getRunTotal_today_pmo($users[$i]["id"],$users[$i]["office"],$today);
			$getgoal = getGoalx($users[$i]["id"],$users[$i]["office"]);
			$grandtotal=0;
			if($getgoal>0)
				$grandtotal=$mtotal."/".$getgoal;
			else
				$grandtotal=$mtotal;
			if($getgoal !="na")
			{
				$mmissing = $getgoal - $mtotal;
				if($mmissing>0)
					$amstr = "&nbsp;&nbsp;<span style='font-size:12pt; font-style:italic'>Missing: -$mmissing</span>";
				else
					$amstr = "&nbsp;&nbsp;<span style='font-size:12pt; font-style:italic'>Goal Completed!</span>";
			}
			//show message of total
			$message .="<tr><td height='34' colspan='3' align='center' valign='middle' style='font-size:14pt; font-family:Tahoma;'>[<span style='font-size:12pt; font-style:italic'>Mngr</span>] <b>".$users[$i]["name"]."</b> <br/>Total: ".$grandtotal." $amstr</td></tr>";

			//get all agent sales from selected manager and office
			$qx = "select * from sales_report where userid='".$users[$i]["id"]."' and office='".$users[$i]["office"]."' and fromdate='$today' order by agentid, date desc";
			if($rxx = mysql_query($qx))
			{
				if(($nqx = mysql_num_rows($rxx))>0)
				{
					$cxx=1;
					while($rxxx = mysql_fetch_array($rxx))
					{
						$atotal +=$rxxx["stotal"];
						$message .="<tr><td colspan='3' valign='middle'><hr/></td></tr>";
						$message .="<tr><td width='6%' align='center' valign='middle'>$cxx</td><td align='center'>".getAgent($rxxx["agentid"])."</td><td width='25%' align='center' valign='middle'>".$rxxx["stotal"]."</td></tr>";
						$cxx++;
					}
					$message .="<tr><td colspan='3' align='right' valign='middle'><hr/></td></tr>";
					$message .="<tr><td colspan='2' width='75%' align='right' valign='middle'>Total:&nbsp;</td><td align='center' valign='middle' width='25%'>$atotal</td></tr>";
					$message .="<tr><td colspan='3' align='right' valign='middle'><hr/></td></tr>";
				}
				else
					$message .="<tr><td colspan='3' align='right' valign='middle' style='font-size:14pt; text-align:center; text-decoration:underline; color:#00477f;'><hr/><br/>NO SALES FOUND FOR TODAY IN SYSTEM<br/><hr/></td></tr>";
			}
			else
				$message .="<tr><td colspan='3' align='right' valign='middle' style='font-size:14pt; text-align:center; text-decoration:underline; color:#00477f;'><hr/><br/>NO AGENT FOUND IN SYSTEM<br/><hr/></td></tr>";
			$atotal=0;
			/******show totals from team leaders ***********************/
			$qy = "select * from task_users where report_to='".$users[$i]["id"]."' and type='5' order by name";
			if($ry = mysql_query($qy))
			{
				if(($ny = mysql_num_rows($ry))>0)
				{
					$message .= "<tr><td height='34' colspan='3' align='center' valign='middle' style='font-size:14pt; font-family:Tahoma; font-weight:bold'><br/><br/>Team Leaders Under<br/> ".$users[$i]["name"]."<br/><hr/><br/></td></tr>";
					while($rowy = mysql_fetch_array($ry))
					{
						$leadersinfo[]=array('id'=>$rowy["id"],'name'=>stripslashes($rowy["name"]),'email'=>stripslashes($rowy["email"]),'phone'=>$rowy["phone"]);
						if(empty($avoidid))
							$avoidid="'".$rowy["id"]."'";
						else
							$avoidid=",'".$rowy["id"]."'";
						$mmissingy="";
						//search managers based on office
						//get total of managers from office during week
						$mtotaly=getRunTotal_today_pmo($rowy["id"],$rowy["office"],$today);
						$getgoaly = getGoalx($rowy["id"],$rowy["office"]);
						$grandtotalx=0;
						if($getgoaly>0)
							$grandtotalx=$mtotaly."/".$getgoaly;
						else
						{
							$mgoal="";
							if($getgoal>0)
							{
								$mgoal=" <span style='font-size:12pt'>[".$users[$i]["name"]." ".$amstr."]</span>";
							}
							$grandtotalx=$mtotaly.$mgoal;
						}
						if($getgoaly !="na")
						{
							$mmissingy = $getgoaly - $mtotaly;
							if($mmissingy>0)
								$amstry = "&nbsp;&nbsp;<span style='font-size:12pt; font-style:italic'>Missing: -$mmissing</span>";
							else
								$amstry = "&nbsp;&nbsp;<span style=' font-size:12pt; font-style:italic'>Goal Completed!</span>";
						}
						//show message of total
						$message .="<tr><td height='34' colspan='3' align='center' valign='middle' style='font-size:14pt; font-family:Tahoma;'><b>".$rowy["name"]."</b><br/>Total: ".$grandtotalx." $amstry</td></tr>";
						$amstry="";
						//get all agent sales from selected manager and office
						$qxy = "select * from sales_report where userid='".$rowy["id"]."' and office='".$rowy["office"]."' and fromdate='$today' order by agentid, date desc";
						if($rxxy = mysql_query($qxy))
						{
							if(($nqxy = mysql_num_rows($rxxy))>0)
							{
								$cxxy=1;
								while($rxxxy = mysql_fetch_array($rxxy))
								{
									$atotaly +=$rxxxy["stotal"];
									$message .="<tr><td colspan='3' valign='middle'><hr/></td></tr>";
									$message .="<tr><td width='6%' align='center' valign='middle'>$cxxy</td><td align='center'>".getAgent($rxxxy["agentid"])."</td><td width='25%' align='center' valign='middle'>".$rxxxy["stotal"]."</td></tr>";
									$cxxy++;
								}
								$message .="<tr><td colspan='3' align='right' valign='middle'><hr/></td></tr>";
								$message .="<tr><td colspan='2' width='75%' align='right' valign='middle'>Total:&nbsp;</td><td align='center' valign='middle' width='25%'>$atotaly</td></tr>";
								$message .="<tr><td colspan='3' align='right' valign='middle'><hr/></td></tr>";
							}
							else
								$message .="<tr><td colspan='3' align='right' valign='middle' style='font-size:14pt; text-align:center; text-decoration:underline;'><hr/>NO SALES FOUND FOR TODAY IN SYSTEM FOR ".strtoupper(stripslashes($rowy["name"]))."<hr/><br/><br/></td></tr>";
						}
						else
							$message .="<tr><td colspan='3' align='right' valign='middle' style='font-size:14pt; text-align:center; text-decoration:underline; '><hr/>NO AGENT FOUND IN SYSTEM FOR ".strtoupper(stripslashes($rowy["name"]))."<hr/><br/><br/></td></tr>";
						$atotal=0;
						$atotaly=0;
					}
				}
			}
			
			/************end of totals from team leader*******************/
			$message .="<tr><td colspan='3' height='10' align='right' valign='middle'>&nbsp;</td></tr>";
			$message .="</table>";
			$message .="<br/><br/><br/><br/>";
		}
		else
			$message .="<div style='font-size:18pt; text-align:center; text-decoration:underline;'><hr/><br/>NO OFFICE FOUND IN SYSTEM<br/><hr/></div>";
		/******search for the other office performance***********/
		$rmessage ="";
		if(!empty($avoidid))
			$xavoidid="'".$users[$i]["id"]."',".$avoidid;
		else
			$xavoidid="'".$users[$i]["id"]."'";
		if(!empty($xavoidid))
		{
			echo $xavoidid." ".$users[$i]["office"]." ".$tday." ".$today;
			$rest_total = getRestOfficeTotal($xavoidid,$users[$i]["office"],$tday,$today);
			if($rest_total>0)
			{
				$rmessage ="<div style='text-align:center;font-size:19pt;'><hr/><br/>REST OF THE<br/> <i><u>".strtoupper($oname)."</u></i> TEAM: <b>".$rest_total."</b><br/><br/><hr/><br/></div>";
			}
		}
		/******end of search of performance from other offices ****************/
		$grandmessage = $messageh.$rmessage.$message;
		if(!empty($users[$i]["email"]))
		{
			//$resultemail = sendEmail_simple($users[$i]["email"],$title,$grandmessage);
			//$resultemail = sendEmail_simple("luishk807@hotmail.com",$title,$grandmessage);
			//echo $grandmessage;
			$mail2=clone $mail;
			$mail2->AddAddress("luishk807@hotmail.com", $users[$i]["name"]);
			$mail2->AddEmbeddedImage("tmp/imagefile.png",'my-image','imagefile.png');
			$mail2->Subject=$title;
			$grandmessagex="<img src='cid:my-image' alt='something'/><br/><br/>".$grandmessage;
			$mail2->Body=$grandmessagex;
			if(!$mail2->Send())
			{
			   echo "Message could not be sent.<p>";
			   echo "Mailer Error:".$mail->ErrorInfo;
			   exit;
			}
			else
				echo "Message has been sent";
			//sleep(5);
			/*if(sizeof($leadersinfo)>0)
			{
				for($x=0;$x<sizeof($leadersinfo);$x++)
				{
					if(!empty($leadersinfo[$x]["email"]))
					{
						//$resultemailx = sendEmail($leadersinfo[$x]["email"],$title,$grandmessage);
						$resultemailx = sendEmail_simple("luishk807@hotmail.com",$title,$grandmessage);
						//echo $grandmessage;
						sleep(5);
						//echo $leadersinfo[$x]["email"]."<br/>";
					}
				}
			}*/
		}
		/*if(!empty($users[$i]["phone"]))
		{
			$mmessage="Family Energy Sales Report Updates: Report For $todaypt Has Been Sent To Your Emails";
			$result = sendSMS($users[$i]["phone"],$mmessage);
			//echo $users[$i]["phone"]."<br/>";
			if(sizeof($leadersinfo)>0)
			{
				for($x=0;$x<sizeof($leadersinfo);$x++)
				{
					if(!empty($leadersinfo[$x]["phone"]))
					{
						$result = sendSMS($leadersinfo[$x]["phone"],$mmessage);
						//echo $leadersinfo[$x]["phone"]."<br/>";
					}
				}
			}
		}*/
		//sleep(5);
		$amstr="";
		$avoidid="";
	}
}
//if(!empty($tday) && !empty($today))
//{
//	$uquery = "insert ignore into sales_sent(pday,ptoday,date)values('".$tday."','".$today."',NOW())";
//	$uresult = @mysql_query($uquery);
//}
$mail->AddAddress("luishk807@hotmail.com", "Luis");
//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
//$mail->AddAttachment("tmp/imagefile.png", "imagefile.png");    // optional name
$mail->AddEmbeddedImage("tmp/imagefile.png",'my-image','imagefile.png');
$mail->Subject = "Here is the subject";
$mail->Body    = "Something is here becuase it's not there<br/><br/><img src='cid:my-image' alt='something'/>";

if(!$mail->Send())
{
   echo "Message could not be sent. <p>";
   echo "Mailer Error: " . $mail->ErrorInfo;
   exit;
}
else
	echo "Message has been sent";
include "include/unconfig.php";
?>
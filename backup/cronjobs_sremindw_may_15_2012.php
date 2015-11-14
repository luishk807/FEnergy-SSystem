<?php
session_start();
include "include/config.php";
include "include/function.php";
require_once ('include/jpgraph/jpgraph.php');
require_once ('include/jpgraph/jpgraph_line.php');
require("include/phpMailer/class.phpmailer.php");
date_default_timezone_set('America/New_York');
$weekday = date('l');
$today = date('Y-m-d');
//$today=date('2012-05-03');
$tday = getCalDate($weekday);
$ardays=getArrayDays($weekday);
//$ardays=array('2012-04-27','2012-04-28','2012-04-29','2012-04-30','2012-05-01','2012-05-02','2012-05-03');
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
$listemails_a=array();
$listphones="";
/****************End Of Email Configuration And Variables*******************************/
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
/**************Graph Setttings*********************************************************/
// Setup the graph
include "include/jgraph_config.php";
/**************COLOR FOR THE LINES************************************************/
//this is the use to color the graph lines, ave number of team leader and manager is 8 but to play safe i used 20
$colorsc=array();//color choosen
$colors=array();
$colors[]='#6495ED';
$colors[]='#43edc7';
$colors[]='#25b7d6';
$colors[]='#d54325';
$colors[]='#1b9784';
$colors[]='#971b6c';
$colors[]='#fcf8cb';
$colors[]='#af217a';
$colors[]='#f8cafc';
$colors[]='#ed446a';
$colors[]='#22af57';
$colors[]='#5ee025';
$colors[]='#25e0a7';
$colors[]='#c489a5';
$colors[]='#498910';
$colors[]='#8ff0f6';
$colors[]='#606a1c';
$colors[]='#649143';
$colors[]='#8a9142';
$colors[]='#8a9381';
/**************END OF COLOR FOR THE LINES************************************************/
/***************End of Graph Settings*************************************************/
//if($weekday=="Thursday")
//{
$query = "select * from task_users where id in('3','22','14','4')";
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
				/*if(empty($listemails))
					$listemails=stripslashes($rows["email"]);
				else
					$listemails .=",".stripslashes($rows["email"]);*/
				$listemails_a[]=array("name"=>stripslashes($rows["name"]),"email"=>stripslashes($rows["email"]));
			}
		}
	}
}
//if(!empty($listemails))
if(sizeof($listemails_a)>0)
{
	$mail2=clone $mail;
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
				$message .="<div><span style='font-size:15pt; font-family:Tahoma; text-align:center; text-decoration:underline;'>Family Energy ".stripslashes($rows["name"]).":&nbsp; ".$grandototal."</span><br/>".$mstr."</div>";
				$message .="<div style='text-align:center'>";
				/***************Script To Get Data For Graph For Manager***********************************************/
				$colors_set="";
				$colorsc=array();
				if(sizeof($colorsc)>0)
				{
					for($y=0;$y<sizeof($colors);$y++)
					{
						$found=false;
						for($z=0;$z<sizeof($colorsc);$z++)
						{
							if($colors[$y]==$colorsc[$z])
							{
								$found=true;
								break;
							}
						}
						if(!$found)
						{
							$colors_set=$colors[$y];
							$colorsc[]=$colors[$y];		
							break;
						}
					}
				}
				else
				{
					$colors_set=$colors[0];
					$colorsc[]=$colors[0];
				}
				$weekamt=getRunTotalall_week_office($rows["id"],$ardays);
				$weekamt_l=array();
				$checktotal="";
				if(sizeof($weekamt)>0)
				{
					for($x=0;$x<sizeof($weekamt);$x++)
					{
							//$weekamt_l[]=$weekamt[$x]["amount"];
							//$checktotal +=$weekamt[$x]["amount"];
							$checktotal +=$weekamt[$x];
					}
					if($checktotal>0)
					{
						$p2=new LinePlot($weekamt);
						include "include/jgraph_config.php";
						$graph->Add($p2);
						$p2->SetColor($colors_set);
						$p2->SetLegend($rows["name"]);
						$graph->legend->SetFrameWeight(1);
						// Output line
						//$graph->Stroke();
						$graph->Stroke(_IMG_HANDLER);
						$fileName = "tmp/imagefile_woffice".$rows["id"].".png";
						$graph->img->Stream($fileName);
						//add image to the php mailer
						$mail2->AddEmbeddedImage("tmp/imagefile_woffice".$rows["id"].".png",md5("office".$rows["id"]).'woffice-image','imagefile_woffice'.$rows["id"].'.png');
						$message .="<img src='cid:".md5("office".$rows["id"])."woffice-image' border='0' alt='graph for ".$rows["name"]."'/>";
					}
				}
			/***************END OF Script To Get Data For Graph*********************************************/
			    $message .="</div>";
			    $message .="<br/><br/>";
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
	//$resultemail = sendEmail_simple("luishk807@hotmail.com",$title,$message);
	//echo $message;
	//$listemails_a=array();
	//$listemails_a[]=array("email"=>"luishk807@hotmail.com","name"=>"luis");
	//$listemails_a[]=array("email"=>"evil_luis@hotmail.com","name"=>"luis");
	for($h=0;$h<sizeof($listemails_a);$h++)
	{
		$mail2->AddAddress($listemails_a[$h]["email"],$listemails_a[$h]["name"]);
		//echo $listemails_a[$h]["email"]."<br/>";
	}
	//$mail2->AddAddress($listemails, $users[$i]["name"]);
	$mail2->Subject=$title;
	$mail2->Body=$message;
	$mail2->Send();
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
//}
include "include/unconfig.php";
?>
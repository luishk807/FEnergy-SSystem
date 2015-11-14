<?php // content="text/plain; charset=utf-8"
session_start();
include "include/config.php";
include "include/function.php";
require_once ('include/jpgraph/jpgraph.php');
require_once ('include/jpgraph/jpgraph_line.php');
require("include/phpMailer/class.phpmailer.php");
/****************Email Configuration And Variables*******************************/
date_default_timezone_set('America/New_York');
$weekday = date('l');
//$today = date('Y-m-d');/
$today = date('2012-05-03');
$tday = getCalDate($weekday);
//$ardays=getArrayDays($weekday);
$ardays=array('2012-04-27','2012-04-28','2012-04-29','2012-04-30','2012-05-01','2012-05-02','2012-05-03');
$todaypf = fixdate_comps('invdate_s',$tday);
$todaypt = fixdate_comps('invdate_s',$today);
$graphdate = fixdate_comps('d',$today);
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
/****************Grab ALL Managers******************************************************/
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
/****************END OF Grabing ALL Managers******************************************************/
$aman=array();
if(sizeof($users)>0)
{
	for($i=0;$i <sizeof($users);$i++)
	{
		$mail2=clone $mail;
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
			$message ="<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr style='font-size:19pt; font-style:italic; font-weight:bold;'><td height='43' colspan='3' align='center' valign='middle'>".stripslashes($users[$i]["name"])."'s Team:<br/><b>$grandtotalm $amstrm</b><br/>";
		/***************Script To Get Data For Graph For Whole Managers Sales*****************************/
		$colors_set="";
		$colorsc=array();
		include "include/jgraph_config.php";
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
		$weekamtx=getRunTotalall_week($users[$i]["id"],$ardays);
		$weekamt_lx=array();
		for($x=0;$x<sizeof($weekamtx);$x++)
		{
			$weekamt_lx[]=$weekamtx[$x]["amount"];
		}
		$p1 = new LinePlot($weekamt_lx);
		$graph->Add($p1);
		$p1->SetColor($colors_set);
		$p1->SetLegend($users[$i]["name"]);
		//search totals of team leaders
		$querylx = "select * from task_users where report_to='".$users[$i]["id"]."' and office='".$users[$i]["office"]."'";
		if($resultlx = mysql_query($querylx))
		{
			if(($num_rowslx = mysql_num_rows($resultlx))>0)
			{
				while($rowslx = mysql_fetch_array($resultlx))
				{
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
					$weekamtx=getRunTotalall_week($rowslx["id"],$ardays);
					$weekamt_lx=array();
					for($x=0;$x<sizeof($weekamtx);$x++)
					{
						$weekamt_lx[]=$weekamtx[$x]["amount"];
					}
					$p1 = new LinePlot($weekamt_lx);
					$graph->Add($p1);
					$p1->SetColor($colors_set);
					$p1->SetLegend($rowslx["name"]);
				}
			}
		}
		$graph->legend->SetFrameWeight(1);
		// Output line
		//$graph->Stroke();
		$graph->Stroke(_IMG_HANDLER);
		$fileName = "tmp/imagefile_wteam".$users[$i]["id"].".png";
		$graph->img->Stream($fileName);
		//add image to the php mailer
		$mail2->AddEmbeddedImage("tmp/imagefile_wteam".$users[$i]["id"].".png",md5($users[$i]["id"]).'_wteam-image','imagefile_wteam'.$users[$i]["id"].'.png');
		$message .="<img src='cid:".md5($users[$i]["id"])."_wteam-image' border='0' alt='graph for ".$users[$i]["name"]."_team'/>";
		/***************END OF Script To Get Data For Graph***********************************************/
		$message .="<hr/></td></tr>";
		$massing="";
			//search managers based on office
			//get total of managers from office during week
			$mtotal=getRunTotal_today_pmo($users[$i]["id"],$users[$i]["office"],$today);
			$getgoal = getGoalx($users[$i]["id"],$users[$i]["office"]);
			$grandtotal=0;
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
			/***************Script To Get Data For Graph For Manager***************************************************/
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
			$weekamt=getRunTotalall_week($users[$i]["id"],$ardays);
			$weekamt_l=array();
			$checktotal="";
			if(sizeof($weekamt)>0)
			{
				for($x=0;$x<sizeof($weekamt);$x++)
				{
						$weekamt_l[]=$weekamt[$x]["amount"];
						$checktotal +=$weekamt[$x]["amount"];
				}
				if($checktotal>0)
				{
					$p2=new LinePlot($weekamt_l);
					include "include/jgraph_config.php";
					$graph->Add($p2);
					$p2->SetColor($colors_set);
					$p2->SetLegend($users[$i]["name"]);
					$graph->legend->SetFrameWeight(1);
					// Output line
					//$graph->Stroke();
					$graph->Stroke(_IMG_HANDLER);
					$fileName = "tmp/imagefile".$users[$i]["id"].".png";
					$graph->img->Stream($fileName);
					//add image to the php mailer
					$mail2->AddEmbeddedImage("tmp/imagefile".$users[$i]["id"].".png",md5($users[$i]["id"]).'-image','imagefile'.$users[$i]["id"].'.png');
					$message .="<tr><td colspan='3' align='center' valign='middle'><img src='cid:".md5($users[$i]["id"])."-image' border='0' alt='graph for ".$users[$i]["name"]."'/></td></tr>";
				}
			}
		/***************END OF Script To Get Data For Graph***********************************************/
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
						$mgoal="";
						$mgoal=" <span style='font-size:12pt'>[".$users[$i]["name"]." ".$amstr."]</span>";
						$grandtotalx=$mtotaly.$mgoal;
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
				/***********Script To Get Data For Graph For Team Leader***************************************/
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
						$weekamt=getRunTotalall_week($rowy["id"],$ardays);
						$weekamt_l=array();
						$checktotal="";
						for($x=0;$x<sizeof($weekamt);$x++)
						{
							$weekamt_l[]=$weekamt[$x]["amount"];
							$checktotal +=$weekamt[$x]["amount"];
						}
						if($checktotal>0)
						{
							$p3 = new LinePlot($weekamt_l);
							include "include/jgraph_config.php";
							$graph->Add($p3);
							$p3->SetColor($colors_set);
							$p3->SetLegend($rowy["name"]);
							$graph->legend->SetFrameWeight(1);
							// Output line
							//$graph->Stroke();
							$graph->Stroke(_IMG_HANDLER);
							$fileName = "tmp/imagefile_team".$rowy["id"].".png";
							$graph->img->Stream($fileName);
							//add image to the php mailer
							$mail2->AddEmbeddedImage("tmp/imagefile_team".$rowy["id"].".png",md5($rowy["id"]).'_team-image','imagefile'.$rowy["id"].'_team.png');
							$message .="<tr><td colspan='3' align='center' valign='middle'><img src='cid:".md5($rowy["id"])."_team-image' border='0' alt='graph for ".$rowy["name"]."'/></td></tr>";
						}
				/***********END OF Script To Get Data For Graph***********************************************/
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
			$mail3=clone $mail2;
			$mail2->AddAddress("luishk807@hotmail.com", $users[$i]["name"]);
			$mail2->Subject=$title;
			$mail2->Body=$grandmessage;
			$mail2->Send();
			//sleep(5);
			if(sizeof($leadersinfo)>0)
			{
				for($x=0;$x<sizeof($leadersinfo);$x++)
				{
					if(!empty($leadersinfo[$x]["email"]))
					{
						//$resultemailx = sendEmail($leadersinfo[$x]["email"],$title,$grandmessage);
						//$resultemailx = sendEmail_simple("luishk807@hotmail.com",$title,$grandmessage);
						//echo $grandmessage;
						$mail4=clone $mail3;
						$mail2->AddAddress("luishk807@hotmail.com", $users[$i]["name"]);
						$mail2->Subject=$title;
						$mail2->Body=$grandmessage;
						$mail2->Send();
						//sleep(5);
						//echo $leadersinfo[$x]["email"]."<br/>";
					}
				}
			}
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
//Send it back to browser
//$graph->img->Headers();
//$graph->img->Stream();
include "include/unconfig.php";
?>
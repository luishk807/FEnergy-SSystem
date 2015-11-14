<?php
session_start();
include "include/config.php";
include "include/function.php";
require_once ('include/jpgraph/jpgraph.php');
require_once ('include/jpgraph/jpgraph_line.php');
require("include/phpMailer/class.phpmailer.php");
date_default_timezone_set('America/New_York');
$weekday=date('l');
if($weekday !="Thursday")
{
	$today=date('Y-m-d');
	$tday=getCalDate($weekday);
	//$today="2012-05-03";
	$ardays=getArrayDays($weekday);
	//$ardays=array('2012-04-27','2012-04-28','2012-04-29','2012-04-30','2012-05-01','2012-05-02','2012-05-03');
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
	$mail->SMTPDebug = 0; // 1 tells it to display SMTP errors and messages, 0 turns off all errors and messages, 2 prints 
	
	$mail->From = "no-reply@yourfamilyenergy.com";
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
	if($weekday !="Thursday")
	{
	$query="select * from task_users where id in('3','4','71') or type in('8','7') and status='1'";
	//$query = "select * from task_users where id in('1','2')";
	if($result=mysql_query($query))
	{
		$mail2=clone $mail;
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
					$listemails_a[]=array("name"=>stripslashes($rows["name"]),"email"=>stripslashes($rows["email"]));
				}
			}
		}
	}
	//if(!empty($listemails))
	if(sizeof($listemails_a)>0)
	{
		$title="Family Energy Sales Report System: $todaypt Sales Summary Report";
		$message="<div style='font-size:20pt; text-align:center; font-weight:bold'>Report Date: ".$todaypt."<br/><hr/></div><br/><br/>";
		$message .="<div style='font-size:16pt; text-align:center;'>Total From <br/>".$todaypf." to ".$todaypt.": <b>".$rtotal."</b><br/>Total Breakdown <br/><hr/></div>";
		$message .="<br/><div style='text-align:center'>";
		//list of breakdown per office
		$query="select * from rec_office where visible='yes' order by name";
		if($result=mysql_query($query))
		{
			if(($numrows=mysql_num_rows($result))>0)
			{
				include "include/jgraph_config.php";
				$officegoal=0;
				$colors_set="";
				$colorsc=array();
				while($rows=mysql_fetch_array($result))
				{
					$ototal=getRunTotalo($rows["id"],$tday,$today);
					$officegoal=getGoalox($rows["id"]);
					$grandototal=0;
					$grandototal=$ototal;
					/*if($officegoal>0)
						$grandototal=$ototal."/".$officegoal;
					else
						$grandototal=$ototal;
					$omissing=$officegoal - $ototal;
					$mstr="";
					if($officegoal>0)
					{
					if($omissing>0)
						$mstr="&nbsp;&nbsp;<span style='font-size:16pt; font-style:italic'>Missing: -".$omissing."</span>";
					else
						$mstr="&nbsp;&nbsp;<span style='font-size:16pt; font-style:italic'>Goal Completed!</span>";
					}*/
					//$message .="<div><span style='font-size:15pt; font-family:Tahoma; text-align:center; text-decoration:underline;'>".stripslashes($rows["name"]).":  ".$grandototal."</span><br/>".$mstr."</div>";
					$message .="<div><span style='font-size:15pt; font-family:Tahoma; text-align:center; text-decoration:underline;'>".stripslashes($rows["name"]).":  ".$grandototal."</span></div>";
					/***************Script To Get Data For Graph For Manager***********************************************/
	
					/*if(sizeof($colorsc)>0)
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
							$checktotal +=$weekamt[$x];
						}
						if($checktotal>0)
						{
							$p2=new LinePlot($weekamt);
							$graph->Add($p2);
							$p2->SetColor($colors_set);
							$p2->SetLegend($rows["name"]);
						}
					}*/
				/***************END OF Script To Get Data For Graph*********************************************/
					$message .="<br/><br/>";
					$officegoal=0;
				}
				/*$graph->legend->SetFrameWeight(1);
				// Output line
				$graph->Stroke(_IMG_HANDLER);
				$fileName = "tmp/imagefile_soffice".$rows["id"].".png";
				$graph->img->Stream($fileName);
				//add image to the php mailer
				$mail2->AddEmbeddedImage("tmp/imagefile_soffice".$rows["id"].".png",md5("office".$rows["id"]).'soffice-image','imagefile_soffice'.$rows["id"].'.png');
				$message .="<div style='text-align:center'>";
				$message .="<img src='cid:".md5("office".$rows["id"])."soffice-image' border='0' alt='graph for ".$rows["name"]."'/>";
				$message .="</div><br/>";*/
				$message .="<br/>";
			}
		}
		$message .="</div>";
		$mesaage .="<br/><br/><hr/><br/>";
		//get all offices
		$query="select * from rec_office where visible='yes' order by name";
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
								$grandtotal=$mtotal;
								/*if($getgoal>0)
									$grandtotal=$mtotal."/".$getgoal;
								else
									$grandtotal=$mtotal;
								$amstr="";
								if($getgoal>0)
								{
									$mmissing=$getgoal-$mtotal;
									if($mmissing>0)
										$amstr="&nbsp;&nbsp;<span style=' font-size:12pt; font-style:italic'>Missing: -".$mmissing."</span>";
									else
										$amstr="&nbsp;&nbsp;<span style=' font-size:12pt; font-style:italic'>Goal Completed!</span>";
								}*/
								$mname="<span style='font-weight:bold'>".getName($rx["userid"])."</span>";
								//$message .="<tr><td height='50' colspan='3' align='center' valign='middle' style='font-size:12pt;'>".$mname." Total: ".$grandtotal."<br/>".$amstr."</td></tr>";
								$message .="<tr><td height='50' colspan='3' align='center' valign='middle' style='font-size:12pt;'>".$mname." Total: ".$grandtotal."</td></tr>";
							/***************Script To Get Data For Graph For Manager****************************/
								/*$colors_set="";
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
								$weekamt=getRunTotalall_week($rx["userid"],$ardays);
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
										$p2->SetLegend(getName($rx["userid"]));
										$graph->legend->SetFrameWeight(1);
										// Output line
										//$graph->Stroke();
										$graph->Stroke(_IMG_HANDLER);
										$fileName = "tmp/imagefile_sman".$rx["userid"].".png";
										$graph->img->Stream($fileName);
										//add image to the php mailer
										$mail2->AddEmbeddedImage("tmp/imagefile_sman".$rx["userid"].".png",md5($rx["userid"]).'sman-image','imagefile_sman'.$rx["userid"].'.png');
										$message .="<tr><td colspan='3' align='center' valign='middle'><img src='cid:".md5($rx["userid"])."sman-image' border='0' alt='graph for ".getName($rx["userid"])."'/></td></tr>";
									}
								}*/
							/***************END OF Script To Get Data For Graph****************************/
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
		//echo $message;
		//$listemails_a=array();
		//$listemails_a[]=array("email"=>"luishk807@hotmail.com","name"=>"luis");
		//$listemails_a[]=array("email"=>"evil_luis@hotmail.com","name"=>"luis");
		for($h=0;$h<sizeof($listemails_a);$h++)
		{
			$mail2->AddAddress($listemails_a[$h]["email"], $listemails_a[$h]["name"]);
		}
		$mail2->Subject=$title;
		$mail2->Body=$message;
		$mail2->Send();
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
}
else
{
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
	$query = "select * from task_users where id in('3','4','71') or type in('8','7') and status='1'";
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
		$query = "select * from rec_office where visible='yes' order by name";
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
					$grandototal=$ototal;
					/*if($officegoal>0)
						$grandototal=$ototal."/".$officegoal;
					else
						$grandototal=$ototal;
					$omissing = $officegoal - $ototal;
					$mstr="";
					if($officegoal>0)
					{
					if($omissing>0)
						$mstr = "&nbsp;&nbsp;<span style='font-size:16pt; font-style:italic'>Missing: -".$omissing."</span>";
					else
						$mstr = "&nbsp;&nbsp;<span style='font-size:16pt; font-style:italic'>Goal Completed!</span>";
					}
					$message .="<div><span style='font-size:15pt; font-family:Tahoma; text-align:center; text-decoration:underline;'>Family Energy ".stripslashes($rows["name"]).":&nbsp; ".$grandototal."</span><br/>".$mstr."</div>";*/
					$message .="<div><span style='font-size:15pt; font-family:Tahoma; text-align:center; text-decoration:underline;'>Family Energy ".stripslashes($rows["name"]).":&nbsp; ".$grandototal."</span></div>";
					$message .="<div style='text-align:center'>";
					/***************Script To Get Data For Graph For Manager***********************************************/
					/*$colors_set="";
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
					}*/
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
		$query = "select * from rec_office where visible='yes' order by name";
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
}
include "include/unconfig.php";
?>
<?php
define("MAPS_HOST", "maps.google.com");
define("KEY", "ABQIAAAAUQoOcLjWVW04XTfLi1SbghRHDJMFrGd7U-5vIm6DVyt_Kv6o_BSNRkm6Jc5CUWvgHIeR0Q2uNVQ4Fw");
$showbutton = true;
$host = "http://www.familyenergymarketing.com/salesreport/";
function getSession()
{
	return "salesuser";
}
function getSystemTitle()
{
	return "Family Energy Sales Report System";
}
function getGHost()
{
	return "/salesreport/";
}
function getReportRdate($date)
{
	$rdate="";
	$query="select rdate from sales_report_real where ddate='".$date."' limit 1";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
			$rdate=$info["rdate"];
		}
		else
			$rdate=$date;
	}
	else
		$rdate=$date;
	return $rdate;
}
function check_removeperf($date)
{
	$check=false;
	$query="select sum(xelec+xgas) as stotal from sales_report_real where ddate='".$date."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			$stx=mysql_fetch_assoc($result);
			if(!empty($stx["stotal"]) && $stx["stotal"]>0)
				$check=true;
		}
	}
	return $check;
}
function getgraphweektotal_off_noffice_real_noffice($date1,$date2,$office)
{
	$stotal=0;
	$qxx="select sum(xelec+xgas) as stotal from sales_report_real where ddate between '".$date1."' and '".$date2."' and office='".$office."'";
	if($rxx=mysql_query($qxx))
	{
		if(($numxx=mysql_num_rows($rxx))>0)
		{
			$stx=mysql_fetch_assoc($rxx);
			if(!empty($stx["stotal"]) && $stx["stotal"]>0)
				$stotal=$stx["stotal"];
			else
				$stotal=0;
		}
		else
			$stotal=0;
	}
	else
		$stotal=0;
	return $stotal;
}
function getRealMonthTotal_noffice($date,$office)
{
	date_default_timezone_set('America/New_York');
	$xtotal=0;
	$xplit=explode("-",$date);
	$month=date("F",mktime(0, 0, 0, $xplit[1],$xplit[2],$xplit[0]));
	$xqoff="select * from sales_an_office where office='".$office."'";
	if($xre=mysql_query($xqoff))
	{
		if(($xnumoff=mysql_num_rows($xre))>0)
		{
			$xoff_info=mysql_fetch_assoc($xre);
			$xtotal=$xoff_info[$month];
			if(empty($xtotal))
				$xtotal=0;
		}
	}
	return $xtotal;
}
function getgraphweektotal_off_noffice_office_real_one($date1,$office)
{
	$stotal=0;
	$qxx="select (sum(xelec)+sum(xgas)) as stotal from sales_report_real where ddate='".$date1."' and office='".$office."'";
	if($rxx=mysql_query($qxx))
	{
		if(($numxx=mysql_num_rows($rxx))>0)
		{
			$stx=mysql_fetch_assoc($rxx);
			if(!empty($stx["stotal"]) && $stx["stotal"]>0)
				$stotal=$stx["stotal"];
			else
				$stotal=0;
		}
		else
			$stotal=0;
	}
	else
		$stotal=0;
	return $stotal;
}
function checkRightPer($fday,$lday,$gtotal)
{
	$total_per=0;
	$realtotal=0;
	$query="select * from rec_office order by id";
	if($result=mysql_query($query))
	{
		if(($numrows=mysql_num_rows($result))>0)
		{
			while($rows=mysql_fetch_array($result))
			{
				$xtotal=getgraphweektotal_off_real($fday,$lday,$rows["id"]);
				$oxtotal_per=@($xtotal/$gtotal)*100;
				$oxtotal_per=number_format($oxtotal_per, 2, '.', '');
				$total_per +=$oxtotal_per;
			}
			if($total_per<100)
			{
				$checktotal=100-$total_per;
				$checktotal=number_format($checktotal, 2, '.', '');
				$realtotal=$checktotal;
			}
		}
	}
	return $realtotal;
}
function getYearTotal()
{
	$stotal=0;
	$query="select sum(total) as stotal from sales_an_sales";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
			if($info["stotal"]>0)
				$stotal=$info["stotal"];
		}
	}
	return $stotal;
}
function getRunOri($office)
{
	$runori=true;
	if(!empty($office) && $office !="all")
	{
		$xoffx=base64_decode($office);
		$xqoff="select * from sales_an_office where office='".$xoffx."'";
		if($xre=mysql_query($xqoff))
		{
			if(($xnumoff=mysql_num_rows($xre))>0)
				$runori=false;
			else
				$_SESSION["salesresult"]="No Goal Information For This Office Found";
		}
		else
			$_SESSION["salesresult"]="No Goal Information For This Office Found";
	}
	return $runori;
}
function getBarTotalByOffice($office,$tyear,$nyear)
{
	$stotal=0.00;
	$date1=$tyear."-04-01";
	$date2=$nyear."-03-31";
	$query="select sum(xelec+xgas) as stotal from sales_report_real where office='".$office."' and ddate between '".$date1."' and '".$date2."'";
	if($result=mysql_query($query))
	{
		if(($num=mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
			if($info["stotal"]>0 && !empty($info["stotal"]))
				$stotal=$info["stotal"];
		}
	}
	return $stotal;
}
function whoToReport($id)
{
	$reportid="";
	$manid="";
	$query="select * from sales_report where agentid='".$id."' order by fromdate desc limit 1";
	if($result=mysql_query($query))
	{
		if(($num=mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
			$reportid=$info["userid"];
		}
	}
	if(!empty($reportid))
	{
		$aname=getName($reportid);
		$rxplit=explode(" ",$aname);
		$query="select * from sales_agent where name like '".clean($aname)."%' limit 1";
		if($result=mysql_query($query))
		{
			if(($num=mysql_num_rows($result))>0)
			{
				$info=mysql_fetch_assoc($result);
				$manid=$info["id"];
			}
		}
	}
	return $manid;
}
function getPieIndTotal_realx($date1,$date2,$office,$grandtotal)
{
	$cont=array();
	$user=array();
	$adrest=false;
	$ind="";
	$total="";
	$restteam=0;
	$qin="select distinct userid from sales_report_real where office='".$office."' and ddate between '".$date1."' and '".$date2."' order by userid";
	if($rin=mysql_query($qin))
	{
		if(($numin=mysql_num_rows($rin))>0)
		{
			$count=0;
			$countx=0;
			//fill the array with team managers and leader
			while($rinx=mysql_fetch_array($rin))
			{
				if(isLeader_name($rinx["userid"]))
				{
					$uname=getAgent($rinx["userid"]);
					$uid=$rinx["userid"];
					$utotal=0;
					$qinx="select (sum(xelec)+sum(xgas)) as total from sales_report_real where office='".$office."' and ddate between '".$date1."' and '".$date2."' and userid='".$rinx["userid"]."' order by userid";
					if($rinxx=mysql_query($qinx))
					{
						if(($numinx=mysql_num_rows($rinxx))>0)
						{
							$rinxxx=mysql_fetch_assoc($rinxx);
							$utotal=$rinxxx["total"];
						}
					}
					if(sizeof($user)>0)
					{
						$found=false;
						for($i=0;$i<sizeof($user);$i++)
						{
							if($user[$i]["userid"]==$rinx["userid"])
							{
								$found=true;
								$xtotal=$user[$i]["stotal"];
								$utotal +=$xtotal;
								$user[$i]['stotal']=$utotal;
								break;
							}
						}
						if(!$found)
							$user[]=array('userid'=>$uid,'name'=>$uname,'stotal'=>$utotal);
					}
					else
						$user[]=array('userid'=>$uid,'name'=>$uname,'stotal'=>$utotal);
				}
			}
			//once array is filled with the managesr and team leader, search their respective agents and refill the totals
			if(sizeof($user)>0)
			{
				mysql_data_seek($rin, 0);
				while($rinx=mysql_fetch_array($rin))
				{
					if(!isLeader_name($rinx["userid"]))
					{
						$uname=getAgent($rinx["userid"]);
						$uid=$rinx["userid"];
						$utotal=0;
						$qinx="select (sum(xelec)+sum(xgas)) as total from sales_report_real where office='".$office."' and ddate between '".$date1."' and '".$date2."' and userid='".$rinx["userid"]."' order by userid";
						if($rinxx=mysql_query($qinx))
						{
							if(($numinx=mysql_num_rows($rinxx))>0)
							{
								$rinxxx=mysql_fetch_assoc($rinxx);
								$utotal=$rinxxx["total"];
							}
						}
						$reportto=whoToReport($rinx["userid"]);
						if(!empty($reportto))
						{
							$found=false;
							for($i=0;$i<sizeof($user);$i++)
							{
								if($user[$i]["userid"]==$reportto)
								{
									$found=true;
									$xtotal=$user[$i]["stotal"];
									$utotal +=$xtotal;
									$user[$i]['stotal']=$utotal;
									break;
								}
							}
							if(!$found)
								$restteam +=$utotal;
						}
						else
							$restteam +=$utotal;
					}
				}
			}
		}
	}
	if(sizeof($user)>0)
	{
		for($i=0;$i<sizeof($user);$i++)
		{
			$stotal=$user[$i]["stotal"];
			if($stotal>0)
			{
				$stotal=@($stotal/$grandtotal)*100;
				$stotal=number_format($stotal, 2, '.', '');
			}
			else
				$stotal=0.00;
			if(!empty($ind))
				$ind .=",'".$user[$i]["name"]."'";
			else
				$ind ="'".$user[$i]["name"]."'";
			if(empty($total))
				$total="".$stotal."";
			else
				$total .=",".$stotal."";
		}
	}
	if($restteam>0)
	{
		if(!empty($ind))
			$ind .=",'Agents Only'";
		else
			$ind ="'Agents Only";
		$restteam=@($restteam/$grandtotal)*100;
		$restteam=number_format($restteam, 2, '.', '');
		if($total==0)
			$total="".$restteam."";
		else
			$total .=",".$restteam."";
	}
	$cont[]=array('name'=>$ind,'total'=>$total);
	return $cont;
}
function lessRows($date,$fday,$lday)
{
	$returnc=true;
	$query = "select * from sales_report_real where ddate='".$date."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
			$returnc=false;
	}
	if($returnc)
	{
		$query = "select sum(xelec+xgas) as stotal from sales_report_real where ddate between '".$fday."' and '".$lday."'";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				$info=mysql_fetch_assoc($result);
				$stotal=$info["stotal"];
				if($stotal>0)
					$returnc=true;
				else
					$returnc=false;
			}
		}
	}
	return $returnc;
}
function getMonthByName($m)
{
	$month_number="";
	$monthnames = array(
	1 => 'January',
	2 => 'February',
	3 => 'March',
	4 => 'April',
	5 => 'May',
	6 => 'June',
	7 => 'July',
	8 => 'August',
	9 => 'September',
	10 => 'October',
	11 => 'November',
	12 => 'December');	
	for($i=1;$i<=12;$i++)
	{
		if(date("F", mktime(0, 0, 0, $i, 1, 0)) == $m)
			$month_number = $i;
	}
	return $month_number;
}
function switchMonth($rdate,$dmonth)
{
	$newdate="";
	if(!empty($rdate) && !empty($dmonth))
	{
		$rdatex=explode("-",$rdate);
		if(sizeof($rdatex)>2)
		{
			$year=$rdatex[0];
			$month=$rdatex[1];
			$day=$rdatex[2];
			$monthx=getMonthByName($dmonth);
			if(!empty($monthx))
			{
				$newdate=$year."-".$monthx."-".$day;
				$newdate=fixdate_comps("mildate",$newdate);		
			}
		}
	}
	return $newdate;
}
function getAgentSales($id)
{
	$stotal=0;
	if(!empty($id))
	{
		$qxx="select sum(stotal) as total from sales_report where agentid='".$id."'";
		if($rxx=mysql_query($qxx))
		{
			if(($numxx=mysql_num_rows($rxx))>0)
			{
				$stx=mysql_fetch_assoc($rxx);
				if(!empty($stx["total"]) && $stx["total"]>0)
					$stotal=$stx["total"];
				else
					$stotal=0;
			}
			else
				$stotal=0;
		}
		else
			$stotal=0;
	}
	else
		$stotal=0;
	return $stotal;
}
function isLeader_name($id)
{
	$aname=getAgentInfo('name',$id);
	$query = "select * from task_users where name like '".clean($aname)."%'";
	if($result = mysql_query($query))
	{
		if(($numrows = mysql_num_rows($result))>0)
		{
			$info = mysql_fetch_assoc($result);
			if($info["type"]=="5" || $info["type"]=="6" || $info["type"]=="7")
				return true;
			else
				return false;
		}
		else
			return false;
	}
	else
		return false;
}
function getPrevMonday($date)
{
	date_default_timezone_set('America/New_York');
	$lweek=date('Y-m-d', strtotime("last week", strtotime($date)) );
	$csunday=date('l',strtotime($lweek));
	if($csunday !="Sunday")
		$xdate=date('Y-m-d', strtotime("last sunday", strtotime($lweek)) );
	else
		$xdate=$lweek;
	$monday=date('Y-m-d', strtotime("next monday", strtotime($xdate)));
	return $monday;
}
function getMonday($y,$m)
{
	  list($n,$d) = explode('-', date('t-d', strtotime("Monday, $y-$m")));
	  $days = array();
	  while ($d <= $n)
	  {
		$days[] = sprintf("%04d-%02d-%02d", $y, $m, $d);
		$d += 7;
	  }
	  return $days;
}
function getGraPdayAmount_real($date1)
{
	$qxx="select (sum(xelec)+sum(xgas)) as stotal from sales_report_real where ddate='".$date1."'";
	if($rxx=mysql_query($qxx))
	{
		if(($numxx=mysql_num_rows($rxx))>0)
		{
			$infoxx=mysql_fetch_assoc($rxx);
			if(empty($infoxx["stotal"]) || $infoxx["stotal"]<1)
				$xtotal=0;
			else
				$xtotal=$infoxx["stotal"];
		}
		else
			$xtotal=0;
	}
	else
		$xtotal=0;
	return $xtotal;
}
function getGraPdayAmount_real_office($date1,$office)
{
	$qxx="select (sum(xelec)+sum(xgas)) as stotal from sales_report_real where ddate='".$date1."' and office='".$office."'";
	if($rxx=mysql_query($qxx))
	{
		if(($numxx=mysql_num_rows($rxx))>0)
		{
			$infoxx=mysql_fetch_assoc($rxx);
			if(empty($infoxx["stotal"]) || $infoxx["stotal"]<1)
				$xtotal=0;
			else
				$xtotal=$infoxx["stotal"];
		}
		else
			$xtotal=0;
	}
	else
		$xtotal=0;
	return $xtotal;
}
function getgraphweektotal_off_noffice_real($date1,$date2)
{
	$stotal=0;
	$qxx="select sum(xelec+xgas) as stotal from sales_report_real where ddate between '".$date1."' and '".$date2."'";
	if($rxx=mysql_query($qxx))
	{
		if(($numxx=mysql_num_rows($rxx))>0)
		{
			$stx=mysql_fetch_assoc($rxx);
			if(!empty($stx["stotal"]) && $stx["stotal"]>0)
				$stotal=$stx["stotal"];
			else
				$stotal=0;
		}
		else
			$stotal=0;
	}
	else
		$stotal=0;
	return $stotal;
}
function getgraphweektotal_off_real_one($date1,$office)
{
	$stotal=0;
	$qxx="select (sum(xelec)+sum(xgas)) as stotal from sales_report_real where ddate='".$date1."' and office='".$office."'";
	if($rxx=mysql_query($qxx))
	{
		if(($numxx=mysql_num_rows($rxx))>0)
		{
			$stx=mysql_fetch_assoc($rxx);
			if(!empty($stx["stotal"]) && $stx["stotal"]>0)
				$stotal=$stx["stotal"];
			else
				$stotal=0;
		}
		else
			$stotal=0;
	}
	else
		$stotal=0;
	return $stotal;
}
function getgraphweektotal_off_noffice_real_one($date1)
{
	$stotal=0;
	$qxx="select (sum(xelec)+sum(xgas)) as stotal from sales_report_real where ddate='".$date1."'";
	if($rxx=mysql_query($qxx))
	{
		if(($numxx=mysql_num_rows($rxx))>0)
		{
			$stx=mysql_fetch_assoc($rxx);
			if(!empty($stx["stotal"]) && $stx["stotal"]>0)
				$stotal=$stx["stotal"];
			else
				$stotal=0;
		}
		else
			$stotal=0;
	}
	else
		$stotal=0;
	return $stotal;
}
function getgraphweektotal_off_real($date1,$date2,$office)
{
	$stotal=0;
	$qxx="select (sum(xelec)+sum(xgas)) as stotal from sales_report_real where ddate between '".$date1."' and '".$date2."' and office='".$office."'";
	if($rxx=mysql_query($qxx))
	{
		if(($numxx=mysql_num_rows($rxx))>0)
		{
			$stx=mysql_fetch_assoc($rxx);
			if(!empty($stx["stotal"]) && $stx["stotal"]>0)
				$stotal=$stx["stotal"];
			else
				$stotal=0;
		}
		else
			$stotal=0;
	}
	else
		$stotal=0;
	return $stotal;
}
function getPieIndTotal_real($date1,$date2,$office,$grandtotal)
{
	$cont=array();
	$adrest=false;
	$ind="";
	$total="";
	$restteam=0;
	$qin="select distinct userid from sales_report_real where office='".$office."' and ddate between '".$date1."' and '".$date2."' order by userid";
	if($rin=mysql_query($qin))
	{
		if(($numin=mysql_num_rows($rin))>0)
		{
			$count=0;
			$countx=0;
			while($rinx=mysql_fetch_array($rin))
			{
				if(isLeader_name($rinx["userid"]))
				{
					$uname=getAgent($rinx["userid"]);
					if($count==0)
						$ind="'".$uname."'";
					else
						$ind .=",'".$uname."'";
					$qinx="select (sum(xelec)+sum(xgas)) as total from sales_report_real where office='".$office."' and ddate between '".$date1."' and '".$date2."' and userid='".$rinx["userid"]."' order by userid";
					if($rinxx=mysql_query($qinx))
					{
						if(($numinx=mysql_num_rows($rinxx))>0)
						{
							$rinxxx=mysql_fetch_assoc($rinxx);
							$stotal=$rinxxx["total"];
							if($stotal>0)
							{
								$stotal=@($stotal/$grandtotal)*100;
								$stotal=number_format($stotal, 2, '.', '');
							}
							else
								$stotal=0.00;
							if($countx==0)
								$total="".$stotal."";
							else
								$total .=",".$stotal."";
							$countx++;
						}
					}
					$count++;	
				}
				else
				{
					$qinx="select (sum(xelec)+sum(xgas)) as total from sales_report_real where office='".$office."' and ddate between '".$date1."' and '".$date2."' and userid='".$rinx["userid"]."' order by userid";
					if($rinxx=mysql_query($qinx))
					{
						if(($numinx=mysql_num_rows($rinxx))>0)
						{
							$rinxxx=mysql_fetch_assoc($rinxx);
							$stotal=$rinxxx["total"];
							$restteam +=$stotal;
						}
					}
					//$count++;
				}
			}
		}
	}
	if($restteam>0)
	{
		if(!empty($ind))
			$ind .=",'Agents Only'";
		else
			$ind ="'Agents Only";
		$restteam=@($restteam/$grandtotal)*100;
		$restteam=number_format($restteam, 2, '.', '');
		if($total==0)
			$total="".$restteam."";
		else
			$total .=",".$restteam."";
	}
	$cont[]=array('name'=>$ind,'total'=>$total);
	return $cont;
}
function getPieIndTotal($date1,$date2,$office,$grandtotal)
{
	$cont=array();
	$ind="";
	$total="";
	$qin="select distinct userid from sales_report where office='".$office."' and fromdate between '".$date1."' and '".$date2."' order by userid";
	if($rin=mysql_query($qin))
	{
		if(($numin=mysql_num_rows($rin))>0)
		{
			$count=0;
			$countx=0;
			while($rinx=mysql_fetch_array($rin))
			{
				$uname=getName($rinx["userid"]);
				if($count==0)
					$ind="'".$uname."'";
				else
					$ind .=",'".$uname."'";
				$qinx="select sum(stotal) as total from sales_report where office='".$office."' and fromdate between '".$date1."' and '".$date2."' and userid='".$rinx["userid"]."' order by userid";
				if($rinxx=mysql_query($qinx))
				{
					if(($numinx=mysql_num_rows($rinxx))>0)
					{
						$rinxxx=mysql_fetch_assoc($rinxx);
						$stotal=$rinxxx["total"];
						if($stotal>0)
						{
							$stotal=@($stotal/$grandtotal)*100;
							$stotal=number_format($stotal, 2, '.', '');
						}
						else
							$stotal=0.00;
						if($countx==0)
							$total="".$stotal."";
						else
							$total .=",".$stotal."";
						$countx++;
					}
				}
				$count++;		  
			}
		}
	}
	$cont[]=array('name'=>$ind,'total'=>$total);
	return $cont;
}
function getRealMonthTotal($month)
{
	$stotal=0;
	$month=ucwords(strtolower($month));
	if(!empty($month))
	{
		$qxx="select * from sales_an_sales where name='".clean($month)."'";
		if($rxx=mysql_query($qxx))
		{
			if(($numxx=mysql_num_rows($rxx))>0)
			{
				$stx=mysql_fetch_assoc($rxx);
				if(!empty($stx["total"]) && $stx["total"]>0)
					$stotal=$stx["total"];
				else
					$stotal=0;
			}
			else
				$stotal=0;
		}
		else
			$stotal=0;
	}
	else
		$stotal=0;
	return $stotal;
}
function getLastDay($cdate)
{
	$lday="";
	if(!empty($cdate))
	{
		$cdatex=explode("-",$cdate);
		$month=$cdatex[1];
		$year=$cdatex[0];
		date_default_timezone_set('America/New_York');
		$lday=date('Y-m-d',strtotime('-1 second',strtotime('+1 month',strtotime($month.'/01/'.$year.' 00:00:00'))));
	}
	return $lday;
}
function getFirstDay($cdate)
{
	$fday="";
	if(!empty($cdate))
	{
		$cdatex=explode("-",$cdate);
		$month=$cdatex[1];
		$year=$cdatex[0];
		$fday=$year."-".$month."-01";
	}
	return $fday;
}
function getgraphweektotal_off($date1,$date2,$office)
{
	$stotal=0;
	$qxx="select sum(stotal) as stotal from sales_report where fromdate between '".$date1."' and '".$date2."' and office='".$office."'";
	if($rxx=mysql_query($qxx))
	{
		if(($numxx=mysql_num_rows($rxx))>0)
		{
			$stx=mysql_fetch_assoc($rxx);
			if(!empty($stx["stotal"]) && $stx["stotal"]>0)
				$stotal=$stx["stotal"];
			else
				$stotal=0;
		}
		else
			$stotal=0;
	}
	else
		$stotal=0;
	return $stotal;
}
function getgraphweektotal_off_noffice($date1,$date2)
{
	$stotal=0;
	$qxx="select sum(stotal) as stotal from sales_report where fromdate between '".$date1."' and '".$date2."'";
	if($rxx=mysql_query($qxx))
	{
		if(($numxx=mysql_num_rows($rxx))>0)
		{
			$stx=mysql_fetch_assoc($rxx);
			if(!empty($stx["stotal"]) && $stx["stotal"]>0)
				$stotal=$stx["stotal"];
			else
				$stotal=0;
		}
		else
			$stotal=0;
	}
	else
		$stotal=0;
	return $stotal;
}
function getThursday($y,$m)
{
	  list($n,$d) = explode('-', date('t-d', strtotime("Thursday, $y-$m")));
	  $days = array();
	  while ($d <= $n)
	  {
		$days[] = sprintf("%04d-%02d-%02d", $y, $m, $d);
		$d += 7;
	  }
	  return $days;
}
/*function getGraPday($date)
{
	date_default_timezone_set('America/New_York');
	if(!empty($date))
	{
		$str=date('l', strtotime($date));
		$xdate=explode("-",$date);
		$y=$xdate[0];
		$m=$xdate[1];
		$d=$xdate[2];
		if($str=="Saturday")
			return date("Y-m-d", mktime(0, 0, 0, $m,$d-1,$y));
		else if($str=="Sunday")
			return date("Y-m-d", mktime(0, 0, 0, $m,$d-2,$y));
		else if($str=="Monday")
			return date("Y-m-d", mktime(0, 0, 0, $m,$d-3,$y));
		else if($str=="Tuesday")
			return date("Y-m-d", mktime(0, 0, 0, $m,$d-4,$y));
		else if($str=="Wednesday")
			return date("Y-m-d", mktime(0, 0, 0, $m,$d-5,$y));
		else if($str=="Thursday")
			return date("Y-m-d", mktime(0, 0, 0, $m,$d-6,$y));
		else
			return $date;
	}
	else
		return $date;
}*/
function getGraPdayAmount($date1,$date2)
{
	$qxx="select sum(stotal) as stotal from sales_report where fromdate between '".$date1."' and '".$date2."'";
	if($rxx=mysql_query($qxx))
	{
		if(($numxx=mysql_num_rows($rxx))>0)
		{
			$infoxx=mysql_fetch_assoc($rxx);
			if(empty($infoxx["stotal"]) || $infoxx["stotal"]<1)
				$xtotal=0;
			else
				$xtotal=$infoxx["stotal"];
		}
		else
			$xtotal=0;
	}
	else
		$xtotal=0;
	return $xtotal;
}
function getTotalAssigned($id)
{
	$query = "select sum(goals) as total from sales_goals_ind where goalid='".$id."'";
	if($result = mysql_query($query))
	{
		if(($numrows = mysql_num_rows($result))>0)
		{
			$info = mysql_fetch_assoc($result);
			if(empty($info["total"]) || $info["total"]<1)
				return "0";
			else
				return $info["total"];
		}
		else
			return "0";
	}
	else
		return "0";
}
function isLeader($id)
{
	$aname = getAgent($id);
	$query = "select * from task_users where name like '".clean($aname)."%'";
	if($result = mysql_query($query))
	{
		if(($numrows = mysql_num_rows($result))>0)
		{
			$info = mysql_fetch_assoc($result);
			if($info["type"]=="5" || $info["type"]=="6" || $info["type"]=="7")
				return true;
			else
				return false;
		}
		else
			return false;
	}
	else
		return false;
}
function setLoginEmail($type)
{
	if($type !="1" && $type !="2" && $type !="4")
		return true;
	else
		return false;
}
function showChooseMan($type)
{
	if($type=="5" || $type=="6")
		return true;
	else
		return false;
}
function checkManSel($type,$office)
{
	if(($type=="5" || $type=="6") && empty($office))
	{
		return true;
	}
	else
		return false;
}
function checkReportTo($type)
{
	if($type=="5")
		return true;
	else
		return false;
}
function getCalDate($str)
{
	date_default_timezone_set('America/New_York');
	if($str=="Saturday")
	{
		return date("Y-m-d", mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
	}
	else if($str=="Sunday")
	{
		return date("Y-m-d", mktime(0, 0, 0, date("m"),date("d")-2,date("Y")));
	}
	else if($str=="Monday")
	{
		return date("Y-m-d", mktime(0, 0, 0, date("m"),date("d")-3,date("Y")));
	}
	else if($str=="Tuesday")
	{
		return date("Y-m-d", mktime(0, 0, 0, date("m"),date("d")-4,date("Y")));
	}
	else if($str=="Wednesday")
	{
		return date("Y-m-d", mktime(0, 0, 0, date("m"),date("d")-5,date("Y")));
	}
	else if($str=="Thursday")
	{
		return date("Y-m-d", mktime(0, 0, 0, date("m"),date("d")-6,date("Y")));
	}
	else
	{
		return date("Y-m-d", mktime(0, 0, 0, date("m"),date("d"),date("Y")));
	}
}
function getGraPday($date)
{
	date_default_timezone_set('America/New_York');
	if(!empty($date))
	{
		$str=date('l', strtotime($date));
		$xdate=explode("-",$date);
		$y=$xdate[0];
		$m=$xdate[1];
		$d=$xdate[2];
		if($str=="Saturday")
			return date("Y-m-d", mktime(0, 0, 0, $m,$d-1,$y));
		else if($str=="Sunday")
			return date("Y-m-d", mktime(0, 0, 0, $m,$d-2,$y));
		else if($str=="Monday")
			return date("Y-m-d", mktime(0, 0, 0, $m,$d-3,$y));
		else if($str=="Tuesday")
			return date("Y-m-d", mktime(0, 0, 0, $m,$d-4,$y));
		else if($str=="Wednesday")
			return date("Y-m-d", mktime(0, 0, 0, $m,$d-5,$y));
		else if($str=="Thursday")
			return date("Y-m-d", mktime(0, 0, 0, $m,$d-6,$y));
		else
			return $date;
	}
	else
		return $date;
}
function getArrayDays($str)
{
	$days=array();
	$ndays="";
	$xdays=7;
	date_default_timezone_set('America/New_York');
	$today =date('Y-m-d');
	if($str=="Saturday")
		$ndays=1;
	else if($str=="Sunday")
		$ndays=2;
	else if($str=="Monday")
		$ndays=3;
	else if($str=="Tuesday")
		$ndays=4;
	else if($str=="Wednesday")
		$ndays=5;
	else if($str=="Thursday")
		$ndays=6;
	if(!empty($ndays))
	{
		$counter=$ndays;
		while($counter>0)
		{
			$days[]=date("Y-m-d", mktime(0, 0, 0, date("m"),date("d")-$counter,date("Y")));
			$counter--;
			$xdays--;
		}
		$days[]=$today;
	}
	else
	{
		$days[]=date("Y-m-d", mktime(0, 0, 0, date("m"),date("d"),date("Y")));
		$xdays--;
	}
	if($xdays>0)
	{
		$counter=$xdays;
		$yday=0;
		for($x=1;$x<$counter;$x++)
		{
			$yday++;
			$days[]=date("Y-m-d", mktime(0, 0, 0, date("m"),date("d")+$yday,date("Y")));
		}
	}
	return $days;
}
function getSalesRow($office,$tday,$today)
{
	$query = "select distinct agentid from sales_report where office='".$office."' and (fromdate between '".$tday."' and '".$today."')";
	if($result = mysql_query($query))
	{
		if(($numrows = mysql_num_rows($result))>0)
				return $numrows;
		else
			return "0";
	}
	else
		return "0";
}
function getGoal($user,$office)
{
	$query = "select * from sales_goals where userid='".$user."' and office='".$office."'";
	if($result=mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$info = mysql_fetch_assoc($result);
			if(empty($info["goals"]) || $info["goals"]<1)
				return "0";
			else
				return $info["goals"];
		}
		else
			return "na";
	}
	else
		return "0";
}
function getGoalx($user,$office)
{
	$query = "select * from sales_goals_ind where userid='".$user."'";
	if($result=mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$info = mysql_fetch_assoc($result);
			if(empty($info["goals"]) || $info["goals"]<1)
				return "0";
			else
				return $info["goals"];
		}
		else
			return "na";
	}
	else
		return "0";
}
function getGoalx_info($user,$office)
{
	$infox=array();
	$query = "select * from sales_goals_ind where userid='".$user."'";
	if($result=mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$info = mysql_fetch_assoc($result);
			$infox[]=array("goalid"=>$info["goalid"],'goals'=>$info["goals"]);
			return $infox;
		}
		else
			return "";
	}
	else
		return "";
}
function getGoalox($office)
{
	$query = "select sum(goals) as goals from sales_goals_office where office='".$office."'";
	if($result=mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$info = mysql_fetch_assoc($result);
			if(empty($info["goals"]) || $info["goals"]<1)
				return "0";
			else
				return $info["goals"];
		}
		else
			return "0";
	}
	else
		return "0";
}
function getGoalo($office)
{
	$query = "select sum(goals) as goals from sales_goals where office='".$office."'";
	if($result=mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$info = mysql_fetch_assoc($result);
			if(empty($info["goals"]) || $info["goals"]<1)
				return "0";
			else
				return $info["goals"];
		}
		else
			return "0";
	}
	else
		return "0";
}
function getGoalmx($userid,$office)
{
	$grandtotal=0;
	$leader="";
	$query = "select sum(goals) as total from sales_goals_ind where userid='".$userid."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$info = mysql_fetch_assoc($result);
				$grandtotal += $info["total"];
		}
	}
	$query = "select * from task_users where report_to='".$userid."' and office='".$office."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			while($rows = mysql_fetch_array($result))
			{
				if(empty($leader))
					$leader="'".$rows["id"]."'";
				else
					$leader .=",'".$rows["id"]."'";
			}
		}
	}
	if(!empty($leader))
	{
		$query = "select sum(goals) as total from sales_goals_ind where userid in (".$leader.")";
		if($result = mysql_query($query))
		{
			if(($num_rows = mysql_num_rows($result))>0)
			{
				$info = mysql_fetch_assoc($result);
					$grandtotal += $info["total"];
			}
		}
	}
	return $grandtotal;
}
function getGoalm($userid,$office)
{
	$grandtotal=0;
	$leader="";
	$query = "select sum(goals) as total from sales_goals where office='".$office."' and userid='".$userid."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$info = mysql_fetch_assoc($result);
				$grandtotal += $info["total"];
		}
	}
	$query = "select * from task_users where report_to='".$userid."' and office='".$office."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			while($rows = mysql_fetch_array($result))
			{
				if(empty($leader))
					$leader="'".$rows["id"]."'";
				else
					$leader .=",'".$rows["id"]."'";
			}
		}
	}
	if(!empty($leader))
	{
		$query = "select sum(goals) as total from sales_goals where office='".$office."' and userid in (".$leader.")";
		if($result = mysql_query($query))
		{
			if(($num_rows = mysql_num_rows($result))>0)
			{
				$info = mysql_fetch_assoc($result);
					$grandtotal += $info["total"];
			}
		}
	}
	return $grandtotal;
}
function getRunTotal_search($userids,$sofficexx,$sagentxx,$datecompx)
{
	$dquery="";
	if(!empty($sofficexx))
		$dquery .=" and office='".$sofficexx."' ";
	if(!empty($sagentxx))
		$dquery .=" and agentid='".$sagentxx."' ";
	//$query = "select sum(stotal) as total from sales_report where agentid='".$userids."' $dquery $datecompx";
	$query = "select sum(stotal) as total from sales_report where $userids $dquery $datecompx";
	if($result=mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$info = mysql_fetch_assoc($result);
			if(empty($info["total"]) || $info["total"]<1)
				return 0;
			else
				return $info["total"];
		}
		else
			return "0";
	}
	else
		return "0";
}
function getRunTotal($id,$tday,$today)
{
	if(!empty($id))
		$query = "select sum(stotal) as total from sales_report where userid='".$id."' and (fromdate between '".$tday."' and '".$today."')";
	else
		$query = "select sum(stotal) as total from sales_report where fromdate between '".$tday."' and '".$today."'";
	if($result=mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$info = mysql_fetch_assoc($result);
			if(empty($info["total"]) || $info["total"]<1)
				return "0";
			else
				return $info["total"];
		}
		else
			return "0";
	}
	else
		return "0";
}
function getRunTotalall($tday,$today)
{
	$query = "select sum(stotal) as total from sales_report where fromdate between '".$tday."' and '".$today."'";
	if($result=mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$info = mysql_fetch_assoc($result);
			if(empty($info["total"]) || $info["total"]<1)
				return "0";
			else
				return $info["total"];
		}
		else
			return "0";
	}
	else
		return "0";
}
function getRunTotalall_week($userid,$days)
{
	$amt=array();
	if(sizeof($days)>0)
	{
		for($i=0;$i<sizeof($days);$i++)
		{
			$weekday=fixdate_comps('weekday',$days[$i]);
			if($weekday=="Sunday")
				$weekdayx="S";
			else if($weekday=="Monday")
				$weekdayx="M";
			else if($weekday=="Tuesday")
				$weekdayx="T";
			else if($weekday=="Wednesday")
				$weekdayx="M";
			else if($weekday=="Thursday")
				$weekdayx="TH";
			else if($weekday=="Friday")
				$weekdayx="F";
			else if($weekday=="Saturday")
				$weekdayx="Sat";
			else
				$weekdayx="N/A";
			$query = "select sum(stotal) as total from sales_report where fromdate='".$days[$i]."' and userid='".$userid."'";
			if($result=mysql_query($query))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					$info = mysql_fetch_assoc($result);
					if(empty($info["total"]) || $info["total"]<1)
						$amt[]=array('day'=>$weekdayx,'amount'=>0);
					else
						$amt[]=array('day'=>$weekdayx,'amount'=>$info["total"]);
				}
				else
					$amt[]=array('day'=>$weekdayx,'amount'=>0);
			}
			else
				$amt[]=array('day'=>$weekdayx,'amount'=>0);
		}
	}
	return $amt;
}
function getRunTotalall_week_man($userid,$office,$ardays)
{
	$grandtotal=0;
	$grandtotalr=array();
	$leader="";
	if(sizeof($ardays)>0)
	{
		for($i=0;$i<sizeof($ardays);$i++)
		{
			$cday=$ardays[$i];
			$query = "select sum(stotal) as total from sales_report where office='".$office."' and userid='".$userid."' and (fromdate ='".$cday."')";
			if($result = mysql_query($query))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					$info = mysql_fetch_assoc($result);
						$grandtotal += $info["total"];
				}
			}
			$query = "select * from task_users where report_to='".$userid."' and office='".$office."'";
			if($result = mysql_query($query))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					while($rows = mysql_fetch_array($result))
					{
						if(empty($leader))
							$leader="'".$rows["id"]."'";
						else
							$leader .=",'".$rows["id"]."'";
					}
				}
			}
			if(!empty($leader))
			{
				$query = "select sum(stotal) as total from sales_report where office='".$office."' and userid in (".$leader.") and (fromdate='".$cday."')";
				if($result = mysql_query($query))
				{
					if(($num_rows = mysql_num_rows($result))>0)
					{
						$info = mysql_fetch_assoc($result);
							$grandtotal += $info["total"];
					}
				}
			}
			$grandtotalr[]=$grandtotal;
			$grandtotal=0;
			$leader="";
		}
	}
	return $grandtotalr;
}
function getRunTotalall_week_office($office,$ardays)
{
	$grandtotal=0;
	$grandtotalr=array();
	$leader="";
	if(sizeof($ardays)>0)
	{
		for($i=0;$i<sizeof($ardays);$i++)
		{
			$cday=$ardays[$i];
			$query = "select sum(stotal) as total from sales_report where office='".$office."' and (fromdate ='".$cday."')";
			if($result = mysql_query($query))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					$info = mysql_fetch_assoc($result);
						$grandtotal += $info["total"];
				}
			}
			$grandtotalr[]=$grandtotal;
			$grandtotal=0;
			$leader="";
		}
	}
	return $grandtotalr;
}
function getRunTotalall_week_all_office($ardays)
{
	$grandtotal=0;
	$grandtotalr=array();
	$leader="";
	if(sizeof($ardays)>0)
	{
		$qx="select * from rec_office order by name";
		if($rx=mysql_query($qx))
		{
			if(($nmx=mysql_num_rows($rx))>0)
			{
				while($rox=mysql_fetch_array($rx))
				{
					for($i=0;$i<sizeof($ardays);$i++)
					{
						$cday=$ardays[$i];
						$query = "select sum(stotal) as total from sales_report where office='".$rox["id"]."' and (fromdate ='".$cday."')";
						if($result = mysql_query($query))
						{
							if(($num_rows = mysql_num_rows($result))>0)
							{
								$info = mysql_fetch_assoc($result);
									$grandtotal += $info["total"];
							}
						}
						$grandtotalr[]=$grandtotal;
						$grandtotal=0;
						$leader="";
					}
				}
			}
		}
	}
	return $grandtotalr;
}
function getRunTotalbyman($userid,$office,$tday,$today)
{
	$grandtotal=0;
	$leader="";
	$query = "select sum(stotal) as total from sales_report where office='".$office."' and userid='".$userid."' and (fromdate between '".$tday."' and '".$today."')";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$info = mysql_fetch_assoc($result);
				$grandtotal += $info["total"];
		}
	}
	$query = "select * from task_users where report_to='".$userid."' and office='".$office."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			while($rows = mysql_fetch_array($result))
			{
				if(empty($leader))
					$leader="'".$rows["id"]."'";
				else
					$leader .=",'".$rows["id"]."'";
			}
		}
	}
	if(!empty($leader))
	{
		$query = "select sum(stotal) as total from sales_report where office='".$office."' and userid in (".$leader.") and (fromdate between '".$tday."' and '".$today."')";
		if($result = mysql_query($query))
		{
			if(($num_rows = mysql_num_rows($result))>0)
			{
				$info = mysql_fetch_assoc($result);
					$grandtotal += $info["total"];
			}
		}
	}
	return $grandtotal;
}
function getRunTotalo($office,$tday,$today)
{
	$query = "select sum(stotal) as total from sales_report where office='".$office."' and (fromdate between '".$tday."' and '".$today."')";
	if($result=mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$info = mysql_fetch_assoc($result);
			if(empty($info["total"]) || $info["total"]<1)
				return "0";
			else
				return $info["total"];
		}
		else
			return "0";
	}
	else
		return "0";
}
function getRestOfficeTotal($listid,$office,$tday,$today)
{
	if(!empty($listid))
	{
		$query = "select sum(stotal) as total from sales_report where office='".$office."' and userid not in(".$listid.") and (fromdate between '".$tday."' and '".$today."')";
		if($result=mysql_query($query))
		{
			if(($num_rows = mysql_num_rows($result))>0)
			{
				$info = mysql_fetch_assoc($result);
				if(empty($info["total"]) || $info["total"]<1)
					return "0";
				else
					return $info["total"];
			}
			else
				return "0";
		}
		else
			return "0";
	}
	else
		return "0";
}
function getRunTotalpmo($man,$office,$tday,$today)
{
	$query = "select sum(stotal) as total from sales_report where office='".$office."' and userid='".$man."' and (fromdate between '".$tday."' and '".$today."')";
	if($result=mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$info = mysql_fetch_assoc($result);
			if(empty($info["total"]) || $info["total"]<1)
				return "0";
			else
				return $info["total"];
		}
		else
			return "0";
	}
	else
		return "0";
}
function getRunTotal_today_pmo($man,$office,$today)
{
	$query = "select sum(stotal) as total from sales_report where office='".$office."' and userid='".$man."' and fromdate = '".$today."'";
	if($result=mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$info = mysql_fetch_assoc($result);
			if(empty($info["total"]) || $info["total"]<1)
				return "0";
			else
				return $info["total"];
		}
		else
			return "0";
	}
	else
		return "0";
}
function getPreviousDate($id)
{
	$query = "select * from sales_report where userid='".$id."' and todate < CURRENT_DATE  order by todate desc limit 1";
	if($result=mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$info = mysql_fetch_assoc($result);
			return $info["todate"];
		}
		else
			return "";
	}
	else
		return "";
}
function redirect()
{
	$userx = $_SESSION["salesuser"];
	if(!pView($userx["type"]))
	{
		header("location:viewreport.php");
		exit;
	}
}
function getLink()
{
	$user=$_SESSION["salesuser"];
	if(!detectAgent())
		return "mobile/";
	else
		return "";
}
function detectAgent()
{
	$useragent=$_SERVER['HTTP_USER_AGENT'];
	if(preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
	return false;
  return true;
}
function getNDays()
{
	return "1";
}
function getDelOr()
{
	return " img=NULL, orientation=NULL,orientation_office=NULL,orientation_show=NULL,orientation_comp=NULL,observation=NULL,observation_office=NULL,observation_show=NULL,observationer=NULL,observation_comp=NULL,observation_comps=NULL,observation_note=NULL,eornotx='yes',textornotx='yes' ";
}
function getDelQuery()
{
	return " img=NULL, int_show_date=NULL, hired=NULL,interview=NULL,inter_status=NULL,interviewer=NULL,interview_note=NULL,orientation=NULL,orientation_office=NULL,orientation_show=NULL,orientation_comp=NULL,observation=NULL,observation_office=NULL,observation_show=NULL,observationer=NULL,observation_comp=NULL,observation_comps=NULL,observation_note=NULL ";
}
function getDelQuerys()
{
	return " status='1', int_show=NULL,int_show_info=NULL,img=NULL, int_show_date=NULL, hired=NULL,interview=NULL,inter_status=NULL,interviewer=NULL,interview_note=NULL,orientation=NULL,orientation_office=NULL,orientation_show=NULL,orientation_comp=NULL,observation=NULL,observation_office=NULL,observation_show=NULL,observationer=NULL,observation_comp=NULL,observation_comps=NULL,observation_note=NULL,folstatus='1',folcome=NULL,folupdated_by=NULL,folupdated_date=NULL,compdate=NULL,compnote=NULL,foldate=NULL,folnote=NULL ";
}
function checkPassDate($id,$date)
{
	date_default_timezone_set('America/New_York');
	$today = date("Y-m-d H:m:s");
	$datetime1 = new DateTime($today);
	$newdatetime= new DateTime($date);
	if(!empty($id) && !empty($date))
	{
		$queryr = "select * from rec_entries where id='".$id."'";
		if($resultr = mysql_query($queryr))
		{
			if(($num_rowsr = mysql_num_rows($resultr))>0)
			{
				$recinfo = mysql_fetch_assoc($resultr);
				$idate = new DateTime($recinfo["idate"]." ".$recinfo["itime"]);
				if($idate > $datetime1)
				{
					if($idate > $newdatetime)
						return true;
					else
						return false;
				}
				else
					return false;
			}
		}
	}
	return true;
}
function sendSMS($phone,$text)
{
	$user = "kakashi807";
    $password = "family1";
    $api_id = "3359877";
    $baseurl ="http://api.clickatell.com";
    
    $text = urlencode($text);
    $to = "1".$phone;

    /*// auth call
   	 $url = "$baseurl/http/auth?user=$user&password=$password&api_id=$api_id";

    // do auth call
    $ret = file($url);

    // explode our response. return string is on first line of the data returned
    $sess = explode(":",$ret[0]);
    if ($sess[0] == "OK") 
	{
        $sess_id = trim($sess[1]); // remove any whitespace
       	// $url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text&from=13474747952&mo=1";
		$url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text&from=18479735787&mo=1";
//$url="http://$baseurl/http/sendmsg?user=$user&password=$password&api_id=$api_id&from=13474747952&mo=1&to=$to&text=$text";
        // do sendmsg call
        $ret = file($url);
        $send = explode(":",$ret[0]);
        if ($send[0] == "ID") {
            return $send[1];
        } else {
            return "";
        }
    } else {
       return "fail";
    }*/
	$url = "$baseurl/http/sendmsg?user=$user&password=$password&api_id=$api_id&MO=1&from=18479735787&to=$to&text=$text";
	$ret = file($url);
	$send = explode(":",$ret[0]);
	if ($send[0]=="ID")
		return $send[1];
	else
		return "";
}
function sendSMSm($phone,$text)
{
	$user = "kakashi807";
    $password = "family1";
    $api_id = "3359877";
    $baseurl ="http://api.clickatell.com";
    
    $text = urlencode($text);
    $to = $phone;

    /*// auth call
   	 $url = "$baseurl/http/auth?user=$user&password=$password&api_id=$api_id";

    // do auth call
    $ret = file($url);

    // explode our response. return string is on first line of the data returned
    $sess = explode(":",$ret[0]);
    if ($sess[0] == "OK") 
	{
        $sess_id = trim($sess[1]); // remove any whitespace
       	// $url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text&from=13474747952&mo=1";
		 $url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text&from=18479735787&mo=1";
//$url="http://$baseurl/http/sendmsg?user=$user&password=$password&api_id=$api_id&from=13474747952&mo=1&to=$to&text=$text";
        // do sendmsg call
        $ret = file($url);
        $send = explode(":",$ret[0]);
        if ($send[0] == "ID") {
            return $send[1];
        } else {
            return "";
        }
    } else {
       return "fail";
    }*/
	$url = "$baseurl/http/sendmsg?user=$user&password=$password&api_id=$api_id&MO=1&from=18479735787&to=$to&text=$text";
	$ret = file($url);
	$send = explode(":",$ret[0]);
	if ($send[0]=="ID")
		return $send[1];
	else
		return "";
}
function sendSMSr($phone,$text,$scheduled_time)
{
	$user = "kakashi807";
    $password = "family1";
    $api_id = "3359877";
    $baseurl ="http://api.clickatell.com";
    
    $text = urlencode($text);
    $to = "1".$phone;

   /* // auth call
   	 $url = "$baseurl/http/auth?user=$user&password=$password&api_id=$api_id&scheduled_time=$scheduled_time";

    // do auth call
    $ret = file($url);

    // explode our response. return string is on first line of the data returned
    $sess = explode(":",$ret[0]);
    if ($sess[0] == "OK") 
	{
        $sess_id = trim($sess[1]); // remove any whitespace
       	// $url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text&from=13474747952&mo=1";
		   $url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text&from=18479735787&mo=1";
//$url="http://$baseurl/http/sendmsg?user=$user&password=$password&api_id=$api_id&from=13474747952&mo=1&to=$to&text=$text";
        // do sendmsg call
        $ret = file($url);
        $send = explode(":",$ret[0]);
        if ($send[0] == "ID") {
            return $send[1];
        } else {
            return "";
        }
    } else {
       return "fail";
    }*/
	$url = "$baseurl/http/sendmsg?user=$user&password=$password&api_id=$api_id&MO=1&from=18479735787&to=$to&text=$text";
	$ret = file($url);
	$send = explode(":",$ret[0]);
	if ($send[0]=="ID")
		return $send[1];
	else
		return "";
}
function checkNA($str)
{
	if(!empty($str) && $str !="" && $str !='0' && $str !=NULL)
		return $str;
	else
		return "N/A";
}
function pView($type)
{
	if($type !='1' && $type !='2' && $type !='4')
		return false;
	return true;
}
function pViewm($type)
{
	if($type !='1' && $type !='2' && $type !='4' && $type !='7' && $type !='8')
		return false;
	return true;
}
function pViewb($type)
{
	if($type =='1' || $type =='2')
		return true;
	return false;
}
function getIP()
{
	 return $_SERVER['REMOTE_ADDR'];
}
function monthName($month_int)
{
	$month_int = (int)$month_int;
	$timestamp = mktime(0, 0, 0, $month_int);
	return date("F", $timestamp);
}
function fixtomilhour($str)
{
	date_default_timezone_set('UTC');
	if(!empty($str))
	{
		$time = date("H:i:s", strtotime($str));
		if(!empty($time))
			return $time;
		else
			return "";
	}
	else
	 	return "";
}
function fixnormhour($str)
{
	if(!empty($str))
	{
		$time = fixdate_comps("h",$str);
		if(!empty($time))
			return $time;
		else
			return "";
	}
	else
	 	return "";
}
function fixnum($str)
{
	if(!empty($str))
	{
		$numx = explode("-",$str);
		if(!empty($numx) && sizeof($numx)>2)
			return $numx;
		else if(!empty($numx) && sizeof($numx)>1)
		{
			$numa = substr($numx[1],0,3);
			$numb = substr($numx[1],3);
			$numc = array();
			$numc[]=$numx[0];
			$numc[]=$numa;
			$numc[]=$numb;
			return $numc;
		}
		else
		{
			$numa = substr($str,0,3);
			$numb = substr($str,3,3);
			$numc = substr($str,6);
			$numd = array();
			$numd[]=$numa;
			$numd[]=$numb;
			$numd[]=$numc;
			return $numd;
		}
	}
	return "";
}
function getGEO($address){
	// Initialize delay in geocode speed
	$delay = 0;
	$base_url = "http://" . MAPS_HOST . "/maps/geo?output=xml" . "&key=" . KEY;

	// Iterate through the rows, geocoding each address
  $geocode_pending = true;

  while ($geocode_pending) {
    $request_url = $base_url . "&q=" . urlencode($address);
   $xml = simplexml_load_file($request_url) or die("url not loading");

    $status = $xml->Response->Status->code;
    if (strcmp($status, "200") == 0) {
      // Successful geocode
      $geocode_pending = false;
      $coordinates = $xml->Response->Placemark->Point->coordinates;
      $coordinatesSplit = explode(",", $coordinates);
      // Format: Longitude, Latitude, Altitude
      $lat = $coordinatesSplit[1];
      $lng = $coordinatesSplit[0];
	  $values = array('lat'=>$lat,'lng'=>$lng);
 	  return $values;

    } else if (strcmp($status, "620") == 0) {
      // sent geocodes too fast
      $delay += 100000;
    } 
	else 
	{
      // failure to geocode
	  $geocode_pending = false;
	 	$values = array('lat'=>"",'lng'=>"");
  		return $values;
    }
    usleep($delay);
  }
}
function clean($str) 
{
	$str = trim($str);
	if(get_magic_quotes_gpc()) 
	{
		$str = stripslashes($str);
	}
	return mysql_real_escape_string($str);
}
function adminstatus($value)
{
	if($value !="1")
	{
		$_SESSION["loginresult"]="Your Account is currently Blocked";
		header("location:index.php");
		exit;
	}
}
function adminReject($value)
{
	if($value !='1' && $value !='2' && $value !='3' && $value !='4' && $value !='5' && $value !='6' && $value !='7' && $value !='8')
	{
		$_SESSION["loginresult"]="Not Enough Priviledge";
		header("location:index.php");
		exit;
	}
}
function adminPrev($str)
{
	if($str =="1" || $str=="4")
		return true;
	return false;
}
function adminMain($str)
{
	if($str !="4")
	{
		unset($_SESSION["salesuser"]);
		$_SESSION["loginresult"]="ACCESS DENIED: The Site is Under Maintenance";
		header("location:index.php");
		exit;
	}
}
function adminlogin()
{
	if(!isset($_SESSION["salesuser"]))
	{
		//$_SESSION["loginresult"]="Illegal Access";
		unset($_SESSION["salesuser"]);
		$_SESSION["loginresult"]="Please Login To Continue";
		header("location:index.php");
		exit;
	}
	else
	{
		$user=$_SESSION["salesuser"];
		//adminMain($user["type"]);
		$query = "select * from task_users where id='".$user["id"]."'";
		if($result = mysql_query($query))
		{
			if(($num_rows =mysql_num_rows($result))>0)
			{
				$checkuser = mysql_fetch_assoc($result);
				if($checkuser["status"] !="1")
				{
					$_SESSION["loginresult"]="Your Account is blocked or Cancelled";
					unset($_SESSION["salesuser"]);
					header("location:index.php");
					exit;
				}
			}
			else
			{
				$_SESSION["loginresult"]="ERROR: Invalid Entry";
				unset($_SESSION["salesuser"]);
				header("location:index.php");
				exit;
			}
		}
		else
		{
			$_SESSION["loginresult"]="ERROR: Invalid Entry";
			unset($_SESSION["salesuser"]);
			header("location:index.php");
			exit;
		}
	}
}
function convertUS($number)
{
	return number_format($number, 2, '.', ',');
}
function fixdate($str)
{
	if(!empty($str))
	{
		$exp = explode("-",$str);
		if(sizeof($exp)>2)
		{
			$y = $exp[0];
			$m = $exp[1];
			$d = $exp[2];
			if($m<10)
				$m = "0".$m;
			if($d<10)
				$d = "0".$d;
			$newdate = $y."-".$m."-".$d;
			return $newdate;
		}
	}
	return "";
}
function fixdate_slash($str)
{
	if(!empty($str))
	{
		$exp = explode("/",$str);
		if(sizeof($exp)>2)
		{
			$y = $exp[2];
			$m = $exp[1];
			$d = $exp[0];
			$newdate = $y."-".$m."-".$d;
			return $newdate;
		}
	}
	return "";
}
function fixdateb($str)
{
	if(!empty($str))
	{
		$exp = explode(" ",$str);
		if(sizeof($exp)>1)
		{
			return $exp[0];
		}
	}
	return "";
}
function fixdate_comps_switch($value,$fampm)
{
	date_default_timezone_set('UTC');
	$newtime ="";
	$double = false;
	$valuex = explode(" ",$value);
	if(sizeof($valuex)>1)
		$double=true;
	$fampm = strtolower($fampm);
	if(!empty($value))
	{
		$nhour = "";
		$xxtime = explode(":",$value);
		if($fampm=='pm')
		{
		    if($xxtime[0] != 12)
				$nhour =$xxtime[0]+12;
			else
				$nhour =$xxtime[0];
		}
		else
		{
			if($xxtime[0]==12)
				$nhour ='00';
			else
				$nhour=$xxtime[0];
		}
		$newtime = $nhour.":".$xxtime[1].":".$xxtime[0];
	}
	return $newtime;
}
function fixdate_comps($task,$value)
{
	date_default_timezone_set('UTC');
	$newtime ="";
	$value = strtotime($value);
	$double = false;
	$valuex = explode(" ",$value);
	if(sizeof($valuex)>1)
		$double=true;
	if(!empty($value) && !empty($task))
	{
		if($task=="h")
			$newtime =date("g:i a",$value);
		if($task=="ho")
			$newtime =date("h",$value);
		else if($task=="d")
			$newtime = date( "F j, Y",$value);
		else if($task=="mildate")
			$newtime = date( "Y-m-d",$value);
		else if($task=="weekday")
			$newtime = date('l',$value);
		else if($task=="mildatecomp")
			$newtime = date( "Y-m-d H:m:s",$value);
		else if($task=="m_text")
			$newtime = date( "F",$value);
		else if($task=="invdate_s")
			$newtime = date( "m/d/Y",$value);
		else if($task=="invdate")
		{
			if($double)
				$newtime = date( "d/m/Y g:i:a",$value);
			else
				$newtime = date( "d/m/Y",$value);
		}
		else if($task=="all")
			$newtime = date( "F j, Y  g:i a",$value);
		else
			$newtime="";
	}
	else
		$newtime="";
	return $newtime;
}
function fixdate_comp($value)
{
	$date="";
	$ampm="am";
	if(!empty($value))
	{
		$exp = explode(" ",$value);
		if(sizeof($exp)>1)
		{
			$date = $exp[0];
			$exptime = explode(":",$exp[1]);
			if($exptime[0]>11)
			{
				if($exptime[0]!="12")
					$h = $exptime[0] - 12;
				else
					$h=$exptime[0];
				$ampm = "pm";
			}
			else
				$h = $exptime[0];
			$date .= " ".$h.":".$exptime[1]."".$ampm;
			return $date;
		}
		else
			$date ="";
	}
	else
		$date="";
	return $date;
}
function getStatus($value)
{
	$query = "select * from task_users_status where id='$value'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$row = mysql_fetch_assoc($result);
			return stripslashes($row["name"]);
		}
		else
			return "N/A";
	}
	else
		return "N/A";
}
function getCoords($value)
{
	$coordss = $value;
	if(!empty($coordss))
	{
		$coorda=explode(",",$coordss);
		$cod = array("lat"=>$coorda[1],"lng"=>$coorda[0]);
	}
	else
		$cod=array("lat"=>"","lng"=>"");
	return $cod;
}
function getUserType($id)
{
	$namea="";
	$query = "select * from task_admin_type where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$rows = mysql_fetch_assoc($result);
			$namea= stripslashes($rows["name"]);
		}
		else
			$namea="N/A";
	}
	else
		$namea = "N/A";
	return $namea;
}
function getUserStatus($id)
{
	$namea="";
	$query = "select * from task_users_status where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$rows = mysql_fetch_assoc($result);
			$namea= stripslashes($rows["name"]);
		}
		else
			$namea="N/A";
	}
	else
		$namea = "N/A";
	return $namea;
}
function findOfficeByName($str)
{
	$ass_office="";
	if(!empty($str))
	{
		$qx="select id from rec_office where name like '%".$str."%' limit 1";
		if($rx=mysql_query($qx))
		{
			if(($nof=mysql_num_rows($rx))>0)
			{
				$inf=mysql_fetch_assoc($rx);
				$ass_office=$inf["id"];
			}
		}
	}
	return $ass_office;
}
function getOfficeName($id)
{
	$namea="";
	$query = "select * from rec_office where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$rows = mysql_fetch_assoc($result);
			$namea= stripslashes($rows["name"]);
		}
		else
			$namea="N/A";
	}
	else
		$namea = "N/A";
	return $namea;
}
function adAgent($acode,$name)
{
	$acode=strtoupper($acode);
	while(substr_count($name,"  ") != 0){
        $name= str_replace("  "," ",$name);
    }
	$found=false;
	$id="";
	if(!empty($acode))
	{
		$qx="select * from sales_agent where acode='".clean($acode)."'";
		if($rx=mysql_query($qx))
		{
			if(($numrox=mysql_num_rows($rx))>0)
			{
				$info=mysql_fetch_assoc($rx);
				$id=$info["id"];
				$found=true;
			}
		}
		if(!$found && !empty($name))
		{
			$qx="insert ignore into sales_agent(name,acode,date)values('".clean($name)."','".clean($acode)."',NOW())";
			if($rx=mysql_query($qx))
				$id=mysql_insert_id();
		}
	}
	return $id;
}
function getAgent($id)
{
	$namea="";
	$query = "select * from sales_agent where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$rows = mysql_fetch_assoc($result);
			$namea= stripslashes($rows["name"]);
		}
		else
			$namea="N/A";
	}
	else
		$namea = "N/A";
	return $namea;
}
function getAgentInfo($task,$id)
{
	$task=trim($task);
	if(!empty($task))
	{
		if(!empty($id))
		{
			$query = "select * from sales_agent where id='".$id."'";
			if($result = mysql_query($query))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					$rows = mysql_fetch_assoc($result);
					if($task=="name")
						return stripslashes($rows["name"]);
					else if($task=="agentcode")
						return trim($rows["acode"]);
					else if($task=="date")
						return $rows["date"];
					else
						return "";
				}
				else
					return "";
			}
			else
				return "";
		}
		else
			return "";
	}
	else
		return "";
}
function getName($id)
{
	$namea="";
	$query="select * from task_users where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$rows=mysql_fetch_assoc($result);
			$namea=stripslashes($rows["name"]);
		}
		else
			$namea="N/A";
	}
	else
		$namea="N/A";
	return $namea;
}
function getCarInfo($task,$id)
{
	$namea="";
	$query = "select * from fencar where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$rows = mysql_fetch_assoc($result);
			if($task=="license")
				$namea= stripslashes($rows["license"]);
			else if($task=="state")
				$namea= stripslashes($rows["state"]);
			else if($task=="regdate")
				$namea= stripslashes($rows["regdate"]);
			else if($task=="regexp")
				$namea= stripslashes($rows["regexp"]);
			else if($task=="insname")
				$namea= stripslashes($rows["insname"]);
			else if($task=="insdate")
				$namea= stripslashes($rows["insdate"]);
			else if($task=="insexp")
				$namea= stripslashes($rows["insexp"]);
			else if($task=="inspdate")
				$namea= stripslashes($rows["inspdate"]);
			else if($task=="description")
				$namea= stripslashes($rows["description"]);
			else if($task=="status")
			{
				$namea= stripslashes($rows["status"]);
				if(!empty($namea))
					$namea=getCarStatus($rows["status"]);
			}
			else if($task=="date")
				$namea= stripslashes($rows["date"]);
		}
		else
			$namea="N/A";
	}
	else
		$namea = "N/A";
	return $namea;
}
function getUserName($id)
{
	$namea="";
	$query = "select * from task_users where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$rows = mysql_fetch_assoc($result);
			$namea= stripslashes($rows["username"]);
		}
		else
			$namea="N/A";
	}
	else
		$namea = "N/A";
	return $namea;
}
function rLongText($str)
{
	if(!empty($str))
	{
		if(strlen($str)>30)
		{
			$str = substr($str,0,30);
			$str = $str."....";
		}
		else
			return $str;
	}
	return $str;
}
function rLongTextb($str)
{
	if(!empty($str))
	{
		if(strlen($str)>30)
		{
			$str = substr($str,0,50);
			$str = $str."....";
		}
		else
			return $str;
	}
	return $str;
}
function rLongTextc($str)
{
	if(!empty($str))
	{
		if(strlen($str)>6)
		{
			$str = substr($str,0,6);
			$str = $str."...";
		}
		else
			return $str;
	}
	return $str;
}
function rLongTextd($str)
{
	if(!empty($str))
	{
		if(strlen($str)>17)
		{
			$str = substr($str,0,17);
			$str = $str."...";
		}
		else
			return $str;
	}
	return $str;
}
function getHost()
{
	return "http://www.familyenergymarketing.com/salesreport/";
}
function sendEmail($email_to,$title,$messages)
{
	//$host = "http://www.familyenergymarketing.com/portal";
	$host=getHost();
	if(empty($email_to))
		return false;
	$message="<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td align='center'><table width='800' border='0' cellspacing='0' cellpadding='0'><tr><td><img src='".$host."images/email1.jpg' width='800' height='210' alt='email_t' style='display:block;'/></td></tr><tr><td background='".$host."images/email2.jpg'><table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td width='7%'>&nbsp;</td><td width='84%' align='left' valign='top'>";
	$message .=$messages;
	$message .="</td><td width='9%'>&nbsp;</td></tr></table><br/></td></tr><tr><td><img src='".$host."images/email3.jpg' width='800' height='143' style='display:block;'/></td></tr></table></td></tr></table>";
	$headers = 'MIME-Version: 1.0'."\r\n";
	$headers .='Content-type: text/html; charset=iso-8859-1'."\r\n";
	$headers .='Content-Transfer-Encoding: base64'."\r\n";
	$headers .="From: FamilyEnergy Sales Report System<no-reply@yourfamilyenergy.com>\r\n"."X-Mailer: PHP/".phpversion();
	$base64contents=rtrim(chunk_split(base64_encode($message)));
	if($result=mail($email_to,$title, $base64contents,$headers))
		return true;
	else
		return false;
}
function sendEmail_simple($email_to,$title,$messages)
{
	//$host = "http://www.familyenergymarketing.com/portal";
	$host=getHost();
	if(empty($email_to))
		return false;
	//$message="<div style='text-align:center'>";
	$message =$messages;
	//$message .="</div>";
	$headers = 'MIME-Version: 1.0'."\r\n";
	$headers .='Content-type: text/html; charset=iso-8859-1'."\r\n";
	$headers .='Content-Transfer-Encoding: base64'."\r\n";
	$headers .="From: FamilyEnergy Sales Report System<no-reply@yourfamilyenergy.com>\r\n"."X-Mailer: PHP/".phpversion();
	$base64contents=rtrim(chunk_split(base64_encode($message)));
	if($result=mail($email_to,$title, $base64contents,$headers))
		return true;
	else
		return false;
}
?>
<?Php
session_start();
include "include/config.php";
include "include/function.php";
date_default_timezone_set('America/New_York');
adminlogin();
redirect();
$year_total=getYearTotal();
$xoffice=$_REQUEST["xofficex"];
$xyear1=$_REQUEST["xyear1"];
$xyear2=$_REQUEST["xyear2"];
$thisyear=date("Y");
$nyear=date("Y", mktime(0, 0, 0, date("m"),date("d"),date("Y")+1));
if(!empty($xyear1) && !empty($xyear2))
{
	$yeartotal=$xyear2-$xyear1;
	if($yeartotal==1)
	{
		$thisyearx=$xyear1;
		$nyearx=date("Y", mktime(0, 0, 0, date("m"),date("d"),$xyear2));
	}
	else
	{
		$thisyearx=$thisyear;
		$nyearx=$nyear;
		$_SESSION["salesresult"]="Invalid Year Entry";
	}
}
else
{
	$thisyearx=$thisyear;
	$nyearx=$nyear;
}
$year_start=$thisyear-50;
$user = $_SESSION["salesuser"];
$grandtotal_perweek=0;
$grandtotal_month=0;
//get the right from april of this year til the march of next year
$getdays=array();
$xset=3;
$runori=getRunOri($_REQUEST["xofficex"]);
//get all the months and years
if(!$runori)
{
	$year_total="";
	$xoffx=base64_decode($_REQUEST["xofficex"]);
	$xqoff="select * from sales_an_office where office='".$xoffx."'";
	if($xre=mysql_query($xqoff))
	{
		if(($xnumoff=mysql_num_rows($xre))>0)
		{
			$xoff_info=mysql_fetch_assoc($xre);
			for($i=0;$i<12;$i++)
			{
				$xset++;
				$month=date("F", mktime(0, 0, 0, $xset,1,$thisyearx));
				$xtotal=0;
				$xmdate=mktime(0, 0, 0, $xset,1,$thisyearx);
				$xtotal=$xoff_info[$month];
				$year_total +=$xtotal;
				if(empty($xtotal))
					$xtotal=0;
				$getdate[]=array('month'=>date("F", $xmdate),'total'=>$xtotal,'rdate'=>date("Y-m-d", $xmdate),'xdate'=>date("M Y", $xmdate));
			}
		}
	}
}
else
{
	for($i=0;$i<12;$i++)
	{
		$xset++;
		$month=date("F", mktime(0, 0, 0, $xset,1,$thisyearx));
		$xtotal=0;
		$xmdate=mktime(0, 0, 0, $xset,1,$thisyearx);
		$query="select total from sales_an_sales where name='".clean($month)."'";
		if($rx=mysql_query($query))
		{
			if(($numx=mysql_num_rows($rx))>0)
			{
				$xinfo=mysql_fetch_assoc($rx);
				$xtotal=$xinfo["total"];
			}
		}
		$getdate[]=array('month'=>date("F", $xmdate),'total'=>$xtotal,'rdate'=>date("Y-m-d", $xmdate),'xdate'=>date("M Y", $xmdate));
	}
}
$cho_monthx=base64_decode($_REQUEST["cho_monthx"]);
if(empty($cho_monthx) || $cho_monthx <0)
{
	for($i=0; $i<sizeof($getdate);$i++)
	{
		$xmonth = date('F');
		//if($i==0)
		if($getdate[$i]["month"]==$xmonth)
		{
			$cho_monthx=$i;
			break;
		}
	}
}
//get all the week dates
$nweek[]="First Week";
$nweek[]="Second Week";
$nweek[]="Third Week";
$nweek[]="Fourth Week";
$nweek[]="Fifth Week";
$nweek[]="Sixth Week";
$gtotal=array();
$weeksales=array();
for($i=0;$i<sizeof($getdate);$i++)
{
	//start every month
	$month="";
	$year="";
	if(!empty($getdate[$i]["rdate"])) //if the date is not empty
	{
		//split the date and get the month and year to create the weeks
		$sdate=explode("-",$getdate[$i]["rdate"]);
		$year=$sdate[0];
		$month=$sdate[1];
		$xdates=array();
		//this function returns all thursdays of every month
		$monday=getMonday($year, $month);
		//begin loop to create all the weeks
		for($x=0;$x<sizeof($monday);$x++)
			$xdates[]=array('week'=>$nweek[$x],"date1"=>$monday[$x]);
		//fills the missing date with 0 to avoid bar graph error
		//must have a total of 12 numbers
		$weeksales[]=array('month'=>$getdate[$i]["month"],'weeksales'=>$xdates);
	}
}
//$monday=getMonday('2012', 'April');
//var_dump($weeksales);
//var_dump($gtotal);
/*for($i=0;$i<sizeof($weeksales);$i++)
{
	$month=$weeksales[$i]["month"];
	echo $month."-----<br/>";
	$weeks=$weeksales[$i]["weeksales"];
	for($x=0;$x<sizeof($weeks);$x++)
	{
		echo $weeks[$x]["week"]." ".$weeks[$x]["date1"]."<br/>";
	}
	echo "<br/><br/>";
}*/
//end of date gatherting
//begin getting all the totals per weeks with the dates gathered from above
$week1_total="";
$week2_total="";
$week3_total="";
$week4_total="";
$week5_total="";
$week6_total="";
$num1x=0;
$num2x=0;
$num3x=0;
$num4x=0;
$num5x=0;
$num6x=0;
/*for($i=0;$i<sizeof($weeksales);$i++)
{
	//echo "----<Br/>";
	$xsales=$weeksales[$i]["weeksales"];
	$lastindex=0;
	for($x=0;$x<sizeof($xsales);$x++)
	{
		$lastindex=$x;
		$xname=$xsales[$x]["week"];
		$xset1=$xsales[$x]["date1"];
		if($runori)
			$xtotal=getGraPdayAmount_real($xset1);
		else
			$xtotal=getGraPdayAmount_real_office($xset1,base64_decode($_REQUEST["xofficex"]));
		if($xname=="First Week")
		{
			if($num1x==0)
				$week1_total =$xtotal;
			else
				$week1_total .=",".$xtotal;
			$num1x++;
		}
		else if($xname=="Second Week")
		{
			if($num2x==0)
				$week2_total =$xtotal;
			else
				$week2_total .=",".$xtotal;
			$num2x++;
		}
		else if($xname=="Third Week")
		{
			if($num3x==0)
				$week3_total =$xtotal;
			else
				$week3_total .=",".$xtotal;
			$num3x++;
		}
		else if($xname=="Fourth Week")
		{
			if($num4x==0)
				$week4_total =$xtotal;
			else
				$week4_total .=",".$xtotal;
			$num4x++;
		}
		else if($xname=="Fifth Week")
		{
			if($num5x==0)
				$week5_total =$xtotal;
			else
				$week5_total .=",".$xtotal;
			$num5x++;
		}
		else if($xname=="Sixth Week")
		{
			if($num6x==0)
				$week6_total =$xtotal;
			else
				$week6_total .=",".$xtotal;
			$num6x++;
		}
	}
	//fill the missing weeks to 0 to match the numbers
	//some months only have 5 weeks, some have 3 and some have 6 so it need to fills those gaps with 0 to give a proper data
	if(sizeof($xsales)<6)
	{
		$xxy=sizeof($xsales);
		//echo $xxy."<br/>";
		$s5x=0;
		$s6x=0;
		for($y=sizeof($xsales);$y<sizeof($nweek);$y++)
		{
			if($xxy==5 && $s6x <2)
			{
				if($num6x==0)
					$week6_total ="0";
				else
					$week6_total .=",0";
				$num6x++;
				$s6x++;
			}
			else if($xxy=="4" && $s5x <1)
			{
				if($s5x <1)
				{
					if($num5x==0)
						$week5_total ="0";
					else
						$week5_total .=",0";
					$num5x++;
					$s5x++;
				}
				if($s6x <1)
				{
					if($num6x==0)
						$week6_total ="0";
					else
						$week6_total .=",0";
					$num6x++;
					$s6x++;
				}
				
			}
		}
	}
}*/
for($i=0;$i<sizeof($weeksales);$i++)
{
	//echo "----<Br/>";
	$xsales=$weeksales[$i]["weeksales"];
	$lastindex=0;
	$swap=false;
	for($x=0;$x<7;$x++)
	{
		$lastindex=$x;
		$xname=$xsales[$x]["week"];
		$xset1=$xsales[$x]["date1"];
		//echo "<span style='color:#000'>".$xname." ".$xset1."<span><br/>";
		if($runori)
			$xtotal=getGraPdayAmount_real($xset1);
		else
			$xtotal=getGraPdayAmount_real_office($xset1,base64_decode($_REQUEST["xofficex"]));
		if(empty($xtotal) || $xtotal<1)
			$xtotal=0;
		if($x==0)
		{
			if($xtotal>0)
			{
				if($num1x==0)
				{
					$week1_total=$xtotal;
					$num1x++;
				}
				else
				{
					$week1_total .=",".$xtotal;
					$num1x++;
				}
			}
			else
				$swap=true;
		}
		else if($x==1)
		{
			if($swap)
			{
				if($num1x==0)
				{
					$week1_total .=$xtotal;
					$num1x++;
				}
				else
				{
					$week1_total .=",".$xtotal;
					$num1x++;
				}
			}
			else
			{
				
				if($num2x==0)
				{
					$week2_total=$xtotal;
					$num2x++;
				}
				else
				{
					$week2_total .=",".$xtotal;
					$num2x++;
				}
			}
		}
		else if($x==2)
		{
			if($swap)
			{
				if($num2x==0)
				{
					$week2_total .=$xtotal;
					$num2x++;
				}
				else
				{
					$week2_total .=",".$xtotal;
					$num2x++;
				}
			}
			else
			{
				
				if($num3x==0)
				{
					$week3_total=$xtotal;
					$num3x++;
				}
				else
				{
					$week3_total .=",".$xtotal;
					$num3x++;
				}
			}
		}
		else if($x==3)
		{
			if($swap)
			{
				if($num3x==0)
				{
					$week3_total .=$xtotal;
					$num3x++;
				}
				else
				{
					$week3_total .=",".$xtotal;
					$num3x++;
				}
			}
			else
			{
				
				if($num4x==0)
				{
					$week4_total=$xtotal;
					$num4x++;
				}
				else
				{
					$week4_total .=",".$xtotal;
					$num4x++;
				}
			}
		}
		else if($x==4)
		{
			if($swap)
			{
				if($num4x==0)
				{
					$week4_total .=$xtotal;
					$num4x++;
				}
				else
				{
					$week4_total .=",".$xtotal;
					$num4x++;
				}
			}
			else
			{
				
				if($num5x==0)
				{
					$week5_total =$xtotal;
					$num5x++;
				}
				else
				{
					$week5_total .=",".$xtotal;
					$num5x++;
				}
			}
		}
		else if($x==5)
		{
			if($swap)
			{
				if($num5x==0)
				{
					$week5_total .= $xtotal;
					$num5x++;
				}
				else
				{
					$week5_total .=",".$xtotal;
					$num5x++;
				}
			}
			else
			{
				if($num6x<12)
				{
					if($num6x==0)
					{
						$week6_total .=$xtotal;
						$num6x++;
					}
					else
					{
						$week6_total .=",".$xtotal;
						$num6x++;
					}
				}
			}
		}
		else if($x==6)
		{
			if($num6x<12)
			{
				if($num6x==0)
				{
					$week6_total .= $xtotal;
					$num6x++;
				}
				else
				{
					$week6_total .=",".$xtotal;
					$num6x++;
				}
			}
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Annual Performance Print View <?php if(!$runori){ echo "For ".getOfficeName(base64_decode($_REQUEST["xofficex"])); }?></title>
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<!--highcharts graph script--->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<!--<script src="http://code.highcharts.com/modules/exporting.js"></script>-->
<script src="highcharts/js/highcharts.js" type="text/javascript"></script>
<!--end highcharts graph script--->
<script type="text/javascript" language="javascript">
var timer=null;
function graphswitch_month_real(value)
{
	var iyear="";
	var xyear1s=document.getElementById("xyear1s").value;
	var xyear2s=document.getElementById("xyear2s").value;
	//alert(xyear1s+" "+xyear2s);
	if(xyear1s.length>0 && xyear2s.length>0)
		iyear="&xyear1="+xyear1s+"&xyear2="+xyear2s;
	window.location.href="viewgraph_r.php?cho_monthx="+value+""+iyear+"#weekcont";
}
$(function () {
	//bar chart
    var chart;
    $(document).ready(function() {
		//start of bar chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'column'
            },
			credits:{enabled:false},
            title: {
               <?Php
				$char_title="";
				if(!$runori)
					$char_title="For ".getOfficeName(base64_decode($xoffice));
				?>
                text: 'Projected Annual Sales <?php echo $char_title; ?>'
            },
            xAxis: {
                categories: [
					<?Php
					if(sizeof($getdate)>0)
					{
						for($i=0;$i<sizeof($getdate);$i++)
						{
							if($i==0)
								echo "'".$getdate[$i]['xdate']."'";
							else
								echo ",'".$getdate[$i]['xdate']."'";
						}
					}
					?>
				]
            },
            yAxis: {
                allowDecimals: false,
                min: 0,
                title: {
                    text: 'Number Of Sales'
                }
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.x +'</b><br/>'+
                        this.series.name +': '+ this.y +'<br/>'+
                        'Total: '+ this.point.stackTotal;
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal'
					,animation:false
                }
            },
            series: [
			{
                name: 'Goal',
                data: [
				<?php
				for($i=0;$i<sizeof($getdate);$i++)
				{
					if($i==0)
						echo $getdate[$i]["total"];
					else
						echo ",".$getdate[$i]["total"];
				}
				?>
				],
                stack: 'male'
            }
			<?Php
			for($i=0;$i<sizeof($nweek);$i++)
			{
				$xname=$nweek[$i];
				echo ",{ name:'".$xname."',\n";
              	 echo "data: [";
				if($xname=="First Week")
					echo $week1_total;
				else if($xname=="Second Week")
					echo $week2_total;
				else if($xname=="Third Week")
					echo $week3_total;
				else if($xname=="Fourth Week")
					echo $week4_total;
				else if($xname=="Fifth Week")
					echo $week5_total;
				else if($xname=="Sixth Week")
					echo $week6_total;
				echo "],\n";
               	echo "stack: 'female'}\n";
			}
			?>
			],
			
        });//end of bar chart
		//call pie chart
		timer= setInterval(bargoal,10);
		//timer= setInterval(pieChart,1000);
    });
});
function bargoal(){
	clearInterval(timer);
	var bargoal;
	bargoal= new Highcharts.Chart({
            chart: {
                renderTo: 'bargoal',
                type: 'bar',
            },
			credits:{enabled:false},
            title: {
				<?php
				//$year_total;
				$bargoal_title="";
				if(!$runori)
					$bargoal_title=" For ".getOfficeName(base64_decode($_REQUEST["xofficex"]));
				?>
                text: 'Goal Meter<?php echo $bargoal_title; ?>'
            },
            xAxis: {
                categories: ['Sales Achieved']
            },
            yAxis: {
                min: 0,
				max:<?php echo $year_total; ?>,
                title: {
                    text: 'Goal To Reach <?php echo $year_total; ?> Sales<?Php echo $bargoal_title; ?>'
                }
            },
            legend: {
                backgroundColor: '#FFFFFF',
                reversed: true
            },
            tooltip: {
                formatter: function() {
                    return ''+
                        this.series.name +': '+ this.y +'<br/>'+
                        'Total: '+ this.point.stackTotal;;
                }
            },
            plotOptions: {
                series: {
                    stacking: 'normal',
					animation:false
                }
            },
                series: [
			<?Php
			$barq_office="";
			if(!$runori)
				$barq_office=" where id='".base64_decode($_REQUEST["xofficex"])."' ";
			$barq="select * from rec_office $barq_office order by name";
			if($rq=mysql_query($barq))
			{
				if(($numq=mysql_num_rows($rq))>0)
				{
					$cq=0;
					while($rqx=mysql_fetch_array($rq))
					{
						if($cq==0)
							$ccomma="";
						else
							$ccomma=",";
						$baroff_total=getBarTotalByOffice($rqx["id"],$thisyearx,$nyearx);
						echo $ccomma."{\n
               			 name: '".stripslashes($rqx["name"])."',\n
                		 data: [".$baroff_total."]\n
           				 }\n";
						$cq++;
					}
				}
			}
			?>
			
			]
        });
	timer= setInterval(pieChart,10);
	//end of stacked bar for yerly goal
}
function printthis()
{
	window.print()
	window.onfocus = function() { window.close(); }
}
function printer()
{
  var t=setTimeout("printthis()",40);
}
</script>
<style>
.graphheader{
	font-family:'rockw';font-size:13pt;color:#00477f;
}
.graphrow{
	font-family:'rockw';font-fize:17pt;
}
.graphrow_date{
	color:#8a8888;font-size:11pt; font-family:'rockw';
}
.graphrow_missing{
	color:#F00;font-family:'rockw';font-fize:17pt;
}
.graphrow_done{
	color:#0C0;font-family:'rockw';font-fize:17pt;
}
</style>
<link rel="icon" type="image/png" href="images/favicon.ico">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="css/styleie.css" />
<![endif]-->
<link rel="stylesheet" type="text/css" href="css/styleprint.css" />
</head>
<body onload="printer()">
<div id="main_cont">
    <div id="lbodycont">
        <div id="lbodycont_middle">
            <div id="lbodycont_middle_in">
				<div id="container" style="height: 300px; text-align:center;"></div>
                <br/>
                <div id="bargoal" style="min-width: 400px; height: 180px; margin: 0 auto; text-align:center"></div>
                <br/>
                <div id="weekcont">
                <!--<div style="text-align:center; font-family:'rockw'; font-size:16pt; color:#8a8888">
					Sales Breakdown For <?php //echo $getdate[$cho_monthx]["month"]; ?>
                </div>-->
                <form action="" method="post">
                <input type="hidden" id="cho_monthx" name="cho_monthx" value="<?Php echo $_REQUEST["cho_monthx"]?>" />
                <input type="hidden" id="xofficex" name="xofficex" value="<?Php echo $_REQUEST["xofficex"]; ?>" />
                <?php
				$pieoffice_array=array();
				//$cho_monthx=0;
				$oxdate=$getdate[$cho_monthx]["rdate"];
				$oxmonth=$getdate[$cho_monthx]["month"];
				//echo $getdate[$cho_monthx]["rdate"];
				$oxyearx=explode("-",$oxdate);
				$oxyear=$oxyearx[0];
				$oxtitle_date=" ".$oxmonth.",".$oxyear;
				$oxfirstday=getFirstDay($oxdate);
				$oxlastday=getLastDay($oxdate);
				$oxgrandtotal=getgraphweektotal_off_noffice_real($oxfirstday,$oxlastday);
				$m_total=checkRightPer($oxfirstday,$oxlastday,$oxgrandtotal);
				if($oxgrandtotal>0)
				$xstyle="style='min-width: 250px; height: 250px; margin: 0 auto;'";
				else
				$xstyle="style='min-width: 250px; height: 250px; margin: 0 auto; display:none;'";
				?>
                	<script type="text/javascript" language="javascript">
					function pieChart()
					{
						clearInterval(timer);
						//start of pie chart
						var chart_pie;
						 chart_pie = new Highcharts.Chart({
						chart: {
							renderTo: 'container_pie',
							plotBackgroundColor: null,
							plotBorderWidth: null,
							plotShadow: false
						},
						credits:{enabled:false},
						title: {
							text: 'Family Energy Office Performance Pie Chart'
						},
						tooltip: {
							formatter: function() {
								return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage,2) +' %';
							}
						},
						plotOptions: {
							pie: {
								allowPointSelect: true,
								cursor: 'pointer',
								dataLabels: {
									enabled: true,
									color: '#000000',
									connectorColor: '#000000',
									formatter: function() {
										return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage,2) +' %';
									}
								},
								animation:false
							}
						},
						series: [{
							type: 'pie',
							name: 'Office Performance',
							data: [
							<?php
							$pie_q="select * from rec_office order by name";
							$checkpie=0;
							if($pie_re=mysql_query($pie_q))
							{
								if(($num_pie=mysql_num_rows($pie_re))>0)
								{
									$pieco=0;
									while($pie_row=mysql_fetch_array($pie_re))
									{
									   $oxgrandtotal_office=getgraphweektotal_off_real($oxfirstday,$oxlastday,$pie_row["id"]);
									   $oxtotal_per=@($oxgrandtotal_office/$oxgrandtotal)*100;
									   $oxtotal_per=number_format($oxtotal_per, 2, '.', '');
										if($pieco==0)
										{
											$oxtotal_per +=$m_total;
											echo "{\n
												name: '".stripslashes($pie_row["name"])."',\n
												y: ". $oxtotal_per.",\n
												sliced: true,\n
												selected: true\n
											}\n";
										}
										else
										{
											echo ",\n
											['".stripslashes($pie_row["name"])."',   ".$oxtotal_per."]\n
											";
										}
										$pieco++;
									}
								}
							}
							?>
							]
						}]
					});
						//end pie chart
					//	document.getElementById("container_pie").style.display="none";
					}
					</script>
					<div id="container_pie" <?php echo $xstyle; ?>></div>
                	<table width='100%' border='0' cellspacing='0' cellpadding='0'>
                      <tr class='graphheader'>
                        <td width='17%' align='center' valign='bottom'>Office</td>
                        <?php
						$last_day=getLastDay($getdate[$cho_monthx]["rdate"]);
					    $first_day=getFirstDay($getdate[$cho_monthx]["rdate"]);
						$weeks=$weeksales[$cho_monthx]["weeksales"];
						if(lessRows($weeks[0]["date1"],$first_day,$last_day))
						{
							$lessrow=true;
							$istart=1;
							$weekrow_bottom=sizeof($weeks)-1;
						}
						else
						{
							$lessrow=false;
							$istart=0;
							$weekrow_bottom=sizeof($weeks);
						}
						if($lessrow)
						{
							$weekrow=(sizeof($weeks)-1)+2;
							for($i=1;$i<sizeof($weeks);$i++)
							{
								$cx=$i;
								echo "<td width='12%' align='center' valign='bottom'>Week ".$cx."<br/><span class='graphrow_date'>".$weeks[$i]["date1"]."</span></td>";
							}
						}
						else
						{
							$weekrow=sizeof($weeks)+2;
							for($i=$istart;$i<sizeof($weeks);$i++)
							{
								$cx=$i+1;
								echo "<td width='12%' align='center' valign='bottom'>Week ".$cx."<br/><span class='graphrow_date'>".$weeks[$i]["date1"]."</span></td>";
							}
						}
						?>
                        <td width='10%' align='center' valign='bottom'>Total</td>
                      </tr>
                      <?php
					  if(!$runori)
					  	$qx="select * from rec_office where id='".base64_decode($_REQUEST["xofficex"])."' order by id";
					  else
					    $qx="select * from rec_office order by id";
					  if($rx=mysql_query($qx))
					  {
						 if(($numrows=mysql_num_rows($rx))>0)
						 {
							 while($rowx=mysql_fetch_array($rx))
							 {
								 $ototal=0;
								 echo "<tr><td colspan='".$weekrow."'><hr/></td></tr>";
								 echo "<tr class='graphrow'>";
								 echo "<td align='center' valign='middle'>".stripslashes($rowx["name"])."</td>";
								 $weeks=$weeksales[$cho_monthx]["weeksales"];
								 for($i=$istart;$i<sizeof($weeks);$i++)
								 {
									 $stotal=getgraphweektotal_off_real_one($weeks[$i]["date1"],$rowx["id"]);
									 $stotal_link=$stotal;
									 echo "<td align='center' valign='middle'>".$stotal_link."</td>";
									 $ototal +=$stotal;
								 }
								 $ototal_link=$ototal;
								 echo "<td align='center' valign='middle'>$ototal_link</td>";
								 echo "</tr>";
							 }
						 }
					  }
					  echo "<tr><td colspan='".$weekrow."'><hr/></td></tr>";
					  if($runori)
					  {
					  	$real_month_total=getRealMonthTotal($getdate[$cho_monthx]["month"]);
					  	$ggrandtotal=getgraphweektotal_off_noffice_real($first_day,$last_day);
					  }
					  else
					  {
						  $real_month_total=getRealMonthTotal_noffice($getdate[$cho_monthx]["rdate"],base64_decode($_REQUEST["xofficex"]));
					  	  $ggrandtotal=getgraphweektotal_off_noffice_real_noffice($first_day,$last_day,base64_decode($_REQUEST["xofficex"]));
					  }
					  $retotal=$ggrandtotal-$real_month_total;
					  if($retotal<0)
					  {
						$restvar="Negative (-)";
					  	$restyle="class='graphrow_missing'";
					  }
					  else
					  {
						$restvar="Positive (+)";
					  	$restyle="class='graphrow_done'";
					  }
					  ?>
                       <?Php
					  if($runori)
					  {
						  ?>
                     <tr class='graphrow'>
                        <td align='center' valign='middle' >Internal Team Total</td>
                        <?php
                        $weeks=$weeksales[$cho_monthx]["weeksales"];
						for($i=$istart;$i<sizeof($weeks);$i++)
						{
							if(!$runori)
								$stotal=getgraphweektotal_off_noffice_office_real_one($weeks[$i]["date1"],base64_decode($_REQUEST["xofficex"]));	
							else
								$stotal=getgraphweektotal_off_noffice_real_one($weeks[$i]["date1"]);
							$stotal_link=$stotal;
							echo "<td align='center' valign='middle'>".$stotal_link."</td>";
							$ototal +=$stotal;
						}
						$ggrandtotal_link=$ggrandtotal;
						echo "<td align='center' valign='middle'>$ggrandtotal_link</td>";
						?>
                      </tr>
                      <tr><td colspan='<?Php echo $weekrow; ?>'><hr/></td></tr>
                      <?Php
					  }
					  ?>
                      <tr class='graphrow'>
                      	<td align='center' valign='middle' >Month Goal</td>
						<td align='center' valign='middle' colspan='<?php echo $weekrow_bottom; ?>'>&nbsp;</td>
                        <td align='center' valign='middle'><?php echo $real_month_total; ?></td>
                      </tr>
                      <tr><td colspan='<?Php echo $weekrow; ?>'><hr/></td></tr>
					   <tr <?php echo $restyle; ?>>
                      	<td align='center' valign='middle' ><?php echo $restvar; ?></td>
						<td align='center' valign='middle' colspan='<?php echo $weekrow_bottom; ?>'>&nbsp;</td>
                        <td align='center' valign='middle'><?php echo $retotal; ?></td>
                      </tr>
                    </table>
                </form>
                </div>
          </div>
      </div>
    </div>
</div>
</body>
</html>
<?php
include "include/unconfig.php";
?>
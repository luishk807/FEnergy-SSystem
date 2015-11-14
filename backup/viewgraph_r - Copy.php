<?Php
session_start();
include "include/config.php";
include "include/function.php";
date_default_timezone_set('America/New_York');
adminlogin();
redirect();
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
$cho_monthx=base64_decode($_REQUEST["cho_monthx"]);
if(empty($cho_monthx) && $cho_monthx <0 )
	$cho_monthx="";
$grandtotal_perweek=0;
$grandtotal_month=0;
//get the right from april of this year til the march of next year
$getdays=array();
$xset=3;
//get all the months and years
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
for($i=0;$i<sizeof($weeksales);$i++)
{
	//echo "----<Br/>";
	$xsales=$weeksales[$i]["weeksales"];
	$lastindex=0;
	for($x=0;$x<sizeof($xsales);$x++)
	{
		$lastindex=$x;
		$xname=$xsales[$x]["week"];
		$xset1=$xsales[$x]["date1"];
		$xtotal=getGraPdayAmount_real($xset1);
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
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome To Sales Report System</title>
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<!--highcharts graph script--->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<!--<script src="http://code.highcharts.com/modules/exporting.js"></script>-->
<script src="highcharts/js/highcharts.js" type="text/javascript"></script>
<script src="highcharts/js/modules/exporting.js" type="text/javascript"></script>
<!--end highcharts graph script--->
<script type="text/javascript" language="javascript">
var timer=null;
function printthis()
{
	var iyear="";
	var xyear1s=document.getElementById("xyear1s").value;
	var xyear2s=document.getElementById("xyear2s").value;
	var cho_monthx=document.getElementById("cho_monthx").value;
	if(xyear1s.length>0 && xyear2s.length>0)
		iyear="&xyear1="+xyear1s+"&xyear2="+xyear2s;
	var newWin = window.open("printview.php?cho_monthx="+cho_monthx+""+iyear,"Family_Energy_Sales_Report_System_Print_View",'width=880, height=1200,resizable');
	if (newWin && newWin.top) {
		newWin.focus();
	} else {
		alert("Please enable your pop up blocker");
	}
}
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
            title: {
                text: 'Projected Annual Sales'
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
		timer= setInterval(bargoal,1000);
		//statcke bar for yearly goal
    });
});
function bargoal(){
	clearInterval(timer);
	var bargoal;
	bargoal= new Highcharts.Chart({
            chart: {
                renderTo: 'bargoal',
                type: 'bar'
            },
            title: {
                text: 'Goal Meter'
            },
            xAxis: {
                categories: ['Sales Achieved']
            },
            yAxis: {
                min: 0,
				max:61690,
                title: {
                    text: 'Goal To Reach 61,690 Sales'
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
                    stacking: 'normal'
                }
            },
                series: [
			<?Php
			$barq="select * from rec_office order by name";
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
						$baroff_total=getBarTotalByOffice($rqx["id"]);
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
	timer= setInterval(pieChart,1000);
	//end of stacked bar for yerly goal
}
function closemodal()
{
	document.getElementById("contmodal").style.display="none";
}
function showgraphinfo_real(value)
{
	showgraphinfopop_real(value);
	document.getElementById("contmodal").style.display="block";
}
$().ready(function() {
	var $scrollingDiv = $("#modalform");
	$(window).scroll(function(){
		$scrollingDiv
			.stop()
			.animate({"marginTop": ($(window).scrollTop() + 5) + "px"}, "slow" );
	});
});
</script>
<style>
.graphheader{
	font-family:'rockw';font-size:14pt;color:#00477f;
}
.graphrow{
	font-family:'rockw';font-fize:18pt;
}
.graphrow_date{
	color:#8a8888;font-size:12pt; font-family:'rockw';
}
.graphrow_missing{
	color:#F00;font-family:'rockw';font-fize:18pt;
}
.graphrow_done{
	color:#0C0;font-family:'rockw';font-fize:18pt;
}
#contmodal{
	display:none;
}
#modalform{
	position:absolute;z-index:10000; width:800px; height:500px; background:#FFF;right: 0;left: 0; margin:0 auto;color:#000; padding:40px;
	top:2%;
}
#modalpop{
	position:fixed; z-index:9000;background-color:#000; top: 0; right: 0; bottom: 0; left: 0;opacity:0.4;
}
</style>
<link rel="icon" type="image/png" href="images/favicon.ico">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="css/styleie.css" />
<![endif]-->
<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>

<body>
<div id="contmodal">
	<div id="modalform">
    </div>
    <div id="modalpop"></div>
</div>
<div id="main_cont">
	<?php
	include "include/header.php";
	?>
    <div id="lbodycont">
    	<div id="lbodycont_header">
        	<div id="lbody_header_in">View Annual Performance</div>
        </div>
        <div id="lbodycont_middle">
            <div id="lbodycont_middle_in">
            	 <?php
				if(pView($user["type"]))
				{
					?>
					<div id="tabmenu">
						<div id="tabmenu_in"><a href='viewreport.php' class='contlinkc' >Back</a>&nbsp;&nbsp;&nbsp;<a href='javascript:printthis()' class='contlinkc' >Print This Page</a>&nbsp;&nbsp;&nbsp;<a href='accountrealtotal_oset.php' class='contlinkc' >Edit Real Total</a>&nbsp;&nbsp;&nbsp;<a href='importexcel.php' class='contlinkc' >Add New File</a></div>
				  </div>
				  <br/>
				<?php
				}
				?>
            	<div id="message" name="message" class="black" style="text-align:center">
                    <?php
                    if(isset($_SESSION["salesresult"]))
                    {
                       echo $_SESSION["salesresult"]."<br/>";
                       unset($_SESSION["salesresult"]);
                    }
                    ?>
                  </div>
                <!--script for the serach engine-->
                <form method="post" action="viewgraph_r.php">
                <input type="hidden" id="xyear1s" name="xyear1s" value="<?Php echo $_REQUEST["xyear1"]?>" />
                <input type="hidden" id="xyear2s" name="xyear2s" value="<?Php echo $_REQUEST["xyear2"]?>" />
                <input type="hidden" id="xofficex" name="xofficex" value="<?Php echo $_REQUEST["xofficex"]?>" />
                	<div style="text-align:center; font-family:'rockw'">
                    	Select Office:&nbsp;&nbsp;
                        <select id="xoffice" name="xoffice" onchange="switchoffice(this.value)">
                        	<option value='all' <?Php if(empty($_REQUEST["xoffice"])) echo "selected='selected'"; ?>>All Offices</option>
                        	<?php
							$qo="select * from rec_office order by name";
							if($rof=mysql_query($qo))
							{
								if(($numo=mysql_num_rows($rof))>0)
								{
									while($rofx=mysql_fetch_array($rof))
									{
										if(base64_decode($_REQUEST["xoffice"])==$rofx["id"])
										echo "<option value='".base64_encode($rofx["id"])."' selected='selected'>".stripslashes($rofx["name"])."</option>";
										else
										echo "<option value='".base64_encode($rofx["id"])."'>".stripslashes($rofx["name"])."</option>";
									}
								}
							}
							?>
                        </select>&nbsp;&nbsp;&nbsp;&nbsp;
                    	Select Years From:&nbsp;&nbsp;
                        <select id="xyear1" name="xyear1">
                        	<?php
							for($ty=$thisyear;$ty>$year_start;$ty--)
								echo "<option value='".$ty."'>".$ty."</option>";
							?>
                        </select>
                        &nbsp;&nbsp;&nbsp;
                         To:
                        &nbsp;&nbsp;&nbsp;
                        <select id="xyear2" name="xyear2">
                        	<?php
							$thisyearx=$thisyear+1;
							for($ty=$thisyearx;$ty>$year_start;$ty--)
								echo "<option value='".$ty."'>".$ty."</option>";
							?>
                        </select>
                        &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Submit" onclick="checkFieldgraphdate_real()"/>
                    </div>
                </form>
                <br/><br/>
                <!--end of script-->
				<div id="container" style="width: 800px; height: 400px"></div>
                <br/><br/>
                <div id="bargoal" style="min-width: 400px; height: 250px; margin: 0 auto"></div>
                <br/><br/>
			  <div style="text-align:center; font-family:'rockw'; font-size:18pt; color:#8a8888">
					Sales Breakdown For 
					<select id="cho_month" name="cho_month" onchange="graphswitch_month_real(this.value)" >
                    	<?Php
						for($i=0; $i<sizeof($getdate);$i++)
						{
							if(!empty($cho_monthx) || $cho_monthx=='0')
							{
								if($i==$cho_monthx)
									echo "<option value='".base64_encode($i)."' selected='selected'>".$getdate[$i]["xdate"]."</option>";
								else
									echo "<option value='".base64_encode($i)."'>".$getdate[$i]["xdate"]."</option>";
							}
							else
							{
								$xmonth = date('F');
								//if($i==0)
								if($getdate[$i]["month"]==$xmonth)
								{
									echo "<option value='".base64_encode($i)."' selected='selected'>".$getdate[$i]["xdate"]."</option>";
									$cho_monthx=$i;
								}
								else
									echo "<option value='".base64_encode($i)."'>".$getdate[$i]["xdate"]."</option>";
							}
						}
						?>
                    </select>
              </div>
              <br/>
                <div id="weekcont">
                <form action="" method="post">
                <input type="hidden" id="cho_monthx" name="cho_monthx" value="<?Php echo $_REQUEST["cho_monthx"]?>" />
                <?php
				$pieoffice_array=array();
				$oxdate=$getdate[$cho_monthx]["rdate"];
				$oxmonth=$getdate[$cho_monthx]["month"];
				$oxyearx=explode("-",$oxdate);
				$oxyear=$oxyearx[0];
				$oxtitle_date=" ".$oxmonth.",".$oxyear;
				$oxfirstday=getFirstDay($oxdate);
				$oxlastday=getLastDay($oxdate);
				$oxgrandtotal=getgraphweektotal_off_noffice_real($oxfirstday,$oxlastday);
				if($oxgrandtotal>0)
				$xstyle="style='min-width: 400px; height: 400px; margin: 0 auto;'";
				else
				$xstyle="style='min-width: 400px; height: 400px; margin: 0 auto; display:none;'";
				?>
                	<script type="text/javascript" language="javascript">
					function pieChart()
					{
						clearInterval(timer);
						//start of pie chart
						var chart_pie;
							   var colors = Highcharts.getOptions().colors,
								categories = [
								<?php
								$qo="select * from rec_office";
								if($ro=mysql_query($qo))
								{
									if(($numo=mysql_num_rows($ro))>0)
									{
										$co=0;
										while($roxx=mysql_fetch_array($ro))
										{
											if($co==0)
												echo "'".stripslashes($roxx["name"])."'";
											else
												echo ",'".stripslashes($roxx["name"])."'";
											$pieoffice_array[]=array('id'=>$roxx["id"],'name'=>$roxx["name"]);
											$co++;
										}
									}
								}
								?>
								],
								name = 'Family Energy Offices',
								data = [
								  <?php
								  for($ox=0;$ox<sizeof($pieoffice_array);$ox++)
								  {
									  $ocomma="";
									  $oxgrandtotal_office=getgraphweektotal_off_real($oxfirstday,$oxlastday,$pieoffice_array[$ox]["id"]);
									  $oxtotal_per=@($oxgrandtotal_office/$oxgrandtotal)*100;
									  $oxtotal_per=number_format($oxtotal_per, 2, '.', '');
									  if($ox>0)
										 $ocomma=",";
									   echo $ocomma;
									   echo "
										 {
											y: ".$oxtotal_per.",
											color: colors[".$ox."],
											drilldown: {
												name: '".$pieoffice_array[$ox]["name"]."',";
									  $oxind_total=getPieIndTotal_realx($oxfirstday,$oxlastday,$pieoffice_array[$ox]["id"], $oxgrandtotal);
									  
									  echo "categories: [".$oxind_total[0]["name"]."],";
									  echo "data: [".$oxind_total[0]["total"]."],";
									  echo "
												color: colors[".$ox."]
											}
										}";
								  }
								  ?>
									];
							// Build the data arrays
					
							var browserData = [];
							var versionsData = [];
							for (var i = 0; i < data.length; i++) {
								// add browser data
								browserData.push({
									name: categories[i],
									y: data[i].y,
									color: data[i].color
								});
					
								// add version data
								for (var j = 0; j < data[i].drilldown.data.length; j++) {
									var brightness = 0.2 - (j / data[i].drilldown.data.length) / 5 ;
									versionsData.push({
										name: data[i].drilldown.categories[j],
										y: data[i].drilldown.data[j],
										color: Highcharts.Color(data[i].color).brighten(brightness).get()
									});
								}
							}
							
							// Create the chart
							chart_pie = new Highcharts.Chart({
								chart: {
									renderTo: 'container_pie',
									type: 'pie'
								},
								title: {
									text: 'Office Performance, <?php echo $oxtitle_date; ?>'
								},
								yAxis: {
									title: {
										text: 'Total percent office sales'
									}
								},
								plotOptions: {
									pie: {
										shadow: true
									}
								},
								tooltip: {
									formatter: function() {
										return '<b>'+ this.point.name +'</b>: '+ this.y +' %';
									}
								},
					
								series: [{
									name: 'Browsers',
									data: browserData,
									size: '60%',
									dataLabels: {
										formatter: function() {
											return this.y > 5 ? this.point.name : null;
										},
										color: 'white',
										distance: -30
									}
								}, {
									name: 'Versions',
									data: versionsData,
									innerSize: '60%',
									dataLabels: {
										formatter: function() {
											// display only if larger than 1
											return this.y > 1 ? '<b>'+ this.point.name +':</b> '+ this.y +'%'  : null;
										}
									}
								}]
							});
							//end pie chart
					//	document.getElementById("container_pie").style.display="none";
					}
					</script>
					<div id="container_pie" <?php echo $xstyle; ?>></div>
                    <br/>
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
									 if($stotal>0)
									{
										$stotal_link_in="date1=".base64_encode($weeks[$i]["date1"])."&office=".base64_encode($rowx["id"]);
										$stotal_link="<a href='javascript:showgraphinfo_real(\"".$stotal_link_in."\")'>$stotal</a>";
									}
									else
										$stotal_link=$stotal;
									 echo "<td align='center' valign='middle'>".$stotal_link."</td>";
									 $ototal +=$stotal;
								 }
								  if($ototal>0)
								  {
									 $ototal_link_in="date1=".base64_encode($first_day)."&date2=".base64_encode($last_day)."&office=".base64_encode($rowx["id"]);
									 $ototal_link="<a href='javascript:showgraphinfo_real(\"".$ototal_link_in."\")'>$ototal</a>";
								  }
							      else
								    $ototal_link=$ototal;
								 echo "<td align='center' valign='middle'>$ototal_link</td>";
								 echo "</tr>";
							 }
						 }
					  }
					  echo "<tr><td colspan='".$weekrow."'><hr/></td></tr>";
					  $real_month_total=getRealMonthTotal($getdate[$cho_monthx]["month"]);
					  $ggrandtotal=getgraphweektotal_off_noffice_real($first_day,$last_day);
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
                     <tr class='graphrow'>
                        <td align='center' valign='middle' >Internal Team Total</td>
                        <?php
                        $weeks=$weeksales[$cho_monthx]["weeksales"];
						for($i=$istart;$i<sizeof($weeks);$i++)
						{
							$stotal=getgraphweektotal_off_noffice_real_one($weeks[$i]["date1"]);
							if($stotal>0)
							{
								$stotal_link_in="date1=".base64_encode($weeks[$i]["date1"])."&office=";
								$stotal_link="<a href='javascript:showgraphinfo_real(\"".$stotal_link_in."\")'>$stotal</a>";
							}
							else
								$stotal_link=$stotal;
							echo "<td align='center' valign='middle'>".$stotal_link."</td>";
							$ototal +=$stotal;
						}
						if($ggrandtotal>0)
						{
						  $ggrandtotal_link_in="date1=".base64_encode($first_day)."&date2=".base64_encode($last_day)."&office=";
						  $ggrandtotal_link="<a href='javascript:showgraphinfo_real(\"".$ggrandtotal_link_in."\")'>$ggrandtotal</a>";
						}
						else
							$ggrandtotal_link=$ggrandtotal;
						echo "<td align='center' valign='middle'>$ggrandtotal_link</td>";
						?>
                      </tr>
                      <tr><td colspan='<?Php echo $weekrow; ?>'><hr/></td></tr>
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
                <div style="height:100px;"></div>
          </div>
      </div>
        <div id="lbodycont_footer"></div>
    </div>
    <div class="clearfooter"></div>
</div>
<?Php
include "include/footer.php";
?>
</body>
</html>
<?php
include "include/unconfig.php";
?>
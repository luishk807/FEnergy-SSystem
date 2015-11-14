<?Php
session_start();
include "include/config.php";
include "include/function.php";
date_default_timezone_set('America/New_York');
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
adminlogin();
redirect();
$thisyear=date("Y");
$nyear=date("Y", mktime(0, 0, 0, date("m"),date("d"),date("Y")+1));
$user = $_SESSION["salesuser"];
$cho_monthx=base64_decode($_REQUEST["cho_monthx"]);
if(empty($cho_monthx))
	$cho_monthx="";
$query = "select * from task_users order by type desc";
//get the right from april of this year til the march of next year
$getdays=array();
$xset=3;
for($i=0;$i<12;$i++)
{
	$xset++;
	$month=date("F", mktime(0, 0, 0, $xset,1,date("Y")));
	$xtotal=0;
	$xmdate=mktime(0, 0, 0, $xset,1,date("Y"));
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
	$month="";
	$year="";
	if(!empty($getdate[$i]["rdate"]))
	{
		$sdate=explode("-",$getdate[$i]["rdate"]);
		$year=$sdate[0];
		$month=$sdate[1];
		$thur=getThursday($year, $month);
		$fday=$year."-".$month."-01";
		$lday=date('Y-m-d',strtotime('-1 second',strtotime('+1 month',strtotime($month.'/01/'.$year.' 00:00:00'))));
		$fx=sizeof($thur)-1;
		$xdates=array();
		$lastindex=0;
		for($x=0;$x<sizeof($thur);$x++)
		{
			$lastindex=$x;
			$set1="";
			$set2="";
			if($x==0)
			{
				if($thur[$x] != $fday)
				{
					$set1=$fday;
					$set2=$thur[$x];
				}
				else
				{
					$set1=$fday;
					$set2=$fday;
				}
			}
			else
			{
				$set1=getGraPday($thur[$x]);
				$set2=$thur[$x];
			}
			$xdates[]=array('week'=>$nweek[$x],"date1"=>$set1,'date2'=>$set2);
		}
		if($thur[$fx]!=$lday)
		{
			$xd=explode("-",$thur[$fx]);
			$set1=date("Y-m-d", mktime(0, 0, 0, $xd[1],$xd[2]+1,$xd[0]));
			$set2=$lday;
			//$xdates[]=array('week'=>$nweek[sizeof($nweek)-1],"date1"=>$set1,'date2'=>$set2);
			$xdates[]=array('week'=>$nweek[$lastindex+1],"date1"=>$set1,'date2'=>$set2);
		}
		$weeksales[]=array('month'=>$getdate[$i]["month"],'weeksales'=>$xdates);
	}
	
}
//var_dump($weeksales);
//var_dump($gtotal);
//end of date gatherting
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
		$xset2=$xsales[$x]["date2"];
		//echo $xset1." ".$xset2;
		$xtotal=getGraPdayAmount($xset1,$xset2);
		//echo " : ".$xtotal."<br/>";
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
/*$wee1=explode(",",$week1_total);
$wee2=explode(",",$week2_total);
$wee3=explode(",",$week3_total);
$wee4=explode(",",$week4_total);
$wee5=explode(",",$week5_total);
$wee6=explode(",",$week6_total);
echo $week1_total." ".sizeof($wee1)."<br/>";
echo $week2_total." ".sizeof($wee2)."<br/>";
echo $week3_total." ".sizeof($wee3)."<br/>";
echo $week4_total." ".sizeof($wee4)."<br/>";
echo $week5_total." ".sizeof($wee5)."<br/>";
echo $week6_total." ".sizeof($wee6)."<br/>";*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome To Sales Report System</title>
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<!--highcharts graph script--->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="highcharts/js/modules/exporting.js" type="text/javascript"></script>
<!--<script src="http://code.highcharts.com/modules/exporting.js"></script>-->
<script src="highcharts/js/highcharts.js" type="text/javascript"></script>
<!--end highcharts graph script--->
<script type="text/javascript" language="javascript">
$(function () {
    var chart;
    $(document).ready(function() {
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
                name: 'Real Total',
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
			
        });
    });
});
</script>
<link rel="icon" type="image/png" href="images/favicon.ico">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="css/styleie.css" />
<![endif]-->
<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>

<body>
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
				//if(pView($user["type"]))
				//{
					?>
				<!--	<div id="tabmenu">
						<div id="tabmenu_in"><a href='createuser.php' class='contlinkc' >Create User</a>&nbsp;&nbsp;&nbsp;</div>
				  </div>
				  <br/>-->
				<?php
				//}
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
				<div id="container" style="width: 800px; height: 400px"></div>
                <br/><br/>
			  <div style="text-align:center; font-family:'rockw'; font-size:18pt; color:#8a8888">
					Sales Breakdown For 
<select id="cho_month" name="cho_month">
                    	<?Php
						for($i=0; $i<sizeof($getdate);$i++)
						{
							if(!empty($cho_monthx))
							{
								if($i==$cho_monthx)
									echo "<option value='".$i."' selected='selected'>".$getdate[$i]["xdate"]."</option>";
								else
									echo "<option value='".$i."'>".$getdate[$i]["xdate"]."</option>";
							}
							else
							{
								if($i==0)
								{
									echo "<option value='".$i."' selected='selected'>".$getdate[$i]["xdate"]."</option>";
									$cho_monthx=$i;
								}
								else
									echo "<option value='".$i."'>".$getdate[$i]["xdate"]."</option>";
							}
						}
						?>
                    </select>
              </div>
              <br/>
                <div>
                	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr style="font-family:'rockw';font-size:14pt;color:#00477f">
                        <td width="22%" align="center" valign="bottom">Office</td>
                        <td width="15%" align="center" valign="bottom">Week 1</td>
                        <td width="16%" align="center" valign="bottom">Week 2</td>
                        <td width="13%" align="center" valign="bottom">Week 3</td>
                        <td width="10%" align="center" valign="bottom">Week 4</td>
                        <td width="12%" align="center" valign="bottom">Week 5</td>
                        <td width="12%" align="center" valign="bottom">Week 6</td>
                      <tr>
                      	<td colspan="7"><hr/></td>
                       </tr>
                      </tr>
                      <tr style="font-family:'rockw';font-fize:18pt;">
                        <td align="center" valign="middle">Manhattan Office</td>
                        <td align="center" valign="middle">&nbsp;</td>
                        <td align="center" valign="middle">&nbsp;</td>
                        <td align="center" valign="middle">&nbsp;</td>
                        <td align="center" valign="middle">&nbsp;</td>
                        <td align="center" valign="middle">&nbsp;</td>
                        <td align="center" valign="middle">&nbsp;</td>
                      </tr>

                    </table>
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
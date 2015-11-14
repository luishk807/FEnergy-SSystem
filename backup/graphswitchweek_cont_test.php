<?php
session_start();
include "include/config.php";
include "include/function.php";
$cho_monthx=$_REQUEST["cho_monthx"];
date_default_timezone_set('America/New_York');
$thisyear=date("Y");
$nyear=date("Y", mktime(0, 0, 0, date("m"),date("d"),date("Y")+1));
$user = $_SESSION["salesuser"];
$cho_monthx=$_REQUEST["cho_monthx"];
if(empty($cho_monthx) && $cho_monthx <0 )
	$cho_monthx="";
$grandtotal_perweek=0;
$grandtotal_month=0;
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
?>
<script type="text/javascript" language="javascript">
//var timer=null;
function pieChartb()
{
	clearInterval(timer);
	//start of pie chart
	var chart_pieb;
	var colors = Highcharts.getOptions().colors,
	categories = [
	<?php
	$pieoffice_array=array();
	$oxdate=$getdate[$cho_monthx]["rdate"];
	$oxmonth=$getdate[$cho_monthx]["month"];
	$oxyearx=explode("-",$oxdate);
	$oxyear=$oxyearx[0];
	$oxtitle_date=" ".$oxmonth.",".$oxyear;
	$oxfirstday=getFirstDay($oxdate);
	$oxlastday=getLastDay($oxdate);
	$oxgrandtotal=getgraphweektotal_off_noffice($oxfirstday,$oxlastday);
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
		$oxgrandtotal_office=getgraphweektotal_off($oxfirstday,$oxlastday,$pieoffice_array[$ox]["id"]);
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
			$oxind_total=getPieIndTotal($oxfirstday,$oxlastday,$pieoffice_array[$ox]["id"], $oxgrandtotal);
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
	chart_pieb = new Highcharts.Chart({
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
}
</script>
<div id="container_pie" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
<br/>
<table width='100%' border='0' cellspacing='0' cellpadding='0'>
	<tr class='graphheader'>
       <td width='17%' align='center' valign='bottom'>Office</td>
        <?php
		$last_day=getLastDay($getdate[$cho_monthx]["rdate"]);
		$first_day=getFirstDay($getdate[$cho_monthx]["rdate"]);
		$weeks=$weeksales[$cho_monthx]["weeksales"];
		$weekrow=sizeof($weeks)+2;
		for($i=0;$i<sizeof($weeks);$i++)
		{
			$cx=$i+1;
			echo "<td width='12%' align='center' valign='bottom'>Week ".$cx."<br/><span class='graphrow_date'>".$weeks[$i]["date1"]."<br/>".$weeks[$i]["date2"]."</span></td>";
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
				  for($i=0;$i<sizeof($weeks);$i++)
				  {
					 $stotal=getgraphweektotal_off($weeks[$i]["date1"],$weeks[$i]["date2"],$rowx["id"]);
					 if($stotal>0)
					 {
						$stotal_link_in="date1=".base64_encode($weeks[$i]["date1"])."&date2=".base64_encode($weeks[$i]["date2"])."&office=".base64_encode($rowx["id"]);
						$stotal_link="<a href='javascript:showgraphinfo(\"".$stotal_link_in."\")'>$stotal</a>";
					}
					else
						$stotal_link=$stotal;
					echo "<td align='center' valign='middle'>".$stotal_link."</td>";
					$ototal +=$stotal;
				}
				if($ototal>0)
				{
					$ototal_link_in="date1=".base64_encode($first_day)."&date2=".base64_encode($last_day)."&office=".base64_encode($rowx["id"]);
					$ototal_link="<a href='javascript:showgraphinfo(\"".$ototal_link_in."\")'>$ototal</a>";
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
	$ggrandtotal=getgraphweektotal_off_noffice($first_day,$last_day);
	$retotal=$ggrandtotal-$real_month_total;
	if($retotal<0)
		$restyle="class='graphrow_missing'";
	else
		$restyle="class='graphrow_done'";
	 ?>
    <tr class='graphrow'>
        <td align='center' valign='middle' >Office Total</td>
        <?php
        $weeks=$weeksales[$cho_monthx]["weeksales"];
		for($i=0;$i<sizeof($weeks);$i++)
		{
			$stotal=getgraphweektotal_off_noffice($weeks[$i]["date1"],$weeks[$i]["date2"]);
			if($stotal>0)
			{
				$stotal_link_in="date1=".base64_encode($weeks[$i]["date1"])."&date2=".base64_encode($weeks[$i]["date2"])."&office=";
				$stotal_link="<a href='javascript:showgraphinfo(\"".$stotal_link_in."\")'>$stotal</a>";
			}
			else
				$stotal_link=$stotal;
			echo "<td align='center' valign='middle'>".$stotal_link."</td>";
			$ototal +=$stotal;
		}
		if($ggrandtotal>0)
		{
			$ggrandtotal_link_in="date1=".base64_encode($first_day)."&date2=".base64_encode($last_day)."&office=";
			$ggrandtotal_link="<a href='javascript:showgraphinfo(\"".$ggrandtotal_link_in."\")'>$ggrandtotal</a>";
		}
		else
			$ggrandtotal_link=$ggrandtotal;
		echo "<td align='center' valign='middle'>$ggrandtotal_link</td>";
		?>
    </tr>
    <tr><td colspan='<?Php echo $weekrow; ?>'><hr/></td></tr>
    <tr class='graphrow'>
        <td align='center' valign='middle' >Real Total</td>
		<td align='center' valign='middle' colspan='<?php echo sizeof($weeks); ?>'>&nbsp;</td>
        <td align='center' valign='middle'><?php echo $real_month_total; ?></td>
    </tr>
    <tr><td colspan='<?Php echo $weekrow; ?>'><hr/></td></tr>
	<tr <?php echo $restyle; ?>>
    	<td align='center' valign='middle' >Remaining</td>
		<td align='center' valign='middle' colspan='<?php echo sizeof($weeks); ?>'>&nbsp;</td>
        <td align='center' valign='middle'><?php echo $retotal; ?></td>
    </tr>
</table>
<?php
include "include/unconfig.php";
?>
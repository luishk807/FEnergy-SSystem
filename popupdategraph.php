<?php
session_start();
include "include/config.php";
include "include/function.php";
$task=$_REQUEST["task"];
$office=base64_decode($_REQUEST["office"]);
$date1=base64_decode($_REQUEST["date1"]);
$date2=base64_decode($_REQUEST["date2"]);
$task=$_REQUEST["task"];
$query="";
$ascdesc = $_REQUEST["ascdesc"];
if($ascdesc=="desc")
	$ascdesc="asc";
else
	$ascdesc="desc";
if(!empty($task))
{
	$querys = " order by ".$task." ".$ascdesc;
}
$officename="";
if(!empty($office))
{
	$query="select * from rec_office where id='".$office."'";
	if($result = mysql_query($query))
	{
		if(($numrows = mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
			$namex=stripslashes($info["name"]);
			$officename = "For ".stripslashes($namex);
			$qoffice=" and office='".$office."'";
		}
	}
}
$qu="date1=".$_REQUEST["date1"]."&date2=".$_REQUEST["date2"]."&office=".$_REQUEST["office"];
$query="select * from sales_report where fromdate between '".$date1."' and '".$date2."' $qoffice $querys";
?>
    <fieldset>
    	<legend>
        	<span style="font-size:15pt; color:#666">Complete Detail List Of Sales <?Php echo $officename; ?></span>
        </legend>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr style="background-color:#28629e; color:#FFF">
    <td width="6%" align="center" valign="middle">&nbsp;</td>
    <td width="20%" align="center" valign="middle"><a class='linkstats' href='javascript:changeStatSort("userid","<?php echo $qu; ?>","<?php echo $ascdesc; ?>")'>Manager/Team Leader</a></td>
    <td width="16%" align="center" valign="middle"><a class='linkstats' href='javascript:changeStatSort("agentid","<?php echo $qu; ?>","<?php echo $ascdesc; ?>")'>Agent</a></td>
    <td width="12%" align="center" valign="middle"><a class='linkstats' href='javascript:changeStatSort("office","<?php echo $qu; ?>","<?php echo $ascdesc; ?>")'>Office</a></td>
    <td width="17%" align="center" valign="middle"><a class='linkstats' href='javascript:changeStatSort("fromdate","<?php echo $qu; ?>","<?php echo $ascdesc; ?>")'>Date</a></td>
    <td width="29%" align="center" valign="middle"><a class='linkstats' href='javascript:changeStatSort("stotal","<?php echo $qu; ?>","<?php echo $ascdesc; ?>")'>Sales</a></td>
  </tr>
  <tr>
    <td colspan="6">
    	<div style="height:350px; overflow:auto" id="statsdiv">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <?php
		if(!empty($query))
		{
			if($result = mysql_query($query))
			{
				if(($num_rows = mysql_num_rows($result))>0)
				{
					$countx=1;
					$totalx=0;
					while($rows=mysql_fetch_array($result))
					{
						$totalx = $countx%2;
						if($totalx==0)
							 $rowstyle="style='font-size:15pt'";
						else
							$rowstyle="style='background-color:#e1fb51; font-size:15pt'";
						echo "<tr $rowstyle>";
						echo "<td width='6%' align='center' valign='middle'>$countx</td>";
						echo "<td width='20%' align='center' valign='middle'><a class='linkstats_b' target='_blank' href='accountreport_set.php?id=".base64_encode($rows["id"])."'>".getName($rows["userid"])."</a></td>";
						echo "<td width='16%' align='center' valign='middle'>".getAgent($rows["agentid"])."</td>";
						$xdate = fixdate_comps("invdate_s",$rows["fromdate"]);
						echo "<td width='12%' align='center' valign='middle'>".getOfficeName($rows["office"])."</td>";
						echo "<td width='17%' align='center' valign='middle'>$xdate</td>";
						echo "<td width='29%' align='center' valign='middle'>".$rows["stotal"]."</td>";
						echo "</tr>";
						 $countx++;
					}
				}
				else
					echo "<tr class='rowstyleno'><td colspan='6' align='center' valign='middle'>Not Found</td></tr>";
			}
			else
			echo "<tr class='rowstyleno'><td colspan='6' align='center' valign='middle'>Not Found</td></tr>";
		}
		else
			echo "<tr class='rowstyleno'><td colspan='6' align='center' valign='middle'>Not Found</td></tr>";
		?>
    </table>
    </div>
    </td>
  </tr>
  </table>
    </fieldset>

<?php
	include "include/config.php";
?>
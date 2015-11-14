<?php
session_start();
include "include/config.php";
include "include/function.php";
$office=base64_decode($_REQUEST["office"]);
$date1=base64_decode($_REQUEST["date1"]);
$date2=base64_decode($_REQUEST["date2"]);
$ascdesc=$_REQUEST["ascdesc"];
$qoffice="";
if($ascdesc=="desc")
	$ascdesc="asc";
else
	$ascdesc="desc";
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
$query="select * from sales_report where fromdate between '".$date1."' and '".$date2."' $qoffice ";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>View Result Page</title>
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<style>
.rowstyleno{
	font-size:14pt;
	background-color:#900;
	color:#FFF;
}
.linkstats{
	color:#FFF;
}
.linkstats_b{
	color:#000;
}
</style>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<form action="" method="post">
  <tr>
    <td align="center" valign="middle">
    	<div id="messagef" name="messagef" class="white" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
    </td>
  </tr>
  <tr>
    <td height="34" align="center" valign="middle">
    <div id="wholegraph_pop">
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
    </div>
        </td>
    </tr>
  <tr>
    <td align="left" valign="middle"><div id="message2" name="message2" class="white" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div></td>
  </tr>
  <tr>
    <td height="50" align="center" valign="middle">
    	<input type="button" value="Cancel" onclick="closemodal()"/>
    </td>
  </tr>
 </form>
</table>
</body>
</html>
<?php
	include "include/config.php";
?>
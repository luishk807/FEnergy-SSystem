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
if(!empty($date2))
{
	$qu="date1=".$_REQUEST["date1"]."&date2=".$_REQUEST["date2"]."&office=".$_REQUEST["office"];
	$query="select * from sales_report_real where ddate between '".$date1."' and '".$date2."' $qoffice ";
}
else
{
	$qu="date1=".$_REQUEST["date1"]."&office=".$_REQUEST["office"];
	$query="select * from sales_report_real where ddate='".$date1."' $qoffice ";
}
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
    <td width="35%" align="center" valign="middle"><a class='linkstats' href='javascript:changeStatSort_real("userid","<?php echo $qu; ?>","<?php echo $ascdesc; ?>")'>Staff</a></td>
    <td width="30%" align="center" valign="middle"><a class='linkstats' href='javascript:changeStatSort_real("office","<?php echo $qu; ?>","<?php echo $ascdesc; ?>")'>Office</a></td>
    <td width="15%" align="center" valign="middle"><a class='linkstats' href='javascript:changeStatSort_real("xelec","<?php echo $qu; ?>","<?php echo $ascdesc; ?>")'>Electric</a></td>
    <td width="14%" align="center" valign="middle"><a class='linkstats' href='javascript:changeStatSort_real("xgas","<?php echo $qu; ?>","<?php echo $ascdesc; ?>")'>Gas</a></td>
  </tr>
  <tr>
    <td colspan="5">
    	<div style="height:350px; overflow:auto" id="statsdiv">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <?php
		$xuser=array();
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
			{
				while($rows=mysql_fetch_array($result))
				{
					if(sizeof($xuser)>0)
					{
						$found=false;
						for($i=0;$i<sizeof($xuser);$i++)
						{
							$found=false;
							$xelec=$rows["xelec"];
							$xgas=$rows["xgas"];
							if(trim($xuser[$i]["userid"])==trim($rows["userid"]))
							{
								$found=true;
								$xxelec=$xuser[$i]["xelec"]+$xelec;
								$xxgas=$xuser[$i]["xgas"]+$xgas;
								$xuser[$i]["xelec"]=$xxelec;
								$xuser[$i]["xgas"]=$xxgas;
								break;
							}
						}
						if(!$found)
							$xuser[]=array('userid'=>$rows["userid"],'ddate'=>$rows["ddate"],'office'=>$rows["office"],'xelec'=>$rows['xelec'],'xgas'=>$rows["xgas"]);
					}
					else
						$xuser[]=array('userid'=>$rows["userid"],'ddate'=>$rows["ddate"],'office'=>$rows["office"],'xelec'=>$rows['xelec'],'xgas'=>$rows["xgas"]);
				}
			}
		}
		if(sizeof($xuser)>0)
		{
			for($i=0;$i<sizeof($xuser);$i++)
			{
				$countx=$i+1;
				$totalx=0;
				$totalx = $countx%2;
				if($totalx==0)
					$rowstyle="style='font-size:15pt'";
				else
					$rowstyle="style='background-color:#e1fb51; font-size:15pt'";
				echo "<tr $rowstyle>";
				echo "<td width='6%' align='center' valign='middle'>$countx</td>";
				echo "<td width='35%' align='center' valign='middle'>".getAgent($xuser[$i]["userid"])."</td>";
				echo "<td width='30%' align='center' valign='middle'>".getOfficeName($xuser[$i]["office"])."</td>";
				echo "<td width='15%' align='center' valign='middle'>".$xuser[$i]["xelec"]."</td>";
				echo "<td width='14%' align='center' valign='middle'>".$xuser[$i]["xgas"]."</td>";
				echo "</tr>";
			}
		}
		else
			echo "<tr class='rowstyleno'><td colspan='5' align='center' valign='middle'>Not Found</td></tr>";
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
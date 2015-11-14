<?Php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
$userx = $_SESSION["salesuser"];
date_default_timezone_set('America/New_York');
$xid = base64_decode($_REQUEST["id"]);
$task=$_REQUEST["task"];
$oid=base64_decode($_REQUEST["oid"]);
$date_detail=base64_decode($_REQUEST["date_detail"]);
if(!empty($rboth))
	$rboth=strtoupper(trim($rboth));
if($task=="mult")
{
	$xidx="where userid in(".$xid.")";
	$aname="Chosen Agent";
}
if($task=="ret")
{
	$date1x=getFirstDay($date_detail);
	$date2x=getLastDay($date_detail);
	$x_month=fixdate_comps('m_text',$date_detail);
	$xname=getAgent($xid);
	$xidx="where userid in(".$xid.") and ddate between '".$date1x."' and '".$date2x."' and office='".$oid."' order by ddate desc";
	$aname="Chosen Agent $xname in $x_month ";
}
if($task=="map")
{
	$date1x=$date_detail;
	$xname=getName($xid);
	$xdate=fixdate_comps('invdate_s',$date1x);
	$xidx="where userid='".$xid."' and fromdate='".$date1x."' order by fromdate desc";
	$aname="Chosen Agent $xname in $xdate ";
}
else
{
	$aname=getAgent($xid);
	$xidx="where userid='".$xid."'";
}
$rtotal=0;
if($task !='map')
{
	$totalcol='6';
	
	$query = "select sum(xelec + xgas) as total from sales_report_real $xidx";
	if(!empty($query))
	{
		if($result = mysql_query($query))
		{
			if(($numrows = mysql_num_rows($result))>0)
			{
				$info=mysql_fetch_assoc($result);
				if($info["total"]>0)
					$rtotal=$info["total"];
			}
		}
	}
	$query = "select * from sales_report_real $xidx";
	$height="style='height:700px'";
	if(!empty($query))
	{
		if($result = mysql_query($query))
		{
			if(($numrows = mysql_num_rows($result))>5)
				$height="";
		}
	}
}
else
{
	$totalcol='5';
	$query = "select sum(stotal) as total from sales_report $xidx";
	if(!empty($query))
	{
		if($result = mysql_query($query))
		{
			if(($numrows = mysql_num_rows($result))>0)
			{
				$info=mysql_fetch_assoc($result);
				if($info["total"]>0)
					$rtotal=$info["total"];
			}
		}
	}
	$query = "select * from sales_report $xidx";
	$height="style='height:700px'";
	if(!empty($query))
	{
		if($result = mysql_query($query))
		{
			if(($numrows = mysql_num_rows($result))>5)
				$height="";
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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script src="js/swfobject_modified.js" type="text/javascript"></script>
<script type="text/javascript" src="js/calendarb_js/date.js"></script>
<script type="text/javascript" src="js/calendarb_js/jquery.datePicker.js"></script>
<link rel="stylesheet" type="text/css" href="css/calendarb.css">
<script type="text/javascript" language="javascript">
 $(function()
 {
	//$('.date-pick').datePicker({autoFocusNextInput: true});
	Date.format = 'mm/dd/yyyy';
	$('.date-pick').datePicker({startDate:'01/01/1996'});
 });
function changeview(value)
{
	if(value !='na')
		window.location.href='viewreport.php?id='+value;
}
$(document).ready(function()
	{
        $(".slidingDiv").hide();
        $(".show_this").show();

		$('.show_this').click(function()
		{
			$(".slidingDiv").slideToggle();
		}
);
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
	<!--header-->
<?php
include "include/header.php";
?>
    <!--end of header-->
    <div id="lbodycont">
    	<div id="lbodycont_header">
        	<div id="lbody_header_in">Sales Report</div>
        </div>
        <div id="lbodycont_middle">
            <div id="lbodycont_middle_in" <?php echo $height; ?>>
           		 <div id="tabmenu">
						<div id="tabmenu_in"><a href='viewgraph_r.php' class='contlinkc' >View Annual Sales Performance</a>&nbsp;&nbsp;&nbsp;</div>
				  </div>
				  <br/>
				<form action="" method="post">
                <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
                     <div id="message" name="message" class="black" style="text-align:center">
                     &nbsp;
                     <?php
                     if(isset($_SESSION["salesresult"]))
                     {
                        echo $_SESSION["salesresult"]."<br/>";
                        unset($_SESSION["salesresult"]);
                     }
                     ?>
                    </div>
                   <?Php
					//}
					//else
					//	echo "<br/><br/>";
					?>
                  <div id="rtotal">
                    <div style="padding-top:10px;">
                    <?php
						 echo "Total For ".$aname." is: ".$rtotal;
                    ?>
                    </div>
                 </div>
                 <br/>
                 <br/>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr class='moheader'>
                  	  <td width="9%" align="center" valign="middle">&nbsp;</td>
                    <td width="26%" align="center" valign="middle">Agent</td>
                    <td width="27%" align="center" valign="middle">Office</td>
                    <td width="12%" align="center" valign="middle">Report Date</td>
                    <?Php
					if($task !='map')
					{
						?>
                    <td width="13%" align="center" valign="middle">Electrical</td>
                    <td width="13%" align="center" valign="middle">Gas</td>
                    <?php
					}
					else
					{
					?>
                    <td width="26%" align="center" valign="middle">Total</td>
                    <?Php
					}
					?>
                  </tr>
                   <?Php
				    $gtotal=$etotal=0;
				  if($result = mysql_query($query))
				  {
					  if(($num_rows=mysql_num_rows($result))>0)
					  {
						  $count=1;
						  while($row = mysql_fetch_array($result))
						  {
							  if($task !='map')
							  {
								  $gtotal += $row["xelec"];
								  $etotal += $row["xgas"];
								  $agentname = getAgent($row["userid"]);
								  $officename = getOfficeName($row["office"]);
								  $agentlink = $agentname;
								  echo "<tr><td colspan='".$totalcol."' align='center'><hr/></td></tr>
					  <tr class='morow'><td height='33' align='center' valign='middle'>$count</td><td height='33' align='center' valign='middle'>$agentlink</td><td align='center' valign='middle'>".$officename."</td><td align='center' valign='middle'>".fixdate_comps("invdate_s",$row["rdate"])."</td><td align='center' valign='middle'>".$row["xelec"]."</td><td align='center' valign='middle'>".$row["xgas"]."</td></tr>";
								$count++;
							  }
							  else
							  {
								  $gtotal += $row["stotal"];
								  $agentname = getAgent($row["userid"]);
								  $officename = getOfficeName($row["office"]);
								  $agentlink = $agentname;
								  echo "<tr><td colspan='".$totalcol."' align='center'><hr/></td></tr>
					  <tr class='morow'><td height='33' align='center' valign='middle'>$count</td><td height='33' align='center' valign='middle'>$agentlink</td><td align='center' valign='middle'>".$officename."</td><td align='center' valign='middle'>".fixdate_comps("invdate_s",$row["fromdate"])."</td><td align='center' valign='middle'>".$row["stotal"]."</td></tr>";
								$count++;
							  }
						  }
					  }
					  else
						echo "<tr><td colspan='".$totalcol."' class='nfound'>No Current Sales Found</td></tr>";
				  }
				  else
					echo "<tr><td colspan='".$totalcol."' class='nfound'>No Current Sales Found</td></tr>";
				  ?>
                  <tr><td colspan="<?Php echo $totalcol; ?>"><hr/></td></tr>
                  <?Php
				  if($task !='map')
				  {
					  ?>
                  <tr>
                  <td colspan="4" align="right" valign="middle">
                        <span class='mform_q'>Total:</span> &nbsp;&nbsp;
                  </td>
                  <td align="center" valign="middle"><span class='mform_q'><?php echo $etotal; ?></span></td>
                  <td align="center" valign="middle"><span class='mform_q'><?php echo $gtotal; ?></span></td>
                  </tr>
                  <tr><td colspan="<?Php echo $totalcol; ?>"><hr/></td></tr>
                  <tr>
                  <?Php
				  }
				  ?>
                  <td colspan="4" align="right" valign="middle">
                        <span class='mform_q'>Grand Total:</span> &nbsp;&nbsp;
                  </td>
                  <?Php
				  if($task !='map')
				  {
				  ?>
                  <td align="center" valign="middle" colspan="2"><span class='mform_q'><?php echo $etotal+$gtotal; ?></span></td>
                  <?php
				  }
				  else
				  {
				  ?>
                  <td align="center" valign="middle"><span class='mform_q'><?php echo $gtotal; ?></span></td>
                  <?php
				  }
				  ?>
                  </tr>
      			</table>
                </form>
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
<?Php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
$userx = $_SESSION["salesuser"];
$xid = base64_decode($_REQUEST["id"]);
$weekday = date('l');
$today = date('Y-m-d');
$tday = getCalDate($weekday);
$gtotal=0.00;
$mname = getUserName($xid);
$height="style='height:700px'";
$query = "select * from sales_report where userid='".$xid."' and (fromdate between '".$tday."' and '".$today."')";
if(!empty($query))
{
	if($result = mysql_query($query))
	{
		if(($numrows = mysql_num_rows($result))>12)
			$height="";
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
						<div id="tabmenu_in"><a href='viewreport.php' class='contlinkc' >View Report</a>&nbsp;&nbsp;&nbsp;</div>
				  </div>
				  <br/>
				<form action="viewreport.php" method="post">
                <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
                     <div id="message2m" name="message2m">
                     &nbsp;
                     <?php
                     if(isset($_SESSION["salesresult"]))
                     {
                        echo $_SESSION["salesresult"]."<br/>";
                        unset($_SESSION["salesresult"]);
                     }
                     ?>
                    </div>
                    <div id="reportmanager">
                        <span class='mform_q'>Running Report For Manager: <?php echo $mname; ?></span>
                     </div>
                 <br/>
                 <br/>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr class='moheader'>
                  	  <td width="9%" align="center" valign="middle">&nbsp;</td>
                    <td width="26%" align="center" valign="middle">Agent</td>
                    <td width="27%" align="center" valign="middle">Office</td>
                    <td width="22%" align="center" valign="middle">Date</td>
                    <td width="16%" align="center" valign="middle">Sales</td>
                  </tr>
                   <?Php
				  if($result = mysql_query($query))
				  {
					  if(($num_rows=mysql_num_rows($result))>0)
					  {
						  $count=1;
						  while($row = mysql_fetch_array($result))
						  {
							  $gtotal += $row["stotal"];
							  $username = getName($row["userid"]);
							  $agentname = getAgent($row["agentid"]);
							  $officename = getOfficeName($row["office"]);
							  if(pView($userx["type"]))
								$agentlink = "<a href='accountreport_set.php?id=".base64_encode($row["id"])."' class='contlinkb'>".$agentname."</a>";
							else
								$agentlink = $agentname;
							  echo "<tr><td colspan='5' align='center'><hr/></td></tr>
				  <tr class='morow'><td height='33' align='center' valign='middle'>$count</td><td height='33' align='center' valign='middle'>$agentlink</td><td align='center' valign='middle'>".$officename."</td><td align='center' valign='middle'>".fixdate_comps("invdate_s",$row["fromdate"])."</td><td align='center' valign='middle'>".$row["stotal"]."</td></tr>";
				  			$count++;
						  }
					  }
					  else
						echo "<tr><td colspan='5' class='nfound'>No Current Sales Found</td></tr>";
				  }
				  else
					echo "<tr><td colspan='5' class='nfound'>No Current Sales Found</td></tr>";
				  ?>
                  <tr><td colspan="5"><hr/></td></tr>
                  <tr>
                  <td colspan="4" align="right" valign="middle">
                        <span class='mform_q'>Total:</span> &nbsp;&nbsp;
                  </td>
                  <td align="center" valign="middle"><span class='mform_q'><?php echo $gtotal; ?></span></td>
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
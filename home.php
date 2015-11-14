<?php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
redirect();
$userx = $_SESSION["salesuser"];
$today = date('Y-m-d');
$query = "select * from sales_report where fromdate='".$today."'";
$height="style='height:500px'";
if($result = mysql_query($query))
{
	if(($num_rows = mysql_num_rows($result))>5)
		$height="";
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
        	<div id="lbody_header_in">Today's Sales</div>
        </div>
        <div id="lbodycont_middle">
            <div id="lbodycont_middle_in" <?php echo $height; ?>>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr class='moheader'>
                  	  <td width="7%" align="center" valign="middle">&nbsp;</td>
                    <td width="18%" align="center" valign="middle">Manager</td>
                    <td width="22%" align="center" valign="middle">Agent</td>
                    <td width="21%" align="center" valign="middle">Office</td>
                    <td width="17%" align="center" valign="middle">Date</td>
                    <td width="15%" align="center" valign="middle">Sales</td>
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
							  {
								$agentlink = "<a href='accountreport_set.php?id=".base64_encode($row["id"])."' class='contlinkb'>".$agentname."</a>";
								$maglink = "<a href='account_set.php?id=".base64_encode($row["userid"])."' class='contlinkb'>".$username."</a>";
							  }
							else
							{
								$agentlink = $agentname;
								$maglink = $username;
							}
							  echo "<tr><td colspan='6' align='center'><hr/></td></tr>
				  <tr class='morow'><td height='33' align='center' valign='middle'>$count</td><td height='33' align='center' valign='middle'>$maglink</td><td height='33' align='center' valign='middle'>$agentlink</td><td align='center' valign='middle'>".$officename."</td><td align='center' valign='middle'>".fixdate_comps("invdate",$row["fromdate"])."</td><td align='center' valign='middle'>".$row["stotal"]."</td></tr>";
				  			$count++;
						  }
					  }
					  else
						echo "<tr><td colspan='6' class='nfound'>No Current Sales Found</td></tr>";
				  }
				  else
					echo "<tr><td colspan='6' class='nfound'>No Current Sales Found</td></tr>";
				  ?>
                  <tr><td colspan="6"><hr/></td></tr>
                  <tr>
                  <td colspan="5" align="right" valign="middle">
                        <span class='mform_q'>Total:</span> &nbsp;&nbsp;
                  </td>
                  <td align="center" valign="middle"><span class='mform_q'><?php echo $gtotal; ?></span></td>
                  </tr>
      			</table>
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
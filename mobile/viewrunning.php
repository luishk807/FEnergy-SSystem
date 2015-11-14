<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
adminlogin();
$userx = $_SESSION["salesuser"];
$xid = base64_decode($_REQUEST["id"]);
$weekday = date('l');
$today = date('Y-m-d');
$tday = getCalDate($weekday);
$gtotal=0.00;
$mname = getUserName($xid);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome To Sales Report System</title>
<script type="text/javascript" language="javascript" src="../js/script.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script src="../js/swfobject_modified.js" type="text/javascript"></script>
<link rel="icon" type="image/png" href="../images/favicon.ico">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="../css/styleie.css" />
<![endif]-->
 <link rel="stylesheet" type="text/css" href="../css/stylem.css" />
</head>

<body>
<?php
include "include/header.php";
?>
<div id="mbody">
<form action="" method="post">
	<div id="tabmenu_in"><a href='viewreport.php' class='contlinkc' ><span class='mform_q'>View Report</span></a>&nbsp;&nbsp;&nbsp;</div>
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
        <td align="center" valign="middle">Agent</td>
        <td align="center" valign="middle">Office</td>
        <td align="center" valign="middle">Date</td>
        <td align="center" valign="middle">Sales</td>
      </tr>
      <?Php
	  $query = "select * from sales_report where userid='".$xid."' and (fromdate between '".$tday."' and '".$today."')";
	  if($result = mysql_query($query))
	  {
		  if(($num_rows=mysql_num_rows($result))>0)
		  {
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
				  echo "<tr><td colspan='4' align='center'><hr/></td></tr>
      <tr class='morow'><td height='33' align='center' valign='middle'>$agentlink</td><td align='center' valign='middle'>".$officename."</td><td align='center' valign='middle'>".fixdate_comps("invdate_s",$row["fromdate"])."</td><td align='center' valign='middle'>".$row["stotal"]."</td></tr>";
			  }
		  }
		  else
		  	echo "<tr><td colspan='4' class='nfound_m'>No Sales Found</td></tr>";
	  }
	  else
	  	echo "<tr><td colspan='4' class='nfound_m'>No Sales Found</td></tr>";
	  ?>
      <tr><td colspan="4"><hr/></td></tr>
      <tr>
      <td colspan="3" align="right" valign="middle">
            <span class='mform_q'>Total:</span> &nbsp;&nbsp;
      </td>
      <td align="center" valign="middle"><span class='mform_q'><?php echo $gtotal; ?></span></td>
      </tr>
    </table>
</form>
</div>
<br/><br/>
</body>
</html>
<?php
include "../include/unconfig.php";
?>
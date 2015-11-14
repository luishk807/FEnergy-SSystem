<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
adminlogin();
redirect();
$user = $_SESSION["salesuser"];
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
    <div id="message" name="message" class="black" style="text-align:center">
    <?php
      if(isset($_SESSION["salesresult"]))
      {
         echo $_SESSION["salesresult"]."<br/>";
         unset($_SESSION["salesresult"]);
      }
    ?>
      </div>
     <!--show all the offices-->
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr class='cont_t_header'>
                    <td height="30" colspan="4" align="center" valign="middle"><hr/><span style='font-size:30pt; font-weight:bold'>Office Goals</span><hr/></td>
                  </tr>
                  <tr class='moheader'>
                    <td align="center" valign="middle">Office Name</td>
                    <td align="center" valign="middle">Goals</td>
                    <td align="center" valign="middle">Updated</td>
                  </tr>
                  <?php
				  $query="select * from sales_goals_office order by date desc";
				  if($result=mysql_query($query))
				  {
					  if(($num_rows=mysql_num_rows($result))>0)
					  {
						  $countx=1;
						  while($rows=mysql_fetch_array($result))
						  {
							 echo "<tr><td colspan='3' align='center' valign='middle'><hr/></td></tr>";
                  			 echo "<tr class='morow_b'><td align='center' valign='middle'><a href='accountgoalsx_oset.php?id=".base64_encode($rows["id"])."' class='contlinkb'>".getOfficeName($rows["office"])."</a></td><td align='center' valign='middle'>".$rows["goals"]."</td><td align='center' valign='middle'>".fixdate_comps('invdate_s',$rows["date"])."</td></tr>";
							 $countx++;
						  }
					  }
					  else
					  	echo "<tr class='nfound_m'><td height='27' colspan='3' align='center' valign='middle'>No Office Goals Found</td></tr>";
				  }
				  else
				  	echo "<tr class='nfound_m'><td height='27' colspan='3' align='center' valign='middle'>No Office Goals Found</td></tr>";
				  ?>
                </table>
                <br/><br/>
                <!--show detailed goals-->
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr class='moheader'>
         <td height="30" colspan="4" align="center" valign="middle"><hr/><span style='font-size:30pt; font-weight:bold'>Detail of Goal Per Office</span><hr/></td>
      </tr>
      <tr class='moheader'>
        <td  align="center" valign="middle">Username</td>
        <td align="center" valign="middle">Updated</td>
        <td align="center" valign="middle">Office</td>
        <td align="center" valign="middle">Goals</td>
      </tr>
      <?Php
	  $query = "select * from sales_goals_office order by office, date desc";
	  if($result = mysql_query($query))
	  {
		  if(($num_rows=mysql_num_rows($result))>0)
		  {
			  while($row = mysql_fetch_array($result))
			  {
				 $qx="select * from sales_goals_ind where goalid='".$row["id"]."' order by date desc";
				 if($rx=mysql_query($qx))
				 {
					if(($nux=mysql_num_rows($rx))>0)
					{
						while($rox=mysql_fetch_array($rx))
						{
							echo "<tr><td colspan='4' align='center' valign='middle'><hr/></td></tr>";
							echo "<tr class='morow_b'>
							<td  align='center' valign='middle'><a href='accountgoalsx_set.php?id=".base64_encode($row["id"])."&plink=o' class='contlinkb'>".getName($rox["userid"])."</a></td><td  align='center' valign='middle'>".fixdate_comps('invdate_s',$rox["date"])."</td><td  align='center' valign='middle'><a href='accountgoalsx_oset.php?id=".base64_encode($row["id"])."' class='contlinkb'>".getOfficeName($row["office"])."</a></td><td align='center' valign='middle'>".$rox["goals"]."</td></tr>";
						}
					}
				}
			  }
		  }
		  else
		  	echo "<tr class='nfound_m'><td colspan='4' class='nfound_m'>No Office Found</td></tr>";
	  }
	  else
	  	echo "<tr class='nfound_m'><td colspan='4' class='nfound_m'>No Office Found</td></tr>";
	  ?>
    </table>    
</div>
<br/><br/>
</body>
</html>
<?php
include "../include/unconfig.php";
?>
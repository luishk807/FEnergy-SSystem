<?Php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
redirect();
$user = $_SESSION["salesuser"];
$query = "select * from sales_goals_ind order by date desc";
$height="style='height:500px'";
if($result = mysql_query($query))
{
	if(($num_rows = mysql_num_rows($result))>12)
		$height="";
}
$height="";
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
	<?php
	include "include/header.php";
	?>
    <div id="lbodycont">
    	<div id="lbodycont_header">
        	<div id="lbody_header_in">View Goals</div>
        </div>
        <div id="lbodycont_middle">
            <div id="lbodycont_middle_in">
            	 <?php
				if(pView($user["type"]))
				{
					?>
					<div id="tabmenu">
						<div id="tabmenu_in"><a href='creategoalsx.php' class='contlinkc' >Create Goals</a>&nbsp;&nbsp;&nbsp;</div>
				  </div>
				  <br/>
				<?php
				}
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
                 <!--show all the offices-->
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr class='cont_t_header'>
                    <td height="30" colspan="4" align="center" valign="middle"><hr/><span style='font-size:15pt; font-weight:bold'>Office Goals</span><hr/></td>
                  </tr>
                  <tr class='cont_t_header'>
                    <td width="11%"  align="center" valign="middle">&nbsp;</td>
                    <td width="25%" align="center" valign="middle">Office Name</td>
                    <td width="26%" align="center" valign="middle">Goals</td>
                    <td width="20%" align="center" valign="middle">Updated</td>
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
							 echo "<tr><td colspan='4' align='center' valign='middle'><hr/></td></tr>";
                  			 echo "<tr><td align='center' valign='middle'>$countx</td><td align='center' valign='middle'><a href='accountgoalsx_oset.php?id=".base64_encode($rows["id"])."' class='contlinkb'>".getOfficeName($rows["office"])."</a></td><td align='center' valign='middle'>".$rows["goals"]."</td><td align='center' valign='middle'>".fixdate_comps('invdate_s',$rows["date"])."</td></tr>";
							 $countx++;
						  }
					  }
					  else
					  	echo "<tr class='nfound'><td height='27' colspan='4' align='center' valign='middle'>No Office Goals Found</td></tr>";
				  }
				  else
				  	echo "<tr class='nfound'><td height='27' colspan='4' align='center' valign='middle'>No Office Goals Found</td></tr>";
				  ?>
                </table>
                <br/><br/>
                <!--show detailed goals-->
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr class='cont_t_header'>
                    <td height="30" colspan="5" align="center" valign="middle"><hr/><span style='font-size:15pt; font-weight:bold'>Detail of Goal Per Office</span><hr/></td>
                  </tr>
                  <tr class='cont_t_header'>
                    <td width="11%" height="30" align="center" valign="middle">&nbsp;</td>
                    <td width="25%" align="center" valign="middle">Name</td>
                    <td width="26%" align="center" valign="middle">Updated</td>
                    <td width="20%" align="center" valign="middle">Office</td>
                    <td width="18%" align="center" valign="middle">Goals</td>
                  </tr>
                  <tr>
                    <td colspan="6" align="center" valign="middle">
                    <div <?php echo $height; ?>>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <?php
						$query = "select * from sales_goals_office order by office, date desc";
						if($result = mysql_query($query))
						{
							if(($num_rows = mysql_num_rows($result))>0)
							{
								$count=1;
								while($rows=mysql_fetch_array($result))
								{
									$qx="select * from sales_goals_ind where goalid='".$rows["id"]."' order by date desc";
									if($rx=mysql_query($qx))
									{
										if(($nux=mysql_num_rows($rx))>0)
										{
											while($rox=mysql_fetch_array($rx))
											{
												echo "<tr><td height='27' colspan='5' align='center' valign='middle'><hr/></td></tr>
												<tr><td width='11%' align='center' valign='middle'>$count</td>
												<td width='25%' align='center' valign='middle'><a href='accountgoalsx_set.php?id=".base64_encode($rows["id"])."&plink=o' class='contlinkb'>".getName($rox["userid"])."</a></td>
												<td width='26%' align='center' valign='middle'>".fixdate_comps('invdate_s',$rox["date"])."</td>
												<td width='20%' align='center' valign='middle'><a href='accountgoalsx_oset.php?id=".base64_encode($rows["id"])."' class='contlinkb'>".getOfficeName($rows["office"])."</a></td>
												<td width='18%' align='center' valign='middle'>".$rox["goals"]."</td></tr>";
												$count++;
											}
										}
									}
								}
							}
							else
								echo "<tr class='nfound'><td height='27' colspan='5' align='center' valign='middle'>No Individual Goals Found</td></tr>";
						}
						else
							echo "<tr class='nfound'><td height='27' colspan='5' align='center' valign='middle'>No Individual Goals Found</td></tr>";
						?>
                        </table>
                      </div>
                    </td>
                  </tr>
                </table>

            </div>
            <div style="height:200px">&nbsp;</div>
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
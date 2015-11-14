<?Php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
redirect();
$user = $_SESSION["salesuser"];
$query = "select * from rec_office order by datecreated desc";
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
	<?php
	include "include/header.php";
	?>
    <div id="lbodycont">
    	<div id="lbodycont_header">
        	<div id="lbody_header_in">View Office</div>
        </div>
        <div id="lbodycont_middle">
            <div id="lbodycont_middle_in">
            	 <?php
				if(pView($user["type"]))
				{
					?>
					<div id="tabmenu">
						<div id="tabmenu_in"><a href='createoffice.php' class='contlinkc' >Create Office</a>&nbsp;&nbsp;&nbsp;</div>
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
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr class='cont_t_header'>
                    <td width="11%" height="30" align="center" valign="middle">&nbsp;</td>
                    <td width="25%" align="center" valign="middle">Name</td>
                    <td width="26%" align="center" valign="middle">Contact</td>
                    <td width="20%" align="center" valign="middle">Phone</td>
                    <td width="18%" align="center" valign="middle">City</td>
                  </tr>
                  <tr>
                    <td colspan="6" align="center" valign="middle">
                    <div <?php echo $height; ?>>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <?php
						$query = "select * from rec_office order by datecreated desc";
						if($result = mysql_query($query))
						{
							if(($num_rows = mysql_num_rows($result))>0)
							{
								$count=1;
								while($rows=mysql_fetch_array($result))
								{
									echo "<tr><td height='27' colspan='5' align='center' valign='middle'><hr/></td></tr>
									<tr><td width='11%' align='center' valign='middle'>$count</td>
									<td width='25%' align='center' valign='middle'><a href='accountoffice_set.php?id=".base64_encode($rows["id"])."' class='contlinkb'>".stripslashes($rows["name"])."</a></td>
									<td width='26%' align='center' valign='middle'>".stripslashes($rows["contact"])."</td>
									<td width='20%' align='center' valign='middle'>".stripslashes($rows["phone"])."</td>
									<td width='18%' align='center' valign='middle'>".stripslashes($rows["city"])."</td></tr>";
						  			$count++;
								}
							}
							else
								echo "<tr class='nfound'><td height='27' colspan='5' align='center' valign='middle'>No Office Found</td></tr>";
						}
						else
							echo "<tr class='nfound'><td height='27' colspan='5' align='center' valign='middle'>No Office Found</td></tr>";
						?>
                        </table>
                      </div>
                    </td>
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
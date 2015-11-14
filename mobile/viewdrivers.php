<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
adminlogin();
redirect();
$vdriver=array();
$query = "select * from fenvdriver order by name";
if($result = mysql_query($query))
{
	if(($numrows=mysql_num_rows($result))>0)
	{
		while($row=mysql_fetch_array($result))
		{
			$vdriver[]=array("id"=>$row["id"],"name"=>stripslashes($row["name"]));
		}
	}
}
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
 <form action="" method="post" >
     <div id="message2m" name="message2m">
     &nbsp;
     <?php
     if(isset($_SESSION["fenresult"]))
     {
        echo $_SESSION["fenresult"]."<br/>";
        unset($_SESSION["fenresult"]);
     }
     ?>
     </div>
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr class='moheader'>
        <td align="center" valign="middle">Name</td>
        <td align="center" valign="middle">Email</td>
        <td align="center" valign="middle">Phone</td>
      </tr>
      <?php
						$query = "select * from fenvdriver order by name desc";
						if($result = mysql_query($query))
						{
							if(($num_rows = mysql_num_rows($result))>0)
							{
								$count=1;
								while($rows=mysql_fetch_array($result))
								{
									echo "<tr><td colspan='3' align='center' valign='middle'><hr/></td></tr><tr class='morow'><td align='center' valign='middle'><a href='accountdriver_set.php?id=".base64_encode($rows["id"])."' class='contlinkb'>".checkNA(stripslashes($rows["name"]))."</a></td><td align='center' valign='middle'>".checkNA(stripslashes($rows["email"]))."</td><td  align='center' valign='middle'>".checkNA(stripslashes($rows["phone"]))."</td></tr>";
						  			$count++;
								}
							}
							else
								echo "<tr class='nfound_m'><td height='27' colspan='3' align='center' valign='middle'>No Drviers Found</td></tr>";
						}
						else
							echo "<tr class='nfound_m'><td height='27' colspan='3' align='center' valign='middle'>No Drivers Found</td></tr>";
						?>
    </table>
</form>        
</div>
<br/><br/>
</body>
</html>
<?php
include "../include/unconfig.php";
?>
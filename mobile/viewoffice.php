<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
adminlogin();
redirect();
$user = $_SESSION["salesuser"];
$query = "select * from rec_office order by datecreated desc";
$height="style='height:500px'";
if($result = mysql_query($query))
{
	if(($num_rows = mysql_num_rows($result))>12)
		$height="";
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
      <tr class='moheader'>
        <td  align="center" valign="middle">Name</td>
         <td  align="center" valign="middle">Contact</td>
         <td align="center" valign="middle">Phone</td>
         <td  align="center" valign="middle">City</td>
      </tr>
      <?Php
	  if($result = mysql_query($query))
	  {
		  if(($num_rows=mysql_num_rows($result))>0)
		  {
			  while($row = mysql_fetch_array($result))
			  {
				echo "<tr><td height='27' colspan='4' align='center' valign='middle'><hr/></td></tr>
				<tr class='morow'>
				<td align='center' valign='middle'><a href='accountoffice_set.php?id=".base64_encode($row["id"])."' class='contlinkb'>".stripslashes($row["name"])."</a></td>
				<td  align='center' valign='middle'>".stripslashes($row["contact"])."</td>
				<td  align='center' valign='middle'>".stripslashes($row["phone"])."</td>
				<td align='center' valign='middle'>".stripslashes($row["city"])."</td></tr>";
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
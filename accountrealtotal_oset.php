<?Php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
redirect();
$userm = $_SESSION["salesuser"];
$id = base64_decode($_REQUEST["id"]);
if(!empty($id))
{
	$query = "select * from sales_an_sales where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
			$info = mysql_fetch_assoc($result);
	}
}
else
{
	$query = "select * from sales_an_sales order by id limit 1";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
			$info = mysql_fetch_assoc($result);
	}
}
if(!pView($userm["type"]))
	$disabled = "disabled='disabled'";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome To Sales Report System</title>
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script src="js/swfobject_modified.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript">
function switchrealmonth(value)
{
	if(value.length>0 && value !='na')
		window.location.href='accountrealtotal_oset.php?id='+value;
}
</script>
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
    <div id="bodycont">
    	<div id="bodycont_header">
        	<div id="body_header_in">
        	Edit Real Total
            </div>
        </div>
        <div id="bodycont_middle" >
            <div id="bodycont_middle_in">
            	           	<!--start-->
            <div style="text-align:center">Hello <b><u><?php echo $userm["username"]; ?></u></b>, To edit the real total for every month please fill up the form below.</div><br/>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <form action="save.php" method="post" onsubmit="return checkFieldtotal()">
            <input type="hidden" id="task" name="task" value="saverealtotal"/>
            <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>"/>
    	    <tr>
    	      <td colspan="2" align="center" valign="middle"><div id="message" name="message" class="black" style="text-align:center">
        &nbsp;
        		<?php
                    if(isset($_SESSION["salesresult"]))
                    {
                        echo $_SESSION["salesresult"]."<br/>";
                        unset($_SESSION["salesresult"]);
                    }
                 ?>
      </div> </td>
   	        </tr>
          <tr>
    	      <td width="33%" height="37" align="right" valign="middle">Select Month of Real Total:</td>
    	      <td width="67%" align="left" valign="middle">&nbsp;&nbsp;
              	<select id="umonth" name="umonth" onchange="switchrealmonth(this.value)">
                	<option value="na">Select Month</option>
                    <?php
					$q = "select * from sales_an_sales order by name";
					if($r=mysql_query($q))
					{
						if(($numr=mysql_num_rows($r))>0)
						{
							while($row= mysql_fetch_array($r))
							{
								if($info["id"]==$row["id"])
									echo "<option value='".base64_encode($row["id"])."' selected='selected'>".stripslashes($row["name"])."</option>";
								else
									echo "<option value='".base64_encode($row["id"])."'>".stripslashes($row["name"])."</option>";
							}
						}
					}
					?>
                </select>
              </td>
  	      </tr>
          <tr>
    	      <td height="37" align="right" valign="middle">Total To Set:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="utotal" name="utotal" size="40" value="<?Php echo $info["total"]; ?>" /></td>
  	      </tr>
          <tr>
    	      <td height="200" align="right" valign="middle" colspan="2">&nbsp;</td>
  	      </tr>
    	    <tr>
    	      <td height="47" colspan="2" align="left" valign="middle">
              <div id="message2" name="message2" class="black" style="text-align:center; padding-right:50px; padding-left:50px">
        &nbsp;
      </div>
              </td>
   	        </tr>
    	    <tr>
    	      <td colspan="2" align="center" valign="middle">
		<a href="viewgraph_r.php" onmouseover="document.cancel.src='images/cancelbtn.jpg'" onmouseout="document.cancel.src='images/cancelbtn.jpg'"><img src="images/cancelbtn.jpg"  border="0" alt="Cancel" name="cancel" /></a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="image"  src="images/savebtn.jpg" onmouseover="javascript:this.src='images/savebtn.jpg';" onmouseout="javascript:this.src='images/savebtn.jpg';">
              </td>
  	      </tr>
    	    <tr>
    	      <td colspan="2" align="left" valign="middle">&nbsp;</td>
  	      </tr>
          </form>
        </table>
                <!--end-->
            </div>
        </div>
        <div id="bodycont_footer">
        </div>
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
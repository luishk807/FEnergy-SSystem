<?Php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
redirect();
$userm = $_SESSION["salesuser"];
if(!pView($userm["type"]))
	$disabled = "disabled='disabled'";
$offices=array();
$otaken=array();
$naval=0;
$query="select distinct id from rec_office";
if($result=mysql_query($query))
{
	$num_rows=mysql_num_rows($result);
	$naval=$num_rows;
	if($num_rows>0)
	{
		while($rows=mysql_fetch_array($result))
		{
			$offices[]=$rows["id"];
		}
	}
}
if(sizeof($offices)>0)
{
	$query="select distinct office from sales_goals_office";
	if($result=mysql_query($query))
	{
		$num_rows=mysql_num_rows($result);
		if($num_rows>0)
		{
			while($rows=mysql_fetch_array($result))
			{
				$otaken[]=$rows["office"];
				for($i=0;$i<sizeof($offices);$i++)
				{
					if($offices[$i]==$rows["office"])
					{
						$naval--;
						break;
					}
				}
			}
		}
	}
}
if($naval<1)
{
	$_SESSION["salesresult"]="No Office Avaliable To Create Goal";
	header("location:viewgoalsx.php");
	exit;
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
    <div id="bodycont">
    	<div id="bodycont_header">
        	<div id="body_header_in">
        	Create Office Goals
            </div>
        </div>
        <div id="bodycont_middle" style="height:500px;"  >
            <div id="bodycont_middle_in">
            	           	<!--start-->
            <div style="text-align:center">Hello <b><u><?php echo $userm["username"]; ?></u></b>, To Create a brand new goal please fill up the form below.</div><br/>
            <div class='formmessage'>To Begin, please choose an <u>office</u> and then set a <u>goal</u>, you will be <u>taken to another page</u> to set a goal for <u>each managers and team leaders</u> based on the goal set for the office choosen.</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <form action="save.php" method="post" onsubmit="return checkFieldy()">
            <input type="hidden" id="task" name="task" value="creategoalsx"/>
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
    	      <td width="33%" height="37" align="right" valign="middle">Select Office For This Goal:</td>
    	      <td width="67%" align="left" valign="middle">&nbsp;&nbsp;
              	<select id="uoffice" name="uoffice">
                	<option value="na">Select Office</option>
                    <?php
					$q = "select * from rec_office order by name";
					if($r=mysql_query($q))
					{
						if(($numr=mysql_num_rows($r))>0)
						{
							while($row= mysql_fetch_array($r))
							{
								if(sizeof($otaken)>0)
								{
									for($i=0;$i<sizeof($otaken);$i++)
									{
										$found=false;
										if($otaken[$i]==$row["id"])
										{
											$found=true;
											break;
										}
									}
									if(!$found)
										echo "<option value='".base64_encode($row["id"])."'>".stripslashes($row["name"])."</option>";
								}
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
    	      <td height="37" align="right" valign="middle">Goal To Set:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="ugoal" name="ugoal" size="40" value="<?Php echo $user["goals"]; ?>" /></td>
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
		<a href="viewgoals.php" onmouseover="document.cancel.src='images/cancelbtn.jpg'" onmouseout="document.cancel.src='images/cancelbtn.jpg'"><img src="images/cancelbtn.jpg"  border="0" alt="Cancel" name="cancel" /></a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="image"  src="images/createbtn.jpg" onmouseover="javascript:this.src='images/createbtn.jpg';" onmouseout="javascript:this.src='images/createbtn.jpg';">
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
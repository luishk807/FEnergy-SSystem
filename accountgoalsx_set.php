<?Php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
redirect();
$id = base64_decode($_REQUEST["id"]);
$noman="";
$nomanid="";
$plinkx=false;
$plink="accountgoalsx_oset.php?id=".$_REQUEST["id"];
if(!empty($_REQUEST["plink"]))
{
	$plinkx=true;
	$plink="viewgoalsx.php";
}
if(!empty($id))
{
	$query = "select distinct userid from sales_goals_ind";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$noman=" id not in(";
			while($row=mysql_fetch_array($result))
			{
				if(empty($nomanid))
					$nomanid .="'".$row["userid"]."'";
				else
					$nomanid .=",'".$row["userid"]."'";
			}
			$noman .=$nomanid;
			$noman .=")";
		}
	}
	$query = "select * from sales_goals_office where id='".$id."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
			$info = mysql_fetch_assoc($result);
	}
}
else
{
	$_SESSION["salesresult"]="ERROR:Invalid Entry";
	header("location:home.php");
	exit;
}
$userm = $_SESSION["salesuser"];
if(!pView($userm["type"]))
	$disabled = "disabled='disabled'";
$getTotalAssinged=getTotalAssigned($info["id"]);
$goalleft=@$info["goals"]-$getTotalAssinged;
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
        	Edit Individual Goals
            </div>
        </div>
        <div id="bodycont_middle">
            <div id="bodycont_middle_in">
            	           	<!--start-->
            <div style="text-align:center">Hello <b><u><?php echo $userm["username"]; ?></u></b>, To Edit goal please fill up the form below.</div><br/>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <form action="" method="post">
            <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
            <input type="hidden" id="ogoal" name="ogoal" value="<?php echo $info["goals"]; ?>" />
            <input type="hidden" id="lgoalx" name="lgoalx" value="<?php echo $goalleft; ?>" />
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
              <td height="55" colspan="2" align="center" valign="middle"><span style="font-family:'Times New Roman'; font-size:18pt;"><hr/><u><?php echo getOfficeName($info["office"]); ?></u> Goal Settings [ <i>Goals: <?php echo $info["goals"]; ?></i> ]
              <?php
              if($plinkx)
			  {
				  ?>&nbsp;<a style='font-size:14pt; font-style:italic' href='accountgoalsx_oset.php?id=<?php echo $_REQUEST["id"]; ?>'>Edit</a>
              <?php
			  }
			  ?>
              </span>
              <br/><hr/></td>
            </tr>
              	    <tr>
              	      <td height="37" colspan="2" align="center" valign="middle"><u><span class='formmessage'>Set Goals For Team Managers And Team Leaders</span></u></td>
   	        </tr>
            <tr>
              	<td height="37" colspan="2" align="center" valign="bottom" style="font-family:'Times New Roman'; font-size:15pt; font-style:italic; text-decoration:underline;">Current Saved Goal [Assigned: <?php echo $getTotalAssinged; ?>]</td>
   	        </tr>
            <?php
			if($getTotalAssinged <1)
			{
				?>
            <tr>
              <td height="37" colspan="2" align="center" valign="middle" style="color:#F00; font-weight:bold; font-family:'Times New Roman'; font-style:italic;">***No Goal Set To Managers/Team Leaders***</td>
            </tr>
            <?php
			}
			else
			{
				//display all the rows of goals
				$qx="select * from sales_goals_ind where goalid='".$info["id"]."' order by date";
				if($rx=mysql_query($qx))
				{
					if(($numrx=mysql_num_rows($rx))>0)
					{
						$countx=0;
						while($rox=mysql_fetch_array($rx))
						{
							$in_goalleft="";
							$in_goalleft=$goalleft+$rox["goals"];
							?>
             <tr>
              <td height="37" colspan="2" align="center" valign="middle">
              Manager/Leader:&nbsp;&nbsp;
              <select id="umanager_<?php echo $countx; ?>" name="umanager_<?php echo $countx; ?>">
                    <?php
					$xnoman="";
					if(!empty($noman))
						$xnoman=" and $noman";
					$q = "select * from task_users where type in ('5','6','7','8') $xnoman order by type";
					echo "<option value='".base64_encode($rox["userid"])."' selected='selected'>".getName($rox["userid"])."</option>";
					if($r=mysql_query($q))
					{
						if(($numr=mysql_num_rows($r))>0)
						{
							while($row= mysql_fetch_array($r))
							{
								echo "<option value='".base64_encode($row["id"])."'>".stripslashes($row["name"])."</option>";
							}
						}
					}
					?>
                </select>
                &nbsp;&nbsp;&nbsp;&nbsp;Goals:<input type="text" id="ugoal_<?php echo $countx; ?>" name="ugoal_<?php echo $countx; ?>" size="10" value="<?Php echo $rox["goals"]; ?>" />&nbsp;&nbsp;<span style='font-size:12pt;'>[<a href="javascript:deleteindgoalx('<?php echo base64_encode($rox["id"]); ?>')" style='font-style:italic'>Delete</a>]&nbsp;&nbsp;[<a href="javascript:updateindgoalx('<?php echo base64_encode($rox["id"]); ?>','<?Php echo "umanager_".$countx; ?>','<?Php echo "ugoal_".$countx; ?>','<?php echo $in_goalleft; ?>')" style='font-style:italic'>Update</a>]</span>
              </td>
   	        </tr>  
                            <?php
							$countx++;
						}
					}
				}
			}
			?>
            <tr>
              <td height="20" colspan="2" align="center" valign="middle"><hr/></td>
            </tr>
           <tr>
              	<td height="37" colspan="2" align="center" valign="bottom" style="font-family:'Times New Roman'; font-size:15pt; font-style:italic; text-decoration:underline;">Assign New Goal [Left: <?php echo $goalleft; ?>]</td>
   	        </tr>
           <?php
		   if($goalleft<1)
		   {
			   ?>
             <tr>
               <td height="37" colspan="2" align="center" valign="middle" ><span style="font-family:'Times New Roman'; font-size:18pt;">No Goals Avaliable To Set<br/><span style='font-size:15pt; font-style:italic'>To Set More Goals, <a href='accountgoalsx_oset.php?id=<?php echo $_REQUEST["id"]; ?>'>Increase Amount of Goals</a></span></span></td>
             </tr>
           <?php
		   }
		   else
		   {
		   ?>
             <tr>
    	      <td height="37" colspan="2" align="center" valign="middle">Manager/Leader:&nbsp;&nbsp;
              	<select id="xumanager" name="xumanager">
                    <?php
					$xnoman="";
					if(!empty($noman))
						$xnoman=" and $noman";
					$q = "select * from task_users where type in ('5','6','7','8') $xnoman order by type";
					if($r=mysql_query($q))
					{
						if(($numr=mysql_num_rows($r))>0)
						{
							while($row= mysql_fetch_array($r))
							{
								echo "<option value='".base64_encode($row["id"])."'>".stripslashes($row["name"])."</option>";
							}
						}
					}
					?>
                </select>
                &nbsp;&nbsp;&nbsp;&nbsp;Goals:<input type="text" id="xugoal" name="xugoal" size="10" value="" />&nbsp;&nbsp;<span style='font-size:12pt;'>[<a href='javascript:creategoalx()' style='font-style:italic'>Save</a>]</span>            
                </td>
   	        </tr>
          <?php
		   }
		   ?>
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
		<a href="<?php echo $plink; ?>" onmouseover="document.cancel.src='images/cancelbtn.jpg'" onmouseout="document.cancel.src='images/cancelbtn.jpg'"><img src="images/cancelbtn.jpg"  border="0" alt="Cancel" name="cancel" /></a>
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
<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
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
 <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
 <input type="hidden" id="ogoal" name="ogoal" value="<?php echo $info["goals"]; ?>" />
 <input type="hidden" id="lgoalx" name="lgoalx" value="<?php echo $goalleft; ?>" />
     <div id="message" name="message">
     &nbsp;
     <?php
     if(isset($_SESSION["salesresult"]))
     {
        echo $_SESSION["salesresult"]."<br/>";
        unset($_SESSION["salesresult"]);
     }
     ?>
     </div>
     <div id="mbodyb">
     <!--start-->
     <div style="text-align:center">Hello <b><u><?php echo $userm["username"]; ?></u></b>, To Edit goal please fill up the following form.</div><br/>
     <div  style="text-align:center">
     <hr/>
     	<span style="font-family:'Times New Roman'; font-size:35pt;"><u><b><?php echo getOfficeName($info["office"]); ?></b></u> <br/>Goal Settings [ <i>Goals: <?php echo $info["goals"]; ?></i> ]
        <?php
              if($plinkx)
			  {
				  ?>&nbsp;<a style='font-size:28pt; font-style:italic' href='accountgoalsx_oset.php?id=<?php echo $_REQUEST["id"]; ?>'>Edit</a>
              <?php
			  }
			  ?>
        </span>
         <hr/>
     </div>
      <br/>
      <u><span class='formmessage' style="font-size:29pt; font-weight:bold">Set Goals For <br/>Team Managers And Team Leaders</span></u>
      <br/><br/>
      <div style="font-family:'Times New Roman'; font-size:29pt; font-style:italic; text-decoration:underline;">
      	Current Saved Goal [Assigned: <?php echo $getTotalAssinged; ?>]
      </div>
       <?php
		if($getTotalAssinged <1)
		{
		?>
      <div style="color:#F00; font-weight:bold; font-family:'Times New Roman'; font-style:italic; font-size:29pt;">***No Goal Set To Managers/Team Leaders***</div>
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
              <hr/>
              <span id="mform_q">Manager/Leader</span>
              <select id="umanager_<?php echo $countx; ?>" name="umanager_<?php echo $countx; ?>" class='moselect'>
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
                &nbsp;&nbsp;&nbsp;&nbsp;<span id="mform_q">Goals:</span><input type="text" id="ugoal_<?php echo $countx; ?>" name="ugoal_<?php echo $countx; ?>" size="50" class='mobiletext_p2' value="<?Php echo $rox["goals"]; ?>" /><br/><span style='font-size:20pt;'>Action >>[<a href="javascript:deleteindgoalx('<?php echo base64_encode($rox["id"]); ?>')" style='font-style:italic'>Delete</a>]&nbsp;&nbsp;[<a href="javascript:updateindgoalx('<?php echo base64_encode($rox["id"]); ?>','<?Php echo "umanager_".$countx; ?>','<?Php echo "ugoal_".$countx; ?>','<?php echo $in_goalleft; ?>')" style='font-style:italic'>Update</a>]</span><br/>
                            <?php
							$countx++;
						}
					}
				}
		}
		?>
        <br/>
        <hr/>
        <hr/>
        <br/>
        <div style="font-family:'Times New Roman'; font-size:29pt; font-style:italic; text-decoration:underline; font-weight:bold">
        	Assign New Goal [Left: <?php echo $goalleft; ?>]
        </div>
        <?php
		if($goalleft<1)
		{
		?>
           <div style="text-align:center"><span style="font-family:'Times New Roman'; font-size:29pt;">
           	No Goals Avaliable To Set<br/><span style='font-size:25pt; font-style:italic'>To Set More Goals, <a href='accountgoalsx_oset.php?id=<?php echo $_REQUEST["id"]; ?>'>Increase Amount of Goals</a></span></span>
           </div>
        <?php
		}
		else
		{
		?>
        <span id="mform_q">Manager/Leader</span>
        <select id="xumanager" name="xumanager" class='moselect'>
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
        &nbsp;&nbsp;&nbsp;&nbsp;<span id="mform_q">Goals:</span><input type="text" id="xugoal" name="xugoal" size="50" class='mobiletext_p2' value="<?Php echo $rox["goals"]; ?>" />
        	<br/>
        	<span style='font-size:20pt;'>Action >>[<a href='javascript:creategoalx()' style='font-style:italic'>Save</a>]</span> 
        <?php
		}
		?>
        <br/><br/>
        <div id="message2" name="message2" class="black" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
         <br/>
         <a href="<?php echo $plink; ?>" onmouseover="document.cancel.src='../images/cancelbtnm.png'" onmouseout="document.cancel.src='../images/cancelbtnm.png'"><img src="../images/cancelbtnm.png"  border="0" alt="Cancel" name="cancel" /></a>
          <br/><br/>
     <!--end-->
     </div>
</form>        
</div>
</body>
</html>
<?php
include "../include/unconfig.php";
?>
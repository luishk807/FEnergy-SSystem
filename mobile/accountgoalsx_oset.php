<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
adminlogin();
redirect();
$userm = $_SESSION["salesuser"];
$id = base64_decode($_REQUEST["id"]);
if(!empty($id))
{
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
if(!pView($userm["type"]))
	$disabled = "disabled='disabled'";
$otaken=array();
$query="select distinct office from sales_goals_office";
if($result=mysql_query($query))
{
	$num_rows=mysql_num_rows($result);
	if($num_rows>0)
	{
		while($rows=mysql_fetch_array($result))
		{
			$otaken[]=$rows["office"];
		}
	}
}
$totalused=getTotalAssigned($id);
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
 <form action="../save.php" method="post" onsubmit="return checkFieldv()" >
<input type="hidden" id="task" name="task" value="savegoalsx"/>
<input type="hidden" id="dall" name="dall" value="no"/>
 <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>"/>
 <input type="hidden" id="cgoal" name="cgoal" value="<?php echo $info["goals"]; ?>"/>
 <input type="hidden" id="goalused" name="goalused" value="<?php echo $totalused; ?>"/>
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
      <span id="mform_q">Select Office:</span>
      <br/>
      <select id="uoffice" name="uoffice" class='moselect'>
       <option value="na">Select Office</option>
        <?php
			$q = "select * from rec_office order by name";
			if($r=mysql_query($q))
			{
				if(($numr=mysql_num_rows($r))>0)
				{
					while($row= mysql_fetch_array($r))
					{
						if($info["office"]==$row["id"])
							echo "<option value='".base64_encode($row["id"])."' selected='selected'>".stripslashes($row["name"])."</option>";
						else
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
			}
		?>
      </select>
      <br/>
      <br/>
  	   <span id="mform_q">Goal To Set:</span>
       <br/>
       <input type="text" id="ugoal" name="ugoal"size="100" class='mobiletext' value="<?Php echo $info["goals"]; ?>" />
       <br/><br/>
       <div style="text-align:center; font-size:30pt;">
       		<a href='accountgoalsx_set.php?id=<?php echo $_REQUEST["id"]; ?>'>Go To Set Goals To Manager/Team Leaders</a>
       </div>
       <div id="message2" name="message2" class="black" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
         <br/>
         <a href="viewgoalsx.php" onmouseover="document.cancel.src='../images/cancelbtnm.png'" onmouseout="document.cancel.src='../images/cancelbtnm.png'"><img src="../images/cancelbtnm.png"  border="0" alt="Cancel" name="cancel" /></a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         <?Php
          if(pView($userm["type"]))
          {
          ?>
          <a href="Javascript:deletetask('deletegoalx','<?php echo $_REQUEST["id"]; ?>')" onmouseover="document.delete.src='../images/deletebtnm.png'" onmouseout="document.delete.src='../images/deletebtnm.png'"><img src="../images/deletebtnm.png"  border="0" alt="Delete This Office" name="delete" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <?php
           }
          ?>
              <input type="image"  src="../images/savebtnm.png" onmouseover="javascript:this.src='../images/savebtnm.png';" onmouseout="javascript:this.src='../images/savebtnm.png';">
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
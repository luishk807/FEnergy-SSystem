<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
adminlogin();
redirect();
$id = base64_decode($_REQUEST["id"]);
if(!empty($id))
{
	$query = "select * from sales_goals where id='".$id."'";
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
 <form action="../save.php" method="post" onsubmit="return checkFieldx()" >
 <input type="hidden" id="task" name="task" value="savegoals"/>
 <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
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
      <span id="mform_q">Select User:</span>
      <br/>
      <select id="umanager" name="umanager" class='moselect'>
       <option value="na">Select User</option>
        <?php
			$q = "select * from task_users where type in ('5','6','7','8') order by type";
			if($r=mysql_query($q))
			{
				if(($numr=mysql_num_rows($r))>0)
				{
					while($row= mysql_fetch_array($r))
					{
						if($info["userid"]==$row["id"])
						echo "<option value='".base64_encode($row["id"])."' selected='selected'>".stripslashes($row["name"])."</option>";
						else
						echo "<option value='".base64_encode($row["id"])."'>".stripslashes($row["name"])."</option>";
					}
				}
			}
		?>
      </select>
      <br/>
      <br/>
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
						echo "<option value='".base64_encode($row["id"])."'>".stripslashes($row["name"])."</option>";
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
              <div id="message2" name="message2" class="black" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
         <br/>
         	 <?php
			  if(pView($userm["type"]))
			  {
				  ?>
      <a href="Javascript:deletetask('goals','<?php echo $_REQUEST["id"]; ?>')" onmouseover="document.delete.src='../images/deletebtnm.png'" onmouseout="document.delete.src='../images/deletebtnm.png'"><img src="../images/deletebtnm.png"  border="0" alt="Delete Goals" name="delete" /></a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
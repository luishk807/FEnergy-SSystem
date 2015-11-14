<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
$cx=$_REQUEST["cx"];
if(detectAgent())
{
	header("location:../fpass_r.php?cx=".$cx);
	exit;
}
date_default_timezone_set('America/New_York');
$today=date('Y-m-d');
if(empty($cx))
{
	$_SESSION["loginresult"]="ERROR:Invalid Entry";
	header("location:index.php");
	exit;
}
else
{
	$query="select * from task_users where fpass_code='".clean($cx)."' and fpass_date='".$today."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
			$info=mysql_fetch_assoc($result);
		else
		{
			$_SESSION["loginresult"]="ERROR:Password Reset Invalid or Expired";
			header("location:index.php");
			exit;
		}
	}
	else
	{
		$_SESSION["loginresult"]="ERROR: System Failure, Unable To Continue";
		header("location:index.php");
		exit;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome To Family Energy Sales Report System</title>
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
include "include/header_s.php";
?>
<div id="mbody_home">
	<div style="text-align:center">Hello <b><u><?Php echo $info["username"]; ?></u></b>, to reset your password please type your new password.</div>
    <br/><br/>
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
    <div style="text-align:center">
    <form method="post" action="../fpass.php" onsubmit="return checkField_fp2()">
        <input type="hidden" id="ctx" name="ctx" value="<?Php echo md5("reset"); ?>" />
        <input type="hidden" id="cx" name="cx" value="<?Php echo $_REQUEST["cx"]; ?>" />
        <span id="mform_q">New Password</span><br/>
         <input type="password" name="fpass" id="fpass" size="100" class='mobiletext'/>
        <br/><br/>Or<br/><br/>
        <span id="mform_q">Re-Type Your Password</span><br/>
        <input type="password" name="rfpass" id="rfpass" size="100" class='mobiletext' />
        <br/><br/>
         <div id="message2" name="message2" class="black" style="text-align:center; padding-right:50px; padding-left:50px">
         &nbsp;
        </div>
       <br/>
       <a href="index.php"><img src="../images/cancelbtnm.png" border="0" alt="Cancel" /></a>
     	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       <input type="image"  src="../images/submitbtnm.png" onmouseover="javascript:this.src='../images/submitbtnm.png';" onmouseout="javascript:this.src='../images/submitbtnm.png';">
     </form>
   </div>
</div>
</body>
</html>
<?php
include "../include/unconfig.php";
?>
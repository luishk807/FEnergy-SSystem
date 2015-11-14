<?php
session_start();
include "include/config.php";
include "include/function.php";
$cx=$_REQUEST["cx"];
if(!detectAgent())
{
	header("location:mobile/fpass_r.php?cx=".$cx);
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
	<!--header-->
<?php
include "include/header_s.php";
?>
    <!--end of header-->
    <div id="lbodycont">
    	<div id="lbodycont_header">
        	<div id="lbody_header_in">Reset Your Password</div>
        </div>
        <div id="lbodycont_middle">
            <div id="lbodycont_middle_in" style="height:600px;">
            	<div style="text-align:center">Hello <b><u><?Php echo $info["username"]; ?></u></b>, to reset your password please type your new password.</div><br/>
                <div id="message" name="message" class="black" style="text-align:center">
                    <?php
                       if(isset($_SESSION["salesresult"]))
                       {
                          echo $_SESSION["salesresult"]."<br/>";
                          unset($_SESSION["salesresult"]);
                       }
                    ?>
                  </div>
                  <div style="text-align:center">
                <form method="post" action="fpass.php" onsubmit="return checkField_fp2()">
                <input type="hidden" id="ctx" name="ctx" value="<?Php echo md5("reset"); ?>" />
                <input type="hidden" id="cx" name="cx" value="<?Php echo $_REQUEST["cx"]; ?>" />
                <h3>New Password</h3>
                <input type="password" name="fpass" id="fpass" size="80" />
               	<br/>
                 <h3>Re-Type Your Password</h3>
                <input type="password" name="rfpass" id="rfpass" size="80" />
                <br/><br/><br/><br/><br/>
                <div id="message2" name="message2" class="black" style="text-align:center">
                &nbsp;
              </div>
              <br/>
                <a href="index.php"><img src="images/cancelbtn.jpg" border="0" alt="Cancel" /></a>
     			 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="image"  src="images/submitbtn.jpg" onmouseover="javascript:this.src='images/submitbtn.jpg';" onmouseout="javascript:this.src='images/submitbtn.jpg';">
                </form>
                </div>
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
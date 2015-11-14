<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
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
	<div style="text-align:center">Hello; to begin, please provide the email address used to create<br/> your Family Energy System Account</div>
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
    <form method="post" action="../fpass.php" onsubmit="return checkField_fp1()">
        <input type="hidden" id="ctx" name="ctx" value="<?Php echo md5("create"); ?>" />
        <span id="mform_q">Email Address</span><br/>
         <input type="text" name="femail" id="femail" size="100" class='mobiletext'/>
        <br/><br/>Or<br/><br/>
        <span id="mform_q">Username</span><br/>
        <input type="text" name="uname" id="uname" size="100" class='mobiletext' />
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
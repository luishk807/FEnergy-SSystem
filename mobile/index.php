<?Php
session_start();
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
<div  style="text-align:center">
  <img src="../images/logobig.png" border="0" />
</div>
<div id="loginheaderb" style="background:#00477f;color:#FFF; height:80px; text-align:center; font-size:25pt; font-family:'rockw'">
	<div id="loginheaderb_in" style="padding-top:20px;">
    	Sales Report System
    </div>
</div>
<div id="mlogincont" style="text-align:center; padding-top:80px;">
 <form action="../login.php" method="post" onsubmit="return checkField_m()" >
     <div id="message2m" name="message2m">
     &nbsp;
     <?php
     if(isset($_SESSION["loginresult"]))
     {
        echo $_SESSION["loginresult"]."<br/>";
        unset($_SESSION["loginresult"]);
     }
     ?>
     </div>
     <span style="font-family:'agentfb'; color:#000;font-size:25pt;">Username:</span>
     <br/>
     <input type="text" size="100" id="uname" name="uname" class='mobiletext'/>
     <br/>
     <br/><br/>
     <span style="font-family:'agentfb'; color:#000;font-size:25pt">Password:</span>
     <br/>
      <input type="password" size="100" id="upass" name="upass" class='mobiletext' />
     <br/><br/><br/>
     <a href='fpassword.php'><img src="../images/fpasswordm.png" border="0"alt="Forget Password" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="image"  src="../images/loginbtnm.png" onmouseover="javascript:this.src='../images/loginbtnm.png';" onmouseout="javascript:this.src='../images/loginbtnm.png';">
</form>        
</div>
</body>
</html>
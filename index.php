<?php 
session_start();
include "include/function.php";
if(!detectAgent())
{
	header("location:mobile/");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<link rel="icon" type="image/png" href="images/favicon.ico">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="css/styleie.css" />
<![endif]-->
<link rel="stylesheet" type="text/css" href="css/style.css" />
<title>Welcome To Sales Report System</title>
</head>

<body>
<div id="main_cont">
	<div id="logincont">
    	<div id="fpassword"><a href='fpassword.php'><img src="images/fpassword.png" border="0"alt="Forget Password" /></a></div>
    	<div id="loginpanel">
       		 <div id="form">
                    <form action="login.php" method="post" onsubmit="return checkField()">
                    <div id="questions_in">
                    <input type="text" size="40" id="uname" name="uname" />
                    	<div id="form_spacer"></div>
                      <input type="password" size="40" id="upass" name="upass" />
               		</div>
                    <div id="form_spacerb"></div>
          			<div id="message2" name="message2" class="black_home">
                        &nbsp;
                          <?php
                    if(isset($_SESSION["loginresult"]))
                    {
                        echo $_SESSION["loginresult"];
                        unset($_SESSION["loginresult"]);
                    }
                 ?>
                      </div>
                      <div id="home_button">
                        <input type="image"  src="images/loginbtnm.jpg" onmouseover="javascript:this.src='images/loginbtnm.jpg';" onmouseout="javascript:this.src='images/loginbtnm.jpg';">
                        </div>
                    </form>
            </div>
        </div>
    </div>
    <div class="clearfooter"></div>
</div>
<?Php
include "include/footer.php";
?>
</body>
</html>
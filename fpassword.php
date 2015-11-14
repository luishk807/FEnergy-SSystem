<?php
session_start();
include "include/config.php";
include "include/function.php";
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
        	<div id="lbody_header_in">Forgot Password Page</div>
        </div>
        <div id="lbodycont_middle">
            <div id="lbodycont_middle_in">
            	<div style="text-align:center">Hello; to begin, please provide the email address used to create<br/> your Family Energy System Account</div>
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
                    <form method="post" action="fpass.php" onsubmit="return checkField_fp1()">
                    <input type="hidden" id="ctx" name="ctx" value="<?Php echo md5("create"); ?>" />
                    <h3>Email Address</h3>
                    <input type="text" name="femail" id="femail" size="80" />
                    <br/><br/>Or
                     <h3>Username</h3>
                    <input type="text" name="uname" id="uname" size="80" />
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
            <div style="height:100px"></div>
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
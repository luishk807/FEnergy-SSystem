<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
adminlogin();
redirect();
$userm = $_SESSION["salesuser"];
$id = base64_decode($_REQUEST["id"]);
$query = "select * from sales_agent where id='".clean($id)."'";
if($result = mysql_query($query))
{
	if(($num_rows= mysql_num_rows($result))>0)
		$user = mysql_fetch_assoc($result);
}
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
          <div style="text-align:center">Hello <b><u><?php echo $userm["username"]; ?></u></b>, Use this page is edit information for this Agent.</div><br/>
          <form action="../save.php" method="post" onsubmit="return checkFieldd_a()">
            <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
            <input type="hidden" id="task" name="task" value="saved"/>
            <input type="hidden" id="cphone" name="cphone" value="" />
    	    <div id="message" name="message" class="black" style="text-align:center">
        &nbsp;
        <?php
                    if(isset($_SESSION["fenresult"]))
                    {
                        echo $_SESSION["fenresult"];
                        unset($_SESSION["fenresult"]);
                    }
                 ?>
      </div> 
      <span id="mform_q">Name:</span><br/>
      <input type="text" id="realname" name="realname" size="100" class='mobiletext' value="<?Php echo $user["name"]; ?>" />
  	   <br/>
      <br/>
      <div id="message2" name="message2" class="black" style="text-align:center; padding-right:50px; padding-left:50px">
        &nbsp;
      </div>
      <br/><br/>
              <?php
			  if(pView($userm["type"]))
			  {
				  ?>
      <a href="Javascript:deletetask('agent','<?php echo $_REQUEST["id"]; ?>')" onmouseover="document.delete.src='../images/deletebtnm.png'" onmouseout="document.delete.src='../images/deletebtnm.png'"><img src="../images/deletebtnm.png"  border="0" alt="Delete User" name="delete" /></a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      		<?php
			  }
			  ?>
              <input type="image"  src="../images/savebtnm.png" onmouseover="javascript:this.src='../images/savebtnm.png';" onmouseout="javascript:this.src='../images/savebtnm.png';">
<br/><br/>
          </form>     
</div>
</body>
</html>
<?php
include "../include/unconfig.php";
?>
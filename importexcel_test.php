<?Php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
//redirect();
$user = $_SESSION["salesuser"];
if(isset($_SESSION["prevlink"]))
	$prevlink = $_SESSION["prevlink"];
else
	$prevlink = "";
date_default_timezone_set('America/New_York');
$xdate = date("Y-m-d");
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
	<?php
	include "include/header.php";
	?>
    <div id="bodycont">
    	<div id="bodycont_header">
        	<div id="body_header_in">
        	Import A Spreadsheet
            </div>
        </div>
        <div id="bodycont_middle" >
            <div id="bodycont_middle_in">
            	           	<!--start-->
            <div style="text-align:center">Hello <b><u><?php echo $user["username"]; ?></u></b>, To save an brand new Excel Spreasheet please fill the form.</div><br/>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <form enctype="multipart/form-data" action="readexcel_test.php" accept-charset="UTF-8" method="post" onsubmit="return checkField_excel();">
  <input type="hidden" name="MAX_FILE_SIZE" value="2000000"/>
            <input type="hidden" id="task" name="task" value="createexcel"/>
    	    <tr>
    	      <td colspan="2" align="center" valign="middle"><div id="message" name="message" class="black" style="text-align:center">
        &nbsp;
        <?php
                    if(isset($_SESSION["salesresult"]))
                    {
                        echo $_SESSION["salesresult"]."<br/>";
                        unset($_SESSION["salesresult"]);
                    }
                 ?>
      </div> </td>
   	        </tr>
    	    <tr>
    	      <td width="27%" height="37" align="right" valign="middle">Choose File:</td>
    	      <td align="left" valign="middle" width="73%">&nbsp;&nbsp;<input type="file" name="file" id="file" size="60" /></td>
  	      </tr>
    	    <tr>
    	      <td height="200" colspan="2" align="right" valign="middle"></td>
   	        </tr>
    	    <tr>
    	      <td height="47" colspan="2" align="left" valign="middle">
              <div id="message2" name="message2" class="black" style="text-align:center; padding-right:50px; padding-left:50px">
        &nbsp;
      </div>
              </td>
   	        </tr>
    	    <tr>
    	      <td colspan="2" align="center" valign="middle">
              <a href="home.php" onmouseover="document.cancel.src='images/cancelbtn.jpg'" onmouseout="document.cancel.src='images/cancelbtn.jpg'"><img src="images/cancelbtn.jpg"  border="0" alt="Cancel and return to View Page" name="cancel" /></a>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="image" id="importbtn" name="importbtn" src="images/importbtn.jpg">
              </td>
  	      </tr>
    	    <tr>
    	      <td colspan="2" align="left" valign="middle">&nbsp;</td>
  	      </tr>
          </form>
        </table>
                <!--end-->
            </div>
        </div>
        <div id="bodycont_footer">
        </div>
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
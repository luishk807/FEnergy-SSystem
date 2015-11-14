<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
adminlogin();
redirect();
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
 <form action="../save.php" method="post" onsubmit="return checkFieldi()">
 <input type="hidden" id="task" name="task" value="createoffice"/>
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
     <div style="text-align:center">Hello <b><u><?php echo $userm["username"]; ?></u></b>, To Create a brand new office please fill up the following form.</div><br/>
      <span id="mform_q">Name:<br/><span style='color:#b0b0b0; font-size:30pt; font-style:italic'>(e.g. Manhattan Office)</span></span>
      <br/>
      <input type="text" id="oname" name="oname"  value="" class='moselect'/>
      <br/>
      <br/>
      <span id="mform_q">Contact:</span>
      <br/>
      <input type="text" id="ocontact" name="ocontact" value="" class='moselect'/>
      <br/>
      <br/>
 <span id="mform_q">Service Email:</span>
      <br/>
      <input type="text" id="oemail" name="oemail" value="" class='moselect'/>
      <br/>
      <br/>
      <span id="mform_q">Phone:</span>
      <br/>
      <input type="text" id="ophone" name="ophone" value="" class='moselect'/>
      <br/>
      <br/>
      <span id="mform_q">Fax:</span>
      <br/>
      <input type="text" id="ofax" name="ofax" value="" class='moselect'/>
      <br/>
      <br/>
      <span id="mform_q">Range of Days Avaliable:</span>
      <br/>
      <input type="text" id="odays" name="odays" value="" class='moselect'/>
      <br/>
      <br/>
      <span id="mform_q">Range of Hours Avaliable:</span>
      <br/>
      <input type="text" id="ohours" name="ohours" value="" class='moselect'/>
      <br/>
      <br/>
      <span id="mform_q">Address:</span>
      <br/>
      <input type="text" id="oaddress" name="oaddress" value="" class='moselect'/>
      <br/>
      <br/>
      <span id="mform_q">City:</span>
      <br/>
      <input type="text" id="ocity" name="ocity" value="" class='moselect'/>
      <br/>
      <br/>
      <span id="mform_q">State:</span>
      <br/>
      <input type="text" id="ostate" name="ostate" value="" class='moselect'/>
      <br/>
      <br/>
       <span id="mform_q">Country:</span>
      <br/>
      <input type="text" id="ocountry" name="ocountry" value="USA" class='moselect'/>
      <br/>
      <br/>
      <hr/>
      <br/><br/>
       <span id="mform_q">Driving Directions:</span>
      <br/>
      <textarea id="odriving" name="odriving" cols="50" rows="10" size="100" class='mobiletextare'></textarea>
      <br/>
      <br/>
       <span id="mform_q">Walking Directions:</span>
      <br/>
      <textarea id="owalking" name="owalking" cols="50" rows="10" size="100" class='mobiletextare'></textarea>
      <br/>
      <br/>
       <br/><br/>
              <div id="message2" name="message2" class="black" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
         <br/>
              <input type="image"  src="../images/createbtnm.png" onmouseover="javascript:this.src='../images/createbtnm.png';" onmouseout="javascript:this.src='../images/createbtnm.png';">
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
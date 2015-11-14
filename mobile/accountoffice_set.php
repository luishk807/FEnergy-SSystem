<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
adminlogin();
redirect();
$user = $_SESSION["salesuser"];
if(!pView($user["type"]))
	$disabled = "disabled='disabled'";
$id = base64_decode($_REQUEST["id"]);
if(empty($id))
{
	$_SESSION["salesresult"]="Invalid Office, please choose an office";
	header('location:viewoffice.php');
	exit;
}
else
{
	$query = "select * from rec_office where id='$id'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
			$userm = mysql_fetch_assoc($result);
		else
		{
			$_SESSION["salesresult"]="Invalid Office, please choose an office";
			header('location:viewoffice.php');
			exit;
		}
	}
	else
	{
		$_SESSION["recresult"]="Invalid Office, please choose an office";
		header('location:viewoffice.php');
		exit;
	}
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
 <form action="../save.php" method="post" onsubmit="return checkFieldi()">
   <input type="hidden" id="task" name="task" value="saveoffice"/>
    <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>"/>
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
     <div style="text-align:center">Hello <b><u><?php echo $user["username"]; ?></u></b>, To Edit Office please fill up the following form.</div><br/>
      <span id="mform_q">Name:<br/><span style='color:#b0b0b0; font-size:30pt; font-style:italic'>(e.g. Manhattan Office)</span></span>
      <br/>
      <input type="text" id="oname" name="oname"  value="<?php echo stripslashes($userm["name"]); ?>"  class='moselect'/>
      <br/>
      <br/>
      <span id="mform_q">Contact:</span>
      <br/>
      <input type="text" id="ocontact" name="ocontact" value="<?Php echo stripslashes($userm["contact"]); ?>" class='moselect'/>
      <br/>
      <br/>
 <span id="mform_q">Service Email:</span>
      <br/>
      <input type="text" id="oemail" name="oemail" value="<?Php echo stripslashes($userm["email"]); ?>" class='moselect'/>
      <br/>
      <br/>
      <span id="mform_q">Phone:</span>
      <br/>
      <input type="text" id="ophone" name="ophone" value="<?Php echo stripslashes($userm["phone"]); ?>" class='moselect'/>
      <br/>
      <br/>
      <span id="mform_q">Fax:</span>
      <br/>
      <input type="text" id="ofax" name="ofax" value="<?Php echo stripslashes($userm["fax"]); ?>" class='moselect'/>
      <br/>
      <br/>
      <span id="mform_q">Range of Days Avaliable:</span>
      <br/>
      <input type="text" id="odays" name="odays" value="<?Php echo stripslashes($userm["days"]); ?>" class='moselect'/>
      <br/>
      <br/>
      <span id="mform_q">Range of Hours Avaliable:</span>
      <br/>
      <input type="text" id="ohours" name="ohours" value="<?Php echo stripslashes($userm["hours"]); ?>" class='moselect'/>
      <br/>
      <br/>
      <span id="mform_q">Address:</span>
      <br/>
      <input type="text" id="oaddress" name="oaddress" value="<?Php echo stripslashes($userm["address"]); ?>" class='moselect'/>
      <br/>
      <br/>
      <span id="mform_q">City:</span>
      <br/>
      <input type="text" id="ocity" name="ocity" value="<?Php echo stripslashes($userm["city"]); ?>" class='moselect'/>
      <br/>
      <br/>
      <span id="mform_q">State:</span>
      <br/>
      <input type="text" id="ostate" name="ostate" value="<?Php echo stripslashes($userm["state"]); ?>" class='moselect'/>
      <br/>
      <br/>
       <span id="mform_q">Country:</span>
      <br/>
      <input type="text" id="ocountry" name="ocountry" value="<?Php echo stripslashes($userm["country"]); ?>" class='moselect'/>
      <br/>
      <br/>
      <span id="mform_q">Zip/Postal Code::</span>
      <br/>
      <input type="text" id="ozip" name="ozip" value="<?Php echo stripslashes($userm["zip"]); ?>" class='moselect'/>
      <br/>
      <br/>
      <hr/>
      <br/><br/>
       <span id="mform_q">Driving Directions:</span>
      <br/>
      <textarea id="odriving" name="odriving" cols="50" rows="10" size="100" class='mobiletextare'><?Php echo htmlentities(stripslashes($userm["idrive"])); ?></textarea>
      <br/>
      <br/>
       <span id="mform_q">Walking Directions:</span>
      <br/>
      <textarea id="owalking" name="owalking" cols="50" rows="10" size="100" class='mobiletextare'><?Php echo htmlentities(stripslashes($userm["iwalk"])); ?></textarea>
      <br/>
      <br/>
       <br/><br/>
              <div id="message2" name="message2" class="black" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
         <br/>
         <?Php
              if(pView($user["type"]))
              {
                ?>
              <a href="Javascript:deletetask('office','<?php echo $_REQUEST["id"]; ?>')" onmouseover="document.delete.src='../images/deletebtnm.png'" onmouseout="document.delete.src='../images/deletebtnm.png'"><img src="../images/deletebtnm.png"  border="0" alt="Delete This Office" name="delete" /></a>&nbsp;&nbsp;&nbsp;
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
<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
adminlogin();
//redirect();
$user=$_SESSION["salesuser"];
$mopen="close";
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
include "include/header.php";
?>
<div id="mbody_home">
     <div id="message2m" name="message2m">
     &nbsp;
     <?php
     if(isset($_SESSION["salesresult"]))
     {
        echo $_SESSION["salesresult"]."<br/>";
        unset($_SESSION["salesresult"]);
     }
     ?>
     </div>
    <div id="divbutton">
    	<div id="divbutton_in">
    		<a href='viewreport.php'>View Report</a>
         </div>
    </div>
   	<br/>
    <div id="divbutton">
    	<div id="divbutton_in">
    		<a href='createreport.php'>Add Report</a>
         </div>
    </div>
    <br/>
    <!--<div id="divbutton">
    	<div id="divbutton_in">
    		<a href='account.php'>My Account</a>
         </div>
    </div>
    <br/>
    <div id="divbutton">
    	<div id="divbutton_in">
    		<a href='createvehicle.php'>Create Vehicles</a>
         </div>
    </div>
    <br/>-->
    <?php
	if(pView($user["type"]))
	{
		?>
    <div id="divbutton">
    	<div id="divbutton_in">
    		<a href='viewusers.php'>View Users</a>
         </div>
    </div>
    <br/>
    <div id="divbutton">
    	<div id="divbutton_in">
    		<a href='createuser.php'>Create Users</a>
         </div>
    </div>
    <br/>
   <div id="divbutton">
    	<div id="divbutton_in">
    		<a href='creategoalsx.php'>Create Goals</a>
         </div>
    </div>
    <br/>
    <div id="divbutton">
    	<div id="divbutton_in">
    		<a href='viewgoalsx.php'>View Goals</a>
         </div>
    </div>
    <br/>
    <div id="divbutton">
    	<div id="divbutton_in">
    		<a href='viewoffice.php'>View Office</a>
         </div>
    </div>
    <br/>
    <div id="divbutton">
    	<div id="divbutton_in">
    		<a href='createoffice.php'>Create Office</a>
         </div>
    </div>
    <?Php
	}
	?>
    <br/><br/>
</div>
</body>
</html>
<?php
include "../include/unconfig.php";
?>
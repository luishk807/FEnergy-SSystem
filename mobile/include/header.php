<div id="header_img">
  <img src="../images/logobig.png" border="0" />
</div>
<div id="loginheaderb">
	<div id="loginheaderb_in" style="padding-top:20px;">
    	Sales Report System
    </div>
</div>
<div id="header_menu">
<?php
$ux = $_SESSION["salesuser"];
if($mopen !="close" && pView($ux["type"]))
{
	?>
    <a class='contlinkc' href='viewgoalsx.php'>Goals</a>&nbsp;&nbsp;
<?Php
}
?>
<a class='contlinkc' href='home.php'>Main Menu</a>&nbsp;&nbsp;
<a class='contlinkc' href='account.php'>My Account</a>&nbsp;&nbsp;
<a class='contlinkc' href='../logout.php'>Logout</a>&nbsp;
</div>
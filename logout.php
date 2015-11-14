<?Php
session_start();
include "include/function.php";
unset($_SESSION["salesuser"]);
$_SESSION["loginresult"]="You Are Logout Successfully";
if(!detectAgent())
	header("location:mobile/");
else
	header('location:index.php');
exit;
?>
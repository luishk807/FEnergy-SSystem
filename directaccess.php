<?php
session_start();
include "include/config.php";
include "include/function.php";
$agent=$_REQUEST["aid"];
$uid=base64_decode($_REQUEST["uid"]);
$task=$_REQUEST["task"];
$oid=$_REQUEST["oid"];
$date_detail=$_REQUEST["date_detail"];
$query = "select * from task_users where id='".$uid."'";
if($result = mysql_query($query))
{
	if(($num_rows = mysql_num_rows($result))>0)
	{
		$row = mysql_fetch_assoc($result);
		$user = array("id"=>$row["id"], "name"=>stripslashes($row["name"]),"username"=>stripslashes($row["username"]),"password"=>stripslashes($row["password"]),"email"=>stripslashes($row["email"]),'title'=>$row["title"],"status"=>$row["status"],"type"=>$row["type"],"phone"=>stripslashes($row["phone"]),'office'=>$row["office"],'report_to'=>$row["report_to"],'acode'=>stripslashes($row["acode"]));
		$_SESSION["salesuser"]=$user;
		header('location:viewreport_r.php?id='.$agent.'&task='.$task."&oid=".$oid."&date_detail=".$date_detail);
		exit;
	}
}
else
{
	$_SESSION["loginresult"]="Invalid Entry";
	unset($_SESSION["salesuser"]);
	header("location:index.php");
	exit;
}
include "include/unconfig.php";
?>
<?php
session_start();
include "include/config.php";
include "include/function.php";
if(empty($_SERVER['HTTP_REFERER']))
{
	$_SESSION["loginresult"]="Illegal entry detected";
	header("location:index.php");
	exit;
}
date_default_timezone_set('America/New_York');
$uname = trim($_REQUEST["uname"]);
$upass = trim($_REQUEST["upass"]);
$query = "select * from task_users where (email = '".clean($uname)."' or username='".clean($uname)."') and password ='".md5(clean($upass))."'";
if($result = mysql_query($query))
{
	if(($num_rows = mysql_num_rows($result))>0)
	{
		$row = mysql_fetch_assoc($result);
		adminstatus($row["status"]);
		adminReject($row["type"]);
		$user = array("id"=>$row["id"], "name"=>stripslashes($row["name"]),"username"=>stripslashes($row["username"]),"password"=>stripslashes($row["password"]),"email"=>stripslashes($row["email"]),'title'=>$row["title"],"status"=>$row["status"],"type"=>$row["type"],"phone"=>stripslashes($row["phone"]),'office'=>$row["office"],'report_to'=>$row["report_to"],'acode'=>stripslashes($row["acode"]));
		$_SESSION["salesuser"]=$user;
		$query = "update task_users set last_checkin_salesr=NOW(),logout=NULL where id='".$row["id"]."'";
		@mysql_query($query);
		//send email to admin
		$emailid=setLoginEmail($row["type"]);//prevent receiver to receive this emali
		//$emailid=false;
		if($emailid)
		{ 
			$qx = "select * from task_users where id in('3')";
			if($rx = mysql_query($qx))
			{
				if(($numr = mysql_num_rows($rx))>0)
				{
					$today = date("m/d/Y H:i:s");
					$admin = mysql_fetch_assoc($rx);
					$title = "Family Energy Sales Report System: $today  ".$row["name"]." just logged in!";
					$message = "Hello,<br/><br/>";
					$message .="This is to let know that ".$row["name"]." just logged in to the Sales Report System.<br/><br/>";
					$message .="To view the new report you can login to Family Energy Sales Report System just click the link below and the given username and password.<br/>";
					$message .="<a href='http://www.familyenergymap.com/salesreport/' target='_blank'>Login Here</a><br/><br/>You can always change this information by login in the website and change your settings.<br/><br/>Attn,<br/><br/>Family Energy Team<br/>";
					$emailto=$admin["email"];
					$cphonex=$admin["phone"];
					//$emailto="luishk807@hotmail.com";
					if(!empty($emailto))
						$resultemail = sendEmail_simple($emailto,$title,$message);
					if(!empty($cphonex))
					{
						$mmessage="Family Energy Sales Report: $today ".$user["name"]." just logged in! wwww.familyenergymap.com/salesreport/";
						$result = sendSMS($cphonex,$mmessage);
					}
				}
			}
		}
		//end of email and text
		if(!detectAgent())
			$flink="mobile/home.php";
		else
			$flink="home.php";
		header("location:".$flink);
		exit;
	}
	else
	{
		$_SESSION["loginresult"]="Invalid Username And Password";
		header("location:index.php");
		exit;
	}
}
else
{
	$_SESSION["loginresult"]="System Error";
	unset($_SESSION["salesuser"]);
	header("location:index.php");
	exit;
}
include "include/unconfig.php";
?>
<?php
session_start();
include "include/config.php";
include "include/function.php";
$task=$_REQUEST["ctx"];
$host = getHost();
$url = getLink();
date_default_timezone_set('America/New_York');
if($task==md5("create"))
{
	$femail=strtolower(trim($_REQUEST["femail"]));
	$uname=trim($_REQUEST["uname"]);
	if(!empty($femail))
		$query = "select * from task_users where email='".clean($femail)."'";
	else if(!empty($uname))
		$query = "select * from task_users where username='".clean($uname)."'";
	else
	{
		$_SESSION["salesresult"]="ERROR: Missing Email or Username To Perform Search";
		header("location:".$url."fpassword.php");
		exit;
	}
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
			adminstatus($info["status"]);
			$today=date('Y-m-d');
			$rcode=md5($info["id"]."-".$today);
			$urlx=$host."fpass_r.php?cx=".$rcode;
			$fday=fixdate_comps('d',$today);
			$qx="update task_users set fpass_code='".$rcode."',fpass_date='".$today."' where id='".$info["id"]."'";
			if($rx=mysql_query($qx))
			{
				if(!empty($info["email"]))
				{
					$email_to=stripslashes($info["email"]);
					$title="Family Energy Password Reset";
					$message ="Hello ".stripslashes($info["name"]).",<br/><br/>";
					$message .="This email is to inform you that today ".$fday." your account has issued an password reset.<br/><br/>";
					$message .="To restart your password please click on the link below and start reseting your password.<br/>";
					$message .="<a href='".$urlx."' target='_blank'>".$urlx."</a><br/><br/>";
					$message .="Please be aware that this password will expire the following day, you only have until today ".$fday." to use this, othewise you will have to request the password reset again.<br/><br/>";
					$message .="Attn,<br/><br/>";
					$message .="Family Energy Team";
					if($result=sendEmail($email_to,$title,$message))
						$_SESSION["salesresult"]="SUCCESS: Password Reset Instruction Sent To ".$email_to;
					else
						$_SESSION["salesresult"]="ERROR: Unable To Sent Password Reset Instructions, Please Try Again Later";
				}
				else
					$_SESSION["salesresult"]="ERROR: Email Account Missing. Unable To Send Email, please contact administrator";
			}
		}
		else
			$_SESSION["salesresult"]="ERROR: No Match Found With The Information Provided";
	}
	else
		$_SESSION["salesresult"]="ERROR: System Failure, Please try again later";
	header("location:".$url."fpassword.php");
	exit;
}
else if($task==md5("reset"))
{
	$fpass=trim($_REQUEST["fpass"]);
	if(empty($fpass))
	{
		$_SESSION["salesresult"]="ERROR:Missing password";
		header("location:".$url."fpass_r.php");
		exit;
	}
	$today=date('Y-m-d');
	$cx=$_REQUEST["cx"];
	$query="select * from task_users where fpass_code='".clean($cx)."' and fpass_date='".$today."'";
	if($result=mysql_query($query))
	{
		if(($num_rows=mysql_num_rows($result))>0)
		{
			$info=mysql_fetch_assoc($result);
			adminstatus($info["status"]);
			$qx="update task_users set password='".md5($fpass)."',fpass_code=NULL,fpass_date=NULL where id='".$info["id"]."'";
			if($rx=mysql_query($qx))
			{
			
				if(!empty($info["email"]))
				{
					$email_to=stripslashes($info["email"]);
					$title="Family Energy Password Reset Successfull";
					$message ="Hello ".stripslashes($info["name"]).",<br/><br/>";
					$message .="This email is to inform you that today ".$fday." your password has been updated.<br/><br/>";
					$message .="Below is your new password:<br/>";
					$message .="Password: <b>".$fpass."</b><br/><br/>";
					$message .="To login please click <a href='".$host."' target='_blank'>HERE</a>. You can always change this password in the setting page.<br/><br/>";
					$message .="Attn,<br/><br/>";
					$message .="Family Energy Team";
					$result=sendEmail($email_to,$title,$message);
				}
				$_SESSION["loginresult"]="SUCCESS: Password Reset Successfull";
				header("location:".$url."index.php");
				exit;
			}
			else
			{
				$_SESSION["salesresult"]="ERROR: Unable to reset password please try again later";
				header("location:".$url."fpass.php");
				exit;
			}
		}
		else
		{
			$_SESSION["loginresult"]="ERROR:Password Reset Invalid or Expired";
			header("location:".$url."index.php");
			exit;
		}
	}
	else
	{
		$_SESSION["loginresult"]="ERROR: System Failure, Unable To Continue";
		header("location:".$url."index.php");
		exit;
	}
}
else
{
	$_SESSION["loginresult"]="ERROR:Invalid Entry";
	header("location:".$url."index.php");
	exit;
}
include "include/unconfig.php";
?>
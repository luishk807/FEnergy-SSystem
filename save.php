<?php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
if(empty($_SERVER['HTTP_REFERER']))
{
	header("location:status.php");
	exit;
}
$host = getHost();
$glink = getLink();
$task = $_REQUEST["task"];
//$listemail = 'mmajor@yourfamilyenergy.com';
$listemail = 'luishk807@hotmail.com';
$listphone = '347-613-1428';
if($task=="save")
{
	$user = $_SESSION["salesuser"];
	$uname = trim($_REQUEST["uname"]);
	$upass = trim($_REQUEST["newpass"]);
	$changepass = $_REQUEST["changepass"];
	$newpass = trim($_REQUEST["newpass"]);
	$cphone = trim($_REQUEST["cphone"]);
	$officeman = base64_decode($_REQUEST["officeman"]);
	$name = trim(ucwords(strtolower($_REQUEST["realname"])));
	$email =trim(strtolower($_REQUEST["uemail"]));
	$reportto = base64_decode($_REQUEST["reportto"]);
	if($uname != $user["username"])
	{
		$query = "select * from task_users where username='".clean($uname)."' and id !='".$user["id"]."'";
		if($result = mysql_query($query))
		{
			if(($num_rows = mysql_num_rows($result))>0)
			{
				$_SESSION["salesresult"]="ERROR: Username already in use";
				header('location:'.$glink.'account.php');
				exit;
			}
		}
	}
	$title = trim(ucwords(strtolower($_REQUEST["utitle"])));
	if($officeman !='na' && !empty($officeman))
		$officemanq = ",office='".$officeman."' ";
	else
		$officemanq=",office=NULL ";
	if($changepass=="yes")
	if($reportto !='na' && !empty($reportto))
		$reporttoq = ",report_to='".$reportto."' ";
	else
		$reporttoq=",report_to=NULL ";
	if($changepass=="yes")
		$query = "update task_users set username='".clean($uname)."',password='".md5(clean($newpass))."',name='".clean($name)."',title='".clean($title)."',email='".clean($email)."', phone='".clean($cphone)."' $officemanq $reporttoq where id='".$user["id"]."'";
	else
		$query = "update task_users set username='".clean($uname)."',name='".clean($name)."',title='".clean($title)."',email='".clean($email)."' , phone='".clean($cphone)."'  $officemanq $reporttoq where id='".$user["id"]."'";
	if($result = mysql_query($query))
	{
		$_SESSION["salesresult"]="SUCCESS: Changes Saved";
	}
	else
		$_SESSION["salesresult"]="ERROR: Unable To Save Changes";
	$query = "select * from task_users where id='".$user["id"]."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$row = mysql_fetch_assoc($result);
			$user = array("id"=>$row["id"], "name"=>stripslashes($row["name"]),"username"=>stripslashes($row["username"]),"password"=>stripslashes($row["password"]),"email"=>stripslashes($row["email"]),'title'=>$row["title"],"status"=>$row["status"],"type"=>$row["type"],"phone"=>stripslashes($row["phone"]),'office'=>$row["office"],'report_to'=>$row["report_to"]);
			adminstatus($row["status"]);
			$_SESSION["salesuser"]=$user;
			//echo "here";
			header("location:".$glink."account.php");
			exit;
		}
		else
		{
			$_SESSION["loginresult"]="Invalid User";
			unset($_SESSION["salesuser"]);
			header("location:".$glink);
			exit;
		}
	}
	else
	{
		$_SESSION["loginresult"]="System Error";
		unset($_SESSION["salesuser"]);
		header("location:".$glink);
		exit;
	}
}
else if($task=="create")
{
	$user = $_SESSION["salesuser"];
	$uname = trim($_REQUEST["uname"]);
	$upass = trim($_REQUEST["newpass"]);
	$name = trim(ucwords(strtolower($_REQUEST["realname"])));
	$email =trim(strtolower($_REQUEST["uemail"]));
	$status =$_REQUEST["ustatus"];
	$type = $_REQUEST["utype"];
	$cphone = trim($_REQUEST["cphone"]);
	$reportto = base64_decode($_REQUEST["reportto"]);
	$officeman = base64_decode($_REQUEST["officeman"]);
	$title = trim(ucwords(strtolower($_REQUEST["utitle"])));
	$query = "select * from task_users where username='".clean($uname)."'";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$_SESSION["salesresult"]="ERROR: Username already exist, please another username";
			header("location:".$glink."createuser.php");
			exit;
		}
	}
	if($officeman !='na' && !empty($officeman))
		$officemanq="'".$officeman."'";
	else
		$officemanq="NULL";
	if($reportto !='na' && !empty($reportto))
		$reporttoq="'".$reportto."'";
	else
		$reporttoq="NULL";
	$query = "insert ignore into task_users(username,password,name,title,email,status,type,date,phone,office,report_to)values('".clean($uname)."','".md5(clean($upass))."','".clean($name)."','".clean($title)."','".clean($email)."','".$status."','".$type."',NOW(),'".clean($cphone)."',$officemanq,$reporttoq)";
	if($result = mysql_query($query))
	{
		$_SESSION["salesresult"]="SUCCESS: User Created";
		if(!empty($email))
		{
			$title = "Family Energy Sales Report System: $name, Your Account is Created!";
			$message = "Hello ".$name.",<br/><br/>";
			$message .="This is to let know that your account for the Family Energy Sales Report System has been created for you from and you can start using it.<br/><br/>";
			$message .="Your Login Information is as follow:<br/>Username: <b>".$uname."</b><br/>Password: <b>".$upass."</b><br/><br/>";
			$message .="To login to Family Energy Sales Report System just click the link below and the given username and password.<br/>";
			$message .="<a href='http://www.familyenergymap.com/salesreport/' target='_blank'>Login Here</a><br/><br/>You can always change this information by login in the website and change your settings.<br/><br/>Attn,<br/><br/>FemCar.com Team<br/>";
			if($resultemail = sendEmail($email,$title,$message))
				$_SESSION["salesresult"]="SUCCESS: User Created and Email Sent";
		}
		if(!empty($cphone))
		{
			$mmessage="Family Energy Sales Report System: Your new account has been created!, Username: $uname and Password: $upass. www.familyenergymap.com/salesreport/";
			$result = sendSMS($cphone,$mmessage);
			if($result !="fail" && !empty($result))
			{
				if($resultemail)
					$_SESSION["salesresult"]="SUCCESS: User Created and Email Sent and Text Message Sent";
				else
					$_SESSION["salesresult"]="SUCCESS: User Created and Text Message Sent";
			}
		}
	}
	else
		$_SESSION["salesresult"]="ERROR: Unable To Create User";
	header('location:'.$glink.'viewusers.php');
	exit;
}
else if($task=="savem")
{
	$userid = base64_decode($_REQUEST["id"]);
	$email =trim(strtolower($_REQUEST["uemail"]));
	$uname = trim($_REQUEST["uname"]);
	$cphone = trim($_REQUEST["cphone"]);
	$officeman = base64_decode($_REQUEST["officeman"]);
	$reportto = base64_decode($_REQUEST["reportto"]);
	$query = "select * from task_users where id='".$userid."'";
	$changeusername=false;
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$checkusername = mysql_fetch_assoc($result);
			if($uname != stripslashes($checkusername["username"]))
			{
				$query = "select * from task_users where username='".clean($uname)."' and id !='".$userid."'";
				if($result = mysql_query($query))
				{
					if(($num_rows = mysql_num_rows($result))>0)
					{
						$_SESSION["salesresult"]="ERROR: Username already in use";
						header('location:'.$glink.'account_set.php?id='.base64_encode($userid));
						exit;
					}
				}
				$changeusername= true;
			}
		}
		else
		{
			$_SESSION["salesresult"]="ERROR: invalid username";
			header("location:".$glink."account_set.php?id=".base64_encode($userid));
			exit;
		}
	}
	else
	{
		$_SESSION["salesresult"]="ERROR: invalid username";
		header("location:".$glink."account_set.php?id=".base64_encode($userid));
		exit;
	}
	$upass = trim($_REQUEST["newpass"]);
	$changepass = $_REQUEST["changepass"];
	$newpass = trim($_REQUEST["newpass"]);
	$name = trim(ucwords(strtolower($_REQUEST["realname"])));
	$status =$_REQUEST["ustatus"];
	$type = $_REQUEST["utype"];
	$title = trim(ucwords(strtolower($_REQUEST["utitle"])));
	if($type=='6' || $type=='5')
	{
		if($officeman !='na' && !empty($officeman))
			$officemanq = ",office='".$officeman."' ";
		else
			$officemanq=",office=NULL ";
		if($type=='6')
			$reporttoq=",report_to=NULL ";
		else
		{
			if($reportto !='na' && !empty($reportto))
				$reporttoq = ",report_to='".$reportto."' ";
			else
				$reporttoq=",report_to=NULL ";
		}
	}
	else
	{
		$officemanq=",office=NULL ";
		$reporttoq=",report_to=NULL ";
	}
	if($changepass=="yes")
		$query = "update task_users set username='".clean($uname)."',password='".md5(clean($newpass))."',name='".clean($name)."',title='".clean($title)."',email='".clean($email)."',status='".$status."',type='".$type."', phone='".$cphone."' $officemanq $reporttoq where id='".$userid."'";
	else
		$query = "update task_users set username='".clean($uname)."',name='".clean($name)."',title='".clean($title)."',email='".clean($email)."',status='".$status."',type='".$type."' ,phone='".clean($cphone)."' $officemanq $reporttoq where id='".$userid."'";
	if($result = mysql_query($query))
	{
		$_SESSION["salesresult"]="SUCCESS: Changes For User $uname Saved";
		if($status =="2")
		{
			$title = "Family Energy Sales Report System: $name, Your Account is currently blocked!";
			$message = "Hello ".$name.",<br/><br/>";
			$message .="This is to let know that your account for the Family Energy Sales Report System has been recently updated and is currently blocked or cancelled<br/><br/>";
			$message .="Only Administrator or high staff personal can grant you access to the FemCar.com System.  You will be notified if your account becomes avaliable.<br/>";
			$message .="<br/><br/>Attn,<br/><br/>FemCar.com Team<br/>";			
		}
		else
		{
			$title = "Family Energy Sales Report System: $name, Your Account is updated!";
			$message = "Hello ".$name.",<br/><br/>";
			$message .="This is to let know that your account for the Family Energy Sales Report System System has been recently updated!<br/><br/>";
			if($changepass=="yes")
				$message .="Your New Login Information is as follow:<br/>Username: <b>$uname</b><br/>Password: <b>".$newpass."</b><br/><br/>";
			else if($changeusername== true)
				$message .="Your New Username is as follow:<br/>Username: <b>$uname</b><br/><br/>";
			$message .="To login to Family Energy Sales Report System just click the link below and the given username and password.<br/>";
			$message .="<a href='http://www.familyenergymap.com/salesreport/' target='_blank'>Login Here</a><br/><br/>Attn,<br/><br/>Family Energy Team<br/>";
		}
		if($changeusername || $changepass=='yes')
		{
			if($resultemail = sendEmail($email,$title,$message))
				$_SESSION["salesresult"]="SUCCESS: User Changes is Saved and Email Sent";
		}
	}
	else
		$_SESSION["salesresult"]="ERROR: Unable To Save Changes For User $uname";
	header('location:'.$glink.'account_set.php?id='.$_REQUEST["id"]);
	exit;
}
else if($task=="createreport")
{
	date_default_timezone_set('America/New_York');
	$today = date("Y-m-d");
	$user = $_SESSION["salesuser"];
	$aname = ucwords(strtolower(trim($_REQUEST["aname"])));
	$acode=$_REQUEST["ucode"];
	$acodeq="";
	$acodeqa="";
	if(!empty($acode))
		$acode=trim(strtoupper($acode));
	$query = "select * from sales_agent where name='".clean($aname)."'";
	if($r = mysql_query($query))
	{
		if(($numrows = mysql_num_rows($r))>0)
		{
			$agentx = mysql_fetch_assoc($r);
			if(!empty($acode))
			{
				$q="update sales_agent set acode='".clean($acode)."' where id='".$agentx["id"]."'";
				@mysql_query($q);
			}
			$agent = $agentx["id"];
		}
		else
		{
			if(!empty($acode))
			{
				$acodeq=",acode";
				$acodeqa=",'".clean($acode)."'";
			}
			$q ="insert ignore into sales_agent(name,date".$acodeq.")values('".clean($aname)."',NOW()".$acodeqa.")";
			if($result = mysql_query($q))
				$agent = mysql_insert_id();
		}
	}
	else
	{
		$_SESSION["salesresult"]="ERROR: Unable To Get Agent Information";
		header("location:".$glink."createreport.php");
		exit;
	}
	$fromdate = fixdate_comps('mildatecomp',$_REQUEST["fromdate"]);
	$xdate = explode(" ",$fromdate);
	$fromdatex = $xdate[0];
	$fromtimex = $xdate[1];
	$uoffice = base64_decode($_REQUEST["uoffice"]);
	$usales =trim($_REQUEST["usales"]);
	$desc = $_REQUEST["udesc"];
	$fampm=$_REQUEST["fampm"];
	$newtime=fixdate_comps_switch($fromtimex,$fampm);
	if(empty($newtime))
		$newtime=$fromtimex;
	$q="select * from sales_report where agentid='".$agent."' and fromdate ='".$today."'";
	if($r=mysql_query($q))
	{
		if(($numrowa = mysql_num_rows($r))>0)
		{
			$xfound=mysql_fetch_assoc($r);
			$xtoday = date("m/d/Y");
			if(!pViewm($user["type"]))
			{
				$_SESSION["salesresult"]="ERROR: $aname Sales $xtoday is already entered";
				header("location:".$glink."createreport.php");
				exit;
			}
			else
			{
				$_SESSION["salesresult"]="ERROR: $aname Sales $xtoday is already entered";
				header("location:".$glink."accountreport_set.php?id=".base64_encode($xfound['id']));
				exit;
			}
		}
	}
	$query = "insert ignore into sales_report(userid,agentid,office,stotal,fromdate,fromtime,descx,date)values('".$user["id"]."','".$agent."','".$uoffice."','".clean($usales)."','".$fromdatex."','".$newtime."','".clean($desc)."',NOW())";
	if($result = mysql_query($query))
	{
		$_SESSION["salesresult"]="SUCCESS: Report Saved";
	}
	else
	{
		$_SESSION["salesresult"]="ERROR: Unable To Save Report";
	}
	header('location:'.$glink.'createreport.php');
	exit;
}
else if($task=="savereport")
{
	$user = $_SESSION["salesuser"];
	$id = base64_decode($_REQUEST["id"]);
	$aname = ucwords(strtolower(trim($_REQUEST["aname"])));
	$acode=$_REQUEST["ucode"];
	if(!empty($acode))
		$acode=trim(strtoupper($acode));
	if($agentname !=$aname)
	{
		$query = "select * from sales_agent where name='".clean($aname)."'";
		if($r = mysql_query($query))
		{
			if(($numrows = mysql_num_rows($r))>0)
			{
				$agentx = mysql_fetch_assoc($r);
				if(!empty($acode))
				{
					$q="update sales_agent set acode='".clean($acode)."' where id='".$agentx["id"]."'";
					@mysql_query($q);
				}
				$agent = $agentx["id"];
			}
			else
			{
				if(!empty($acode))
				{
					$acodeq=",acode";
					$acodeqa=",'".clean($acode)."'";
				}
				$q ="insert ignore into sales_agent(name,date".$acodeq.")values('".clean($aname)."',NOW()".$acodeqa.")";
				//$q ="insert ignore into sales_agent(name,date)values('".clean($aname)."',NOW())";
				if($result = mysql_query($q))
					$agent = mysql_insert_id();
			}
		}
		else
		{
			$_SESSION["salesresult"]="ERROR: Unable To Get Agent Information";
			header("location:".$glink."createreport.php");
			exit;
		}
	}
	$fromdate = fixdate_comps('mildatecomp',$_REQUEST["fromdate"]);
	$uman = base64_decode($_REQUEST["uman"]);
	$xdate = explode(" ",$fromdate);
	$fromdatex = $xdate[0];
	$fromtimex = $xdate[1];
	$uoffice = base64_decode($_REQUEST["uoffice"]);
	$usales =trim($_REQUEST["usales"]);
	$desc = $_REQUEST["udesc"];
	$fampm=$_REQUEST["fampm"];
	$newtime=fixdate_comps_switch($fromtimex,$fampm);
	if(empty($newtime))
		$newtime=$fromtimex;
	$query = "update sales_report set userid='".$uman."', agentid='".$agent."',office='".$uoffice."',stotal='".clean($usales)."',fromdate='".$fromdatex."',fromtime='".$newtime."',descx='".clean($desc)."' where id='".$id."'";
	if($result = mysql_query($query))
	{
		$_SESSION["salesresult"]="SUCCESS: Report Saved";
	}
	else
	{
		$_SESSION["salesresult"]="ERROR: Unable To Save Report";
	}
	header('location:'.$glink.'viewreport.php');
	exit;
}
else if($task=="savereport_r")
{
	$user = $_SESSION["salesuser"];
	$id = base64_decode($_REQUEST["id"]);
	$aname = ucwords(strtolower(trim($_REQUEST["aname"])));
	$acode=$_REQUEST["ucode"];
	if(!empty($acode))
		$acode=trim(strtoupper($acode));
	if($agentname !=$aname)
	{
		$query = "select * from sales_agent where name='".clean($aname)."'";
		if($r = mysql_query($query))
		{
			if(($numrows = mysql_num_rows($r))>0)
			{
				$agentx = mysql_fetch_assoc($r);
				if(!empty($acode))
				{
					$q="update sales_agent set acode='".clean($acode)."' where id='".$agentx["id"]."'";
					@mysql_query($q);
				}
				$agent = $agentx["id"];
			}
			else
			{
				if(!empty($acode))
				{
					$acodeq=",acode";
					$acodeqa=",'".clean($acode)."'";
				}
				$q ="insert ignore into sales_agent(name,date".$acodeq.")values('".clean($aname)."',NOW()".$acodeqa.")";
				//$q ="insert ignore into sales_agent(name,date)values('".clean($aname)."',NOW())";
				if($result = mysql_query($q))
					$agent = mysql_insert_id();
			}
		}
		else
		{
			$_SESSION["salesresult"]="ERROR: Unable To Get Agent Information";
			header("location:".$glink."createreport.php");
			exit;
		}
	}
	$ddatex = fixdate_comps('mildate',$_REQUEST["ddatex"]);
	$uoffice = base64_decode($_REQUEST["uoffice"]);
	$xelec =trim($_REQUEST["uelec"]);
	$xgas =trim($_REQUEST["ugas"]);
	$desc = $_REQUEST["udesc"];
	$query = "update sales_report_real set userid='".$agent."',office='".$uoffice."',xelec='".clean($xelec)."',xgas='".clean($xgas)."',ddate='".$ddatex."',descx='".clean($desc)."' where id='".$id."'";
	if($result = mysql_query($query))
		$_SESSION["salesresult"]="SUCCESS: Report Saved";
	else
		$_SESSION["salesresult"]="ERROR: Unable To Save Report";
	header('location:'.$glink.'viewgraph_r.php');
	exit;
}
else if($task=="createreport_r")
{
	date_default_timezone_set('America/New_York');
	$today = date("Y-m-d");
	$user = $_SESSION["salesuser"];
	$aname = ucwords(strtolower(trim($_REQUEST["aname"])));
	$acode=$_REQUEST["ucode"];
	$acodeq="";
	$acodeqa="";
	$fileid=base64_decode($_REQUEST["fileid"]);
	if(!empty($acode))
		$acode=trim(strtoupper($acode));
	$query = "select * from sales_agent where name='".clean($aname)."'";
	if($r = mysql_query($query))
	{
		if(($numrows = mysql_num_rows($r))>0)
		{
			$agentx = mysql_fetch_assoc($r);
			if(!empty($acode))
			{
				$q="update sales_agent set acode='".clean($acode)."' where id='".$agentx["id"]."'";
				@mysql_query($q);
			}
			$agent = $agentx["id"];
		}
		else
		{
			if(!empty($acode))
			{
				$acodeq=",acode";
				$acodeqa=",'".clean($acode)."'";
			}
			$q ="insert ignore into sales_agent(name,date".$acodeq.")values('".clean($aname)."',NOW()".$acodeqa.")";
			if($result = mysql_query($q))
				$agent = mysql_insert_id();
		}
	}
	else
	{
		$_SESSION["salesresult"]="ERROR: Unable To Get Agent Information";
		header("location:".$glink."createreport.php");
		exit;
	}
	$ddatex= fixdate_comps('mildate',$_REQUEST["ddatex"]);
	$uoffice = base64_decode($_REQUEST["uoffice"]);
	$usales =trim($_REQUEST["usales"]);
	$desc = $_REQUEST["udesc"];
	$q="select * from sales_report_real where userid='".$agent."' and ddate ='".$today."'";
	if($r=mysql_query($q))
	{
		if(($numrowa = mysql_num_rows($r))>0)
		{
			$xfound=mysql_fetch_assoc($r);
			$xtoday = date("m/d/Y");
			if(!pViewm($user["type"]))
			{
				$_SESSION["salesresult"]="ERROR: $aname Sales $xtoday already exists";
				header("location:".$glink."createreport_r.php");
				exit;
			}
			else
			{
				$_SESSION["salesresult"]="ERROR: $aname Sales $xtoday is already entered";
				header("location:".$glink."accountreport_set_r.php?id=".base64_encode($xfound['id']));
				exit;
			}
		}
	}
	$query = "insert ignore into sales_report_real(fileid,userid,office,stotal,ddate,descx,date)values('".$fileid."','".$agent."','".$uoffice."','".clean($usales)."','".$ddatex."','".clean($desc)."',NOW())";
	if($result = mysql_query($query))
		$_SESSION["salesresult"]="SUCCESS: Report Saved";
	else
		$_SESSION["salesresult"]="ERROR: Unable To Save Report";
	header('location:'.$glink.'createreport_r.php');
	exit;
}
else if($task=="creategoalsx")
{
	$user = $_SESSION["salesuser"];
	$ugoal =trim($_REQUEST["ugoal"]);
	$office = base64_decode($_REQUEST["uoffice"]);
	$query = "insert ignore into sales_goals_office(goals,date,office)values('".$ugoal."',NOW(),'".$office."')";
	if($result = mysql_query($query))
	{
		$id=mysql_insert_id();
		$_SESSION["salesresult"]="SUCCESS: Goals Saved, please set the goals for managers and team leaders";
		header('location:'.$glink.'accountgoalsx_set.php?id='.base64_encode($id));
		exit;
	}
	else
	{
		$_SESSION["salesresult"]="ERROR: Unable To Save Goals";
		header('location:'.$glink.'creategoalsx.php');
		exit;
	}
}
else if($task=="savegoalsx")
{
	$user = $_SESSION["salesuser"];
	$id = base64_decode($_REQUEST["id"]);
	$ugoal = trim($_REQUEST["ugoal"]);
	$office = base64_decode($_REQUEST["uoffice"]);
	$dall=trim($_REQUEST["dall"]);
	$query = "update sales_goals_office set goals='".$ugoal."',date=NOW(),office='".$office."' where id='".$id."'";
	if($result = mysql_query($query))
	{
		$_SESSION["salesresult"]="SUCCESS: Office Goal Saved";
		if($dall=="yes")
		{
			$query = "delete from sales_goals_ind where goalid='".$id."'";
			if($result = mysql_query($query))
				$_SESSION["salesresult"]="SUCCESS: Office Goal Saved and Indivial Goals For Office Resetted";
			else
				$_SESSION["salesresult"]="ERROR: Office Goal Saved But Unable To Delete All Individual Goals";
		}
	}
	else
	{
		$_SESSION["salesresult"]="ERROR: Unable To Save Office Goal";
	}
	header('location:'.$glink.'viewgoalsx.php');
	exit;
}
else if($task=="deletegoalx")
{
	if(isset($_SESSION["salesuser"]))
	{
		$user = $_SESSION["salesuser"];
		if(!pViewm($user["type"]))
		{
			$_SESSION["salesresult"]="ERROR: You Are Not Authorized To Do Goals Deletion";
			header("location:".$glink."home.php");
			exit;
		}
	}
	else
	{
		$_SESSION["salesresult"]="ERROR: Invalid Entry";
		header("location:".$glink."home.php");
		exit;
	}
	$id = base64_decode($_REQUEST["id"]);
	$query = "delete from sales_goals_ind where goalid='$id'";
	if($result = mysql_query($query))
	{
		$_SESSION["salesresult"]="SUCCESS: Individual Goal Deleted";
		$query = "delete from sales_goals_office where id='$id'";
		if($result = mysql_query($query))
			$_SESSION["salesresult"]="SUCCESS: Goals Delete Completed";
		else
			$_SESSION["salesresult"]="ERROR: Individual Goals Deleted, but unable to delete office goals";
	}
	else
		$_SESSION["salesresult"]="ERROR: Unable To Delete Goal, Please try again later";
	header('location:'.$glink.'viewgoalsx.php');
	exit;
}
else if($task=="createindgoalx")
{
	$user = $_SESSION["salesuser"];
	$id = base64_decode($_REQUEST["id"]);
	$ugoal =trim($_REQUEST["ugoal"]);
	$umanager = base64_decode($_REQUEST["umanager"]);
	$query = "insert ignore into sales_goals_ind(goals,date,goalid,userid)values('".$ugoal."',NOW(),'".$id."','".$umanager."')";
	if($result = mysql_query($query))
		$_SESSION["salesresult"]="SUCCESS: Goals Saved";
	else
		$_SESSION["salesresult"]="ERROR: Unable To Save Goals";
	header('location:'.$glink.'accountgoalsx_set.php?id='.base64_encode($id));
	exit;
}
else if($task=="deleteindgoalx")
{
	if(isset($_SESSION["salesuser"]))
	{
		$user = $_SESSION["salesuser"];
		if(!pViewm($user["type"]))
		{
			$_SESSION["salesresult"]="ERROR: You Are Not Authorized To Do Goals Deletion";
			header("location:".$glink."home.php");
			exit;
		}
	}
	else
	{
		$_SESSION["salesresult"]="ERROR: Invalid Entry";
		header("location:".$glink."home.php");
		exit;
	}
	$id = $_REQUEST["id"];
	$idx = base64_decode($_REQUEST["idx"]);
	$query = "delete from sales_goals_ind where id='$idx'";
	if($result = mysql_query($query))
		$_SESSION["salesresult"]="SUCCESS: Goal Deleted";
	else
		$_SESSION["salesresult"]="ERROR: Unable To Delete Goal, Please try again later";
	header('location:'.$glink.'accountgoalsx_set.php?id='.$id);
	exit;
}
else if($task=="updateindgoalx")
{
	$user = $_SESSION["salesuser"];
	$id = base64_decode($_REQUEST["id"]);
	$idx=base64_decode($_REQUEST["idx"]);
	$umanager=base64_decode($_REQUEST["umanager"]);
	$ugoal = trim($_REQUEST["ugoal"]);
	$query = "update sales_goals_ind set goals='".$ugoal."',goalid='".$id."', date=NOW(),userid='".$umanager."' where id='".$idx."'";
	if($result = mysql_query($query))
		$_SESSION["salesresult"]="SUCCESS: Goal Updated";
	else
		$_SESSION["salesresult"]="ERROR: Unable To Update Goal";
	header('location:'.$glink.'accountgoalsx_set.php?id='.base64_encode($id));
	exit;
}
else if($task=="creategoals")
{
	$user = $_SESSION["salesuser"];
	$umanager = base64_decode($_REQUEST["umanager"]);
	$ugoal =trim($_REQUEST["ugoal"]);
	$office = base64_decode($_REQUEST["uoffice"]);
	$query = "insert ignore into sales_goals(userid,goals,date,office)values('".$umanager."','".$ugoal."',NOW(),'".$office."')";
	if($result = mysql_query($query))
		$_SESSION["salesresult"]="SUCCESS: Goals Saved";
	else
		$_SESSION["salesresult"]="ERROR: Unable To Save Goals";
	header('location:'.$glink.'creategoals.php');
	exit;
}
else if($task=="savegoals")
{
	$user = $_SESSION["salesuser"];
	$id = base64_decode($_REQUEST["id"]);
	$umanager= base64_decode($_REQUEST["umanager"]);
	$ugoal = trim($_REQUEST["ugoal"]);
	$office = base64_decode($_REQUEST["uoffice"]);
	$query = "update sales_goals set userid='".$umanager."',goals='".$ugoal."',date=NOW(),office='".$office."' where id='".$id."'";
	if($result = mysql_query($query))
	{
		$_SESSION["salesresult"]="SUCCESS: Goal Saved";
	}
	else
	{
		$_SESSION["salesresult"]="ERROR: Unable To Save Goal";
	}
	header('location:'.$glink.'viewgoals.php');
	exit;
}
else if($task=="saveoffice")
{
	$user = $_SESSION["salesuser"];
	$id = base64_decode($_REQUEST["id"]);
	$oname = trim(ucwords(strtolower($_REQUEST["oname"])));
	$oemail = trim(strtolower($_REQUEST["oemail"]));
	$ophone= trim($_REQUEST["ophone"]);
	$ofax = trim($_REQUEST["ofax"]);
	$odays = trim(ucwords(strtolower($_REQUEST["odays"])));
	$ohours = trim(ucwords(strtolower($_REQUEST["ohours"])));
	$oaddress = trim(ucwords(strtolower($_REQUEST["oaddress"])));
	$ocity = trim(ucwords(strtolower($_REQUEST["ocity"])));
	$ostate= trim(ucwords(strtolower($_REQUEST["ostate"])));
	$ocountry = trim(ucwords(strtolower($_REQUEST["ocountry"])));
	$ozip = trim(strtoupper($_REQUEST["ozip"]));
	$odriving = trim($_REQUEST["odriving"]);
	$owalking = trim($_REQUEST["owalking"]);
	$ocontact = trim(ucwords(strtolower($_REQUEST["ocontact"])));
	$query = "update rec_office set name='".clean($oname)."',email='".clean($oemail)."',address='".clean($oaddress)."', city='".clean($ocity)."',state='".clean($ostate)."',country='".clean($ocountry)."', zip='".clean($ozip)."', phone='".clean($ophone)."', days='".clean($odays)."', hours='".clean($ohours)."',idrive='".clean($odriving)."', iwalk='".clean($owalking)."',fax='".clean($ofax)."',contact='".clean($ocontact)."' where id='$id'";
	if($result = mysql_query($query))
		$_SESSION["salesresult"]="SUCCESS: Changes Saved";
	else
		$_SESSION["salesresult"]="ERROR: Unable To Save Changes";
	header("location:".$glink."accountoffice_set.php?id=".base64_encode($id));
	exit;
}
else if($task=="saverealtotal")
{
	$user = $_SESSION["salesuser"];
	$id = base64_decode($_REQUEST["id"]);
	$utotal = trim($_REQUEST["utotal"]);
	$query = "update sales_an_sales set total='".clean($utotal)."' where id='$id'";
	if($result = mysql_query($query))
		$_SESSION["salesresult"]="SUCCESS: Changes Saved";
	else
		$_SESSION["salesresult"]="ERROR: Unable To Save Changes";
	header("location:".$glink."accountrealtotal_oset.php?id=".base64_encode($id));
	exit;
}
else if($task=="createagent")
{
	$user = $_SESSION["salesuser"];
	$aname=ucwords(strtolower(trim($_REQUEST["uname"])));
	$acode = trim(strtoupper($_REQUEST["ucode"]));
	$mdup=$_REQUEST["mdup"];
	$mdup_in=$_REQUEST["mdup_in"];
	$query = "insert into sales_agent(name,acode,date)values('".clean($aname)."','".clean($acode)."',NOW())";
	if($result = mysql_query($query))
	{
		$id=mysql_insert_id();
		$_SESSION["salesresult"]="SUCCESS: Agent Created";
		if(!empty($id))
		{
			if($mdup)
			{
				for($i=0;$i<sizeof($mdup_in);$i++)
				{
					$idx=base64_decode($mdup_in[$i]);
					$xdo=false;
					$qx="select * from sales_report where agentid='".$idx."'";
					if($rx=mysql_query($qx))
					{
						if(($numx=mysql_num_rows($rx))>0)
							$xdo=true;
					}
					if($xdo)
					{
						$qx="update sales_report set agentid='".$id."' where agentid='".$idx."'";
						if($rx=mysql_query($qx))
						{
							$_SESSION["salesresult"]="SUCCESS: Changes Saved and Sales Updated";
							$qxx="delete from sales_agent where id='".$idx."'";
							if($rxi=mysql_query($qxx))
								$_SESSION["salesresult"]="SUCCESS: Changes Saved and Sales Updated and Selected Agent Deleted";
							else
								$_SESSION["salesresult"]="SUCCESS: Changes Saved and Sales Updated but Selected Agent Can't Be Deleted";
						}
						else
						{
							$qxx="delete from sales_agent where id='".$idx."'";
							if($rxi=mysql_query($qxx))
								$_SESSION["salesresult"]="SUCCESS: Changes Saved and Selected Agent Deleted";
							else
								$_SESSION["salesresult"]="SUCCESS: Changes Saved but Selected Agent Can't Be Deleted";
						}
					}
					else
					{
						$qxx="delete from sales_agent where id='".$idx."'";
						if($rxx=mysql_query($qxx))
							$_SESSION["salesresult"]="SUCCESS: Changes Saved and Selected Agent Deleted";
						else
							$_SESSION["salesresult"]="SUCCESS: Changes Saved but Selected Agent Deleted";
					}
					//echo $idx."<br/>";
				}
			}
		}
	}
	else
		$_SESSION["salesresult"]="ERROR: Unable To Create Agent";
	header("location:".$glink."viewagents.php");
	exit;
}
else if($task=="saveagent")
{
	$user = $_SESSION["salesuser"];
	$id = base64_decode($_REQUEST["id"]);
	$aname=ucwords(strtolower(trim($_REQUEST["uname"])));
	$acode = trim(strtoupper($_REQUEST["ucode"]));
	$mdup=$_REQUEST["mdup"];
	$mdup_inx=array();
	$mdup_in=$_REQUEST["mdup_in"];
	if(empty($acode))
		$acodeq=",acode=NULL";
	else
		$acodeq=",acode='".clean($acode)."'";
	$query = "update sales_agent set name='".clean($aname)."' $acodeq where id='$id'";
	if($result = mysql_query($query))
		$_SESSION["salesresult"]="SUCCESS: Changes Saved";
	else
		$_SESSION["salesresult"]="ERROR: Unable To Save Changes";
	if($mdup)
	{
		for($i=0;$i<sizeof($mdup_in);$i++)
		{
			$idx=base64_decode($mdup_in[$i]);
			$xdo=false;
			$qx="select * from sales_report where agentid='".$idx."'";
			if($rx=mysql_query($qx))
			{
				if(($numx=mysql_num_rows($rx))>0)
					$xdo=true;
			}
			if($xdo)
			{
				$qx="update sales_report set agentid='".$id."' where agentid='".$idx."'";
				if($rx=mysql_query($qx))
				{
					$_SESSION["salesresult"]="SUCCESS: Changes Saved and Sales Updated";
					$qxx="delete from sales_agent where id='".$idx."'";
					if($rxi=mysql_query($qxx))
						$_SESSION["salesresult"]="SUCCESS: Changes Saved and Sales Updated and Selected Agent Deleted";
					else
						$_SESSION["salesresult"]="SUCCESS: Changes Saved and Sales Updated but Selected Agent Can't Be Deleted";
				}
				else
				{
					$qxx="delete from sales_agent where id='".$idx."'";
					if($rxi=mysql_query($qxx))
						$_SESSION["salesresult"]="SUCCESS: Changes Saved and Selected Agent Deleted";
					else
						$_SESSION["salesresult"]="SUCCESS: Changes Saved but Selected Agent Can't Be Deleted";
				}
			}
			else
			{
				$qxx="delete from sales_agent where id='".$idx."'";
				if($rxx=mysql_query($qxx))
					$_SESSION["salesresult"]="SUCCESS: Changes Saved and Selected Agent Deleted";
				else
					$_SESSION["salesresult"]="SUCCESS: Changes Saved but Selected Agent Deleted";
			}
			//set the real report agent
			$qx="select * from sales_report_real where userid='".$idx."'";
			if($rx=mysql_query($qx))
			{
				if(($numx=mysql_num_rows($rx))>0)
				{
					$qxx="update sales_report_real set userid='".$id."' where userid='".$idx."'";
					if($rxx=mysql_query($qxx))
						$_SESSION["salesresult"]="SUCCESS: Changes Saved and Selected Agent Deleted Completely";
				}
			}
			//echo $idx."<br/>";
		}
	}
	header("location:".$glink."accountagent_set.php?id=".base64_encode($id));
	exit;
}
else if($task=="createoffice")
{
	$user = $_SESSION["salesuser"];
	$oname = trim(ucwords(strtolower($_REQUEST["oname"])));
	$oemail= trim(strtolower($_REQUEST["oemail"]));
	$ophone= trim($_REQUEST["ophone"]);
	$ofax = trim($_REQUEST["ofax"]);
	$odays = trim(ucwords(strtolower($_REQUEST["odays"])));
	$ohours = trim(ucwords(strtolower($_REQUEST["ohours"])));
	$oaddress = trim(ucwords(strtolower($_REQUEST["oaddress"])));
	$ocity = trim(ucwords(strtolower($_REQUEST["ocity"])));
	$ostate= trim(ucwords(strtolower($_REQUEST["ostate"])));
	$ocountry = trim(ucwords(strtolower($_REQUEST["ocountry"])));
	$ozip = trim(strtoupper($_REQUEST["ozip"]));
	$odriving = trim($_REQUEST["odriving"]);
	$owalking = trim($_REQUEST["owalking"]);
	$ocontact = trim(ucwords(strtolower($_REQUEST["ocontact"])));
	$query = "insert ignore into rec_office(name,address,city,state,country,zip,email,phone,fax,days,hours,idrive,iwalk,datecreated,contact)values('".clean($oname)."','".clean($oaddress)."','".clean($ocity)."','".clean($ostate)."','".clean($ocountry)."','".clean($ozip)."','".clean($oemail)."','".clean($ophone)."','".clean($ofax)."','".clean($odays)."','".clean($ohours)."','".clean($odriving)."','".clean($owalking)."',NOW(),'".clean($ocontact)."')";
	if($result = mysql_query($query))
		$_SESSION["salesresult"]="SUCCESS: Office Created";
	else
		$_SESSION["salesresult"]="ERROR: Unable To Create Office";
	header('location:'.$glink.'viewoffice.php');
	exit;
}
else if($task=="saveofficerealtotal")
{
	$id=base64_decode($_REQUEST["office"]);
	$jan_total=trim($_REQUEST["january_utotal"]);
	$feb_total=trim($_REQUEST["february_utotal"]);
	$march_total=trim($_REQUEST["march_utotal"]);
	$april_total=trim($_REQUEST["april_utotal"]);
	$may_total=trim($_REQUEST["may_utotal"]);
	$june_total=trim($_REQUEST["june_utotal"]);
	$july_total=trim($_REQUEST["july_utotal"]);
	$august_total=trim($_REQUEST["august_utotal"]);
	$sept_total=trim($_REQUEST["september_utotal"]);
	$october_total=trim($_REQUEST["october_utotal"]);
	$nov_total=trim($_REQUEST["november_utotal"]);
	$dec_total=trim($_REQUEST["december_utotal"]);
	if(!empty($id))
	{
		$insert=true;
		$query="select * from sales_an_office where office='".$id."'";
		if($result=mysql_query($query))
		{
			if(($num_rows=mysql_num_rows($result))>0)
				$insert=false;
		}
		if(!$insert)
		{
			$query="update sales_an_office set January='".$jan_total."',February='".$feb_total."',March='".$march_total."',April='".$april_total."',May='".$may_total."',June='".$june_total."',July='".$july_total."',August='".$august_total."',September='".$sept_total."',October='".$october_total."',November='".$nov_total."',December='".$dec_total."',modified_by=NOW() where office='".$id."'";
		}
		else
		{
			$query="insert ignore into sales_an_office(office,January,February,March,April,May,June,July,August,September,October,November,December,date)values('".$id."','".$jan_total."','".$feb_total."','".$march_total."','".$april_total."','".$may_total."','".$june_total."','".$july_total."','".$august_total."','".$sept_total."','".$october_total."','".$nov_total."','".$dec_total."',NOW())";
		}
		//echo $query;
		if($result=mysql_query($query))
			$_SESSION["salesresult"]="SUCCESS: Goal Information Saved";
		else
			$_SESSION["salesresult"]="ERROR: Unable To Save Goal Information";
	}
	else
		$_SESSION["salesresult"]="ERROR: Unable To Save Goal Information";
	header("location:accountofficerealtotal_oset.php");
	exit;
}
else if($task=="deleteoffice")
{
	if(isset($_SESSION["salesuser"]))
	{
		$user = $_SESSION["salesuser"];
		if(!pView($user["type"]))
		{
			$_SESSION["salesresult"]="ERROR:You are not authorized to do Office Deletion";
			header("location:".$glink."home.php");
			exit;
		}
	}
	else
	{
		$_SESSION["salesresult"]="ERROR:Invalid Entry";
		header("location:".$glink."home.php");
		exit;
	}
	$id = base64_decode($_REQUEST["id"]);
	$query = "delete from rec_office where id='$id'";
	if($result = mysql_query($query))
		$_SESSION["salesresult"]="SUCCESS: Office Deleted";
	else
		$_SESSION["salesresult"]="ERROR: Unable To Delete Office, Please try again later";
	header('location:'.$glink.'viewoffice.php');
	exit;
}
else if($task=="delete")
{
	if(isset($_SESSION["salesuser"]))
	{
		$user = $_SESSION["salesuser"];
		if(!pViewm($user["type"]))
		{
			$_SESSION["salesresult"]="ERROR: You Are Not Authorized To Do User Deletion";
			header("location:".$glink."home.php");
			exit;
		}
	}
	else
	{
		$_SESSION["salesresult"]="ERROR: Invalid Entry";
		header("location:".$glink."home.php");
		exit;
	}
	$id = base64_decode($_REQUEST["id"]);
	$query = "delete from task_users where id='$id'";
	if($result = mysql_query($query))
		$_SESSION["salesresult"]="SUCCESS: User Deleted";
	else
		$_SESSION["salesresult"]="ERROR: Unable To Delete User, Please try again later";
	header('location:'.$glink.'viewusers.php');
	exit;
}
else if($task=="deletegoals")
{
	if(isset($_SESSION["salesuser"]))
	{
		$user = $_SESSION["salesuser"];
		if(!pViewm($user["type"]))
		{
			$_SESSION["salesresult"]="ERROR: You Are Not Authorized To Do Goals Deletion";
			header("location:".$glink."home.php");
			exit;
		}
	}
	else
	{
		$_SESSION["salesresult"]="ERROR: Invalid Entry";
		header("location:".$glink."home.php");
		exit;
	}
	$id = base64_decode($_REQUEST["id"]);
	$query = "delete from sales_goals where id='$id'";
	if($result = mysql_query($query))
		$_SESSION["salesresult"]="SUCCESS: Goal Deleted";
	else
		$_SESSION["salesresult"]="ERROR: Unable To Delete Goal, Please try again later";
	header('location:'.$glink.'viewgoals.php');
	exit;
}
else if($task=="deleteagent")
{
	if(isset($_SESSION["salesuser"]))
	{
		$user = $_SESSION["salesuser"];
		if(!pViewm($user["type"]))
		{
			$_SESSION["salesresult"]="ERROR: You Are Not Authorized To Do Agent Deletion";
			header("location:".$glink."home.php");
			exit;
		}
	}
	else
	{
		$_SESSION["salesresult"]="ERROR: Invalid Entry";
		header("location:".$glink);
		exit;
	}
	$id = base64_decode($_REQUEST["id"]);
	$query = "delete from sales_agent where id='$id'";
	if($result = mysql_query($query))
		$_SESSION["salesresult"]="SUCCESS: Agent Deleted";
	else
		$_SESSION["salesresult"]="ERROR: Unable To Delete Agent, Please try again later";
	header('location:'.$glink.'viewagents.php');
	exit;
}
else if($task=="deletereport")
{
	if(isset($_SESSION["salesuser"]))
	{
		$user = $_SESSION["salesuser"];
		if(!pViewm($user["type"]))
		{
			$_SESSION["salesresult"]="ERROR: You Are Not Authorized To Do Report Deletion";
			header("location:".$glink."home.php");
			exit;
		}
	}
	else
	{
		$_SESSION["salesresult"]="ERROR: Invalid Entry";
		header("location:".$glink);
		exit;
	}
	$id = base64_decode($_REQUEST["id"]);
	$query = "delete from sales_report where id='$id'";
	if($result = mysql_query($query))
		$_SESSION["salesresult"]="SUCCESS: Report Deleted";
	else
		$_SESSION["salesresult"]="ERROR: Unable To Delete Report, Please try again later";
	header('location:'.$glink.'viewreport.php');
	exit;
}
else if($task=="deletereport_r")
{
	if(isset($_SESSION["salesuser"]))
	{
		$user = $_SESSION["salesuser"];
		if(!pViewm($user["type"]))
		{
			$_SESSION["salesresult"]="ERROR: You Are Not Authorized To Do Report Deletion";
			header("location:".$glink."home.php");
			exit;
		}
	}
	else
	{
		$_SESSION["salesresult"]="ERROR: Invalid Entry";
		header("location:".$glink);
		exit;
	}
	$id = base64_decode($_REQUEST["id"]);
	$query = "delete from sales_report_real where id='$id'";
	if($result = mysql_query($query))
		$_SESSION["salesresult"]="SUCCESS: Report Deleted";
	else
		$_SESSION["salesresult"]="ERROR: Unable To Delete Report, Please try again later";
	header('location:'.$glink.'viewgraph_r.php');
	exit;
}
else
{
	if($task=="reopen")
	{
		$id = base64_decode($_REQUEST["id"]);
		if(!empty($id))
		{
			$query = "update rec_entries set folcome=NULL, status='1', int_show=NULL, int_show_info=NULL, int_show_date=NULL, folupdated_by=NULL, folupdated_date=NULL, compdate=NULL, folstatus='1', compnote=NULL, foldate=NULL, folnote=NULL where id='".$id."'";
			if($result = mysql_query($query))
				$_SESSION["recresult"]="SUCCESS: Information Restarted";
			else
				$_SESSION["recresult"]="ERROR: Unable To Restart Entry, Please try again later";
			header('location:setrec.php?id='.base64_encode($id));
			exit;
		}
		else
		{
			header('location:status.php');
			exit;
		}
	}
	else
	{
		header('location:status.php');
		exit;
	}
}
include "include/unconfig.php";
?>
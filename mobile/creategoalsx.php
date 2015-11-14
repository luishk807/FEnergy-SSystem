<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
adminlogin();
redirect();
$userm = $_SESSION["salesuser"];
if(!pView($userm["type"]))
	$disabled = "disabled='disabled'";
$offices=array();
$otaken=array();
$naval=0;
$query="select distinct id from rec_office";
if($result=mysql_query($query))
{
	$num_rows=mysql_num_rows($result);
	$naval=$num_rows;
	if($num_rows>0)
	{
		while($rows=mysql_fetch_array($result))
		{
			$offices[]=$rows["id"];
		}
	}
}
if(sizeof($offices)>0)
{
	$query="select distinct office from sales_goals_office";
	if($result=mysql_query($query))
	{
		$num_rows=mysql_num_rows($result);
		if($num_rows>0)
		{
			while($rows=mysql_fetch_array($result))
			{
				$otaken[]=$rows["office"];
				for($i=0;$i<sizeof($offices);$i++)
				{
					if($offices[$i]==$rows["office"])
					{
						$naval--;
						break;
					}
				}
			}
		}
	}
}
if($naval<1)
{
	$_SESSION["salesresult"]="No Office Avaliable To Create Goal";
	header("location:viewgoalsx.php");
	exit;
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
 <form action="../save.php" method="post" onsubmit="return checkFieldy()" >
 <input type="hidden" id="task" name="task" value="creategoalsx"/>
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
     <div style="text-align:center">Hello <b><u><?php echo $userm["username"]; ?></u></b>, To Create a brand new goal please fill up the following form.</div><br/>
     <div class='formmessage'>To Begin, please choose an <u>office</u> and then set a <u>goal</u>, you will be <u>taken to another page</u> to set a goal for <u>each managers and team leaders</u> based on the goal set for the office choosen.</div>
      <span id="mform_q">Select Office:</span>
      <br/>
      <select id="uoffice" name="uoffice" class='moselect'>
       <option value="na">Select Office</option>
        <?php
			$q = "select * from rec_office order by name";
			if($r=mysql_query($q))
			{
				if(($numr=mysql_num_rows($r))>0)
				{
					while($row= mysql_fetch_array($r))
					{
						if(sizeof($otaken)>0)
						{
							for($i=0;$i<sizeof($otaken);$i++)
							{
								$found=false;
								if($otaken[$i]==$row["id"])
								{
									$found=true;
									break;
								}
							}
							if(!$found)
								echo "<option value='".base64_encode($row["id"])."'>".stripslashes($row["name"])."</option>";
						}
						else
							echo "<option value='".base64_encode($row["id"])."'>".stripslashes($row["name"])."</option>";
					}
				}
			}
		?>
      </select>
      <br/>
      <br/>
  	   <span id="mform_q">Goal To Set:</span>
       <br/>
       <input type="text" id="ugoal" name="ugoal"size="100" class='mobiletext' value="<?Php echo $user["goals"]; ?>" />
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
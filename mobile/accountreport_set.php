<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
adminlogin();
//redirect();
$user = $_SESSION["salesuser"];
$id = base64_decode($_REQUEST["id"]);
if(!empty($id))
{
	$q ="select * from sales_report where id='".$id."'";
	if($result = mysql_query($q))
	{
		if(($num_rows = mysql_num_rows($result))>0)
			$info = mysql_fetch_assoc($result);
		else
		{
			$_SESSION["salesresult"]="ERROR: Invalid Access";
			header('location:home.php');
			exit;
		}
	}
	else
	{
		$_SESSION["salesresult"]="ERROR: Invalid Access";
		header('location:home.php');
		exit;
	}
}
else
{
	$_SESSION["salesresult"]="ERROR: Invalid Access";
	header('location:home.php');
	exit;
}
date_default_timezone_set('America/New_York');
$userm = $_SESSION["salesuser"];
$thisyear=date("Y");
$thism = date('m');
$thisd = date('d');
$thishx = date('H');
$thish = date('h');
$thismin = date('i');
$thissec = date('s');
if(!empty($info["fromdate"]))
{
	$getfromtimex_h=fixdate_comps('ho',$info["fromtime"]);
	$getfromtimex = explode(":",$info["fromtime"]);
	$getfromdatex = explode("-",$info["fromdate"]);
	$fromyx=$getfromdatex[0];
	$frommx=$getfromdatex[1];
	$fromdx = $getfromdatex[2];
	$fromhx = $getfromtimex_h;
	$fromhx_x=$getfromtimex[0];
	$frommmx = $getfromtimex[1];
	$amsele="";
	$pmsele="";
	if($fromhx_x>11)
		$pmselex="selected='selected'";
	else
		$amselex="selected='selected'";
}
$fromy = $thisyear-50;
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
 <script type="text/javascript" language="javascript">
 $(function()
 {
	//$('.date-pick').datePicker({autoFocusNextInput: true});
	Date.format = 'mm/dd/yyyy';
	$('.date-pick').datePicker({startDate:'01/01/1996'});
 });
function lookup(inputString) {
   if(inputString.length == 0) {
      $('#suggestions').fadeOut(); // Hide the suggestions box
   } else {
      $.post("../rpcm.php", {queryString: ""+inputString+""}, function(data) { // Do an AJAX call
         $('#suggestions').fadeIn(); // Show the suggestions box
         $('#suggestions').html(data); // Fill the suggestions box
      });
   }
}
function addthis(value,acode)
{
	document.getElementById("suggestions").style.display='none';
	document.getElementById("aname").value=value;
	document.getElementById("ucode").value=acode;
}
</script>
</head>

<body>
<?php
include "include/header.php";
?>
<div id="mbody">
           <div style="text-align:center">Hello <b><u><?php echo $user["username"]; ?></u></b>, To Create a brand new Report please fill up the following form.</div><br/>
          <form action="../save.php" method="post" onsubmit="return checkFieldd_ax()">
            <input type="hidden" id="task" name="task" value="savereport"/>
            <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
            <input type="hidden" id="fromdate" name="fromdate" value="" />
            <input type="hidden" id="todate" name="todate" value="" />
            <input type="hidden" id="agentid" name="agentid" value="<?Php echo $info["agentid"]; ?>"/>
            <input type="hidden" id="agentname" name="agentname" value="<?Php echo getAgent($info["agentid"]); ?>"/>
    	    <div id="message" name="message" class="black" style="text-align:center">
        &nbsp;
        <?php
                    if(isset($_SESSION["salesresult"]))
                    {
                        echo $_SESSION["salesresult"];
                        unset($_SESSION["salesresult"]);
                    }
                 ?>
      </div> 
      <br/>
      <span id="mform_q">Manager:</span>
      <br/>
      <select class='moselect' id="uman" name="uman">
      	<option value="na">Select Manager</option>
<?php
                $query = "select * from task_users where type in('5','6','14') order by name desc";
                if($result = mysql_query($query))
                {
                    if(($numr = mysql_num_rows($result))>0)
                    {
                        while($rows = mysql_fetch_array($result))
                        {
							if($info["userid"]==$rows["id"])
							 echo "<option selected='selected' value='".base64_encode($rows["id"])."'>".stripslashes($rows["name"])."</option>";
							else
                            echo "<option value='".base64_encode($rows["id"])."'>".stripslashes($rows["name"])."</option>";
                        }
                    }
                }
                ?>
      </select>
      <br/><br/>
      <span id="mform_q">Agent Name:</span>
      <?php
	  $aname = getAgent($info["agentid"]);
	  ?>
      <br/><input type="text" id="aname" name="aname" size="100" class='mobiletext' value="<?php echo $aname; ?>" onkeyup="lookup(this.value);"  /><div id="suggestions"></div>
  	  <br/>
      <br/>
      <span id="mform_q">Agent Code</span>
      <br/>
      <input type="text" id="ucode" name="ucode" size="100" class='mobiletext' value='<?php echo getAgentInfo('agentcode',$info["agentid"]); ?>' />
  	  <br/>
      <br/>
      <span id="mform_q">Office:</span>
      <br/>
      <select class='moselect' id="uoffice" name="uoffice">
      	<option value="na">Select Office</option>
        <?php
		$query = "select * from rec_office order by name desc";
		if($result = mysql_query($query))
		{
			if(($numr = mysql_num_rows($result))>0)
			{
				while($rows = mysql_fetch_array($result))
				{
					if($info["office"]==$rows["id"])
					echo "<option selected='selected' value='".base64_encode($rows["id"])."'>".stripslashes($rows["name"])."</option>";
					else
					echo "<option value='".base64_encode($rows["id"])."'>".stripslashes($rows["name"])."</option>";
				}
			}
		}
		?>
      </select>
      <br/><br/>
      <span id="mform_q">Number of Sales Made:</span>
      <br/>
      <input type="text" id="usales" name="usales" size="100" class='mobiletext' value='<?php echo $info["stotal"]; ?>' />
  	  <br/>
      <br/>
      <span id="mform_q">From Date:</span>
      <br/>
            <select id="fromm" name="fromm" class='moselect'>
            <option value='na'>Month</option>
            	<?php
				for($x=1;$x<13;$x++)
				{
					$xb = $x;
					if($xb<10)
						$xb="0".$x;
					if($frommx==$xb)
						echo "<option value='$xb' selected='selected'>$xb</option>";
					else
						echo "<option value='$xb'>$xb</option>";
				}
				?>
            </select>&nbsp;/&nbsp;
             <select id="fromd" name="fromd" class='moselect'>
            <option value='na'>Day</option>
            	<?php
				for($x=1;$x<32;$x++)
				{
					$xb = $x;
					if($xb<10)
						$xb="0".$x;
					if($fromdx==$xb)
						echo "<option value='$xb' selected='selected'>$xb</option>";
					else
						echo "<option value='$xb'>$xb</option>";
				}
				?>
            </select>&nbsp;/&nbsp;
             <select id="fromy" name="fromy" class='moselect'>
            	<?php
				for($x=$thisyear;$x>$fromy;$x--)
				{
					$xb = $x;
					if($fromyx==$xb)
						echo "<option value='$xb' selected='selected'>$xb</option>";
					else
						echo "<option value='$xb'>$xb</option>";
				}
				?>
            </select>
            <br/><br/>
            <!--time-->
            &nbsp;&nbsp;&nbsp;<span id="mform_q" style="color:#CCC">Time:</span>&nbsp;<br/>
             <select class="moselect" id="fhour" name="fhour">
                <option value='na'>Hour</option>
                <?php
					for($i=1;$i<13;$i++)
					{
						if($i<10)
							$timer = "0".$i;
						else
							$timer = $i;
						if($timer==$fromhx)
						echo "<option selected='selected' value='$i'>$timer</option>";
						else
						echo "<option value='$i'>$timer</option>";
					}
				?>
                </select>&nbsp;:&nbsp;
                <select class="moselect" id="fminute" name="fminute">
                   <option value='na'>Minutes</option>
                   <?php
					for($i=0;$i<60;$i++)
					{
						if($i<10)
							$timer = "0".$i;
						else
							$timer = $i;
						if($timer==$frommmx)
						echo "<option selected='selected' value='$i'>$timer</option>";
						else
						echo "<option value='$i'>$timer</option>";
					}
				   ?>
                 </select>
                 <select class="moselect" id="fampm" name="fampm">
                     <option value="am" <?php echo $amselex; ?>>AM</option>
                      <option vlaue="pm" <?php echo $pmselex; ?>>PM</option>
                  </select>
              <!--end of time-->
  	  <br/>
      <br/>
      <span id="mform_q">Note:</span>
             <br/><textarea id="udesc" name="udesc" cols="50" rows="10" size="100" class='mobiletextare'><?php echo stripslashes($info["descx"]); ?></textarea>
             <br/><br/>
       <br/><br/>
              <div id="message2" name="message2" class="black" style="text-align:center; padding-right:50px; padding-left:50px">
        &nbsp;
      </div>
       <br/><br/>
       		<?php
			  if(pView($user["type"]))
			  {
				  ?>
      <a href="Javascript:deletetask('report','<?php echo $_REQUEST["id"]; ?>')" onmouseover="document.delete.src='../images/deletebtnm.png'" onmouseout="document.delete.src='../images/deletebtnm.png'"><img src="../images/deletebtnm.png"  border="0" alt="Delete User" name="delete" /></a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      		<?php
			  }
			  ?>
              <input type="image"  src="../images/savebtnm.png" onmouseover="javascript:this.src='../images/savebtnm.png';" onmouseout="javascript:this.src='../images/savebtnm.png';">
          </form>
</div>
</body>
</html>
<?php
include "../include/unconfig.php";
?>
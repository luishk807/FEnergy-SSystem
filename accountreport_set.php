<?Php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
redirect();
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
if(empty($info["fromdate"]))
	$today=date("m/d/Y");
else
	$today= fixdate_comps('invdate_s',$info["fromdate"]);
if(!empty($info["fromtime"]))
{
	$xtime = explode(":",$info["fromtime"]);
	$thishx =$xtime[0];
	$thish = fixdate_comps('ho',$info["fromtime"]);
	$thismin = $xtime[1];
}
else
{
	$thishx = date('H');
	$thish = date('h');
	$thismin = date('i');
}
$amsele="";
$pmsele="";
if($thishx>11)
	$pmsele="selected='selected'";
else
	$amsele="selected='selected'";
$fromy = $thisyear-50;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome To Sales Report System</title>
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script src="js/swfobject_modified.js" type="text/javascript"></script>
<script type="text/javascript" src="js/calendarb_js/date.js"></script>
<script type="text/javascript" src="js/calendarb_js/jquery.datePicker.js"></script>
<link rel="stylesheet" type="text/css" href="css/calendarb.css">
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
      $.post("rpc.php", {queryString: ""+inputString+""}, function(data) { // Do an AJAX call
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
<link rel="icon" type="image/png" href="images/favicon.ico">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="css/styleie.css" />
<![endif]-->
<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>

<body>
<div id="main_cont">
	<?php
	include "include/header.php";
	?>
    <div id="bodycont">
    	<div id="bodycont_header">
        	<div id="body_header_in">
        	Edit Report
            </div>
        </div>
        <div id="bodycont_middle" >
            <div id="bodycont_middle_in">
            	           	<!--start-->
            <div style="text-align:center">Hello <b><u><?php echo $user["username"]; ?></u></b>,To Create a brand new Report please fill up the following form.</div><br/>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <form action="save.php" method="post" onsubmit="return checkFieldv_ax()">
           <input type="hidden" id="task" name="task" value="savereport"/>
            <input type="hidden" id="fromdate" name="fromdate" value="" />
            <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
            <input type="hidden" id="todate" name="todate" value="" />
            <input type="hidden" id="agentid" name="agentid" value="<?Php echo $info["agentid"]; ?>"/>
            <input type="hidden" id="agentname" name="agentname" value="<?Php echo getAgent($info["agentid"]); ?>" />
    	    <tr>
    	      <td colspan="2" align="center" valign="middle"><div id="message" name="message" class="black" style="text-align:center">
        &nbsp;
        <?php
                    if(isset($_SESSION["salesresult"]))
                    {
                        echo $_SESSION["salesresult"]."<br/>";
                        unset($_SESSION["salesresult"]);
                    }
                 ?>
      </div> </td>
   	        </tr>
    	    <tr>
    	      <td height="37" align="right" valign="top">Manager:</td>
    	      <td align="left" valign="top">&nbsp;&nbsp;<select class='moselect' id="uman" name="uman">
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
              </td>
  	      </tr>
    	    <tr>
    	      <td width="27%" height="37" align="right" valign="top">Agent Name:</td>
    	      <td width="73%" align="left" valign="top">&nbsp;&nbsp;<input type="text" id="aname" name="aname" autocomplete="off"
 size="60" onkeyup="lookup(this.value);" value="<?php echo getAgent($info["agentid"]); ?>"/>&nbsp;&nbsp;<div id="suggestions"></div>
 			</td>
  	      </tr>
          <tr>
              <td height="37" align="right" valign="middle">Agent Code:</td>
              <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="ucode" name="ucode" size="60" value='<?php echo getAgentInfo('agentcode',$info["agentid"]); ?>' /></td>
            </tr>
            <tr>
    	      <td height="37" align="right" valign="middle">Office::</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<select class='moselect' id="uoffice" name="uoffice">
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
              </td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Number of Sales Made:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="usales" name="usales" size="60"  value='<?php echo $info["stotal"]; ?>'/></td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Date:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input name="fromdatex" id="fromdatex" class="date-pick" readonly="readonly" value="<?php echo $today; ?>" />
              </td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Time:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<select class="moselect" id="fhour" name="fhour">
                <option value='na'>Hour</option>
                <?php
					for($i=1;$i<13;$i++)
					{
						if($i<10)
							$timer = "0".$i;
						else
							$timer = $i;
						if($timer==$thish)
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
						if($timer==$thismin)
						echo "<option selected='selected' value='$i'>$timer</option>";
						else
						echo "<option value='$i'>$timer</option>";
					}
				   ?>
                 </select>
                 <select class="moselect" id="fampm" name="fampm">
                     <option value="am" <?php echo $amsele; ?>>AM</option>
                      <option vlaue="pm" <?php echo $pmsele; ?>>PM</option>
                  </select>
              </td>
  	      </tr>
          <tr>
    	      <td height="37" align="right" valign="middle" colspan="2">&nbsp;</td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="top">Note:</td>
    	      <td align="left" valign="top">&nbsp;&nbsp;<textarea id="udesc" name="udesc" cols="50" rows="10"><?php echo stripslashes($info["descx"]); ?></textarea>
              </td>
  	      </tr>
    	    <tr>
    	      <td height="47" colspan="2" align="left" valign="middle">
              <div id="message2" name="message2" class="black" style="text-align:center; padding-right:50px; padding-left:50px">
        &nbsp;
      </div>
              </td>
   	        </tr>
    	    <tr>
    	      <td colspan="2" align="center" valign="middle">
              <?php
			  if(pView($user["type"]))
			  {
				  ?>
      <a href="Javascript:deletetask('report','<?php echo $_REQUEST["id"]; ?>')" onmouseover="document.delete.src='images/deletebtn.jpg'" onmouseout="document.delete.src='images/deletebtn.jpg'"><img src="images/deletebtn.jpg"  border="0" alt="Delete User" name="delete" /></a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      		<?php
			  }
			  ?>
              <input type="image"  src="images/savebtn.jpg" onmouseover="javascript:this.src='images/savebtn.jpg';" onmouseout="javascript:this.src='images/savebtn.jpg';"/>
              </td>
  	      </tr>
    	    <tr>
    	      <td colspan="2" align="left" valign="middle">&nbsp;</td>
  	      </tr>
          </form>
        </table>
                <!--end-->
            </div>
        </div>
        <div id="bodycont_footer">
        </div>
    </div>
    <div class="clearfooter"></div>
</div>
<?Php
include "include/footer.php";
?>
</body>
</html>
<?php
include "include/unconfig.php";
?>
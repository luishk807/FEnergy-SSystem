<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
adminlogin();
//redirect();
$user = $_SESSION["salesuser"];
date_default_timezone_set('America/New_York');
$userm = $_SESSION["salesuser"];
$thisyear=date("Y");
$thism = date('m');
$thisd = date('d');
$thishx = date('H');
$thish = date('h');
$thismin = date('i');
$thissec = date('s');
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
<title>Welcome To Family Energy Sales Report System</title>
<script type="text/javascript" language="javascript" src="../js/script.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script src="../js/swfobject_modified.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript">
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
           <div style="text-align:center">Hello <b><u><?php echo $user["username"]; ?></u></b>, To Create a brand new Report please fill up the following form.</div><br/>
          <form action="../save.php" method="post" onsubmit="return checkFieldd_a()">
            <input type="hidden" id="task" name="task" value="createreport"/>
            <input type="hidden" id="fromdate" name="fromdate" value="" />
            <input type="hidden" id="todate" name="todate" value="" />
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
      <span id="mform_q">Agent Name:</span>
      <br/><input type="text" id="aname" name="aname" autocomplete="off"
 size="100" class='mobiletext' onkeyup="lookup(this.value);"  /><div id="suggestions"></div>
  	  <br/>
      <br/>
      <span id="mform_q">Agent Code:</span>
      <br/><input type="text" id="ucode" name="ucode" size="100" class='mobiletext'  />
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
					echo "<option value='".base64_encode($rows["id"])."'>".stripslashes($rows["name"])."</option>";
				}
			}
		}
		?>
      </select>
      <br/><br/>
      <span id="mform_q">Number of Sales Made:</span>
      <br/>
      <span id="mform_q"></span>&nbsp;<input type="text" id="usales" name="usales" size="100" class='mobiletext'  />
  	  <br/>
      <br/>
      <span id="mform_q">Date:</span>
      <br/>
            <select id="fromm" name="fromm" class='moselect'>
            <option value='na'>Month</option>
            	<?php
				for($x=1;$x<13;$x++)
				{
					$xb = $x;
					if($xb<10)
						$xb="0".$x;
					if($thism==$xb)
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
					if($thisd==$xb)
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
					if($regdate[0]==$xb)
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
              <!--end of time-->
  	  <br/>
      <br/>
      <span id="mform_q">Note:</span>
             <br/><textarea id="udesc" name="udesc" cols="50" rows="10" size="100" class='mobiletextare'><?php echo stripslashes($user["description"]); ?></textarea>
             <br/><br/>
       <br/><br/>
              <div id="message2" name="message2" class="black" style="text-align:center; padding-right:50px; padding-left:50px">
        &nbsp;
      </div>
       <br/><br/>
              <input type="image"  src="../images/createbtnm.png" onmouseover="javascript:this.src='../images/createbtnm.png';" onmouseout="javascript:this.src='../images/createbtnm.png';">
          </form>
</div>
</body>
</html>
<?php
include "../include/unconfig.php";
?>
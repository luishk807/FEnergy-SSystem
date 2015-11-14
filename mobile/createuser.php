<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
adminlogin();
redirect();
$userm = $_SESSION["salesuser"];
if(!pView($userm["type"]))
	$disabled = "disabled='disabled'";
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
 <form action="../save.php" method="post" onsubmit="return checkFieldg()" >
 <input type="hidden" id="changepass" name="changepass" value="no"/>
 <input type="hidden" id="task" name="task" value="create"/>
 <input type="hidden" id="cphone" name="cphone" value="" />
 <input type="hidden" id="checktype" name="checktype" value="no" />
 <input type="hidden" id="checkreportt" name="checkreportt" value="no" />
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
     <div style="text-align:center">Hello <b><u><?php echo $userm["username"]; ?></u></b>, To Create a brand new user please fill up the following form.</div><br/>
      <span id="mform_q">Username:</span>
      <br/>
      <input type="text" id="uname" name="uname" size="100" class='mobiletext' value="<?php echo $user["username"]; ?>" />
      <br/>
      <br/>
  	   <span id="mform_q">Email:</span>
       <br/>
       <input type="text" id="uemail" name="uemail" size="100" class='mobiletext' />
       <br/><br/>
       <div id="allowpassworddiv" name="allowpassworddiv">
            <span id="mform_q">New Password: </span>
            <br/>
            <input type="password" id="newpass" name="newpass" size="100" class='mobiletext' />
            <br/><br/>
            <span id="mform_q">Re-Type Password:</span>
            <br/>
             <input type="password" id="renewpass" name="renewpass" size="100" class='mobiletext' />
              </div>
           <span id="mform_q">Name:</span>
           <br/>
    	   <input type="text" id="realname" name="realname" size="100" class='mobiletext' value="<?Php echo $user["name"]; ?>" />
           <br/><br/>
           <span id="mform_q">Phone Number:</span>
    	   <br/>
           <?php
           $fixnum = @fixnum($user["phone"]);
           ?>
           (<input type="text" id="cphonea" name="cphonea" size="5" value="<?php echo @$fixnum[0] ?>" maxlength="3" class='mobiletext_p1'/>) - <input type="text" id="cphoneb" name="cphoneb" size="10" value="<?php echo @$fixnum[1]; ?>" maxlength="3" class='mobiletext_p2' /> - <input type="text" id="cphonec" name="cphonec" size="20" value="<?php echo @$fixnum[2] ?>" maxlength="6" class='mobiletext_p3' />
           <br/><br/>
           <span id="mform_q">Title:</span>
           <br/>
    	   <input type="text" id="utitle" name="utitle" size="100" class='mobiletext' value="<?Php echo $user["title"]; ?>"/>
           <br/><br/>
  	      <span id="mform_q">Status:</span>
    	  <br/>
          <select id="ustatus" name="ustatus"  class='moselect'>
                <?php
					$query = "select * from task_users_status order by id";
					if($result = mysql_query($query))
					{
						if(($num_rows = mysql_num_rows($result))>0)
						{
							while($rows = mysql_fetch_array($result))
							{
								if(!empty($user["status"]))
								{
									if($user["status"]==$rows["id"])
										echo "<option value='".$rows["id"]."' selected='selected'>".$rows["name"]."</option>";
									else
										echo "<option value='".$rows["id"]."'>".$rows["name"]."</option>";
								}
								else
									echo "<option value='".$rows["id"]."'>".$rows["name"]."</option>";
							}
						}
					}
				?>
              </select>
          <br/><br/>
          <span id="mform_q">Type:</span>
    	  <br/>
                <select id="utype" name="utype"  class='moselect' onchange="allowofficeman(this.value)">
                <?php
					$query = "select * from task_admin_type order by id";
					if($result = mysql_query($query))
					{
						if(($num_rows = mysql_num_rows($result))>0)
						{
							while($rows = mysql_fetch_array($result))
							{
								if(!empty($user["type"]))
								{
									if($user["type"]==$rows["id"])
										echo "<option value='".$rows["id"]."' selected='selected'>".$rows["name"]."</option>";
									else
										echo "<option value='".$rows["id"]."'>".$rows["name"]."</option>";
								}
								else
									echo "<option value='".$rows["id"]."'>".$rows["name"]."</option>";
							}
						}
					}
				?>
              </select>
          <br/><br/>
          <div id="officemandiv" name="officemandiv" style="display:none">
          <span id="mform_q">Manager For Office:</span>
    	  <br/>
                <select id="officeman" name="officeman" class='moselect'>
                  <option value="na">Select Office</option>
                  <?php
						$qo="select * from rec_office order by id";
						if($ro = mysql_query($qo))
						{
							if(($numro = mysql_num_rows($ro))>0)
							{
								while($rowo = mysql_fetch_array($ro))
								{
									echo "<option value='".base64_encode($rowo["id"])."'>".stripslashes($rowo["name"])."</option>";
								}
							}
						}
				  ?>
                  </select>
          <br/><br/>
          </div>
          <div id="reporttodiv" name="reporttodiv" style="display:none">
          <span id="mform_q">Report To:</span>
    	  <br/>
                <select id="reportto" name="reportto" class='moselect'>
                  <option value="na">Select Manager</option>
                  <?php
						$qo="select * from task_users where type='6' order by id";
						if($ro = mysql_query($qo))
						{
							if(($numro = mysql_num_rows($ro))>0)
							{
								while($rowo = mysql_fetch_array($ro))
								{
									echo "<option value='".base64_encode($rowo["id"])."'>".stripslashes($rowo["name"])."</option>";
								}
							}
						}
				  ?>
                  </select>
          <br/><br/>
          </div>
              <div id="message2" name="message2" class="black" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
         <br/>
              <input type="image"  src="../images/savebtnm.png" onmouseover="javascript:this.src='../images/savebtnm.png';" onmouseout="javascript:this.src='../images/savebtnm.png';">
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
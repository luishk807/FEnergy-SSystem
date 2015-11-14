<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
adminlogin();
redirect();
$userm = $_SESSION["salesuser"];
$id = base64_decode($_REQUEST["id"]);
$query = "select * from task_users where id='".clean($id)."'";
if($result = mysql_query($query))
{
	if(($num_rows= mysql_num_rows($result))>0)
		$user = mysql_fetch_assoc($result);
}
if(!pView($userm["type"]))
	$disabled = "disabled='disabled'";
$checktype='no';
$checkreportt='no';
$showmano = "style='display:none'";
$showreportt = "style='display:none'";
if(checkManSel($user["type"],$user["office"]))
	$checktype="yes";
if(showChooseMan($user["type"]))
	$showmano="";
if(checkReportTo($user["type"]))
	$checkreportt="yes";
if($checkreportt=="yes")
	$showreportt="";
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
        <!--start-->
       <div style="text-align:center">Hello <b><u><?php echo $userm["username"]; ?></u></b>, Use this page is edit information for this user.</div><br/>
          <form action="../save.php" method="post" onsubmit="return checkFieldd()">
          	<input type="hidden" id="changepass" name="changepass" value="no"/>
            <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
            <input type="hidden" id="task" name="task" value="savem"/>
            <input type="hidden" id="cphone" name="cphone" value="" />
            <input type="hidden" id="cutype" name="cutype" value="<?php echo $user["type"]; ?>" />
            <input type="hidden" id="checktype" name="checktype" value="<?php echo $checktype; ?>"  />
            <input type="hidden" id="checkreportt" name="checkreportt" value="<?php echo $checkreportt; ?>" />
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
      <span id="mform_q">Username:</span>
      <br/><input type="text" id="uname" name="uname" size="100" class='mobiletext' value="<?php echo $user["username"]; ?>" />
      <br/><br/>
      <span id="mform_q">Change Password?:</span><input type="checkbox" id="checkchange" name="checkchange" onclick="allowpassword()" />
      <br/><br/>
      <span id="mform_q">Email:</span>
      <br/><input type="text" id="uemail" name="uemail" size="100" class='mobiletext' value="<?Php echo $user["email"]; ?>" />
      <br/><br/>
              <div id="allowpassworddiv" name="allowpassworddiv" style="display:none">
              <fieldset>
        	<legend><span id="mform_q">Change Password</span></legend>
             <span id="mform_q">New Password:</span>
             <br/>
             <input type="password" id="newpass" name="newpass" size="100" class='mobiletext' />
             <br/><br/>
             <span id="mform_q">Re-Type Password:</span>
             <br/><input type="password" id="renewpass" name="renewpass" size="100" class='mobiletext' />
             <br/>
			</fieldset>
              </div>
           <br/>
           <span id="mform_q">Name:</span>
           <br/><input type="text" id="realname" name="realname" size="100" class='mobiletext' value="<?Php echo $user["name"]; ?>" />
           <br/><br/>
           <span id="mform_q">Phone Number:</span>
           <br/>
              	<?Php
                    $fixnum = @fixnum($user["phone"]);
                 ?>
              		(<input type="text" id="cphonea" name="cphonea" size="5" value="<?php echo @$fixnum[0] ?>" maxlength="3" class='mobiletext_p1'/>) - <input type="text" id="cphoneb" name="cphoneb" size="10" value="<?php echo @$fixnum[1]; ?>" maxlength="3" class='mobiletext_p2'/> - <input type="text" id="cphonec" name="cphonec" size="20" value="<?php echo @$fixnum[2] ?>" maxlength="6" class='mobiletext_p3' />
            <br/><br/>
            <span id="mform_q">Title:</span>
            <br/>
            <input type="text" id="utitle" name="utitle" size="100" class='mobiletext' value="<?Php echo $user["title"]; ?>"/>
            <br/><br/>
            <span id="mform_q">Status:</span>
            <br/>
            <select id="ustatus" name="ustatus" class='moselect' <?php echo $disabled; ?>>
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
                <select id="utype" name="utype" class='moselect' <?php echo $disabled; ?> onchange="allowofficeman(this.value)">
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
          <div id="officemandiv" name="officemandiv" <?php echo $showmano; ?>>
          <span id="mform_q">Manager For Office:</span>
           <br/>
               <select id="officeman" name="officeman" class='moselect' >
                  <option value="na">Select Office</option>
                  <?php
						$qo="select * from rec_office order by id";
						if($ro = mysql_query($qo))
						{
							if(($numro = mysql_num_rows($ro))>0)
							{
								while($rowo = mysql_fetch_array($ro))
								{
									if($user["office"]==$rowo["id"])
									echo "<option  selected='selected' value='".base64_encode($rowo["id"])."'>".stripslashes($rowo["name"])."</option>";
									else
									echo "<option value='".base64_encode($rowo["id"])."'>".stripslashes($rowo["name"])."</option>";
								}
							}
						}
				  ?>
                  </select>
          <br/><br/>
          </div>
         <div id="reporttodiv" name="reporttodiv" <?php echo $showreportt; ?>>
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
									if($user["report_to"]==$rowo["id"])
									echo "<option  selected='selected' value='".base64_encode($rowo["id"])."'>".stripslashes($rowo["name"])."</option>";
									else
									echo "<option value='".base64_encode($rowo["id"])."'>".stripslashes($rowo["name"])."</option>";
								}
							}
						}
				  ?>
                  </select>
          <br/><br/>
          </div>
              <div id="message2" name="message2" class="black" style="text-align:center; padding-right:50px; padding-left:50px">
        &nbsp;
      </div>
       <br/><br/>
              <?php
			  if(pView($userm["type"]))
			  {
				  ?>
      <a href="Javascript:deletetask('users','<?php echo $_REQUEST["id"]; ?>')" onmouseover="document.delete.src='../images/deletebtnm.png'" onmouseout="document.delete.src='../images/deletebtnm.png'"><img src="../images/deletebtnm.png"  border="0" alt="Delete User" name="delete" /></a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      		<?php
			  }
			  ?>
              <input type="image"  src="../images/savebtnm.png" onmouseover="javascript:this.src='../images/savebtnm.png';" onmouseout="javascript:this.src='../images/savebtnm.png';">
          </form>
                <!--end-->    
</div>
</body>
</html>
<?php
include "../include/unconfig.php";
?>
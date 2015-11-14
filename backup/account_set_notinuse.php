<?Php
session_start();
include "include/config.php";
include "include/function.php";
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
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script src="js/swfobject_modified.js" type="text/javascript"></script>
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
        	User Information
            </div>
        </div>
        <div id="bodycont_middle" >
            <div id="bodycont_middle_in">
            	           	<!--start-->
                           <div style="text-align:center">Hello <b><u><?php echo $userm["username"]; ?></u></b>, Use this page is edit information for this user.</div><br/>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <form action="save.php" method="post" onsubmit="return checkFieldd()">
          	<input type="hidden" id="changepass" name="changepass" value="no"/>
            <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
            <input type="hidden" id="task" name="task" value="savem"/>
            <input type="hidden" id="cphone" name="cphone" value="" />
            <input type="hidden" id="cutype" name="cutype" value="<?php echo $user["type"]; ?>" />
            <input type="hidden" id="checktype" name="checktype" value="<?php echo $checktype; ?>"  />
            <input type="hidden" id="checkreportt" name="checkreportt" value="<?php echo $checkreportt; ?>" />
    	    <tr>
    	      <td colspan="2" align="center" valign="middle"><div id="message" name="message" class="black" style="text-align:center">
        &nbsp;
        <?php
                    if(isset($_SESSION["salesresult"]))
                    {
                        echo $_SESSION["salesresult"];
                        unset($_SESSION["salesresult"]);
                    }
                 ?>
      </div> </td>
   	        </tr>
    	    <tr>
    	      <td width="27%" height="37" align="right" valign="middle">Username:</td>
    	      <td width="73%" align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="uname" name="uname" size="60" value="<?php echo $user["username"]; ?>" /></td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Change Password?:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="checkbox" id="checkchange" name="checkchange" onclick="allowpassword()" /></td>
  	      </tr>
            <tr>
    	      <td height="37" align="right" valign="middle">Email:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="uemail" name="uemail" size="60" value="<?Php echo $user["email"]; ?>" /></td>
  	      </tr>
    	    <tr>
    	      <td  colspan="2" align="right" valign="middle">
              <div id="allowpassworddiv" name="allowpassworddiv" style="display:none">
              	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="27%" height="36" align="right" valign="middle">New Password: </td>
                    <td width="73%" align="left" valign="middle">&nbsp;&nbsp;<input type="password" id="newpass" name="newpass" size="60" /></td>
                  </tr>
                  <tr>
                    <td height="36" align="right" valign="middle">Re-Type Password:</td>
                    <td align="left" valign="middle">&nbsp;&nbsp;<input type="password" id="renewpass" name="renewpass" size="60" /></td>
                  </tr>
                </table>

              </div>
              </td>
   	        </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Name:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="realname" name="realname" size="60" value="<?Php echo $user["name"]; ?>" /></td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Phone Number:</td>
    	      <td align="left" valign="middle">&nbsp;
              	<?Php
                    $fixnum = @fixnum($user["phone"]);
                 ?>
              		(<input type="text" id="cphonea" name="cphonea" size="5" value="<?php echo @$fixnum[0] ?>" maxlength="3"/>) - <input type="text" id="cphoneb" name="cphoneb" size="10" value="<?php echo @$fixnum[1]; ?>" maxlength="3" /> - <input type="text" id="cphonec" name="cphonec" size="20" value="<?php echo @$fixnum[2] ?>" maxlength="6" />
              </td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Title:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="utitle" name="utitle" size="60" value="<?Php echo $user["title"]; ?>"/></td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Status:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;  <select id="ustatus" name="ustatus" <?php echo $disabled; ?>>
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
              </td>
  	      </tr>
    	    <tr>
    	      <td height="37" align="right" valign="middle">Type:</td>
    	      <td align="left" valign="middle">
              	&nbsp;&nbsp;

                <select id="utype" name="utype" <?php echo $disabled; ?> onchange="allowofficeman(this.value)">
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
              </td>
  	      </tr>
          <tr>
    	      <td  colspan="2" align="left" valign="middle">
              <div id="officemandiv" name="officemandiv" <?php echo $showmano; ?>>
              	<table width="100%" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="27%" height="37" align="right" valign="middle">Manager For Office:</td>
    	          <td width="73%" align="left" valign="middle">&nbsp;&nbsp;
                  <select id="officeman" name="officeman">
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
                  </td>
  	          </tr>
  	        </table>
            	</div>
            </td>
   	        </tr>
           <tr>
    	      <td  colspan="2" align="left" valign="middle">
              <div id="reporttodiv" name="reporttodiv" <?php echo $showreportt; ?>>
              	<table width="100%" border="0" cellspacing="0" cellpadding="0">
    	        <tr>
    	          <td width="27%" height="37" align="right" valign="middle">Report To:</td>
    	          <td width="73%" align="left" valign="middle">&nbsp;&nbsp;
                  <select id="reportto" name="reportto">
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
                  </td>
  	          </tr>
  	        </table>
            	</div>
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
               <a href="viewusers.php" onmouseover="document.cancel.src='images/cancelbtn.jpg'" onmouseout="document.cancel.src='images/cancelbtn.jpg'"><img src="images/cancelbtn.jpg"  border="0" alt="Cancel" name="cancel" /></a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <?php
			  if(pView($userm["type"]))
			  {
				  ?>
      <a href="Javascript:deletetask('users','<?php echo $_REQUEST["id"]; ?>')" onmouseover="document.delete.src='images/deletebtn.jpg'" onmouseout="document.delete.src='images/deletebtn.jpg'"><img src="images/deletebtn.jpg"  border="0" alt="Delete User" name="delete" /></a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      		<?php
			  }
			  ?>
              <input type="image"  src="images/savebtn.jpg" onmouseover="javascript:this.src='images/savebtn.jpg';" onmouseout="javascript:this.src='images/savebtn.jpg';">
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
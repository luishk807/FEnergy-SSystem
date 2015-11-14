<?Php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
redirect();
$userm = $_SESSION["salesuser"];
$id = base64_decode($_REQUEST["id"]);
$query = "select * from sales_agent where id='".clean($id)."'";
if($result = mysql_query($query))
{
	if(($num_rows= mysql_num_rows($result))>0)
		$user = mysql_fetch_assoc($result);
}
if(!pView($userm["type"]))
	$disabled = "disabled='disabled'";
$allowdup="no";
$qup="select * from sales_agent where id !='".$id."' order by name";
if($rq=mysql_query($qup))
{
	if(($ndup=mysql_num_rows($rq))>0)
		$allowdup="yes";
}
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
<script type="text/javascript" language="javascript">
function allowdup_div()
{
	//script to uncheckbox
	checkbox_marker("agentform","mdup_in",false)
	var checkthis=document.getElementById("mdup").checked;
	if(checkthis==true)
		document.getElementById("mdup_div").style.display="block";
	else
		document.getElementById("mdup_div").style.display="none";
}
</script>
<style>
.agentf_title{
	font-family:'rockw'; font-size:13pt; background:#00477f; color:#FFF;
}
.agentf_row{
	font-family:'rockw'; font-size:14pt; background:#9fd3fc;
}
.agentf_rowb{
	font-family:'rockw'; font-size:14pt;
}
.agentf_nfound{
	font-family:'rockw'; font-size:14pt; background:#900; color:#FFF;
}
.agentstitle{
	font-family:'rockw'; font-size:15pt;
}
#mdup_div_in{
	height:200px; overflow:auto;border:1px solid; text-align:left;
}
</style>
</head>

<body>
<div id="main_cont">
	<?php
	include "include/header.php";
	?>
    <div id="bodycont">
    	<div id="bodycont_header">
        	<div id="body_header_in">
        	Agent Information
            </div>
        </div>
        <div id="bodycont_middle" >
            <div id="bodycont_middle_in">
            	           	<!--start-->
                           <div style="text-align:center">Hello <b><u><?php echo $userm["username"]; ?></u></b>, Use this page is edit information for this agent.</div><br/>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <form action="save.php" method="post" id="agentform" name="agentform" onsubmit="return checkFieldagenti()">
            <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
            <input type="hidden" id="task" name="task" value="saveagent"/>
            <input type="hidden" id="checkdup" name="checkdup" value="<?php echo $allowdup; ?>" />
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
    	      <td width="27%" height="37" align="right" valign="middle">Name:</td>
    	      <td width="73%" align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="uname" name="uname" size="60" value="<?php echo stripslashes($user["name"]); ?>" /></td>
  	      </tr>
            <tr>
    	      <td height="37" align="right" valign="middle">Agent Code:</td>
    	      <td align="left" valign="middle">&nbsp;&nbsp;<input type="text" id="ucode" name="ucode" size="60" value="<?Php echo $user["acode"]; ?>" /></td>
  	      </tr>
          <?php
		  if($allowdup=="yes")
		  {
				?>
            <tr>
              <td height="37" align="right" valign="middle">Merge Dupplicates?:</td>
              <td align="left" valign="middle">&nbsp;<input type="checkbox" id="mdup" name="mdup" onclick="allowdup_div()" /></td>
            </tr>
           <?php
		  }
		  ?>
            <tr>
              <td height="5" colspan="2" align="right" valign="middle"></td>
            </tr>
            <tr>
              <td height="37" colspan="2" align="left" valign="middle">
              <div id="mdup_div" name="mdup_div" style="display:none">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="27%" height="37" align="right" valign="top">Choose Agents To Merge: </td>
                    <td width="73%" height="200" align="left" valign="top">
                    	<div id="mdup_div_in">
                        <?php
						if($rq=mysql_query($qup))
						{
							if(($ndup=mysql_num_rows($rq))>0)
							{
								while($rqx=mysql_fetch_array($rq))
								{
									if(!empty($rqx["acode"]))
										$axcode="<span style='color:#999;font-family:\'rockw\'; font-size:13pt'> < ".stripslashes($rqx["acode"])." > </span>";
									else
										$axcode="";
									echo "&nbsp;<input type='checkbox' id='mdup_in' name='mdup_in[]' value='".base64_encode($rqx["id"])."'/>&nbsp;&nbsp;".stripslashes($rqx["name"])."&nbsp;&nbsp;".$axcode."<br/>";
								}
							}
						}
						?>
                        </div>
                    </td>
                  </tr>
                </table>
              </div>
              </td>
            </tr>
    	    <tr>
    	      <td colspan="2" align="center" valign="middle">&nbsp;</td>
   	        </tr>
    	    <tr>
    	      <td colspan="2" align="center" valign="middle"><span class='agentstitle'>Sales From This Person</span></td>
  	      </tr>
    	    <tr>
    	      <td height="300" colspan="2" align="left" valign="top">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
    	      <tr class='agentf_title'>
    	          <td width="9%" height="24" align="center" valign="bottom">&nbsp;</td>
    	          <td align='center' valign='bottom'>Team Leader/Manager</td>
    	          <td width='17%' align='center' valign='bottom'>Office</td>
    	          <td width='17%' align='center' valign='bottom'>Sales</td>
    	          <td width='17%' align='center' valign='bottom'>Date</td>
  	          </tr>
              <tr>
              	<td colspan="6" align="left">
                	<div style="height:300px;overflow:auto;">
                    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <?php
						  $qx="select * from sales_report where agentid='".$id."' order by fromdate desc";
						  if($rx=mysql_query($qx))
						  {
							  if(($numx=mysql_num_rows($rx))>0)
							  {
								  $cox=1;
								  $totalx=0;
								  while($rox=mysql_fetch_array($rx))
								  {
									 $cstyle="class='agentf_row'";
									 $totalx = $cox%2;
									 if($totalx==0)
										$cstyle="class='agentf_row'";
									else
										$cstyle="class='agentf_rowb'";
									 echo "<tr ".$cstyle."><td align='center' valign='middle' width='9%' height='24'>".$cox."</td><td align='center' valign='middle'><a href='account_set.php?id=".base64_encode($rox["userid"])."' class='contlinkb'>".getName($rox["userid"])."</a></td><td width='17%' align='center' valign='middle'>".getOfficeName($rox["office"])."</td><td width='17%' align='center' valign='middle'><a href='accountreport_set.php?id=".base64_encode($rox["id"])."' class='contlinkb'>".$rox["stotal"]."</a></td><td width='17%' align='center' valign='middle'>".fixdate_comps('invdate_s',$rox["date"])."</td></tr>";
									 $cox++;
								  }
							  }
							  else
								echo "<tr class='agentf_nfound'><td colspan='5' align='center' valign='middle'>No Sales Found</td></tr>";
						  }
						  else
							echo "<tr class='agentf_nfound'><td colspan='5' align='center' valign='middle'>No Sales Found</td></tr>";
						  ?>
						</table>
                    </div>
                </td>
              </tr>
  	        </table>
              </td>
  	      </tr>
    	    <tr>
    	      <td height="47" colspan="2" align="left" valign="middle">
              <div id="message2" name="message2" class="black" style="text-align:center; padding-right:50px; padding-left:50px">&nbsp;</div>
              </td>
   	        </tr>
    	    <tr>
    	      <td colspan="2" align="center" valign="middle">
               <a href="viewagents.php" onmouseover="document.cancel.src='images/cancelbtn.jpg'" onmouseout="document.cancel.src='images/cancelbtn.jpg'"><img src="images/cancelbtn.jpg"  border="0" alt="Cancel" name="cancel" /></a>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <?php
			  if(pView($userm["type"]))
			  {
				  ?>
      <a href="Javascript:deletetask('agent','<?php echo $_REQUEST["id"]; ?>')" onmouseover="document.delete.src='images/deletebtn.jpg'" onmouseout="document.delete.src='images/deletebtn.jpg'"><img src="images/deletebtn.jpg"  border="0" alt="Delete User" name="delete" /></a>
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
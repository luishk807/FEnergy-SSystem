<?Php
session_start();
include "include/config.php";
include "include/function.php";
adminlogin();
$userx = $_SESSION["salesuser"];
date_default_timezone_set('America/New_York');
$xid = base64_decode($_REQUEST["id"]);
$umanb = $_REQUEST["umanb"];
if(!empty($umanb) && $umanb !='na')
{
	if($umanb !='all')
		$xid = base64_decode($umanb);
}
$weekday = date('l');
$today = date('Y-m-d');
$tday = getCalDate($weekday);
$gtotal=0.00;
$thisyear=date("Y");
$fromy = $thisyear-50;
//from search input
$sfromm = $_REQUEST["date1"];
$stoy = $_REQUEST["date2"];
$sdatef="";
$sdatet="";
$dquery="";
$showrun=true;
$showreturn =false;
$showd_date=false;
$soffice = $_REQUEST["soffice"];
$sagentx = $_REQUEST["sagent"];
if(!empty($sfromm))
	$sdatef = fixdate_comps('mildate',$sfromm);
if(!empty($stoy))
	$sdatet = fixdate_comps('mildate',$stoy);
if(!empty($sdatef) || !empty($sdatet))
{
	if(empty($sdatef))
		$sdatef = $sdatet;
	if(empty($sdatet))
		$sdatet =$sdatef;
}
if($sagentx !='na' && $sagentx !='all')
{
	$sagentxx = base64_decode($sagentx);
	if(!empty($sagentxx))
		$dquery = " and agentid='".$sagentxx."' ";
}
else
	$sagentxx="";
if($soffice != 'na' && $soffice !='all')
{
	$sofficexx=base64_decode($soffice);
	if(!empty($sofficexx))
		$dquery .= " and office='".$sofficexx."' ";
}
else
	$sofficexx="";
if(!empty($sagentx) || !empty($soffice) || !empty($umanb) || !empty($_REQUEST["date1"]) || !empty($_REQUEST["date2"]))
	$showreturn=true;
if(!empty($sdatet) && !empty($sdatef))
{
	$dquery .= "and (fromdate between '".$sdatef."' and '".$sdatet."') order by fromdate desc";
	$tday = $sdatef;
	$today = $sdatet;
	$showrun=false;
}
else
{
	if($showreturn)
	{
		if(empty($_REQUEST["date1"]) || empty($_REQUEST["date2"]))
			$dquery .= " order by fromdate desc";
		else
			$dquery .= "and fromdate='".$today."' order by fromdate desc";
	}
	else
		$dquery .= "and fromdate='".$today."' order by fromdate desc";
}
//end of search input;
if(!pViewm($userx["type"]))
{
	$assignid=$userx["id"];
	$pdayx = getPreviousDate($id);
	$pday = explode(" ",$pdayx);
	$rtotal = getRunTotal($userx["id"],$tday,$today);
	$query = "select * from sales_report where userid='".$assignid."' $dquery";
}
else
{
	if(!empty($xid))
		$xqid = " where userid='".$xid."'";
	else
		$xqid="";
	$query = "select * from sales_report $xqid order by fromdate desc limit 1";
	if($result = mysql_query($query))
	{
		if(($num_rows = mysql_num_rows($result))>0)
		{
			$info = mysql_fetch_assoc($result);
			$assignid=$info["userid"];
			$pdayx = explode(" ",$info["todate"]);
			$pday = $pdayx[0];
			$rtotal = getRunTotal($assignid,$tday,$today);
		}
	}
	//assign manager on the manager dropdown box
	$nomanager="";
	if(empty($xid))
	{
		if($umanb !='all' && $umanb !='na')
			$xid = $assignid;
		else
		{
			$xid='';
			$nomanager="selected='selected'";
		}
	}
	else
	{
		if($umanb=='all' || $umanb =='na')
			$nomanager="selected='selected'";
	}
	if($umanb =='all')
		$userids="userid is not null ";
	else
		$userids="userid='".$assignid."'";
	if($showreturn)
	{
		$showrun=false;
		if(empty($_REQUEST["date1"]) || empty($_REQUEST["date2"]))
		{
			$datecompx="";
			$showd_date=true;
		}
		else
		{
			$xdate1=fixdate_comps('mildate',$_REQUEST["date1"]);
			$xdate2=fixdate_comps('mildate',$_REQUEST["date2"]);
			$datecompx=" and (fromdate between '".$xdate1."' and '".$xdate2."')";
		}
		$rtotal = getRunTotal_search($userids,$sofficexx,$sagentxx,$datecompx);
	}
	$query = "select * from sales_report where $userids $dquery";
}
$height="style='height:700px'";
if(!empty($query))
{
	if($result = mysql_query($query))
	{
		if(($numrows = mysql_num_rows($result))>5)
			$height="";
	}
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
function changeview(value)
{
	if(value !='na')
		window.location.href='viewreport.php?id='+value;
}
$(document).ready(function()
	{
        $(".slidingDiv").hide();
        $(".show_this").show();

		$('.show_this').click(function()
		{
			$(".slidingDiv").slideToggle();
		}
);
});
</script>
<link rel="icon" type="image/png" href="images/favicon.ico">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="css/styleie.css" />
<![endif]-->
<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>

<body>
<div id="main_cont">
	<!--header-->
<?php
include "include/header.php";
?>
    <!--end of header-->
    <div id="lbodycont">
    	<div id="lbodycont_header">
        	<div id="lbody_header_in">Sales Report</div>
        </div>
        <div id="lbodycont_middle">
            <div id="lbodycont_middle_in" <?php echo $height; ?>>
           		 <div id="tabmenu">
						<div id="tabmenu_in"><a href='viewgraph_r.php' class='contlinkc' >View Annual Sales Performance</a>&nbsp;&nbsp;&nbsp;</div>
				  </div>
				  <br/>
            	<?php
				if($showreturn)
					echo "<div id='tabmenu'><div id='tabmenu_in'><a href='viewreport.php' class='contlinkc' >Back To Previous</a></div></div>";
				?>
				<form action="viewreport.php" method="post">
                <input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
                     <div id="message" name="message" class="black" style="text-align:center">
                     &nbsp;
                     <?php
                     if(isset($_SESSION["salesresult"]))
                     {
                        echo $_SESSION["salesresult"]."<br/>";
                        unset($_SESSION["salesresult"]);
                     }
                     ?>
                    </div>
                    <div id="reportmanager">
						<?Php
                        if(pViewm($userx["type"]))
                        {
                        ?>
                        <span class='mform_q'>Report For Manager:</span>
                <select id="umanager" name="umanager" class='moselect' onchange="changeview(this.value);">
                			<option value='na' <?Php echo $nomanager; ?>>Select A Manager</option>
                            <?php
                            $qa="select * from task_users where type in('5','6','7') order by name";
                            if($ra = mysql_query($qa))
                            {
                                if(($na = mysql_num_rows($ra))>0)
                                {
                                    while($rowa = mysql_fetch_array($ra))
                                    {
										if(empty($nomanager))
										{
											if($xid==$rowa["id"])
											echo "<option value='".base64_encode($rowa["id"])."' selected='selected'>".stripslashes($rowa["name"])."</option>";
											else
											echo "<option value='".base64_encode($rowa["id"])."'>".stripslashes($rowa["name"])."</option>";
										}
										else
											echo "<option value='".base64_encode($rowa["id"])."'>".stripslashes($rowa["name"])."</option>";
                                    }
                                }
                            }
                            ?>
                      </select>
                        <?php
                        }
                        else
                        {
                        ?>
                        <span class='mform_q'>Report For Manager: <?php echo $userx["name"]; ?></span>
                        <?Php
                        }
                        ?>
                  </div>
                    <!--search stuff-->
                    <?Php
					//if(pViewm($userx["type"]))
					//{
						?>
                         <br/>
   <div style="text-align:center">
                         <p class="show_this" style="font-family:'agentfb'; color:#666;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[+] Show Advance Search</p>
                         <div class="slidingDiv">
                         <hr/>
                         <div style="text-align:center; margin-left:auto; margin-right:auto; padding-left:80px;">
                            <div style="width:340px; text-align:center; margin-left:auto; margin-right:auto; float:left;">
                                <div style="float:left; padding-right:5px;">Choose A Date Range:</div>&nbsp;
                                <input name="date1" id="date1" class="date-pick" readonly="readonly" />
                            </div>
                            &nbsp;&nbsp;&nbsp;
                            <div style="float:left; padding-right:10px;">
                             To:
                            </div>
                            <div style="width:200px; text-align:center; margin-left:auto; margin-right:auto;float:left">
                                <input name="date2" id="date2" class="date-pick" readonly="readonly" />
                            </div>
                            <div style="clear:both"></div>
                        </div>
                          <br/><br/>
                          <span id="mform_q">Select Office: </span>&nbsp;
                          <select class='moselect' id="soffice" name="soffice">
                          <?php
						  if(!pViewm($userx["type"]))
						  {
							  echo "<option value='".base64_encode($userx["office"])."'>".getOfficeName($userx["office"])."</option>";
						  }
						  else
						  {
							  ?>
                            <option value="na">Select Office</option>
                            <option value="all" selected="selected">All Office</option>
                            <?php
                            $qx = "select * from rec_office order by name desc";
                            if($rx = mysql_query($qx))
                            {
                                if(($nx = mysql_num_rows($rx))>0)
                                {
                                    while($rox = mysql_fetch_array($rx))
                                    {
                                        echo "<option value='".base64_encode($rox["id"])."'>".stripslashes($rox["name"])."</option>";
                                    }
                                }
                            }
						  }
                            ?>
                          </select>
                          <br/><br/>
                          <span id="mform_q">Select Manager: </span>&nbsp;
                          <select id="umanb" name="umanb">
                          <?php
						  if(!pViewm($userx["type"]))
						  {
							  echo "<option value='".base64_encode($userx["id"])."'>".stripslashes($userx["name"])."</option>";
						  }
						  else
						  {
							  ?>
                            <option value="na">Select Manager</option>
                            <option value="all" selected="selected">All Managers</option>
                            <?php
                            $qx = "select * from task_users where type in('5','6','7') order by date desc";
                            if($rx = mysql_query($qx))
                            {
                                if(($nx = mysql_num_rows($rx))>0)
                                {
                                    while($rox = mysql_fetch_array($rx))
                                    {
                                        echo "<option value='".base64_encode($rox["id"])."'>".stripslashes($rox["name"])."</option>";
                                    }
                                }
                            }
						  }
                            ?>
                          </select>
                          <br/><br/>
                          <span id="mform_q">Select Agent: </span>&nbsp;
                          <select class='moselect' id="sagent" name="sagent">
                            <option value="na">Select Agent</option>
                            <option value="all" selected="selected">All Agent</option>
                            <?php
						  if(pViewm($userx["type"]))
						  {
                            $qx = "select * from sales_agent order by name desc";
                            if($rx = mysql_query($qx))
                            {
                                if(($nx = mysql_num_rows($rx))>0)
                                {
                                    while($rox = mysql_fetch_array($rx))
                                    {
                                        echo "<option value='".base64_encode($rox["id"])."'>".stripslashes($rox["name"])."</option>";
                                    }
                                }
                            }
						  }
                            ?>
                          </select>
                          <br/><br/>
                          <input type="image"  src="images/searchbtn.jpg" onmouseover="javascript:this.src='images/searchbtn.jpg';" onmouseout="javascript:this.src='images/searchbtn.jpg';"/>
                               <hr/>
                         </div>
                         </div>
                         <br/>
                         <br/>
                    <!--end of search stuff-->
                   <?Php
					//}
					//else
					//	echo "<br/><br/>";
					?>
                  <div id="rtotal">
                    <div style="padding-top:10px;">
                    <?php
                        if($showrun)
                            $rlink = "<a href='viewrunning.php?id=".base64_encode($assignid)."' class='contlink'>".$rtotal."</a>";
                        else
                            $rlink = $rtotal;
						$agoal=getGoalx_info($assignid,'');
						$agoalm="";
						if(!empty($agoal[0]["goals"]))
						{
							if(pViewm($userx["type"]))
								$agoalm=$rlink."/<a href='accountgoalsx_set.php?id=".base64_encode($agoal[0]["goalid"])."' class='contlink'>".$agoal[0]["goals"]."</a>";
							else
								$agoalm=$rlink."/".$agoal[0]["goals"];
						}
						else
							$agoalm=$rlink;
						if(!$showd_date)
                       		 echo "Total From ".fixdate_comps("invdate_s",$tday)." to ".fixdate_comps("invdate_s",$today)." is: ".$agoalm;
						 else
							 echo "Total is: ".$agoalm;
                    ?>
                    </div>
                 </div>
                 <br/>
                 <br/>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr class='moheader'>
                  	  <td width="9%" align="center" valign="middle">&nbsp;</td>
                    <td width="26%" align="center" valign="middle">Agent</td>
                    <td width="27%" align="center" valign="middle">Office</td>
                    <td width="22%" align="center" valign="middle">Date</td>
                    <td width="16%" align="center" valign="middle">Sales</td>
                  </tr>
                   <?Php
				  if($result = mysql_query($query))
				  {
					  if(($num_rows=mysql_num_rows($result))>0)
					  {
						  $count=1;
						  while($row = mysql_fetch_array($result))
						  {
							  $gtotal += $row["stotal"];
							  $username = getName($row["userid"]);
							  $agentname = getAgent($row["agentid"]);
							  $officename = getOfficeName($row["office"]);
							  if(pView($userx["type"]))
								$agentlink = "<a href='accountreport_set.php?id=".base64_encode($row["id"])."' class='contlinkb'>".$agentname."</a>";
							else
								$agentlink = $agentname;
							  echo "<tr><td colspan='5' align='center'><hr/></td></tr>
				  <tr class='morow'><td height='33' align='center' valign='middle'>$count</td><td height='33' align='center' valign='middle'>$agentlink</td><td align='center' valign='middle'>".$officename."</td><td align='center' valign='middle'>".fixdate_comps("invdate_s",$row["fromdate"])."</td><td align='center' valign='middle'>".$row["stotal"]."</td></tr>";
				  			$count++;
						  }
					  }
					  else
						echo "<tr><td colspan='5' class='nfound'>No Current Sales Found</td></tr>";
				  }
				  else
					echo "<tr><td colspan='5' class='nfound'>No Current Sales Found</td></tr>";
				  ?>
                  <tr><td colspan="5"><hr/></td></tr>
                  <tr>
                  <td colspan="4" align="right" valign="middle">
                        <span class='mform_q'>Total:</span> &nbsp;&nbsp;
                  </td>
                  <td align="center" valign="middle"><span class='mform_q'><?php echo $gtotal; ?></span></td>
                  </tr>
      			</table>
                </form>
            </div>
      </div>
        <div id="lbodycont_footer"></div>
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
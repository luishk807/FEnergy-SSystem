<?Php
session_start();
include "../include/config.php";
include "../include/function.php";
adminlogin();
$userx = $_SESSION["salesuser"];
date_default_timezone_set('America/New_York');
$xid = base64_decode($_REQUEST["id"]);
$weekday = date('l');
$today = date('Y-m-d');
$tday = getCalDate($weekday);
$gtotal=0.00;
$thisyear=date("Y");
$fromy = $thisyear-50;
//from search input
$sfromm = $_REQUEST["sfromm"];
$sfromd = $_REQUEST["sfromd"];
$sfromy = $_REQUEST["sfromy"];
$stom = $_REQUEST["stom"];
$stod = $_REQUEST["stod"];
$stoy = $_REQUEST["stoy"];
$sdatef="";
$sdatet="";
$dquery="";
$showrun=true;
$showreturn =false;
$soffice = $_REQUEST["soffice"];
$sagentx = $_REQUEST["sagent"];
if($sfromm !='na' && $sfromd !='na' &&  !empty($sfromd) && !empty($sfromm))
	$sdatef = $sfromy."-".$sfromm."-".$sfromd;
if($stom !='na' && $stod !='na' &&  !empty($stod) && !empty($stom))
	$sdatet = $stoy."-".$stom."-".$stod;
if(!empty($sdatef) || !empty($sdatet))
{
	$showreturn=true;
	if(empty($sdatef))
		$sdatef = $sdatet;
	if(empty($sdatet))
		$sdatet =$sdatef;
}
if($sagentx !='na' && $sagentx !='all')
{
	$sagentxx = base64_decode($sagentx);
	if(!empty($sagentxx))
	{
		$showreturn=true;
		$dquery = " and agentid='".$sagentxx."' ";
	}
}
if($soffice != 'na' && $soffice !='all')
{
	$sofficexx = base64_decode($soffice);
	if(!empty($sofficexx))
	{
		$dquery .= " and office='".$sofficexx."' ";
		$showreturn=true;
	}
}
if(!empty($sdatet) && !empty($sdatef))
{
	$dquery .= "and (fromdate between '".$sdatef."' and '".$sdatet."') order by fromdate desc";
	$tday = $sdatef;
	$today = $sdatet;
	$showrun=false;
}
else
	$dquery .= "and fromdate='".$today."' order by fromdate desc";
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
	$query = "select * from sales_report where userid='".$assignid."' $dquery";
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
<script type="text/javascript" language="javascript">
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
<?php
if($showreturn)
	echo "<div id='tabmenu'><div id='tabmenu_in'><a href='viewreport.php' class='contlinkc' ><span class='mform_q'>Back To Previous</span></a></div></div>";
	?>
<form action="viewreport.php" method="post">
<input type="hidden" id="id" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
     <div id="message2m" name="message2m">
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
        	<?php
			$qa="select * from task_users where type in('5','6','7') order by name";
			if($ra = mysql_query($qa))
			{
				if(($na = mysql_num_rows($ra))>0)
				{
					while($rowa = mysql_fetch_array($ra))
					{
						if($xid==$rowa["id"])
						echo "<option value='".base64_encode($rowa["id"])."' selected='selected'>".stripslashes($rowa["name"])."</option>";
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
     <br/>
     <?Php
	 if(pViewm($userx["type"]))
	 {
		 ?>
     <p class="show_this" style="font-family:'agentfb'; color:#666;font-size:25pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[+] Show Advance Search</p>
     <div class="slidingDiv">
     <hr/>
      <span id="mform_q">From Date:</span>
            <select id="sfromm" name="sfromm" class='moselect'>
            <option value='na'>Month</option>
            	<?php
				for($x=1;$x<13;$x++)
				{
					$xb = $x;
					if($xb<10)
						$xb="0".$x;
					echo "<option value='$xb'>$xb</option>";
				}
				?>
            </select>&nbsp;/&nbsp;
             <select id="sfromd" name="sfromd" class='moselect'>
            <option value='na'>Day</option>
            	<?php
				for($x=1;$x<32;$x++)
				{
					$xb = $x;
					if($xb<10)
						$xb="0".$x;
					echo "<option value='$xb'>$xb</option>";
				}
				?>
            </select>&nbsp;/&nbsp;
             <select id="sfromy" name="sfromy" class='moselect'>
            	<?php
				for($x=$thisyear;$x>$fromy;$x--)
				{
					$xb = $x;
					echo "<option value='$xb'>$xb</option>";
				}
				?>
            </select>
            <br/><br/>To:&nbsp;
            <select id="stom" name="stom" class='moselect'>
            <option value='na'>Month</option>
            	<?php
				for($x=1;$x<13;$x++)
				{
					$xb = $x;
					if($xb<10)
						$xb="0".$x;
					echo "<option value='$xb'>$xb</option>";
				}
				?>
            </select>&nbsp;/&nbsp;
             <select id="stod" name="stod" class='moselect'>
            <option value='na'>Day</option>
            	<?php
				for($x=1;$x<32;$x++)
				{
					$xb = $x;
					if($xb<10)
						$xb="0".$x;
					echo "<option value='$xb'>$xb</option>";
				}
				?>
            </select>&nbsp;/&nbsp;
             <select id="stoy" name="stoy" class='moselect'>
            	<?php
				for($x=$thisyear;$x>$fromy;$x--)
				{
					$xb = $x;
						echo "<option value='$xb'>$xb</option>";
				}
				?>
            </select>
      <br/><br/>
      <span id="mform_q">Select Office: </span>&nbsp;
      <select class='moselect' id="soffice" name="soffice">
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
		?>
      </select>
      <br/><br/>
      <span id="mform_q">Select Agent: </span>&nbsp;
      <select class='moselect' id="sagent" name="sagent">
      	<option value="na">Select Agent</option>
        <option value="all" selected="selected">All Agent</option>
        <?php
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
		?>
      </select>
      <br/><br/>
      <input type="image"  src="../images/searchbtnm.png" onmouseover="javascript:this.src='../images/searchbtnm.png';" onmouseout="javascript:this.src='../images/searchbtnm.png';"/>
           <hr/>
     </div>
     <br/>
     <?php
	 }
	 ?>
     <br/>
     <div id="rtotal" style="background:#00477f; font-family:'falk'; font-size:24pt; color:#FFF; height:80px; text-align:center">
     	<div style="padding-top:20px;">
     	<?php
			if($showrun)
				$rlink = "<a href='viewrunning.php?id=".base64_encode($assignid)."' class='contlinkc'>".$rtotal."</a>";
			else
				$rlink = $rtotal;
			echo "Total From ".fixdate_comps("invdate_s",$tday)." to ".fixdate_comps("invdate_s",$today)." is: ".$rlink;
		?>
        </div>
     </div>
     <br/>
     <br/>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr class='moheader'>
        <td align="center" valign="middle">Agent</td>
        <td align="center" valign="middle">Office</td>
        <td align="center" valign="middle">Date</td>
        <td align="center" valign="middle">Sales</td>
      </tr>
      <?Php
	  if($result = mysql_query($query))
	  {
		  if(($num_rows=mysql_num_rows($result))>0)
		  {
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
				  echo "<tr><td colspan='4' align='center'><hr/></td></tr>
      <tr class='morow'><td height='33' align='center' valign='middle'>$agentlink</td><td align='center' valign='middle'>".$officename."</td><td align='center' valign='middle'>".fixdate_comps("invdate_s",$row["fromdate"])."</td><td align='center' valign='middle'>".$row["stotal"]."</td></tr>";
			  }
		  }
		  else
		  	echo "<tr><td colspan='4' class='nfound_m'>No Current Sales Found</td></tr>";
	  }
	  else
	  	echo "<tr><td colspan='4' class='nfound_m'>No Current Sales Found</td></tr>";
	  ?>
      <tr><td colspan="4"><hr/></td></tr>
      <tr>
      <td colspan="3" align="right" valign="middle">
            <span class='mform_q'>Total:</span> &nbsp;&nbsp;
      </td>
      <td align="center" valign="middle"><span class='mform_q'><?php echo $gtotal; ?></span></td>
      </tr>
    </table>
</form>
</div>
<br/><br/>
</body>
</html>
<?php
include "../include/unconfig.php";
?>
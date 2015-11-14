<?php
include "include/config.php";
include "include/function.php";
date_default_timezone_set('America/New_York');
/*$lweek=date('Y-m-d', strtotime("last week", strtotime('2012-07-09')) );
$csunday=date('l',strtotime($lweek));
if($csunday !="Sunday")
	$xdate=date('Y-m-d', strtotime("last sunday", strtotime($lweek)) );
else
	$xdate=$lweek;
$nmonday=date('Y-m-d', strtotime("next monday", strtotime($xdate)) );
echo $nmonday."<Br/>";*/
//$mondayz=date('Y-m-d', strtotime("next monday",strtotime($weekstartz)) );
//echo $mondayz."<br/>";
$monday = getPrevMonday('2012-08-06');
echo $monday;
include "include/unconfig.php";
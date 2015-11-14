<?php
session_start();
include "include/config.php";
include "include/function.php";
$mmessage="Family Energy Sales Report $xtoday Reminder: Please Submit Sales Today Before 11:00pm http://www.familyenergymap.com/salesreport/";
date_default_timezone_set('America/New_York');
$h=date('H');
if($h>10 && $h<12)
$result = sendSMS('347-613-1428',$mmessage);
include "include/unconfig.php";
?>
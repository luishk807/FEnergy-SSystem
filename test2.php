<?Php
$target = 1200;
$total =500;
$total = intval($total);
//Add any fund additions here
if($total != 0) {
  $percent = ($total / $target) * 100;
  $fill = round((132 / 100) * $percent);
} else {
  $percent = 0;
  $fill = 0;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style>
.outer_bar {
  height:20px;
  width:132px;
  border:1px solid black;
  position:relative;
  margin-left:150px;
  margin-top:84px;
}
.total_display {
  position:absolute;
  top:12%;
  left:29%;
  height:20px;
  width:132px;
  color:#999;
}
.inner_bar {
  height:20px;
  background:#444;
}
</style>
</head>

<body>
<div class="outer_bar">
  <div class="total_display">£<?php echo $total; ?> / £<?php echo $target; ?></div>
    <div class="inner_bar" style="width:<?php echo $fill; ?>px;"></div>
</div>
</body>
</html>
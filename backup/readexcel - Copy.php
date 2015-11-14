<?Php
session_start();
include 'include/config.php';
include "include/function.php";
/*if(empty($_SERVER['HTTP_REFERER']))
{
	$_SESSION["salesresult"]="ERROR:Invalid Access";
	header("location:home.php");
	exit;
}*/
require_once dirname(__FILE__) . '/include/excel/PHPExcel.php';
$ext = explode(".",$_FILES['file']['name']);
$dmonth="";
$rdate="";
if($ext[1] != "xlsx")
{
	$_SESSION["salesresult"]="Invalid File Type";
	header("location:importexcel.php");
	exit;
}
else
{
//read 2003 format
//$objPHPExcel = new PHPExcel();
//$objReader = new PHPExcel_Reader_Excel5();
//$objReader->setReadDataOnly(true);
//end of read 2003 format
//read 2007 format
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objReader->setReadDataOnly(true);
//end of read 2007 format
date_default_timezone_set('America/New_York');
$objPHPExcel = $objReader->load($_FILES['file']['tmp_name']);
//second try
$rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();
$array_data = array();
foreach($rowIterator as $row){
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
    //if(1==$row->getRowIndex() || 2==$row->getRowIndex()) continue;//skip first row
    $rowIndex = $row->getRowIndex();
	$array_data[$rowIndex]=array('A'=>'','B'=>'','C'=>'','D'=>'','E'=>'','F'=>'','G'=>'');
	foreach ($cellIterator as $cell) 
	{
       	if('A' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        } else if('B' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        } else if('C' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('D' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('E' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('F' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }else if('G' == $cell->getColumn()){
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
        }
		
   }
}
$user = $array_data;
$def=2;
$entrysaved=0;
$bronx=array();
$manha=array();
$brooklyn=array();
//echo sizeof($user)."<br/>";
if(sizeof($user)>0)
{
	$qx="insert into sales_report_real_m(date)values(NOW())";
	if($rx=mysql_query($qx))
	{
		$idx=mysql_insert_id();
		for($i=0;$i<sizeof($user)+1;$i++)
		{
			$rFirst=ucwords(strtolower(trim($user[$i]["A"])));
			//echo $user[0]["B"]."<br/>";
			if($i==1)
				$dmonth=trim($user[$i]["B"]);
			else if($i==2)
			{
				@$rdate= PHPExcel_Style_NumberFormat::toFormattedString($user[$i]["B"], "YYYY-M-D");
				//$rdate=fixdate_comps("mildate",$rdate);
				$xdate=explode("-",$rdate);
				$rdate=date("Y-m-d", mktime(0, 0, 0, $xdate[0],$xdate[1],$xdate[2]));
			}
			else if($i>2)
			{
				if(empty($dmonth))
					$dmonth=date('F');
				if(empty($rdate))
					$rdate=date('Y-m-d');
				$rdate=switchMonth($rdate,$dmonth);
				$xoffice=explode(" ",$rFirst);
				$ass_office="";
				$agentid="";
				$agentid_s=strtoupper(trim($user[$i]["B"]));
				$agentname_s=ucwords(strtolower(trim($user[$i]["C"])));
				if(empty($user[$i]["D"]))
					$elec=0;
				else
					$elec=trim($user[$i]["D"]);
				if(empty($user[$i]["E"]))
					$gas=0;
				else
					$gas=trim($user[$i]["E"]);
				//$agentname_s=str_replace("Â","",$agentname_s);
				//$agentname_s=utf8_encode($agentname_s);
				while(substr_count($agentname_s,"  ") != 0){
				   $agentname_s= str_replace("  "," ",$agentname_s);
				}
				$aid=adAgent($agentid_s,$agentname_s);
				$ass_office=findOfficeByName($xoffice[2]);
				//$aid=1;
				//$ass_office=1;
				if(!empty($ass_office) && !empty($aid))
				{
					$query="insert ignore into sales_report_real(fileid,userid,office,xgas,xelec,dmonth,ddate,date)values('".$idx."','".$aid."','".$ass_office."','".$gas."','".$elec."','".$dmonth."','".$rdate."',NOW())";
					//echo $query."<br/>";
					if($result=mysql_query($query))
						$entrysaved++;
				}
			}
		}
	}
	if(empty($entrysaved) || $entrysaved <1)
	{
		$query="delete from sales_report_real_m where id='".$idx."'";
		@mysql_query($query);	
	}
}
if($entrysaved>0)
	$_SESSION["salesresult"]="SUCCESS: ".$entrysaved." Information Saved";
else
	$_SESSION["salesresult"]="ERROR: Unable To Save Information";
header('location:importexcel.php');
exit;
}
include 'include/unconfig.php';
?>
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
    if(1==$row->getRowIndex()) continue;//skip first row
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
		for($i=0;$i<sizeof($user)+2;$i++)
		{
				$rFirst=ucwords(strtolower(trim($user[$i]["A"])));
				@$rdate= PHPExcel_Style_NumberFormat::toFormattedString($user[$i]["F"], "YYYY-M-D");
				//$rdate=fixdate_comps("mildate",$rdate);
				$xdate=explode("-",$rdate);
				//09/04/2012
				//$rdate=date("Y-m-d", mktime(0, 0, 0, $xdate[0],$xdate[1],$xdate[2]));
				//echo $rdate."<br/>";
				$f_year=$xdate[0];
				$f_month=$xdate[1];
				$f_day=$xdate[2];
				if($f_year>100)
				{
					//if the date is not the usual date format (09/04/2012) then we believe is under the format
					//04-23-2012
					$f_year=$xdate[0];
					$f_month=$xdate[1];
					$f_day=$xdate[2];
				}
				else
				{
					$f_month=$xdate[0];
					$f_day=$xdate[1];
					$f_year=$xdate[2];
				}
				$rxdate="";
				@$rxdate=fixdate_comps('mildate',$rdate);
				if(empty($rxdate))
					$rxdate="";
				@$rdate=date("Y-m-d", mktime(0, 0, 0, $f_month,$f_day,$f_year));
				$dmonth=trim($user[$i]["G"]);
				if(empty($dmonth))
					$dmonth=date('F');
				if(empty($rdate))
					$rdate=date('Y-m-d');
				//$rdate=switchMonth($rdate,$dmonth);
				$rdate=getPrevMonday($rdate);
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
				$agentname_s=iconv('UTF-8', 'ISO-8859-1//IGNORE', $agentname_s);
				$xplit=explode(" ",$agentname_s);
				$fname="";
				for($y=0;$y<sizeof($xplit);$y++)
				{
					if($y==0 || empty($fname))
						$fname=trim($xplit[$y]);
					else
						$fname .=" ".trim($xplit[$y]);
				}
				$fname=preg_replace( '/\s+/',' ',$fname);
				$aid=adAgent($agentid_s,$fname);
				$ass_office=findOfficeByName($xoffice[2]);
				//$aid=1;
				//$ass_office=1;
				if(!empty($ass_office) && !empty($aid))
				{
					$query="insert ignore into sales_report_real(fileid,userid,office,xgas,xelec,dmonth,ddate,date,rdate)values('".$idx."','".$aid."','".$ass_office."','".$gas."','".$elec."','".$dmonth."','".$rdate."',NOW(),'".$rxdate."')";
					//echo $query."<br/>";
					if($result=mysql_query($query))
						$entrysaved++;
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
{
	$_SESSION["salesresult"]="SUCCESS: ".$entrysaved." Information Saved";
	header('location:viewgraph_r.php');
}
else
{
	$_SESSION["salesresult"]="ERROR: Unable To Save Information";
	header('location:importexcel.php');
}
exit;
}
include 'include/unconfig.php';
?>
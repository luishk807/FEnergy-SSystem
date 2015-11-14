<?Php
include "include/function.php";
include "include/config.php";
date_default_timezone_set('America/New_York');
$query="select * from sales_agent where id='161'";
//$pattern = array("'é'", "'è'", "'ë'", "'ê'", "'É'", "'È'", "'Ë'", "'Ê'", "'á'", "'à'", "'ä'", "'â'", "'å'", "'Á'", "'À'", "'Ä'", "'Â'", "'Å'", "'ó'", "'ò'", "'ö'", "'ô'", "'Ó'", "'Ò'", "'Ö'", "'Ô'", "'í'", "'ì'", "'ï'", "'î'", "'Í'", "'Ì'", "'Ï'", "'Î'", "'ú'", "'ù'", "'ü'", "'û'", "'Ú'", "'Ù'", "'Ü'", "'Û'", "'ý'", "'ÿ'", "'Ý'", "'ø'", "'Ø'", "'œ'", "'Œ'", "'Æ'", "'ç'", "'Ç'");
//$replace = array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E', 'a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A', 'A', 'o', 'o', 'o', 'o', 'O', 'O', 'O', 'O', 'i', 'i', 'i', 'I', 'I', 'I', 'I', 'I', 'u', 'u', 'u', 'u', 'U', 'U', 'U', 'U', 'y', 'y', 'Y', 'o', 'O', 'a', 'A', 'A', 'c', 'C'); 
if($result=mysql_query($query))
{
	if(($num_rows=mysql_num_rows($result))>0)
	{
		$info=mysql_fetch_assoc($result);
		$agentname_s=$info["name"];
		//$agentname_s=str_replace("Â","",$xname);
		//$first_name =  preg_replace($pattern, $replace, $xname);
		//$agentname_s= mb_convert_encoding($agentname_s, "HTML-ENTITIES", "UTF-8");
		//$agentname_s=str_replace("  ","",$agentname_s);
		$agentname_s=iconv('UTF-8', 'ISO-8859-1//IGNORE', $agentname_s);
		$xplit=explode(" ",$agentname_s);
		$fname="";
		for($i=0;$i<sizeof($xplit);$i++)
		{
			if($i==0 || empty($fname))
				$fname=trim($xplit[$i]);
			else
				$fname .=" ".trim($xplit[$i]);
				
		}
		echo trim($fname)."<br/>";
	}
}
include "include/unconfig.php";
?>
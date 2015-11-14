<?php
session_start();
include "include/config.php";
include "include/function.php";
?>
<p id="searchresults">
<?php
      // Is there a posted query string?
      if(isset($_POST['queryString'])) 
	  {
		  $query = "select * from sales_agent where name like '%".$_REQUEST["queryString"]."%'";
		  if($result = mysql_query($query))
		  {
			  if(($num_rows = mysql_num_rows($result))>0)
			  {
				  
			  }
		  }
         $queryString = clean($_POST['queryString']);
         // Is the string length greater than 0?
         if(strlen($queryString) >0)
		 {
            $query = "SELECT * FROM sales_agent WHERE name LIKE '%" . $queryString . "%' order by name LIMIT 8";
            if($result = mysql_query($query))
			{
               while ($rows = mysql_fetch_array($result)) 
			   {
				   echo "<a href='javascript:addthis(\"".stripslashes($rows["name"])."\")'>".stripslashes($rows["name"])."</a>";
			   }
            } else {
               echo 'ERROR: There was a problem with the query.';
            }
         } else {
            // Dont do anything.
         } // There is a queryString.
      }
?>
</p>
<?php
include "include/unconfig.php";
?>
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
		 /* $query = "select * from sales_agent where name like '%".$_REQUEST["queryString"]."%'";
		  if($result = mysql_query($query))
		  {
			  if(($num_rows = mysql_num_rows($result))>0)
			  {
				  
			  }
		  }*/
         $queryString = clean($_POST['queryString']);
         // Is the string length greater than 0?
         if(strlen($queryString) >0)
		 {
            $query = "SELECT * FROM sales_agent WHERE name LIKE '%" . $queryString . "%' order by name LIMIT 8";
            if($result = mysql_query($query))
			{
               while ($rows = mysql_fetch_array($result)) 
			   {
				   if(empty($rows["acode"]))
				   {
					   ?>
                    <a href='javascript:addthis("<?php echo stripslashes($rows["name"]); ?>","<?Php echo stripslashes($rows["acode"]); ?>")'><?php echo stripslashes($rows["name"]); ?></a>
                       <?php
				   }
				   else
				   {
				   ?>
				   <a href='javascript:addthis("<?php echo stripslashes($rows["name"]); ?>","<?Php echo stripslashes($rows["acode"]); ?>")'><?php echo stripslashes($rows["name"]); ?> &nbsp;&nbsp;&nbsp;<span style="font-size:17pt;color:#999;font-family:'rockw'">Agent Code:&nbsp;<?php echo stripslashes($rows["acode"]); ?></span> </a>
                   <?php
				   }
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
    <?php
$hop = true;
$ux = $_SESSION["salesuser"];
if(!pView($ux["type"]))
	$hop=false;
?>
<div id="header">
    	<div id="logo">
        	<?php
			if($hop)
			{
				?>
        	<a href='home.php'><img src="images/logo_in.png" border="0" alt="logo" /></a>
            <?php
			}
			else
			{
			?>
            <img src="images/logo_in.png" border="0" alt="logo" />
            <?php
			}
			?>
        </div>
        <div id="menu">
            <div id="contmiddle">
            	<div id="contmiddle_in">
                	<div id="menu_in">
                    	<ul>
                        	<li><a href='setting.php'>Account</a>
                            	<ul>
                                	<li><a href='logout.php'>Logout</a></li>
                                </ul>
                             </li>
                            <li><a href='createreport.php'>Add</a>
                            	<ul>
                                	<li><a href='createreport.php'>Report</a></li>
                                    <?php
									if($hop)
									{
										?>
                                	<li><a href='create.php'>Users</a></li>
                                    <li><a href='createoffice.php'>Offices</a></li>
                                    <li><a href='createagents.php'>Agents</a></li>
                                    <li><a href='importexcel.php'>Excel</a></li>
                                    <?php
									}
									?>
                                </ul>
                            </li>
                            <li><a href='viewreport.php'>Views</a>
                            	<ul>
                                	<li><a href='viewreport.php'>Reports</a></li>
                                    <li><a href='viewgraph_r.php'>Year Perf</a></li>
                                    <?php
									if($hop)
									{
										?>
                                	<li><a href='viewusers.php'>Users</a></li>
                                    <li><a href='viewoffice.php'>Offices</a></li>
                                    <li><a href='viewgoalsx.php'>Goals</a></li>
                                    <li><a href='viewagents.php'>Agents</a></li>
                                    <?php
									}
									?>
                                </ul>
                            </li>
                            <?php
							if($hop)
			 				{
								?>
                            <li><a href='viewgoalsx.php'>Goals</a>
                            	<ul>
                                	<li><a href='viewgoalsx.php'>Goals</a></li>
                                	<li><a href='creategoalsx.php'>Add</a></li>
                                </ul>
                            </li>
                            <li><a href='accountrealtotal_oset.php'>Real Total</a>
                            	<ul>
                                	<li><a href='accountrealtotal_oset.php'>Edit Total</a></li>
                                </ul>
                            </li>
                            <?php
							}
							?>
                        </ul>
                    </div>
           		 </div>
            </div>
            <div id="contright"></div>
            <div class="cleardiv"></div>
        </div>
        <div class="cleardiv"></div>
    </div>
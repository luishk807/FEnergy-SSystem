<?Php
$graph = new Graph(400,500);
$graph->SetScale("textlin");
					
$theme_class=new UniversalTheme;
					
$graph->SetTheme($theme_class);
$graph->img->SetAntiAliasing(false);
$graph->title->Set('Graph For Weekly Sales From '.$todaypf.' to '.$todaypt);
$graph->SetBox(false);
					
$graph->img->SetAntiAliasing();
					
$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);
					
$graph->xgrid->Show();
$graph->xgrid->SetLineStyle("solid");
$graph->xaxis->SetTickLabels(array('F','SAT','SUN','M','T','W','TH'));
$graph->xgrid->SetColor('#E3E3E3');
?>
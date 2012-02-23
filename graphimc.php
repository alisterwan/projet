<?php // content="text/plain; charset=utf-8"

include './header.php';

if (isset($userid)){ // vérification si logué ou pas


require_once ('jpgraph/src/jpgraph.php');
require_once ('jpgraph/src/jpgraph_bar.php');


if( file_exists("graphimc.png") ) {
unlink("graphimc.png");
}

$query = "SELECT imc, date FROM evolution WHERE id_user='$userid'";
$res = mysql_query($query) or die("error");

while ($row = mysql_fetch_array($res)){

$datay1[] = $row['imc'];
$datay2[] = $row['date'];

}


// Create the graph. These two calls are always required
$graph = new Graph(600,600);
$graph->SetScale("textlin");

$theme_class=new UniversalTheme;
$graph->SetTheme($theme_class);

$graph->title->Set('Evolution of your imc');

$graph->Set90AndMargin(50,40,40,40);
$graph->img->SetAngle(90); 

// set major and minor tick positions manually
$graph->SetBox(false);

//$graph->ygrid->SetColor('gray');
$graph->ygrid->Show(false);
$graph->ygrid->SetFill(false);
$graph->xaxis->SetTickLabels($datay2);
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);

// For background to be gradient, setfill is needed first.
$graph->SetBackgroundGradient('#00CED1', '#FFFFFF', GRAD_HOR, BGRAD_PLOT);

// Create the bar plots
$b1plot = new BarPlot($datay1);

// ...and add it to the graPH
$graph->Add($b1plot);





$b1plot->SetWeight(0);
$b1plot->SetFillGradient("#808000","#90EE90",GRAD_HOR);
$b1plot->SetWidth(17);
// Display the graph
$graph->Stroke();
$img="graphimc.png";
$graph->Stroke($img);

}else{

header('Location: index.php');	

}
?>
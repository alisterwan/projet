<?php 

include './header.php';

if (isset($userid)){ // vérification si logué ou pas

require_once ('jpgraph/src/jpgraph.php');
require_once ('jpgraph/src/jpgraph_line.php');

if( file_exists("graph.png") ) {
unlink("graph.png");
}



// Requete récupérant le poid et dates d'une personne
$query = "SELECT weight, date FROM evolution WHERE id_user='$userid'";
$res = mysql_query($query) or die("error");



while ($row = mysql_fetch_array($res)){

$datay1[] = $row['weight'];
$datay2[] = $row['date'];

}


// Setup the graph
$graph = new Graph(650,650);
$graph->SetScale("textlin");

$theme_class= new UniversalTheme;
$graph->SetTheme($theme_class);

$graph->title->Set('Evolution');
$graph->SetBox(false);

$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);

$graph->xaxis->SetTickLabels($datay2);
$graph->ygrid->SetFill(false);
//$graph->SetBackgroundImage("tiger_bkg.png",BGIMG_FILLFRAME);

$p1 = new LinePlot($datay1);
$graph->Add($p1);

//$p2 = new LinePlot($datay2);
//$graph->Add($p2);

$p1->SetColor("#55bbdd");
$p1->SetLegend('Your weight chart');
$p1->mark->SetType(MARK_FILLEDCIRCLE,'',1.0);
$p1->mark->SetColor('#55bbdd');
$p1->mark->SetFillColor('#55bbdd');
$p1->SetCenter();

/*$p2->SetColor("#aaaaaa");
$p2->SetLegend('Line 2');
$p2->mark->SetType(MARK_UTRIANGLE,'',1.0);
$p2->mark->SetColor('#aaaaaa');
$p2->mark->SetFillColor('#aaaaaa');
$p2->value->SetMargin(14);
$p2->SetCenter();*/

$graph->legend->SetFrameWeight(1);
$graph->legend->SetColor('#4E4E4E','#00A78A');
$graph->legend->SetMarkAbsSize(8);


// Output line
$graph->Stroke();
$img="graph.png";
$graph->Stroke($img);




}else{

header('Location: index.php');	

}
?>
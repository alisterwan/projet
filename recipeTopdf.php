<?php

include './header.php';

header('Content-type: text/plain');

function retrieve_recipe_infos($id){ // prend en paramtre l'id de l'user, soit $_SESSION['id']
	$sql='SELECT name_en,description_en,country_origin,difficulty,num_serves,duration_preparation,duration_cook,preparation_en,id_user FROM recipes WHERE id='.$id;
	$query=mysql_query($sql);
	$verif = mysql_num_rows($query);
	
	if ($verif > 0){
	return $result=mysql_fetch_assoc($query);
	}
	return false;
  }


require('fpdf.php');


$i = retrieve_recipe_infos($_GET['id']);

class PDF extends FPDF
{
// En-tte
function Header()
{

    // Police Arial gras 15
    $this->SetFont('Arial','B',15);
    // DŽcalage ˆ droite
    $this->Cell(80);
    // Titre
    $this->Cell(40,10,'Recette Digeat',1,0,'C');
    // Saut de ligne
    $this->Ln(20);
}


function TitleRecipe($id)
{
	$toto = retrieve_recipe_infos($id);
 
 $query = "SELECT username FROM users WHERE id='$toto[id_user]'";	
	$result2 = mysql_query($query);
	
	if(mysql_num_rows($result2) == 1){
	$ij = mysql_fetch_assoc($result2);
	}	
 
    // Arial 12
    $this->SetFont('Arial','',18);
    // Couleur de fond
    $this->SetFillColor(200,220,255);
    // Titre
    $this->Cell(0,6,"$toto[name_en] by $ij[username]" ,0,1,'C',true);
    // Saut de ligne
    $this->Ln(4);
}



function RecipeImage()
{
$query = sprintf("SELECT path_source FROM recipe_photos WHERE id_recipe='%s'",
	mysql_real_escape_string($_GET['id'])); 	
	$result2 = mysql_query($query);
	
	if(mysql_num_rows($result2) == 1){
	$ij = mysql_fetch_assoc($result2);
	 $this->Image($ij['path_source'],10,40,80);	
	}
}


function IngredientRecipe($id)
{

$i = retrieve_recipe_infos($id);

    
$this->MultiCell(0,6,'Ingredients');
 // Saut de ligne
    $this->Ln();

//selection des ingredients reliees a la recette
	$query = sprintf("SELECT id_ingredient FROM recipe_ingredients WHERE id_recipe='%s'",
	mysql_real_escape_string($_GET['id'])); 	
	$result = mysql_query($query);	
	
	while($row=mysql_fetch_row($result)) {
   	$query1 = "SELECT name_en FROM ingredients WHERE id=$row[0]";
	$response = mysql_query($query1);
	while($row1 = mysql_fetch_assoc($response)){
	$this->MultiCell(0,6,'-'.$row1['name_en']);	
	$this->Ln();
	}	
   }
   
} 

function InfosRecipe($id){  
  
   $i = retrieve_recipe_infos($id);
   
	$query21 = mysql_query("SELECT * FROM country WHERE id_country=$i[country_origin]");
  	$res2 = mysql_fetch_assoc($query21);	
  	$i[country_origin]=$res2[name_en];	
  
   $query11 = "SELECT name_en FROM recipe_difficulty WHERE id=$i[difficulty]";
   $res11 = mysql_query($query11); 
   $row = mysql_fetch_assoc($res11); 
  
   $i[difficulty]= $row[name_en];

$this->MultiCell(0,8,'Description:'.$i['description_en']);

$this->MultiCell(0,8,'Origin:'.$i['country_origin']);

$this->MultiCell(0,8,'Difficulty:'.$i['difficulty']);

$this->MultiCell(0,8,'Serves:'.$i['num_serves']);

$this->MultiCell(0,8,'Preparation:'.$i['duration_preparation'].'minutes');

$this->MultiCell(0,8,'Cooking:'.$i['duration_cook'].'minutes');

$this->MultiCell(0,8,'Instructions:'.$i['preparation_en']);

}

// Pied de page
function Footer()
{
    // Positionnement ˆ 1,5 cm du bas
    $this->SetY(-15);
    // Police Arial italique 8
    $this->SetFont('Arial','I',8);
    // NumŽro de page
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

// Instanciation de la classe dŽrivŽe
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',14);
$pdf->TitleRecipe($_GET['id']);
$pdf->RecipeImage();
$pdf->SetLeftMargin(100);
$pdf->IngredientRecipe($_GET['id']);
$pdf->SetLeftMargin(10); 
$pdf->Ln(10);

$pdf->InfosRecipe($_GET['id']);
$pdf->Output();
?>


<?php 

include './header.php';

if (isset($userid)){ // vérification si logué ou pas


$userinfos=retrieve_user_infos($userid);
  

//  requête pour savoir si la personne a déjà enregistré ses objectifs
$query0 = "SELECT id_user FROM objective WHERE id_user='$userid'";
$res0 = mysql_query($query0) or die("error 0");
$row = mysql_fetch_array($res0);


if( $row['id_user']!=null ){ // Si la personne n'a encore enregistré aucune donnée sur son poid, le graph ne s'affichera pas


// Affiche le graph
 $html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
	

	<div id='myAccordion' class='tswAccordion'>
		<div class='tswAccordionInactiveSection'>
			<div class='tswAccordionHeader'>Chart</div>
				<div class='tswAccordionBody'>
				<!--Content for section 1-->
				<ul>
				<img src='graph.php' alt='évolution' width='500' height='450' />
				
	";
		


// Récupère le poid de la personne
$query1 = "SELECT weight FROM evolution WHERE id_user='$userid'";
$result1 = mysql_query($query1) or die("error 1");


while($row0 = mysql_fetch_array($result1)){

$weight0=$row0['weight'];// récupère le poid a jour 

}


// Récupère la taille de la personne
$query2 = "SELECT size FROM objective WHERE id_user='$userid'";
$result2 = mysql_query($query2) or die("error 1");
$row1 = mysql_fetch_array($result2);

$size0=($row1['size']/100); // reconversion en de la taille en cm en m, pour le calcul de l'imc




$imc=$weight0/($size0*$size0); // Calcul de l'imc, poid / taille²

if( $imc<=18.5 ){ $res = ' You are too thin ';}
if( $imc>18.5 && $imc<=25 ){ $res =  'You are healthy';}
if( $imc>25 && $imc<=30 ){ $res =  'You are obese';}
if( $imc>30 ){ $res =  'Go practise sports!';}

$imc = round($imc,2);

$html.="
		<p>Your imc is: $imc</br> 
		$res</p>
	    </ul>
		</div>
	</div>
";



$html.="
		<div class='tswAccordionInactiveSection'>
			<div class='tswAccordionHeader'>Keep up to date your data</div>
			<div class='tswAccordionBody'>
				<!--Content for section 2-->
				<ul>
				<form action='graphique.php' enctype='multipart/form-data' method='post'> 
				  Weight (kilograms):<br/> 
        		  <input type='text' name='weight' size='50'/><br/><br/>
	  
	     		 Size (centimeters):<br/> 
    	      	<input type='text' name='size' size='50'/><br/><br/>
	        	  <input type='submit' value='Send'/> 
				</form>
			 	</ul>
			</div>
			</div>
	
	</div>
	<script type='text/javascript'>
		var accordion = tswAccordionGetForId(\"myAccordion\");
		accordion.setMouseOver(true);
	</script>	
";



if( ( isset($_POST['weight']) && $_POST['weight']!=null ) || ( isset($_POST['size']) && $_POST['size']!=null ) ){  // on rentre dans ce if si seulement le champ poid ou taille a été rempli

				// Date
				$Date = date("Y-m-d");
				
				// On récupère les variables
				$weight=$_POST['weight'];
				$size=$_POST['size'];
				
				
				// Compte le nombre de données rentrées
				$query = "SELECT count(id) AS ImgCount 
				FROM evolution
				WHERE id_user='$userid'"; 
				$result = mysql_query($query) or die("error 5"); 
				$ImgCount  = mysql_result($result,0,"ImgCount"); 
				
				
				// sélectionne id
				$query2 = "SELECT id FROM evolution WHERE id_user='$userid'";
				$result2 = mysql_query($query2) or die("error 6");
				
				// Supprime le nombre de données en trop
				if( $ImgCount > 7 ) {
				
				
				$row = mysql_fetch_array($result2);
				$ID=$row['id'];
				
				
				$query = "DELETE FROM evolution WHERE id = '$ID'";
				$result = mysql_query($query) or die("error 7"); 
				
				
				
				}
				
				

				if( $weight!=null ){ // cela évite que le champs vide soit envoyer à a bdd
				
				
				// enregistre dans la bdd evolution
				$query = "INSERT INTO evolution SET
				weight='$weight',
				id_user='$userid',
				date='$Date'";
				$res = mysql_query($query) or die("error 3"); 
				}
				
				if( $size!=null ){ // cela évite que le champs vide soit envoyer à a bdd 
				
				// enregistre dans la bdd objectives si la taille de la personne a changé et qu'il l'a précisé
				$query = "UPDATE objective SET
				size='$size'
				WHERE id_user='$userid'";
				$res = mysql_query($query) or die("error 4");
				}
				
				// Actualise la page pour afficher les nouvelles données
	
				header('Location: graphique.php');	
				
			}


}
printDocument(' Evolution ');

}else{

header('Location: index.php');	
}

?>
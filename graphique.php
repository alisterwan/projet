<?php 

include './header.php';

if (isset($userid)){ // vérification si logué ou pas


$userinfos=retrieve_user_infos($userid);
  

//  requête pour savoir si la personne a déjà enregistré ses objectifs
$query = "SELECT id_user FROM objective WHERE id_user='$userid'";
$res = mysql_query($query) or die("error 0");
$row = mysql_fetch_array($res);


if( $row['id_user']!=null ){ // Si la personne n'a encore enregistré aucune donnée sur son poid, le graph ne s'affichera pas


// Affiche le graph
 $html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
	

	<div id='myAccordion' class='tswAccordion'>
		<div class='tswAccordionInactiveSection'>
			<div class='tswAccordionHeader'>Chart</div>
				<div class='tswAccordionBody'>
				<!--Content for section 1-->
				<ul>
				<img src='graph.php' alt='évolution weight' width='500' height='450' />
				<img src='graphimc.php' alt='évolution imc' width='500' height='450' />
				
	";
		
$query = "SELECT weight FROM evolution WHERE id_user='$userid'";
$result = mysql_query($query) or die("error 0");
while($row = mysql_fetch_array($result)){

$poid=$row['weight']; // récup poid à jour

}

$query = "SELECT size FROM objective WHERE id_user='$userid'";
$result = mysql_query($query) or die("error 0");
$row = mysql_fetch_array($result);

$taille = $row['size']/100; // récup taille

		

$query1 = "SELECT imc FROM evolution WHERE id_user='$userid'";
$result1 = mysql_query($query1) or die("error 1");
while($row1 = mysql_fetch_array($result1)){ 

$imc=$row1['imc']; // récupère l'imc à jour

}


if( $imc<=18.5 ){ $res = ' You are too thin ';}
if( $imc>18.5 && $imc<=25 ){ $res =  'You are healthy';}
if( $imc>25 && $imc<=30 ){ $res =  'You are obese';}
if( $imc>30 ){ $res =  'Go practise sports!';}

$imc = round($imc,2);

$html.="<p>You weight $poid kg and you measure $taille m.
		Your imc is: $imc</br> 
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
			
				
				// Compte le nombre de données rentrées ( pour graphique )
				$query2 = "SELECT count(id) AS ImgCount 
				FROM evolution
				WHERE id_user='$userid'"; 
				$result2 = mysql_query($query2) or die("error 5"); 
				$ImgCount  = mysql_result($result2,0,"ImgCount"); 
				
				
				// sélectionne id ( pour graphique graph.php )
				$query3 = "SELECT id FROM evolution WHERE id_user='$userid'";
				$result3 = mysql_query($query3) or die("error 6");
				
				// Supprime le nombre de données en trop ( pour graphique )
				if( $ImgCount > 7 ) {
				
				$row = mysql_fetch_array($result3);
				$ID=$row['id'];
				
				
				$query4 = "DELETE FROM evolution WHERE id = '$ID'";
				$result4 = mysql_query($query4) or die("error 7"); 
				
				}
				
				
				if( $weight!=null && $size!=null ) { // cela évite que les champs vides soient envoyés
				
				
				$size0=$size/100; // on repasse la taille en mètre pour le calcul de l'imc
				$imc = $weight/($size0*$size0); // calcul de l'imc
				
				
				// enregistre dans la bdd evolution
				$query5 = "INSERT INTO evolution SET
				weight='$weight',
				imc='$imc',
				id_user='$userid',
				date='$Date'";
				$res = mysql_query($query5) or die("error 8");
				
				// enregistre dans la bdd objectives si la taille de la personne a changé et qu'il l'a précisé
				$query6 = "UPDATE objective SET
				size='$size'
				WHERE id_user='$userid'";
				$res = mysql_query($query6) or die("error 9");
				
				}
				
				
				else {
				
				if( $weight!=null && $size==null ){ // cela évite que le champs poid vide soit envoyer à a bdd
				

				
				$query7 = "SELECT size FROM objective WHERE id_user='$userid'";
				$result7 = mysql_query($query7) or die("error 8");
				$row = mysql_fetch_array($result7);
				
				$size=$row['size']/100; // on repasse la taille en mètre pour le calcul de l'imc
				$imc=$weight/($size*$size); // calcul de l'imc avec le poid/taille qui vient d'être rentré
				
				// enregistre dans la bdd evolution
				$query = "INSERT INTO evolution SET
				weight='$weight',
				imc='$imc',
				id_user='$userid',
				date='$Date'";
				$res = mysql_query($query) or die("error 9"); 
				
				}
				
				
				else { // cela évite que le champs taille vide soit envoyer à a bdd 
				

				
				$query8 = "SELECT weight FROM evolution WHERE id_user='$userid'";
				$result8 = mysql_query($query8) or die("error 10");
				
					while($row = mysql_fetch_array($result8)){
				
					$weight=$row['weight']; // récupère le poid à jour
				
					}
				
				
				$size0 = $size/100; // convertion de la taille en mètre pour le calcul de l'imc
				
				$imc = $weight/($size0*$size0); // calcul de l'imc
				
				// enregistre dans la bdd objectives si la taille de la personne a changé et qu'il l'a précisé
				$query = "UPDATE objective SET
				size='$size'
				WHERE id_user='$userid'";
				$res = mysql_query($query) or die("error 4");
				
				}
				
			}
				// Actualise la page pour afficher les nouvelles données
	
				//header('Location: graphique.php');	
				?><meta http-equiv="Refresh" content="0"; URL="http://etudiant.univ-mlv.fr/~jwankutk/tuto_john/graphique.php"><?php
			}


}
printDocument(' Evolution ');

}else{

header('Location: index.php');	
}

?>
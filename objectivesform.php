<?php
  
  include './header.php';
  

if (isset($userid)){ // vérification si logué ou pas



 $html.="  <h3>Objectives</h3> 
<form action='objectivesform.php' enctype='multipart/form-data' method='post'> 
	  Weight (kilograms):<br/> 
          <input type='text' name='weight' size='50'/> 
	  <br/><br/>
	  
      Size (centimeters):<br/> 
          <input type='text' name='size' size='50'/> 
	  <br/><br/>
	  
	  How many pounds do you want to lose?<br/>
	     <input type='text' name='lose' size='50'/> 
	  <br/><br/>
	  
	  How many pounds do you want to gain?<br/>
		  <input type='text' name='gain' size='50'/> 
	  <br/><br/>
	  
	  Do you want to taste/discover new recipes?<br/> 
          <label for='recipes'>Yes</label>
          <input type='radio' name='new' value='1' id='recipes'/>
		  <label for='recipes'>No</label>
          <input type='radio' name='new' value='0' id='recipes'/>
	  <br/><br/>
	  
	   Are you allergic to specific ingredients?<br/> 
          <label for='recipes'>Yes</label>
          <input type='radio' name='allergic' value='1' id='recipes'/>
		  <label for='recipes'>No</label>
          <input type='radio' name='allergic' value='0' id='recipes'/>
	  <br/><br/>
	  
	  
	  
          <input type='submit' value='Send'/> 
</form>";





if( ( isset($_POST['weight']) && $_POST['weight']!=null ) && ( isset($_POST['size']) && $_POST['size']!=null ) && ( (isset($_POST['lose']) && $_POST['lose']!=null) || ($_POST['gain']!=null && isset($_POST['gain'])) ) && isset($_POST['new']) ){


				// Date et Heure 
				$Date = date("Y-m-d");
				$Time = date("H:i:s");

				
				// On récupère les variables
				$weight=$_POST['weight'];
				$size=$_POST['size'];
				$lose=$_POST['lose'];
				$gain=$_POST['gain'];
				$new_recipes=$_POST['new'];
				
				
				// Vérifie si l'utilisateur n'a pas déja rempli le formulaire de l'objectif, si oui, alors il sera supprimé et remplacé par ses nouveaux objectifs
				$query0 = "SELECT * FROM objective WHERE id_user='$userid'";
				$res0 = mysql_query($query0) or die("error");
				
				if( isset($res0) ) { 
				
				$query0 = " DELETE FROM objective WHERE id_user='$userid' ";
				$res0 = mysql_query($query0) or die("error");
				
				}
				
				

				// enregistre dans la bdd objective
				$query = "INSERT INTO objective SET
				weight='$weight',
				size='$size',
				id_user='$userid',
				lose_weight='$lose',
				gain_weight='$gain',
				date='$Date'";
				$res = mysql_query($query) or die("error");

				
				// enregistre dans la bdd objective
				$query2 = "INSERT INTO evolution SET
				weight='$weight',
				id_user='$userid',
				date='$Date'";
				$res2 = mysql_query($query2) or die("error 2");
				
				if( isset($res) && isset($res2) ) ?><meta http-equiv="Refresh" content="0"; URL=http://etudiant.univ-mlv.fr/~jwankutk/tuto_john/graphique.php"><?php


}
  

  printDocument(' Objectives ');


}else{
	
	header('Location: index.php');
}
  
?>
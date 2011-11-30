<?php
	include './header.php';


function updateInfo($date,$hobbies,$job,$music,$films,$books,$aboutme,$favouritefood,$userid){ // fonction qui update sur la BDD
	
	$query = "UPDATE information SET date_birth='$date', hobbies='$hobbies', job='$job', 
	music='$music', films='$films', books='$books', aboutme='$aboutme', favouritefood='$favouritefood' WHERE id_user=$userid ";
	$res = @mysql_query($query);
	if(!$res)
		die("Error: ".mysql_error());
	else
		return $res;
}

	
if (isset($userid)){ // vérification si logué ou pas	
	
	// Mise à jour des données
	if ($_POST && isset($_GET['mode']) && $_GET['mode']=="info_edit_process") { // ça va appeler la fonction qui va modifier la BDD : updateUser
		
		$date          = $_POST['date'];
		$hobbies       = $_POST['hobbies'];
		$job           = $_POST['job'];
		$music         = $_POST['music'];
		$films   	 = $_POST['films'];
		$books   	 = $_POST['books'];	
		$aboutme  	 = $_POST['aboutme'];
		$favouritefood = $_POST['favouritefood'];
		
		
	  
		//mise a jour dans la bdd
		$res = updateInfo($date,$hobbies,$job,$music,$films,$books,$aboutme,$favouritefood,$userid);
	  
		if(!$res){
			echo "Query error";  
		}else{
			unset($_POST);
			header("Location: modify_info.php?updatesuccess=1");
			//Echo "Update successfully";
		}	    	
	  
		// Ne pas envoyer le POST dans header.php
		unset($_POST);
		
		
	}else{ // Affichage profile

		if (isset($_GET['updatesuccess'])){
			$message="Update info successful";
		}

		$useraddinfos=retrieve_user_add_infos($userid); // retrieve_user_infos renvoit un tableau associatif contenant toutes les infos d'un user
		
		
			
		if($useraddinfos!=false){// vérifie si la fonction est bien passée

			$date=$useraddinfos['date'];
			$hobbies=$useraddinfos['hobbies'];
			$job=$useraddinfos['job'];
			$music=$useraddinfos['music'];
			$films=$useraddinfos['films'];
			$books=$useraddinfos['books'];
			$aboutme=$useraddinfos['aboutme'];
			$favouritefood=$useraddinfos['favouritefood'];
					

			// affichage champs profile, c'est tout ce pâté
			$html= "<p>Edit your personal information:</p>
			<form action='modify_info.php?mode=info_edit_process' method='post'>
			<table>
			<tr><td>Date of birth:</td><td><input type='text' name='date' value='$date'></td></tr>
			<tr><td>Hobbies:</td><td><input type='textarea' name='hobbies' value='$hobbies'></td></tr>
			<tr><td>Job:</td><td><input type='text' name='job' value='$job'></td></tr>
			<tr><td>Music:</td><td><input type='textarea' name='music' value='$music'></td></tr>
			<tr><td>Films:</td><td><input type='textarea' name='films' value='$films'></td></tr>
			<tr><td>Books:</td><td><input type='textarea' name='books' value='$books'></td></tr>
			<tr><td>About me:</td><td><input type='textarea' name='aboutme' value='$aboutme'></td></tr>
			<tr><td>Favourite food:</td><td><input type='textarea' name='favouritefood' value='$favouritefood'></td></tr>
			<tr><td></td><td><button type='submit'>Update my information &rarr;</button></td></tr>
			</table>
		  </form>";
			
		}else{
			$message = "<p class='error'>Table INFORMATION error</p>";
		}
		
	} // fin affichage profile
	
	PrintDocument('Edit your personal information');
	
}else{
	
	header('Location: index.php');
}







 
?>

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
		
		$date          = $_POST['myDateInput'];
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
			
		
			<form action='modify_info.php?mode=info_edit_process' method='post' id='contribution'>
		
		
			<span id='myFormCalendar' class='tswFormCalendar'>
		<label for='myDateInput'>Date of birth (dd/mm/yyyy):</label> 
			<a href='javascript:tswFormCalendarGetForId(\"myFormCalendar\").togglePopUp();'>
			<span id='myFormCalendar_tswButton'
				class='tswFormCalendarButton'></span></a>
	</span>
	<script type='text/javascript'>
		//Initialize Form Calendar
		var tswFormCalendar = tswFormCalendarGetForId(\"myFormCalendar\");
		tswFormCalendar.dateFormat = 'dd/MM/yyyy';
	</script>
		<input id='myFormCalendar_tswInput' 
			name='myDateInput' value='$date'
			onkeyup='tswFormCalendarGetForId(\"myFormCalendar\").updateDates();'
			type='text' size='20' maxlength='10'/> 
	
		

			<label>Hobbies:</td><td><input type='textarea' name='hobbies' value='$hobbies'></label>
			<label>Job:</td><td><input type='text' name='job' value='$job'></label>
			<label>Music:</td><td><input type='textarea' name='music' value='$music'></label>
			<label>Films:</td><td><input type='textarea' name='films' value='$films'></label>
			<label>Books:</td><td><input type='textarea' name='books' value='$books'></label>
			<label>About me:</td><td><input type='textarea' name='aboutme' value='$aboutme'></label>
			<label>Favourite food:</td><td><input type='textarea' name='favouritefood' value='$favouritefood'></label>
			<label><button type='submit'>Update my information &rarr;</button></label>
			</table>
		  </form>
		
		
		";
		
		
			
		}else{
			$message = "<p class='error'>Table INFORMATION error</p>";
		}
		
	} // fin affichage profile
	
	PrintDocument('Edit your personal information');
	
}else{
	
	header('Location: index.php');
}







 
?>

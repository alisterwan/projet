<?php
	include './header.php';


// Fonction qui insere les infos perso d'un user dans la bdd
	function insertInfo($date,$hobbies,$job,$music,$films,$books,$aboutme,$favouritefood, $userid){
		$query = sprintf("INSERT INTO information(date_birth,hobbies,job,music,films,books,aboutme,favouritefood,id_user) VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s');",
		mysql_real_escape_string(strip_tags($date)),
		mysql_real_escape_string(strip_tags($hobbies)),
		mysql_real_escape_string(strip_tags($job)),
		mysql_real_escape_string(strip_tags($music)),
		mysql_real_escape_string(strip_tags($films)),
		mysql_real_escape_string(strip_tags($books)),
		mysql_real_escape_string(strip_tags($aboutme)),
		mysql_real_escape_string(strip_tags($favouritefood)),
		mysql_real_escape_string(strip_tags($userid)));
		$res = @mysql_query($query);
		if(!$res)
			die("Error: ".mysql_error());
		else
			return $res;
	}

	
if (isset($userid)){ // vérification si logué ou pas	
	 
  $userinfos=retrieve_user_infos($userid);
  $html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
  <h3>Add information</h3>
  
			
<form action='add_info.php?mode=add_info_process' method='post' id='contribution'>
		
		
			<span id='myFormCalendar' class='tswFormCalendar'>
		<label for='myDateInput'>Date of birth (yyyy-mm-dd):</label> 
			<a href='javascript:tswFormCalendarGetForId(\"myFormCalendar\").togglePopUp();'>
			<span id='myFormCalendar_tswButton'
				class='tswFormCalendarButton'></span></a>
	</span>
	<script type='text/javascript'>
		//Initialize Form Calendar
		var tswFormCalendar = tswFormCalendarGetForId(\"myFormCalendar\");
		tswFormCalendar.dateFormat = 'yyyy-MM-dd';
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
  
  
	// Mise à jour des données
	if ($_POST && isset($_GET['mode']) && $_GET['mode']=="add_info_process") { // ça va appeler la fonction qui va inserer les infos dans la BDD : insertInfo		
		$date          = $_POST['myDateInput'];
		$hobbies       = $_POST['hobbies'];
		$job           = $_POST['job'];
		$music         = $_POST['music'];
		$films   	 = $_POST['films'];
		$books   	 = $_POST['books'];	
		$aboutme  	 = $_POST['aboutme'];
		$favouritefood = $_POST['favouritefood'];
		
		
		
		
	  
		//mise a jour dans la bdd
		$res = insertInfo($date,$hobbies,$job,$music,$films,$books,$aboutme,$favouritefood,$userid);
	  
		if(!$res){
			echo "Query error";  
		}else{
			unset($_POST);
			header("Location: add_info.php?insertsuccess=1");
	
			//Echo "Update successfully";
		}	    	
	  
		// Ne pas envoyer le POST dans header.php
		unset($_POST);
		
		
	}
	
	else{ // Affichage profile

		if (isset($_GET['insertsuccess'])){
			$message="Your information has been modified";
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
			
						
			
		}
		
	} // fin affichage profile
}
  
  printDocument('Add information');
	

 
?>

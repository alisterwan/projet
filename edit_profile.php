<?php
include './header.php';

/*echo '<SCRIPT LANGUAGE=Javascript SRC="autoComplete/jquery-1.2.1.pack.js" />
<SCRIPT LANGUAGE=Javascript SRC="autoComplete/custom_functions.js" />';
echo "<link rel='stylesheet' href='autocomplete.css' type='text/css' media='screen'>";*/


//fonction update users

function updateUser($firstname,$surname,$address,$city,$country,$username,$email, $userid){ // fonction qui update sur la BDD

	$query = "UPDATE users SET firstname='$firstname', surname='$surname', address='$address', 
	city='$city', country='$country', username='$username', mail='$email' WHERE id=$userid ";
	$res = @mysql_query($query);
	if(!$res)
		die("Error: ".mysql_error());
	else
		return $res;
}


if (isset($userid)){ // vérification si logué ou pas	

	// Mise à jour des données
	if ($_POST && isset($_GET['mode']) && $_GET['mode']=="profile_edit_process") { // ça va appeler la fonction qui va modifier la BDD : updateUser

		$firstname = $_POST['firstname'];
		$surname   = $_POST['surname'];
		$address   = $_POST['address'];
		$city      = $_POST['city'];
		$country   = $_POST['country'];
		$username  = $_POST['username'];
		$mail      = $_POST['mail'];




		//mise a jour dans la bdd
		$res = updateUser($firstname,$surname,$address,$city,$country,$username,$mail, $userid);

		if(!$res){
			echo "Query error";  
		}else{
			unset($_POST);
			header("Location: edit_profile.php?updatesuccess=1");
			//Echo "Update successfully";
		}	    	

		// Ne pas envoyer le POST dans header.php
		unset($_POST);


	}else{ // Affichage profile

		if (isset($_GET['updatesuccess'])){
			$message="Update successful";

		}

		$userinfos=retrieve_user_infos($userid); // retrieve_user_infos renvoit un tableau associatif contenant toutes les infos d'un user



		if($userinfos!=false){// vérifie si la fonction est bien passée


			$username=$userinfos['username'];
			$firstname=$userinfos['firstname'];
			$surname=$userinfos['surname'];
			$mail=$userinfos['mail'];
			$address=mysql_escape_string($userinfos['address']);
			$city=$userinfos['city'];
			$country=$userinfos['country'];			

						/*Type your county:
				<br />
				<input type="text" size="30" value="" id="inputString" onkeyup="lookup(this.value);" onblur="fill();" />
	
			
			<div class="suggestionsBox" id="suggestions" style="display: none;">
				<img src="upArrow.png" style="position: relative; top: -12px; left: 30px;" alt="upArrow" />
				<div class="suggestionList" id="autoSuggestionsList">
					&nbsp;
				</div>
			</div>*/

			// affichage champs profile, c'est tout ce pâté
			$html= '<link rel="stylesheet" href="autocomplete.css" type="text/css" media="screen">
			<p>Your profile information:</p>
			<form action="edit_profile.php?mode=profile_edit_process" method="post" id="contribution">
			<label>Firstname:<input type="text" name="firstname" value='.$firstname.' required></label>
			<label>Surname:<input type="text" name="surname" value='.$surname.' required></label>
			
			<label>Country:<input type="text" name="country" list="countryList" value='.$country.'></label>
			
			
			<label>Address:<input type="text" name="address" value='.$address.'></label>
			<label>City:<input type="text" name="city" value='.$city.' ></label>
			<label>Username:<input type="text" name="username" value='.$username.' required></label>
			<label>Email:<input type="text" name="mail" value='.$mail.' required></label>
			<div><button type="submit">Update</button></div>
		 	</form>';

			//requete pour recuperer les pays 
			$country = mysql_query("SELECT name_en FROM country");
  			while($res = mysql_fetch_array($country)) {
    			$list .= "<option value='$res[0]'>";
  			}
			  $html .= "<datalist id='countryList'>$list</datalist>";

			//requete pour recuperer l'id du pays





		}else{
			$message = "<p class='error'>Table USER error</p>";
		}

	} // fin affichage profile

	PrintDocument('Your profile overview');

}else{

	header('Location: index.php');
}

?>

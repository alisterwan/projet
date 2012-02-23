<?php
include './header.php';

//fonction update users
function updateUser($firstname,$surname,$address,$country,$username,$email, $userid){ // fonction qui update sur la BDD

	$query = sprintf("UPDATE users SET firstname='%s', surname='%s', address='%s',country=$country, username='$username', mail='$email' WHERE id=$userid;",
	mysql_real_escape_string(strip_tags($firstname)),
	mysql_real_escape_string(strip_tags($surname)),
	mysql_real_escape_string(strip_tags($address)));
	$res = @mysql_query($query);
	if(!$res)
		die("Error: ".mysql_error());
	else
		return $res;
}

/*******************************************************************************/

if (isset($userid)){ // vérification si logué ou pas	

	// Mise à jour des données
	if ($_POST && isset($_GET['mode']) && $_GET['mode']=="profile_edit_process") { // ça va appeler la fonction qui va modifier la BDD : updateUser
		
		
		$query1 = mysql_query("SELECT * FROM country WHERE name_en='$_POST[id_country]'");
  		$res2 = mysql_fetch_assoc($query1);		
		
		
		$firstname = $_POST['firstname'];
		$surname   = $_POST['surname'];
		$address   = $_POST['address'];

		$country   = $res2[id_country];
		
		//$city      = $_POST['city'];
		
		$username  = $_POST['username'];
		$mail      = $_POST['mail'];




		//mise a jour dans la bdd
		$res = updateUser($firstname,$surname,$address,$country,$username,$mail, $userid);
		
		$country = $res2[name_en]; 
		

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
			//$city=$userinfos['city'];
			$country=$userinfos['country'];			

					

			// affichage champs profile, c'est tout ce pâté
			$html= '
			<p>Your profile information:</p>
			<form action="edit_profile.php?mode=profile_edit_process" method="post" id="contribution">
			<label>Firstname:<input type="text" name="firstname" value='.$firstname.' required></label>
			<label>Surname:<input type="text" name="surname" value='.$surname.' required></label>
			
			<label>Country:<input type="text" name="id_country" list="countryList"></label>
			
			
			<label>Address:<input type="text" name="address" value='.$address.'></label>

			<label>Username:<input type="text" name="username" value='.$username.' required></label>
			<label>Email:<input type="text" name="mail" value='.$mail.' required></label>
			<div><button type="submit">Update</button></div>
		 	</form>';

			//requete pour recuperer les pays 
			$country = mysql_query("SELECT id_country,name_en FROM country");
  			while($res = mysql_fetch_assoc($country)) {
    			$list .= "<option value='$res[name_en]'>";
  			}
			  $html .= "<datalist id='countryList'>$list</datalist>";



		}else{
			$message = "<p class='error'>Table USER error</p>";
		}

	} // fin affichage profile

	PrintDocument('Your profile overview');

}else{

	header('Location: index.php');
}

?>

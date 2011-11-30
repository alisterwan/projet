<?php
include './header.php';

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
			$address=$userinfos['address'];
			$city=$userinfos['city'];
			$country=$userinfos['country'];			

			// affichage champs profile, c'est tout ce pâté
			$html= '<p>Your profile information:</p>
			<form action="edit_profile.php?mode=profile_edit_process" method="post">
			<table>
			<tr><td>Firstname:</td><td><input type="text" name="firstname" value='.$firstname.' required></td></tr>
			<tr><td>Surname:</td><td><input type="text" name="surname" value='.$surname.' required></td></tr>
			<tr><td>Address:</td><td><input type="text" name="address" value='.$address.' ></td></tr>
			<tr><td>City:</td><td><input type="text" name="city" value='.$city.' ></td></tr>
			<tr><td>Country:</td><td><select name="country" value='.$country.' >   
				<option value="France">France</option>
			</select></td></tr>
			<tr><td>Username:</td><td><input type="text" name="username" value='.$username.' required></td></tr>
			<tr><td>Email:</td><td><input type="text" name="mail" value='.$mail.' required></td></tr>
			<tr><td></td><td><button type="submit">Update</button></td></tr>
			</table>
		  </form>';
			
		}else{
			$message = "<p class='error'>Table USER error</p>";
		}
		
	} // fin affichage profile
	
	PrintDocument('Your profile overview');
	
}else{
	
	header('Location: index.php');
}

?>

  
 


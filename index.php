<?php
	include 'header.php';

function leftboxContent(){
		 // ATTENTION IL FAUT METTRE LES QUOTES POUR id !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			  // Requête qui récupère toutes les coordonnées du client
	global $userid;
	if (isConnected($userid)) {
		$userinfos=retrieve_user_infos($userid);
			  
		$content = "<div>
			<img src='./img/templates/friends.png' width='50px' height='50px' title='Friends'/>
			<img src='./img/templates/messages.png' width='50px' height='50px' title='Messages'/>
			<img src='./img/templates/notifications.png' width='50px' height='50px' title='Notifications'/>
			</div>";
	 
	 	$countryname = getCountryNameById($userinfos['country']);		
	
		$content .= "<p>Your account information:</p>
				<div>$userinfos[firstname] &nbsp;$userinfos[surname]</div>
				<div>$userinfos[address]</div>
				<div>$userinfos[city]</div>
				<div>$countryname</div>
				<div>$userinfos[mail]</div>
			
					
			<div class='stack'>
			<img src='img/stacks/stack.png' alt='stack'/>
			<ul id='stack'>
			<li><a href='objectivesform.php'><span>Objectives</span><img src='img/stacks/objectives.png' alt='My Objectives' /></a></li>
			<li><a href='information.php'><span>Information</span><img src='img/stacks/information.png' alt='My infos' /></a></li>
			
			<li><a href='albums.php'><span>Albums</span><img src='img/stacks/albums.png' alt='My albums' /></a></li>
			<li><a href='friends.php'><span>Friends</span><img src='img/stacks/myfriends.png' alt='My friends' /></a></li>	
			<li><a href='recipes.php'><span>Recipes</span><img src='img/stacks/recipes.png' alt='My recipes' /></a></li>				
			</ul>
					</div>";	

	}else{
		// Afficher les formulaires pour se connecter et s'inscrire
		$content = "
			<form action='./index.php?mode=logon' method='post'>
			  <img src='./img/templates/login.png'/>
			  <div><input type='text' name='name' placeholder='Username' required></div>
			  <div><input type='password' name='pass' placeholder='password' required></div>
			  <div><input type='submit' value='Submit'> <a href='./#'>Forgot your password?</a></div>
			</form>";
	}

	return $content;
}


//si logué
if (isset($userid)) {
    // content
}

printDocument('Digeat Homepage');

	
?>

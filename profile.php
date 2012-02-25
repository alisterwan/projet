<?php

include './header.php';

/***************fonctions***************************/

//fonction pour verifier si deux users sont amis
  
/*
La fonction getAllGroupsByUserId($id) récupère un array des numéros des groupes auxquel $id appartient.

La fonction checkPermission($vargroup, $user) renvoie TRUE si le $user appartient à au moins un des groupes $vargroup. 
  
printAddNewFriend($userid) imprime les boutons pour ajouter en ami ou follow. $userid est l'ID du demandeur.
/**************************************************/

function printInfoMember($id){
	$infos = retrieve_user_add_infos($id);
	$ficelle = "<h4>";
	
	if($infos['date_birth']!="") $ficelle.= 'Born in '.$infos['date_birth'].'<br/>';
	if($infos['job']!="") $ficelle.= 'Works as '.$infos['job'].'<br/>';
	if($infos['music']!="") $ficelle.= 'Listens to '.$infos['music'].'<br/></h4>';
	
	return $ficelle;
}

function printProfileBanner(){
	return "<div id='content'>
		<div id='dock'>
			<div class='dock-container'>				
				<a class='dock-item' href='newmessage.php'><span>Messages</span><img src='img/dock/email.png' alt='messages' /></a> 			
				<a class='dock-item' href='groups.php'><span>Groups</span><img src='img/dock/portfolio.png' alt='history' /></a> 			
				<a class='dock-item' href='followers.php'><span>Followers</span><img src='img/dock/link.png' alt='links' /></a> 
				<a class='dock-item' href='#'><span>RSS</span><img src='img/dock/rss.png' alt='rss' /></a> 			
			</div>
		</div>
		</div>";
}

function printAddNewFriend($userid){
	return "<a href='#'><img src='./img/templates/follow.png' width='113px' height='42px' /></a>
		  <a href='#' id='removeing' onclick='addFriends(event,$userid,$_GET[id_user])'><img src='./img/templates/addfriends.png' width='113px' height='42px' /></a>
		 
		 <script>
		  function addFriends(e, id_user, id_friend) {
		  var a, url, x;
		  e.preventDefault();
		  a = e.target.parentNode;
		  a.parentNode.hidden = true;
		  url = './addFriends.php?id_user='+ id_user +'&id_friend=' + id_friend;
		  x = new XMLHttpRequest();
		  x.open('GET', url, true);
		  x.onload = function(e) {
			a.innerHTML = this.responseText;
			if(this.responseText !== 'success') {
			  a.innerHTML = this.responseText;
			  a.parentNode.hidden = false;
			}
		  };
		  x.send();
		}
		</script>";	
}

if (isset($userid)){  // vérification si logué en tant qu'utilisateur

	$userinfos=retrieve_user_infos($userid);
	$useraddinfos=retrieve_user_add_infos($userid);
	$userfriends = retrieve_user_friends($userid);  
  
	/////////////////////// Affichage du nom et bannière élémentaire ////////////////////////
	$html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>";
	$html.= printProfileBanner();
	/////////////////////// FIN Affichage du nom et bannière élémentaire ////////////////////////
	
	if($useraddinfos){ // affichage infos passion du membre
		$html.=printInfoMember($userid);
 	}

	if(isset($_GET['id_user']) && $_GET['id_user']!= $userid){ //pour les users qui visitent les profiles
		$userinfos=retrieve_user_infos($_GET['id_user']);
		$useraddinfos=retrieve_user_add_infos($_GET['id_user']);
		  
		$html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>";
		$html.= printProfileBanner();
		  
		if(!$useraddinfos){ // "visiteur", mais no content available
			$html.="<div>Sorry, there's no content available to show.</div>";
		 
			$vargroup = getAllGroupsByUserId($userid);
			$var = checkPermission($vargroup,$_GET['id_user']);
		   
		    if(!$var) $html.= printAddNewFriend($userid);
		
		}else{ // "visiteur", content available 	 
			$html.= printInfoMember($_GET['id_user']);
			$vargroup = getAllGroupsByUserId($userid);
			$var = checkPermission($vargroup,$_GET['id_user']);

			if(!$var) $html.= printAddNewFriend($userid);
		}
	}  	  	 
	 printDocument('Profile Page'); 
}

/*******************************VISITEURS NON INSCRITS*************************************/

else if (isset($_GET['id_user'])){ // pour les visiteurs
	$html='';
	$html.= RegistrationForVisitors();
	printDocument('Profile');
}
 
 /********************************************************************/
?>

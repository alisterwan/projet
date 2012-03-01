<?php include 'header.php';

/***************fonctions***************************/

//fonction pour verifier si deux users sont amis
  
/*
La fonction getAllGroupsByUserId($id) récupère un array des numéros des groupes auxquel $id appartient.

La fonction checkPermission($vargroup, $user) renvoie TRUE si le $user appartient à au moins un des groupes $vargroup. 
  
printAddNewFriend($userid) imprime les boutons pour ajouter en ami ou follow. $userid est l'ID du demandeur.
/**************************************************/

/////////////// GETTERS

function getAllGroupsExceptFollowersByUserId($idcreator){
	$query = sprintf("SELECT * FROM groups	WHERE id_creator='%s'",	mysql_real_escape_string($idcreator));
	$result = mysql_query($query);

	if (!$result) {
		return false;
	}else{
		$reponse;
		while ($row = mysql_fetch_assoc($result)) {
			if($row['name']!='Followers') $reponse[]=$row['id'];
		}
		return $reponse;
	}
}

function isLoggedVisitor(){
	global $userid;
	return (isConnected($userid) && isset($_GET['id_user']) && $_GET['id_user']!=$userid);
}

function isFriend(){
	global $userid;
	if(isConnected($userid)){
		if(isLoggedVisitor()){
			return checkPermission(getAllGroupsExceptFollowersByUserId($_GET['id_user']), $userid);
		}
	}	
	return false;
}

function isVisitor(){
	global $userid;
	if(!isConnected($userid) || isLoggedVisitor()){
		return true;
	}
	return false;
}

function isLost(){ // non connected and not visiting anything lol
	global $userid;
	if(!isConnected($userid) && !isVisitor()) return true;
	
	return false;
}

function isOwner(){
	return (!isLost() && !isFriend() && !isVisitor());
}
////////////// END GETTERS


function leftboxContent(){
	$content ='';
	global $userid;
	
	if(!isLost()){
		if (isset($_SESSION['id'])) { // if logged in
			if (isLoggedVisitor()) { // if visitor 
				// Requête qui récupère toutes les coordonnées du client
				$userinfos=retrieve_user_infos($_GET['id_user']);
				$content.= "<img src= '$userinfos[avatar]' width='170px' height='200px' />";
			}else{
				// Requête qui récupère toutes les coordonnées du client
				global $userid;
				$userinfos=retrieve_user_infos($userid);
				$content.= "<img src= '$userinfos[avatar]' width='170px' height='200px'><a href='./image.php'><img src= './img/templates/camera.png' width='50px' height='50px'></a>Change my avatar";
				$content.="<div class='stack'>
					<img src='img/stacks/stack.png' alt='stack'>
					<ul id='stack'>
						<li><a href='objectivesform.php'><span>Objectives</span><img src='img/stacks/objectives.png' alt='My Objectives'></a></li>
						<li><a href='information.php'><span>Information</span><img src='img/stacks/information.png' alt='My infos'></a></li>			
						<li><a href='albums.php'><span>Albums</span><img src='img/stacks/albums.png' alt='My albums'></a></li>
						<li><a href='friends.php'><span>Friends</span><img src='img/stacks/myfriends.png' alt='My friends'></a></li>	
						<li><a href='recipes.php'><span>Recipes</span><img src='img/stacks/recipes.png' alt='My recipes'></a></li>				
					</ul>
					</div>"; // printstack
			}
		}else if(isset($_GET['id_user'])){ // non logged in visitor
			// Requête qui récupère toutes les coordonnées du client
			$userinfos=retrieve_user_infos($_GET['id_user']);
			$content.= "<img src= '$userinfos[avatar]' width='170px' height='200px' />";
		}
		
		$content.= '<br/><br/>';
		
		if(isVisitor()){ // print link to Profile
			$content.= '<a href="profile.php?id_user='.$_GET['id_user'].'" >Profile</a>';
		}else{
			$content.= '<a href="profile.php" >Profile</a>';
		}		
		$content.= '<br/>';		
		if(isVisitor()){ // print link to wall
			$content.= '<a href="wall.php?id='.$_GET['id_user'].'" >Wall</a>';
		}else{
			$content.= '<a href="wall.php" >Wall</a>';
		}
		$content.= '<br/>';
		if(isVisitor()){ // print link to friend list
			$content.= '<a href="friends.php?id='.$_GET['id_user'].'" >Friends list</a>';
		}else{
			$content.= '<a href="friends.php" >Friends list</a>';
		}
		$content.= '<br/>';	
		if(isFriend()){ // print link to messages
			$content.= '<a href="private_messages.php?id_recipient='.$_GET['id_user'].'" >Private Messages</a>';
		}elseif(isOwner()){
			$content.= '<a href="private_messages.php" >Private Messages</a>';
			$content.= '<br/>';
			$content.= '<a href="fridge.php" >Fridge</a>';
			$content.= '<br/>';
			$content.= '<a href="shoplist.php" >Shoplist</a>';	
		}
		$content.= '<br/>';
	}
	return $content;
}



/////////////////////////////// PRINTERS /////////////////////////////
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
				<a class='dock-item' href='newmessage.php'><span>Messages</span><img src='img/dock/email.png' alt='messages'></a> 			
				<a class='dock-item' href='groups.php'><span>Groups</span><img src='img/dock/portfolio.png' alt='history'></a> 			
				<a class='dock-item' href='followers.php'><span>Followers</span><img src='img/dock/link.png' alt='links'></a> 
				<a class='dock-item' href='#'><span>RSS</span><img src='img/dock/rss.png' alt='rss'></a> 			
			</div>
		</div>
		</div>";
}

function printAddNewFriend($userid){
	return "
		  <a href='#' id='removeing' onclick='addFriends(event,$userid,$_GET[id_user])'><img src='./img/templates/addfriends.png' width='113px' height='42px'></a>
		 
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

function printAddNewSubscriber($userid){
	return "<a href='#' onclick='addSubscribers(event,$userid,$_GET[id_user])'><img src='./img/templates/follow.png' width='113px' height='42px'></a>
	
		 <script>
		  function addSubscribers(e, id_user, id_friend) {
		  var a, url, x;
		  e.preventDefault();
		  a = e.target.parentNode;
		  a.parentNode.hidden = true;
		  url = './addSubscribers.php?id_user='+ id_user +'&id_friend=' + id_friend;
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

////////////////////////////////////////////END FUNCTIONS////////////////////////////////////////////////////



if (isset($userid)){  // vérification si logué en tant qu'utilisateur
	$html = '';
	$userinfos=retrieve_user_infos($userid);
	$useraddinfos=retrieve_user_add_infos($userid);
	$userfriends = retrieve_user_friends($userid);  
  

		

	if(isset($_GET['id_user']) && $_GET['id_user']!= $userid){ //pour les users qui visitent les profiles
		$userinfos=retrieve_user_infos($_GET['id_user']);
		$useraddinfos=retrieve_user_add_infos($_GET['id_user']);
		  
		$html.= "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>";
		//$html.= printProfileBanner();
		  
		if(!$useraddinfos){ // "visiteur", mais no content available
			$html.="<div>Sorry, there's no content available to show.</div>";
		 
			$vargroup = getAllGroupsByUserId($userid);
			$var = checkPermission($vargroup,$_GET['id_user']);
		   
		    if(!$var){
				$html.= printAddNewSubscriber($userid);
				$html.= printAddNewFriend($userid);
				
			}		
		}else{ // "visiteur", content available 	 
			$html.= printInfoMember($_GET['id_user']);
			$vargroup = getAllGroupsByUserId($userid);
			$var = checkPermission($vargroup,$_GET['id_user']);

			if(!$var) $html.= printAddNewFriend($userid);
		}
	}else{ // user viewing its own profile
	
		/////////////////////// Affichage du nom et bannière élémentaire ////////////////////////
		$html.= "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>";
		//$html.= printProfileBanner();
		/////////////////////// FIN Affichage du nom et bannière élémentaire ////////////////////////
		
		if($useraddinfos){ // affichage infos passion du membre
			$html.=printInfoMember($userid);
		}
	}
	 printDocument('Profile Page'); 
}

/*******************************VISITEURS NON INSCRITS*************************************/

else if (isset($_GET['id_user'])){ // pour les visiteurs
	$html.= RegistrationForVisitors();
	printDocument('Profile');
}
 
 /********************************************************************/
?>

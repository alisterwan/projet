<?php

include 'header.php';

/**************Fonctions****************************/

/**************************************************/

//////////////////// BOXES ////////////

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
	return (isConnected() && isset($_GET['id']) && $_GET['id']!=$userid);
}

function isFriend(){
	global $userid;
	if(isConnected()){
		if(isLoggedVisitor()){
			return checkPermission(getAllGroupsExceptFollowersByUserId($_GET['id']), $userid);
		}
	}	
	return false;
}

function isVisitor(){
	global $userid;
	if(!isConnected() || isLoggedVisitor()){
		return true;
	}
	return false;
}

function isLost(){ // non connected and not visiting anything lol
	global $userid;
	if(!isConnected() && !isVisitor()) return true;
	
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
				$userinfos=retrieve_user_infos($_GET['id']);
				$content.= "<img src= '$userinfos[avatar]' width='170px' height='200px' />";
			}else{
				// Requête qui récupère toutes les coordonnées du client
				global $userid;
				$userinfos=retrieve_user_infos($userid);
				$content.= "<img src= '$userinfos[avatar]' width='170px' height='200px'><a href='./image.php'><img src= './img/templates/camera.png' width='50px' height='50px'></a>Change my avatar";
				
			}
		}else if(isset($_GET['id'])){ // non logged in visitor
			// Requête qui récupère toutes les coordonnées du client
			$userinfos=retrieve_user_infos($_GET['id']);
			$content.= "<img src= '$userinfos[avatar]' width='170px' height='200px' />";
		}
		
		$content.= '<br/><br/>';
		
		if(isVisitor()){ // print link to Profile
			$content.= '<a href="profile.php?id_user='.$_GET['id'].'" >Profile</a>';
		}else{
			$content.= '<a href="profile.php" >Profile</a>';
		}		
		$content.= '<br/>';		
		if(isVisitor()){ // print link to wall
			$content.= '<a href="wall.php?id='.$_GET['id'].'" >Wall</a>';
		}else{
			$content.= '<a href="wall.php" >Wall</a>';
		}
		$content.= '<br/>';
		if(isVisitor()){ // print link to friend list
			$content.= '<a href="friends.php?id='.$_GET['id'].'" >Friends list</a>';
		}else{
			$content.= '<a href="friends.php" >Friends list</a>';
		}
		$content.= '<br/>';	
		if(isFriend()){ // print link to messages
			$content.= '<a href="private_messages.php?id_recipient='.$_GET['id'].'" >Private Messages</a>';
			$content.= '<br/>';
			$content.= '<a href="recipes.php?iduser='.$_GET['id'].'">Recipes</a>';
		}elseif(isOwner()){
			$content.= '<a href="private_messages.php" >Private Messages</a>';
			$content.= '<br/>';
			$content.= '<a href="recipes.php">Recipes</a>';
			$content.= '<br/>';
			$content.= '<a href="fridge.php" >Fridge</a>';
			$content.= '<br/>';
			$content.= '<a href="shoplist.php" >Shoplist</a>';	
		}
		$content.= '<br/>';
	}
	return $content;
}


function rightboxContent() {
	// ATTENTION IL FAUT METTRE LES QUOTES POUR id
	if (isset($_SESSION['id'])) {
		// Requête qui récupère les infos de l'utilisteur.
		$userinfos = retrieve_user_infos($_SESSION['id']);

		$content = "";

		$countryname = getCountryNameById($userinfos['country']);

		$content.= "<p>Your account information:</p>
		<div>$userinfos[firstname] &nbsp;$userinfos[surname]</div>
		<div>$userinfos[address]</div>
		<div>$userinfos[city]</div>
		<div>$countryname</div>
		<div>$userinfos[mail]</div>
		<div class='stack'>
			<img src='img/stacks/stack.png' alt='stack'>
			<ul id='stack'>
				<li>
					<a href='objectivesform.php'>
						<span>Objectives</span>
						<img src='img/stacks/objectives.png' alt='My Objectives'>
					</a>
				</li>
				<li>
					<a href='information.php'>
						<span>Information</span>
						<img src='img/stacks/information.png' alt='My infos'>
					</a>
				</li>
				<li>
					<a href='albums.php'>
						<span>Albums</span>
						<img src='img/stacks/albums.png' alt='My albums'>
					</a>
				</li>
				<li>
					<a href='friends.php'>
						<span>Friends</span>
						<img src='img/stacks/myfriends.png' alt='My friends'>
					</a>
				</li>
				<li>
					<a href='recipes.php'>
						<span>Recipes</span>
						<img src='img/stacks/recipes.png' alt='My recipes'>
					</a>
				</li>
			</ul>
		</div>";
	} else {
		// Afficher les formulaires pour se connecter et s'inscrire.
		$content = "
		<form action='./index.php?mode=logon' method='post'>
			<img src='./img/templates/login.png'>
			<div><input type='text' name='name' placeholder='Username' required></div>
			<div><input type='password' name='pass' placeholder='password' required></div>
			<div><input type='submit' value='Submit'> <a href='./#'>Forgot your password?</a></div>
		</form>";
	}

	return $content;
}


function printAvatarBadgeByURL($url) {
	return "<img src='$url' style='width: 48px; height: 48px;' alt=avatar>";
}


function printUserBadgeById($id) {
	$user = retrieve_user_infos($id);

	if(!$user){
		return '';
	}

	$ficelle  = '
	<table style="text-align: left;" border="0" cellpadding="2" cellspacing="2">
		<tr>
			<td>'.printAvatarBadgeByURL($user['avatar']).'</td>
			<td><a href=profile.php?id_user='.$user['id'].'>'.$user['firstname'].' '.$user['surname'].'<br>';
			
	global $userid;

	if (!belongsToUserGroups($userid, $user['id'])) {
		$ficelle.= '<a href="#">Add to contacts</a>';
	}

	$ficelle.='</a></td></tr></table>';

	return $ficelle;
}


function printContactsByUserId($id) {
	$groupsids = getAllGroupsByUserId($id);
	$ficelle   = '';
	foreach ($groupsids AS $groupid) {
		$ficelle.= '<br/><h4>'.getGroupNameById($groupid).'</h4>';
		$users    = getUserIdByGroup($groupid);
		if ($users) {
			foreach ($users AS $user) {
				$ficelle.= printUserBadgeById($user).'<br>';
			}
		} else {
			$ficelle.= 'No contact<br>';
		}
	}
	return $ficelle;
}

////////////////////////////////////////////////////END FUNCTIONS////////////////////////////////////////////////////////////////

if (isConnected() && !isset($_GET['id'])) {
	$userinfos    = retrieve_user_infos($userid);
	$message ='';
	/************* Friends Request **************************/

	// Selection des id_group reliées à l'user.
	$query  = sprintf("SELECT id_group FROM groups_relations WHERE id_user='%s' AND approval='0'",
		mysql_real_escape_string($userid));
	$result = mysql_query($query);

	while ($row = mysql_fetch_assoc($result)) {
		$query1    = "SELECT id_creator FROM groups WHERE id=$row[id_group]";
		$response1 = mysql_query($query1);

		while ($row1 = mysql_fetch_assoc($response1)) {
			$query2    = "SELECT * FROM users WHERE id=$row1[id_creator]";
			$response2 = mysql_query($query2);

			while ($friend = mysql_fetch_assoc($response2)) {
				$message.= "
				<p class='error'>
					<a href='profile.php?id_user=$friend[id]'><img src='$friend[avatar]' style='height: 50px; width: 50px;'>$friend[username]</a> wants to be your friend.
					<a href='#' onclick='confirmFriends(event,$row[id_group],$userid)'>Accept</a>
					<a href='#' onclick='cancelFriends(event,$row[id_group],$userid)'>Decline</a>
				</p>";
			}
		}
	}

	/****************************************************/
	/***************** Followers announcers *************/

	// Selection des id_group reliées à l'user.
	$query  = sprintf("SELECT id_group FROM groups_relations WHERE id_user='%s' AND approval='1' AND status='0'",
		mysql_real_escape_string($userid));
	$result = mysql_query($query);

	while ($row = mysql_fetch_assoc($result)) {
		$query1 = "SELECT id_creator FROM groups WHERE id=$row[id_group]";
		$response1 = mysql_query($query1);

		while ($row1 = mysql_fetch_assoc($response1)) {
			$query2    = "SELECT * FROM users WHERE id=$row1[id_creator]";
			$response2 = mysql_query($query2);

			while($friend = mysql_fetch_assoc($response2)) {
				$message .= "
<p class='error'>
	<a href='profile.php?id_user=$friend[id]'><img src='$friend[avatar]' style='height: 50px; width: 50px;'>$friend[username]</a> is following you.
	<a href='#' onclick='confirmFollow(event,$row[id_group],$userid)'>Ok</a>
</p>";
			}
		}
	}

	/****************************************************/
	//Affichage des amis

	$html = "
	<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
	<div id='content'>
		<div id='dock'>
			<div class='dock-container'>
				<a class='dock-item' href='newmessage.php'><span>Messages</span><img src='img/dock/email.png' alt='messages'></a>
				<a class='dock-item' href='groups.php'><span>Groups</span><img src='img/dock/portfolio.png' alt='history'></a>
				<a class='dock-item' href='followers.php'><span>Followers</span><img src='img/dock/link.png' alt='links'></a>
				<a class='dock-item' href='#'><span>RSS</span><img src='img/dock/rss.png' alt='rss'></a>
			</div>
		</div>
	</div>
	<h3>My Contacts</h3>";

	/////////////  AFFICHAGE DES CONTACTS
	$html.= printContactsByUserId($userid);
	////////////  AFFICHAGE DES CONTACTS FIN
} else if (isConnected() && isset($_GET['id'])) {
	// Registered STALKER
	$userinfos = retrieve_user_infos($_GET['id']);
	$html  = "<h1>$userinfos[firstname] $userinfos[surname]</h1><h3>Contacts</h3>";
	$html.= printContactsByUserId($_GET['id']);
}

printDocument('My friends');

?>

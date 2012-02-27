<?php
	include 'header.php';
/**************Fonctions****************************/

/**************************************************/
function leftboxContent() {
	if (isset($_SESSION['id'])) {
		if (isset($_GET['id_user']) ) { 
			// Requête qui récupère toutes les coordonnées du client
			$userinfos=retrieve_user_infos($_GET['id_user']);
			$content = "<img src= '$userinfos[avatar]' width='170px' height='200px' />";

    		return $content;
		}else{
		// Requête qui récupère toutes les coordonnées du client
			global $userid;
			$userinfos=retrieve_user_infos($userid);
			$content = "<img src= '$userinfos[avatar]' width='170px' height='200px' /><a href='./image.php'><img src= './img/templates/camera.png' width='50px' height='50px'/></a>Change my avatar";
			return $content;
		}
    }else if (isset($_GET['id_user'])) { 
		// Requête qui récupère toutes les coordonnées du client
		$userinfos=retrieve_user_infos($_GET['id_user']);
		$content = "<img src= '$userinfos[avatar]' width='170px' height='200px' />";
		return $content;
    }
}

function rightboxContent() {
		if (isset($_SESSION['id'])) { // ATTENTION IL FAUT METTRE LES QUOTES POUR id !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			  // requete qui récupère toutes les coord du client
			  global $userid;

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
			</form>
		   ";
		}

		return $content;
}

function printAvatarBadgeByURL($url){
	return '<img src="'.$url.'"  width="48" height="48" alt="avatar">';
}

function printUserBadgeById($id){
	$ficelle = '<table style="text-align: left; " border="0" cellpadding="2" cellspacing="2" ><tr>';
	if($user = retrieve_user_infos($id)){
		$ficelle.='<td>'.printAvatarBadgeByURL($user['avatar']).'</td>
		<td>&nbsp;'.$user['firstname'].' '.$user['surname'].'<br/>
		&nbsp;';
		global $userid;
		if(!belongsToUserGroups($userid, $user['id'])) {
			$ficelle.= '<a href="#" >Add to contacts</a></td></tr></table>';
		}else{
			$ficelle.='</td></tr></table>';
		}
		return $ficelle;
	}
	return '';
}

function printContactsByUserId($id){
	$groupsnames;
	$groupsids = getAllGroupsByUserId($id);
	$ficelle='';
	foreach($groupsids AS $groupid){
		$ficelle.= '<br/><h4>'.getGroupNameById($groupid).'</h4>';
		$users = getUserIdByGroup($groupid);
		if($users){
			foreach($users AS $user){
				$ficelle.= printUserBadgeById($user).'<br/>';				
			}			
		}else{
			$ficelle.='No contact<br/>';
		}
	}
	return $ficelle;
}

////////////////////////////////////////////////////END FUNCTIONS////////////////////////////////////////////////////////////////

if (isConnected($userid) && !isset($_GET['id'])){	

	$userinfos=retrieve_user_infos($userid);
	$useraddinfos=retrieve_user_add_infos($userid);
	$userfriends = retrieve_user_friends($userid);	
	
/**************Friends Request****************************/ 
 //selection des id_group reliees au user
	$query = sprintf("SELECT id_group FROM groups_relations WHERE id_user='%s' AND approval='0'",
	mysql_real_escape_string($userid)); 	
	$result = mysql_query($query);	
	
	while($row=mysql_fetch_assoc($result)) {
   	$query1 = "SELECT id_creator FROM groups WHERE id=$row[id_group]";
	$response = mysql_query($query1);
	
	while($row1 = mysql_fetch_assoc($response)){
	$query2 = "SELECT * FROM users WHERE id=$row1[id_creator]";
	$response2 = mysql_query($query2);
	
	while($friend = mysql_fetch_assoc($response2)){	
	$message.="
	<p class='error'>
	<a href='profile.php?id_user=$friend[id]'>$friend[username]<img src='$friend[avatar]' width='100px height='100px''/></a> wants to be your friend 
	
	<a href='#' onclick='confirmFriends(event,$row[id_group],$userid)'>Accept</a> 
	
	<script>
	  function confirmFriends(e, idgroup, id_user, username) {
      var a, url, x;
      e.preventDefault();
      a = e.target.parentNode;
      a.parentNode.hidden = true;
      url = './confirmfriends.php?idgroup='+ idgroup +'&id_user=' + id_user;
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
	</script>

	
	<a href='#' onclick='cancelFriends(event,$row[id_group],$userid)'>Decline</a>
	
	<script>
	  function cancelFriends(e, idgroup, id_user) {
      var a, url, x;
      e.preventDefault();
      a = e.target.parentNode;
      a.parentNode.hidden = true;
      url = './cancelfriends.php?idgroup='+ idgroup +'&id_user=' + id_user;
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
	</script>
	
	</p>";	
			}
		}
	
	}
	
	
/****************************************************/	
 
 //Affichage des amis
 
	$html= "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
  
	<div id='content'>
	<div id='dock'>
		<div class='dock-container'>
			
			<a class='dock-item' href='newmessage.php'><span>Messages</span><img src='img/dock/email.png' alt='messages' /></a> 
		
			<a class='dock-item' href='groups.php'><span>Groups</span><img src='img/dock/portfolio.png' alt='history' /></a> 
		
			<a class='dock-item' href='followers.php'><span>Followers</span><img src='img/dock/link.png' alt='links' /></a> 
			<a class='dock-item' href='#'><span>RSS</span><img src='img/dock/rss.png' alt='rss' /></a> 
		
		</div>
	</div>
  	</div>";
 
	$html.="<h3>My Contacts</h3>";
	
	
	/////////////  AFFICHAGE DES CONTACTS
	$html.= printContactsByUserId($userid);
	////////////  AFFICHAGE DES CONTACTS FIN 
	
	
}elseif (isConnected($userid) && isset($_GET['id'])){ // Registered STALKER
	$userinfos=retrieve_user_infos($_GET['id']);
	$html= "<h1>$userinfos[firstname] $userinfos[surname]</h1>";
	$html.="<h3>Contacts</h3>";
	$html.= printContactsByUserId($_GET['id']);
}
	printDocument('My friends');
?>
<?php include 'header.php';

//////////////////// BOXES ////////////

function getAllGroupsExceptFollowersByUserId($idcreator){
	$query = sprintf("SELECT * FROM groups	WHERE id_creator='%s'",	mysql_real_escape_string($idcreator));
	$result = mysql_query($query);

	if (!$result || !userIdExists($idcreator)) {
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
	return (isConnected() && isset($_GET['id_recipient']) && $_GET['id_recipient']!=$userid);
}

function isFriend(){
	global $userid;
	if(isConnected()){
		if(isLoggedVisitor()){
			return checkPermission(getAllGroupsExceptFollowersByUserId($_GET['id_recipient']), $userid);
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
	
	if(!isConnected() && (!isset($_GET['id_recipient']) || (isset($_GET['id_recipient']) && !userIdExists($_GET['id_recipient']) )    )){ // id recipient bullshit
		return '';
	}
	
	if(!isLost()){
		if (isset($_SESSION['id'])) { // if logged in
			if (isLoggedVisitor()) { // if visitor 
				// Requête qui récupère toutes les coordonnées du client
				$userinfos=retrieve_user_infos($_GET['id_recipient']);
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
		}else if(isset($_GET['id_recipient'])){ // non logged in visitor
			// Requête qui récupère toutes les coordonnées du client
			$userinfos=retrieve_user_infos($_GET['id_recipient']);
			$content.= "<img src= '$userinfos[avatar]' width='170px' height='200px' />";
		}
		
		$content.= '<br/><br/>';
		
		if(isVisitor()){ // print link to Profile
			$content.= '<a href="profile.php?id_user='.$_GET['id_recipient'].'" >Profile</a>';
		}else{
			$content.= '<a href="profile.php" >Profile</a>';
		}		
		$content.= '<br/>';		
		if(isVisitor()){ // print link to wall
			$content.= '<a href="wall.php?id='.$_GET['id_recipient'].'" >Wall</a>';
		}else{
			$content.= '<a href="wall.php" >Wall</a>';
		}
		$content.= '<br/>';
		if(isVisitor()){ // print link to friend list
			$content.= '<a href="friends.php?id='.$_GET['id_recipient'].'" >Friends list</a>';
		}else{
			$content.= '<a href="friends.php" >Friends list</a>';
		}
		$content.= '<br/>';	
		if(isFriend()){ // print link to messages
			$content.= '<a href="private_messages.php?id_recipient='.$_GET['id_recipient'].'" >Private Messages</a>';
			$content.= '<br/>';
			$content.= '<a href="recipes.php?iduser='.$_GET['id_recipient'].'">Recipes</a>';
		}elseif(isOwner()){
			$content.= '<a href="private_messages.php" >Private Messages</a>';
		}
		$content.= '<br/>';
		
		if(isOwner()){ // recipes fridge and shoplist
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

/////////////// GETTERS

function getConversationByIdRecipient($id_recipient){
	global $userid;
	$sql = 'SELECT id, date
	FROM private_messages
	WHERE id_sender ='.$userid.'
	AND id_recipient ='.$id_recipient.'
	UNION ALL SELECT id, date
	FROM private_messages
	WHERE id_sender ='.$id_recipient.'
	AND id_recipient ='.$userid.'
	ORDER BY date DESC';
	$query = mysql_query($sql);
	
	if(!$query || mysql_num_rows($query)<1) return false;
	
	$conversation;
	while ($result = mysql_fetch_assoc($query)){
		$conversation[]= $result['id'];
	}
	return $conversation;
}

function getDialogers(){
	global $userid;//global $message;
	/*$sql ='SELECT id_sender, date FROM private_messages WHERE id_recipient='.$userid.'
	UNION DISTINCT
	SELECT id_recipient, date FROM private_messages WHERE id_sender ='.$userid.' ORDER BY date DESC';*/
	$sql ='SELECT id_sender FROM private_messages WHERE id_recipient='.$userid.'
	UNION DISTINCT
	SELECT id_recipient FROM private_messages WHERE id_sender ='.$userid;
	
	$query = mysql_query($sql);
	if(!$query || mysql_num_rows($query)<1) return false;
	
	$dialogers;
	while ($result = mysql_fetch_assoc($query)){
		$dialogers[]= $result['id_sender'];  //$message.= 'dialoger: '.$result['id_sender'].'<br/>';
	}
	
	$resultat;
	for($i=count($dialogers)-1; $i>=0; $i--){
		$resultat[] = $dialogers[$i];
	}
	
	return $resultat;
}

function getLastMessageIdOfConversationByOtherUser($user){
	global $userid;
	/*$sql= '	SELECT id, date FROM private_messages WHERE id_sender='.$userid.' AND id_recipient='.$user.'
	UNION ALL
	SELECT id, date FROM private_messages WHERE id_sender=1 AND id_recipient='.$userid.'
	ORDER BY date DESC
	LIMIT 0,1';*/
	
	// last sent
	$sql = 'SELECT id, date FROM private_messages WHERE id_sender='.$userid.' AND id_recipient='.$user.' ORDER BY date DESC LIMIT 0,1';
	$query = mysql_query($sql);
	if(!$query || mysql_num_rows($query)<1){
		$lastsent = false;
	}else{
		$lastsent = mysql_fetch_assoc($query);
	}
	
	// last received
	$sql = 'SELECT id, date FROM private_messages WHERE id_sender='.$user.' AND id_recipient='.$userid.' ORDER BY date DESC 0,1';
	$query = mysql_query($sql);
	if(!$query || mysql_num_rows($query)<1){
		$lastreceived = false;
	}else{
		$lastreceived = mysql_fetch_assoc($query);
	}

	if($lastsent == false){ // nothing sent
		return $lastreceived['id'];
	}
	if($lastreceived == false){ // nothing received
		return $lastsent['id'];
	}
	
	if($lastreceived['date'] < $lastsent['date']){ // last checking before sending the very last message id
		return $lastreceived['id']; // lastsent
	}
	return $lastsent['id']; // lastreceived	
}

function isSender($user, $idmessage){
	$sql = 'SELECT id_sender FROM private_messages WHERE id='.$idmessage;
	$query = mysql_query($sql);
	
	if(!$query) return false;
	
	$result = mysql_fetch_assoc($query);
	return $result['id_sender'] == $user;
}
/////////////// PRINTERS

function printAvatarByUserId($id){ // display avatar miniature
	$sql = 'SELECT avatar FROM users WHERE id='.$id;
	$query = mysql_query($sql);
	$result = mysql_fetch_assoc($query);
	if(!file_exists($result['avatar'])){
		$result['avatar'] = NO_IMAGE;
	}
	return '<img src="'.$result['avatar'].'"  width="64" height="64" alt="avatar">';
}

function printPMById($idpm){ // display one single message by message ID
	$sql = 'SELECT * FROM private_messages WHERE id='.$idpm;
	$query = mysql_query($sql);
	if(!$query) return false;
	$result = mysql_fetch_assoc($query);
	
	$id_sender = $result['id_sender'];
	$id_recipient = $result['id_recipient'];
	$message = $result['message'];
	$date = $result['date'];
	
	$ficelle = '<table style="text-align: left; " border="0" cellpadding="2" cellspacing="2"><tr>';
	$ficelle.= '<td>'.printAvatarByUserId($id_sender).'</td>';
	$ficelle.= '<td>From: '.printLinkToProfileByUserId($id_sender).'<br/>'.$message;
	//$ficelle.= '</td><td style="text-align: right; ">'.$date.'</td>'; DATE in another case
	$ficelle.= '<br/>'.$date.'</td></tr>';
	$ficelle.= '</table>';
	
	return $ficelle;
}

function printAllPMByIdRecipient($id_recipient){ // display lastest messages of a conversation
	$allpm = getConversationByIdRecipient($id_recipient);
	
	if($allpm==false) return 'No message';
	
	$ficelle ='';
	foreach($allpm AS $pm){
		$ficelle.=printPMById($pm).'<hr/>';
	}
	return $ficelle;
}

function printNewPMByIdRecipient($id_recipient){ // display new message form 
	$ficelle ='<form action="private_messages.php?id_recipient='.$id_recipient.'" method="POST" >';
	$ficelle.= '<table style="text-align: left; " border="0" cellpadding="2" cellspacing="2">';
	$ficelle.= '<td style="text-align: right; "> To:</td><td>'.printLinkToProfileByUserId($id_recipient).'</td></tr>';
	$ficelle.= '<td style="text-align: right; "> Message:</td><td><textarea name="newpm" id="newpm" /></textarea></td></tr>';
	$ficelle.= '<tr><td></td><td><input type="submit" value="Send" /></td></tr></table></form>';
	return $ficelle;
}

function printDiscussionByMessageId($id_message){
	global $userid;
	$sql = 'SELECT * FROM private_messages WHERE id='.$id_message;
	$query = mysql_query($sql);
	$ficelle = '';//$sql;//'';'';
	
	if(!$query || mysql_num_rows($query)<1) return $ficelle;
	
	$result = mysql_fetch_assoc($query);
	
	$ficelle.= '<table style="text-align: left; " border="0" cellpadding="2" cellspacing="2"><tr>';
	
	if(isSender($userid, $id_message)){
		$ficelle.= '<td>'.printAvatarByUserId($result['id_recipient']).'</td>
		<td>You have sent to '.printLinkToProfileByUserId($result['id_recipient']).':<br/><a href="private_messages.php?id_recipient='.$result['id_recipient'].'" >';
	}else{
		$ficelle.= '<td>'.printAvatarByUserId($result['id_sender']).'</td>
		<td>You have received from '.printLinkToProfileByUserId($result['id_sender']).':<br/><a href="private_messages.php?id_recipient='.$result['id_sender'].'" >';		
	}
	
	$ficelle.= $result['message'].'</a>';
	//$ficelle.= '</td><td>'.$result['date'].'</td></tr></table>'; date in an other case
	$ficelle.= '<br/>'.$result['date'].'</td></tr></table>';
	
	return $ficelle;
}

function printLastestDiscussions(){
	$ficelle = '';
	$dialogers = getDialogers();
	if($dialogers == false){ // no discussion 
		$ficelle.= 'No message';	
	}else{
		foreach($dialogers AS $single){
			$ficelle.= '<hr/>'.printDiscussionByMessageId(getLastMessageIdOfConversationByOtherUser($single));
		}		
	}
	
	return $ficelle;
}

////////////////////// END FUNCTIONS


global $userid;
if(isConnected()){ // logged in
	$html = ''; // God !
	
	if(isset($_GET['id_recipient']) && userIdExists($_GET['id_recipient']) && $userid!=$_GET['id_recipient'] && belongsToUserGroups($userid, $_GET['id_recipient']) ){ // view chats with id_recipient
	
		if($_POST){ // something to treat?
			if(isset($_POST['newpm']) && $_POST['newpm']!=''){ // new pm 
				$sql = 'INSERT INTO private_messages(id_sender, id_recipient, message) 
				VALUES('.$userid.', '.$_GET['id_recipient'].', "'.$_POST['newpm'].'")';
				$query = mysql_query($sql); // sending message
				if (!$query) $message = 'Sending message failed';
			}
		}
	
		$html.= '<a href="'.$_SERVER['PHP_SELF'].'" >Back to Private Messages main page</a>';
		$html.= '<h3>Private messages</h3>';
		$html.= printNewPMByIdRecipient($_GET['id_recipient']).'<h4>Latest messages</h4>';
		$html.= printAllPMByIdRecipient($_GET['id_recipient']);
		
	}elseif(isset($_GET['id_recipient']) && !userIdExists($_GET['id_recipient'])){ // id_recipient bulshit

	}elseif(isset($_GET['id_recipient'])  && !belongsToUserGroups($userid, $_GET['id_recipient']) && $userid!=$_GET['id_recipient']){ // doesn't belong to user group, shouldn't be there anyway!

	}else{ // view General
		$html.= '<a href="friends.php" >Back to Contacts main page</a>';
		$html.= '<h2>Private Messages</h2>
		<h4>Latest discussions</h4>'.
		printLastestDiscussions();
	}

	printDocument('Private messages');
}elseif(isset($_GET['id_recipient']) && $userid==$_GET['id_recipient'] && userIdExists($_GET['id_recipient'])){
	header('Location: private_messages.php');
}else{ // visitors
	$html='';
	$html.= RegistrationForVisitors();
	printDocument('Sign up now!');
}


?>
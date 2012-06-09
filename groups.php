<?php
include './header.php';

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

function retrieve_group_member($groupid){ // prend en paramètre l'id de groupe, soit $_SESSION['id']
	$sql='SELECT id_creator,name FROM groups_relations WHERE id_group='.$groupid;
	$query=mysql_query($sql);
	$verif = mysql_num_rows($query);
	
	if ($verif > 0){
	return $result=mysql_fetch_assoc($query);
	}
	return false;
}

function createGroup($name,$creatorid){
	$query2 = sprintf("INSERT INTO groups(name,id_creator) VALUES('%s','%s');",
	mysql_real_escape_string(strip_tags($name)),
	mysql_real_escape_string(strip_tags($creatorid)));
	$res = @mysql_query($query2);
	if(!$res)
	die("Error: ".mysql_error());
	else
	return $res;
}

function getGroupName($idgroup){
	$query = "SELECT * from groups WHERE id='$idgroup'";
	$result = mysql_query($query);
	if(!$result)
	die("Error: ".mysql_error());
	else
	return $row = mysql_fetch_assoc($result);
}

function redirect() { //fonction de redirection vers la page de groupe créé
    $query = mysql_fetch_row(mysql_query(
		sprintf("SELECT id FROM groups WHERE name LIKE '%s'",
		mysql_real_escape_string(strip_tags($_POST['name'])))
    ));
    $id = $query[0];
    header("Location: groups.php?id=$id");
    exit;
} 

function printAvatarBadgeByURL($url) {
	if(file_exists($url)){
		return "<img src='$url' style='width: 48px; height: 48px;' alt=avatar>";
	}else{
		return '<img src="'.NO_IMAGE.'" style="width: 48px; height: 48px;" alt="avatar">';
	}
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
	$ficelle.='</a></td></tr></table>';

	return $ficelle;
}

function addFriendToGroupId($idgroup,$iduser){
	
	// vérifier s'il n'est pas déjà ajouté
	$query = mysql_query('SELECT id FROM groups_relations WHERE id_group='.$idgroup.' AND id_user='.$iduser);
	if($query && mysql_num_rows($query) > 0){
		return '<div>'.getFirstnameSurnameByUserId($iduser).' already belongs to the group.</div>';
	}
	
	$query  = "INSERT INTO groups_relations(id_group,id_user,approval,status) VALUES ($idgroup,$iduser,1,1)";
	$result = mysql_query($query);

	if (!$result) {
		die("Error: ".mysql_error());
	}
		
	else return "<div>".getFirstnameSurnameByUserId($iduser)." successfully added.</div>";			

}


function printContactsByUserId($id,$idgroup) {	
	$groupsids = getFriendsByUserId($id);
	$ficelle   = "<form action='groups.php?mode=addcontact&id=$idgroup' method='post'>";
	foreach ($groupsids AS $groupid){
		$users    = getUserIdByGroup($groupid);
		if ($users) {
			foreach ($users AS $user) {
				$ficelle.= printUserBadgeById($user)."<input type='checkbox'  name='users[]' value='$user'><br>";
			}
		} 
	}
	$ficelle.= "<input type='submit' value='submit'></form>";

	return $ficelle;
}

//////////////////////////////////////////////////////


if (isset($userid)){  // vérification si logué ou pas

	if(isset($_GET['mode']) && $_GET['mode'] == "new_group"){ // new group
		if($_POST) {
			$groupname = $_POST['name'];		
			$res = createGroup($groupname,$userid); 
		 
			if (!$res){ 
				$message = "<p class='error'>Cannot create a new group</p>";
			}else{
				$html = "Your group has been successfully added.";
				redirect();
			}	 
		}
  
		$userinfos=retrieve_user_infos($userid);
		$useraddinfos=retrieve_user_add_infos($userid);
	  
		$html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>			 
				<form action='$_SERVER[PHP_SELF]?mode=new_group' method='post' id='contribution'>
				<p>Create a group</p>";
				
		if(isset($_POST['name'])){
			$html.="<label>Group name:<input type='text' name='name' value='$groupname' required></label>";
		}else{
			$html.="<label>Group name:<input type='text' name='name' required></label>";
		}
		
		$html.="<input type='submit' value='Send'></form>";
		
		printDocument('Create a new group');
		
	}
	
	if(isset($_GET['mode']) && $_GET['mode'] == "addcontact" && $_GET['id']){
		$html = '';
		if(isset($_POST['users'])) { 
			$html.= '<a href="'.$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'" >Back to Group page</a>';
			$group = getGroupName($_GET['id']);
			//$html.= "<h3>Contact has been added to $group[name]</h3>";
			for ($i = 0; $i <count($_POST['users']); $i++){
				$html.= addFriendToGroupId($_GET['id'],$_POST['users'][$i]);
			}
		}else{
			//content to add
			$group = getGroupName($_GET['id']);
			$html = '<a href="'.$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'" >Return to Group Page</a>';
			$html.= "<h3>Add contact to $group[name]</h3>";
			$html.= printContactsByUserId($userid,$_GET['id']);
		}
		printDocument('Add contact to group');
		
    }else{	// group page
	
		$userinfos=retrieve_user_infos($userid);
		$useraddinfos=retrieve_user_add_infos($userid);
	  
		//$i = retrieve_group_member($_GET['id']);
	  
		$html = '<a href="friends.php" >Return to Contacts Page</a>';
		$html.= "<h2>My Groups</h2>";	
		
		//requete pour recuperer les groupes  
		$group = mysql_query("SELECT id,name FROM groups WHERE id_creator='$userid'");
		$html.= "<ul>";
		while($res = mysql_fetch_assoc($group)) {		 
			$html.= "<a href='groups.php?id=$res[id]'><li>$res[name]</li></a>";	
		}			
			
		$html.= "<li><a href='groups.php?mode=new_group'>Add a new group</li></a></ul>";
		
		if(isset($_GET['id'])){
			$html.= '<hr>';
			$group = getGroupName($_GET['id']);
			$html.= "<h3>$group[name]</h3>";
			$users = getUserIdByGroup($_GET['id']);
			if ($users) {
				foreach ($users AS $user) {
					$html.= printUserBadgeById($user).'<br>';
				}
				$html.="<a href='groups.php?mode=addcontact&id=$_GET[id]'>Add contacts</a>";
			} else {
				$html.= 'No contact<br>';
				$html.="<a href='groups.php?mode=addcontact&id=$_GET[id]'>Add contacts</a>";
			}
		}
		
		printDocument('My Groups');
	}
}else{	
	header('Location: index.php');
}
  
?>
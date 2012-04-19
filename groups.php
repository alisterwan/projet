<?php
include './header.php';

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

function redirect() { //fonction de redirection vers la page de groupe créé
    $query = mysql_fetch_row(mysql_query(
		sprintf("SELECT id FROM groups WHERE name LIKE '%s'",
		mysql_real_escape_string(strip_tags($_POST['name'])))
    ));
    $id = $query[0];
    header("Location: groups.php?id=$id");
    exit;
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
			<div id='content'>
				<div id='dock'>
					<div class='dock-container'>			
						<a class='dock-item' href='newmessage.php'><span>Messages</span><img src='img/dock/email.png' alt='messages' /></a>	
						<a class='dock-item' href='groups.php'><span>Groups</span><img src='img/dock/portfolio.png' alt='history' /></a>			
						<a class='dock-item' href='followers.php'><span>Followers</span><img src='img/dock/link.png' alt='links' /></a> 
						<a class='dock-item' href='#'><span>RSS</span><img src='img/dock/rss.png' alt='rss' /></a>		
					</div>
				</div>
			</div>	 
		 
			<form action='newgroup.php' method='post' id='contribution'>
				<p>Create a group</p>
				<label>Group name:<input type='text' name='name' value='$name' required></label>		  
				<input type='submit' value='Send'>
			</form>";
		
		printDocument('Create a new group');
		
	}else{	// group page
	
		$userinfos=retrieve_user_infos($userid);
		$useraddinfos=retrieve_user_add_infos($userid);
	  
		//$i = retrieve_group_member($_GET['id']);
	  
		$html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>		
			<div id='content'>
				<div id='dock'>
					<div class='dock-container'>			
						<a class='dock-item' href='newmessage.php'><span>Messages</span><img src='img/dock/email.png' alt='messages' /></a> 		
						<a class='dock-item' href='groups.php'><span>Groups</span><img src='img/dock/portfolio.png' alt='history' /></a> 		
						<a class='dock-item' href='followers.php'><span>Followers</span><img src='img/dock/link.png' alt='links' /></a> 
						<a class='dock-item' href='#'><span>RSS</span><img src='img/dock/rss.png' alt='rss' /></a> 		
					</div>
				</div>
			</div>	  
			<h2>My Groups</h2>";	
		
		//requete pour recuperer les groupes  
		$group = mysql_query("SELECT id,name FROM groups WHERE id_creator='$userid'");
			$html .= "<ul>";
			while($res = mysql_fetch_assoc($group)) {		 
				$html .= "<a href='groups.php?id=$res[id]'><li>$res[name]</li></a>";	
			}			
			
			$html .= "<li><a href='groups.php?mode=new_group'>Add a new group</li></a></ul>";
		
		printDocument('Compose a message');
	}
}else{	
	header('Location: index.php');
}
  
?>

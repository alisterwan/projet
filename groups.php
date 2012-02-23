<?php
	include './header.php';



function retrieve_group_member($groupid){ // prend en paramËtre l'id de groupe, soit $_SESSION['id']
	$sql='SELECT id_creator,name FROM groups_relations WHERE id_group='.$id;
	$query=mysql_query($sql);
	$verif = mysql_num_rows($query);
	
	if ($verif > 0){
	return $result=mysql_fetch_assoc($query);
	}
	return false;
  }


if (isset($userid)){  // vérification si logué ou pas

  
  $userinfos=retrieve_user_infos($userid);
  $useraddinfos=retrieve_user_add_infos($userid);
  
  $i = retrieve_group_member($_GET[id]);
  
  $html = "
	<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
	
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
  
	
	 <h2>My Groups</h2>
	";
	
	
	 //requete pour recuperer les groupes  
	 $group = mysql_query("SELECT id,name FROM groups WHERE id_creator='$userid'");
  		$html .= "<ul>";
  		while($res = mysql_fetch_assoc($group)) {		 
		 $html .= "<a href='groups.php?id=$res[id]'><li>$res[name]</li></a>";
	
		 }
			
		 $html .= "<li><a href='newgroup.php'>
		 		   Add a new group	 
		 		   </li></a> 
		 		   </ul>
		 		   ";
	

  printDocument('Compose a message');
  
}else{
	
	header('Location: index.php');
}
  
?>

<?php

include './header.php';

/***************fonctions***************************/

//fonction pour verifier si deux users sont amis
  

/**************************************************/




if (isset($userid)){  // vérification si logué en tant qu'utilisateur

  $userinfos=retrieve_user_infos($userid);
  $useraddinfos=retrieve_user_add_infos($userid);
  $userfriends = retrieve_user_friends($userid);
  
  
  
  
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
  
  ";
  
  if($useraddinfos){
 	  $html.="<h4>Born in $useraddinfos[date_birth], Works as $useraddinfos[job] Listen to $useraddinfos[music] music</h4>";
 	 
 	  }

 
  


 if(isset($_GET[id_user]) && $_GET[id_user]!= $userid){ //pour les users qui visitent les profiles
 $userinfos=retrieve_user_infos($_GET[id_user]);
 	  $useraddinfos=retrieve_user_add_infos($_GET[id_user]);
 	  
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
  
 	  
 	  ";
 	  
 	  if(!$useraddinfos){
 	  $html.="<div>Sorry, there's no content available to show.</div>";
 	 
 	  $vargroup = getGroupId($userid);
 	  $var = checkFriendship($vargroup,$_GET[id_user]);
 	   
 	  
 	  if($var){
 	   $html.= $var;
 	  }
 	  	  
 	
 	  }
  
  	else {
 	 
 	 
 	 
 	 $html.="
 	 <h4>Born in $useraddinfos[date_birth], Works as $useraddinfos[job] Listen to			 $useraddinfos[music] music</h4> ";	
 	 
 	 $vargroup = getGroupId($userid);
 	  $var = checkFriendship($vargroup,$_GET[id_user]);
 	   
 	  
 	  if($var){
 	   $html.= $var;
 	  }

	}

 
 }  
  
   printDocument('Profile Page'); 
}

/********************************************************************/

	else if (isset($_GET[id_user])){ // pour les visiteurs
	  $userinfos=retrieve_user_infos($_GET[id_user]);
 	  $useraddinfos=retrieve_user_add_infos($_GET[id_user]);
 	  
 	  $html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
 	  "
 	  
 	  ;
 	  
 	  if(!$useraddinfos){
 	  
 	  $html.="<div>Sorry, there's no content available to show.</div>
 	  <a href='#'><img src='./img/templates/follow.png' width='113px' height='42px' /></a>
 	  <a href='#'><img src='./img/templates/addfriends.png' width='113px' height='42px' /></a>
 	  ";
 	  }
  
  	else {
 	 
 	 $html.="
 	 <h4>Born in $useraddinfos[date_birth], Works at $useraddinfos[job] Listen to			 $useraddinfos[music] music</h4>
 	<a href='#'><img src='./img/templates/follow.png' width='113px' height='42px' /></a>
 	<a href='#'><img src='./img/templates/addfriends.png' width='113px' height='42px' /></a>
 	  ";
	}
	printDocument('Profile');
}
 
 /********************************************************************/

?>

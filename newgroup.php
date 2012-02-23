<?php
	include './header.php';

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

 //fonction de redirection vers la page de groupe crŽe
  function redirect() {
    $query = mysql_fetch_row(mysql_query(
      sprintf("SELECT id FROM groups WHERE name LIKE '%s'",
        mysql_real_escape_string(strip_tags($_POST['name'])))
    ));
    $id = $query[0];
    header("Location: groups.php?id=$id");
    exit;
  }



if (isset($userid)){  // vŽrification si loguŽ ou pas

	if($_POST) {

	$groupname = $_POST['name'];
		
	$res = createGroup($groupname,$userid); 
	 
	if (!$res){ 
	$message = "<p class='error'>Cannot create a new group</p>";
	}
	else {
	$html = "Your group has been successfully added.";
	redirect();
	}
	 
	}	

  
  $userinfos=retrieve_user_infos($userid);
  $useraddinfos=retrieve_user_add_infos($userid);
  
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
   
	 
	 
	<form action='newgroup.php' method='post' id='contribution'>
    <p>Create a group</p>
    <label>Group name:<input type='text' name='name' value='$name' required></label>
  
    <input type='submit' value='Send'>
  	</form>
	
	";
	
	
	

  printDocument('Create a new group');
  
}else{
	
	header('Location: index.php');
}
  
?>

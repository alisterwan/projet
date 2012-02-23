<?php
	include './header.php';


if (isset($userid)){  // vŽrification si loguŽ ou pas

  
  $userinfos=retrieve_user_infos($userid);
  $useraddinfos=retrieve_user_add_infos($userid);
  
  $html = "
	<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
	
	 
	<form action='newmessage.php' method='post' id='contribution'>
    <p>Compose your message</p>
    <label>To:<input type='text' name='name' value='$name' required></label>
    <p>Your message:</p>
    <textarea name='description'>$_POST[description]</textarea>
    <input type='submit' value='Send'>
  	</form>
	
	";
	
	
	

  printDocument('Compose a message');
  
}else{
	
	header('Location: index.php');
}
  
?>

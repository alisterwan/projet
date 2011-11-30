<?php
  include './header.php';

if (isset($userid)){ // vérification si logué ou pas

  
  $userinfos=retrieve_user_infos($userid);
  $html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
  <h3>My Albums</h3>
  ";

  printDocument('My Albums');
  
}else{
	
	header('Location: index.php');
}
  
?>

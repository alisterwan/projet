<?php
	include './header.php';

if (isset($userid)){  // v�rification si logu� ou pas

  
  $userinfos=retrieve_user_infos($userid);
  $useraddinfos=retrieve_user_add_infos($userid);
  
  $html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
  <h3>My Profile</h3>
  <h4>Born in $useraddinfos[date_birth], Works at $useraddinfos[job] Listen to $useraddinfos[music]</h4>
  ";

  printDocument('My Profile');
  
}else{
	
	header('Location: index.php');
}
  
?>

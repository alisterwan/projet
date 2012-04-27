<?php
  include '../header.php';
  header('Content-type: text/plain');
	
	$query = "SELECT id FROM groups	WHERE id_creator='$_GET[id_user]' AND name='Followers'";
	$result = mysql_query($query);

	if($result){
	 $row =	mysql_fetch_assoc($result);
	}
	

	$userinfos = retrieve_user_infos($_GET['id_friend']);
	
	$res2 = mysql_query("INSERT INTO groups_relations(id_group,id_user,approval,status) VALUES ($row[id],$_GET[id_friend],1,0)");
		
		if(!$res2)
			die("Error: ".mysql_error());
		else
			 echo "You are following $userinfos[firstname] $userinfos[surname] ($userinfos[username])";	
 

?>

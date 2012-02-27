<?php
  include './header.php';
  header('Content-type: text/plain');

	$query = "SELECT id FROM groups	WHERE id_creator='$_GET[id_user]' AND name='Friends'";
	$result = mysql_query($query);

	if($result){
	 $row =	mysql_fetch_assoc($result);
	}
	

	$res2 = @mysql_query("INSERT INTO groups_relations(id_group,id_user,approval) VALUES ($row[id],$_GET[id_friend],0)");
		
		if(!$res2)
			die("Error: ".mysql_error());
		else
			 echo "
			 <img src='./img/templates/pendingfriend.png' width='113px' height='42px' />";	
 


?>

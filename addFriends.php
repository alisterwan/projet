<?php
  include './header.php';
  header('Content-type: text/plain');

	
  $userfriends = retrieve_user_friends($userid);
  $groupnum = getGroupId($userid);
  
  	$query2 ="INSERT INTO groups_relations(id_group,id_user,approval) VALUES ($groupnum,$_GET[id_friend],0)";
		
		$res2 = @mysql_query($query2);
		
		if(!$res2)
			die("Error: ".mysql_error());
		else
			 echo "<img src='./img/templates/pendingfriend.png' width='113px' height='42px' />";	
 

  
?>

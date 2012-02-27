<?php
  include './header.php';
  header('Content-type: text/plain');


		
		
		$res2 = @mysql_query("INSERT INTO groups_relations(id_group,id_user,approval) VALUES ($_GET[id_user],$_GET[id_friend],0)");
		
		if(!$res2)
			die("Error: ".mysql_error());
		else
			 echo "
			 <img src='./img/templates/pendingfriend.png' width='113px' height='42px' />";	
 
?>

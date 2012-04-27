<?php
  include '../header.php';
  header('Content-type: text/plain');

  	
  
  	$query ="DELETE FROM groups_relations WHERE id_group='$_GET[idgroup]' AND id_user='$_GET[id_user]'";
		
		$res2 = @mysql_query($query);
		
		if(!$res2)
			die("Error:".mysql_error());
		else
			 echo "You have canceled this request";	
 

  
?>

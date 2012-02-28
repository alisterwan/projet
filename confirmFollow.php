<?php
  include './header.php';
  header('Content-type: text/plain');




  //met a jour dans un sens la requete
  $query = sprintf("UPDATE groups_relations SET approval='1', status='1' WHERE id_group='$_GET[idgroup]' AND id_user='$_GET[id_user]'");
		$res = @mysql_query($query);
		
		if(!$res){
			die("Error: ".mysql_error());
			}

echo "New subscriber";
?>			
<?php
  include './header.php';
 header('Content-type: text/plain');


function printNotification($iduser){

  $sql = "SELECT * FROM groups_relations WHERE id_user='$iduser' AND status='0'";
  $query = mysql_query($sql);

  if(!$query) die("Error: ".mysql_error());

  $row = mysql_fetch_assoc($query));
	
  return $row;	
}

?>

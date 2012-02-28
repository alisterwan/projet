<?php
  include './header.php';
 header('Content-type: text/plain');


function printNotification($iduser){

  $sql = "SELECT * FROM groups_relations WHERE id_user='$iduser' AND status='0'";
  $query = mysql_query($sql);

  if(!$query) die("Error: ".mysql_error());
  
  $html = "<ol>";
  	
  while($row = mysql_fetch_assoc($query)){
  
  $html. = "<li>
  <span>id Groupe: $row[id_group]</span>
  <span>id User: $row[id_user]</span>
  <span>Approval $row[approval]</span>
  </li>"
  
  }
			
  $html. = "</ol>";	
		
  return $html;	
}

?>

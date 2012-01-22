<?php
  include './header.php';
 header('Content-type: text/plain');

  //efface un ingredient d'une recette
  $sql = "DELETE from recipes WHERE id='$_GET[id]'";
  $query = @mysql_query($sql);

  if(!$query) die("Error: ".mysql_error());

  echo "success";
  
?>

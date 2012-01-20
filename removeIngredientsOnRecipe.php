<?php
  include './header.php';
  header('Content-type: text/plain');

  //efface un ingredient d'une recette
  $sql = "DELETE from recipe_ingredients WHERE id_recipe='$_GET[id_rec]' AND id_ingredient='$_GET[id_ing]'";
  $query = @mysql_query($sql);

  if(!$query) die("Error: ".mysql_error());

  echo "success";
?>

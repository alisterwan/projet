<?php
  include '../header.php';
 header('Content-type: text/plain');

  $sql1 = "DELETE from recipe_ingredients WHERE id_recipe='$_GET[id]'";
  $query1 = @mysql_query($sql1);	
  
   if(!$query1) die("Error 1: ".mysql_error());	


//quand les recettes auront toutes les fonctionnalitŽs on pourra utiliser 
//ces fonctions 

/*   
   else	
 
  $sql2 = "DELETE from recipe_photos WHERE id_recipe='$_GET[id]'";
  $query2 = @mysql_query($sql2);	
  
   if(!$query2) die("Error 2: ".mysql_error());	
   
  else
  
   $sql3 = "DELETE from recipe_comments WHERE id_recipe='$_GET[id]'";
  $query3 = @mysql_query($sql3);	
  
   if(!$query3) die("Error 3: ".mysql_error());	 
   
   else
   
   $sql4 = "DELETE from recipe_view_permission WHERE id_recipe='$_GET[id]'";
  $query4 = @mysql_query($sql4);	
  
   if(!$query4) die("Error 4: ".mysql_error());	 
   
   $sql5 = "DELETE from recipe_view_permission WHERE id_recipe='$_GET[id]'";
  $query5 = @mysql_query($sql5));	
  
   if(!$query5) die("Error 5: ".mysql_error());	
  
  else
   
    $sql6 = "DELETE from recipe_rating WHERE id_recipe='$_GET[id]'";
  $query6 = @mysql_query($sql6);	
  
   if(!$query6) die("Error 6: ".mysql_error()); 	
   
   else
   
    $sql7 = "DELETE from recipe_taste WHERE id_recipe='$_GET[id]'";
  $query7 = @mysql_query($sql7);	
  
   if(!$query7) die("Error 7: ".mysql_error()); 	
*/
	else

  //efface une recette
  $sql = "DELETE from recipes WHERE id='$_GET[id]'";
  $query = @mysql_query($sql);

  if(!$query) die("Error: ".mysql_error());

  echo "success";  
?>

<?php
  include './header.php';

function retrieve_recipe_infos($id){ // prend en paramètre l'id de l'user, soit $_SESSION['id']
	$sql='SELECT name_en,description_en,country_origin,difficulty,num_serves,duration_preparation,duration_cook,preparation_en FROM recipes WHERE id='.$id;
	$query=mysql_query($sql);
	$verif = mysql_num_rows($query);
	
	if ($verif > 0){
	return $result=mysql_fetch_assoc($query);
	}
	return false;
  }



if (isset($userid)){ // vérification si logué ou pas

  $userinfos=retrieve_user_infos($userid);
  
   $i = retrieve_recipe_infos($_GET[id]);
   
  
   
   if($i[difficulty] == 0){ $i[difficulty] = 'Easy';}
   else 
   if($i[difficulty] == 1){ $i[difficulty] = 'Normal';}
   else
   if($i[difficulty] == 2){ $i[difficulty] = 'Difficult';}
   else 
   if($i[difficulty] == 3){ $i[difficulty] = 'Lunatic';}
  
  $html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
  <h1 align='center'>$i[name_en]</h1>
  
  	<div>
	<strong>Ingredients</strong>:<ul>";
	//selection des ingredients reliees a la recette
	$query = sprintf("SELECT id_ingredient FROM recipe_ingredients WHERE id_recipe='%s'",
	mysql_real_escape_string($_GET[id])); 	
	$result = mysql_query($query);	
	
	while($row=mysql_fetch_row($result)) {
   	$query1 = "SELECT name_en FROM ingredients WHERE id=$row[0]";
	$response = mysql_query($query1);
	while($row1 = mysql_fetch_assoc($response)){
	$html.="<li>$row1[name_en]</li>";	
	}	
   }
   
	$query = sprintf("SELECT path_source FROM recipe_photos WHERE id_recipe='%s'",
	mysql_real_escape_string($_GET[id])); 	
	$result2 = mysql_query($query);
	
	if(mysql_num_rows($result2) == 1){
	$ij = mysql_fetch_row($result2);
	 $html.= "<img src='img/recipes/$userinfos[id]_$_GET[id].jpg' />";	
	}
	


  $html.="
  	</ul>
	</div>
	
	<div>
	<div><strong>Description</strong>: $i[description_en]</div>
	<div><strong>Origin</strong>: $i[country_origin]</div>
	<div><strong>Difficulty</strong>: $i[difficulty]</div>
	<div><strong>Number Serves</strong>: $i[num_serves] </div>
	<div><strong>Preparation</strong>: $i[duration_preparation] minutes</div>
	<div><strong>Cooking</strong>: $i[duration_cook] minutes</div>
	<div><strong>Instructions</strong>: $i[preparation_en]</div>
	

	</div>";

  
 
  

  printDocument('My Recipes');
  
}else{
	
	header('Location: index.php');
}
  
?>

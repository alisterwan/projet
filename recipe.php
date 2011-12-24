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

function mysql_fetch_all($id_recipe) {
  $query = sprintf("SELECT id_ingredient FROM recipe_ingredients
    WHERE id_recipe='%s'",
    mysql_real_escape_string($id_recipe)); 	
	
   $result = mysql_query($query);	
   	
   while($row=mysql_fetch_array($result)) {
       
	foreach($row as $value){
	$q ='SELECT name_en FROM ingredients WHERE id='.$value;	
	$res = mysql_query($q);
	$value = mysql_fetch_array($res);
	echo $value;
	echo "\n";	
		}
   }
   return false;
}




if (isset($userid)){ // vérification si logué ou pas

  $userinfos=retrieve_user_infos($userid);
  
   $i = retrieve_recipe_infos($_GET[id]);
   $j = mysql_fetch_all($_GET[id]);
   
  
  $html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
  <h3>$i[name_en]</h3>
  
  	<div>
	<strong>Ingredients</strong>$j[id_ingredient]
	</div>
 
	<div>
	<div><strong>Description</strong>: $i[description_en]</div>
	<div><strong>Origin</strong>: $i[country_origin]</div>
	<div><strong>Difficulty</strong>: $i[difficulty]</div>
	<div><strong>Number Serves</strong>: $i[num_serves] persons</div>
	<div><strong>Preparation</strong>: $i[duration_preparation] minutes</div>
	<div><strong>Cooking</strong>: $i[duration_cook] minutes</div>
	<div><strong>Instructions</strong>: $i[preparation_en]</div>
	

	</div>";

  
 
  

  printDocument('My Recipes');
  
}else{
	
	header('Location: index.php');
}
  
?>

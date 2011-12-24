<?php
	include './header.php';

function printRecipes($userid) {
$recipe = good_query_assoc("SELECT * FROM recipes WHERE id_user = $userid");

	if (!$recipe){
		
		return $html="
		<h4>You haven't got recipes</h4>
		<div>
		<ul>
		<li><small><a href='./newrecipe.php'>Add a new recipe</a></small></li>
		<li><small><a href='./newingredient.php'>Add a new ingredient</a></small></li>
		<ul>
		</div>
		";
	}
	
	else 
	
	{
		
		
		return $html="
		<a href='./recipe.php?id=$recipe[id]'>$recipe[name_en]</a> 
		<div>
		<ul>
		<li><small><a href='./newrecipe.php'>Add a new recipe</a></small></li>
		<li><small><a href='./newingredient.php'>Add a new ingredient</a></small></li>
		<ul>
		</div>
		";

	  }
 }



if (isset($userid)){  // vérification si logué ou pas

  
  $userinfos=retrieve_user_infos($userid);
  $useraddinfos=retrieve_user_add_infos($userid);
  
  $html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
  <h3>My Profile</h3>
  <h4>Born in $useraddinfos[date_birth], Works at $useraddinfos[job] Listen to $useraddinfos[music]</h4>
  
  ".printRecipes($userid)."
  
  ";

  printDocument('My Recipes');
  
}else{
	
	header('Location: index.php');
}
  
?>

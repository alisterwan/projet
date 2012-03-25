<?php
	include './header.php';
/****************************/

function retrieve_recipe_infos($id){
	$sql='SELECT name_en,description_en,country_origin,difficulty,num_serves,duration_preparation,duration_cook,preparation_en,id_user FROM recipes WHERE id='.$id;
	$query=mysql_query($sql);
	$verif = mysql_num_rows($query);

	if ($verif > 0){
	return $result=mysql_fetch_assoc($query);
	}
	return false;
  }

/***************************/


if (isset($userid)){  // vérification si logué ou pas


	/* Affichage des recettes des amis*/
	if (isset($_GET['iduser'])){

	$userinfos=retrieve_user_infos($_GET['iduser']);
    $useraddinfos=retrieve_user_add_infos($_GET['iduser']);



	$query = sprintf("SELECT * FROM recipes WHERE id_user='%s' AND approval='0'",
	mysql_real_escape_string($_GET['iduser']));
	$result = mysql_query($query);
	$toto = mysql_num_rows($result);

	if ($toto==0){
	$html="
	<h3>Your friend hasn't got recipes</h3>";
	}
	else {

	$html = "<h3>$userinfos[firstname] $userinfos[surname] ($userinfos[username]) 's Recipes:</h3>";

	while($row3=mysql_fetch_assoc($result)) {


	$query2 = "SELECT * FROM recipe_photos WHERE id_recipe=$row3[id]";
	$result2 = mysql_query($query2);
	$row2=mysql_fetch_assoc($result2);

		$html.="<div><a href='./recipe.php?id=$row3[id]&iduser=$_GET[iduser]'><img src='$row2[path_source]' 	width='250px' height='220px' alt='$row3[name_en]' title='$row3[name_en]'/><br/>$row3[name_en]</a></div>";
		}
	}
} else {

  $userinfos=retrieve_user_infos($userid);
  $useraddinfos=retrieve_user_add_infos($userid);

  $html = "
	<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
	<h3>My Recipes</h3>
	<div class='navlinks'>
		<a href='./newrecipe.php'>Add Recipe</a>
		<a href='./newingredient.php'>Add Ingredients</a>
	</div>";

$query = sprintf("SELECT * FROM recipes WHERE id_user='%s'",
	mysql_real_escape_string($userid));
	$result = mysql_query($query);

	if (!$result){
	$html.="
	<p>You haven't got recipes</p>";
	}
	else {

	while($row3=mysql_fetch_assoc($result)) {

	$query2 = "SELECT * FROM recipe_photos WHERE id_recipe=$row3[id]";
	$result2 = mysql_query($query2);
	$row2=mysql_fetch_assoc($result2);

	$html.="<div><a href='./recipe.php?id=$row3[id]'><img src='$row2[path_source]' width='250px' height='220px' alt='$row3[name_en]' title='$row3[name_en]'/><br/>$row3[name_en]</a></div>";
	}

	}
	}

  printDocument('My Recipes');
}




else{
		header('Location: index.php');
}

?>

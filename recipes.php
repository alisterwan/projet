<?php
include 'header.php';

/******* NEW Recipe Functions *******/

//fonction pour inserer un ingredient dans la bdd
function insertIngredient($name){
	$query = sprintf("INSERT INTO ingredients(name_en) VALUES('%s');", mysql_real_escape_string(strip_tags($name)));
	$result = @mysql_query($query);

	if($result){
		return $result;
	}else{
		die('Error: '.mysql_error());
	}
}

//fonction pour recuperer le nom de l'ingredient
function getnameIngredient($name) {
	$query = sprintf("SELECT name_en FROM ingredients WHERE name_en='%s'", mysql_real_escape_string($name));
	$result = mysql_query($query);

	if (!$result) {
		return false;
	} else while ($row = mysql_fetch_assoc($result)) {
		// ??????????????????????????????????????
		// return dans un while ?
		return $row['name_en'];
	}

	// ??????????????????????????????????????
	// on appele une fonction après avoir retourner la méthode?
	mysql_free_result($result);
}



//fonction pour recuperer le id de l'ingredient
function getidIngredient($name) {
	$query = sprintf("SELECT id FROM ingredients WHERE name_en='%s'", mysql_real_escape_string($name));
	$result = mysql_query($query);

	if (!$result) {
		//si l'ingredient n'existe pas on l'ajoute dans la table ingredient
		return false;
	}else{
		while ($row = mysql_fetch_assoc($result)) {
			// ??????????????????????????????????????
			// return dans un while ?
			return $row['id'];
		}
	}

	// ??????????????????????????????????????
	// on appele une fonction après avoir retourner la méthode?
	mysql_free_result($result);
}

// Fonction qui insere un new recipe dans la bdd
function insertRecipeIng($idRecipe, $idIngredient) {
	$query = sprintf("INSERT INTO recipe_ingredients(id_recipe,id_ingredient) VALUES('%s','%s');",
		mysql_real_escape_string(strip_tags($idRecipe)),
		mysql_real_escape_string(strip_tags($idIngredient)));
	$result = @mysql_query($query);

	if ($result) {
		return $result;
	} else {
		die('Error: '.mysql_error());
	}
}

//fonction de redirection vers la page de recette crée
function redirect_recipe() {
	$query = mysql_fetch_row(
		mysql_query(
			sprintf("SELECT id FROM recipes WHERE name_en LIKE '%s'",
				mysql_real_escape_string(
					strip_tags($_POST['name'])
				)
			)
		)
	);

	$id = $query[0];
	header("Location: recipe.php?id=$id");
	exit;
}

function insertRecipe() {
	global $userid;

	// On récupère l'ID du pays POSTé.
	$country = mysql_fetch_assoc(mysql_query("SELECT id_country FROM country WHERE name_en='$_POST[country]'"));
	if (!$country) {
		// On met France comme pays par défaut si le pays donné n'existe pas.
		$country['id_country'] = 67;
	}

	$query = sprintf("INSERT INTO recipes(name_en, description_en, country_origin, difficulty, num_serves, duration_preparation, duration_cook, preparation_en, approval, id_user) VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');",
		mysql_real_escape_string(strip_tags($_POST['name'])),
		mysql_real_escape_string(strip_tags($_POST['description'])),
		mysql_real_escape_string(strip_tags($country['id_country'])),
		mysql_real_escape_string(strip_tags($_POST['difficulty'])),
		mysql_real_escape_string(strip_tags($_POST['serves'])),
		mysql_real_escape_string(strip_tags($_POST['prepDuration'])),
		mysql_real_escape_string(strip_tags($_POST['cookDuration'])),
		mysql_real_escape_string(strip_tags($_POST['method'])),
		mysql_real_escape_string(strip_tags($_POST['permission'])),
		mysql_real_escape_string(strip_tags($userid)));
	$result = mysql_query($query);

	if (!$result) {
		die('Error: '.mysql_error());
	}

	return true;
}

/*********** END -- NEW Recipe functions ***********/

 /////////////
 
/*********** NEW Ingredient functions ***********/

function redirect_ingredient() {
	$query = mysql_fetch_row(mysql_query(sprintf("SELECT id FROM ingredients WHERE name_en LIKE '%s'",
			mysql_real_escape_string(strip_tags($_POST['name'])))));
    $id = $query[0];
    header("Location: ingredients.php?id=$id");
    exit;
}

/*********** END -- NEW Ingredient functions ***********/

//////////////

/***************Generic functions *************/

function retrieve_recipe_infos($id){
	$sql='SELECT name_en,description_en,country_origin,difficulty,num_serves,duration_preparation,duration_cook,preparation_en,id_user FROM recipes WHERE id='.$id;
	$query=mysql_query($sql);
	$verif = mysql_num_rows($query);

	if ($verif > 0){
		return $result=mysql_fetch_assoc($query);
	}
	return false;
}

/**************MAIN*************/

if(isset($_GET['mode']) && $_GET['mode'] == "new_recipe"){
	if ($_POST) {
	$query = mysql_num_rows(
		mysql_query(
			sprintf("SELECT id FROM recipes WHERE name_en LIKE '%s'",
				mysql_real_escape_string(strip_tags($_POST['name']))
			)
		)
	);
	
	if ($query > 0) {
		// Si la recette existe déjà,
		// on redirige l'utilisateur vers la dite recette.
		redirect_recipe();
		// On a exit.
	}

	$reussi = insertRecipe();

	// On récupère l'ID de la recette.
	$id_recipe = mysql_insert_id();

	// On renomme et déplace l'image correctement.
	if ((isset($_FILES['picture']) && ($_FILES['picture']['error'] == UPLOAD_ERR_OK))) {
		// Array contenant les mime-types autorisés.
		$imgExtensions = array('jpg', 'jpeg', 'png', 'gif');
		$extension     = strtolower(pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION));

		// On vérifie qu'il s'agit bien d'une image.
		if (!in_array($extension, $imgExtensions)) {
			$message.= "<p class='error'>The uploaded file's type is not supported.</p>";
		} else {
			$file_path = './img/recipes/'.$userid.'_'.$id_recipe.'.'.$extension;

			// Transfère de l'image du répertoire temporaire vers le dossier './img/recipes/'.
			move_uploaded_file($_FILES['picture']['tmp_name'], $file_path);

			// Créer le lien entre la recette et l'image.
			$query = sprintf("INSERT into recipe_photos(id_recipe, path_source) VALUES('%s', '%s');",
				mysql_real_escape_string(strip_tags($id_recipe)),
				mysql_real_escape_string(strip_tags($file_path)));
			@mysql_query($query);
		}
	}

	// Insertion des ingrédients dans le BDD.
	$i = 1;
	while ($_POST[$i]) {
		$ing = getidIngredient($_POST[$i]);

		//insere l'ingredient dans la bdd s'il n'existe pas
		if ($ing == 0) {
			insertIngredient(($_POST[$i]));
			$ing = getidIngredient(($_POST[$i]));
		}

		insertRecipeIng($id_recipe, $ing);
		$i++;
	}

	// Il y aurait-il une meilleure manière de vérifier
	// que toute les requêtes ont été exécuté correctement ?
	if ($reussi && $query) {
		redirect_recipe();
	} else {
		$message.= "<p class='error'>Please check your recipe again and submit.</p>";
	}
}



	$html =	"<form action='recipes.php?mode=new_recipe' method='post' id='contribution' enctype='multipart/form-data'>
			<p>Please define the recipe.</p>";
			
	if (isset($_POST['name'])){ // récup name si déjà envoyé
		$html.= "<label>Name <input type='text' name='name' value='$_POST[name]' required /></label>";
	}else{
		$html.= "<label>Name <input type='text' name='name' required /></label>";
	}
	if (isset($_POST['name'])){ // récup description si déjà envoyé
		$html.= "<label>Description <textarea name='description'>$_POST[description]</textarea></label>";
	}else{
		$html.= "<label>Description <textarea name='description'></textarea></label>";
	}		
	if (isset($_POST['name'])){ // récup country si déjà envoyé
		$html.= "<label>Origin <input type='text' name='country' list='countryList' value='$_POST[country]' /></label>";
	}else{
		$html.= "<label>Origin <input type='text' name='country' list='countryList' /></label>";
	}
			
	$html.= "<label>Difficulty
			<select name='difficulty'>";

	$result = mysql_query("SELECT id, name_en FROM recipe_difficulty");

	while ($rows = mysql_fetch_assoc($result)) {
		$html.= "<option value='$rows[id]'>$rows[name_en]</option>";
	}


	$html.= "</select>
		</label>";
		
	if (isset($_POST['serves'])){ // récup Serves si déjà envoyé
		$html.= "<label>Servings <input type='number' name='serves' value='$_POST[serves]' /></label>";
	}else{
		$html.= "<label>Servings <input type='number' name='serves' /></label>";
	}
	if (isset($_POST['prepDuration'])){ // récup prepDuration si déjà envoyé
		$html.= "<label>Preparation Duration (min) <input type='number' name='prepDuration' value='$_POST[prepDuration]' /></label>";
	}else{
		$html.= "<label>Preparation Duration (min) <input type='number' name='prepDuration'  /></label>";
	}
	if (isset($_POST['cookDuration'])){ // récup cookDuration si déjà envoyé
		$html.= "<label>Cooking Duration (min) <input type='number' name='cookDuration' value='$_POST[cookDuration]' /></label>";
	}else{
		$html.= "<label>Cooking Duration (min) <input type='number' name='cookDuration' /></label>";
	}

	$html.= "<label for='picture'>Picture of the Recipe :</label>
		<input type='file' size='65' name='picture' /></p>";


	$i = 1;
	while (isset($_POST[$i])) {
		$html.= "<label>Ingredient $i<input type='text' name='$i' list='ingredientList' value='".$_POST["$i"]."'></label>";
		$i++;
	}

	$html.= "<a id='more' href='#'>Add ingredient...</a><br>
			<script>
				var i = $i;
				$('#more').on('click', function(e) {
					e.preventDefault();
					$(this).before('<label>Ingredient '+i+'<input type=\"text\" name='+i+' list=\"ingredientList\"></label>');
					$(this).prev().updatePolyfill();
					i++;
				});
			</script>";
			
	if (isset($_POST['method'])){ // récup cookDuration si déjà envoyé
		$html.= "<label>Preparation Method <textarea name='method'>$_POST[method]</textarea></label>";
	}else{
		$html.= "<label>Preparation Method <textarea name='method'></textarea></label>";
	}

	$html.= "<label>Make this recipe:</label>
				<input type='radio' name='permission' value='0' checked>Public
				<input type='radio' name='permission' value='1'>Private
				<input type='submit' value='Submit'>
			</form>";


	//requête pour recupérer les pays
	$country = mysql_query("SELECT name_en FROM country");
	$list = "";
	while ($res = mysql_fetch_array($country)) {
		$list.= "<option value='$res[0]'>";
	}
	$html.= "<datalist id='countryList'>$list</datalist>";


	//requête pour recupérer les ingrédients
	$ingredients = mysql_query("SELECT name_en,id FROM ingredients");
	$list2 = "";
	while ($ingredient = mysql_fetch_array($ingredients)) {
		$list2.= "<option value='$ingredient[0]'>$ingredient[0]</option>";
	}
	$html.= "<datalist id='ingredientList'>$list2</datalist>";

	printDocument('New Recipe');
	
}else if(isset($_GET['mode']) && $_GET['mode'] == "new_ingredient"){
	
	if(isset($_POST['name']) && isset($_POST['description']) && $_POST['name']!="" ){

	$query = mysql_num_rows(mysql_query(sprintf("SELECT id FROM ingredients WHERE name_en LIKE '%s'",
        mysql_real_escape_string(strip_tags($_POST['name'])))));
		
    if($query){
		redirect_ingredient();
    }else{
		$query = sprintf("INSERT INTO ingredients(name_en,description_en) VALUES('%s','%s');",
        mysql_real_escape_string(strip_tags($_POST['name'])),
        mysql_real_escape_string(strip_tags($_POST['description'])));
		$response = @mysql_query($query);
		if(!$response) {
			$message = "<p class='error'>Connection error.</p>";
		}else{
			redirect_ingredient();
		}
    }
	}

	// formulaire
	$html = "<form action='recipes.php?mode=new_ingredient' method='post' id='contribution'>
			<p>Please define the ingredient.</p>
			<label>Name <input type='text' name='name' required></label>
			<p>You may also describe it.</p>
			<textarea name='description'></textarea>
			<input type='submit' value='Submit'>
		</form>";

	printDocument('New ingredient');
	
}else{ // Shows recipes
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
				$html="<h3>Your friend hasn't got recipes</h3>";
			}else{
				$html = "<h3>$userinfos[firstname] $userinfos[surname] ($userinfos[username]) 's Recipes:</h3>";
				while($row3=mysql_fetch_assoc($result)) {
					$query2 = "SELECT * FROM recipe_photos WHERE id_recipe=$row3[id]";
					$result2 = mysql_query($query2);
					$row2=mysql_fetch_assoc($result2);
					$html.="<div><a href='./recipe.php?id=$row3[id]&iduser=$_GET[iduser]'><img src='$row2[path_source]' 	width='250px' height='220px' alt='$row3[name_en]' title='$row3[name_en]'/><br/>$row3[name_en]</a></div>";
				}
			}
		}else{

			$userinfos=retrieve_user_infos($userid);
			$useraddinfos=retrieve_user_add_infos($userid);

			/*$html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
				<h3>My Recipes</h3>
				<div class='navlinks'>
					<a href='recipes.php?mode=new_recipe'>Add Recipe</a>
					<a href='recipes.php?mode=new_ingredient'>Add Ingredients</a>
				</div>";*/
			$html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
				<h3>My Recipes</h3>
				<div class='navlinks'>
					<a href='recipes.php?mode=new_recipe'>Add Recipe</a>
					<a href='recipes.php?mode=new_ingredient'>Add Ingredients</a>
				</div>";
				
			$query = sprintf("SELECT * FROM recipes WHERE id_user='%s'", mysql_real_escape_string($userid));
			
			$result = mysql_query($query);

			if (!$result){
				$html.="<p>You haven't got recipes</p>";
				
			}else{
				while($row3=mysql_fetch_assoc($result)) {
					$query2 = "SELECT * FROM recipe_photos WHERE id_recipe=$row3[id]";
					$result2 = mysql_query($query2);
					$row2=mysql_fetch_assoc($result2);
					$html.="<div><a href='./recipe.php?id=$row3[id]'><img src='$row2[path_source]' width='250px' height='220px' alt='$row3[name_en]' title='$row3[name_en]'/><br/>$row3[name_en]</a></div>";
				}
			}
		}
	  printDocument('My Recipes');
	  
	}else{
		header('Location: index.php');
	}
}
?>

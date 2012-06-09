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
	} else {
		$row = mysql_fetch_assoc($result);
		$html = $row['name_en'];
	}

	mysql_free_result($result);
	return $html;
}

//fonction pour recuperer le id de l'ingredient
function getidIngredient($name) {
	$query = sprintf("SELECT id FROM ingredients WHERE name_en='%s'", mysql_real_escape_string($name));
	$result = mysql_query($query);

	if (!$result) {
		//si l'ingredient n'existe pas on l'ajoute dans la table ingredient
		return false;
	}else{
		$row = mysql_fetch_assoc($result);
		$html = $row['id'];
	}

	// ??????????????????????????????????????
	// on appele une fonction après avoir retourner la méthode?
	mysql_free_result($result);
	return $html;
}

// recupère un tableau d'id de recettes faites par les contacts de l'utilisateur
function getLastestRecipesofContact(){
	if(!isConnected()) return false;

	global $userid;
	$groups = getAllGroupsByUserId($userid);
	if(!$groups || count($groups)<1) return false;

	$users = getAllUsersOfGroups($groups);

	if($users == false || count($users)<1 ) return false;

	$sql = 'SELECT id FROM recipes WHERE ';
	$nbusers = count($users);
	$i = 0;
	foreach($users AS $user){
		$i++;
		$sql.= 'id_user='.$user;
		if($i < $nbusers) $sql.= ' OR ';
	}

	$sql.= ' ORDER BY creation DESC LIMIT 0, 4';
	$query = mysql_query($sql);
	if(!$query || mysql_num_rows($query)<1) return false;

	$posts;
	while($result = mysql_fetch_assoc($query)){
		$recipes[] = $result['id'];
	}
	return $recipes;
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

	$query = sprintf("INSERT INTO recipes(name_en, description_en, country_origin, difficulty, num_serves, duration_preparation, duration_cook, preparation_en, type, approval, id_user) VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');",
		mysql_real_escape_string(strip_tags($_POST['name'])),
		mysql_real_escape_string(strip_tags($_POST['description'])),
		mysql_real_escape_string(strip_tags($country['id_country'])),
		mysql_real_escape_string(strip_tags($_POST['difficulty'])),
		mysql_real_escape_string(strip_tags($_POST['serves'])),
		mysql_real_escape_string(strip_tags($_POST['prepDuration'])),		
		mysql_real_escape_string(strip_tags($_POST['cookDuration'])),
		mysql_real_escape_string(strip_tags($_POST['method'])),
		mysql_real_escape_string(strip_tags($_POST['recipe_type'])),
		mysql_real_escape_string(strip_tags($_POST['permission'])),
		mysql_real_escape_string(strip_tags($userid)));
	$result = mysql_query($query);

	if (!$result) {
		die('Error: '.mysql_error());
	}

	return true;
}


/*************** PRINTERS ********************/
function printSelectRecipeType(){
	$sdf = '<label>Recipe type <select name="recipe_type" id="recipe_type" >';
	
	$query = mysql_query('SELECT * FROM recipe_type');
	if($query!=false){
		while($type = mysql_fetch_assoc($query)){
			if(isset($_POST['recipe_type']) && $_POST['recipe_type'] == $type['id']){
				$sdf.= '<option value='.$type['id'].' selected="selected">'.$type['name_en'].'</option>';
			}else{
				$sdf.= '<option value='.$type['id'].' >'.$type['name_en'].'</option>';
			}
		}
	}
	
	$sdf.= '</select></label>';
	return $sdf;
}

function printRecipeDifficulty(){
	$sdf = "<label>Difficulty
			<select name='difficulty'>";

	$result = mysql_query("SELECT id, name_en FROM recipe_difficulty");

	while ($rows = mysql_fetch_assoc($result)) {
		if(isset($_POST['difficulty']) && $_POST['difficulty'] == $rows['id']){
			$sdf.= "<option value='$rows[id]' selected='selected' >$rows[name_en]</option>";
		}else{
			$sdf.= "<option value='$rows[id]' >$rows[name_en]</option>";
		}
	}

	$sdf.= "</select></label>";
	return $sdf;
}

function printPublicRecipes(){
	$sq = "<h1>All recipes</h1>";
	
	$sq.= '<h3>Dish type</h3>';
	// print type selector
	$sq.= printRecipeTypeSelector();
	
	$sq.= "<hr>";
	
	// print results by selected type
	$sq.= '<strong>Results</strong> <br><br>';
	$sq.= printRecipesBySelectedType();
	
	return $sq;
}

function printRecipeTypeSelector(){
	$sq = '';
	
	if(isset($_GET['recipe_type'])){
		if(isset($_GET['mode']) && $_GET['mode'] == "pending_public_recipes"){
			$sq.= '<center><a href="'.$_SERVER['PHP_SELF'].'?mode=pending_public_recipes" >All</a>';
		}else{
			$sq.= '<center><a href="'.$_SERVER['PHP_SELF'].'?mode=public_recipes" >All</a>';
		}
	}else{
		$sq.= '<center>All';
	}
	
	$query = mysql_query('SELECT * FROM recipe_type');
	if(!$query){
		return $sq;
	}
	
	while($result = mysql_fetch_assoc($query)){
		if(  (isset($_GET['recipe_type']) && $result['id'] != $_GET['recipe_type']) || !isset($_GET['recipe_type']) ){
			if(isset($_GET['mode']) && $_GET['mode'] == "pending_public_recipes"){
				$sq.= ' | <a href="'.$_SERVER['PHP_SELF'].'?mode=pending_public_recipes&recipe_type='.$result['id'].'" >'.$result['name_en'].'</a>';
			}else{
				$sq.= ' | <a href="'.$_SERVER['PHP_SELF'].'?mode=public_recipes&recipe_type='.$result['id'].'" >'.$result['name_en'].'</a>';
			}
		}else{
			$sq.= ' | '.$result['name_en'];
		}
	}
	$sq.= '</center>';
	return $sq;
}

function printRecipesBySelectedType(){
	$sq = "";
	$type;

	if(!isset($_GET['recipe_type'])){
		if(isset($_GET['mode']) && $_GET['mode'] == "pending_public_recipes"){
			$sql = 'SELECT id, name_en FROM recipes WHERE approval=0';
		}else{
			$sql = 'SELECT id, name_en FROM recipes WHERE approval=2';
		}
	}else{
		if(isset($_GET['mode']) && $_GET['mode'] == "pending_public_recipes"){
			$sql = 'SELECT id, name_en FROM recipes WHERE approval=0 AND type='.$_GET['recipe_type'];
		}else{
			$sql = 'SELECT id, name_en FROM recipes WHERE approval=2 AND type='.$_GET['recipe_type'];
		}
	}
	
	$query = mysql_query($sql);
	if(!$query || mysql_num_rows($query) < 1){
		return 'No recipe found';
	}
	
	while($result = mysql_fetch_assoc($query)){
		$sq.= '<a href="recipe.php?id='.$result['id'].'" >'.printRecipeThumbnail($result['id']).' '.$result['name_en'].'</a><br><br>';
	}
	
	return $sq;
}

function printRecipeThumbnail($recipeID){
	$query = mysql_query('SELECT path_source FROM recipe_photos WHERE id_recipe='.$recipeID);
	if(!$query || mysql_num_rows($query) < 1){
		return '<img style="width: 75px; height: 75px;" alt="recipe_img" src="'.NO_IMAGE.'" border="0" >';
	}
	$result = mysql_fetch_assoc($query);
	if(file_exists($result['path_source'])){
		return '<img style="width: 75px; height: 75px;" alt="recipe_img" src="'.$result['path_source'].'" border="0" >';
	}
	return '<img style="width: 75px; height: 75px;" alt="recipe_img" src="'.NO_IMAGE.'" border="0" >';
}

function printMyRecipes(){
	global $userid;
	$df = "";
	
	$df.= '<h3>My Recipes</h3>';
	
	$query = mysql_query('SELECT id, name_en FROM recipes WHERE id_user='.$userid);
	
	if(!$query || mysql_num_rows($query) < 1 ){
		$df.= 'No recipe found.';
	}else{
		while($result = mysql_fetch_assoc($query)){
			$df.= '<a href="recipe.php?id='.$result['id'].'" >'.printRecipeThumbnail($result['id']).' '.$result['name_en'].'</a><br><br>';
		}
	}
	
	return $df;
}

function printLastestPublicRecipes(){
	$df = "";
	
	$df.= '<h3>Lastest Public Recipes</h3>';
	
	$query = mysql_query('SELECT id, name_en FROM recipes WHERE approval=2 ORDER BY creation DESC LIMIT 0, 4');
	
	if(!$query || mysql_num_rows($query) < 1 ){
		$df.= 'No recipe found.';
	}else{
		while($result = mysql_fetch_assoc($query)){
			$df.= '<a href="recipe.php?id='.$result['id'].'" >'.printRecipeThumbnail($result['id']).' '.$result['name_en'].'</a><br><br>';
		}
	}
	
	return $df;
}

function printLastestRecipesOfContacts(){
	$df = "<h3>Lastest Recipes of Contacts</h3>";
	
	$recipes = getLastestRecipesofContact();
	if(!$recipes){
		$df.= 'No recipe found';
	}else{
		foreach($recipes AS $recipe){
			$query = mysql_query('SELECT id, name_en FROM recipes WHERE id='.$recipe);
			if($query && mysql_num_rows($query) >0 ){
				$result = mysql_fetch_assoc($query);
				$df.= '<a href="recipe.php?id='.$result['id'].'" >'.printRecipeThumbnail($result['id']).' '.$result['name_en'].'</a><br><br>';
			}
		}
	}
	
	return $df;
}

function printPendingPublicRecipes(){
	$sq = "<h1>Vote for recipes!</h1>";
	
	$sq.= '<h3>Dish type</h3>';
	// print type selector
	$sq.= printRecipeTypeSelector();
	
	$sq.= "<hr>";
	
	// print results by selected type
	$sq.= '<strong>Results</strong> <br><br>';
	$sq.= printRecipesBySelectedType();
	
	return $sq;
}

/*********** END -- NEW Recipe functions ***********/

 /////////////
 
/*********** NEW Ingredient functions ***********/

function redirect_ingredient() {
	$query = mysql_fetch_row(mysql_query(sprintf("SELECT id FROM ingredients WHERE name_en LIKE '%s'",
			mysql_real_escape_string(strip_tags($_POST['name'])))));
    $id = $query[0];
    header("Location: recipes.php");
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
global $message;

if(isset($_GET['mode']) && $_GET['mode'] == "new_recipe"){
	if ($_POST) {
	// vérifie s'il existe une recette du même nom
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
			$rep = '/img/recipes/';
			$file_path = $userid.'_'.$id_recipe.'.'.$extension;
			$destination = $rep.$file_path;

			// Transfère de l'image du répertoire temporaire vers le dossier './img/recipes/'.
			if(!move_uploaded_file($_FILES['picture']['tmp_name'], $destination)){
			exit("cannot upload");
			};
			
			$thumb = "img/recipesthumb/$file_path";
			createThumb($destination,$thumb,150,"left");

			// Créer le lien entre la recette et l'image.
			$query = sprintf("INSERT into recipe_photos(id_recipe, path_source) VALUES('%s', '%s');",
				mysql_real_escape_string(strip_tags($id_recipe)),
				mysql_real_escape_string(strip_tags($thumb)));
			@mysql_query($query);
		}
	}

	// Insertion des ingrédients dans le BDD.
	$i = 1;
	while (isset($_POST[$i])) {
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
	$html = '';
	$html.= '<a href="'.$_SERVER['PHP_SELF'].'" >Return to Recipe main page</a>';
	
	$html.=	"<form action='recipes.php?mode=new_recipe' method='post' id='contribution' enctype='multipart/form-data'>
			<p>Please define the recipe.</p>";
			
	if (isset($_POST['name'])){ // récup name si déjà envoyé
		$html.= "<label>Name <input type='text' name='name' value='$_POST[name]' required /></label>";
	}else{
		$html.= "<label>Name <input type='text' name='name' required /></label>";
	}
	
	$html.= printSelectRecipeType(); // Recipe Type Selection $_POST['recipe_type']
	
	if (isset($_POST['description'])){ // récup description si déjà envoyé
		$html.= "<label>Description <textarea name='description'>$_POST[description]</textarea></label>";
	}else{
		$html.= "<label>Description <textarea name='description'></textarea></label>";
	}		
	if (isset($_POST['country'])){ // récup country si déjà envoyé
		$html.= "<label>Origin <input type='text' name='country' list='countryList' value='$_POST[country]' /></label>";
	}else{
		$html.= "<label>Origin <input type='text' name='country' list='countryList' /></label>";
	}
			
	$html.= printRecipeDifficulty(); // Recipe Difficulty Selection $_POST['difficulty']
		
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
	$html = '';
	$html.= '<a href="'.$_SERVER['PHP_SELF'].'" >Return to Recipe main page</a>';
	$html.= "<form action='recipes.php?mode=new_ingredient' method='post' id='contribution'>
			<p>Please define the ingredient.</p>
			<label>Name <input type='text' name='name' required></label>
			<p>You may also describe it.</p>
			<textarea name='description'></textarea>
			<input type='submit' value='Submit'>
		</form>";

	printDocument('New ingredient');
	
}else{ // Shows recipes
	$html = "";
	if (isset($userid)){  // vérification si logué ou pas
	
		/* Affichage des recettes des amis*/
		if (isset($_GET['iduser'])){
			$html.='<a href="profile.php?id_user='.$_GET['iduser'].'" >Back to Profile</a>';
			
			$userinfos=retrieve_user_infos($_GET['iduser']);
			$useraddinfos=retrieve_user_add_infos($_GET['iduser']);
			
			if(belongsToUserGroups($userid, $_GET['iduser'])){ // affiche les recettes d'un contact
				$query = sprintf("SELECT * FROM recipes WHERE id_user='%s' ", mysql_real_escape_string($_GET['iduser']));
			}else{ // affiche les recettes d'un inconnu
				$query = sprintf("SELECT * FROM recipes WHERE id_user='%s' AND (approval='0' OR approval='2') ", mysql_real_escape_string($_GET['iduser']));
			}
			$result = mysql_query($query);
			$toto = mysql_num_rows($result);

			if ($toto==0){
				$html.="<h3>Your friend has no recipe</h3>";
			}else{
				$html.= "<h3>$userinfos[firstname] $userinfos[surname] ($userinfos[username]) 's Recipes:</h3>";
				while($row3=mysql_fetch_assoc($result)) {
					$query2 = "SELECT * FROM recipe_photos WHERE id_recipe=$row3[id]";
					$result2 = mysql_query($query2);
					if(!$result2 || mysql_num_rows($result2) < 1){
						$row2['path_source'] = NO_IMAGE;
					}else{
						$row2=mysql_fetch_assoc($result2);
						if(!file_exists($row2['path_source'])){
							$row2['path_source'] = NO_IMAGE;
						}
					}

					//$html.="<div><a href='./recipe.php?id=$row3[id]&iduser=$_GET[iduser]'><img src='$row2[path_source]' 	width='250px' height='220px' alt='$row3[name_en]' title='$row3[name_en]'/><br/>$row3[name_en]</a></div>";
					$html.= '<div><a href="recipe.php?id='.$row3['id'].'&iduser='.$_GET['iduser'].'" >'.printRecipeThumbnail($row3['id']).' '.$row3['name_en'].'</a><br><br></div>';
				}
			}
		}elseif(isset($_GET['mode']) && $_GET['mode'] == "public_recipes" ){
			$html.= '<a href="'.$_SERVER['PHP_SELF'].'" >Return to Recipe main page</a>';
			$html.= printPublicRecipes();
			
		}elseif(isset($_GET['mode']) && $_GET['mode'] == "pending_public_recipes"){
			$html.= '<a href="'.$_SERVER['PHP_SELF'].'" >Return to Recipe main page</a>';
			$html.= printPendingPublicRecipes();
		}else{ // afficher ses recettes, les dernières recettes publiques

			$userinfos=retrieve_user_infos($userid);
			$useraddinfos=retrieve_user_add_infos($userid);

			$html.= '<h1>Recipes</h1>';
			$html.= '<div class="navlinks">
						<a href="recipes.php?mode=new_recipe">Add Recipe</a>
						<a href="recipes.php?mode=new_ingredient">Add Ingredients</a>
					</div>'; // liens pour ajouter des ingrédients ou recettes
			
			$html.= '<div class="navlinks">
						<a href="recipes.php?mode=public_recipes">All Recipes</a>
						<a href="recipes.php?mode=pending_public_recipes">Vote for recipes!</a>
					</div>'; // liens pour afficher toutes les recettes publiques
			
			$html.= printMyRecipes().'<hr>';
			$html.= printLastestRecipesOfContacts().'<hr>';
			$html.= printLastestPublicRecipes().'<hr>';
		}
		
		printDocument('Recipes');
	  
	}else{
		header('Location: index.php');
	}
}
?>

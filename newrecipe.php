<?php

include './header.php';

//fonction pour inserer un ingredient dans la bdd
function insertIngredient($name) {
	$query = sprintf("INSERT INTO ingredients(name_en) VALUES('%s');",
		mysql_real_escape_string(strip_tags($name)));
	$result = @mysql_query($query);

	if ($result) {
		return $result;
	} else {
		die('Error: '.mysql_error());
	}
}



//fonction pour recuperer le nom de l'ingredient
function getnameIngredient($name) {
	$query = sprintf("SELECT name_en FROM ingredients WHERE name_en='%s'",
		mysql_real_escape_string($name));
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
	$query = sprintf("SELECT id FROM ingredients WHERE name_en='%s'",
		mysql_real_escape_string($name));
	$result = mysql_query($query);

	if (!$result) {
		//si l'ingredient n'existe pas on l'ajoute dans la table ingredient
		return false;
	} else while ($row = mysql_fetch_assoc($result)) {
		// ??????????????????????????????????????
		// return dans un while ?
		return $row['id'];
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
function redirect() {
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



function insertRecipe($name, $description, $country, $difficulty, $serves, $preparation, $cook, $instructions, $approval, $id_user) {
	$query = sprintf("INSERT INTO recipes(name_en, description_en, country_origin, difficulty, num_serves, duration_preparation, duration_cook, preparation_en, approval, id_user) VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');",
		mysql_real_escape_string(strip_tags($name)),
		mysql_real_escape_string(strip_tags($description)),
		mysql_real_escape_string(strip_tags($country)),
		mysql_real_escape_string(strip_tags($difficulty)),
		mysql_real_escape_string(strip_tags($serves)),
		mysql_real_escape_string(strip_tags($preparation)),
		mysql_real_escape_string(strip_tags($cook)),
		mysql_real_escape_string(strip_tags($instructions)),
		mysql_real_escape_string(strip_tags($approval)),
		mysql_real_escape_string(strip_tags($id_user)));
	$result = @mysql_query($query);

	if ($result) {
		return $result;
	} else {
		die('Error: '.mysql_error());
	}
}



/*********************************************************************/

// vérification si logué ou pas
if (!isset($userid)) {
	header('Location: index.php');
	exit;
}

/*********************************************************************/



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
		redirect();
		// On a exit.
	}

	$query1 = mysql_query("SELECT * FROM country WHERE name_en='$_POST[country]'");
	$res2   = mysql_fetch_assoc($query1);

	$reussi = insertRecipe(
		$_POST['name'],
		$_POST['description'],
		$res2['id_country'],
		$_POST['difficulty'],
		$_POST['serves'],
		$_POST['prepDuration'],
		$_POST['cookDuration'],
		$_POST['method'],
		$_POST['permission'],
		$userid
	);

	// On récupère l'ID de la recette.
	$getid_recipe = mysql_insert_id();

	// On renomme et déplace l'image correctement.
	// FIX IT
	if ((isset($_FILES['picture']) && ($_FILES['picture']['error'] == UPLOAD_ERR_OK))) {
		//On fait un tableau contenant les extensions autorisées.
		$extensionsOk = array('.PNG', '.GIF', '.JPG', '.JPEG', '.png', '.gif', '.jpg', '.jpeg');

		// On récupère l'extension, donc à partir de ce qu'il y a après le '.'
		$extension = strrchr($_FILES['picture']['name'], '.');

		//Test si l'extension n'est pas dans le tableau
		if (!in_array($extension, $extensionsOk)) {
			echo 'You must upload a file type png, gif, jpg, jpeg';
		} else {
			// vérification de la taille de l'image
			$destination = './img/recipes/';

			// si il y a une image avec le même, le nom est changé grâce à rand(). Cela évite que l'image soit écrasée.
			while (file_exists($destination.$_FILES['picture']['name'])) {
				$_FILES['picture']['name'] = rand().$_FILES['picture']['name'];
			}

			// transfère de l'image du répertoire temporaire vers le dossier avatar
			move_uploaded_file($_FILES['picture']['tmp_name'], './img/recipes/'.$userid._.$getid_recipe.$extension);

			// met l'image uploadée en profil
			$image = './img/recipes/'.$userid._.$getid_recipe.$extension;
			$query = sprintf("INSERT into recipe_photos(id_recipe,path_source) VALUES('%s','%s');",
				mysql_real_escape_string(strip_tags($getid_recipe)),
				mysql_real_escape_string(strip_tags($image)));
			@mysql_query($query);
		}
	}

	// Insertion des ingrédients.
	// FIX IT

	// Il y aurait-il une meilleure manière de vérifier
	// que toute les requettes ont été exécuter correctement?
	if ($reussi && $query) {
		redirect();
	} else {
		$message = "<p class='error'>Please check your recipe again and submit.</p>";
	}
}



$html =
	"<form action='newrecipe.php' method='post' id='contribution' enctype='multipart/form-data'>
		<p>Please define the recipe.</p>
		<label>Name <input type='text' name='name' value='$_POST[name]' required></label>
		<label>Description <textarea name='description'>$_POST[description]</textarea></label>
		<label>Origin <input type='text' name='country' list='countryList' value='$_POST[country]'></label>
		<label>Difficulty
			<select name='difficulty'>";


$result = mysql_query("SELECT id, name_en FROM recipe_difficulty");

while ($rows = mysql_fetch_assoc($result)) {
	$html .= "<option value='$rows[id]'>$rows[name_en]</option>";
}


$html .= "
		</select>
	</label>
	<label>Servings <input type='number' name='serves' value='$_POST[serves]'></label>
	<label>Preparation Duration (min) <input type='number' name='prepDuration' value='$_POST[prepDuration]'></label>
	<label>Cooking Duration (min) <input type='number' name='cookDuration' value='$_POST[cookDuration]'></label>
	<label for='picture'>Picture of the Recipe :</label>
	<input type='file' size='65' name='picture' /></p>";


$i = 1;
while ($_POST[$i]) {
	$ing = getidIngredient($_POST[$i]);

	//insere l'ingredient dans la bdd s'il n'existe pas
	if ($ing == 0) {
		insertIngredient(($_POST[$i]));
		$ing = getidIngredient(($_POST[$i]));
	}

	insertRecipeIng($getid_recipe, $ing);

	$html .= "<label>Ingredient $i<input type='text' name='$i' list='ingredientList' value='".$_POST["$i"]."'></label>";
	$i++;
}


$html .=
		"<a id='more' href='#'>Add ingredient...</a><br>
		<script>
			var i = $i;
			$('#more').on('click', function(e) {
				e.preventDefault();
				$(this).before('<label>Ingredient '+i+'<input type=\"text\" name='+i+' list=\"ingredientList\"></label>');
				$(this).prev().updatePolyfill();
				i++;
			});
		</script>
		<label>Preparation Method <textarea name='method'>$_POST[method]</textarea></label>
		<label>Make this recipe:</label>
		<input type='radio' name='permission' value='0' checked>Public
		<input type='radio' name='permission' value='1'>Private
		<input type='submit' value='Submit'>
	</form>";


//requête pour recupérer les pays
$country = mysql_query("SELECT name_en FROM country");
while ($res = mysql_fetch_array($country)) {
	$list .= "<option value='$res[0]'>";
}
$html .= "<datalist id='countryList'>$list</datalist>";


//requête pour recupérer les ingrédients
$ingredients = mysql_query("SELECT name_en,id FROM ingredients");
while ($ingredient = mysql_fetch_array($ingredients)) {
	$list2 .= "<option value='$ingredient[0]'>$ingredient[0]</option>";
}
$html .= "<datalist id='ingredientList'>$list2</datalist>";


printDocument('New Recipe');

?>

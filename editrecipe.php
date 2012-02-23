<?php

include './header.php';

//mise a jour de la recette
function updateRecipe($name, $description, $country, $difficulty, $serves, $preparation, $cook, $instructions) {
	$query = sprintf("UPDATE recipes SET name_en='$name',description_en='$description',country_origin='$country',difficulty='$difficulty',num_serves='$serves',duration_preparation='$preparation',duration_cook='$cook',preparation_en='$instructions')");
	$res = @mysql_query($query);
	if($res) {
		return $res;
	} else {
		die("Error: ".mysql_error());
	}
}

/******************************************************************************************************/

//recupere les infos sur la recette
function retrieve_recipe_infos($id) {
	// prend en paramètre l'id de l'user, soit $_SESSION['id']
	$sql = 'SELECT name_en, description_en, country_origin, difficulty, num_serves, duration_preparation, duration_cook, preparation_en FROM recipes WHERE id='.$id;
	$query = mysql_query($sql);
	$verif = mysql_num_rows($query);
	if ($verif > 0) {
		return mysql_fetch_assoc($query);
	}
	return false;
}


/******************************************************************************************************/


if (!isset($_SESSION['id'])) {
	// vérification si logué ou pas
	header('Location: index.php');
	die();
}

$i = retrieve_recipe_infos($_GET['id']);


switch ($i['difficulty']) {
	case: 0
		$i['difficulty'] = 'Easy';
	case: 1
		$i['difficulty'] = 'Normal';
	case: 2
		$i['difficulty'] = 'Difficult';
	case: 3
		$i['difficulty'] = 'Lunatic';
}



//selection des ingredients reliées a la recette
$query  = sprintf("SELECT id_ingredient FROM recipe_ingredients WHERE id_recipe='%s'", mysql_real_escape_string($_GET[id]));
$result = mysql_query($query);

while ($row=mysql_fetch_row($result)) {
	$query1      = "SELECT name_en FROM ingredients WHERE id=$row[0]";
	$response    = mysql_query($query1);
	while ($row1 = mysql_fetch_assoc($response)) {
		$html   .= "<li>$row1[name_en]</li>";
	}
}

$html =
	"<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
	<h1 align='center'>$i[name_en]</h1>
	<form action='editrecipe.php' method='post' id='contribution' enctype='multipart/form-data'>
		<p>Edit your recipe.</p>
		<label>Name <input type='text' name='name' value='$i[name_en]' required></label>
		<label>Description <input type='text' name='description' value='$i[description_en]'></label>
		<label>Origin <input type='text' name='country' list='countryList' value='$i[country_origin]'></label>
		<label>Difficulty
			<select name='difficulty' value='$i[difficulty]'>
				<option value='0'".($_POST['difficulty'] === 0 ? " selected" : null).">Easy</option>
				<option value='1'".($_POST['difficulty'] === 1 ? " selected" : null).">Normal</option>
				<option value='2'".($_POST['difficulty'] === 2 ? " selected" : null).">Difficult</option>
				<option value='3'".($_POST['difficulty'] === 3 ? " selected" : null).">Lunatic</option>
			</select>
		</label>
		<label>Serves <input type='number' name='serves' value='$i[num_serves]'></label>
		<label>Preparation Duration (min) <input type='number' name='prepDuration' value='$i[duration_preparation]'></label>
		<label>Cooking Duration (min) <input type='number' name='cookDuration' value='$i[duration_cook]'></label>
		<label for='picture'>Picture of the Recipe :</label>";

$query   = sprintf("SELECT path_source FROM recipe_photos WHERE id_recipe='%s'", mysql_real_escape_string($_GET[id]));
$result2 = mysql_query($query);

if (mysql_num_rows($result2) == 1) {
	$ij    = mysql_fetch_row($result2);
	$html .= "
	<img src='img/recipes/$userinfos[id]_$_GET[id].jpg'>
	<p>Edit this picture: </p><input type='file' size='65' name='picture'>
	<p><strong>Ingredients:</strong></p>";
}

$html .= "
	<ul>
	<script>
		function removeIngredientsOnRecipe(e, id_ing, id_rec) {
			var a, url, x;
			e.preventDefault();
			a = e.target.parentNode;
			a.parentNode.hidden = true;
			url = './removeIngredientsOnRecipe.php?id_ing=' + id_ing + '&id_rec=' + id_rec;
			x = new XMLHttpRequest();
			x.open('GET', url, true);
			x.onload = function(e) {
				a.innerHTML = this.responseText;
				if(this.responseText !== 'success') {
					a.innerHTML = this.responseText;
					a.parentNode.hidden = false;
				}
			};
			x.send();
		}
	</script>";

//selection des ingredients reliees a la recette
$query  = sprintf("SELECT id_ingredient FROM recipe_ingredients WHERE id_recipe='%s'", mysql_real_escape_string($_GET[id]));
$result = mysql_query($query);
$tmp = 1;
while ($row=mysql_fetch_row($result)) {
	$query1   = "SELECT name_en FROM ingredients WHERE id=$row[0]";
	$response = mysql_query($query1);
	while ($row1 = mysql_fetch_assoc($response)) {
		$html.="
		<li>$row1[name_en] <a id='removeing' onclick='removeIngredientsOnRecipe(event, $row[0], $_GET[id])' href='#'><img src='./img/templates/deleteing.png' width='10px' height='10px'/></a></li>";
		$tmp++;
	}
}

$j = $tmp;

$html .= "
	</ul>
	<a id='more' href='#'>Add ingredient...</a><br>
	<script>
		var i = $j;
		$('#more').on('click', function(e) {
			e.preventDefault();
			$(this).before('<label>Ingredient '+i+'<input type=\"text\" name='+i+' list=\"ingredientList\"></label>');
			$(this).prev().updatePolyfill();
			i++;
		});
	</script>
	<label>Preparation Method <textarea name='method'>$i[preparation_en]</textarea></label>
	<input type='submit' value='Submit'>
	</form>";

$ingredients = mysql_query("SELECT name_en,id FROM ingredients");
while ($ingredient = mysql_fetch_array($ingredients)) {
	$list2 .= "<option value='$ingredient[0]'>$ingredient[0]</option>";
}
$html .= "<datalist id='ingredientList'>$list2</datalist>";

if ((isset($_FILES['picture']) && ($_FILES['picture']['error'] == UPLOAD_ERR_OK))) {
	//On fait un tableau contenant les extensions autorisées.
	$extensionsOk = array('.PNG', '.GIF', '.JPG', '.JPEG', '.png', '.gif', '.jpg', '.jpeg');

	// On récupère l'extension, donc à partir de ce qu'il y a après le '.'
	$extension = strrchr($_FILES['picture']['name'], '.');

	//teste
	if (!in_array($extension, $extensionsOk)) {
		//Si l'extension n'est pas dans le tableau
		echo 'You must upload a file type png, gif, jpg, jpeg';
	} else if (filesize($_FILES['picture']['name']>10)) {
		// vérification de la taille de l'image
		echo 'File too large.';
	} else {
		$destination = './img/recipes/';

		// si il y a une image avec le même, le nom est changé grâce à rand(). Cela évite que l'image soit écrasée.
		while(file_exists($destination.$_FILES['picture']['name'])) {
			$_FILES['picture']['name'] = rand().$_FILES['picture']['name'];
		}

		// transfère de l'image du répertoire temporaire vers le dossier avatar
		move_uploaded_file($_FILES['picture']['tmp_name'], './img/recipes/'.$userid._.$getid_recipe.$extension);

		// met l'image uploadée en profil
		$image = './img/recipes/'.$userid._.$getid_recipe.$extension;
		$query = sprintf("INSERT into recipe_photos(id_recipe,path_source) VALUES('%s','%s');",
		mysql_real_escape_string(strip_tags($getid_recipe)),
		mysql_real_escape_string(strip_tags($image)));
		$res = @mysql_query($query);
	}
}

printDocument('Edit this Recipes');

?>

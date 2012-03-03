<?php

/************************Global Function*************************/
function QuerybyTableandId($table,$id){
	$sql = "SELECT * from $table WHERE id='$id'";
	$result = mysql_query($sql);
	$verif = mysql_num_rows($result);
		
		if ($verif>0){
			return $row = mysql_fetch_assoc($result);
		}

		else return false;
}

function DeleteOnTable($table,$id){
	$sql = "DELETE from $table WHERE id='$id'";
	$res = @mysql_query($sql);
	if(!$res)
		die("Error: ".mysql_error());
	else
		return $res;
}


function GetCountryById($id){
	$sql = "SELECT * from country WHERE id_country='$id'";
	$result = mysql_query($sql);
	$verif = mysql_num_rows($result);
		
		if ($verif>0){
			return $row = mysql_fetch_assoc($result);
		}
		else return false;
}

function GetIdCountryByName($name){
	$sql = "SELECT id_country from country WHERE name_en='$name'";
	$result = mysql_query($sql);
	$verif = mysql_num_rows($result);
		
		if ($verif>0){
			return $row = mysql_fetch_assoc($result);
		}
		else return false;
}

//recupere une datalist de pays pour l'autocompletion
function printCountrydatalist(){
	$html='';
	$sql = "SELECT id_country,name_en FROM country";
	$country = mysql_query($sql);
	$list='';// ne pas oublier d'initialiser
  		while($res = mysql_fetch_assoc($country)) {
			$list.= "<option value='$res[name_en]' >";
  			}
			$html.= "<datalist id='countryList'>$list</datalist>";

	return $html;	
}

//recupere une datalist d'ingredients pour l'autocompletion
function printIngdatalist(){
	$html='';
	$ingredients = mysql_query("SELECT name_en,id FROM ingredients");
	while ($ingredient = mysql_fetch_array($ingredients)) {
	$list2 .= "<option value='$ingredient[0]'>$ingredient[0]</option>";
	}
	$html.= "<datalist id='ingredientList'>$list2</datalist>";
	return $html;	
}

/************************End Global Function*************************/

/**************************Functions RECIPES************************/

function RecipeInfosOnTable($table,$id){
	$sql = "SELECT * from $table WHERE id_recipe='$id'";
	$result = mysql_query($sql);
	$verif = mysql_num_rows($result);
		if ($verif>0){
			return $row = mysql_fetch_assoc($result);
		}
		else return false;
}

function printRecipeImage($id){
	$recipe = RecipeInfosOnTable('recipe_photos',$id);
	$html = "<img src='../$recipe[path_source]' width='200px' height='175px'>";
	
	return $html;
}

function getDifficultyNameById($id){
	$sql = "SELECT * from recipe_difficulty WHERE id='$id'";
	$result = mysql_query($sql);
	$verif = mysql_num_rows($result);
		
		if ($verif>0){
			return $row = mysql_fetch_assoc($result);
		}
		else return false;
}


function printIngredientsByIdRecipe($id){
	
	//selection des ingredients reliees a la recette
	$query = sprintf("SELECT id_ingredient FROM recipe_ingredients WHERE id_recipe='%s'",
	mysql_real_escape_string($id)); 	
	$result = mysql_query($query);	
	
	while($row=mysql_fetch_row($result)) {
   	$query1 = "SELECT name_en FROM ingredients WHERE id=$row[0]";
	$response = mysql_query($query1);
	while($row1 = mysql_fetch_assoc($response)){
	$html.="<li>$row1[name_en]<a id='removeing' onclick='removeIngredientsOnRecipe(event,$row[0],$id)' href='#'><img src='../img/templates/deleteing.png' width='10px' height='10px'/></a></li>";	
	}	
   }
   
   return $html;
}

function CountIngredients($id){
	$sql="SELECT * from recipe_ingredients WHERE id_recipe='$id'";
	$result = mysql_query($sql);
	$verif = mysql_num_rows($result);
	
	return $verif;
}


function IngredientAdd($j){
	return "
	<a id='more' href='#'>Add ingredient...</a><br>
		<script>
			var i = $j+1;
			$('#more').on('click', function(e) {
				e.preventDefault();
				$(this).before('<label>Ingredient '+i+'<input type=\"text\" name='+i+' list=\"ingredientList\"></label>');
				$(this).prev().updatePolyfill();
				i++;
			});
		</script>

	";

}

//formulaire de la recette choisie
function printFormRecipe($id){
	$recipe = QuerybyTableandId('recipes',$id);
	$country = GetCountryById($recipe['country_origin']);
	
	$html = "
	<form action='./index.php?mode=recipes' id=contribution method='post'>
	<p>Delete or update its details.</p>
	<div>Name: <input type='text' name='name' value='$recipe[name_en]' required></div>
	<div>Description: <input type='text' name='description' value='$recipe[description_en]'></div>
	<div>Origin: <input type='text' name='origin' list='countryList' value='$country[name_en]'></div>
	<div>Difficulty: 
	<select name='difficulty'>";
	
	$result = mysql_query("SELECT id, name_en FROM recipe_difficulty");

	while ($rows = mysql_fetch_assoc($result)) {
		$html.= "<option value='$rows[id]'>$rows[name_en]</option>";
		}

	$html.= "</select><p>Image of the recipe: </p>";
	
	//affiche l'image de la recette
	$html.= printRecipeImage($id);
	
	$html.="<div>Edit this picture :</div>
			<input type='file' size='65' name='picture'></p>";	
	
	$html.="<strong>Ingredients</strong>:<ul>";
	$html.= printIngredientsByIdRecipe($id);
	
	$var = CountIngredients($id);
	
	$html.= IngredientAdd($var);
	
	$html.= " 
	</ul><div>Serves: <input type='text' name='serves' value='$recipe[num_serves]' required></div>
	<div>Preparation: <input type='text' name='preparation' value='$recipe[duration_preparation]' required></div>
	<div>Cook: <input type='text' name='cook' value='$recipe[duration_cook]' required></div>
	<div>Method:<textarea name='method'>$recipe[preparation_en]</textarea></div>
	<div>
		<button type='submit' name='update' value='$recipe[id]'>Update</button>
		<button type='submit' name='delete' value='$recipe[id]'>Delete</button>
	</div>
	</form>";
	
	
	$html.= printCountrydatalist();
	$html.= printIngdatalist();
	
	return $html;

}


//fonction pour recuperer les infos de l'ingredient
function getidIngredient($name){
	$query = "SELECT * FROM ingredients WHERE name_en='$name'";
	$result = mysql_query($query);
	$verif = mysql_num_rows($result);
	if ($verif==0) {
		return false;
		}  
	return mysql_fetch_assoc($result);
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





//mise a jour de la recette
 function updateRecipe($id){
	
	if ($_POST) { // si le user submit
	$i = CountIngredients($id);
	$i = $i+1;
		while ($_POST[$i]) {
			$ing = getidIngredient($_POST[$i]);
				if($ing){
				$sql = insertRecipeIng($id,$ing['id']);
				$i++;
				}
				else{
				$sql = insertIngredient($_POST[$i]);
				$sql2 = getidIngredient($_POST[$i]);
				$sql3 = insertRecipeIng($id,$sql2['id']);
				$i++;
				}
					
		}

	}
	
	if($_POST['origin']==NULL) {
	$query = "UPDATE recipes SET name_en='$_POST[name]',description_en='$_POST[description]',country_origin=NULL,difficulty='$_POST[difficulty]',num_serves='$_POST[serves]',duration_preparation='$_POST[preparation]',duration_cook='$_POST[cook]',preparation_en='$_POST[instructions]' WHERE id='$id'";
	}
	
		else{
			$country = GetIdCountryByName($_POST['origin']);
			$query = "UPDATE recipes SET name_en='$_POST[name]',description_en='$_POST[description]',country_origin='$country[id_country]',difficulty='$_POST[difficulty]',num_serves='$_POST[serves]',duration_preparation='$_POST[preparation]',duration_cook='$_POST[cook]',preparation_en='$_POST[method]' WHERE id='$id'";
		}
		
        $res = @mysql_query($query);

		if(!$res)
			die("Error: ".mysql_error());
		else
			return $res;
}


// Fonction qui update une nouvelle image de recette dans la bdd
function updateRecipePhoto($idRecipe, $newimage){
$query = sprintf("UPDATE recipe_photos SET path_source='$newimage' WHERE id_recipe='$idRecipe'" );
        $res = @mysql_query($query);

		if(!$res)
			die("Error: ".mysql_error());
		else
			return $res;

}

			

/**************************END Functions RECIPES************************/

/**************************Functions USERS************************/

function printFormCustomer($id){
	$customer = QuerybyTableandId('users',$id);
	$country = GetCountryById($customer['country']);
	
	$html = "
	<form action='./index.php?mode=customers' id='contribution' method='post'>
	<p>Delete or update its details.</p>
	<div>Firstname: <input type='text' name='firstname' value='$customer[firstname]' required></div>
	<div>Surname: <input type='text' name='surname' value='$customer[surname]' required></div>
	<div>Username: <input type='text' name='username' value='$customer[username]' required></div>
	<div>Address: <input type='text' name='address' value='$customer[address]'></div>
	<div>Country: <input type='text' name='country' list='countryList' value='$country[name_en]'></div>
	<div>Email: <input type='text' name='mail' value='$customer[mail]' required></div>
	<div>
		<button type='submit' name='update' value='$customer[id]'>Update</button>
		<button type='submit' name='delete' value='$customer[id]'>Delete</button>
	</div>
	</form>";
	
	$html.= printCountrydatalist();
	
	return $html;
}

//fonction update users
function updateUser($id){
		//si le champs country est vide
		if($_POST['country']==NULL) {
				$query = "UPDATE users SET firstname='$_POST[firstname]', surname='$_POST[surname]', address='$_POST[address]',country=NULL, username='$_POST[username]', mail='$_POST[mail]' WHERE id=$id";
		}
	
		else{
				$country = GetIdCountryByName($_POST['country']);
				$query = "UPDATE users SET firstname='$_POST[firstname]', surname='$_POST[surname]', address='$_POST[address]',country='$country[id_country]', username='$_POST[username]', mail='$_POST[mail]' WHERE id=$id";	
			}
		
	$res = @mysql_query($query);
	if(!$res)
		die("Error: ".mysql_error());
	else
		return $res;
}

/***************************END Functions USERS************************/

/**************************Functions INGREDIENT************************/

function printFormIngredient($id){
	$ing = QuerybyTableandId('ingredients',$id);
	
	$html = "
	<form action='./index.php?mode=ingredients' id='contribution' method='post'>
	<p>Delete or update its details.</p>
	<div>Name Fr: <input type='text' name='name_fr' value='$ing[name_fr]'></div>
	<div>Description Fr: <input type='text' name='description_fr' value='$ing[description_fr]'></div>
	<div>Name En: <input type='text' name='name_en' value='$ing[name_en]'></div>
	<div>Description En: <input type='text' name='description_en' value='$ing[description_en]'></div>
	<div>Approval: <input type='text' name='approval' value='$ing[approval]'></div>
	<div>
		<button type='submit' name='update' value='$ing[id]'>Update</button>
		<button type='submit' name='delete' value='$ing[id]'>Delete</button>
	</div>
	</form>";
	
	return $html;
}

function updateIngredient($id){

$query = "UPDATE ingredients SET name_fr='$_POST[name_fr]', description_fr='$_POST[description_fr]', name_en='$_POST[name_en]',description_en='$_POST[description_en]', approval='$_POST[approval]' WHERE id='$id' ";

		$res = @mysql_query($query);
	if(!$res)
		die("Error: ".mysql_error());
	else
		return $res;
}

/**************************END INGREDIENT************************/

function printFooter() {
		echo "</body></html>";
	}



?>
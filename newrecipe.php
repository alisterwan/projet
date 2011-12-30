<?php
  include './header.php';

  //fonction pour inserer un ingredient dans la bdd
  function insertIngredient($name){
   $query2 = sprintf("INSERT INTO ingredients(name_en) VALUES('%s');",
   mysql_real_escape_string(strip_tags($name)));
   $res = @mysql_query($query2);
   if(!$res)
  die("Error: ".mysql_error());
  else
  return $res;
  }


  //fonction pour recuperer le nom de l'ingredient
  function getnameIngredient($name){
  $query = sprintf("SELECT name_en FROM ingredients
    WHERE name_en='%s'",
    mysql_real_escape_string($name));

   $result = mysql_query($query);

   if ($result == 0) {
   return false;
   }

   else
   while ($row = mysql_fetch_assoc($result)) {
   return $row['name_en'];
   }

   mysql_free_result($result);
}



  //fonction pour recuperer le id de l'ingredient
  function getidIngredient($name){
  $query = sprintf("SELECT id FROM ingredients
    WHERE name_en='%s'",
    mysql_real_escape_string($name));
    $result = mysql_query($query);
    
   if (!$result) {
   //si l'ingredient n'existe pas on l'ajoute dans la table ingredient
   return false;
   }

   else
   while ($row = mysql_fetch_assoc($result)) {
   return $row['id'];
   }

   mysql_free_result($result);
}




  // Fonction qui insere un new recipe dans la bdd
  function insertRecipeIng($idRecipe,$idIngredient){
    $query = sprintf("INSERT INTO recipe_ingredients(id_recipe,id_ingredient) VALUES('%s','%s');",
    mysql_real_escape_string(strip_tags($idRecipe)),
    mysql_real_escape_string(strip_tags($idIngredient)));
    $res = @mysql_query($query);
    if(!$res)
      die("Error: ".mysql_error());
    else
      return $res;
}

  //fonction de redirection vers la page de recette crée
  function redirect() {
    $query = mysql_fetch_row(mysql_query(
      sprintf("SELECT id FROM recipes WHERE name_en LIKE '%s'",
        mysql_real_escape_string(strip_tags($_POST['name'])))
    ));
    $id = $query[0];
    header("Location: recipe.php?id=$id");
    exit;
  }
  
  
 function insertRecipe($name,$description,$country,$difficulty,$serves,$preparation,$cook,$instructions,$id_user){
	$query = sprintf("INSERT INTO recipes(name_en,description_en,country_origin,difficulty,num_serves,duration_preparation,duration_cook,preparation_en,id_user) VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s');",
  	 mysql_real_escape_string(strip_tags($name)),
        mysql_real_escape_string(strip_tags($description)),
        mysql_real_escape_string(strip_tags($country)),
        mysql_real_escape_string(strip_tags($difficulty)),
        mysql_real_escape_string(strip_tags($serves)),
        mysql_real_escape_string(strip_tags($preparation)),
        mysql_real_escape_string(strip_tags($cook)),
        mysql_real_escape_string(strip_tags($instructions)),
  	 mysql_real_escape_string(strip_tags($id_user)));
        $res = @mysql_query($query);

		if(!$res)
			die("Error: ".mysql_error());
		else
			return $res;
	}
 
 
/*********************************************************************/

if (isset($userid)){  // vÈrification si logué ou pas


  $userinfos=retrieve_user_infos($userid);

  if($_POST) {
    $query = mysql_num_rows(mysql_query(
      sprintf("SELECT id FROM recipes WHERE name_en LIKE '%s'",
        mysql_real_escape_string(strip_tags($_POST['name'])))
    ));
    if ($query) {
      redirect();
    } else {

insertRecipe($_POST['name'],$_POST['description'],$_POST['country'],$_POST['difficulty'],$_POST['serves'],$_POST['prepDuration'],$_POST['cookDuration'],$_POST['method'],$userid);

   //on recupere le id de la recette
   $getid_recipe = mysql_insert_id();

      /*
       *insérer les ingrédients
       */

     

      if(!$query) {
        $message = "<p class='error'>Connection error.</p>";
      } else {
        redirect();
      }
    }
  }

  $html =
  "<form action='newrecipe.php' method='post' id='contribution' enctype='multipart/form-data'>
    <p>Please define the recipe.</p>
    <label>Name <input type='text' name='name' value='$_POST[name]' required></label>
    <label>Description <input type='text' name='description' value='$_POST[description]'></label>
    <label>Origin <input type='text' name='country' list='countryList' value='$_POST[country]'></label>
    <label>Difficulty
      <select name='difficulty'>
        <option value='0'".($_POST['difficulty'] === 0 ? " selected" : null).">Easy</option>
        <option value='1'".($_POST['difficulty'] === 1 ? " selected" : null).">Normal</option>
        <option value='2'".($_POST['difficulty'] === 2 ? " selected" : null).">Difficult</option>
        <option value='3'".($_POST['difficulty'] === 3 ? " selected" : null).">Lunatic</option>
      </select>
    </label>
    <label>Serves <input type='number' name='serves' value='$_POST[serves]'></label>
    <label>Preparation Duration (min) <input type='number' name='prepDuration' value='$_POST[prepDuration]'></label>
    <label>Cooking Duration (min) <input type='number' name='cookDuration' value='$_POST[cookDuration]'></label>
    <label for='picture'>Picture of the Recipe :</label>
    <input type='file' size='65' name='picture' /></p>
    ";

  $i = 1;
  while($_POST[$i]) {
      $ing = getidIngredient($_POST[$i]);
      //insere l'ingredient dans la bdd s'il n'existe pas
      if($ing == 0){
      insertIngredient(($_POST[$i]));
      $ing = getidIngredient(($_POST[$i]));
      $res3 = insertRecipeIng($getid_recipe,$ing);
      }
      else
      $res3 = insertRecipeIng($getid_recipe,$ing); 
    
    $html .= "<label>Ingredient $i<input type='text' name='$i' list='ingredientList' value='".$_POST["$i"]."'></label>";
    $i++;
  }

  $html .=
    "<a id='more' href='#'>Add ingredient...</a><br>
    <script>
      var i = $i;
      $('#more').on('click', function(e) {
        e.preventDefault();
<<<<<<< HEAD
        $(this).before('<label>Ingredient '+i+'<input type=\"text\" name='+i+' list=\"ingredientList\"></label>');
=======
        $(this).before('<label>Ingredient '+i+'<input type=\"text\" name=\"ing'+i+'\" list=\"ingredientList\"></label>');
>>>>>>> d685b7ed013b13495b3dbca432cb2d5c2724090d
        $(this).prev().updatePolyfill();
        i++;
      });
    </script>
    <label>Preparation Method <textarea name='method'>$_POST[method]</textarea></label>
    <input type='submit' value='Submit'>
  </form>";

  //requete pour recuperer les pays
  $country = mysql_query("SELECT name_en FROM country");
  while($res = mysql_fetch_array($country)) {
  $list .= "<option value='$res[0]'>";
  }
  $html .= "<datalist id='countryList'>$list</datalist>";

  $ingredients = mysql_query("SELECT name_en,id FROM ingredients");
  while($ingredient = mysql_fetch_array($ingredients)) {
    $list2 .= "<option value='$ingredient[0]'>$ingredient[0]</option>";
  }
  $html .= "<datalist id='ingredientList'>$list2</datalist>";

if( ( isset($_FILES['picture']) && ($_FILES['picture']['error'] == UPLOAD_ERR_OK) ) ){    

	//On fait un tableau contenant les extensions autorisées.
	$extensionsOk = array('.PNG', '.GIF', '.JPG', '.JPEG', '.png', '.gif', '.jpg', '.jpeg');
	
	// On récupère l'extension, donc à partir de ce qu'il y a après le '.'
	$extension = strrchr($_FILES['picture']['name'], '.');
	
	//teste
	if(!in_array($extension, $extensionsOk)) //Si l'extension n'est pas dans le tableau
	{
		echo 'You must upload a file type png, gif, jpg, jpeg';
	}

	else{
	
			// vérification de la taille de l'image
			if( filesize($_FILES['picture']['name']>10) ){

			echo 'File too large.';
			
			}
		
		
		
			else{
			
			
				$destination = './img/recipes/';

				// si il y a une image avec le même, le nom est changé grâce à rand(). Cela évite que l'image soit écrasée.
				while(file_exists($destination.$_FILES['picture']['name'])) {
					$_FILES['picture']['name'] = rand().$_FILES['picture']['name'];
				}
    
				// transfère de l'image du répertoire temporaire vers le dossier avatar	
				move_uploaded_file($_FILES['picture']['tmp_name'], './img/recipes/'.$userid._.$getid_recipe);    
   
	
				// met l'image uploadée en profil	
				$image = './img/recipes/'.$userid._.$getid_recipe;
				$query = sprintf("INSERT into recipe_photos(id_recipe,path_source) VALUES('%s','%s');", 
				mysql_real_escape_string(strip_tags($getid_recipe)),
				mysql_real_escape_string(strip_tags($image)));
				$res = @mysql_query($query);
	
		}
	
	}

	
}


  printDocument();

} else {

  header('Location: index.php');
}
?>

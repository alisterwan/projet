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
 
 
  
  
  // Fonction qui insere un new user dans la bdd
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

  function redirect() {
    $query = mysql_fetch_row(mysql_query(
      sprintf("SELECT id FROM recipes WHERE name_en LIKE '%s'",
        mysql_real_escape_string(strip_tags($_POST['name'])))
    ));
    $id = $query[0];
    header("Location: recipe.php?id=$id");
    exit;
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
	        
	    
      $query = sprintf("INSERT INTO recipes(name_en,description_en,country_origin,difficulty,num_serves,duration_preparation,duration_cook,preparation_en,id_user) VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s');",
	 mysql_real_escape_string(strip_tags($_POST['name'])),
        mysql_real_escape_string(strip_tags($_POST['description'])),
        mysql_real_escape_string(strip_tags($_POST['country'])),
        mysql_real_escape_string(strip_tags($_POST['difficulty'])),
        mysql_real_escape_string(strip_tags($_POST['serves'])),
        mysql_real_escape_string(strip_tags($_POST['prepDuration'])),
        mysql_real_escape_string(strip_tags($_POST['cookDuration'])),
        mysql_real_escape_string(strip_tags($_POST['method'])),
	 mysql_real_escape_string(strip_tags($userid)));
        $response = @mysql_query($query);
	 
	 //on recupere le id de la recette
	 $getid_recipe = mysql_insert_id();


      /*
       * quel méthode pour insérer les ingrédients?
       */
	
	//si les champs ingredients on été remplis
	
      if (($_POST[ing1])){ 
      $ing1 = getidIngredient(($_POST[ing1]));
       if($ing1 == 0){
	    insertIngredient(($_POST[ing1]));
	    $ing1 = getidIngredient(($_POST[ing1]));
	    $res2 = insertRecipeIng($getid_recipe,$ing1);
	    }
      else 
      $res2 = insertRecipeIng($getid_recipe,$ing1);
      }
      
      if (($_POST[ing2])){
      $ing2 = getidIngredient($_POST[ing2]);
      if($ing2 == 0){
	    insertIngredient(($_POST[ing2]));
	    $ing2 = getidIngredient(($_POST[ing2]));
	    $res3 = insertRecipeIng($getid_recipe,$ing2);
	    }
      else 
      $res3 = insertRecipeIng($getid_recipe,$ing2);
      }
      
      if (($_POST[ing3])){
      $ing3 = getidIngredient($_POST[ing3]);
       if($ing3 == 0){
	    insertIngredient(($_POST[ing3]));
	    $ing3 = getidIngredient(($_POST[ing3]));
	    $res4 = insertRecipeIng($getid_recipe,$ing3);
	    }
      else 
      $res4 = insertRecipeIng($getid_recipe,$ing3);
      }
      
      if (($_POST[ing4])){
      $ing4 = getidIngredient($_POST[ing4]);
       if($ing4 == 0){
	    insertIngredient(($_POST[ing4]));
	    $ing4 = getidIngredient(($_POST[ing4]));
	    $res5 = insertRecipeIng($getid_recipe,$ing4);
	    }
      else 
      $res5 = insertRecipeIng($getid_recipe,$ing4);
      }
      
      if (($_POST[ing5])){
      $ing5 = getidIngredient($_POST[ing5]);
       if($ing5 == 0){
	    insertIngredient(($_POST[ing5]));
	    $ing5 = getidIngredient(($_POST[ing5]));
	    $res6 = insertRecipeIng($getid_recipe,$ing5);
	    }
      else 
      $res6 = insertRecipeIng($getid_recipe,$ing5);
      }

      
      

      if(!$query) {
        $message = "<p class='error'>Connection error.</p>";
      } else {
        redirect();
      }
    }
  }

  //il faudrait peut-être le faire par étape
  $html =
    "
  <form action='newrecipe.php' method='post' id='contribution'>
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
    <label>Ingredient 1<input type='text' name='ing1' list='ingredientList' value='$_POST[ing1]'></label>
    <label>Ingredient 2<input type='text' name='ing2' list='ingredientList' value='$_POST[ing2]'></label>
    <label>Ingredient 3<input type='text' name='ing3' list='ingredientList' value='$_POST[ing3]'></label>
    <label>Ingredient 4<input type='text' name='ing4' list='ingredientList' value='$_POST[ing4]'></label>
    <label>Ingredient 5<input type='text' name='ing5' list='ingredientList' value='$_POST[ing5]'></label>
    <label>Preparation Method <textarea name='method'>$_POST[method]</textarea></label>
    <input type='submit' value='Submit'>
  </form>
    ";

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
  

  printDocument();
  
}else{
	
	header('Location: index.php');
}
?>


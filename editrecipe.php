<?php
  include './header.php';


 function updateRecipe($name,$description,$country,$difficulty,$serves,$preparation,$cook,$instructions){
	$query = sprintf("UPDATE recipes SET name_en='$name',description_en='$description',country_origin='$country',difficulty='$difficulty',num_serves='$serves',duration_preparation='$preparation',duration_cook='$cook',preparation_en='$instructions')");
        $res = @mysql_query($query);

		if(!$res)
			die("Error: ".mysql_error());
		else
			return $res;
	}
	

function retrieve_recipe_infos($id){ // prend en paramètre l'id de l'user, soit $_SESSION['id']
	$sql='SELECT name_en,description_en,country_origin,difficulty,num_serves,duration_preparation,duration_cook,preparation_en FROM recipes WHERE id='.$id;
	$query=mysql_query($sql);
	$verif = mysql_num_rows($query);
	
	if ($verif > 0){
	return $result=mysql_fetch_assoc($query);
	}
	return false;
  }



if (isset($userid)){ // vérification si logué ou pas

  $userinfos=retrieve_user_infos($userid);
  
   $i = retrieve_recipe_infos($_GET[id]);
   
  
   
   if($i[difficulty] == 0){ $i[difficulty] = 'Easy';}
   else 
   if($i[difficulty] == 1){ $i[difficulty] = 'Normal';}
   else
   if($i[difficulty] == 2){ $i[difficulty] = 'Difficult';}
   else 
   if($i[difficulty] == 3){ $i[difficulty] = 'Lunatic';}
  
  $html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
  <h1 align='center'>$i[name_en]</h1>
  
  	<div>
	<strong>Ingredients</strong>:<ul>";
	//selection des ingredients reliees a la recette
	$query = sprintf("SELECT id_ingredient FROM recipe_ingredients WHERE id_recipe='%s'",
	mysql_real_escape_string($_GET[id])); 	
	$result = mysql_query($query);
	
	while($row=mysql_fetch_row($result)) {
   	$query1 = "SELECT name_en FROM ingredients WHERE id=$row[0]";
	$response = mysql_query($query1);
	while($row1 = mysql_fetch_assoc($response)){
	$html.="<li>$row1[name_en]</li>";	
	}	
   }
   
	$query = sprintf("SELECT path_source FROM recipe_photos WHERE id_recipe='%s'",
	mysql_real_escape_string($_GET[id])); 	
	$result2 = mysql_query($query);
	
	if(mysql_num_rows($result2) == 1){
	$ij = mysql_fetch_row($result2);
	 $html.= "<img src='img/recipes/$userinfos[id]_$_GET[id].jpg' />";	
	}
	
	$html =
  "<form action='editrecipe.php' method='post' id='contribution' enctype='multipart/form-data'>
    <p>Edit your recipe.</p>
    <label>Name <input type='text' name='name' value='' required></label>
    <label>Description <input type='text' name='description' value=''></label>
    <label>Origin <input type='text' name='country' list='countryList' value='$_POST[country]'></label>
    <label>Difficulty
      <select name='difficulty'>
        <option value='0'".($_POST['difficulty'] === 0 ? " selected" : null).">Easy</option>
        <option value='1'".($_POST['difficulty'] === 1 ? " selected" : null).">Normal</option>
        <option value='2'".($_POST['difficulty'] === 2 ? " selected" : null).">Difficult</option>
        <option value='3'".($_POST['difficulty'] === 3 ? " selected" : null).">Lunatic</option>
      </select>
    </label>
    <label>Serves <input type='number' name='serves' value=''></label>
    <label>Preparation Duration (min) <input type='number' name='prepDuration' value=''></label>
    <label>Cooking Duration (min) <input type='number' name='cookDuration' value=''></label>
    <label for='picture'>Picture of the Recipe :</label>
    <input type='file' size='65' name='picture' /></p>
    ";


  $html.="
  	</ul>
	</div>
	
	<div>
	<div><strong>Description</strong>: $i[description_en]</div>
	<div><strong>Origin</strong>: $i[country_origin]</div>
	<div><strong>Difficulty</strong>: $i[difficulty]</div>
	<div><strong>Number Serves</strong>: $i[num_serves] </div>
	<div><strong>Preparation</strong>: $i[duration_preparation] minutes</div>
	<div><strong>Cooking</strong>: $i[duration_cook] minutes</div>
	<div><strong>Instructions</strong>: $i[preparation_en]</div>
		

	</div>";

  
 
  

  printDocument('My Recipes');
  
}else{
	
	header('Location: index.php');
}
  
?>

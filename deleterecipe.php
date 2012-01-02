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
  
  
  $html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>";
  

  printDocument('My Recipes');
  
}else{
	
	header('Location: index.php');
}
  
?>

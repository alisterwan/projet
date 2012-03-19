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
}	
  else{
  
  $userinfos=retrieve_user_infos($userid);
  $useraddinfos=retrieve_user_add_infos($userid);
  
  $html = "
<script type='text/javascript'>
			$(document).ready(function () {
			$('ul.menu_body li:even').addClass('alt');
    		$('img.menu_head').click(function () {
			$('ul.menu_body').slideToggle('medium');
    		});
			$('ul.menu_body li a').mouseover(function () {
			$(this).animate({ fontSize: '14px', paddingLeft: '20px' }, 50 );
    		});
			$('ul.menu_body li a').mouseout(function () {
			$(this).animate({ fontSize: '12px', paddingLeft: '10px' }, 50 );
    			});
			});
			</script>

	<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
  	<h3>My Recipes</h3>
	<img src='./img/templates/option.png' width='150' height='40' class='menu_head' />
		<ul class='menu_body'>
    		<li><a href='./newrecipe.php'>Add Recipe</a></li>
    		<li><a href='./newingredient.php'>Add Ingredients</a></li>

		</ul>
		
		

  ";

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

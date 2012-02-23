<?php
	include './header.php';


if (isset($userid)){  // vérification si logué ou pas

  
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

  printDocument('My Recipes');
  
}else{
	
	header('Location: index.php');
}
  
?>

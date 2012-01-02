<?php
	include './header.php';


if (isset($userid)){  // vérification si logué ou pas

  
  $userinfos=retrieve_user_infos($userid);
  $useraddinfos=retrieve_user_add_infos($userid);
  
  $html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
  <h3>My Recipes</h3>
  <h4>Born in $useraddinfos[date_birth], Works at $useraddinfos[job] Listen to $useraddinfos[music]</h4>
  ";

$query = sprintf("SELECT * FROM recipes WHERE id_user='%s'",
	mysql_real_escape_string($userid));	
	$result = mysql_query($query);	
	
	while($row=mysql_fetch_row($result)) {
	$html.="<div><a href='./recipe.php?id=$row[13]'><img src='img/recipes/$row[12]_$row[13].jpg' /><br>$row[0]</a></div>";	
	}	


if (!$query){
	$html.="<h4>You haven't got recipes</h4>
	<div>
	<ul>
	<li><small><a href='./newrecipe.php'>Add a new recipe</a></small></li>
	<li><small><a href='./newingredient.php'>Add a new ingredient</a></small></li>
	<ul>
	</div>
	";
	}
	

	 $html.="
	<div>
	<ul>
	<li><small><a href='./newrecipe.php'>Add a new recipe</a></small></li>
	<li><small><a href='./newingredient.php'>Add a new ingredient</a></small></li>
	<ul>
	</div>
	";		
	



  printDocument('My Recipes');
  
}else{
	
	header('Location: index.php');
}
  
?>

<?php

function getRecipesPhotobyId($idrecipe){
	$query = sprintf("SELECT path_source FROM recipe_photos WHERE id_recipe='%s'", mysql_real_escape_string($idrecipe));
	$result2 = mysql_query($query);

	if(mysql_num_rows($result2) == 1){
		$ij = mysql_fetch_assoc($result2);
		$html = "<img src='$ij[path_source]' width='100px' height='75px' />";
	}
	return $html;
}

function retrieve_recipe_infos($id){ // prend en paramètre l'id de la recette
	$sql="SELECT * FROM recipes WHERE id='$id' AND approval=0";
	$query=mysql_query($sql);
	$verif = mysql_num_rows($query);

	if ($verif > 0){
	return $result=mysql_fetch_assoc($query);
	}
	return false;
  }
/************************************************/

include './header.php';

$html = '<h2>Results</h2>';

if (isset($_GET['recherche']) && $_GET['recherche']) {
	$rec = htmlentities($_GET['recherche']);


	/***********************************************/

	//On recherche une recette
	$req  = 'SELECT * FROM recipes WHERE ';
	$mots = explode(' ',$rec); //En separre lexpression en mots cles
	foreach ($mots as $mot) {
		$req .= ' name_en LIKE "%'.$mot.'%" AND approval="0" OR';
	}
	$req    .= ' 1=0';
	$requete = mysql_query($req);
	
	if(mysql_num_rows($requete)>0){
	$html.= "<h4>Results on recipes:</h4>";
	}
	
	//On affiche les resultats
	while ($dnn = mysql_fetch_array($requete)) {
		//recupere le username
		$query  = "SELECT username FROM users WHERE id='$dnn[id_user]'";
		$result = mysql_query($query);

		$res = mysql_fetch_assoc($result);

		//recupere la photo de la recette
		$query2  = "SELECT path_source FROM recipe_photos WHERE id_recipe=$dnn[id]";
		$result2 = mysql_query($query2);
		$res2    = mysql_fetch_assoc($result2);
		
		$html   .= "
		<div>
			<a href='./searchrecipe.php?id=$dnn[id]&id_user=$dnn[id_user]'>
				<img src='$res2[path_source]' style='height: 150px; width: 150px;'>
				<div>$dnn[name_en] by $res[username]</div>
			</a>
		</div>";
	}

	/***********************************************/
	//si la requete n'est pas une recette on recherche dans les ingredients

	if (mysql_num_rows($requete) == 0) {
		//On recherche un ingredient
		$req = 'SELECT * FROM ingredients WHERE ';

		$mots = explode(' ',$rec);//En separre lexpression en mots cles
		foreach ($mots as $mot) {
			$req .= 'name_en LIKE "%'.$mot.'%" OR name_fr LIKE "%'.$mot.'%" OR description_en LIKE "%'.$mot.'%" OR description_fr LIKE "%'.$mot.'%" OR';
		}

		$req     .= ' 1=0';
		$requete2 = mysql_query($req);
	
		if(mysql_num_rows($requete2)>0){
		$html.= "<h4>Results on Ingredients:</h4>";
		}

		//On recupere l'id de l'ingredient
		while ($dnn = mysql_fetch_array($requete2)) {
			$query3 = "SELECT id_recipe FROM recipe_ingredients WHERE id_ingredient=$dnn[id]";
			$requete3 = mysql_query($query3);
			
			//equina, i also summon god
			while($dnn2 = mysql_fetch_array($requete3)){
				$wawa = retrieve_recipe_infos($dnn2['id_recipe']);
				$user = retrieve_user_infos($wawa['id_user']);
				if($user && $wawa){
				$html.= "<p><div><span>";
				$html.= getRecipesPhotobyId($wawa['id']);
				$html.="</span><span><a href='recipe.php?id=$wawa[id]'>$wawa[name_en]</a> by <a href='profile.php?id_user=$wawa[id_user]'>$user[username]</a></span></div></p>";
				}
			}	
		}
	}
	/***********************************************/
	//si la requete n'est pas une recette on recherche dans les users

	if (mysql_num_rows($requete) == 0) {
		//On recherche un user
		$req = 'SELECT * FROM users WHERE ';

		$mots = explode(' ',$rec);//En separre lexpression en mots cles
		foreach ($mots as $mot) {
			$req .= 'username LIKE "%'.$mot.'%" OR firstname LIKE "%'.$mot.'%" OR surname LIKE "%'.$mot.'%" OR';
		}

		$req     .= ' 1=0';
		$requete2 = mysql_query($req);

		if(mysql_num_rows($requete2)>0){
	$html.= "<h4>Results on users:</h4>";
	}

		//On affiche les resultats
		while ($dnn = mysql_fetch_array($requete2)) {
			$html .= "
			<div>
				<a href='./profile.php?id_user=$dnn[id]'>
					<img src='$dnn[avatar]' style='height: 200px; width: 175px;'>
					<div>$dnn[firstname] $dnn[surname] ($dnn[username])</div>
				</a>
			</div>";
		}
	}

	
} else {
	$html .= "<h2>No Results Found</h2>";
}

printDocument('Search');

?>

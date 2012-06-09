<?php
include './header.php';
/*
  *
  *
  *
  *ISSUE DELETING RECIPE	
  *
  *
  *
  *
 */ 
  
define("VOTES_REQUIRED", 2);
  
function retrieve_recipe_infos($id){ // prend en paramètre l'id de l'user, soit $_SESSION['id']
	$sql='SELECT * FROM recipes WHERE id='.$id;
	$query=mysql_query($sql);
	$verif = mysql_num_rows($query);

	if ($verif > 0){
		return $result=mysql_fetch_assoc($query);
	}
	return false;
}

function getNbVotesByRecipeId($id){ // returns number of votes;; FALSE if error
	$query = mysql_query('SELECT id FROM recipe_public_approval WHERE id_recipe='.$id);
	if(!$query) return false;
	return mysql_num_rows($query);
}

function getCurrentUserVoteByRecipeId($id){ // returns whether current user has voted for recipe or not
	global $userid;
	$query = mysql_query('SELECT id FROM recipe_public_approval WHERE id_recipe='.$id.' AND id_user='.$userid);
	if(!$query) return false;
	if(mysql_num_rows($query)>0) return true;
	return false;
}

function is_public_ByRecipeId($id){
	$query = mysql_query('SELECT approval FROM recipes WHERE id='.$id);
	if(!$query) return false;
	$state = mysql_fetch_assoc($query);
	if($state['approval'] == 0) return true;
	return false;
}

function is_approved_public_ByRecipeId($id){
	$query = mysql_query('SELECT approval FROM recipes WHERE id='.$id);
	if(!$query) return false;
	$state = mysql_fetch_assoc($query);
	if($state['approval'] == 2) return true;
	return false;	
}

function is_CurrentUser_RecipeCreator_ByRecipeID($id){
	global $userid;
	$recipe = retrieve_recipe_infos($id);
	if(!$recipe) return false;
	return $userid == $recipe['id_user'];
}

function recipe_exists($id){ // retourne booléen si la recette existe ou non
	$query = mysql_query('SELECT id FROM recipes WHERE id='.$id);
	if(!$query || mysql_num_rows($query) < 1) return false;
	return true;
}

function getNumLikesByRecipeID($id){ // retourne le nombre de likes pour une recette
	$query = mysql_query('SELECT id, id_recipe, rating FROM recipe_rating WHERE id_recipe='.$id.' AND rating=1' );
	if(!$query || mysql_num_rows($query) < 1 ){
		return 0;
	}
	return mysql_num_rows($query);
}

function getNumDislikesByRecipeID($id){ // retourne le nombre de dislikes pour une recette
	$query = mysql_query('SELECT id, id_recipe, rating FROM recipe_rating WHERE id_recipe='.$id.' AND rating=0' );
	if(!$query || mysql_num_rows($query) < 1 ){
		return 0;
	}
	return mysql_num_rows($query);
}

function hasVoted($idrecipe){ // retourne si l'utilisateur a déjà voté
	global $userid;
	$query = mysql_query('SELECT * FROM recipe_rating WHERE id_recipe='.$idrecipe.' AND id_user='.$userid);
	if(!$query || mysql_num_rows($query) < 1){
		return false;
	}
	return true;
}

function getUserVote($idrecipe){ // retourne le vote de l'utilisateur 0 ou 1
	global $userid;
	
	$query = mysql_query('SELECT * FROM recipe_rating WHERE id_recipe='.$idrecipe.' AND id_user='.$userid);
	if(!$query){
		return false;
	}
	
	$result = mysql_fetch_assoc($query);
	
	return $result['rating'];
}

/******************** PRINTERS **************************/
function printPublicApproval(){
	global $userid;
	$ficelle = '';
	if(isset($_GET['id'])){ // recipe id set
		$recipeID = $_GET['id'];
		if(is_public_ByRecipeId($recipeID)){ // public recipe, not approved yet
			$ficelle.= '<br><strong>This recipe is awating for public approval</strong>';
			$recipeinfos = retrieve_recipe_infos($recipeID);
			if($recipeinfos!=false && $userid!=$recipeinfos['id_user']){
				
				if(getCurrentUserVoteByRecipeId($recipeID)){ // current user has already voted
					$ficelle.= '<br>You have already voted.';
				}else{ // hasn't voted yet
					$ficelle.= '<form action="recipe.php?id='.$recipeID.'" method="post" >';
					
					if(isset($_GET['iduser'])) $ficelle.= '<input type="hidden" name="iduser" id="iduser" value='.$_GET['iduser'].' />';
					$ficelle.= '<input type="hidden" name="vote" id="vote" value=1 />
					<input type="submit" value="Approve for this recipe!" /></form>';
				}
			}
		}elseif(is_approved_public_ByRecipeId($recipeID)){ // aproved public recipe
			$ficelle.= '<br>This recipe is public.';
		}
	}
	return $ficelle;
}

function printMandatoryJScript($recipeID){
	$df = "";
	
	if(is_CurrentUser_RecipeCreator_ByRecipeID($recipeID)){
		$df.= "<script type='text/javascript'>
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
				</script>";
	}
	
	return $df;
}

function printUserActions_ByRecipeId($recipeID){
	$df = "";
	
	if(is_CurrentUser_RecipeCreator_ByRecipeID($recipeID)){
		$dir = $_SERVER['PHP_SELF'];
		$df.= "<div>
		<img src='img/templates/option.png' width='150' height='40' class='menu_head' />
			<ul class='menu_body'>
				<li><a href='editrecipe.php?id=$_GET[id]'>Edit</a></li>
				<li><a id='removerec' onclick='removeRecipe(event,$_GET[id])' href='#'>Delete</a></li>


				<script>
		  function removeRecipe(e, id) {
		  var a, url, x;
		  e.preventDefault();
		  a = e.target.parentNode;
		  a.parentNode.hidden = true;
		  url = './jsphp/deleterecipe.php?id=' + id;
		  x = new XMLHttpRequest();
		  x.open('GET', url, true);
		  x.onload = function(e) {
			a.innerHTML = this.responseText;
			if(this.responseText !== 'success') {
			  a.innerHTML = this.responseText;
			  a.parentNode.hidden = false;
			} else {
			  location.pathname = 'recipes.php';
			}
		  };
		  x.send();
		}
		</script>



				<li><a href='recipeTopdf.php?id=$_GET[id]'>Export to PDF</a></li>
				<li><a href='#'>Share</a></li>

				<script>
		  function exportRecipe(e, data) {
		  var a, url, x;
		  e.preventDefault();
		  a = e.target.parentNode;
		  a.parentNode.hidden = true;
		  url = './jsphp/recipeTopdf.php?data=' + data;
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
		</script>

			</ul>
		</div>";
	}else{
		$df.= "<div>
			<img src='img/templates/option.png' width='150' height='40' class='menu_head'>
				<ul class='menu_body'>


					<li><a href='recipeTopdf.php?id=$_GET[id]'>Export to PDF</a></li>
					<li><a href='#'>Share</a></li>

					<script>
			  function exportRecipe(e, data) {
			  var a, url, x;
			  e.preventDefault();
			  a = e.target.parentNode;
			  a.parentNode.hidden = true;
			  url = './jsphp/recipeTopdf.php?data=' + data;
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
			</script>

				</ul>
			</div>";
	}
	
	return $df;
}

function printRecipeRating($id){
	global $userid;
	$df = '';	
	
	if(!recipe_exists($id)){
		return "Recipe not found";
	}
	
	if($_POST){
		// like
		if(isset($_POST['Like_recipe']) && !hasVoted($_POST['Like_recipe'])){
			$query = mysql_query('INSERT INTO recipe_rating(id_recipe, id_user, rating) VALUES('.$id.', '.$userid.', 1)');
			if(!$query){
				return 'Vote error';
			}
		}
		
		// dislike
		if(isset($_POST['Dislike_recipe']) && !hasVoted($_POST['Dislike_recipe'])){
			$query = mysql_query('INSERT INTO recipe_rating(id_recipe, id_user, rating) VALUES('.$id.', '.$userid.', 0)');
			if(!$query){
				return 'Vote error';
			}
		}	
		
		//undo
		if(isset($_POST['Undo_vote'])){
			$query = mysql_query('DELETE FROM recipe_rating WHERE id_user='.$userid.' AND id_recipe='.$id);
			if(!$query){
				return 'Vote undo error';
			}
		}
	}
	
	$likes = getNumLikesByRecipeID($id);
	$dislikes = getNumDislikesByRecipeID($id);
	// print les nombres de likes/dislikes
	if($likes<2){
		$df.= 'Like: '.$likes;
	}else{
		$df.= 'Likes: '.$likes;
	}
	$df.= ', ';
	if($dislikes<2){
		$df.= 'Dislike: '.$dislikes;
	}else{
		$df.= 'Dislikes: '.$dislikes;
	}
	$df.= '<br>';
	
	$df.= '<form action="'.$_SERVER['PHP_SELF'].'?id='.$id.'" method="post" >'; // form line
	if(hasVoted($id)){
		if(getUserVote($id) == 1){ // user likes it!
			$df.= 'You like this recipe. ';
		}else{
			$df.= 'You don\'t like this recipe. ';
		}
		$df.= 	'<button title="Undo_vote" value='.$id.' name="Undo_vote" type="submit">
					Undo
				</button>';
	}else{
		// Like button
		$df.= '<button title="Like_recipe" value='.$id.' name="Like_recipe" type="submit">
					Like
				</button>';
		// Dislike button
		$df.= '<button title="Dislike_recipe" value='.$id.' name="Dislike_recipe" type="submit">
					Dislike
					</button>';
	}
	
	$df.= '</form>';
	$df.= '<hr>';
	
	return $df;
}

/***********************************************************/
if (isset($userid)){ // vérification si logué ou pas

	if(isset($_GET['id']) && isset($_POST['vote'])){
		$recipeID = $_GET['id'];
		if(is_public_ByRecipeId($recipeID) && !getCurrentUserVoteByRecipeId($recipeID) ){ // hasn't voted yet
			$query = mysql_query('INSERT INTO recipe_public_approval(id_recipe, id_user) VALUES('.$recipeID.', '.$userid.')'); // vote request
			if(!$query) $message.= "FATAL ERROR Approving recipe"; // vote failed
			
			$query = mysql_query('SELECT id FROM recipe_public_approval WHERE id_recipe='.$recipeID);
			if(!$query){
				$message.= "FATAL ERROR Checking public recipe"; // vote failed
			}else{
				if(mysql_num_rows($query) >= VOTES_REQUIRED){ // make this recipe public approved
					$query = mysql_query('UPDATE recipes SET approval=2 WHERE id='.$recipeID);
					if(!$query){
						$message.= "FATAL ERROR Checking public recipe"; //make this recipe public approved failed
					}else{
						$query = mysql_query('DELETE FROM recipe_public_approval WHERE id_recipe='.$recipeID);
						if(!$query)
							$message.= "FATAL ERROR Checking public recipe"; // deleting votes failed
					}
				}
			}
		}
	}

	if(isset($_GET['id']) && !isset($_GET['iduser'])){ // id de la recette et PAS du user indiqués dans l'url
		$html = '';
		
		$i = retrieve_recipe_infos($_GET['id']);

		if(!$i){
			$html.= 'Recipe not found';
		}else{
			$query21 = mysql_query("SELECT * FROM country WHERE id_country=$i[country_origin]");
			$res2 = mysql_fetch_assoc($query21);
			$i['country_origin']=$res2['name_en'];

			$query11 = "SELECT name_en FROM recipe_difficulty WHERE id=$i[difficulty]";
			$res11 = mysql_query($query11);
			$row = mysql_fetch_assoc($res11);

			$i['difficulty']= $row['name_en'];

			$html.= printMandatoryJScript($_GET['id']);
			
			
			
			$data = '<h2>'.$i['name_en'].'</h2> by '.printLinkToProfileByUserId($i['id_user']).printPublicApproval().'<br><br>';
			
			$data.= printRecipeRating($_GET['id']);
			
			$html.= printUserActions_ByRecipeId($_GET['id']);

			$query = sprintf("SELECT path_source FROM recipe_photos WHERE id_recipe='%s'",
			mysql_real_escape_string($_GET['id']));
			$result2 = mysql_query($query);

			if(mysql_num_rows($result2) > 0){
				$ij = mysql_fetch_assoc($result2);
				if(!file_exists($ij['path_source'])){
					$data.= "<img src='".NO_IMAGE."' width='200px' height='175px' />";
				}else{
					$data.= "<img src='$ij[path_source]' width='200px' height='175px' />";
				}
			}else{
				$data.= "<img src='".NO_IMAGE."' width='200px' height='175px' />";
			}
			
			$data.="<hr><strong>Ingredients</strong>:<ul>";

			//selection des ingredients reliees a la recette
			$query = sprintf("SELECT id_ingredient FROM recipe_ingredients WHERE id_recipe='%s'",
			mysql_real_escape_string($_GET['id']));
			$result = mysql_query($query);

			while($row=mysql_fetch_row($result)) {
				$query1 = "SELECT name_en FROM ingredients WHERE id=$row[0]";
				$response = mysql_query($query1);
				while($row1 = mysql_fetch_assoc($response)){
					$data.="<li>$row1[name_en]</li>";
				}
			}

			
			
			$data.="</ul>
				<div><strong>Description</strong>: $i[description_en]</div><br>
				<div><strong>Origin</strong>: $i[country_origin]</div>
				<div><strong>Difficulty</strong>: $i[difficulty]</div>
				<div><strong>Servings</strong>: $i[num_serves] </div>
				<div><strong>Preparation</strong>: $i[duration_preparation] minutes</div>
				<div><strong>Cooking</strong>: $i[duration_cook] minutes</div><br>
				<div><strong>Instructions</strong>: $i[preparation_en]</div>";

			$html.= "$data";
		
		}

	}else if(isset($_GET['iduser']) && isset($_GET['id'])){ // id de la recette et du user indiqués dans l'url
		$html = '';

		$i = retrieve_recipe_infos($_GET['id']);

		if(!$i){
			$html.= 'Recipe not found';
		}else{
			$query21 = mysql_query("SELECT * FROM country WHERE id_country=$i[country_origin]");
			$res2 = mysql_fetch_assoc($query21);
			$i['country_origin']=$res2['name_en'];

			$query11 = "SELECT name_en FROM recipe_difficulty WHERE id=$i[difficulty]";
			$res11 = mysql_query($query11);
			$row = mysql_fetch_assoc($res11);

			$i['difficulty']= $row['name_en'];

			$html.= "<script type='text/javascript'>
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
					</script>";

			$data = '<h2>'.$i['name_en'].'</h2> by '.printLinkToProfileByUserId($i['id_user']).printPublicApproval().'<br/><br/>';
			
			$html.="<div>
			<img src='img/templates/option.png' width='150' height='40' class='menu_head' />
				<ul class='menu_body'>


					<li><a href='recipeTopdf.php?id=$_GET[id]'>Export to PDF</a></li>
					<li><a href='#'>Share</a></li>

					<script>
			  function exportRecipe(e, data) {
			  var a, url, x;
			  e.preventDefault();
			  a = e.target.parentNode;
			  a.parentNode.hidden = true;
			  url = './jsphp/recipeTopdf.php?data=' + data;
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
			</script>

				</ul>
			</div>";

			
			$query = sprintf("SELECT path_source FROM recipe_photos WHERE id_recipe='%s'",
			mysql_real_escape_string($_GET['id']));
			$result2 = mysql_query($query);

			if(mysql_num_rows($result2) > 0){
				$ij = mysql_fetch_assoc($result2);
				if(!file_exists($ij['path_source'])){
					$data.= "<img src='".NO_IMAGE."' width='200px' height='175px' />";
				}else{
					$data.= "<img src='$ij[path_source]' width='200px' height='175px' />";
				}
			}else{
				$data.= "<img src='".NO_IMAGE."' width='200px' height='175px' />";
			}			
			
			$data.="<strong>Ingredients</strong>:<ul>";

			//selection des ingredients reliees a la recette
			$query = sprintf("SELECT id_ingredient FROM recipe_ingredients WHERE id_recipe='%s'",
			mysql_real_escape_string($_GET['id']));
			$result = mysql_query($query);

			while($row=mysql_fetch_row($result)) {
				$query1 = "SELECT name_en FROM ingredients WHERE id=$row[0]";
				$response = mysql_query($query1);
				while($row1 = mysql_fetch_assoc($response)){
					$data.="<li>$row1[name_en]</li>";
				}
			}

			$data.="</ul>
				<div><strong>Description</strong>: $i[description_en]</div>
				<div><strong>Origin</strong>: $i[country_origin]</div>
				<div><strong>Difficulty</strong>: $i[difficulty]</div>
				<div><strong>Servings</strong>: $i[num_serves] </div>
				<div><strong>Preparation</strong>: $i[duration_preparation] minutes</div>
				<div><strong>Cooking</strong>: $i[duration_cook] minutes</div>
				<div><strong>Instructions</strong>: $i[preparation_en]</div>";

			$html.= "$data";		
		}
	}
	printDocument('Recipe');
}else{

	header('Location: index.php');
}

?>

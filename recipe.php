<?php
  include './header.php';
    

function retrieve_recipe_infos($id){ // prend en paramètre l'id de l'user, soit $_SESSION['id']
	$sql='SELECT * FROM recipes WHERE id='.$id;
	$query=mysql_query($sql);
	$verif = mysql_num_rows($query);
	
	if ($verif > 0){
	return $result=mysql_fetch_assoc($query);
	}
	return false;
  }



if (isset($userid)){ // vérification si logué ou pas
	
	$html = '';
  
	$i = retrieve_recipe_infos($_GET['id']);
   
  
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

	$data = '<h2>'.$i['name_en'].'</h2> by '.printLinkToProfileByUserId($i['id_user']).'<br/><br/>';
	
	
	
	$html.="
	
	<div>
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
      url = './deleterecipe.php?id=' + id;
      x = new XMLHttpRequest();
      x.open('GET', url, true);
      x.onload = function(e) {
        a.innerHTML = this.responseText;
        if(this.responseText !== 'success') {
          a.innerHTML = this.responseText;
          a.parentNode.hidden = false;
        } else {
		  location.pathname = '/~jwankutk/tuto_john/recipes.php';
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
      url = './recipeTopdf.php?data=' + data;
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
   
	$query = sprintf("SELECT path_source FROM recipe_photos WHERE id_recipe='%s'",
	mysql_real_escape_string($_GET['id'])); 	
	$result2 = mysql_query($query);
	
	if(mysql_num_rows($result2) == 1){
		$ij = mysql_fetch_assoc($result2);
		$data.= "<img src='$ij[path_source]' width='200px' height='175px' />";	
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
  

	printDocument('Recipe');  
}else{
	
	header('Location: index.php');
}
  
?>

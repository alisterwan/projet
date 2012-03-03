<?php
	include './header.php';

	/********************************GESTION DES CUSTOMERS **********************************/ 
	//si le formulaire validé et update
	if (isset($_GET['mode'])  && $_GET['mode']=="customers" && $_POST['update']){
	$update = updateUser($_POST['update']);
		if($update){
		echo " Update Successful";
		}
		else{
		echo "Update Failed";
		}
	}
	
	//si le formulaire validé et delete
	if (isset($_GET['mode'])  && $_GET['mode']=="customers" && $_POST['delete']){
	$delete = DeleteOnTable('users',$_POST['delete']);
		if($delete){
		echo " Delete Successful";
		}
		else{
		echo "Delete Failed";
		}
	}
	
	
	if ( isset($_GET['mode'])  && $_GET['mode']=="customers") {
	//affiche tous les customers
	echo "<p>Select a customer in the drop-down list.</p>
	<form action='./index.php?mode=customers' method='get'>
	<button type='submit' name='select' value='customers'>Select</button>
	<select name='iduser'>";
		
		$query = "SELECT * FROM users";
		$result = mysql_query($query);
	
		while ($customer = mysql_fetch_assoc($result)){
			echo "<option value='$customer[id]'>
			$customer[firstname] $customer[surname] ($customer[username])
			</option>";
			}
		
		echo "</select></form>";

		
	}

	//si un user est choisit dans la liste on affiche ses coordonnŽes
	if ( isset($_GET['select'])  && $_GET['select']=="customers" && $_GET['iduser'] ) {
	$html = printFormCustomer($_GET['iduser']);
	
	echo "$html"; 
	
	}

	/*********************************GESTION DES RECETTES **********************************/
	//si le formulaire validé et update
	if (isset($_GET['mode'])  && $_GET['mode']=="recipes" && $_POST['update']){
	$update = updateRecipe($_POST['update']);
		if($update){
		echo " Update Successful";
		}
		else{
		echo "Update Failed";
		}
	}
	
	//si le formulaire validé et delete
	if (isset($_GET['mode'])  && $_GET['mode']=="recipes" && $_POST['delete']){
	$delete = DeleteOnTable('recipes',$_POST['delete']);
		if($delete){
		echo " Delete Successful";
		}
		else{
		echo "Delete Failed";
		}
	}
	
	if ( isset($_GET['mode'])  && $_GET['mode']=="recipes") {
	//affiche toutes les recettes
	echo "<p>Select a recipe in the drop-down list.</p>
	<form action='./index.php?mode=recipes' method='get'>
	<button type='submit' name='select' value='recipes'>Select</button>
	<select name='idrecipe'>";
		
		$query = "SELECT * FROM recipes";
		$result = mysql_query($query);
	
		while ($recipe = mysql_fetch_assoc($result)){
			$query2 = "SELECT * FROM users WHERE id='$recipe[id_user]'";
			$result2 = mysql_query($query2);
			$row = mysql_fetch_assoc($result2);
			
			echo "<option value='$recipe[id]'>
			$recipe[name_en] $row[name_en] by $row[firstname] $row[surname] ($row[username])
			</option>";
			}
		echo "</select></form>";

	}
	
	//si une recette est choisie dans la liste on affiche ses infos
	if ( isset($_GET['select'])  && $_GET['select']=="recipes" && $_GET['idrecipe'] ) {
		$html = printFormRecipe($_GET['idrecipe']);
				
		echo  "$html";
			 
	}
	
	/************************************GESTION DES INGREDIENTS ******************************/
	
	//si le formulaire validé et update
	if (isset($_GET['mode'])  && $_GET['mode']=="ingredients" && $_POST['update']){
	$update = updateIngredient($_POST['update']);
		if($update){
		echo " Update Successful";
		}
		else{
		echo "Update Failed";
		}
	}
	
	//si le formulaire validé et delete
	if (isset($_GET['mode'])  && $_GET['mode']=="ingredients" && $_POST['delete']){
	$delete = DeleteOnTable('ingredients',$_POST['delete']);
		if($delete){
		echo " Delete Successful";
		}
		else{
		echo "Delete Failed";
		}
	}
	
	if ( isset($_GET['mode'])  && $_GET['mode']=="ingredients") {
	//affiche tous les ingredients
	echo "<p>Select an ingredient in the drop-down list.</p>
	<form action='./index.php?mode=ingredients' method='get'>
	<button type='submit' name='select' value='ingredients'>Select</button>
	<select name='idingredient'>";
		
		$query = "SELECT * FROM ingredients";
		$result = mysql_query($query);
	
		while ($ing = mysql_fetch_assoc($result)){
			$query2 = "SELECT * FROM ingredients WHERE id='$ing[id]'";
			$result2 = mysql_query($query2);
			$row = mysql_fetch_assoc($result2);
			
			echo "<option value='$ing[id]'>
			$row[name_en]
			</option>";
			}
		
		echo "</select></form>";

	}
	
	
	
	//si un ingredient est choisi dans la liste on affiche ses infos
	if ( isset($_GET['select'])  && $_GET['select']=="ingredients" && $_GET['idingredient']) {	
		$html = printFormIngredient($_GET['idingredient']);
	echo  "$html"; 
	}
	
	

	
	printFooter();
?>

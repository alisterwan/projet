<?php
  include './header.php';

/*********Fonctions*****************/
function redirect_ingredient() {
	$query = mysql_fetch_row(mysql_query(sprintf("SELECT id FROM ingredients WHERE name_en LIKE '%s'",
			mysql_real_escape_string(strip_tags($_POST['name'])))));
    $id = $query[0];
    header("Location: ingredients.php?id=$id");
    exit;
}
/***********************************/

if(isset($_POST['name']) && isset($_POST['description']) && $_POST['name']!="" ){

	$query = mysql_num_rows(mysql_query(sprintf("SELECT id FROM ingredients WHERE name_en LIKE '%s'",
        mysql_real_escape_string(strip_tags($_POST['name'])))));
		
    if($query){
		redirect_ingredient();
    }else{
		$query = sprintf("INSERT INTO ingredients(name_en,description_en) VALUES('%s','%s');",
        mysql_real_escape_string(strip_tags($_POST['name'])),
        mysql_real_escape_string(strip_tags($_POST['description'])));
		$response = @mysql_query($query);
		if(!$response) {
			$message = "<p class='error'>Connection error.</p>";
		}else{
			redirect_ingredient();
		}
    }
}

// formulaire
$html = "<form action='newingredient.php' method='post' id='contribution'>
			<p>Please define the ingredient.</p>
			<label>Name <input type='text' name='name' required></label>
			<p>You may also describe it.</p>
			<textarea name='description'></textarea>
			<input type='submit' value='Submit'>
		</form>";

 printDocument();
?>
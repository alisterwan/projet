<?php
include './header.php';
?>



<div id='addingredients' style='text-align:justify;'>
		
		<form action='newrecipe.php' method='get'>
			<label id='addIng'>
			<img src='img/button_add.gif'name='nbChamps' id='Ingredients' onclick='addIngredient()'/>
			
			</label>

 
			
			<datalist id='ingredientList'>
		<?php
 		$ingredients = mysql_query("SELECT name_en,id FROM ingredients");
  		while($ingredient = mysql_fetch_array($ingredients)) {
    	echo "<option value='$ingredient[0]'>$ingredient[0]</option>";
 		 } 
		?>
		
			</datalist>
		
		
		<input type='submit' value='Submit'>
		</form>	


</div>	

</body>
</html>
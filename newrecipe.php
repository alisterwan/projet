<?php
  include './header.php';

  function redirect() {
    //$id = query recette id;
    //header("Location: recipe.php?id=$id");
    //exit;
  }

  if($_POST) {
    //query si le nom de l'ingrédient existe déjà
    if ("cette recette existe déjà") {
      redirect();
    } else {
      //query
      if(!$query) {
        $message = "<p class='error'>Connection error.</p>";
      } else {
        redirect();
      }
    }
  }

  //il faudrait peut-être le faire par étape
  $html =
    "
  <form action='newrecipe.php' method='post' id='contribution'>
    <p>Please define the recipe.</p>
    <label>Name <input type='text' name='name' value='$_POST[name]' required></label>
    <label>Description <input type='text' name='description' value='$_POST[description]'></label>
    <label>Origin <input type='text' name='origin' value='$_POST[origin]'></label>
    <label>Difficulty <input type='text' name='difficulty' value='$_POST[difficulty]'></label>
    <label>Serves <input type='text' name='serves' value='$_POST[serves]'></label>
    <label>Duration Preparation <input type='text' name='preparation' value='$_POST[preparation]'></label>
    <label>Duration Cook<input type='text' name='serves' value='$_POST[serves]'></label>
    <label>Ingredient 1<input type='text' name='ing1' value='$_POST[ing1]'></label>
    <label>Ingredient 2<input type='text' name='ing2' value='$_POST[ing2]'></label>
    <label>Ingredient 3<input type='text' name='ing3' value='$_POST[ing3]'></label>
    <label>Ingredient 4<input type='text' name='ing4' value='$_POST[ing4]'></label>
    <label>Ingredient 5<input type='text' name='ing5' value='$_POST[ing5]'></label>
    <label>Preparation Method <textarea name='method'>$_POST[method]</textarea></label>
    <input type='submit' value='Submit'>
  </form>
    ";

  printDocument();
?>


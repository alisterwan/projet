<?php
  include './header.php';

  function redirect() {
    //$id = query recette id;
    //header("Location: recipe.php?id=$id");
    //exit;
  }

  if($_POST) {
    //query si le nom de l'ingrédient existe déjà
    if ("cet recette existe déjà") {
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
    <label>Attribut 1 <input type='text' name='attr1' value='$_POST[attr1]'></label>
    <label>Attribut 2 <input type='text' name='attr2' value='$_POST[attr2]'></label>
    <label>Directions <textarea name='description'>$_POST[description]</textarea></label>
    <input type='submit' value='Submit'>
  </form>
    ";

  printDocument();
?>


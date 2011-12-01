<?php
  include './header.php';

  function redirect() {
    $query = mysql_fetch_row(mysql_query(
      sprintf("SELECT id FROM recipes WHERE name_en LIKE '%s'",
        mysql_real_escape_string(strip_tags($_POST['name'])))
    ));
    $id = $query[0];
    header("Location: recipe.php?id=$id");
    exit;
  }

  if($_POST) {
    $query = mysql_num_rows(mysql_query(
      sprintf("SELECT id FROM recipes WHERE name_en LIKE '%s'",
        mysql_real_escape_string(strip_tags($_POST['name'])))
    ));
    if ($query) {
      redirect();
    } else {
      $query = sprintf("INSERT INTO recipes(name_en,description_en,country_origin,difficulty,num_serves,duration_preparation,duration_cook,preparation_en) VALUES('%s','%s','%s','%s','%s','%s','%s','%s');",
        mysql_real_escape_string(strip_tags($_POST['name'])),
        mysql_real_escape_string(strip_tags($_POST['description'])),
        mysql_real_escape_string(strip_tags($_POST['origin'])),
        mysql_real_escape_string(strip_tags($_POST['difficulty'])),
        mysql_real_escape_string(strip_tags($_POST['serves'])),
        mysql_real_escape_string(strip_tags($_POST['prepDuration'])),
        mysql_real_escape_string(strip_tags($_POST['cookDuration'])),
        mysql_real_escape_string(strip_tags($_POST['method'])));
      $response = @mysql_query($query);

      /*
       * quel méthode pour insérer les ingrédients?
       */

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
    <label>Preparation Duration <input type='text' name='prepDuration' value='$_POST[prepDuration]'></label>
    <label>Cooking Duration <input type='text' name='cookDuration' value='$_POST[cookDuration]'></label>
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


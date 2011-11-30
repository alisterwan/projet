<?php
  include './header.php';

  function redirect() {
    //$id = query ingredient id;
    //header("Location: ingredient.php?id=$id");
    //exit;
  }

  if($_POST) {
    //query si le nom de l'ingrédient existe déjà
    if ("cet ingrédient existe déjà") {
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

  $name = $_POST['name'] ? $_POST['name'] : $_GET['name'];

  $html =
    "
  <form action='newingredient.php' method='post' id='contribution'>
    <p>Please define the ingredient.</p>
    <label>Name <input type='text' name='name' value='$name' required></label>
    <label>Attribut 1 <input type='text' name='attr1' value='$_POST[attr1]'></label>
    <label>Attribut 2 <input type='text' name='attr2' value='$_POST[attr2]'></label>
    <p>You may also describe it.</p>
    <textarea name='description'>$_POST[description]</textarea>
    <input type='submit' value='Submit'>
  </form>
    ";

  printDocument();
?>


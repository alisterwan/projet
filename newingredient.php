<?php
  include './header.php';

  $html .=
    "
  <form action='handlecontribution.php' method='post' id='contribution'>
    <p>Please define the ingredient.</p>
    <input type='hidden' name='contribution' value='ingredient'>
    <label>Name
    <input type='text' name='name' required></label>
    <label>Attribut 1
    <input type='text' name='attr1' required></label>
    <label>Attribut 2
    <input type='text' name='attr2' required></label>
    <label>Attribut 3
    <input type='text' name='attr3' required></label>
    <label>Attribut 4
    <input type='text' name='attr4' required></label>
    <label>Attribut 5
    <input type='text' name='attr5' required></label>
    <label>Attribut 6
    <input type='text' name='attr6' required></label>
    <label>Attribut 7
    <input type='text' name='attr7' required></label>
    <label>Attribut 8
    <input type='text' name='attr8' required></label>
    <label>Attribut 9
    <input type='text' name='attr9' required></label>
    <p>You may also describe it.</p>
    <textarea></textarea>
    <input type='submit' value='Submit'>
  </form>
    ";

  printDocument();
?>


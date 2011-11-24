<?php
  include './header.php';

  $html = "<h1>$user[0] $user[1] ($user[6])</h1>
  <img src='$user[9]'/>
  <h3>My Albums</h3>
  ";

  printDocument('My Albums');
?>

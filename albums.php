<?php
  include './header.php';

  $html = "<h1>$user[firstname] $user[surname] ($user[username])</h1>
  <h3>My Albums</h3>
  ";

  printDocument('My Albums');
?>

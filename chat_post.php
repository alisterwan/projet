<?php
include './header.php';

  // Effectuer ici la requête qui insère le message
  if (!pg_query($conn,"INSERT INTO chat(message,id_cust) VALUES ('$_POST[message]','$customer[7]')")) {
    $html = "<p class='error'>Query error.</p>";
    printDocument('Error');
  }

  // Puis rediriger vers chat.php comme ceci :
  header('Location: chat.php');
?>

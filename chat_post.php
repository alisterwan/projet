<?php
  include './header.php';

  // Effectuer la requête qui insère le message
  if (pg_query($conn,"INSERT INTO chat(message,id_cust) VALUES ('$_POST[message]','$_SESSION[id]')")) {
    // Puis rediriger vers chat.php comme ceci :
    header('Location: chat.php');
  }
  else {
    $message = "<p class='error'>Query error.</p>";
    printDocument('Error');
  }
?>

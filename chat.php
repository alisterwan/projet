<?php
  include './header.php';

  $html = "
  <form action='chat_post.php' method='post'>
    <input type='text' name='message' id='message'>
    <input type='submit' value='Send'>
  </form>";


  // Affichage de chaque message (toutes les données sont protégées par htmlspecialchars)
  $chat = pg_query($conn,"SELECT message,id_cust FROM chat");
  while ($row = pg_fetch_row($chat)) {
    $html .= "<strong>id:$row[1]</strong> $row[0]<br>";
  }

  printDocument('Chatroom');
?>

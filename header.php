<?php
  session_start();

  // Connexion à la base de donnée
  if (!$conn = pg_connect("host=sqletud.univ-mlv.fr port=5432 dbname=mboivent_db user=mboivent password=equina4"))
    echo "<p class='error'>Connexion error.</p>";

  // Affiche l'entête
  function printHeader($title) {

    // Affiche le nom d'utilisatuer s'il est connecté et un lien vers sa page perso.
    if ($_SESSION[name])
      $html = "<span id='log'>Welcome <a href='./account.php'>$_SESSION[name]</a>. <a href='./logout.php'>Log out</a>.</span>";

    // Sinon, afficher un lien pour se connecter.
    else
      $html = "<span id='log'>Welcome. <a href='./login.php'>Log in</a> or <a href='./registration.php'>register</a>.</span>";

    echo "
<!doctype html>
<html lang='en'>
  <head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'>
    <title>$title</title>
    <meta name='description' content='Projet tuto'>
    <meta name='author' content='John Marie Equina Nicolas'>
    <link href='http://fonts.googleapis.com/css?family=Clara' rel='stylesheet'>
    <link href='stylesheet.css' rel='stylesheet'>
  </head>
  <body>
    <header>
      <div class='header'>
        <a href='./index.php' id='logo'>DigEat</a>
      </div>
    </header>
    <div id='body' class='clearfix'>
      <div id='leftbox' class='panel'></div>
      <div id='content' class='panel'>";
  }

  // Affichage du pied de la page.
  function printFooter() {
    echo "</div>
      <div id='rightbox' class='panel'>
        <form action='./index.php' method='post'>
          <div>Log in:</div>
          <div><input type='text' name='username' placeholder='Username' required></div>
          <div><input type='password' name='password' placeholder='password' required></div>
          <div><input type='submit' name='proceed' value='Submit'> <a href='./#'>Forgot your password?</a></div>
        </form>
        <form action='./registration.php' method='post'>
          <div>Register:</div>
          <div><input type='text' name='firstname' placeholder='Firstname' required></div>
          <div><input type='text' name='surname' placeholder='Surname' required></div>
          <div><input type='text' name='email' placeholder='Email' required></div>
          <div><input type='submit' name='proceed' value='Submit'></div>
        </form>
      </div></div></body></html>";
  }

?>

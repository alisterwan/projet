<?php
  session_start();



  // Connexion à la base de donnée
  if (!$conn = pg_connect("host=sqletud.univ-mlv.fr port=5432 dbname=mboivent_db user=mboivent password=equina4")) {
    $message = "<p class='error'>Connexion error.</p>";
  }


  else if ($_POST) {
    $name = $_POST[name];
    $pass = sha1($_POST[pass]);


  // Rediriger l'admin s'il est correctement identifié
    if ($name == 'admin' && $pass == 'f6793a9e6ca5356123fe0ab34bb46443894a5edf') {
      $_SESSION[masterpass] = $pass;
      header('Location: admin/index.php');
    }


    // Vérification du client dans la base de donnée
    else if (pg_num_rows($query = pg_query($conn,"SELECT id_customer FROM users WHERE username='$user' and password='$pass'"))) {
      $_SESSION[id] = pg_fetch_result($query, 0, 0);
      $message = "<p class='loggedin'>You are successfully logged in. Welcome <a href='./#'>$name</a>.</p>";
    }
    else {
      $message = "<p class='error'>Username or password incorrect, try again.</p>";
    }
  }



  if ($_SESSION[id]) {
    /*
     * On récupère les infos de l'utilisateur sous forme de tableau associatif:
     * $user[firstname]
     * $user[surname]
     * $user[address]
     * $user[city]
     * $user[country]
     * $user[username]
     * $user[pass]
     * $user[mail]
     * $user[id_customer] == $_SESSION[id]
     */
    $user = pg_fetch_assoc(pg_query($conn,"SELECT * from users where id_customer='$_SESSION[id]'"));
  }


  function printDocument($title = "DigEat") {
    global $message, $html;
    $title = $title == "DigEat" ? $title : "DigEat - $title";
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
      <div id='content' class='panel'>
        $message
        $html
      </div>
    <div id='rightbox' class='panel'>
      ".rightboxContent()."
    </div>
  </body>
</html>";
  }



  function rightboxContent() {
    if ($_SESSION[id]) {
      // Requête qui récupère toutes les coordonnées du client
      global $user;
      $content = "<p>Your account information:</p>
        <div>$user[firstname] $user[surname]</div>
        <div>$user[adress]</div>
        <div>$user[city]</div>
        <div>$user[country]</div>
        <div>$user[mail]</div>
        <a href='./profile.php'>My profile</a><br>
        <a href='./modifyaccount.php'>Modify my account</a><br>
        <a href='./image.php'>My albums</a><br>
        <a href='./chat.php'>Chatroom</a><br>
        <a href='./logout.php'>Log out</a>";
    }

    else {
      // Afficher les formulaires pour se connecter et s'inscrire
      $content = "
        <form action='./index.php' method='post'>
          <div>Log in:</div>
          <div><input type='text' name='name' placeholder='Username' required></div>
          <div><input type='password' name='pass' placeholder='password' required></div>
          <div><input type='submit' value='Submit'> <a href='./#'>Forgot your password?</a></div>
        </form>
        <form action='./registration.php' method='post'>
          <div>Register:</div>
          <div><input type='text' name='firstname' placeholder='Firstname' required></div>
          <div><input type='text' name='surname' placeholder='Surname' required></div>
          <div><input type='text' name='email' placeholder='Email' required></div>
          <div><input type='submit' name='proceed' value='Submit'></div>
        </form>";
    }
    return $content;
  }

?>

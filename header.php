<?php
  session_start();



  // Connexion à la base de donnée
  if (!$conn = pg_connect("host=sqletud.univ-mlv.fr port=5432 dbname=mboivent_db user=mboivent password=equina4")) {
    $message = "<p class='error'>Connexion error.</p>";
  }


  else if ($_POST) {
    $user = $_POST[username];
    $pass = sha1($_POST[password]);


  // Rediriger l'admin s'il est correctement identifié
    if ($user == 'admin' && $pass == 'f6793a9e6ca5356123fe0ab34bb46443894a5edf') {
      $_SESSION[name] = Admininistrator;
      $_SESSION[masterpass] = $pass;
      header('Location: http://etudiant.univ-mlv.fr/~jwankutk/tuto/admin/index.php');
    }


    // Vérification du client dans la base de donnée
    else if (pg_num_rows(pg_query($conn,"SELECT firstname,surname,address,city,country,username,password,mail,id_customer FROM users WHERE username='$user' and password='$pass'"))) {
      $message = "<p class='loggedin'>You are successfully logged in. Welcome <a href='./#'>$user</a>.</p>";
      $_SESSION[name] = $user;
    }
    else {
      $message = "<p class='error'>Username or password incorrect, try again.</p>";
    }
}



  // Affiche l'entête
  function printHeader($title) {
    global $message;
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
      </div>
    <div id='rightbox' class='panel'>
      ".rightboxContent()."
    </div>";
  }



  function rightboxContent() {
    if ($_SESSION[name]) {
      // Requête qui récupère toutes les coordonnées du client
      global $conn;
      $customer = pg_fetch_row(pg_query($conn,"SELECT firstname,surname,address,city,country,mail,username,id_customer from users where username='$_SESSION[name]'"));
      $content = "<p>Your account information:</p>
        <div>$customer[0] $customer[1]</div>
        <div>$customer[2]</div>
        <div>$customer[3]</div>
        <div>$customer[4]</div>
        <div>$customer[5]</div>
        <a href='./profile.php'>My profile</a><br>
        <a href='./modifyaccount.php'>Modify my account</a><br>
        <a href='./image.php'>My albums</a><br>
        <a href='./chat.php'>Chatroom</a><br>
        <a href='./logout.php'>Log out</a>";
      //On recupere le id de l'utilisateur dans une variable temporaire
      $var_id = $customer[7];
      $var_user =  $customer[6];
    }

    else {
      // Afficher les formulaires pour se connecter et s'inscrire
      $content = "
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
        </form>";
    }
    return $content;
  }


  // Affichage du pied de la page.
  function printFooter() {
    echo "</div></body></html>";
  }

?>

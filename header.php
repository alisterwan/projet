<?php

include './config.php';



  // Connexion à la base de donnée
  $conn = connect(HOST,USER,PASSWORD);

    if ($_POST) {
    $name = $_POST[name];
    $pass = sha1($_POST[pass]);


  // Rediriger l'admin s'il est correctement identifié
    if ($name == 'admin' && $pass == 'f6793a9e6ca5356123fe0ab34bb46443894a5edf') {
      $_SESSION[masterpass] = $pass;
      header('Location: admin/index.php');
    }


    // Vérification du client dans la base de donnée
    else if (mysql_num_rows($query = mysql_query("SELECT id FROM users WHERE username='$name' and password='$pass'"))) {
      $_SESSION[id] = mysql_result($query, 0, 0);
      $message = "<p class='loggedin'>You are successfully logged in. Welcome <a href='./#'>$name</a>.</p>";
    }
    else {
      $message = "<p class='error'>Username or password incorrect, try again.</p>";
    }
  }



  if ($_SESSION[id]) {
    /*
     * On récupère les infos de l'utilisateur sous forme de tableau associatif:
     * $user[0] = firstname
     * $user[1] = surname 
     * $user[3] = address
     * $user[4] = city 
     * $user[5] = country 
     * $user[6] = username
     * $user[9] = profile picture
     * $user[8] = email    
     * $user[10] = $_SESSION[id]
     */
    $query = "SELECT * from users where id='$_SESSION[id]'";
    $query2 = mysql_query($query);
    $user = mysql_fetch_row($query2); 
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
      <div id='header'>
        <a href='./index.php' id='logo'>DigEat</a>
        <nav>
          <a class='nav' href='./index.php'>Home</a>
          <a class='nav' href='./#'>Recipes</a>
          <a class='nav' href='#'>Community</a>
        </nav>
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
        <div>$user[0] $user[1]</div>
        <div>$user[3]</div>
        <div>$user[4]</div>
        <div>$user[5]</div>
        <div>$user[8]</div>
        <a href='./profile.php'>My profile</a><br>
        <a href='./modifyaccount.php'>Modify my account</a><br>
        <a href='./albums.php'>My albums</a><br>
        <a href='./#'>My Recipes</a><br>
	 <a href='./friends.php'>Friends</a><br>
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

<?php

include './includes/config.php';
include './query_functions.php';



  // Connexion à la base de donnée
  $conn = connect(HOST,USER,PASSWORD);

    if ($_POST) {
    $user = $_POST[name];
    $pass = sha1($_POST[pass]);


  // Rediriger l'admin s'il est correctement identifié
    if ($user == 'admin' && $pass == 'f6793a9e6ca5356123fe0ab34bb46443894a5edf') {
      $_SESSION[masterpass] = $pass;
      header('Location: admin/index.php');
    }


    // Vérification du client dans la base de donnée
    
	 $query  = "SELECT id,username FROM users WHERE username='$user' and password='$pass'";
	 $result = mysql_query($query);

        if (mysql_num_rows($result) == 1){
	 $row = mysql_fetch_array($result, MYSQL_NUM);
	 $_SESSION[id] = $row[0];
	 $_SESSION[mail] = $row[1];
	  }
		
    
    	else {
      $message = "<p class='error'>Username or password incorrect, try again.</p>";
	    }


    }

  if (isset($_SESSION[id])) {
    /*
     * On récupère les infos de l'utilisateur sous forme de tableau associatif:
     * $user[firstname]
     * $user[surname] 
     * $user[address]
     * $user[city] 
     * $user[country] 
     * $user[username]
     * $user[avatar]
     * $user[email]    
     * $user[id]
     */
   	$user = good_query_assoc("SELECT * FROM users WHERE id='$_SESSION[id]'");
     
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
        ".navContent()."
	</div>
    </header>
    <div id='body' class='clearfix'>
      <div id='leftbox' class='panel'>
      ".leftboxContent()."
      </div>
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


function navContent(){
    if (isset($_SESSION[id])) {
      // Requête qui récupère toutes les coordonnées du client

      $content = "
      	 <nav>
          <a class='nav' href='./index.php'>Home</a>
	   <a class='nav' href='profile.php'>Profile</a>
          <a class='nav' href='./#'>Recipes</a>
          <a class='nav' href='#'>Community</a>
        </nav>

      ";
    }
    
    else {
	$content = " <nav>
          <a class='nav' href='./index.php'>Home</a>
          <a class='nav' href='./#'>Recipes</a>
          <a class='nav' href='#'>Community</a>
	   <a class='nav' href='registration.php'>Register</a>
        </nav>";
	    
    }
    
    return $content;	
} 


 function leftboxContent() {
    if (isset($_SESSION[id])) {
      // Requête qui récupère toutes les coordonnées du client
      global $user;
      $content = "
      <img src='$user[avatar]' width='170px' height='200px' />
      <a href='./#'>Change my avatar</a><br>
      <a href='./information.php'>My information</a>
      ";
    }
    
    return $content;
}

  function rightboxContent() {
    if (isset($_SESSION[id])) {
      // Requête qui récupère toutes les coordonnées du client
      global $user;
      $content = "<p>Your account information:</p>	
        <div>$user[firstname] $user[surname]</div>
        <div>$user[address]</div>
        <div>$user[city]</div>
        <div>$user[country]</div>
        <div>$user[mail]</div>
       
	<ul>
        <li><a href='./edit_profile.php'>Edit profile</a></li>
        <li><a href='./albums.php'>My albums</a></li>
        <li><a href='./#'>My Recipes</a></li>
	 <li><a href='./friends.php'>Friends</a></li>
        <li><a href='./chat.php'>Chatroom</a></li>
        <li><a href='./logout.php'>Log out</a></li>
	</ul>
	 ";
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
       ";
    }
    return $content;
  }

?>

<?php
  session_start();

  // Connexion à la base de donnée
  if (!$conn = pg_connect("host=sqletud.univ-mlv.fr port=5432 dbname=mboivent_db user=mboivent password=equina4"))
    echo "<p class='error'>Connexion error.</p>";

  // Affiche l'entête
  function printHeader($title) {

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
      <div id='content' class='panel'></div>";
  }
	
	if (!$_SESSION[name]){
	printIdentity();
	}	
	
else {
	// Requête qui récupère toutes les coordonnées du client
	$customer = pg_fetch_row(pg_query($conn,"SELECT firstname,surname,address,city,country,mail from users where username='$_SESSION[name]'"));
	
	echo "
	<div id='rightbox' class='panel'>
		<p>Your account information:</p>
			<div> $customer[0] $customer[1] </div>
			<div> $customer[2] </div>
			<div> $customer[3] </div>
			<div> $customer[4] </div>
			<div> $customer[5] </div>
	
			<a href='./profile.php'>My profile</a><br>
			<a href='./modifyaccount.php'>Modify my account</a><br>
			<a href='./logout.php'>Log out</a>
	</div>";
	}

  // Affichage les formulaires pour se connecter et s'inscrire
  function printIdentity() {
    echo " <div id='rightbox' class='panel'>
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
      </div></div>";
  }
  
 // Affichage du pied de la page.
	function printFooter() {
		echo "
		</div>
	</body>
</html>";
	} 

?>

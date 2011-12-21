<?php

include("includes/config.php");
include("query_functions.php");



///// MODULE CONNEXION /////
    if ($_POST && isset($_GET['mode']) && $_GET['mode']=="logon") {
		$username = $_POST['name']; // ATTENTION IL FAUT METTRE LES QUOTES POUR name !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		$pass = sha1($_POST['pass']); // ATTENTION IL FAUT METTRE LES QUOTES POUR pass !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


	  // Rediriger l'admin s'il est correctement identifié
		if ($username == 'admin' && $pass == 'f6793a9e6ca5356123fe0ab34bb46443894a5edf') {
		  $_SESSION['masterpass'] = $pass; // ATTENTION IL FAUT METTRE LES QUOTES POUR masterpass !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		  header('Location: admin/index.php'); // redirection
		}


		// Vérification du client dans la base de donnée

		 $query  = "SELECT id,username FROM users WHERE username='$username' and password='$pass'";
		 $result = mysql_query($query);

			if (mysql_num_rows($result) == 1){
				 $row = mysql_fetch_array($result, MYSQL_NUM);
				 $_SESSION['id'] = $row[0]; // ATTENTION IL FAUT METTRE LES QUOTES
				 $_SESSION['mail'] = $row[1]; // ATTENTION IL FAUT METTRE LES QUOTES
			}
			else {
				$message = "<p class='error'>Username or password incorrect, try again.</p>";
			}


    }
///// FIN MODULE CONNEXION /////



//// DEFINIT LA VARIABLE GLOBALE  $userid
  if (isset($_SESSION['id'])) {

	$userid=$_SESSION['id'];

  }


 function printDocument($title = "DigEat"){
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
			<link href='./css/stylesheet.css' rel='stylesheet'>
			<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js'></script>
			<script src='js/modernizr.custom.65662.js'></script>
			<script src='js-webshim/minified/polyfiller.js'></script>
			<script>$.webshims.polyfill();</script>
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
    if (isset($_SESSION['id'])) { // ATTENTION IL FAUT METTRE LES QUOTES POUR id !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
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
    if (isset($_SESSION['id'])) { // ATTENTION IL FAUT METTRE LES QUOTES POUR id !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
      // Requête qui récupère toutes les coordonnées du client
      global $userid;
      $userinfos=retrieve_user_infos($userid);

 	$content = "
      <img src= '$userinfos[avatar]' width='170px' height='200px' />
      <a href='./#'>Change my avatar</a><br>
      <a href='./information.php'>My information</a>      ";
	  return $content; // ATTENTION LE return n'était pas à la bonne place!
    }

    //return $content;
}


function rightboxContent() {
		if (isset($_SESSION['id'])) { // ATTENTION IL FAUT METTRE LES QUOTES POUR id !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			  // Requête qui récupère toutes les coordonnées du client
			  global $userid;

			  $userinfos=retrieve_user_infos($userid);
			  /*$content = "<p>Your account information:</p>
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
			</ul>			 ";*/

			 $content = '<p>Your account information:</p>
				<div>'.$userinfos['firstname'].'&nbsp;'.$userinfos['surname'].'</div>
				<div>'.$userinfos['address'].'</div>
				<div>'.$userinfos['city'].'</div>
				<div>'.$userinfos['country'].'</div>
				<div>'.$userinfos['mail'].'</div>

			<ul>
				<li><a href="./edit_profile.php">Edit profile</a></li>
				<li><a href="./albums.php">My albums</a></li>
				<li><a href="./recipes.php">My Recipes</a></li>
			 <li><a href="./friends.php">Friends</a></li>
				<li><a href="./chat.php">Chatroom</a></li>
				<li><a href="./logout.php">Log out</a></li>
			</ul>			 ';

		}else{
		  // Afficher les formulaires pour se connecter et s'inscrire
		  $content = "
			<form action='./index.php?mode=logon' method='post'>
			  <img src='./img/templates/login.png'/>
			  <div><input type='text' name='name' placeholder='Username' required></div>
			  <div><input type='password' name='pass' placeholder='password' required></div>
			  <div><input type='submit' value='Submit'> <a href='./#'>Forgot your password?</a></div>
			</form>
		   ";
		}

		return $content;
}

?>
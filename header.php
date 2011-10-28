<?php
	session_start();

	// Connexion à la base de donnée
	if (!$conn = pg_connect("host=sqletud.univ-mlv.fr port=5432 dbname=mboivent_db user=jwankutk password=equina4"))
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
		<meta name='description' content='Projet web'>
		<meta name='author' content='Alister & Mayhem'>
		<link rel='stylesheet' href='stylesheet.css'>
	</head>
	<body>
		
	}

	// Affichage du pied de la page.
	function printFooter() {
		echo "
		</div>
		<div id='footer'>Copyright &#169; 2011, Projet tuto 2011 or its affiliates.</div>
	</body>
</html>";
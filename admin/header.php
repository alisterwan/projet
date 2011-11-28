<?php
	function printFooter() {
		echo "</body></html>";
	}

	session_start();
	// On redirige l'utilisateur s'il n'est pas connecté en tant qu'administrateur
	if($_SESSION[masterpass] != 'f6793a9e6ca5356123fe0ab34bb46443894a5edf')
		header("location: ../index.php");

	// Connexion à la base de donnée
	if (!$conn = pg_connect("host=sqletud.univ-mlv.fr port=5432 dbname=mboivent_db user=mboivent password=equina4"))
		echo "<p class='error'>Connexion error.</p>";
?>

<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Administration panel</title>
		<meta name="description" content="Projet tuto">
		<meta name="author" content="JNEM">
		<link rel="stylesheet" href="stylesheet.css">
	</head>
	<body>
		<div id="nav">
			<div>Administrateur</div>
	
			<div><a href='./customers.php'>Customer's list</a></div>
			<div><a href='../logout.php'>Log out</a></div>
		</div>

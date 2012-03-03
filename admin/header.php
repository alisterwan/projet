<?php 
	
include('../includes/config.php');
include('query_admin.php');
	

	
	// On redirige l'utilisateur s'il n'est pas connecté en tant qu'administrateur
	if($_SESSION['masterpass'] != 'f6793a9e6ca5356123fe0ab34bb46443894a5edf')
		header("location: ../index.php");

?>

<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Administration panel</title>
		<meta name="description" content="Projet tuto">
		<meta name="author" content="JNEO">
		<link rel="stylesheet" href="stylesheet.css">
		
		<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js'></script>
		<script src='../js/modernizr.custom.65662.js'></script>
		<script src='../js-webshim/minified/polyfiller.js'></script>
		<script>$.webshims.polyfill();</script>
		
		<script>
	  function removeIngredientsOnRecipe(e, id_ing, id_rec) {
      var a, url, x;
      e.preventDefault();
      a = e.target.parentNode;
      a.parentNode.hidden = true;
      url = '../removeIngredientsOnRecipe.php?id_ing=' + id_ing + '&id_rec=' + id_rec;
      x = new XMLHttpRequest();
      x.open('GET', url, true);
      x.onload = function(e) {
        a.innerHTML = this.responseText;
        if(this.responseText !== 'success') {
          a.innerHTML = this.responseText;
          a.parentNode.hidden = false;
        }
      };
      x.send();
    }
	</script>
	
	</head>
	<body>
		<div id="nav">
			<div>Administrator</div>
	
			<div><a href='./index.php?mode=customers'>Customer's list</a></div>
			<div><a href='./index.php?mode=recipes'>Recipe's list</a></div>
			<div><a href='./index.php?mode=ingredients'>Ingredients list</a></div>
			<div><a href='../logout.php'>Log out</a></div>
		</div>

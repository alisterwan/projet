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



///////// MODULE LOCALIZATION /////////

function i8n($string, $table, $id) {
	$language = "en"; //get user's language
	$query  = "SELECT ".$string."_en,".$string."_".$language." FROM $table WHERE id='$id'";
	$result = mysql_query($query);
	if ($language !== "en" and $result[1] != null) {
		return $result[1];
	} else {
		return $result[0];
	}
}

///// FIN MODULE LOCALIZATION /////////



function printDocument($title = 'DigEat') {
	global $message, $html, $friend, $notifications;
	$title = $title == 'DigEat' ? $title : "DigEat - $title";
	echo "
		<!doctype html>
		<html lang='en'>
		<head>
			<meta charset='utf-8'>
			<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'>
			<title>$title</title>
			<meta name='description' content='Projet tuto'>
			<meta name='author' content='John Olivier Equina Nicolas'>
			<link href='http://fonts.googleapis.com/css?family=Clara' rel='stylesheet'>
			<link rel='stylesheet' type='text/css' media='screen' href='css/all-examples.css'>
			<link href='./css/stylesheet.css' rel='stylesheet'>

			<script src='js/MooTools/mootools.js'></script>
			<script src='js/MenuMatic/MenuMatic.js'></script>
			<link rel='stylesheet' type='text/css' href='js/MenuMatic/MenuMatic_myNavigationMenu.css'>

			<script src='jsdate/TSWBrowserDetect.js'></script>
			<script src='jsdate/TSWUtils.js'></script>
			<script src='jsdate/TSWDateAndTime.js'></script>
			<script src='jsdate/TSWFormCalendar.js'></script>
			<link rel='stylesheet' type='text/css' href='jsdate/TSWFormCalendar_myFormCalendar.css'>

			<script type='text/javascript' src='js/jquery.js'></script>

			<script src='js/TSWAccordion.js'></script>
			<script src='js/TSWDomUtils.js'></script>
			<link rel='stylesheet' type='text/css' href='js/TSWAccordion.css' />

			<script src='js/stack-1.js'></script>

			<script src='js/fisheye-iutil.min.js'></script>
			<script src='js/dock-example1.js'></script>

			<script src='js/modernizr.custom.65662.js'></script>
			<script src='js-webshim/minified/polyfiller.js'></script>
			<script>$.webshims.polyfill();</script>

			<script>
				function updateNotifications() {
					var x = new XMLHttpRequest();
					url = './notifications.php';
					x.open('GET', url, true);
					x.onload = function() {
						var d = document;
						n = d.getElementById('notifications');
						n.innerHTML = this.responseText;
						n.previousElementSibling.firstChild.textContent = n.firstChild.childElementCount;
					};
					x.send();
					setTimeout(updateNotifications, 10000);
				}
			</script>
		</head>
		<body>
			<header>
				<div id='header'>
					<a href='./index.php' id='logo'>DigEat</a>
					".navContent()."
					<input id='notif' type='checkbox' hidden>
					<label for='notif'><span>0</span></label>
					<div id='notifications'></div>
					<script>updateNotifications()</script>
				</div>
			</header>
			<div id='body' class='clearfix'>";

	if (function_exists('leftboxContent')) {
		// Displays leftBox if exists
		echo "<div id='leftbox' class='panel'>".leftboxContent()."</div>";
	}

	echo "<div id='content' class='panel'> $message $html $friend $notifications </div>";

	if (function_exists('rightboxContent')) {
		// Displays rightBox if exists
		echo "<div id='rightbox' class='panel'>".rightboxContent()."</div>";
	}

	echo '</div></body></html>';
}


function navContent() {
    if (isset($_SESSION['id'])) { // ATTENTION IL FAUT METTRE LES QUOTES POUR id !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
      // Requête qui récupère toutes les coordonnées du client

      $content = "

      	 <nav>
   	<ul id='myNavigationMenu'>
		<li><a href='./index.php'>Home</a></li>
		<li><a href='./profile.php'>Profile</a>
			<ul>
				<li><a href='#'>Edit Profile</a>
					<ul>
						<li><a href='edit_profile.php'>Account information</a></li>
						<li><a href='information.php'>Personal information</a></li>
					</ul>
				</li>
				<li><a href='#'>Objectives</a>
					<ul>
						<li><a href='objectivesform.php'>Set Objectives</a></li>
						<li><a href='graphique.php'>View My Chart</a></li>
					</ul>
				</li>
				<li><a href='./logout.php'>Log out</a></li>
			</ul>
		</li>
				<li><a href='#'>Community</a></li>
		<li><a href='./search_advanced.php'>Search</a></li>
	</ul>
	<!-- Create a MenuMatic Instance -->
	<script type='text/javascript' >
		window.addEvent('load', function() {
			var myMenu = new MenuMatic({
				id: 'myNavigationMenu',
				subMenusContainerId: 'myNavigationMenu_menuContainer',
				orientation: 'horizontal',
				effect: 'slide & fade',
				duration: 800,
				hideDelay: 1000,
				opacity: 100});
		});
	</script>

        </nav>";
    }else{
	$content = "
	<nav>
	<ul id='myNavigationMenu'>
		<li><a href='./index.php'>Home</a></li>
		<li><a href='#'>Recipes</a></li>
		<li><a href='#'>Community</a></li>
		<li><a href='./registration.php'>Registration</a></li>
		<li><a href='./search_advanced.php'>Search</a></li>
	</ul>
	<!-- Create a MenuMatic Instance -->
	<script type='text/javascript' >
		window.addEvent('load', function() {
			var myMenu = new MenuMatic({
				id: 'myNavigationMenu',
				subMenusContainerId: 'myNavigationMenu_menuContainer',
				orientation: 'horizontal',
				effect: 'slide & fade',
				duration: 800,
				hideDelay: 1000,
				opacity: 100});
		});
	</script>
	 </nav>";
    }

    return $content;
}
?>

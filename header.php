<?php

include("includes/config.php");
include("query_functions.php");



///// MODULE CONNEXION /////
if ($_POST && isset($_GET['mode']) && $_GET['mode'] == 'logon') {
	// ATTENTION IL FAUT METTRE LES QUOTES POUR name
	$username = $_POST['name'];
	// ATTENTION IL FAUT METTRE LES QUOTES POUR pass
	$pass = sha1($_POST['pass']);


	// Rediriger l'admin s'il est correctement identifié
	if ($username == 'admin' && $pass == 'f6793a9e6ca5356123fe0ab34bb46443894a5edf') {
		// ATTENTION IL FAUT METTRE LES QUOTES POUR masterpass
		$_SESSION['masterpass'] = $pass;
		// redirection
		header('Location: admin/index.php');
	}


	// Vérification du client dans la base de donnée
	$query  = "SELECT id,username FROM users WHERE username='$username' and password='$pass'";
	$result = mysql_query($query);

	if (mysql_num_rows($result) == 1) {
		 $row = mysql_fetch_array($result, MYSQL_NUM);
		 $_SESSION['id']   = $row[0]; // ATTENTION IL FAUT METTRE LES QUOTES
		 $_SESSION['mail'] = $row[1]; // ATTENTION IL FAUT METTRE LES QUOTES
	} else {
		$message = '<p class=error>Username or password incorrect, try again.</p>';
	}
}
///// FIN MODULE CONNEXION /////



//// DEFINIT LA VARIABLE GLOBALE $userid
if (isset($_SESSION['id'])) {
	$userid = $_SESSION['id'];
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



function printDocument($title) {
	global $message, $html;
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
			<link href='./css/stylesheet.css' rel='stylesheet'>
			<script type='text/javascript' src='js/MooTools/mootools.js'></script>
			<script type='text/javascript' src='js/MenuMatic/MenuMatic.js'></script>
			<link rel='stylesheet' type='text/css' href='js/MenuMatic/MenuMatic_myNavigationMenu.css' />
			<script type='text/javascript' src='jsdate/TSWBrowserDetect.js'></script>
			<script type='text/javascript' src='jsdate/TSWUtils.js'></script>
			<script type='text/javascript' src='jsdate/TSWDateAndTime.js'></script>
			<script type='text/javascript' src='jsdate/TSWFormCalendar.js'></script>
			<link rel='stylesheet' type='text/css' href='jsdate/TSWFormCalendar_myFormCalendar.css' />
			<script type='text/javascript' src='js/jquery.js'></script>
			<script type='text/javascript' src='js/TSWAccordion.js'></script>
			<script type='text/javascript' src='js/TSWDomUtils.js'></script>
			<link rel='stylesheet' type='text/css' href='js/TSWAccordion.css' />
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
				<div id='leftbox' class='panel'>".leftboxContent()."</div>
				<div id='content' class='panel'>$message$html</div>
				<div id='rightbox' class='panel'>".rightboxContent()."</div>
			</div>
		</body>
	</html>";
}



function navContent() {
	// ATTENTION IL FAUT METTRE LES QUOTES POUR id
	if (isset($_SESSION['id'])) {
		// Requête qui récupère toutes les coordonnées du client
		$content = "
		<nav>
			<ul id='myNavigationMenu'>
				<li><a href='./index.php'>Home</a></li>
				<li>
					<a href='./profile.php'>Profile</a>
					<ul>
						<li>
							<a href='#'>Edit Profile</a>
							<ul>
								<li><a href='edit_profile.php'>Account information</a></li>
								<li><a href='modify_info.php'>Personal information</a></li>
							</ul>
						</li>
						<li><a href='./logout.php'>Log out</a></li>
					</ul>
				</li>
				<li>
					<a href='#'>Recipes</a>
					<ul>
						<li><a href='./recipes.php'>My Recipes</a></li>
						<li><a href='#'>Feeds</a></li>
					</ul>
				</li>
				<li><a href='#'>Community</a></li>
				<li><a href='./search_advanced.php'>Search</a></li>
			</ul>
			<!-- Create a MenuMatic Instance -->
			<script>
				window.addEventListener('load', function() {
					var myMenu = new MenuMatic({
						id: 'myNavigationMenu',
						subMenusContainerId: 'myNavigationMenu_menuContainer',
						orientation: 'horizontal',
						effect: 'slide & fade',
						duration: 800,
						hideDelay: 1000,
						opacity: 100
					});
				});
			</script>
		</nav>";
	} else {
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
				<script>
					window.addEventListener('load', function() {
						var myMenu = new MenuMatic({
							id: 'myNavigationMenu',
							subMenusContainerId: 'myNavigationMenu_menuContainer',
							orientation: 'horizontal',
							effect: 'slide & fade',
							duration: 800,
							hideDelay: 1000,
							opacity: 100
						});
					});
				</script>
			</nav>";
	}
	return $content;
}



function leftboxContent() {
	if (isset($_SESSION['id'])) {

		// WTF
		// Pourquoi est-ce qu'on fait trois fois la même chose ??????

		if (isset($_GET['id_user'])) {
			// Requête qui récupère toutes les coordonnées du client
			$userinfos = retrieve_user_infos($_GET['id_user']);
			$content   = "<img src='$userinfos[avatar]' style='width: 170px; height: 200px;'>";
		} else {
			// Requête qui récupère toutes les coordonnées du client
			global $userid;
			$userinfos = retrieve_user_infos($userid);
			$content   = "
			<img src='$userinfos[avatar]' style='width: 170px; height: 200px;'>
			<a href='./image.php'>Change my avatar</a><br>";
		}
	} else if (isset($_GET['id_user'])) {
		// Requête qui récupère toutes les coordonnées du client
		$userinfos = retrieve_user_infos($_GET['id_user']);
		$content   = "<img src='$userinfos[avatar]' style='width: 170px; height: 200px;'>";
	}
	return $content;
}



function rightboxContent() {
	// ATTENTION IL FAUT METTRE LES QUOTES POUR id
	if (isset($_SESSION['id'])) {
		// Requête qui récupère toutes les coordonnées du client
		global $userid;

		$userinfos = retrieve_user_infos($userid);

		$content = '
		<div>
			<img src="./img/templates/friends.png"       style="width: 50px; height: 50px;" title=Friends>
			<img src="./img/templates/messages.png"      style="width: 50px; height: 50px;" title=Messages>
			<img src="./img/templates/notifications.png" style="width: 50px; height: 50px;" title=Notifications>
		</div>
		<p>Your account information:</p>
		<div>'.$userinfos['firstname'].'&nbsp;'.$userinfos['surname'].'</div>
		<div>'.$userinfos['address'].'</div>
		<div>'.$userinfos['city'].'</div>
		<div>'.$userinfos['country'].'</div>
		<div>'.$userinfos['mail'].'</div>
		<ul id="myNavigationMenu">
			<li>
				<a href="./profile.php">My Datas</a>
				<ul>
					<li>
						<a href="#">Objectives</a>
						<ul>
							<li><a href="objectivesform.php">Set Objectives</a></li>
							<li><a href="graphique.php">View My Chart</a></li>
						</ul>
					</li>
					<li><a href="./information.php">My Information</a></li>
					<li><a href="./albums.php">My Albums</a></li>
					<li><a href="./friends.php">Friends</a></li>
					<li><a href="./recipes.php">My Recipes</a></li>
				</ul>
			</li>
		</ul>
		<script>
			window.addEventListener("load", function() {
				var myMenu = new MenuMatic({
					id: "myNavigationMenu",
					subMenusContainerId: "myNavigationMenu_menuContainer",
					orientation: "horizontal",
					effect: "slide & fade",
					duration: 800,
					hideDelay: 1000,
					opacity: 100
				});
			});
		</script>';
	} else {
		// Afficher les formulaires pour se connecter et s'inscrire
		$content = "
		<form action='./index.php?mode=logon' method='post'>
			<img src='./img/templates/login.png'>
			<div><input type='text' name='name' placeholder='Username' required></div>
			<div><input type='password' name='pass' placeholder='password' required></div>
			<div><input type='submit' value='Submit'> <a href='./#'>Forgot your password?</a></div>
		</form>";
	}
	return $content;
}

?>

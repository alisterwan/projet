<?php
	session_start();

	// Connexion à la base de donnée
	if (!$conn = pg_connect("host=sqletud.univ-mlv.fr port=5432 dbname=mboivent_db user=mboivent password=equina4"))
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
		<meta name='description' content='Projet tuto'>
		<meta name='author' content='John Marie Equina Nicolas'>
		<link rel='stylesheet' href='stylesheet.css'>
	</head>
	<body>
		<div id='header'>
			
	<a href='./index.php'> <img src='./img/digeat.png' id='logo'>	</a>
		
		</div>
		<div id='leftbox'></div>
		<div id='rightbox'>
		
 	<div id='rightboxchild'> 
 		
 		<table>
 		<form action='./index.php' method='post'>
 		<tr>
			<td> <span> Username : </span> </td>
			<td> <input type='text' name='username' value='' required> </td>
		</tr>
		<tr>
			<td> <span>password : </span> </td>
			<td> <input type='password' name='password' value='' required> </td>
		</tr>
			<tr><td><a href='./#'>Forgot your password? </a> </td></tr>
		<tr>
			<td> <input type='image' src='./img/login.png' align='middle' name='proceed' value='submit'></td>
		</tr>
		</form>
 		</table>
 	</div>
	
	<div id='rightboxchild'>

	<table>
<form action='./registration.php' method='post'>
<tr>
<td> <span>Firstname : </span> </td>
<td> <input type='text' name='firstname' value='' required> </td>
</tr>
<tr>
<td> <span>Surname : </span> </td>
<td> <input type='text' name='surname' value='' required> </td>
</tr>
<tr><td></td></tr>
<tr>
<td> <span>email : </span> </td>
<td> <input type='text' name='email' value='' required> </td>
</tr>

<tr>
<td> <input type='image' src='./img/signup.png' align='middle' name='proceed' value='submit'> </td>
</tr>
</form>
</table>
	</div>
	
	
	
		</div>
		
		<div id='body'>";}
?>		
	
<?php	// Affichage du pied de la page.
	function printFooter() {
		echo "
		</div>
	</body>
</html>";
	}

?>

<?php
	include './header.php';
	printHeader('Home Page');
	

// Rediriger l'admin s'il est correctement identifié
	if ($_POST[username] == admin && sha1($_POST[password]) == 'f6793a9e6ca5356123fe0ab34bb46443894a5edf') {
		$_SESSION[name] = Admininistrator;
		$_SESSION[masterpass] = 'f6793a9e6ca5356123fe0ab34bb46443894a5edf';
		echo "<script language='Javascript'>
<!--
document.location.replace('http://etudiant.univ-mlv.fr/~jwankutk/tuto/admin/index.php');
// -->
</script>";
	}
	
	else

if ($_POST) {
		$user = $_POST[username];
		$pass = sha1($_POST[password]);

		// Vérification du client dans la base de donnée
		if (pg_num_rows(pg_query($conn,"SELECT firstname,surname,address,city,country,username,password,mail,id_customer FROM users WHERE username='$user' and password='$pass'"))) {
			echo "<p>You are successfully logged in. Welcome <a href='./account.php'>$user</a>.</p>";
			$_SESSION[name] = $user;
		}

		else {
			echo "<p class='error'>Username or password incorrect, try again.</p>";
		
		}

}


?>




<?php printFooter();?>
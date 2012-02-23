<?php
	include './header.php';
	printHeader('Your account overview');

	// Formulaire qui affiche les données du client connecté.
	function printForm($customer) {
	echo "
	<div>
	<p>Your account information:</p>
	<div> $customer[0] $customer[1] </div>
	<div> $customer[2] </div>
	<div> $customer[3] </div>
	<div> $customer[4] </div>
	<div> $customer[5] </div>

	<a href='./#'>Modify my account</a>
	</div>";
	}

	// Requête qui récupère toutes les coordonnées du client
	$customer = pg_fetch_row(pg_query($conn,"SELECT firstname,surname,address,city,country,mail from users where username='$_SESSION[name]'"));


	printForm($customer);


	$c = pg_fetch_row(pg_query($conn,"SELECT id_customer FROM users WHERE username='$_SESSION[name]'"));


	echo "</p>

	</div>";


	printFooter();
?>

<?php
	include './header.php';

	function form($conn,$id) {
		$query = pg_query($conn,"SELECT firstname,surname,address,city,country,username,password,mail,id_customer FROM users WHERE id_customer=$id");
		$customer = pg_fetch_row($query);
		echo "<form action='./managecustomers.php' method='post'>
			<p>Delete or update its details.</p>
			<div><input type='text' name='firstname' value='$customer[0]' required> Firstname</div>
			<div><input type='text' name='surname' value='$customer[1]' required> Surname</div>
			<div><input type='text' name='username' value='$customer[5]' required> Username</div>
			<div><input type='text' name='address' value='$customer[2]' required> Address</div>
			<div><input type='text' name='city' value='$customer[3]' required> City</div>
			<div><input type='text' name='country' value='$customer[4]' required> Country</div>
			<div><input type='text' name='mail' value='$customer[7]' required> Email</div>
			<div>
				<button type='submit' name='update' value='$id'>Update</button>
				<button type='submit' name='delete' value='$id'>Delete</button>
			</div>
			</form>";
	}

	// On affiche une liste des clients
	echo "<p>Select a customer in the drop-down list.</p>";

	echo "<form action='./customers.php' method='post'>
		<button type='submit' name='select' value='customer'>Select</button>
		<select name='choice'>";
	$query = pg_query($conn,"SELECT id_customer, firstname, surname, username FROM users");
	while ($customer = pg_fetch_row($query))
		echo "<option value='$customer[0]'>$customer[1] $customer[2] ($customer[3])</option>";
	echo "</select></form>";

	// On affiche les données du client à la selection
	if ($_POST[select]) {
		$id = $_POST[choice];
		form($conn,$id);
	}
	// On met à jour ses données
	else if ($id = $_POST[update]) {
		$query = pg_query($conn,
			"UPDATE
				users
			SET
				firstname = '$_POST[firstname]',
				surname   = '$_POST[surname]',
				address   = '$_POST[address]',
				city      = '$_POST[city]',
				country   = '$_POST[country]',
				username  = '$_POST[username]',
				mail      = '$_POST[mail]'
			WHERE
				id_customer=$id;"
		);
		if ($query)
			echo "<p>Successful update.</p>";
		else {
			echo "<p class='error'>Query error.</p>";
			form($conn,$id);
		}
	}
	// On supprime le client de la base de donnée
	else if ($id = $_POST[delete]) {
		pg_query($conn,"DELETE FROM users WHERE id_customer=$id;");
	}

	printFooter();
?>

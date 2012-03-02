<?php
	include './header.php';

	function form($id) {
		$query = "SELECT * FROM users WHERE id=$id";
		
		$result = mysql_query($query);
		
		$customer = mysql_fetch_assoc($result);
		echo "<form action='./managecustomers.php' method='post'>
			<p>You can now delete or update details.</p>
			<div><input type='text' name='firstname' value='$customer[firstname]' required> Firstname</div>
			<div><input type='text' name='surname' value='$customer[surname]' required> Surname</div>
			<div><input type='text' name='username' value='$customer[username]' required> Username</div>
			<div><input type='text' name='address' value='$customer[address]' required> Address</div>
			<div><input type='text' name='country' value='$customer[country]' required> Country</div>
			<div><input type='text' name='mail' value='$customer[mail]' required> email</div>
			<div>
				<button type='submit' name='update' value='$id'>Update</button>
				<button type='submit' name='delete' value='$id'>Delete</button>
			</div>
			</form>";
	}

	// On affiche une liste des clients
	
	echo "<form action='./index.php?' method='post'>
		<button type='submit' name='select' value='customer'>Select</button>
		<select name='choice'>";
	$query = "SELECT id, firstname, surname, username FROM users";
	while ($customer = mysql_fetch_row($query))
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

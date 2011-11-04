<?php
	include './header.php';
	printHeader('Your account overview');

	function form($conn,$id) {
		$query = pg_query($conn,"SELECT firstname,surname,address,city,country,username,password,mail,id_customer FROM users WHERE id_customer=$id");
		$customer = pg_fetch_row($query);
			
		echo "
			<p>Modify your account : </p>
			<form action='./modifyaccount.php' method='post'>			
			<div><input type='text' name='firstname' value='$customer[0]' required> Firstname</div>
			<div><input type='text' name='surname' value='$customer[1]' required> Surname</div>
			<div><input type='text' name='username' value='$customer[5]' required> Username</div>
			<div><input type='text' name='address' value='$customer[2]' required> Address</div>
			<div><input type='text' name='city' value='$customer[3]' required> City</div>
			<div><input type='text' name='country' value='$customer[4]' required> Country</div>
			<div><input type='text' name='mail' value='$customer[7]' required> Email</div>
			<div><button type='submit' name='update' value='$id'>Update</button></div>
			</form>";
	}

	$name = $_SESSION[name];
	$query = pg_query($conn,"SELECT id_customer FROM users where username='$name'");
	while ($customer = pg_fetch_row($query))
	
	$id = $customer[0];
	form($conn,$id);
	
	// On met à jour ses données
	
	if ($id = $_POST[update]) {
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

	printFooter();
?>

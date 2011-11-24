<?php
	include './header.php';

	$html = "<h1>$user[0] $user[1] ($user[6])</h1>
		 <img src='$user[9]'/>	
	";

	printDocument('Profile');
?>

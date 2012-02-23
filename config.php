<?php

session_start();

/************************
	CONSTANTS
/************************/
define("HOST", "sqletud.univ-mlv.fr");
define("USER", "eyou01");
define("PASSWORD", "equina4");
define("DB", "eyou01_db");

/************************
	FUNCTIONS
/************************/
function connect($db, $user, $password) {
	$link = @mysql_connect($db, $user, $password);
	if ($link) {
		$db = mysql_select_db(DB);
		if($db) {
			return $link;
		} else {
			die("Could not select database: ".mysql_error());
		}
	} else {
		die("Could not connect: ".mysql_error());
	}
}

?>

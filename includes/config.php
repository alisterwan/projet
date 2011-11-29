<?php

  session_start();
  
/************************
	CONSTANTS
/************************/
define("HOST", "localhost");  
define("USER", "root");
define("PASSWORD", "root");  
define("DB", "project");

/************************
	FUNCTIONS
/************************/
function connect($db, $user, $password){
	$link = @mysql_connect($db, $user, $password);
	if (!$link)
	    die("Could not connect: ".mysql_error());
	else{
		$db = mysql_select_db(DB);
		if(!$db)
		die("Could not select database: ".mysql_error());
		else return $link;
	}
}

$conn = connect(HOST,USER,PASSWORD);

// Définir le jeu de caractères
mysql_set_charset($conn, 'utf8');

?>
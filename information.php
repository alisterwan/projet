<?php

include './header.php';

/////////////////////// FONCTIONS
function printInfo($userid) {
$info = good_query_assoc("SELECT * FROM information WHERE id_user = $userid");

	if (!$info){
	
		return $html="You haven't got information
		<p align='right'><small><a href='./add_info.php'>Add information</a></small></p>
		";
	}else {
		
		
		return $html="
		Born: $info[date_birth]
		<br/>Hobbies: $info[hobbies]
		<br/>Job: $info[job]
		<br/>Music: $info[music]
		<br/>Films: $info[films]
		<br/>Books: $info[books]
		<br/>About me: $info[aboutme]
		<br/>Favourite food: $info[favouritefood]
		<p align='right'><small><a href='./modify_info.php'>Edit information</a></small></p>
		";

	  }
 }
 
 
/////////////////////// FONCTIONS
 
 
 if (isset($userid)){ // vérification si logué ou pas
 
	$html ="
	<h1>Your information</h1>
	".printInfo($userid)."
	
	";

	printDocument("Your information");
	
}else{
	header('Location: index.php');
}
?>
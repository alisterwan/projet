<?php

include './header.php';


function printInfo() {
$info = good_query_assoc("SELECT * FROM information WHERE id_user = $user[id]'");

	if (!$info){
	echo "You haven't got information";
	}

	else {
	echo "Hobbies: $info[hobbies]";
	echo "Job: $info[job]";
	echo "Music: $info[music]";
	echo "Films: $info[films]";
	echo "Books: $info[books]";
	echo "About me: $info[aboutme]";
	echo "Favourite food: $info[favouritedish]";
	  }
  }
$html ="
<h1>Your information</h1>
".printInfo()."
<p align='right'><small><a href='./modify_info.php'>Modify my information</a></small></p>
";

printDocument("Your information");
?>
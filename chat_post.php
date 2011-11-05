<?php
include './header.php';

if (!pg_query($conn,"INSERT INTO chat(message,id_cust) VALUES ('$_POST[message]','$var_id')")) {
				echo "<p class='error'>Query error.</p>";
			}

// Effectuer ici la requête qui insère le message
// Puis rediriger vers chat.php comme ceci :
	echo "<script language='Javascript'>
<!--
document.location.replace('http://etudiant.univ-mlv.fr/~jwankutk/tuto/chat.php');
// -->
</script>";
?>
<?php
	session_start();
	session_unregister(name);
	session_unregister(masterpass);
	echo "<script language='Javascript'>
	<!--
	document.location.replace('http://etudiant.univ-mlv.fr/~jwankutk/tuto/index.php');
	// -->
	</script>";
?>

<?php
  include '../header.php';
  header('Content-type: text/plain');
	
	//efface un album
	$sql = "DELETE from albums WHERE id='$_GET[id]'";
		$query = @mysql_query($sql);

		if(!$query) die("Error: ".mysql_error());
		echo "success";
?>

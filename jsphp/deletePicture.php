<?php
  include '../header.php';
  header('Content-type: text/plain');
	//delete photo from album
  	$sql = "DELETE from albums_photos WHERE id='$_GET[id]'";
		$query = @mysql_query($sql);

		if(!$query) die("Error: ".mysql_error());
		echo "success";
?>

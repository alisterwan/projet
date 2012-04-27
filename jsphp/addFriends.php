<?php

include '../header.php';

header('Content-type: text/plain');


$query  = "SELECT id FROM groups	WHERE id_creator='$_GET[id_user]' AND name='Friends'";
$result = mysql_query($query);

if ($result) {
	$row =	mysql_fetch_assoc($result);
}


$query  = "INSERT INTO groups_relations(id_group,id_user,approval,status) VALUES ($row[id],$_GET[id_friend],0,0)";
$result = mysql_query($query);

if ($result) {
	echo "Friend request sent";
} else {
	die("Error: ".mysql_error());
}

?>

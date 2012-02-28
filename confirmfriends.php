<?php

include './header.php';

header('Content-type: text/plain');


// Met à jour dans un sens la requête.
$query  = sprintf("UPDATE groups_relations SET approval='1', status='1' WHERE id_group='$_GET[idgroup]' AND id_user='$_GET[id_user]'");
$result = @mysql_query($query);

if (!$result) {
    die("Error: ".mysql_error());
}


// on choisit l'ID user.
$query  = "SELECT id_creator FROM groups WHERE id='$_GET[idgroup]' AND name='Friends'";
$result = mysql_query($query);

if ($result) {
    $row = mysql_fetch_assoc($result);
}


$query  = "SELECT id FROM groups WHERE id_creator='$_GET[id_user]' AND name='Friends'";
$result = mysql_query($query);

if ($result) {
    $row2 =	mysql_fetch_assoc($result);
}


$userinfos = retrieve_user_infos($row['id_creator']);


$query  = "INSERT INTO groups_relations(id_group,id_user,approval,status) VALUES ($row2[id],$row[id_creator],1,1)";
$result = @mysql_query($query);

if (!$result) {
    die("Error: Query 2".mysql_error());
}

echo "You are now friend with $userinfos[firstname] $userinfos[surname]($userinfos[username])";

?>

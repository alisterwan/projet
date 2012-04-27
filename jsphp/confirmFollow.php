<?php

include '../header.php';

header('Content-type: text/plain');


// Met à jour dans un sens la requête.
$query  = sprintf("UPDATE groups_relations SET approval='1', status='1' WHERE id_group='$_GET[idgroup]' AND id_user='$_GET[id_user]'");
$result = @mysql_query($query);

if (!$result) {
    die("Error: ".mysql_error());
}


echo "New subscriber";

?>

<?php

include '../header.php';

header('Content-type: text/plain');


// Met ˆ jour dans un sens la requte.
$query  = sprintf("UPDATE wall_post_comment SET status='1' WHERE id_wall_post='$_GET[idwall]' AND id_poster='$_GET[id_user]'");
$result = @mysql_query($query);

if (!$result) {
    die("Error: ".mysql_error());
}

echo "success";

?>
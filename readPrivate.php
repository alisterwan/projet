<?php

include './header.php';

header('Content-type: text/plain');


// Met ˆ jour dans un sens la requte.
$query  = sprintf("UPDATE private_messages SET status='1' WHERE id='$_GET[id]' AND id_recipient='$_GET[id_user]'");
$result = @mysql_query($query);

if (!$result) {
    die("Error: ".mysql_error());
}

?>
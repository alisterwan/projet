<?php

include '../tuto_john/header.php';

$ingredient = htmlentities(addslashes($_POST['p']));
if (!empty($ingredient)) {
$res = mysql_query("select name_en from ingredients where name_en='".$ingredient."'");
echo (($row = mysql_fetch_array($res)) ? 'FAIL' : 'OK');
}

?>

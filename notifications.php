<?php

include './header.php';
header('Content-type: text/plain');

$query  = "SELECT * FROM groups_relations WHERE id_user='$userid' AND status='0'";
$result = mysql_query($query);

if (!$result) {
	die("Error: ".mysql_error());
}

echo "<ol>";
while ($row = mysql_fetch_assoc($result)) {
	echo "<li><span class=id_group>$row[id_group]</span><span class=id_user>$row[id_user]</span><span class=approval>$row[approval]</span></li>";
}
echo "</ol>";

?>

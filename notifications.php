<?php

include './header.php';
header('Content-type: text/plain');

echo '<ol>';

$query  = sprintf("SELECT * FROM groups_relations WHERE id_user='%s' AND status='0'",
	mysql_real_escape_string($userid));
$result = mysql_query($query);


while ($row = mysql_fetch_assoc($result)) {
	$query1    = "SELECT id_creator FROM groups WHERE id=$row[id_group]";
	$response1 = mysql_query($query1);

	while ($row1 = mysql_fetch_assoc($response1)) {
		$query2    = "SELECT * FROM users WHERE id=$row1[id_creator]";
		$response2 = mysql_query($query2);

		while ($friend = mysql_fetch_assoc($response2)) {
			echo "<li><a href='profile.php?id_user=$friend[id]'>$friend[username]</a><img src='$friend[avatar]' style='height: 20px; width: 20px;'> wants to be your friend.
				<a href='#' onclick='confirmFriends(event,$row[id_group],$userid)'>Accept</a>
				<a href='#' onclick='cancelFriends(event,$row[id_group],$userid)'>Decline</a>
			</li>";
		}
	}
}


$query  = sprintf("SELECT id_group FROM groups_relations WHERE id_user='%s' AND status='0'",
	mysql_real_escape_string($userid));
$result = mysql_query($query);

while ($row = mysql_fetch_assoc($result)) {
	$query1    = "SELECT id_creator FROM groups WHERE id=$row[id_group]";
	$response1 = mysql_query($query1);

	while ($row1 = mysql_fetch_assoc($response1)) {
		$query2    = "SELECT * FROM users WHERE id=$row1[id_creator]";
		$response2 = mysql_query($query2);

		while ($friend = mysql_fetch_assoc($response2)) {
			echo "<li><a href='profile.php?id_user=$friend[id]'>$friend[username]</a><img src='$friend[avatar]' style='height: 20px; width: 20px;'> is following you.
				<a href='#' onclick='confirmFollow(event,$row[id_group],$userid)'>OK</a>
			</li>";
		}
	}
}


echo '</ol>';

?>

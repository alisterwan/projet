<?php

include './header.php';
header('Content-type: text/plain');

echo '<ol>';

$query  = sprintf("SELECT * FROM private_messages WHERE id_recipient='%s' AND status='0'",
	mysql_real_escape_string($userid));
$result = mysql_query($query);


	while ($row1 = mysql_fetch_assoc($result)) {
		$query2    = "SELECT * FROM users WHERE id=$row1[id_sender]";
		$response2 = mysql_query($query2);

		while ($friend = mysql_fetch_assoc($response2)) {
			echo "<li><a href='profile.php?id_user=$friend[id]'>$friend[username]</a><img src='$friend[avatar]' style='height: 20px; width: 20px;'> wrote you a private message.
				<a href='#' onclick='readPrivate(event,$row1[id],$userid)'>See</a>
			</li>";
		}
}


$query  = sprintf("SELECT * FROM groups_relations WHERE id_user='%s' AND approval='0' AND status='0'",
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


$query  = sprintf("SELECT id_group FROM groups_relations WHERE id_user='%s' AND approval='1' AND status='0'",
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

$query  = sprintf("SELECT * FROM wall_post WHERE id_user='%s'",
	mysql_real_escape_string($userid));
$result = mysql_query($query);


while ($row = mysql_fetch_assoc($result)) {
	$query1    = "SELECT * FROM wall_post_comment WHERE id_wall_post=$row[id] AND status='0'";
	$response1 = mysql_query($query1);

	while ($row1 = mysql_fetch_assoc($response1)) {
		$query2    = "SELECT * FROM users WHERE id=$row1[id_poster]";
		$response2 = mysql_query($query2);

		while ($friend = mysql_fetch_assoc($response2)) {
			echo "<li><a href='profile.php?id_user=$friend[id]'>$friend[username]</a><img src='$friend[avatar]' style='height: 20px; width: 20px;'> commented on your wall post.
				<a href='#' onclick='readComment(event,$row[id],$friend[id])'>See</a>

			</li>";
		}
	}
}

echo '</ol>';

?>

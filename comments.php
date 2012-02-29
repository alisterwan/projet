<?php

include './header.php';
header('Content-type: text/plain');

echo '<ol>';

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
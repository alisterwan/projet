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
/*	echo "<li><span class=id_group>$row[id_group]</span><span class=id_user>$row[id_user]</span><span class=approval>$row[approval]</span></li>";*/


/**************Friends Request****************************/ 
    
    //selection des id_group reliees au user
	$query = sprintf("SELECT id_group FROM groups_relations WHERE id_user='%s' AND approval='0'",
	mysql_real_escape_string($userid)); 	
	$result = mysql_query($query);	
	
	while($row=mysql_fetch_assoc($result)) {
   	$query1 = "SELECT id_creator FROM groups WHERE id=$row[id_group]";
	$response = mysql_query($query1);
	
	while($row1 = mysql_fetch_assoc($response)){
	$query2 = "SELECT * FROM users WHERE id=$row1[id_creator]";
	$response2 = mysql_query($query2);
	
	while($friend = mysql_fetch_assoc($response2)){	
	echo "<li>
	<a href='profile.php?id_user=$friend[id]'>$friend[username]<img src='$friend[avatar]' width='100px height='100px''/></a> wants to be your friend 
	
	<a href='#' onclick='confirmFriends(event,$row[id_group],$userid)'>Accept</a> 
	
	<script>
	  function confirmFriends(e, idgroup, id_user, username) {
      var a, url, x;
      e.preventDefault();
      a = e.target.parentNode;
      a.parentNode.hidden = true;
      url = './confirmfriends.php?idgroup='+ idgroup +'&id_user=' + id_user;
      x = new XMLHttpRequest();
      x.open('GET', url, true);
      x.onload = function(e) {
        a.innerHTML = this.responseText;
        if(this.responseText !== 'success') {
          a.innerHTML = this.responseText;
          a.parentNode.hidden = false;
        }
      };
      x.send();
    }
	</script>

	
	<a href='#' onclick='cancelFriends(event,$row[id_group],$userid)'>Decline</a>
	
	<script>
	  function cancelFriends(e, idgroup, id_user) {
      var a, url, x;
      e.preventDefault();
      a = e.target.parentNode;
      a.parentNode.hidden = true;
      url = './cancelfriends.php?idgroup='+ idgroup +'&id_user=' + id_user;
      x = new XMLHttpRequest();
      x.open('GET', url, true);
      x.onload = function(e) {
        a.innerHTML = this.responseText;
        if(this.responseText !== 'success') {
          a.innerHTML = this.responseText;
          a.parentNode.hidden = false;
        }
      };
      x.send();
    }
	</script>
	
	</li>";	
			}
		}
	
	}
	
	
/****************************************************/

/******************Followers announcers**************/

 //selection des id_group reliees au user
	$query = sprintf("SELECT id_group FROM groups_relations WHERE id_user='%s' AND approval='1' AND status='0'",
	mysql_real_escape_string($userid)); 	
	$result = mysql_query($query);	
	
	while($row=mysql_fetch_assoc($result)) {
   	$query1 = "SELECT id_creator FROM groups WHERE id=$row[id_group]";
	$response = mysql_query($query1);
	
	while($row1 = mysql_fetch_assoc($response)){
	$query2 = "SELECT * FROM users WHERE id=$row1[id_creator]";
	$response2 = mysql_query($query2);
	
	while($friend = mysql_fetch_assoc($response2)){	
	echo "
	<li>
	<a href='profile.php?id_user=$friend[id]'>$friend[username]<img src='$friend[avatar]' width='100px height='100px'></a> is following you.
	
	<a href='#' onclick='confirmFollow(event,$row[id_group],$userid)'>Ok</a> 
	
	<script>
	  function confirmFollow(e, idgroup, id_user, username) {
      var a, url, x;
      e.preventDefault();
      a = e.target.parentNode;
      a.parentNode.hidden = true;
      url = './confirmFollow.php?idgroup='+ idgroup +'&id_user=' + id_user;
      x = new XMLHttpRequest();
      x.open('GET', url, true);
      x.onload = function(e) {
        a.innerHTML = this.responseText;
        if(this.responseText !== 'success') {
          a.innerHTML = this.responseText;
          a.parentNode.hidden = false;
        }
      };
      x.send();
    }
	</script>

	
	
	
	</li>";	
			}
		}
	
	}


/****************************************************/



}
echo "</ol>";




?>

<?php
	include 'header.php';
/**************Fonctions****************************/

/**************************************************/



if (isset($userid)){	

  $userinfos=retrieve_user_infos($userid);
  $useraddinfos=retrieve_user_add_infos($userid);
  $userfriends = retrieve_user_friends($userid);	
	
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
	$message.="
	<p class='error'>
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
	
	</p>";	
			}
		}
	
	}	
	
	
/****************************************************/	
 
 //Affichage des amis
 
 $html= "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
  
  <div id='content'>
  <div id='dock'>
		<div class='dock-container'>
			
			<a class='dock-item' href='newmessage.php'><span>Messages</span><img src='img/dock/email.png' alt='messages' /></a> 
		
			<a class='dock-item' href='groups.php'><span>Groups</span><img src='img/dock/portfolio.png' alt='history' /></a> 
		
			<a class='dock-item' href='followers.php'><span>Followers</span><img src='img/dock/link.png' alt='links' /></a> 
			<a class='dock-item' href='#'><span>RSS</span><img src='img/dock/rss.png' alt='rss' /></a> 
		
		</div>
	</div>
  	</div>
 
 <h2>My Friends</h2>
  ";
	
	$query = "SELECT id FROM groups WHERE name='friends' AND id_creator='$userid'";
    $result = mysql_query($query);
    while($row = mysql_fetch_assoc($result)){
    
    $query2 = "SELECT id_user FROM groups_relations WHERE id_group='$row[id]' AND approval='1'";
    $result2 = mysql_query($query2);
    while($row1 = mysql_fetch_assoc($result2)){
    	
    $query3 = "SELECT * FROM users WHERE id=$row1[id_user]";	
    $result3 = mysql_query($query3);
    while($row2 = mysql_fetch_assoc($result3)){
    $html.=	"
    <div><a href='./profile.php?id_user=$row2[id]'><img src='$row2[avatar]' width='100px' height='100px' alt='$row2[username]' title='$row2[username]'/><br/>$row2[firstname] $row2[surname]($row2[username])</a></div>
    ";
    		}	
    	}
 	 }
 	
 
     

 }

	printDocument('My friends');
?>

<?php
  include './header.php';
  
function getAllAlbumsbyUserId($id){
	$html ="";
	$query = "SELECT * FROM albums WHERE id_user='$id'";
	$result = mysql_query($query); 
	if(mysql_num_rows($result)==0){
	$html.= "<p class='error'>You haven't created any albums yet</p> ";
	$html.="<p><a href='create_album.php'>Create a new album</a></p>";
	}
	else{
		$html.="<h4>Your albums</h4>"; 
		while($row = mysql_fetch_assoc($result)){
		$html.=" <div><a href='./albums.php?id=$row[id]'><span>$row[name] </span></a><a href='#' onclick='removeAlbum(event,$row[id])'><img src='./img/templates/deleteing.png' width='10px' height='10px'></a></div>";
		}
		$html.="<p><a href='create_album.php'>Create a new album</a></p>";
	}
	return $html;
}


if (isset($userid)){ // vérification si logué ou pas
 
 $html="<script>	
			function removeAlbum(e, id) {
      var a, url, x;
      e.preventDefault();
      a = e.target.parentNode;
      a.parentNode.hidden = true;
      url = './deleteAlbum.php?id=' + id;
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
	";
 $html.= getAllAlbumsbyUserId($userid);
 
 
 if(isset($_GET['id'])){
 $html = "<p>titi</p>";
 }
 
 printDocument('My Albums');
 
}
else{
header('Location: index.php');
}
  
?>

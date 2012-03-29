<?php
  include './header.php';
  
function getRandomAlbumCover($idalbum){
$query = "SELECT path_thumbnail,id_album from albums_photos WHERE id_album='$idalbum' ORDER BY RAND() LIMIT 1";
$result = mysql_query($query);
		$html ="<div id='albums'>";
		while($row = mysql_fetch_assoc($result)){
		$html.= "<a href='./albums.php?id=$row[id_album]'><img src='$row[path_thumbnail]'></a>";
		}
		$html.="</div>";
return $html;
}  
  
function printPhotosFromAlbum($idalbum){
$query = "SELECT path_thumbnail from albums_photos WHERE id_album='$idalbum'";
$result = mysql_query($query);
$html="<div id='albums'>";

		while($row = mysql_fetch_assoc($result)){
		$html.= "<a href='#'><img src='$row[path_thumbnail]'></a>";
		}
$html.="</div>";
return $html;
}  
 
function getAlbumName($idalbum){
$query = "SELECT name from albums WHERE id='$idalbum'";
$result = mysql_query($query);

	if (mysql_num_rows($result)>0){
	return mysql_fetch_assoc($result);
	}
} 
  
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
		$html.= getRandomAlbumCover($row['id']);
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
 
 //affichage des thumbnails de l'album
$albumname = getAlbumName($_GET['id']); 
$html = "<b>$albumname[name]</b>";
$html.= printPhotosFromAlbum($_GET['id']);
//lien pour ajouter nouvelle photo
$html.="<p><a href='./upload_album.php?idalbum=$_GET[id]'>Add photos to your album</a></p>";

 }
 
 printDocument('My Albums');
 
}
else{
header('Location: index.php');
}
  
?>

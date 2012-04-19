<?php
include 'header.php';
  
  
/************** ALBUM FUNCTIONS ****************/
  
function getRandomAlbumCover($idalbum){
	$query = "SELECT path_thumbnail,id_album from albums_photos WHERE id_album='$idalbum' ORDER BY RAND() LIMIT 1";
	$result = mysql_query($query);
	//$html ="<div id='allalbums'>";
	$html = "";
	while($row = mysql_fetch_assoc($result)){
		$html.= "<a href='./albums.php?id=$row[id_album]'><img src='$row[path_thumbnail]'></a>";
	}
	//$html.="</div>";
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
	return false;
} 
  
function getAllAlbumsbyUserId($id){
	$html ="";
	$query = "SELECT * FROM albums WHERE id_user='$id'";
	$result = mysql_query($query); 
	if(mysql_num_rows($result)==0){
		$html.= "<p class='error'>You haven't created any albums yet</p> ";
		$html.="<p><a href='albums.php?mode=create_album'>Create a new album</a></p>";
	}
	else{
		$html.="<h4>Your albums</h4>"; 
		$html.="<div id='allalbums'>";
		while($row = mysql_fetch_assoc($result)){
			$html.= getRandomAlbumCover($row['id']);
			$html.=" <a href='albums.php?id=$row[id]'>$row[name]</a><a href='#' onclick='removeAlbum(event,$row[id])'><img src='./img/templates/deleteing.png' width='10px' height='10px'></a>";
		}
		$html.="</div>";
		$html.="<p><a href='albums.php?mode=create_album'>Create a new album</a></p>";
	}
	return $html;
}

/*******************CREATE ALBUM FUNCTIONS******************/
function newAlbumForm($id){
	$html ="<form action='albums.php?mode=create_album' enctype='multipart/form-data' method='post'> 
			<p>Create a new Album</p>
			<p><label>Title:</label><input type='text' name='title'></p>
			<p><label>Description:</label>
			<textarea name='description' rows='5' cols='40'></textarea></p>
			<input type='submit' value='Add an album'> 
			</form>";	
	
	if($_POST){
		$res = addAlbum($_POST['title'],$_POST['description'],$id);
		if($res){
			$html ="<p>New album created.</p>";
			$idalbum = mysql_insert_id();
			$html.= "<p><a href='./upload_album.php?idalbum=$idalbum'>Add photos to your album</a></p>";
		} 
	}	
	return $html;
}

function addAlbum($name,$description,$id){
	$query = sprintf("INSERT INTO albums(name,description,id_user) VALUES('%s','%s','%s');", 
	mysql_real_escape_string(strip_tags($name)),
	mysql_real_escape_string(strip_tags($description)),
	mysql_real_escape_string(strip_tags($id)));
		
	$res = @mysql_query($query);
	if(!$res)
		die("Error: ".mysql_error());
	else
		return $res;	
}



/*********************       MAIN       **********************/


if (isset($userid)){ // vérification si logué ou pas
 
	if(isset($_GET['mode']) && $_GET['mode'] == "create_album"){
		$userinfos=retrieve_user_infos($userid);
		$html = newAlbumForm($userid);
		printDocument('Create Album');
		
	}else if(isset($_GET['mode']) && $_GET['mode'] == "delete_album" && isset($_GET['id'])){		
		$sql = "DELETE from albums WHERE id='$_GET[id]'";
		$query = @mysql_query($sql);

		if(!$query) die("Error: ".mysql_error());
		echo "success";
		
	}else{
	
		$html="<script>	
				function removeAlbum(e, id) {
				var a, url, x;
				e.preventDefault();
				a = e.target.parentNode;
				a.parentNode.hidden = true;
				url = './albums.php?mode=delete_album&id='+id;
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
		</script>";
		$html.= getAllAlbumsbyUserId($userid); 
	 

		if(isset($_GET['id'])){
		 
			//affichage des thumbnails de l'album
			$albumname = getAlbumName($_GET['id']); 
			$html = "<b>$albumname[name]</b>";
			$html.= printPhotosFromAlbum($_GET['id']);
			//lien pour ajouter nouvelle photo
			$html.="<p><a href='upload_album.php?idalbum=$_GET[id]'>Add photos to your album</a></p>";

		}		 
		printDocument('My Albums');
	}
 
}else{
	header('Location: index.php');
}
  
?>

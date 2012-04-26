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
	$query = "SELECT * from albums_photos WHERE id_album='$idalbum'";
	$result = mysql_query($query);
	$html="<div id='albums'>";

	while($row = mysql_fetch_assoc($result)){
		$html.= "<a href='$row[path]' onclick='return tswImageZoomAnimate(this);'
		onmouseover='tswImageZoomPreloadImage(this);'><img src='$row[path_thumbnail]'></a>
<a href='#' onclick='removePicture(event,$row[id])'><img src='./img/templates/deleteing.png' width='10px' height='10px'></a>";
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

function countPhotosOnAlbums($idalbum){
$query = "SELECT * FROM albums_photos WHERE id_album='$idalbum'";
$result = mysql_query($query);
$verif = mysql_num_rows($result);

return $verif;
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
		$html.="<div><ul id='pictures'>";
		while($row = mysql_fetch_assoc($result)){
			//$html.= 
			$countphotos = countPhotosOnAlbums($row['id']);
			$html.=" <li class='legend-top'>".getRandomAlbumCover($row['id'])."<a href='albums.php?id=$row[id]'></a><span>$row[name]($countphotos photos) <a href='#' onclick='removeAlbum(event,$row[id])'><img src='./img/templates/deleteing.png' width='10px' height='10px'></a></li>";
		}
		$html.="</ul></div>";
		$html.="<div><a href='albums.php?mode=create_album'>Create a new album</a></div>";
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
			$html.= "<p><a href='albums.php?mode=upload_album&idalbum=$idalbum'>Add photos to your album</a></p>";
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

/*******************UPLOAD ALBUM FUNCTIONS******************/
//add photo info to database
function addPhotoToAlbum($idalbum,$pathfull,$paththum){
	$query = sprintf("INSERT INTO albums_photos(id_album,path,path_thumbnail) VALUES('%s','%s','%s');", 
	mysql_real_escape_string(strip_tags($idalbum)),
	mysql_real_escape_string(strip_tags($pathfull)),
	mysql_real_escape_string(strip_tags($paththum)));
		
	$res = @mysql_query($query);
	if(!$res)
		die("Error: ".mysql_error());
	else
		return $res;	
}





function printMultiUploadForm($idalbum){

	$taillemax = 2000000; // taille max d'un fichier (multiple de 1024)
	$filetype = "/jpeg|png/i"; // types de fichiers accepteacute;s, separes par |
	$nametype = "/\.jpeg|\.jpg|\.png/i"; // extensions correspondantes
	$rep = "img/albums/"; // reacute;pertoire de destination
	$maxfichier = 10; // nombre maximal de fichiers
	// 1 fichier par deacute;faut (ou supeacute;rieur à $maxfichier)
	$upload = (isset($_GET['upload']) && $_GET['upload'] <= $maxfichier) ? 	$_GET['upload'] : 1;

	// choix du nombre $upload de fichier(s)
	$html = "<form action='albums.php?mode=upload_album' method='post'>\n";
	$html.= "Choose the number of photos you want to upload<select name='upload' onChange=\"window.open(this.options[this.selectedIndex].value,'_self')\">\n";
	for($i=1; $i<=$maxfichier; $i++) {
		$html.="<option value='albums.php?mode=upload_album&upload=$i&idalbum=$idalbum'";
		if($i == $upload) $html.= " selected";
		$html.= ">$i\n";
	}
	$html.= "</select></form>";

	// le formulaire
	$html.= "<form action='albums.php?mode=upload_album&idalbum=$idalbum' enctype='multipart/form-data' method='post'>";
	// boucle selon nombre de fichiers $upload
	for($i=1; $i<=$upload; $i++) {
		$html.= "<input type='hidden' name='MAX_FILE_SIZE' value='$taillemax'>";
		$html.= "Fichier <input type='file' name='lefichier[]'></p>";
	}

	$html.="<input type='submit' value='submit'></form>";

	if($_POST) {
		$msg = array(); // message
		$fichier = $_FILES['lefichier']; // simplication du tableau $_FILES
		for($i=0; $i<count($fichier['name']); $i++) {
			// nom du fichier original = nom par défaut lorsqu'on upload
			$nom = $idalbum.'_'.$fichier['name'][$i];
			$nom_default = $fichier['name'][$i];
			// reacute;pertoire de destination
			$destination = $rep.$nom;
			// test erreur (PHP 4.3)
			if($fichier['error'][$i]) {
				switch($fichier['error'][$i]) {
					// deacute;passement de upload_max_filesize dans php.ini
					case UPLOAD_ERR_INI_SIZE:
					  $msg[] = "File too large!"; break;
					// deacute;passement de MAX_FILE_SIZE dans le formulaire
					case UPLOAD_ERR_FORM_SIZE:
					  $msg[] = "File too large :(bigger than ".(INT)($taillemax/1024)." Mb)"; break;
					// autres erreurs
					default:
					  $msg[] = "File not found!";
				}
			}
			// test taille fichier
			elseif($fichier['size'][$i] > $taillemax)
				$msg[] = "File $nom_default too large : ".$fichier['size'][$i];
			// test type fichier
			elseif(!preg_match($filetype, $fichier['type'][$i]))
				$msg[] = "File $nom_default has an incorrect extension: ".$fichier['type'][$i];
			// test upload sur serveur (rep. temporaire)
			elseif(!@is_uploaded_file($fichier['tmp_name'][$i]))
				$msg[] = "Cannot upload $nom_default";
			// test transfert du serveur au reacute;pertoire
			elseif(!@move_uploaded_file($fichier['tmp_name'][$i], $destination))
				$msg[] = "A transfert problem with $nom_default";
			else
				if(strlen($nom)){
					$msg[] = "File <b>$nom_default</b> successfully uploaded.";	
				}
				
			$thumb = "img/thumbnails/$nom";
			createThumb($destination,$thumb,150,"left");
			addPhotoToAlbum($idalbum,$destination,$thumb);
		}
		
		// affichage confirmation
		for($i=0; $i<=count($msg); $i++){
			if(isset($msg[$i]))	$html.= "<p>$msg[$i]</p>";
		}
	}

	return $html;
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
	
	}else if(isset($_GET['mode']) && $_GET['mode'] == "delete_picture" && isset($_GET['id'])){		
		$sql = "DELETE from albums_photos WHERE id='$_GET[id]'";
		$query = @mysql_query($sql);

		if(!$query) die("Error: ".mysql_error());
		echo "success";
	
	}elseif(isset($_GET['mode']) && $_GET['mode'] == "upload_album" && isset($_GET['idalbum'])){
		$userinfos=retrieve_user_infos($userid);
		$idalbum = $_GET['idalbum'];
		$html = printMultiUploadForm($idalbum);

		printDocument('Upload Album');
	
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
			$html = "<script>	
				function removePicture(e,id) {
				var a, url, x;
				e.preventDefault();
				a = e.target.parentNode;
				a.parentNode.hidden = true;
				url = './albums.php?mode=delete_picture&id='+id;
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
			$html.= "<b>$albumname[name]</b>";
			$html.= printPhotosFromAlbum($_GET['id']);
			//lien pour ajouter nouvelle photo
			$html.="<p><a href='albums.php?mode=upload_album&idalbum=$_GET[id]'>Add photos to your album</a></p>";

		}		 
		printDocument('My Albums');
	}
 
}else{
	header('Location: index.php');
}
  
?>

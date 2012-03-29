<?php 

include'./header.php';

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


function getFileExtension($file){
   if($file != '.'){
   $ext = strrchr($file, '.');
   }
   else{
   $ext = 'Incorrect filename.';
   }
  // On affiche le résultat
  return $ext;
}

//creation de thumbnails
function createThumb($src, $dest, $largeur, $pos){
  list($srcX, $srcY, $type, $attr) = getimagesize($src);
  if(getFileExtension($src)==".jpg" || getFileExtension($src)=="jpeg"){
  $imgSrc=imagecreatefromjpeg($src); 
  }
  if(getFileExtension($src)==".png" ){
  $imgSrc=imagecreatefrompng($src);  
  }
  if (empty($imgSrc)){ 
      return false; 
  }
  if($srcX>= $srcY){
      $dim=$srcY;
      $horizontale=true;
  }
  elseif($srcX<= $srcY){
      $dim=$srcX; 
      $verticale=true;
  }
  else{
      $dim=$srcX;
  }   
  //on determine le point de depart x,y
  if($horizontale)
  {
   switch($pos){
    case "left":
      $point_x_ref="0";
      $point_y_ref="0";
    break;
    case "right":
      $point_x_ref=($srcX)-($dim);
      $point_y_ref="0";
    break;
    default: 
      $point_x_ref=($srcX/2)-($dim/2);
      $point_y_ref="0";
    break;
    }
  }
  elseif($verticale)
  {
   switch($pos){
    case "top":
      $point_x_ref="0";
      $point_y_ref="0";
    break;
    case "bottom":
      $point_x_ref="0";
      $point_y_ref=($srcY)-($dim);
    break;
    default: 
      $point_x_ref="0";
      $point_y_ref=($srcY/2)-($dim/2); 
    break;
   }
  }
  $imDest=@imagecreatetruecolor($largeur, $largeur); 
          
  imagecopyresampled($imDest, $imgSrc, 0, 0, $point_x_ref, $point_y_ref, 
$largeur , $largeur, $dim, $dim); 
  imagedestroy($imgSrc); 
  
  if(getFileExtension($src)==".jpg" || getFileExtension($src)==".jpeg"){
  imagejpeg($imDest, $dest, 100); 
  }
  
  if(getFileExtension($src)==".png"){
  imagepng($imDest, $dest); 
  } 
  
  imagedestroy($imDest); 
  return true;
}


function printMultiUploadForm($idalbum){

	$taillemax = 2000000; // taille max d'un fichier (multiple de 1024)
	$filetype = "/jpeg|gif|png/i"; // types de fichiers accepteacute;s, separes par |
	$nametype = "/\.jpeg|\.jpg|\.gif|\.png/i"; // extensions correspondantes
	$rep = "img/albums/"; // reacute;pertoire de destination
	$maxfichier = 10; // nombre maximal de fichiers
	// 1 fichier par deacute;faut (ou supeacute;rieur à $maxfichier)
	$upload = (isset($_GET['upload']) && $_GET['upload'] <= $maxfichier) ? 	$_GET['upload'] : 1;

	// choix du nombre $upload de fichier(s)
	$html = "<form action='./upload_album.php' method='post'>\n";
	$html.= "Choose the number of photos you want to upload<select name='upload' onChange=\"window.open(this.options[this.selectedIndex].value,'_self')\">\n";
	for($i=1; $i<=$maxfichier; $i++) {
	$html.="<option value='./upload_album.php?upload=$i&idalbum=$idalbum'";
		if($i == $upload) $html.= " selected";
		$html.= ">$i\n";
	}
	$html.= "</select></form>";

	// le formulaire
	$html.= "<form action='upload_album.php?idalbum=$idalbum' enctype='multipart/form-data' method='post'>";
	// boucle selon nombre de fichiers $upload
	for($i=1; $i<=$upload; $i++) {
	$html.= "<input type='hidden' name='MAX_FILE_SIZE' value='$taillemax'>";
	$html.= "Fichier <input type='file' name='lefichier[]'></p>";
	}

	$html.="<input type='submit' value='submit'>
</form>";

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
	for($i=0; $i<=count($msg); $i++)
		$html.= "<p>$msg[$i]</p>";
}

return $html;
}


if (isset($userid)){

  $userinfos=retrieve_user_infos($userid);
  $idalbum = $_GET['idalbum'];
  
  $html = printMultiUploadForm($idalbum);

printDocument('Upload Album');		
}

else{

header('Location: index.php');

}
	
?>
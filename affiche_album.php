<?php 

 include './header.php';

if (isset($userid)){ // vérification si logué ou pas


//----------Affiche les image(s)----------


if( isset($_GET['title']) && isset($_GET['id']) ){ 	

$AlbumName = $_GET['title'];
$AlbumID = $_GET['id'];

	
// La description
$query = "SELECT description_album AS RubDesc
          FROM recipe_albums
	      WHERE id='$AlbumID'";
$result = mysql_query($query) or die("ERROR");
$RubDesc = mysql_result($result,0,"RubDesc"); 

// Le nombre d'images
$query = "SELECT count(id) AS ImgCount 
          FROM recipe_photos
		  WHERE id_album='$AlbumID'"; 
$result = mysql_query($query) or die("ERROR 2"); 
$ImgCount  = mysql_result($result,0,"ImgCount"); 
echo "<br/><br/>$RubDesc<br/><br/>$ImgCount image(s)<br/><br/>";


// Commence la table dans laquelle sont affichées les imagettes
echo "<table border='0' width='100%'><tr>";

// Récupère la liste des images de la rubrique sélectionnée
$query = "SELECT id, name
          FROM recipe_photos
	      WHERE id_album='$AlbumID'
          ORDER BY name"; 
$result = mysql_query($query) or die('ERROR 3'); 

// On veut afficher 4 images par ligne
$NbrImgParLigne = 4;
$NumImgLigne = 0;

// Commence la table dans laquelle sont affichées les imagettes
echo "<table border='0' width='100%'><tr>";

// Traite les images une après les autres
while ($row = mysql_fetch_array($result))  
{
     // Passe l'affichage des images à la ligne si 4 images affichées
     if ($NumImgLigne>=$NbrImgParLigne)
     {
         echo "</tr><tr>";
         $NumImgLigne = 0;
     } 

     $NumImgLigne++;

     // Commence une colonne de la grille pour y inclure l'image
     echo "<td align='center'>";
 
     // Récupère l'ID et le nom des photos
     $id_photo = $row['id']; 
     $ImageName = $row['name']; 


     
	
if( isset($ImageName) ) { // si il y a des images, nous rentrons dans la fonction pour les afficher 

     // Affiche le nom de l'image et sa miniature
     echo "$ImageName<br/><a href=\"/doss/projet/img/".$AlbumName."/".$userid.'_profile_'.$ImageName."\"><img src=\"./img/".$AlbumName."/".$userid."_profile_".$ImageName."\" height=\"140px\" width=\"140px\" border=\"0\" alt=\"$AlbumName\"></a>";

	}

}
     // Termine la colonne de la grille pour cette image
     echo "</td>";
	 
	 
	 

   
   // Termine la grille 
 	echo  "</tr></table>";
 

  
  echo '<br />  
		<br />  
		<br />  
		<br />'; 

  
  
//----------Ajouter image(s)----------




echo "
  <h3>Upload picture</h3>
  <form method='post' enctype='multipart/form-data'>
<label for='picture'>Image :</label>
<input type='file' size='65' name='picture' /></p>

</select>

<input type='submit' value='Add picture(s)'/> 
</form>";
	
	
	if( ( isset($_FILES['picture']) && ($_FILES['picture']['error'] == UPLOAD_ERR_OK) ) ){    
	

	//On fait un tableau contenant les extensions autorisées.
	$extensionsOk = array('.PNG', '.GIF', '.JPG', '.JPEG', '.png', '.gif', '.jpg', '.jpeg');
	
	// On récupère l'extension, donc à partir de ce qu'il y a après le '.'
	$extension = strrchr($_FILES['picture']['name'], '.');
	
	//teste
	if(!in_array($extension, $extensionsOk)) //Si l'extension n'est pas dans le tableau
	{
		echo 'You must upload a file type png, gif, jpg, jpeg';
	}

	else{
	
			// vérification de la taille de l'image
			if( filesize($_FILES['picture']['name']>100000) ){

			echo 'File too large.';
			
			}
		
		
		
			else{
			
			
				$destination = './img/'.$AlbumName.'/';

				// si il y a une image avec le même, le nom est changé grâce à rand(). Cela évite que l'image soit écrasée.
				while(file_exists("C:/wamp/www/doss/projet/img/".$AlbumName."/".$userid.'_profile_'.$_FILES['picture']['name'])) {
					$_FILES['picture']['name'] = rand().$_FILES['picture']['name'];
				}
    
				// transfère de l'image du répertoire temporaire vers le dossier correspondant
				move_uploaded_file($_FILES['picture']['tmp_name'], $destination.$userid.'_profile_'.$_FILES["picture"]["name"]);

				
				$Name=$_FILES["picture"]["name"];
				$Path = $destination.$userid.'_profile_'.$_FILES["picture"]["name"];
				
				// Date et Heure 
				$Date = date("Y-m-d");
				$Time = date("H:i:s");
				
			
				// enregistre dans la bdd
				$query = "INSERT INTO recipe_photos SET
				id_album='$AlbumID',
				name='$Name',
				path_source='$Path',
				date='$Date',
				time='$Time'";
				$res = mysql_query($query) or die("error");
				
				// Si l'upload est okay, l'album est affiché automatiquement pour que l'on voit l'image uploader
				if( isset($res) ){  ?><meta http-equiv="Refresh" content="0; URL=http://127.0.0.1/doss/projet/albums.php"><?php }
   
	
		}
	
	}  
  
  
 }
 
 
}



//----------Supprime les image(s) choisie(s)----------




 echo "<br/><br/><br/>
 
 
 <form action='affiche_album.php?title=".$AlbumName."&id=".$AlbumID."' enctype='multipart/form-data' method='post'>

<h3>Delete Image(s):</h3> ";
$query = "SELECT id, name FROM recipe_photos ORDER BY name DESC";
$result = mysql_query($query) or die("ERROR");
echo "<select name='image'>";
$Selected = 0;

while ($row = mysql_fetch_array($result))
{
  if($Selected == 0)
  {
    echo '<option value="', $row[0], '" selected="selected">', $row[1], '</option>';
    $Selected = 1;
  }
  
  else
  {
    echo '<option value="', $row[0], '">', $row[1], '</option>';
  }
  
}

echo "</select>
	<br/><br/>
	<input type='submit' value='Confirm'/> 
	</form>";

if( isset($_POST['image']) ){


// Récupération de l'id l'image
	$photoID = $_POST['image'];
	

// Récupération du nom de l'image
	$query = "SELECT name AS PhotoName
			  FROM recipe_photos
		      WHERE id=$photoID";
	$result = mysql_query($query) or die("ERROR");
	$PhotoName = mysql_result($result,0,"PhotoName"); 
	
	
// Suprime l'image de dossier
	$ImgPath = "c:/wamp/www/doss/projet/img/".$AlbumName."/".$userid."_profile_".$PhotoName;
	if (!unlink($ImgPath))
	{	
		echo "ERROR";
	}
	
	
	// Supprime l'image de la rubrique de la base de données
	$query = "DELETE FROM recipe_photos WHERE name='$PhotoName'";
	$result = mysql_query($query);

	
	// Rafraichis après avoir suprrimer l'album
	?><meta http-equiv="Refresh" content="0; URL=http://127.0.0.1/doss/projet/albums.php"><?php
	
}

 
printDocument(' My Pictures ');

}else{
	
	header('Location: index.php');
}


?>
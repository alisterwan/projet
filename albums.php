<?php
  include './header.php';
  

if (isset($userid)){ // vérification si logué ou pas


//----------Affiche les images----------


// Sélectionne l'id et le titre de chaque album présent dans la bdd
$query="SELECT id, title FROM recipe_albums ORDER BY title"; 
$result = mysql_query($query) or die("ERROR0");
				
				
				
				
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
 
     // Récupère l'ID et le nom de l'album, en déduit le nom de la miniature
     $AlbumID = $row['id']; 
     $AlbumName = $row['title']; 

	 
	 // Récupère le nom d'une image pour la mettre sur un album en miniature
	 $query = "SELECT name FROM recipe_photos WHERE id_album='$AlbumID'";
     $requete = mysql_query($query) or die("ERROR1");
     $row = mysql_fetch_array($requete);
	 $ImageName=$row['0'];
	 
	 if( !isset($ImageName) && $ImageName==null ){ 
	 
	 //$ImageName='album.jpg';
	
     // Affiche l'album et sa miniature
     echo "$AlbumName<br/><a href=\"/doss/projet/affiche_album.php?title=".$AlbumName."&id=".$AlbumID."\"><img src=\"./img/img_album_defaut/album.jpg\" height=\"140px\" width=\"140px\" border=\"0\" alt=\"$AlbumName\"></a>";


	 }
     
	 else{

     // Affiche l'album et sa miniature
     echo "$AlbumName<br/><a href=\"/doss/projet/affiche_album.php?title=".$AlbumName."&id=".$AlbumID."\"><img src=\"./img/".$AlbumName."/".$userid."_profile_".$ImageName."\" height=\"140px\" width=\"140px\" border=\"0\" alt=\"$AlbumName\"></a>";

	}

     // Termine la colonne de la grille pour cette image
     echo "</td>";
	 
	 
	 
} 
   
   // Termine la grille 
 	echo  "</tr></table>";
  
  
  
 //----------Supprime les albums----------
  
  
  
 echo "<form action='albums.php' enctype='multipart/form-data' method='post'>

Delete Album: ";
$query = "SELECT id, title FROM recipe_albums ORDER BY title DESC";
$result = mysql_query($query) or die("ERROR0");
echo "<select name='frm_select_rubrique3'>";
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

	
	if( isset($_POST['frm_select_rubrique3']) ){
	
	
	$AlbumID = $_POST['frm_select_rubrique3'];

	// Récupération du nom de la rubrique
	$query = "SELECT title AS RubName
			  FROM recipe_albums
		      WHERE id=$AlbumID";
	$result = mysql_query($query) or die("ERROR");
	$AlbumName = mysql_result($result,0,"RubName"); 

	$query = "SELECT name FROM recipe_photos WHERE id_album='$AlbumID'"; 
	$result = mysql_query($query); 
	while ($row = mysql_fetch_array($result))  
	{
		$ImageName = $row[0];
		if ( (!unlink("c:/wamp/www/doss/projet/img/".$AlbumName."/".$userid."_profile_".$ImageName)) )
		{
			echo "ERROR";
		}
	}
	
	
	// Suprime le répertoire de la rubrique
	$AlbumPath = "c:/wamp/www/doss/projet/img/".$AlbumName;
	if (!rmdir($AlbumPath))
	{	
		echo "ERROR";
	}
	
	
	// Supprime les images de la rubrique de la base de données
	$query = "DELETE FROM recipe_photos WHERE id_album='$AlbumID'";
	$result = mysql_query($query);
	
	// Supprime l'album de la base de données
	$query = "DELETE FROM recipe_albums WHERE id='$AlbumID'";
	$result = mysql_query($query);
	
	// Rafraichis après avoir suprrimer l'album
	?><meta http-equiv="Refresh" content="0; URL=http://127.0.0.1/doss/projet/albums.php"><?php

}


//----------Lien menant à la page pour créer un album----------


?>

<br />
<br />

<!-- Lien pour créer un album -->
<a href="Create_album.php">Create an album</a><?php


 printDocument('My Albums');
 
}else{
	
	header('Location: index.php');
}
  
?>

<?php
	include './header.php';


	$html = "<h1>$user[firstname] $user[surname] ($user[username])</h1>
	<FORM method='POST' action='./image.php' enctype='multipart/form-data' >
	<input type='hidden' name='MAX_FILE_SIZE' value='1000000'>Upload a picture:<input type='file' name='image' value='parcourir'>
	<br> <input type='submit' name='val' value='Upload'>";
	

		$name = $_SESSION[id].'_'.rand().'_';
		
		$dossier = 'img/users/'.$name;
		$fichier = basename($_FILES['image']['name']);
		$taille_maxi = 1000000;
		$taille = filesize($_FILES['image']['tmp_name']);

		$extensions = array(".png", ".gif",  ".GIF",  ".PNG",  ".JPG",  ".JPEG",".jpg", ".jpeg");
		$extension = strrchr($_FILES['image']['name'], "."); 

		if(!in_array($extension, $extensions))
		{
     		$erreur = "Vous devez uploader un fichier de type png, gif, jpg, jpeg";
		}

		if($taille>$taille_maxi)
		{
     		$erreur = "Le fichier est trop gros...";
		}

		if(!isset($erreur)) 
		{
     
     		$fichier = strtr($fichier, 
          'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
          'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
     		$fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
   		
   		if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $fichier)) 
     		{  echo "Upload successful!";   
		   
			}
     		else 
     		{  echo "Upload failed!";  }
     


}
printDocument('Upload a picture');	
?>

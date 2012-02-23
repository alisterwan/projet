<?php 

include'./header.php';


if (isset($userid)){
 

  $userinfos=retrieve_user_infos($userid);
  $html = '<form action="Create_album.php" enctype="multipart/form-data" method="post"> 
	  Title:<br/> 
          <input type="text" name="title" size="50"/> 
	  <br/><br/>
          Description:<br/> 
          <textarea name="description" rows="5" cols="40"></textarea>
	  <br/><br/>
          <input type="submit" value="Add an album"/> 
</form>
  ';
  
  

	if ( isset($_POST['title']) && isset($_POST['description']) )
	{
		
			// On récupère les variables
		$Title = $_POST['title'];
		$Description = $_POST['description'];
  
	// Date et Heure 
	$Date = date("Y-m-d");
	$Time = date("H:i:s");
	
	// Sélectionne dans la bdd
	$query = "SELECT title FROM recipe_albums WHERE title='$Title'";
	$result1 = mysql_query($query);
	$row=mysql_fetch_array($result1);
	




if( $row['0']!=$Title ) 
{


	// Ajout de l'album
	$query = "INSERT INTO recipe_albums SET
		    title='$Title',
			description_album='$Description',
			date='$Date',
			time='$Time'"; 
	$result2 = mysql_query($query); 


if( isset($result2) ) 
	{ 
	
		mkdir("/img/".$Title."");
		header('Location: albums.php');
			
		
	}
	  
	  
}




}

printDocument('Create Album');

}else{

header('Location: index.php');

}
	
?>
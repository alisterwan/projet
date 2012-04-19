<?php 

include'./header.php';

function newAlbumForm($id){
	$html ="
	<form action='create_album.php' enctype='multipart/form-data' method='post'> 
	  <p>Create a new Album</p>
	  <p><label>Title:</label><input type='text' name='title'></p>
      <p><label>Description:</label>
      <textarea name='description' rows='5' cols='40'></textarea></p>
      <input type='submit' value='Add an album'> 
	</form>
	";	
	
	 if($_POST){
	$res = addAlbum($_POST['title'],$_POST['description'],$id);
		if($res){
		$html ="<p>New album created.</p>";
		$idalbum = mysql_insert_id();
		$html.="<p><a href='./upload_album.php?idalbum=$idalbum'>Add photos to your album</a></p>";
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

if (isset($userid)){
 
  	 	
  $userinfos=retrieve_user_infos($userid);
  $html = newAlbumForm($userid);
	
 	

printDocument('Create Album');

}

else{

header('Location: index.php');

}
	
?>
<?php 

include'./header.php';

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
 
 if($_POST){
	$res = addAlbum($_POST['title'],$_POST['description'],$userid);
		if($res){
		$html ="<p>New album created.</p>";
		} 
	}
 
 
  $userinfos=retrieve_user_infos($userid);


printDocument('Upload Album');

}

else{

header('Location: index.php');

}
	
?>
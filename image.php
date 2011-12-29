<?php

include './header.php';

if (isset($userid)){  // vÈrification si loguÈ ou pas

  
  $userinfos=retrieve_user_infos($userid);
  $useraddinfos=retrieve_user_add_infos($userid);

$html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
  <h3>Change my Avatar</h3>
  <form method='post' action='image.php' enctype='multipart/form-data'>
Change your avatar:
<label for='picture'>Image :</label>
<input type='file' size='65' name='picture' /></p>
<input type='submit' name='upload' value='Upload your picture' />
</form>
  ";

$html .= "<br /><br />
      <FORM method='POST' action='./image.php'>
          Use Gravatar : 
          <label for='use'>Yes</label>
          <input type='radio' name='useGravatar' value='use' id='use'/>
          <label for='notUse'>Default picture</label>
          <input type='radio' name='useGravatar' value='notUse' id='notUse'/>
          <input type='submit' name='val' value='Update'>
      </FORM>";

if (isset($_POST['useGravatar'])) {
    if(isset($_SESSION['id'])) {
        $userinfos=retrieve_user_infos($_SESSION['id']);
        $useGravatar = $_POST['useGravatar'];
        if ($useGravatar == 'use') {
            $avatar = 'http://www.gravatar.com/avatar/' . md5(  trim( $userinfos['mail']  ) ) . '?s=200';
            $query = "UPDATE users SET avatar='$avatar' WHERE id='$userid' ";
            $res = mysql_query($query);
        } else {
	     
	     $sex_query = "SELECT sex FROM users WHERE id='$userid' ";
	     $query1 = mysql_query($sex_query);
	     $res1 = mysql_fetch_assoc($query1);
		
	     if ($res1['sex'] == '1'){
		   $image = './img/avatar/man_default.png';
		   $query = "UPDATE users SET avatar='$image' WHERE id='$userid' ";
		   $res = mysql_query($query);  
		   } 
		   
	     else {    
            $image = './img/avatar/woman_default.png';
            $query = "UPDATE users SET avatar='$image' WHERE id='$userid' ";
            $res = mysql_query($query);
	     }
        }
    }
}

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
			if( filesize($_FILES['picture']['name']>10) ){

			echo 'File too large.';
			
			}
		
		
		
			else{
			
			
				$destination = './img/avatar/';

				// si il y a une image avec le même, le nom est changé grâce à rand(). Cela évite que l'image soit écrasée.
				while(file_exists($destination.$_FILES['picture']['name'])) {
					$_FILES['picture']['name'] = rand().$_FILES['picture']['name'];
				}
    
				// transfère de l'image du répertoire temporaire vers le dossier avatar	
				move_uploaded_file($_FILES['picture']['tmp_name'], './img/avatar/'.$userid.'_profile_'.$_FILES["picture"]["name"]);    
   
	
				// met l'image uploadée en profil	
				$image = './img/avatar/'.$userid.'_profile_'.$_FILES["picture"]["name"];
				$query = "UPDATE users SET avatar='$image' WHERE id='$userid' ";
				$res = mysql_query($query);
	
		}
	
	}

	
}



printDocument('Upload a picture');
}

else{
	
	header('Location: index.php');
}
?>

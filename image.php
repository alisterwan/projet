<?php

include './header.php';

function upload($index,$destination,$maxsize=FALSE,$extensions=FALSE)
{
   //Test1: fichier correctement uploadé
     if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) return FALSE;
   //Test2: taille limite
     if ($maxsize !== FALSE AND $_FILES[$index]['size'] > $maxsize) return FALSE;
   //Test3: extension
     $ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
     if ($extensions !== FALSE AND !in_array($ext,$extensions)) return FALSE;
   //Déplacement
     return move_uploaded_file($_FILES[$index]['tmp_name'],$destination);
}



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
          <label for='notUse'>No</label>
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

if (isset($_POST['picture'])) {
	
	echo "coucou";
	
	$upload1 = upload('$_POST["picture"]','./img/avatar/',15360, array('png','gif','jpg','jpeg') );
	
	echo "coucou apres upload";
	
	$image = './img/avatar/$_POST["picture"]';
	$query = "UPDATE users SET avatar='$image' WHERE id='$userid' ";
	if ($upload1) echo "Upload de l'icone réussi!";
	
}


printDocument('Upload a picture');
}

else{
	
	header('Location: index.php');
}
?>

<?php

include './header.php';

$html = "<h1>$user[firstname] $user[surname] ($user[username])</h1>
	<FORM method='POST' action='./image.php' enctype='multipart/form-data' >
	<input type='hidden' name='MAX_FILE_SIZE' value='1000000'>Upload a picture:<input type='file' name='image' value='parcourir'>
	<br> <input type='submit' name='val' value='Upload'>
      </FORM>";

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
            $image = $_SESSION['id'] . '.jpg';
            $query = "UPDATE users SET avatar='$avatar' WHERE id='$userid' ";
            $res = mysql_query($query);
        }
    }
}

if (isset($_POST["image"])) {
    $name = $_SESSION[id];

    $dossier = 'img/users/' . $name;
    $fichier = basename($_FILES['image']['name']);
    $taille_maxi = 1000000;
    $taille = filesize($_FILES['image']['tmp_name']);

    $extensions = array(".png", ".gif", ".GIF", ".PNG", ".JPG", ".JPEG", ".jpg", ".jpeg");
    $extension = strrchr($_FILES['image']['name'], ".");

    if (!in_array($extension, $extensions)) {
        $erreur = "Vous devez uploader un fichier de type png, gif, jpg, jpeg";
    }

    if ($taille > $taille_maxi) {
        $erreur = "Le fichier est trop gros...";
    }
    if (!isset($erreur)) {
        $fichier = strtr($fichier, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
        $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $fichier)) {
            echo "Upload successful!";
        } else {
            echo "Upload failed!";
        }
    }
}

printDocument('Upload a picture');
?>

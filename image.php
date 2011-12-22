<?php

include './header.php';

if (isset($userid)){  // vÈrification si loguÈ ou pas

  
  $userinfos=retrieve_user_infos($userid);
  $useraddinfos=retrieve_user_add_infos($userid);

$html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
  <h3>Change my Avatar</h3>";

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



printDocument('Upload a picture');
}

else{
	
	header('Location: index.php');
}
?>

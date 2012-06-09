<?php

define("NO_IMAGE", "img/default/noimage.gif");
//Functions


/*********************************************************************************/

function printInfoBanner($userid){
	
	$useraddinfos=retrieve_user_add_infos($userid);
	$userinfos=retrieve_user_infos($userid);
	
	if($useraddinfos){
	return 
	"<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
  	
	<h4>Born in $useraddinfos[date_birth], Works at $useraddinfos[job] Listen to $useraddinfos[music]</h4>
	";}
	
	else {
	return 
	"<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>
  	 ";
	}
 }


//Fonctions qui fait des requetes MySQL

function retrieve_countryid($country){
$sql='SELECT id FROM country WHERE name_en='.$country;
	$query=mysql_query($sql);
	$verif = mysql_num_rows($query);
	
	if ($verif == 1){
		return $result=mysql_fetch_assoc($query);
	}
	
	return false;	
}

function retrieve_cityname($id_country){
$sql='SELECT name_en FROM city WHERE id_country='.$id_country;
	$query=mysql_query($sql);
	
	if (mysql_num_rows($query) == 1){
		return $result=mysql_fetch_assoc($query);
	}
	
	return false;	
}

function retrieve_user_infos($id){ // prend en paramètre l'id de l'user, soit $_SESSION['id']
	$sql='SELECT * FROM users WHERE id='.$id;
	$query=mysql_query($sql);
	$verif = mysql_num_rows($query);
	
	if ($verif == 1){
	return $result=mysql_fetch_assoc($query);
	}
	return false;
  }
  
function retrieve_user_friends($id){
$sql='SELECT * FROM groups WHERE id_creator='.$id;
	$query=mysql_query($sql);
	$verif = mysql_num_rows($query);
	
	if ($verif == 1){
	return $result=mysql_fetch_assoc($query);
	}
	return false;
}    

function retrieve_user_add_infos($id){ // prend en paramÃ¨tre l'id de l'user, soit $_SESSION['id']

	$sql='SELECT * FROM information WHERE id_user='.$id;
	$query=mysql_query($sql);
	
	if (mysql_num_rows($query) == 1){
		return $result=mysql_fetch_assoc($query);
	}
	
	return false;
}

//fonction pour recuperer les friends request
function checkFriendRequest($id){
	$query = sprintf("SELECT * FROM groups_relations WHERE id_user='%s' AND approval='0'",
	mysql_real_escape_string($id));
    $result = mysql_query($query);

	if (!$result) {
		return false;
	}

	else
	while ($row = mysql_fetch_assoc($result)) {
		return $row; 
	}

	mysql_free_result($result);
}


//fonction pour recuperer le id de l'emetteur
function getGroupCreator($idgroup){
	$query = sprintf("SELECT * FROM groups
	WHERE id='%s'",
	mysql_real_escape_string($idgroup));
	$result = mysql_query($query);

	if (!$result) {
		return false;
	}

	else
	while ($row = mysql_fetch_assoc($result)) {
	return $row['id_creator'];
	}

	mysql_free_result($result);
}
	

/*****************FUNCTION ON SEARCH****************/




/******************END FUNCTION ON SEARCH***********/
	
	

/******************************************************************/


function good_query($chaine, $debug=0)
{
    if ($debug == 1)
        echo $chaine;

    if ($debug == 2)
        error_log($chaine);

    $resultat = mysql_query($chaine);

    if ($resultat == false)
    {
        error_log("SQL error: ".mysql_error()."\n\nOriginal query: $chaine\n");
    }
    return $resultat;
}

function good_query_list($sql, $debug=0)
{
    // cette fonction a besoin de good_query() fonction
    $resultat = good_query($sql, $debug);
  
    if($lst = mysql_fetch_row($resultat))
    {
  mysql_free_result($resultat);
  return $lst;
    }
    mysql_free_result($resultat);
    return false;
}

function good_query_assoc($sql, $debug=0)
{
    // cette fonction a besoin de good_query() fonction
    $resultat = good_query($sql, $debug);
  
    if($lst = mysql_fetch_assoc($resultat))
    {
  mysql_free_result($resultat);
  return $lst;
    }
    mysql_free_result($resultat);
    return false;
}

function good_query_value($sql, $debug=0)
{
    // cette fonction a besoin de good_query_list() fonction
    $lst = good_query_list($sql, $debug);
    return is_array($lst)?$lst[0]:false;
}

function good_query_table($sql, $debug=0)
{
    // cette fonction a besoin de good_query() fonction
    $resultat = good_query($sql, $debug);

    $table = array();
    if (mysql_num_rows($resultat) > 0)
    {
        $i = 0;
        while($table[$i] = mysql_fetch_assoc($resultat))
      $i++;
        unset($table[$i]);
    }
    mysql_free_result($resultat);
    return $table;
}

function RegistrationForVisitors(){
	$ficelle='';
	$ficelle.='<h4>Join the community!<br/>
	<a href="registration.php" />Register now.</a>
	<br/><br/>
	It\'s free!';
	return $ficelle;
}

function getFileExtension($file){
	if($file != '.'){
		$ext = strrchr($file, '.');
	}else{
		$ext = 'Incorrect filename.';
	}
	// On affiche le résultat
	return $ext;
}

//creation de thumbnails
function createThumb($src, $dest, $largeur, $pos){
	list($srcX, $srcY, $type, $attr) = getimagesize($src);
	if(getFileExtension($src)==".jpg" || getFileExtension($src)==".jpeg"){
		$imgSrc=imagecreatefromjpeg($src); 
	}
	if(getFileExtension($src)==".png" ){
		$imgSrc=imagecreatefrompng($src);  
	}
	if(getFileExtension($src)==".gif" ){
		$imgSrc=imagecreatefromgif($src);  
	}
	if (empty($imgSrc)){ 
		return false; 
	}
	if($srcX>= $srcY){
		$dim=$srcY;
		$horizontale=true;
	}elseif($srcX<= $srcY){
		$dim=$srcX; 
		$verticale=true;
	}else{
		$dim=$srcX;
	}   
  
	//on determine le point de depart x,y
	if($horizontale){
		switch($pos){
			case "left":
				$point_x_ref="0";
				$point_y_ref="0";
			break;
			case "right":
				$point_x_ref=($srcX)-($dim);
				$point_y_ref="0";
			break;
			default: 
				$point_x_ref=($srcX/2)-($dim/2);
				$point_y_ref="0";
			break;
		}
	}elseif($verticale){
		switch($pos){
			case "top":
				$point_x_ref="0";
				$point_y_ref="0";
			break;
			case "bottom":
				$point_x_ref="0";
				$point_y_ref=($srcY)-($dim);
			break;
			default: 
				$point_x_ref="0";
				$point_y_ref=($srcY/2)-($dim/2); 
			break;
	   }
	}
	$imDest=@imagecreatetruecolor($largeur, $largeur); 
          
	imagecopyresampled($imDest, $imgSrc, 0, 0, $point_x_ref, $point_y_ref, $largeur , $largeur, $dim, $dim); 
	imagedestroy($imgSrc); 
  
	if(getFileExtension($src)==".jpg" || getFileExtension($src)==".jpeg"){
		imagejpeg($imDest, $dest, 100); 
	}
  
	if(getFileExtension($src)==".png"){
		imagepng($imDest, $dest); 
	} 
	
	if(getFileExtension($src)==".gif"){
		imagegif($imDest, $dest); 
	} 
  
	imagedestroy($imDest); 
	return true;
}


///////////////////////////////////////// FONCTIONS QUI GERENT LES PERMiSSIONS! ////////////////////////////////////////

function getAllGroupsByUserId($idcreator){
	$query = sprintf("SELECT * FROM groups	WHERE id_creator='%s'",	mysql_real_escape_string($idcreator));
	$result = mysql_query($query);

	if (!$result) {
		return false;
	}else{
		$reponse;
		while ($row = mysql_fetch_assoc($result)) {
			$reponse[]=$row['id'];
		}
		return $reponse;
	}
}

function getFriendsByUserId($idcreator){
	$query = sprintf("SELECT * FROM groups	WHERE id_creator='%s' AND name='Friends'",	
	mysql_real_escape_string($idcreator));
	$result = mysql_query($query);

	if (!$result) {
		return false;
	}else{
		$reponse;
		while ($row = mysql_fetch_assoc($result)) {
			$reponse[]=$row['id'];
		}
		return $reponse;
	}
}

function getAllUsersOfGroups($groups){ // returns all userID (array) who belong to $groups (array) EXCEPT owners|| returns FALSE if none
	if(count($groups)<1) return false;
	
	$users;
	foreach($groups AS $group){
		$sql = 'SELECT DISTINCT id_user FROM groups_relations WHERE id_group='.$group.' AND approval=1';
		$query = mysql_query($sql);
		if($query!=false && mysql_num_rows($query)>0){
			while ($result = mysql_fetch_assoc($query)){
				if(isset($users) && is_array($users) && count($users)>0 && !in_array($result['id_user'], $users)){ // add to array
					$users[] = $result['id_user'];
				}elseif(!isset($users)){ // creates new array
					$users[] = $result['id_user'];
				}
			}
		}
	}
	
	if(isset($users) && is_array($users)) return $users; // returns userID
	
	return false; // none
}

function getAllGroupNameByUserId($idcreator){
	$query = sprintf("SELECT name FROM groups	WHERE id_creator='%s'",	mysql_real_escape_string($idcreator));
	$result = mysql_query($query);

	if (!$result) {
		return false;
	}else{
		$reponse;
		while ($row = mysql_fetch_assoc($result)) {
			$reponse[]=$row['name'];
		}
		return $reponse;
	}
}

function getGroupsByRecipeId($id){
	$query = sprintf("SELECT * FROM recipe_view_permission WHERE id_recipe='%s'",	mysql_real_escape_string($id));
	$result = mysql_query($query);

	if (!$result) {
		return false;
	}else{
		$reponse;
		while ($row = mysql_fetch_assoc($result)) {
			$reponse[]=$row['id'];
		}
		return $reponse;
	}
}

function checkPermission($idgroup,$iduser){ // TRUE if $iduser belongs to any group of $idgroup (<<<WHICH IS INTEGER OR ARRAY OF INTEGER>>>!!!!!)
	if (is_array($idgroup)){ 
		foreach($idgroup as $idgroupsingle){
			// checking for others
			$sql = 'SELECT id FROM groups_relations WHERE id_group='.$idgroupsingle.' AND id_user='.$iduser.' AND approval=1';
			$query = mysql_query($sql);
			if(!$query) return false; // fix test
			if(mysql_num_rows($query)>0){
				return true;
			}
			
			// cheking for self
			$sql = 'SELECT id_creator FROM groups WHERE id='.$idgroupsingle.' AND id_creator='.$iduser;
			$query = mysql_query($sql); if(!$query) return false; // fix test
			if(mysql_num_rows($query)>0){
				return true;
			}		
		}
	}elseif(is_numeric($idgroup)){
		// checking for others
		$sql = 'SELECT id FROM groups_relations WHERE id_group='.$idgroup.' AND id_user='.$iduser.' AND approval=1';
		$query = mysql_query($sql); if(!$query) return false; // fix test
		if(mysql_num_rows($query)>0){
			return true;
		}
	
		// cheking for self
		$sql = 'SELECT id_creator FROM groups WHERE id='.$idgroup.' AND id_creator='.$iduser;
		$query = mysql_query($sql); if(!$query) return false; // fix test
		if(mysql_num_rows($query)>0){
				return true;
		}		
	}
	
	return false;
}

function belongsToUserGroups($currentUser, $user){ // TRUE if $user belongs to any $currentUser groups
	$groups = getAllGroupsByUserId($user);// get les groups de user
	if($groups){
		return checkPermission($groups, $currentUser);
	}
	return false; // user n'a aucun groupe
}


function userIdExists($id){ // Does $id exist ?
	$sql = 'SELECT id FROM users WHERE id='.$id;
	$query = mysql_query($sql);
	if(!$query) return false;
	if (mysql_num_rows($query)<1) return false;
	
	return true;
}


function isConnected(){
	return isset($_SESSION['id']);
}

///////////////////FIN////////////////////// FONCTIONS QUI GERENT LES PERMiSSIONS! /////////////////////FIN///////////////////


//////////////////////////////// GET DES TEXTS BY ID /////////////////////////////////////////////////////////////
function getCountryNameById($id){
	$sql='SELECT name_en FROM country WHERE id_country='.$id;
	$query = mysql_query($sql);
	
	if($query){
		$result = mysql_fetch_assoc($query);
		return $result['name_en'];
	}
	return '';
}

function getGroupNameById($id){
	$sql='SELECT name FROM groups WHERE id='.$id;
	$query = mysql_query($sql);
	
	if($query){
		$result = mysql_fetch_assoc($query);
		return $result['name'];
	}
	return '';
}

function getFriendsById($id){
$sql='SELECT * FROM groups WHERE id_creator='.$id.' AND name="Friends"';
	$query = mysql_query($sql);
	
	if($query){
		$result = mysql_fetch_assoc($query);
		return $result;
	}
	return '';
}

function getUserIdByGroup($id){
	$query = sprintf("SELECT * FROM groups_relations WHERE id_group='%s'",	mysql_real_escape_string($id));
	$result = mysql_query($query);

	if (!$result || mysql_num_rows($result)<=0) {
		return false;
	}else{
		$reponse;
		while ($row = mysql_fetch_assoc($result)) {
			$reponse[]=$row['id_user'];
		}
		return $reponse;
	}
}

function getFirstnameSurnameByUserId($id){
	$sql = 'SELECT firstname, surname FROM users WHERE id='.$id;
	$query = mysql_query($sql);
	if(!$query) return false;
	$result = mysql_fetch_assoc($query);
	return $result['firstname'].' '.$result['surname'];
}

function printLinkToProfileByUserId($id){
	return '<a href="profile.php?id_user='.$id.'" >'.getFirstnameSurnameByUserId($id).'</a>';
}

//////////////////////////////// FIN GET DES TEXTS BY ID /////////////////////////////////////////////////////////////
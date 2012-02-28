<?php


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
  $query = sprintf("SELECT * FROM groups_relations
    WHERE id_user='%s' AND approval='0'",
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

function checkPermission($idgroup,$iduser){
	foreach($idgroup as $idgroupsingle){
		$sql = 'SELECT id FROM groups_relations WHERE id_group='.$idgroupsingle.' AND id_user='.$iduser.' AND approval=1';
		$query = mysql_query($sql);
		if(mysql_num_rows($query)>0){
			return true;
		}
	}		
	return false;
}

function belongsToUserGroups($currentUser, $user){
	$groups = getAllGroupsByUserId($user);// récupère les groups de user
	if($groups){
		return checkPermission($groups, $currentUser);
	}
	return false; // user n'a aucun groupe
}

function isConnected($userid){
	return isset($userid);
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
	$sql = 'SELECT firstname, surname FROM users WHERE id_user='.$id;
	$query = mysql_query($sql);
	if(!$query) return false;
	$result = mysql_fetch_assoc($query);
	return $result['firstname'].' '.$result['surname'];
}

function printLinkToProfileByUserId($id){
	return '<a href="profile.php?id='.$id.'" target="_blank" >'.getFirstnameSurnameByUserId($id).'</a>';
}

//////////////////////////////// FIN GET DES TEXTS BY ID /////////////////////////////////////////////////////////////
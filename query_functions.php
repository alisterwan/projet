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

function retrieve_user_add_infos($id){ // prend en paramètre l'id de l'user, soit $_SESSION['id']

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


	//fonction pour recuperer l'id du groupe par le id_creator
	function getGroupId($idcreator){
	$query = sprintf("SELECT * FROM groups
    WHERE id_creator='%s'",
    mysql_real_escape_string($idcreator));
    $result = mysql_query($query);

   if (!$result) {
   return false;
   }

   else
   while ($row = mysql_fetch_assoc($result)) {
   return $row['id'];
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
	
	  function checkFriendship($idgroup,$iduser){
  $query = sprintf("SELECT * FROM groups_relations
    WHERE id_group='%s' AND id_user='%s' AND approval='1'",
    mysql_real_escape_string($idgroup),
    mysql_real_escape_string($iduser));
    $result = mysql_query($query);
	
	$verif = mysql_num_rows($result);
	
   if ($verif == 0) {
 
   return $row=" <a href='#'><img src='./img/templates/follow.png' width='113px' height='42px' /></a>
 	  <a href='#' id='removeing' onclick='addFriends(event,$userid,$_GET[id_user])'><img src='./img/templates/addfriends.png' width='113px' height='42px' /></a>
 	 
 	 <script>
	  function addFriends(e, id_user, id_friend) {
      var a, url, x;
      e.preventDefault();
      a = e.target.parentNode;
      a.parentNode.hidden = true;
      url = './addFriends.php?id_user='+ id_user +'&id_friend=' + id_friend;
      x = new XMLHttpRequest();
      x.open('GET', url, true);
      x.onload = function(e) {
        a.innerHTML = this.responseText;
        if(this.responseText !== 'success') {
          a.innerHTML = this.responseText;
          a.parentNode.hidden = false;
        }
      };
      x.send();
    }
	</script>
"; 
   }
   
   else {
   return $row="";
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

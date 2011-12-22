<?php


//Functions
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
	
	if (mysql_num_rows($query) == 1){
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
	
	if (mysql_num_rows($query) == 1){
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

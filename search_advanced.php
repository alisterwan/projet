<?php

include './header.php';

if(isset($_GET['recherche']))
{
        $rec = htmlentities($_GET['recherche']);
        
       		
       	/***********************************************/
        //On recherche une recette
		$req = 'SELECT * FROM recipes WHERE ';

        $mots = explode(' ',$rec);//En separre lexpression en mots cles
        foreach($mots as $mot)
        	{
          	$req .= ' name_en LIKE "%'.$mot.'%" OR';
        	}
        
        $req .= ' 1=0';
		$requete = mysql_query($req);
		
		$html.= "
        
        <form action='search_advanced.php' method='get'>
		<strong>Search</strong> <input type='text' name='recherche' value='$rec' />
		<input type='image' src='./img/templates/search.png' width='35px' height='35px'/>
		</form>
        <h2>Results</h2>
        ";
	
		
	
		//On affiche les resultats
		while($dnn = mysql_fetch_array($requete))
		{

	
		//recupere le username	
		$query = "SELECT username FROM users WHERE id='$dnn[id_user]' ";
		$result = mysql_query($query);
		
		$res = mysql_fetch_assoc($result);
	
		//recupere la photo de la recette
		$query2 = "SELECT path_source FROM recipe_photos WHERE id_recipe=$dnn[id]";
		$result2= mysql_query($query2);
		$res2 = mysql_fetch_assoc($result2); 

		$html.="
		<div><a href='./searchrecipe.php?id=$dnn[id]'><img src='$res2[path_source]' 		width='150px' height='150px' /><div>$dnn[name_en] by $res[username]</div></a></div>";
		     
		}
		
				
       	/***********************************************/
		//si la requete n'est pas une recette on recherche dans les users
		
		if (mysql_num_rows($requete)==0){
		//On recherche un user
		$req = 'SELECT * FROM users WHERE ';

        $mots = explode(' ',$rec);//En separre lexpression en mots cles
        foreach($mots as $mot)
        	{
          	$req .= ' username LIKE "%'.$mot.'%" OR';
        	}
        
        $req .= ' 1=0';
		$requete2 = mysql_query($req);
		
		if (mysql_num_rows($requete2)==0){
		$html.= "
       
        <h2>No Results Found</h2>";
		}
	
		//On affiche les resultats
		while($dnn = mysql_fetch_array($requete2))
		{

		$html.="<div><a href='./profile.php?id=$dnn[id]'><img src='$dnn[avatar]' 		width='175px' height='200px' /><div>$dnn[firstname] $dnn[surname] ($dnn[username])</div></a></div>";     
		}
		
       	/***********************************************/
       	
       	
		
		}
          					
		        
		}


		
		
		else
		{
        $rec = '';


//Le formulaire de recherche

$html="

<form action='search_advanced.php' method='get'>
<table>
<tr><td>
<strong>Search</strong> <input type='text' name='recherche' value='$rec' /></td>
<td><input type='image' src='./img/templates/search.png' width='35px' height='35px'/></td>
</tr></table></form>";


}

printDocument('Search');
?>

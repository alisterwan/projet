<?php

include './header.php';

if(isset($_GET['recherche']))
{
        $rec = htmlentities($_GET['recherche']);
}
else
{
        $rec = '';
}
//On determine le type de recherche
if(isset($_GET['type']))
{
        if($_GET['type']=='un')//Par ingredients
        {
                $type = 1;
        }
        elseif($_GET['type']=='tout')//Nom de la recette
        {
                $type = 2;
        }
        else//L'expression exacte
        {
                $type = 3;
        }
}
else
{
        $type = 1;//type par defaut
}


//On dertermine les informations des recettes
$req = 'SELECT * FROM recipes WHERE ';
if($type==1)
{//ayant un des mots dans leurs informations
        $mots = explode(' ',$rec);//En separre lexpression en mots cles
        foreach($mots as $mot)
        {
                $req .= ' name_en LIKE "%'.$mot.'%" OR';
        }
        $req .= ' 1=0';
}
elseif($type==2)
{//ayant tout des mots dans leurs informations
        $mots = explode(' ',$rec);//En separre lexpression en mots cles
        foreach($mots as $mot)
        {
                $req .= ' name_en LIKE "%'.$mot.'%" AND';
        }
        $req .= ' 1=1';
}


//Les recettes seront ranges par noms en ordre croissant
$req .= ' order by name_en asc';
$requete = mysql_query($req);




//Le formulaire de recherche

$html="

<form action='search.php' method='get'>
<table>
<tr>
<td><strong>Search</strong> <input type='text' name='recherche' value='$rec' /></td><td>
<input type='image' src='./img/templates/search.png' width='35px' height='35px'/>
</td>
</tr>
<tr><td><input type='radio' name='type' value='un' <?php if($type==1) checked='checked' ?> Keywords <input type='radio' name='type' value='tout' <?php if($type==2)checked='checked' ?>Name</td></tr>
</table>
</form>";


	if($_GET[recherche]){
	
	$html.= "<h2>Results</h2>";
	
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
		<div>
                <a href='./searchrecipe.php?id=$dnn[id]'><img src='$res2[path_source]' width='150px' height='150px' />
	        <div>";

		

	$html.="
	$dnn[name_en] by $res[username]
		</div>
		</a>
	</div>
	";
		
	}
}

printDocument('Search');
?>

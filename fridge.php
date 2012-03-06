<?php include 'header.php';


/***************fonctions***************************/

//fonction pour verifier si deux users sont amis
  
/*
La fonction getAllGroupsByUserId($id) récupère un array des numéros des groupes auxquel $id appartient.

La fonction checkPermission($vargroup, $user) renvoie TRUE si le $user appartient à au moins un des groupes $vargroup. 
  
printAddNewFriend($userid) imprime les boutons pour ajouter en ami ou follow. $userid est l'ID du demandeur.
/**************************************************/

/////////////// GETTERS

function getAllGroupsExceptFollowersByUserId($idcreator){
	$query = sprintf("SELECT * FROM groups	WHERE id_creator='%s'",	mysql_real_escape_string($idcreator));
	$result = mysql_query($query);

	if (!$result) {
		return false;
	}else{
		$reponse;
		while ($row = mysql_fetch_assoc($result)) {
			if($row['name']!='Followers') $reponse[]=$row['id'];
		}
		return $reponse;
	}
}

function isLoggedVisitor(){
	global $userid;
	return (isConnected() && isset($_GET['id_user']) && $_GET['id_user']!=$userid);
}

function isFriend(){
	global $userid;
	if(isConnected()){
		if(isLoggedVisitor()){
			return checkPermission(getAllGroupsExceptFollowersByUserId($_GET['id_user']), $userid);
		}
	}	
	return false;
}

function isVisitor(){
	global $userid;
	if(!isConnected() || isLoggedVisitor()){
		return true;
	}
	return false;
}

function isLost(){ // non connected and not visiting anything lol
	global $userid;
	if(!isConnected() && !isVisitor()) return true;
	
	return false;
}

function isOwner(){
	return (!isLost() && !isFriend() && !isVisitor());
}
////////////// END GETTERS


function leftboxContent(){
	$content ='';
	global $userid;
	
	if(!isLost()){
		if (isset($_SESSION['id'])) { // if logged in
			if (isLoggedVisitor()) { // if visitor 
				// Requête qui récupère toutes les coordonnées du client
				$userinfos=retrieve_user_infos($_GET['id_user']);
				$content.= "<img src= '$userinfos[avatar]' width='170px' height='200px' />";
			}else{
				// Requête qui récupère toutes les coordonnées du client
				global $userid;
				$userinfos=retrieve_user_infos($userid);
				$content.= "<img src= '$userinfos[avatar]' width='170px' height='200px'><a href='./image.php'><img src= './img/templates/camera.png' width='50px' height='50px'></a>Change my avatar";
				
			}
		}else if(isset($_GET['id_user'])){ // non logged in visitor
			// Requête qui récupère toutes les coordonnées du client
			$userinfos=retrieve_user_infos($_GET['id_user']);
			$content.= "<img src= '$userinfos[avatar]' width='170px' height='200px' />";
		}
		
		$content.= '<br/><br/>';
		
		if(isVisitor()){ // print link to Profile
			$content.= '<a href="profile.php?id_user='.$_GET['id_user'].'" >Profile</a>';
		}else{
			$content.= '<a href="profile.php" >Profile</a>';
		}		
		$content.= '<br/>';		
		if(isVisitor()){ // print link to wall
			$content.= '<a href="wall.php?id='.$_GET['id_user'].'" >Wall</a>';
		}else{
			$content.= '<a href="wall.php" >Wall</a>';
		}
		$content.= '<br/>';
		if(isVisitor()){ // print link to friend list
			$content.= '<a href="friends.php?id='.$_GET['id_user'].'" >Friends list</a>';
		}else{
			$content.= '<a href="friends.php" >Friends list</a>';
		}
		$content.= '<br/>';	
		if(isFriend()){ // print link to messages
			$content.= '<a href="private_messages.php?id_recipient='.$_GET['id_user'].'" >Private Messages</a>';
		}elseif(isOwner()){
			$content.= '<a href="private_messages.php" >Private Messages</a>';
			$content.= '<br/>';
			$content.= '<a href="fridge.php" >Fridge</a>';
			$content.= '<br/>';
			$content.= '<a href="shoplist.php" >Shoplist</a>';	
		}
		$content.= '<br/>';
	}
	return $content;
}


if ($_POST) { // si le user submit
	$i = CountIngredients($userid);
	$i = $i+1;
		while ($_POST[$i]) {
			$ing = getidIngredient($_POST[$i]);
				if($ing){
				$sql = insertIntoFridgeExistingIng($_POST[$i],$userid);
				$i++;
				}
				else{
				$sql = insertIntoFridgeIng($_POST[$i],$userid);
				$i++;
				}
					
		}

	}


/////////////////////////////// PRINTERS /////////////////////////////
function printInfoMember($id){
	$infos = retrieve_user_add_infos($id);
	$ficelle = "<h4>";
	
	if($infos['date_birth']!="") $ficelle.= 'Born in '.$infos['date_birth'].'<br/>';
	if($infos['job']!="") $ficelle.= 'Works as '.$infos['job'].'<br/>';
	if($infos['music']!="") $ficelle.= 'Listens to '.$infos['music'].'<br/></h4>';
	
	return $ficelle;
}

function printProfileBanner(){
	return "<div id='content'>
		<div id='dock'>
			<div class='dock-container'>				
				<a class='dock-item' href='newmessage.php'><span>Messages</span><img src='img/dock/email.png' alt='messages'></a> 			
				<a class='dock-item' href='groups.php'><span>Groups</span><img src='img/dock/portfolio.png' alt='history'></a> 			
				<a class='dock-item' href='followers.php'><span>Followers</span><img src='img/dock/link.png' alt='links'></a> 
				<a class='dock-item' href='#'><span>RSS</span><img src='img/dock/rss.png' alt='rss'></a> 			
			</div>
		</div>
		</div>";
}


function MyFridge($userid){
	$html = "<h2>My Fridge</h2>";
	$sql="SELECT * from fridge WHERE id_user='$userid'";
	$result = mysql_query($sql);
	$verif = mysql_num_rows($result);
		if($verif>0){
		    $html.= "<div>What you have in your fridge: </div>";
		    $html.= "<ol>";
		    	while ($var = mysql_fetch_assoc($result)){
		    		$html.= "
		    		<li>
		    		$var[text_default] status:$var[status]
		    		<a>Available</a> 
		    		<a>Unavailaible</a>
		    		<a href='#' onclick='removeIngOnfridge(event,$var[id])'><img src='./img/templates/deleteing.png' width='10px' height='10px'></a>
		    		</li>";
		   		}
			$html.= "</ol>";
			return $html;
		}	
		else{
			$html.= "<p class='error'>Your fridge is empty. Please fill it.</p>";
			return $html;
		} 
}


function CountIngredients($userid){
	$sql="SELECT * from fridge WHERE id_user='$userid'";
	$result = mysql_query($sql);
	$verif = mysql_num_rows($result);
	
	return $verif;
}

function IngredientAdd($j){
	return "
	<a id='more' href='#'>Add ingredient...</a><br>
		<script>
			var i = $j+1;
			$('#more').on('click', function(e) {
				e.preventDefault();
				$(this).before('<label>Ingredient '+i+'<input type=\"text\" name='+i+' list=\"ingredientList\"></label>');
				$(this).prev().updatePolyfill();
				i++;
			});
		</script>

	";

}

function printFormAddIngredient($userid){
	$var = CountIngredients($userid);
	$html ="
	<form action='fridge.php' method='post' id='contribution' enctype='multipart/form-data'>";
	$html.= IngredientAdd($var);
	$html.="<input type='submit' value='Submit'>
	";
	return $html;
}


//fonction pour recuperer les infos de l'ingredient
function getidIngredient($name){
	$query = "SELECT * FROM ingredients WHERE name_en='$name'";
	$result = mysql_query($query);
	$verif = mysql_num_rows($result);
	if ($verif==0) {
		return false;
		}  
	return mysql_fetch_assoc($result);
}


function insertIntoFridgeExistingIng($i,$userid){
	$i = getidIngredient($i);
	
	$query = sprintf("INSERT INTO fridge(id_user,id_ingredient,text_default,status) VALUES('%s','%s', '%s',2);",
		mysql_real_escape_string(strip_tags($userid)),
		mysql_real_escape_string(strip_tags($i['id'])),
		mysql_real_escape_string(strip_tags($i['name_en'])));
	$result = mysql_query($query);
	if ($result) {
		return $result;
		} 
	else {
			die('Error: '.mysql_error());
		   }

}

function insertIntoFridgeIng($text,$userid){
	$query = sprintf("INSERT INTO fridge(id_user,text_default,status) VALUES('%s', '%s', '%s');",
		mysql_real_escape_string(strip_tags($userid)),
		mysql_real_escape_string(strip_tags($text)),
		mysql_real_escape_string(strip_tags('2')));
	$result = mysql_query($query);
	
		if ($result) {
		return $result;
		} 
	else {
			die('Error: '.mysql_error());
		   }
}


////////////////////////////////////////////END FUNCTIONS////////////////////////////////////////////////////



if (isset($userid)){  // vérification si logué en tant qu'utilisateur
	$html = '';
	$userinfos=retrieve_user_infos($userid);
	$useraddinfos=retrieve_user_add_infos($userid);
	$userfriends = retrieve_user_friends($userid);  
  
	$html.="<script>	
			function removeIngOnfridge(e, id) {
      var a, url, x;
      e.preventDefault();
      a = e.target.parentNode;
      a.parentNode.hidden = true;
      url = './deleteOnFridge.php?id=' + id;
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

	$html.= MyFridge($userid);
	$html.= printFormAddIngredient($userid);


	//requête pour recupérer les ingrédients
	$ingredients = mysql_query("SELECT name_en,id FROM ingredients");
	while ($ingredient = mysql_fetch_array($ingredients)) {
	$list2 .= "<option value='$ingredient[0]'>$ingredient[0]</option>";
	}
	$html .= "<datalist id='ingredientList'>$list2</datalist>";
	
	
	

	 printDocument('My Fridge'); 
}



/*******************************VISITEURS NON INSCRITS*************************************/

else if (isset($_GET['id_user'])){ // pour les visiteurs
	$html.= RegistrationForVisitors();
	printDocument('Profile');
}
 
 /********************************************************************/
?>

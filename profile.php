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
				$content.="<div class='stack'>
					<img src='img/stacks/stack.png' alt='stack'>
					<ul id='stack'>
						<li><a href='objectivesform.php'><span>Objectives</span><img src='img/stacks/objectives.png' alt='My Objectives'></a></li>
						<li><a href='information.php'><span>Information</span><img src='img/stacks/information.png' alt='My infos'></a></li>			
						<li><a href='albums.php'><span>Albums</span><img src='img/stacks/albums.png' alt='My albums'></a></li>
						<li><a href='friends.php'><span>Friends</span><img src='img/stacks/myfriends.png' alt='My friends'></a></li>	
						<li><a href='recipes.php'><span>Recipes</span><img src='img/stacks/recipes.png' alt='My recipes'></a></li>				
					</ul>
					</div>"; // printstack
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
			$content.= '<br/>';
			$content.= '<a href="recipes.php?iduser='.$_GET['id_user'].'">Recipes</a>';
		}
		
		if(isOwner()){
			$content.= '<a href="recipes.php">Recipes</a>';
			$content.= '<br/>';
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

////////////////////////////// PROCESS ////////////////////////////////
//fonction update users
function updateUser($firstname,$surname,$address,$country,$username,$email, $userid){ // fonction qui update sur la BDD

	if($country==0) {
		$country=NULL;
		$query = sprintf("UPDATE users SET firstname='%s', surname='%s', address='%s',country=NULL, username='$username', mail='$email' WHERE id=$userid;",
		mysql_real_escape_string(strip_tags($firstname)),
		mysql_real_escape_string(strip_tags($surname)),
		mysql_real_escape_string(strip_tags($address)));
	}else{
		$query = sprintf("UPDATE users SET firstname='%s', surname='%s', address='%s',country=$country, username='$username', mail='$email' WHERE id=$userid;",
		mysql_real_escape_string(strip_tags($firstname)),
		mysql_real_escape_string(strip_tags($surname)),
		mysql_real_escape_string(strip_tags($address)));
	}
	$res = @mysql_query($query);
	if(!$res)
		die("Error: ".mysql_error());
	else
		return $res;
}

// Fonction qui insere les infos perso d'un user dans la bdd
	function insertInfo($date,$hobbies,$job,$music,$films,$books,$aboutme,$favouritefood, $userid){
		$query = sprintf("INSERT INTO information(date_birth,hobbies,job,music,films,books,aboutme,favouritefood,id_user) VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s');",
		mysql_real_escape_string(strip_tags($date)),
		mysql_real_escape_string(strip_tags($hobbies)),
		mysql_real_escape_string(strip_tags($job)),
		mysql_real_escape_string(strip_tags($music)),
		mysql_real_escape_string(strip_tags($films)),
		mysql_real_escape_string(strip_tags($books)),
		mysql_real_escape_string(strip_tags($aboutme)),
		mysql_real_escape_string(strip_tags($favouritefood)),
		mysql_real_escape_string(strip_tags($userid)));
		$res = @mysql_query($query);
		if(!$res)
			die("Error: ".mysql_error());
		else
			return $res;
	}
function updateInfo($date,$hobbies,$job,$music,$films,$books,$aboutme,$favouritefood,$userid){ // fonction qui update sur la BDD
	
	$query = "UPDATE information SET date_birth='$date', hobbies='$hobbies', job='$job', 
	music='$music', films='$films', books='$books', aboutme='$aboutme', favouritefood='$favouritefood' WHERE id_user=$userid ";
	$res = @mysql_query($query);
	if(!$res){
		//die("Error: ".mysql_error());
		return false;
	}
	else{
		return $res;
	}
}
	
	

/////////////////////////////// PRINTERS /////////////////////////////
function printInfoMember($id){
	$info = retrieve_user_add_infos($id);
	/*$ficelle = "<h4>";
	
	if($infos['date_birth']!="") $ficelle.= 'Born in '.$infos['date_birth'].'<br/>';
	if($infos['job']!="") $ficelle.= 'Works as '.$infos['job'].'<br/>';
	if($infos['music']!="") $ficelle.= 'Listens to '.$infos['music'].'<br/></h4>';*/
	
	$ficelle="";
		
	if ($info['date_birth'])
		$ficelle.="	<b> Born </b>: $info[date_birth] ";
	if ($info['hobbies'])
		$ficelle.="<br>	<b> Hobbies </b>: $info[hobbies] ";
	if ($info['job'])
		$ficelle.="<br>	<b> Job </b>: $info[job] ";
	if ($info['music'])
		$ficelle.="	<br> <b> Music </b>: $info[music] ";
	if ($info['films'])
		$ficelle.="	<br> <b> Films </b>: $info[films] ";
	if ($info['books'])
		$ficelle.="<br>	<b> Books </b>: $info[books] ";
	if ($info['aboutme'])
		$ficelle.="<br>	<b> About me  </b>:". $info['aboutme'] ;
	if ($info['favouritefood'])
		$ficelle.="	<br><b> Favourite food  </b>: $info[favouritefood] ";
		
	return $ficelle;
}

/************************************************************************************************************************************************************************************************************/
/************************************************************************************************************************************************************************************************************/
/************************************************************************************************************************************************************************************************************/
/************************************************************************************************************************************************************************************************************/

function printAccountInformation ($id){

	$res2 = retrieve_user_infos($id);


	$ficelle ="
		<br/> <b> Firstname </b>:  $res2[firstname]
		<br/> <b> Surname </b> : $res2[surname]";
		if ($res2['sex']==1)	
			$ficelle.=" <br/> <b> Sex </b> : Masculin";
		else $ficelle.=" <br/> <b> Sex </b> : Feminin";
		
		if ($res2['address'])
			$ficelle.="	<br/> <b> Address </b> : $res2[address] ";
		if ($res2['city'])
			$ficelle.="	<br/> <b> City </b> : $res2[city] ";
			
			$country= getCountryNameById($res2['country']);
			
		if ($res2['country'])
			$ficelle.="	<br/> <b> Country </b> : $country ";
	$ficelle.="
		<br/> <b> Email </b>: $res2[mail]";
	
	return $ficelle;
}
/************************************************************************************************************************************************************************************************************/
/************************************************************************************************************************************************************************************************************/
/************************************************************************************************************************************************************************************************************/
/************************************************************************************************************************************************************************************************************/

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

function printAddNewFriend($userid){
	return "
		  <a href='#' id='removeing' onclick='addFriends(event,$userid,$_GET[id_user])'><img src='./img/templates/addfriends.png' width='113px' height='42px'></a>
		 
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
		</script>";	
}

function printAddNewSubscriber($userid){
	return "<a href='#' onclick='addSubscribers(event,$userid,$_GET[id_user])'><img src='./img/templates/follow.png' width='113px' height='42px'></a>
	
		 <script>
		  function addSubscribers(e, id_user, id_friend) {
		  var a, url, x;
		  e.preventDefault();
		  a = e.target.parentNode;
		  a.parentNode.hidden = true;
		  url = './addSubscribers.php?id_user='+ id_user +'&id_friend=' + id_friend;
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
		</script>";	
}

function printEditAccountInfoForm($userinfos){
	$content = '';
	$username=$userinfos['username'];
	$firstname=$userinfos['firstname'];
	$surname=$userinfos['surname'];
	$mail=$userinfos['mail'];
	$address=$userinfos['address'];
	//$city=$userinfos['city'];
	$country= getCountryNameById($userinfos['country']);		
					
	// affichage champs profile, c'est tout ce pâté
	$content.= '<p><h4>Your profile information:</h4></p>
					<form action="profile.php?mode=edit_account_infos&action=edit_account_infos_process " method="post" id="contribution">
					<label>Firstname:<input type="text" name="firstname" value='.$firstname.' required></label>
					<label>Surname:<input type="text" name="surname" value='.$surname.' required></label>			
					<label>Country:<input type="text" name="id_country" list="countryList" value="'.$country.'" /></label>			
					<label>Address:<input type="text" name="address" value="'.$address.'"></label>
					<label>Username:<input type="text" name="username" value='.$username.' required></label>
					<label>Email:<input type="text" name="mail" value='.$mail.' required></label>
					<div><button type="submit">Update</button></div>
	</form>';

	//requete pour recuperer les pays 
	$countries = mysql_query("SELECT id_country,name_en FROM country");
	$list='';// ne pas oublier d'initialiser
	while($res = mysql_fetch_assoc($countries)) {
		$list .= "<option value='$res[name_en]' >";
	}
	$content.= "<datalist id='countryList'>$list</datalist>";
	
	return $content;
}


function printProfileForm($useraddinfos, $do){
	$date=$useraddinfos['date_birth'];
	$hobbies=$useraddinfos['hobbies'];
	$job=$useraddinfos['job'];
	$music=$useraddinfos['music'];
	$films=$useraddinfos['films'];
	$books=$useraddinfos['books'];
	$aboutme=$useraddinfos['aboutme'];
	$favouritefood=$useraddinfos['favouritefood'];
			
	$content = '';
	$content.= "<p><h4>Edit your personal information:</h4></p><br/>";
	
	if($do == "insert") {
		$content.= '<form action="profile.php?mode=edit_profile&action=new_insert_process" method="post" id="contribution">';
	}
	if($do == "update"){
		$content.= '<form action="profile.php?mode=edit_profile&action=update_process" method="post" id="contribution">';
	}
	
	$content.="
		<span id='myFormCalendar' class='tswFormCalendar'>
		<label for='myDateInput'>Date of birth (yyyy-mm-dd):</label> 
			<a href='javascript:tswFormCalendarGetForId(\"myFormCalendar\").togglePopUp();'>
			<span id='myFormCalendar_tswButton'
				class='tswFormCalendarButton'></span></a>
	</span>
	<script type='text/javascript'>
		//Initialize Form Calendar
		var tswFormCalendar = tswFormCalendarGetForId(\"myFormCalendar\");
		tswFormCalendar.dateFormat = 'yyyy-MM-dd';
	</script>
		<input id='myFormCalendar_tswInput' 
			name='myDateInput' value='$date'
			onkeyup='tswFormCalendarGetForId(\"myFormCalendar\").updateDates();'
			type='text' size='20' maxlength='10'/> 
			<label>Hobbies:</td><td><input type='textarea' name='hobbies' value='$hobbies'></label>
			<label>Job:</td><td><input type='text' name='job' value='$job'></label>
			<label>Music:</td><td><input type='textarea' name='music' value='$music'></label>
			<label>Films:</td><td><input type='textarea' name='films' value='$films'></label>
			<label>Books:</td><td><input type='textarea' name='books' value='$books'></label>
			<label>About me:</td><td><input type='textarea' name='aboutme' value='$aboutme'></label>
			<label>Favourite food:</td><td><input type='textarea' name='favouritefood' value='$favouritefood'></label>
			<label><button type='submit'>Update my information &rarr;</button></label>
			</table>
	</form>";

	return $content;
}

function getRecipeInfosbyUser($iduser){ 
	$sql='SELECT * FROM recipes WHERE id_user='.$iduser;
	$query=mysql_query($sql);
	$verif = mysql_num_rows($query);

	if ($verif > 0){
	return $result=mysql_fetch_assoc($query);
	}
	return false;
  }


function getUserRecipesbyUserID($userid){
$query = sprintf("SELECT * FROM recipes WHERE id_user='%s'",
	mysql_real_escape_string($userid));
	$result = mysql_query($query);

	if (!$result){
	$html ="
	<p>You haven't got recipes</p>";
	}
	else {
	$html ="";
		while($row3=mysql_fetch_assoc($result)) {
		$query2 = "SELECT * FROM recipe_photos WHERE id_recipe=$row3[id]";
		$result2 = mysql_query($query2);
		$row2=mysql_fetch_assoc($result2);

		$html.="<div><a href='./recipe.php?id=$row3[id]'><img 		src='$row2[path_source]' width='60px' height='60px' alt='$row3[name_en]' 	title='$row3[name_en]'/><br/>$row3[name_en]</a> $row3[creation]</div>";
		}

	}
return $html;
}

function getLatestPosts(){

	global $userid;

	$sql = 'SELECT id, date FROM wall_post WHERE ';
	$sql.= 'id_user='.$userid;
	$sql.= ' ORDER BY date DESC LIMIT 0, 10';
	
	$query = mysql_query($sql);
	if(!$query || mysql_num_rows($query)<1) return false;

	$posts;
	while($result = mysql_fetch_assoc($query)){
		$posts[] = $result['id'];
	}
	return $posts;
}

function printWallPostById($idpost){ // display a Post and Comments
	global $userid;
	$sql = 'SELECT * FROM wall_post WHERE id='.$idpost;
	$query = mysql_query($sql);
	if(!$query) return false;

	$result = mysql_fetch_assoc($query);
	$id_post = $result['id'];
	$id_user = $result['id_user'];
	$post_type = $result['post_type'];
	$content = $result['content'];
	$approval = $result['approval'];
	$date = $result['date'];

	$ficelle ='';
	$ficelle.= '<table style="text-align: left; " border="1" cellpadding="2" cellspacing="2">';
	$ficelle.= '<tr><td>'.printAvatarByUserId($id_user).'<br/>';// case avatar
	$ficelle.= printRatingPost($idpost).'</td>'; // ratings

	$ficelle.= '<td>'.printLinkToProfileByUserId($id_user).' - '.$date.'<br/>';
	$ficelle.= $content.'<hr/>';
	$ficelle.= printLikeDislikePost($id_post).'<br/>'.printPermissionChoices($id_post, getCreatorIdByPostId($id_post)).'<hr/>';
	$ficelle.= printAllCommentsByWallPostId($id_post);
	$ficelle.= '</td></tr></table>';

	return $ficelle;
}

/**************** CONTRAINTES *********************/	
			
	function IngredientAddProhib($j){
	return "
	<a id='more' href='#'>Add ingredient</a><br>
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
	
	function IngredientAddLike($k){
	return "
	<a id='more' href='#'>Add ingredient</a><br>
		<script>
			var i = $k+1;
			$('#more').on('click', function(e) {
				e.preventDefault();
				$(this).before('<label>Ingredient '+i+'<input type=\"text\" name='+i+' list=\"ingredientList\"></label>');
				$(this).prev().updatePolyfill();
				i++;
			});
		</script>

	";
	}
	
	function IngredientAddDislike($k){
	return "
	<a id='more' href='#'>Add ingredient</a><br>
		<script>
			var i = $k+1;
			$('#more').on('click', function(e) {
				e.preventDefault();
				$(this).before('<label>Ingredient '+i+'<input type=\"text\" name='+i+' list=\"ingredientList\"></label>');
				$(this).prev().updatePolyfill();
				i++;
			});
		</script>

	";
	}
	
	
	function getAllInMyProhib($userid){
		$html = "<fieldset>
		<legend>User Preference Prohibition<a href=\"profile.php?mode=edit_prohib\" > EDIT </a></legend>";
		$html.= "<ul>";
		$sql="SELECT * from user_preference_prohibition WHERE id_user='$userid'";
		$result = mysql_query($sql);
		
		if(!$result || mysql_num_rows($result)==0){
			$html.= "<div>Empty, please fill it</div>";
		}else{			
			while($row = mysql_fetch_assoc($result)){
				$html.= "<li>$row[name_en]<a href='#' onclick='removeIngOnProhib(event,$row[id])'><img src='./img/templates/deleteing.png' width='10px' height='10px'></a></li>";
			}	
		}
		$html.= "</ul></fieldset>";
		return $html;
	}
	
	function getAllIngILike($userid){
	$html = "<fieldset>
	<legend>User Preference Like <a href=\"profile.php?mode=edit_like\" > EDIT </a></legend>";
	$html.= "<ul>";
	$sql="SELECT * from user_preference_like WHERE id_user='$userid'";
	$result = mysql_query($sql);
	
	if(!$result || mysql_num_rows($result)==0){
		$html.= "<div>Empty, please fill it</div>";
	}else{		
		while($row = mysql_fetch_assoc($result)){
			$html.= "<li>$row[name_en]<a href='#' onclick='removeIngOnLike(event,$row[id])'><img src='./img/templates/deleteing.png' width='10px' height='10px'></a></li>";
		}	
	}
	$html.= "</ul></fieldset>";
	return $html;
	}
	
	function getAllIngIDislike($userid){
	$html = "<fieldset>
	<legend>User Preference Dislike <a href=\"profile.php?mode=edit_dislike\" > EDIT </a></legend>";
	$html.= "<ul>";
	$sql="SELECT * from user_preference_dislike WHERE id_user='$userid'";
	$result = mysql_query($sql);
	
	if(!$result || mysql_num_rows($result)==0){
		$html.= "<div>Empty, please fill it</div>";
	}else{		
		while($row = mysql_fetch_assoc($result)){
			$html.= "<li>$row[name_en]<a href='#' onclick='removeIngOnDislike(event,$row[id])'><img src='./img/templates/deleteing.png' width='10px' height='10px'></a></li>";
		}	
	}
	$html.= "</ul></fieldset>";
	return $html;
	}
	
	
	function CountIngredientsProhib($userid){
	$sql="SELECT * from user_preference_prohibition WHERE id_user='$userid'";
	$result = mysql_query($sql);
	$verif = mysql_num_rows($result);
	
	return $verif;
}

	function CountIngredientsLike($userid){
	$sql="SELECT * from user_preference_like WHERE id_user='$userid'";
	$result = mysql_query($sql);
	$verif = mysql_num_rows($result);
	
	return $verif;
}

	function CountIngredientsDislike($userid){
	$sql="SELECT * from user_preference_dislike WHERE id_user='$userid'";
	$result = mysql_query($sql);
	$verif = mysql_num_rows($result);
	
	return $verif;
}

function printFormAddIngredientProhib($userid){
		//instructions pour rechercher
	if(isset($_POST)){	
	$i = CountIngredientsProhib($userid)+1;
	while (isset($_POST[$i])) {
		$ing = getidIngredient($_POST[$i]);
		if($ing){
			$sql = insertIntoUserProhibExistingIng($_POST[$i],$userid);
			$i++;
		}else{
			$sql = insertIntoUserProhibIng($_POST[$i],$userid);
			$i++;
		}					
	}	
	}
	
	$var = CountIngredientsProhib($userid);

	$html ="
	<form action='profile.php?mode=edit_contrainte' method='post' id='contribution' enctype='multipart/form-data'>";
	$html.= IngredientAddProhib($var);
	$html.="<input type='submit' value='Submit'>
	";


	return $html;
}


function printFormAddIngredientLike($userid){
		//instructions pour rechercher
	if(isset($_POST)){	
	$i = CountIngredientsLike($userid)+1;
	while (isset($_POST[$i])) {
		$ing = getidIngredient($_POST[$i]);
		if($ing){
			$sql = insertIntoUserLikeExistingIng($_POST[$i],$userid);
			$i++;
		}else{
			$sql = insertIntoUserLikeIng($_POST[$i],$userid);
			$i++;
		}					
	}	
	}
	
	$var = CountIngredientsLike($userid);

	$html ="
	<form action='profile.php?mode=edit_like' method='post' id='contribution' enctype='multipart/form-data'>";
	$html.= IngredientAddLike($var);
	$html.="<input type='submit' value='Submit'>
	";


			return $html;
}
	
function printFormAddIngredientDislike($userid){
		//instructions pour rechercher
	if(isset($_POST)){	
	$i = CountIngredientsDislike($userid)+1;
	while (isset($_POST[$i])) {
		$ing = getidIngredient($_POST[$i]);
		if($ing){
			$sql = insertIntoUserDislikeExistingIng($_POST[$i],$userid);
			$i++;
		}else{
			$sql = insertIntoUserDislikeIng($_POST[$i],$userid);
			$i++;
		}					
	}	
	}
	
	$var = CountIngredientsDislike($userid);

	$html ="
	<form action='profile.php?mode=edit_dislike' method='post' id='contribution' enctype='multipart/form-data'>";
	$html.= IngredientAddDislike($var);
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


function insertIntoUserProhibExistingIng($i,$userid){
	$i = getidIngredient($i);
	
	$query = sprintf("INSERT INTO user_preference_prohibition(id_user,name_en,name_fr) VALUES('%s','%s', '%s');",
		mysql_real_escape_string(strip_tags($userid)),
		mysql_real_escape_string(strip_tags($i['name_en'])),
		mysql_real_escape_string(strip_tags($i['name_fr'])));
	$result = mysql_query($query);
	if ($result) {
		return $result;
		} 
	else {
			die('Error: '.mysql_error());
		   }

}

function insertIntoUserLikeExistingIng($i,$userid){
	$i = getidIngredient($i);
	
	$query = sprintf("INSERT INTO user_preference_like(id_user,name_en,name_fr) VALUES('%s','%s', '%s');",
		mysql_real_escape_string(strip_tags($userid)),
		mysql_real_escape_string(strip_tags($i['name_en'])),
		mysql_real_escape_string(strip_tags($i['name_fr'])));
	$result = mysql_query($query);
	if ($result) {
		return $result;
		} 
	else {
			die('Error: '.mysql_error());
		   }

}

function insertIntoUserDislikeExistingIng($i,$userid){
	$i = getidIngredient($i);
	
	$query = sprintf("INSERT INTO user_preference_dislike(id_user,name_en,name_fr) VALUES('%s','%s', '%s');",
		mysql_real_escape_string(strip_tags($userid)),
		mysql_real_escape_string(strip_tags($i['name_en'])),
		mysql_real_escape_string(strip_tags($i['name_fr'])));
	$result = mysql_query($query);
	if ($result) {
		return $result;
		} 
	else {
			die('Error: '.mysql_error());
		   }

}


function insertIntoUserProhibIng($name_en,$userid){
	$query = sprintf("INSERT INTO user_preference_prohibition(id_user,name_en) VALUES('%s', '%s');",
		mysql_real_escape_string(strip_tags($userid)),
		mysql_real_escape_string(strip_tags($name_en)));
	$result = mysql_query($query);
	
		if ($result) {
		return $result;
		} 
	else {
			die('Error: '.mysql_error());
		   }
}
	
function insertIntoUserLikeIng($name_en,$userid){
	$query = sprintf("INSERT INTO user_preference_like(id_user,name_en) VALUES('%s', '%s');",
		mysql_real_escape_string(strip_tags($userid)),
		mysql_real_escape_string(strip_tags($name_en)));
	$result = mysql_query($query);
	
		if ($result) {
		return $result;
		} 
	else {
			die('Error: '.mysql_error());
		   }
}			

function insertIntoUserDislikeIng($name_en,$userid){
	$query = sprintf("INSERT INTO user_preference_dislike(id_user,name_en) VALUES('%s', '%s');",
		mysql_real_escape_string(strip_tags($userid)),
		mysql_real_escape_string(strip_tags($name_en)));
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
  

		

	if(isset($_GET['id_user']) && $_GET['id_user']!= $userid){ //pour les users qui visitent les profiles
		$userinfos=retrieve_user_infos($_GET['id_user']);
		$useraddinfos=retrieve_user_add_infos($_GET['id_user']);
		  
		$html.= "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>";
		//$html.= printProfileBanner();
		  
		if(!$useraddinfos){ // "visiteur", mais no content available
			$html.="<div>Sorry, there's no content available to show.</div>";
		 
			$vargroup = getAllGroupsByUserId($userid);
			$var = checkPermission($vargroup,$_GET['id_user']);
		   
		    if(!$var){
				$html.= printAddNewSubscriber($userid);
				$html.= printAddNewFriend($userid);
				
			}		
		}else{ // "visiteur", content available 	 
			$html.= printInfoMember($_GET['id_user']);
			$vargroup = getAllGroupsByUserId($userid);
			$var = checkPermission($vargroup,$_GET['id_user']);

			if(!$var) $html.= printAddNewFriend($userid);
		}
	}else{ // user viewing its own profile
	
		if(!isset($_GET['mode'])){ // no mod defined
			/////////////////////// Affichage du nom et bannière élémentaire ////////////////////////
			$html.= "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>";
			//$html.= printProfileBanner();
			/////////////////////// FIN Affichage du nom et bannière élémentaire ////////////////////////
			
			if($useraddinfos){ // affichage infos passion du membre
				$html.=" <b> <p align=\"center\"> INFORMATIONS PERSONELLES |  <a href=\"profile.php?mode=edit_account_infos\" >Edit</a> </p> </b> ";
				$html.=printAccountInformation($userid);
				$html.=" <br> <b> <p align=\"center\">  HOBBIES / ABOUT ME | <a href=\"profile.php?mode=edit_profile\" >Edit</a></p> </b>";
				$html.=printInfoMember($userid);
				$html.=" <br> <b> <p align=\"center\">  CONTRAINTES ALIMENTAIRES </p> </b>";
				$html.=	getAllInMyProhib($userid);
				$html.= getAllIngILike($userid);	
				$html.= getAllIngIDislike($userid);	
			}
		
			//$html.= '<br/><br/><a href="profile.php?mode=edit_profile" />Edit your profile</a> | <a href="profile.php?mode=edit_account_infos" />Edit your account informations</a>';
			$html.="<h4>Lastest posts</h4>";
			$html.= getUserRecipesbyUserID($userid);
		
		}elseif(isset($_GET['mode']) && $_GET['mode']=="edit_account_infos"){ // edit account infos
			// Mise à jour des données
			if ($_POST && isset($_GET['action']) && $_GET['action']=="edit_account_infos_process") { // ça va appeler la fonction qui va modifier la BDD : updateUser
				
				$query1 = mysql_query("SELECT * FROM country WHERE name_en='$_POST[id_country]'");
				if(!$query1){ // pays non reconnu
					$country=0;
				}else{
					$res2 = mysql_fetch_assoc($query1);	
					$country   = $res2['id_country'];			
				}
				
				$firstname = $_POST['firstname'];
				$surname   = $_POST['surname'];
				$address   = $_POST['address'];
				//$city      = $_POST['city'];		
				$username  = $_POST['username'];
				$mail      = $_POST['mail'];
		/************************************************************************************************************************************************************************************************************/
/************************************************************************************************************************************************************************************************************/
/************************************************************************************************************************************************************************************************************/
/************************************************************************************************************************************************************************************************************/		
				//mise a jour dans la bdd
				$res = updateUser($firstname,$surname,$address,$country,$username,$mail, $userid);

				if(!$res){
					$message = "Query error";  
				}else{
					unset($_POST);
					header("Location: profile.php?mode=edit_account_infos&updatesuccess=1");
					//Echo "Update successfully";
				}	    	

				// Ne pas envoyer le POST dans header.php
				unset($_POST);
				
			}else{ // Affichage edit_account_infos

				if (isset($_GET['updatesuccess'])){
					$html.="Update successful";
				}

				$userinfos=retrieve_user_infos($userid); // retrieve_user_infos renvoit un tableau associatif contenant toutes les infos d'un user

				if($userinfos!=false){// vérifie si la fonction est bien passée
					$html.= printEditAccountInfoForm($userinfos);

				}else{
					$message = "<p class='error'>Table USER error</p>";
				}

			} // fin affichage profile
			
			$html.= '<br/><a href="profile.php" >Back to Profile</a>';
			
		}
		
		elseif(isset($_GET['mode']) && $_GET['mode']=="edit_prohib"){ // edit account infos
			// Mise à jour des données
			
			$html.="<script>	
			function removeIngOnProhib(e, id) {
      var a, url, x;
      e.preventDefault();
      a = e.target.parentNode;
      a.parentNode.hidden = true;
      url = './deleteOnProhib.php?id=' + id;
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
		
			
			$html.= getAllInMyProhib($userid);
			$html.= printFormAddIngredientProhib($userid);
			
			
			$html.="<h5><h5/><br>";
			
			$html.= '<br/><a href="profile.php">Back to Profile</a>';
			
			//requête pour recupérer les ingrédients
	$ingredients = mysql_query("SELECT name_en,id FROM ingredients");
	$list2 = "";
	while ($ingredient = mysql_fetch_array($ingredients)) {
	$list2.= "<option value='$ingredient[0]'>$ingredient[0]</option>";
	}
	$html.= "<datalist id='ingredientList'>$list2</datalist>";
		
		}
		
		elseif(isset($_GET['mode']) && $_GET['mode']=="edit_like"){ // edit account infos
			// Mise à jour des données
			
			$html.="<script>	
			function removeIngOnLike(e, id) {
      var a, url, x;
      e.preventDefault();
      a = e.target.parentNode;
      a.parentNode.hidden = true;
      url = './deleteOnLike.php?id=' + id;
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
			
			$html.= getAllIngILike($userid);
			$html.= printFormAddIngredientLike($userid);
			
			
			$html.="<h5><h5/><br>";
			
			$html.= '<br/><a href="profile.php">Back to Profile</a>';
			
			//requête pour recupérer les ingrédients
	$ingredients = mysql_query("SELECT name_en,id FROM ingredients");
	$list2 = "";
	while ($ingredient = mysql_fetch_array($ingredients)) {
	$list2.= "<option value='$ingredient[0]'>$ingredient[0]</option>";
	}
	$html.= "<datalist id='ingredientList'>$list2</datalist>";
		
		}
		
		elseif(isset($_GET['mode']) && $_GET['mode']=="edit_dislike"){
		// Mise à jour des données
			$html.="<script>	
			function removeIngOnDislike(e, id) {
      var a, url, x;
      e.preventDefault();
      a = e.target.parentNode;
      a.parentNode.hidden = true;
      url = './deleteOnDislike.php?id=' + id;
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
			
			$html.= getAllIngIDislike($userid);
			$html.= printFormAddIngredientDislike($userid);
			
			
			$html.="<h5><h5/><br>";
			
			$html.= '<br/><a href="profile.php">Back to Profile</a>';
			
			//requête pour recupérer les ingrédients
	$ingredients = mysql_query("SELECT name_en,id FROM ingredients");
	$list2 = "";
	while ($ingredient = mysql_fetch_array($ingredients)) {
	$list2.= "<option value='$ingredient[0]'>$ingredient[0]</option>";
	}
	$html.= "<datalist id='ingredientList'>$list2</datalist>";
		
		}
		
		elseif(isset($_GET['mode']) && $_GET['mode']=="edit_profile"){ // edit profile
			if (isset($_GET['updatesuccess'])) $html.= 'Update successful';
			
			$sql = 'SELECT * FROM information WHERE id_user ='.$userid; // gets user profile
			
			if(mysql_num_rows(mysql_query($sql))<1){
				$do = "insert";
			}else{
				$do = "update";
			}
			
			if(isset($_GET['action']) && $_GET['action']=="new_insert_process"){ // new insert process
				$date          = $_POST['myDateInput'];
				$hobbies       = $_POST['hobbies'];
				$job           = $_POST['job'];
				$music         = $_POST['music'];
				$films   	 = $_POST['films'];
				$books   	 = $_POST['books'];	
				$aboutme  	 = $_POST['aboutme'];
				$favouritefood = $_POST['favouritefood'];
				
				//mise a jour dans la bdd
				$res = insertInfo($date,$hobbies,$job,$music,$films,$books,$aboutme,$favouritefood,$userid);
			  
				if(!$res){
					echo "Query error";  
				}else{
					unset($_POST);
					header("Location: profile.php?mode=edit_profile&updatesuccess=1");			
					//Echo "Update successfully";
				}	    	
			  
				// Ne pas envoyer le POST dans header.php
				unset($_POST);
			}elseif(isset($_GET['action']) && $_GET['action']=="update_process"){
				$message=$_GET['action'];
				$date          = $_POST['myDateInput'];
				$hobbies       = $_POST['hobbies'];
				$job           = $_POST['job'];
				$music         = $_POST['music'];
				$films   	 = $_POST['films'];
				$books   	 = $_POST['books'];	
				$aboutme  	 = $_POST['aboutme'];
				$favouritefood = $_POST['favouritefood'];				
			  
				//mise a jour dans la bdd
				$res = updateInfo($date,$hobbies,$job,$music,$films,$books,$aboutme,$favouritefood,$userid);
			  
				if(!$res){
					echo "Query error";  
				}else{
					unset($_POST);
					header("Location: profile.php?mode=edit_profile&updatesuccess=1");
					//Echo "Update successfully";
				}	    	
			  
				// Ne pas envoyer le POST dans header.php
				unset($_POST);				
			}
		
			$useraddinfos=retrieve_user_add_infos($userid); // retrieve_user_infos renvoit un tableau associatif contenant toutes les infos d'un user
			$html.= printProfileForm($useraddinfos, $do);
			$html.= '<br/><br/><a href="profile.php" />Back to Profile</a>';
		}
	}
	 printDocument('Profile Page'); 
}

/*******************************VISITEURS NON INSCRITS*************************************/

else if (isset($_GET['id_user'])){ // pour les visiteurs
	$html.= RegistrationForVisitors();
	printDocument('Profile');
}
 
 /********************************************************************/
?>

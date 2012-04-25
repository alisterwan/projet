<?php include 'header.php';

////////////////// BOXES //////////////////
function leftboxContent(){
		 // ATTENTION IL FAUT METTRE LES QUOTES POUR id !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			  // Requête qui récupère toutes les coordonnées du client
	global $userid;
	$content = '';
	if (isConnected($userid)) {
		$userinfos=retrieve_user_infos($userid);

		/*$content = "<div>
			<img src='./img/templates/friends.png' width='50px' height='50px' title='Friends'/>
			<img src='./img/templates/messages.png' width='50px' height='50px' title='Messages'/>
			<img src='./img/templates/notifications.png' width='50px' height='50px' title='Notifications'/>
			</div>";*/

	 	$countryname = getCountryNameById($userinfos['country']);

		$content.= "<p>Your account information:</p>
				<div>$userinfos[firstname] &nbsp;$userinfos[surname]</div>
				<div>$userinfos[address]</div>
				<div>$userinfos[city]</div>
				<div>$countryname</div>
				<div>$userinfos[mail]</div>

				<div class='stack'>
					<img src='img/stacks/stack.png' alt='stack'/>
					<ul id='stack'>
					<li><a href='objectivesform.php'><span>Objectives</span><img src='img/stacks/objectives.png' alt='My Objectives' /></a></li>
					<li><a href='information.php'><span>Information</span><img src='img/stacks/information.png' alt='My infos' /></a></li>
					<li><a href='albums.php'><span>Albums</span><img src='img/stacks/albums.png' alt='My albums' /></a></li>
					<li><a href='friends.php'><span>Friends</span><img src='img/stacks/myfriends.png' alt='My friends' /></a></li>
					<li><a href='recipes.php'><span>Recipes</span><img src='img/stacks/recipes.png' alt='My recipes' /></a></li>
					</ul>
				</div>";

	}else{
		// Afficher les formulaires pour se connecter et s'inscrire
		$content = "
			<form action='./index.php?mode=logon' method='post'>
			  <img src='./img/templates/login.png'/>
			  <div><input type='text' name='name' placeholder='Username' required></div>
			  <div><input type='password' name='pass' placeholder='password' required></div>
			  <div><input type='submit' value='Submit'> <a href='./#'>Forgot your password?</a></div>
			</form>";
	}

	return $content;
}
///////////////////////// END BOXES ///////////////////////////////

///////////////////////// GETTERS//////////////
function getLatestPostsOfContacts(){ // returns 10 latest posts ID of all contacts | FALSE if no post or not connected
	if(!isConnected()) return false;

	global $userid;
	$groups = getAllGroupsByUserId($userid);
	if(!$groups || count($groups)<1) return false;

	$users = getAllUsersOfGroups($groups);

	if($users == false || count($users)<1 ) return false;

	$sql = 'SELECT id, date FROM wall_post WHERE ';
	$nbusers = count($users);
	$i = 0;
	foreach($users AS $user){
		$i++;
		$sql.= 'id_user='.$user;
		if($i < $nbusers) $sql.= ' OR ';
	}

	$sql.= ' ORDER BY date DESC LIMIT 0, 4';
	$query = mysql_query($sql);
	if(!$query || mysql_num_rows($query)<1) return false;

	$posts;
	while($result = mysql_fetch_assoc($query)){
		$posts[] = $result['id'];
	}
	return $posts;
}

function getLatestPublicPosts(){ // returns 10 latest public posts ID of public | FALSE if no post
	$sql = 'SELECT id, date FROM wall_post ORDER BY date DESC LIMIT 0,10';
	$query = mysql_query($sql);
	if(!$query || mysql_num_rows($query)<1) return false;

	$posts;
	while ($result = mysql_fetch_assoc($query)){
		$posts[] = $result['id'];
	}
	return $posts;
}

function getLastestRecipesofContact(){
	if(!isConnected()) return false;

	global $userid;
	$groups = getAllGroupsByUserId($userid);
	if(!$groups || count($groups)<1) return false;

	$users = getAllUsersOfGroups($groups);

	if($users == false || count($users)<1 ) return false;

	$sql = 'SELECT id FROM recipes WHERE ';
	$nbusers = count($users);
	$i = 0;
	foreach($users AS $user){
		$i++;
		$sql.= 'id_user='.$user;
		if($i < $nbusers) $sql.= ' OR ';
	}

	$sql.= ' ORDER BY creation DESC LIMIT 0, 4';
	$query = mysql_query($sql);
	if(!$query || mysql_num_rows($query)<1) return false;

	$posts;
	while($result = mysql_fetch_assoc($query)){
		$recipes[] = $result['id'];
	}
	return $recipes;
}

/**********************************************************/

/////////////////////////////////////////
function getCommentsIdByWallPostId($postid){ // return array of comment id from Wall post id/// FALSE if no comment
	$sql = 'SELECT id FROM wall_post_comment WHERE id_wall_post='.$postid;
	$query = mysql_query($sql);

	if(!$query || mysql_num_rows($query)==0) return false; // wrong query or no post

	$posts; // initializing array of postId
	while($result = mysql_fetch_assoc($query)){
		$posts[]=$result['id'];
	}
	return $posts;
}

function getGroupsByWallPostId($id){ // return allowed groups by Post ID /// False if NONE
	//$query = sprintf("SELECT * FROM wall_post_permission WHERE id_wall_post='%s'",	mysql_real_escape_string($id));
	$query = 'SELECT id_group FROM wall_post_permission WHERE id_wall_post='.$id;
	$result = mysql_query($query);
	global $message;
	if (!$result) {
		return false;
	}else{
		if(mysql_num_rows($result)<1) return false; // no group

		$reponse;
		while ($row = mysql_fetch_assoc($result)) {
			$reponse[]=$row['id_group'];
		}
		return $reponse; // return groups
	}
}

function getPostIdByUserId($id){ // return posts of wall's user
	$sql = 'SELECT id, id_user FROM wall_post WHERE id_user='.$id.' ORDER BY date DESC';
	$query = mysql_query($sql);

	if(!$query || mysql_num_rows($query)==0) return false; // wrong query or no post

	$postid; // initializing array of postId
	while($result = mysql_fetch_assoc($query)){
		$postid[] = $result['id'];
	}
	return $postid;
}

function getPostId_filter_permission($idpost, $iduser){ // return false if no post /// return array of post id according to permissions
	$result;
	$atleastone = false;
	if(count($idpost)>0){ // have post
		foreach($idpost AS $post){
			if(checkPermission(getGroupsByWallPostId($post), $iduser) || getCreatorIdByPostId($post)==$iduser){
				$result[] = $post;
				if(!$atleastone) $atleastone = true;
			}
		}
		if(!$atleastone) return false;
		return $result;
	}
	return false;
}

function getCreatorIdByPostId($idpost){
	$sql = 'SELECT id_user FROM wall_post WHERE id='.$idpost;
	$result = mysql_fetch_assoc(mysql_query($sql));
	return $result['id_user'];
}

function getWallOwnerByCommentId($idcomment){
	$sql = 'SELECT id_user FROM wall_post WHERE id=(SELECT id_wall_post FROM wall_post_comment WHERE id='.$idcomment.')';
	$query = mysql_query($sql);
	$result = mysql_fetch_assoc($query);
	return $result['id_user'];
}

function posterOrOwnerOfComment($idcomment, $iduser){ // return if $iduser is owner or poster of comment
	$sql = 'SELECT id_poster FROM wall_post_comment WHERE id='.$idcomment;
	$query = mysql_query($sql);

	if(!$query) return false;
	$result = mysql_fetch_assoc($query);
	if($result['id_poster']==$iduser) return true; // if poster
	///////////////////////

	$sql = 'SELECT id_user FROM wall_post WHERE id=(SELECT id_wall_post FROM wall_post_comment WHERE id='.$idcomment.')';
	$query = mysql_query($sql);
	if(!$query) return false;
	$result = mysql_fetch_assoc($query);
	if($result['id_user']==$iduser) return true; // if owner

	return false; // neither poster or owner
}

function alreadyRatedCommentByUser($idcomment, $iduser){ // return if current user has rated a comment
	$sql = 'SELECT id FROM wall_post_comment_rating WHERE id_comment='.$idcomment.' AND id_user='.$iduser;
	$query = mysql_query($sql);
	if(!$query || mysql_num_rows($query)<=0){
		return false;
	}else{
		return true;
	}
}

function posterOrOwnerOfPost($idpost, $iduser){ // return if current user is owner or poster of a Post /// false if neither
	$sql = 'SELECT id_user FROM wall_post WHERE id='.$idpost;
	$query = mysql_query($sql);

	if(!$query) return false;

	$result = mysql_fetch_assoc($query);
	return $result['id_user']==$iduser;
}

function alreadyRatedPostByUser($idpost, $iduser){ // return if current user has rated a POST
	$sql = 'SELECT id FROM wall_post_rating WHERE id_post='.$idpost.' AND id_user='.$iduser;
	$query = mysql_query($sql);
	if(!$query || mysql_num_rows($query)<=0){
		return false;
	}else{
		return true;
	}
}

function getLikesByPost($idpost){ // return number of Likes of a $idpost
	$sql = 'SELECT id FROM wall_post_rating WHERE rating=1 AND id_post='.$idpost;
	$query = mysql_query($sql);

	if(!$query || mysql_num_rows($query)<1) return 0;
	return mysql_num_rows($query);
}

function getDislikesByPost($idpost){ // return number of Dislikes of a $idpost
	$sql = 'SELECT id FROM wall_post_rating WHERE rating=0 AND id_post='.$idpost;
	$query = mysql_query($sql);

	if(!$query || mysql_num_rows($query)<1) return 0;
	return mysql_num_rows($query);
}

function getLikesByComment($idcomment){ // return number of Likes of a $idpost
	$sql = 'SELECT id FROM wall_post_comment_rating WHERE rating=1 AND id_comment='.$idcomment;
	$query = mysql_query($sql);

	if(!$query || mysql_num_rows($query)<1) return 0;
	return mysql_num_rows($query);
}

function getDislikesByComment($idcomment){ // return number of Dislikes of a $idpost
	$sql = 'SELECT id FROM wall_post_comment_rating WHERE rating=0 AND id_comment='.$idcomment;
	$query = mysql_query($sql);

	if(!$query || mysql_num_rows($query)<1) return 0;
	return mysql_num_rows($query);
}

function getFriendGroupIdByUserId($id){ // return Friends group of a user $id
	global $userid;
	$sql = "SELECT id FROM groups WHERE id_creator=".$userid." AND name='Friends' ";
	$query = mysql_query($sql);
	$result = mysql_fetch_assoc($query);
	return $result['id'];
}

function getCurrentUserPostRatingByPost($idpost){
	global $userid;
	$sql = 'SELECT rating FROM wall_post_rating WHERE id_post='.$idpost.' AND id_user='.$userid;
	$result = mysql_fetch_assoc(mysql_query($sql));
	return $result['rating'];
}

function getCurrentUserPostRatingByComment($idcomment){
	global $userid;
	$sql = 'SELECT rating FROM wall_post_comment_rating WHERE id_comment='.$idcomment.' AND id_user='.$userid;
	$result = mysql_fetch_assoc(mysql_query($sql));
	return $result['rating'];
}

function printAllPostByUserId($id){ // display ALL POSTS AND COMMENTS according to permissions///  FALSE IF NO POST
	global $userid;
	$allposts;
	if($id==$userid){ // get post from current user viewing its own wall
		$sql = 'SELECT id FROM wall_post WHERE id_user='.$id.' ORDER BY date DESC';
		$query = mysql_query($sql);
		if(!$query || mysql_num_rows($query)<1) return false; // no post
		while($result = mysql_fetch_assoc($query)){
			$allposts[] = $result['id'];
		}
	}else{ // visitor viewing a wall
		$posts = getPostIdByUserId($id);
		if($posts!=false){ // have post
			$allposts = getPostId_filter_permission($posts, $userid);
		}else{ // no post
			return false;
		}
	}

	if(!$allposts) return false; // no post

	$ficelle = '';
	foreach($allposts AS $post){
		$ficelle.= printWallPostById($post).'<br/><br/>';
	}
	return $ficelle;
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

function printAvatarByUserId($id){ // display avatar miniature
	$sql = 'SELECT avatar FROM users WHERE id='.$id;
	$query = mysql_query($sql);
	$result = mysql_fetch_assoc($query);
	return '<img src="'.$result['avatar'].'"  width="64" height="64" alt="avatar">';
}

function printLikeDislikePost($idpost){ // like dislike delete bar for POST
	global $userid;
	$ficelle = '';
	$wallowner = getCreatorIdByPostId($idpost);
	if(!alreadyRatedPostByUser($idpost, $userid)){
		// Like button
		if($wallowner!=$userid){
			$ficelle.= '<form action="wall.php?id='.$wallowner.'" method="POST" >';
		}else{
			$ficelle.= '<form action="wall.php" method="POST" >';
		}
		$ficelle.= '<input type="submit" value="Like" /><input type="hidden" name="rating_post_like" id="rating_post_like" value='.$idpost.' /></form>';

		// Dislike button
		if($wallowner!=$userid){
			$ficelle.= '<form action="wall.php?id='.$wallowner.'" method="POST" >';
		}else{
			$ficelle.= '<form action="wall.php" method="POST" >';
		}
		$ficelle.= '<input type="submit" value="Dislike" /><input type="hidden" name="rating_post_dislike" id="rating_post_dislike" value='.$idpost.' /></form>';
	}else{ // undo rating?
		if(getCurrentUserPostRatingByPost($idpost)==0){
			$ficelle.='You dislike this post. ';
		}else{
			$ficelle.='You like this post. ';
		}

		if($wallowner!=$userid){
			$ficelle.= '<form action="wall.php?id='.$wallowner.'" method="POST" >';
		}else{
			$ficelle.= '<form action="wall.php" method="POST" >';
		}
		$ficelle.='<input type="submit" value="Undo" /><input type="hidden" name="undo_post_rating" id="undo_post_rating" value='.$idpost.' /></form>';
	}

	// delete button
	if(posterOrOwnerOfPost($idpost, $userid)){
		if($wallowner!=$userid){
			$ficelle.= '<form action="wall.php?id='.$wallowner.'" method="POST" >';
		}else{
			$ficelle.= '<form action="wall.php" method="POST" >';
		}
		$ficelle.= '<input type="submit" value="Delete" /><input type="hidden" name="delete_post" id="delete_post" value='.$idpost.' /></input></form>';
	}

	// date
	$sql = 'SELECT date FROM wall_post WHERE id='.$idpost;
	$query = mysql_query($sql);
	$result = mysql_fetch_assoc($query);
	$ficelle.= $result['date'];

	return $ficelle;
}

function printCommentByCommentId($idcomment){ // comment display
	$sql = 'SELECT * FROM wall_post_comment WHERE id='.$idcomment;
	$query = mysql_query($sql);

	if(!$query){
		return false;
	}

	$result = mysql_fetch_assoc($query);

	$ficelle = '<table style="text-align: left; " border="1" cellpadding="2" cellspacing="2">';
	$ficelle.= '<tr><td>'.printAvatarByUserId($result['id_poster']).'<br/>'; // case avatar
	$ficelle.= printRatingComment($idcomment).'</td>'; // ratings

	$ficelle.= '<td>'.printLinkToProfileByUserId($result['id_poster']).' '.$result['comment'].'<br/><br/>';
	$ficelle.= printLikeDislikeComment($idcomment).'</td>';
	$ficelle.= '</tr></table><br/>';

	return $ficelle;
}

function printAllCommentsByWallPostId($postid){ // all comments for a post
	global $userid;
	$ficelle ='';
	$sql = 'SELECT * FROM wall_post_comment WHERE id_wall_post='.$postid;
	$query = mysql_query($sql);

	$nbcomment = mysql_num_rows($query);
	if($nbcomment>1){
		$ficelle.= $nbcomment.' comments';
	}else{
		$ficelle.= $nbcomment.' comment';
	}
	$ficelle.= '<br/>';

	////////////////PRINT ALL COMMENTS/////////////
	$commentsID = getCommentsIdByWallPostId($postid);
	if($commentsID!=false){
		foreach($commentsID AS $comment){
			$ficelle.= printCommentByCommentId($comment);
		}
	}
	///////////////NEW COMMENT/////////////
	$wallowner = getCreatorIdByPostId($postid);
	if($wallowner!=$userid){
		$ficelle.= '<form action="wall.php?id='.$wallowner.'" method="POST" >';
	}else{
			$ficelle.= '<form action="wall.php" method="POST" >';
	}
	$ficelle.= '<input type="hidden" name="idpost" id="idpost" value='.$postid.' />
	<textarea name="newcomment" id="newcomment" /></textarea><br/>
	<input type="submit" value="Post new comment" /></form>';

	return $ficelle;
}

function printLikeDislikeComment($idcomment){ // like dislike delete bar for comment
	global $userid;

	$ficelle = '';
	$wallowner = getWallOwnerByCommentId($idcomment);
	if(!alreadyRatedCommentByUser($idcomment, $userid)){
		// Like button
		if($wallowner!=$userid){
			$ficelle.= '<form action="wall.php?id='.$wallowner.'" method="POST" >';
		}else{
			$ficelle.= '<form action="wall.php" method="POST" >';
		}
		$ficelle.= '<input type="submit" value="Like" /><input type="hidden" name="rating_comment" id="rating_comment" value='.$idcomment.' />
		<input type="hidden" name="rating" id="rating" value="1" /></form>';

		// Dislike button
		if($wallowner!=$userid){
			$ficelle.= '<form action="wall.php?id='.$wallowner.'" method="POST" >';
		}else{
			$ficelle.= '<form action="wall.php" method="POST" >';
		}
		$ficelle.= '<input type="submit" value="Dislike" /><input type="hidden" name="rating_comment" id="rating_comment" value='.$idcomment.' />
		<input type="hidden" name="rating" id="rating" value="0" /></form>';
	}else{ // undo rating?
		if(getCurrentUserPostRatingByComment($idcomment)==0){
			$ficelle.='You dislike this comment. ';
		}else{
			$ficelle.='You like this comment. ';
		}
		if($wallowner!=$userid){
			$ficelle.= '<form action="wall.php?id='.$wallowner.'" method="POST" >';
		}else{
			$ficelle.= '<form action="wall.php" method="POST" >';
		}
		$ficelle.='<input type="submit" value="Undo" /><input type="hidden" name="undo_comment_rating" id="undo_comment_rating" value='.$idcomment.' /></form>';
	}

	// delete button
	if(posterOrOwnerOfComment($idcomment, $userid)){
		if($wallowner!=$userid){
			$ficelle.= '<form action="wall.php?id='.$wallowner.'" method="POST" >';
		}else{
			$ficelle.= '<form action="wall.php" method="POST" >';
		}
		$ficelle.= '<input type="submit" value="Delete" /><input type="hidden" name="delete_comment" id="delete_comment" value='.$idcomment.' /></input></form>';
	}

	// date
	$sql = 'SELECT date FROM wall_post_comment WHERE id='.$idcomment;
	$query = mysql_query($sql);
	$result = mysql_fetch_assoc($query);
	$ficelle.= $result['date'];

	return $ficelle;
}

function printPermissionChoices($idpost, $iduser){ // display Permissions and choices
	global $userid;
	if($userid != $iduser) return '';

	$ficelle ='<form action="wall.php" method="post" >';
	$ficelle.= '<label for="share_with_group" >Share with</label> <select name="share_with_group" id="share_with_group">';

	$groupsid = getAllGroupsByUserId($iduser); // all groups of iduser
	$allowedGroupsId = getGroupsByWallPostId($idpost); // all groups granted by iduser

	foreach($groupsid AS $group){ // add only non granted groups
		if(!in_array($group, $allowedGroupsId)) $ficelle.= '<option value="'.$group.'">'.getGroupNameById($group).'</option>';
	}

	$ficelle.= '</select> <input type="submit" value="Submit" /><input type="hidden" name="post_allowed" id="post_allowed" value='.$idpost.' /></form>';

	if($allowedGroupsId!=false ){
		foreach($allowedGroupsId AS $allowedGroup){
			$ficelle.= '<form action="wall.php" method="post" >'.getGroupNameById($allowedGroup).'<input type="hidden" name="remove_permission" id ="remove_permission" value='.$allowedGroup.' />
			<input type="hidden" name="post_restricted" id ="post_restricted" value='.$idpost.' />
			<input type="submit" value="Remove" /></form>';
		}
	}
	return $ficelle;
}

function printRatingPost($idpost){
	$ficelle = '';
	if(getLikesByPost($idpost)<2) {
		$ficelle.= getLikesByPost($idpost).' like'; // like
	}else{
		$ficelle.= getLikesByPost($idpost).' likes'; // likes
	}

	$ficelle.= '<br/>';
	//$ficelle.= '&nbsp;';

	if(getDislikesByPost($idpost)<2) {
		$ficelle.= getDislikesByPost($idpost).' dislike'; // dislike
	}else{
		$ficelle.= getDislikesByPost($idpost).' dislikes'; // dislikes
	}
	$ficelle.= '<br/>';
	return $ficelle;
}

function printRatingComment($idcomment){
	$ficelle = '';
	if(getLikesByComment($idcomment)<2) {
		$ficelle.= getLikesByComment($idcomment).' like'; // like
	}else{
		$ficelle.= getLikesByComment($idcomment).' likes'; // likes
	}

	$ficelle.= '<br/>';
	//$ficelle.= '&nbsp;';

	if(getDislikesByComment($idcomment)<2) {
		$ficelle.= getDislikesByComment($idcomment).' dislike'; // dislike
	}else{
		$ficelle.= getDislikesByComment($idcomment).' dislikes'; // dislikes
	}
	$ficelle.= '<br/>';
	return $ficelle;
}

function retrieve_recipe_infos($id){ // prend en paramètre l'id de l'user, soit $_SESSION['id']
	$sql='SELECT * FROM recipes WHERE id='.$id;
	$query=mysql_query($sql);
	$verif = mysql_num_rows($query);

	if ($verif > 0){
	return $result=mysql_fetch_assoc($query);
	}
	return false;
  }


function printRecipesBadgeById($id) {
	$recipe = retrieve_recipe_infos($id);
	$user = retrieve_user_infos($recipe['id_user']);

	//recuperation de la photo
	$query = sprintf("SELECT path_source FROM recipe_photos WHERE id_recipe='%s'",
	mysql_real_escape_string($id));
	$result2 = mysql_query($query);

	if(mysql_num_rows($result2) == 1){
		$recip = mysql_fetch_assoc($result2);
	}

	if(!$recipe){
		return '';
	}

	$ficelle  = "
	<table style='text-align: left;' border='0' cellpadding='2' cellspacing='2'>
		<tr>
			<td><img src='$recip[path_source]' width='60px' height='60px'> </td>
			<td><a href='recipe.php?id=$recipe[id]&iduser=$recipe[id_user]'>
			$recipe[name_en]</a>&nbsp<br>
			by <a href='profile.php?id_user=$user[id]'>$user[firstname] $user[surname] </td>";

	$ficelle.="</tr></table>";

	return $ficelle;
}

/////////////////////////////////////////////////////////////////


if(isset($_GET['action']) && $_GET['action']=="logout"){
	session_unset();
	header('Location: index.php');
}

if(isConnected()){
	$html = '';

	$recipes = getLastestRecipesofContact();
	if($recipes!=false){
		$html.="<h3>Lastest friends recipes</h3><div class='recipes'>";
		foreach($recipes AS $recipe){
			$html.= printRecipesBadgeById($recipe).'<br/>';
		}
		$html.="</div>";
	}

	$posts = getLatestPostsOfContacts();
	if($posts!=false){
		$html.="<h3>Lastest friends posts</h3><div class='wall'>";
		foreach($posts AS $post){
			$html.= printWallPostById($post).'<br/>';
		}
		$html.="</div>";
	}
}

if(($_POST)){ // something to treat?

		////////////////////////////// POST MODIFICATIONS!!!! //////////////////////////////////////

		if(isset($_POST['newpost']) && $_POST['newpost']!=""){ // NEW POST
			$sql = "INSERT INTO wall_post(id_user, post_type, content, approval)
			VALUES (".$userid.", 1, '".$_POST['newpost']."', 1)";
			$query = mysql_query($sql); // insert new post


			$sql = 'INSERT INTO wall_post_permission(id_wall_post, id_group) VALUES('.mysql_insert_id().', '.getFriendGroupIdByUserId($userid).')';
			$query = mysql_query($sql); // insert new permission (Friends default)
		}

		if(isset($_POST['rating_post_like'])){ // Post rating LIKE
			$idpost = $_POST['rating_post_like'];
			$sql = 'INSERT INTO wall_post_rating(id_post, id_user, rating)
			VALUES('.$idpost.', '.$userid.', 1)';
			$query = mysql_query($sql); // Post liked
		}

		if(isset($_POST['rating_post_dislike'])){ // Post rating DISLIKE
			$idpost = $_POST['rating_post_dislike'];
			$sql = 'INSERT INTO wall_post_rating(id_post, id_user, rating)
			VALUES('.$idpost.', '.$userid.', 0)';
			$query = mysql_query($sql); // Post disliked
		}

		if(isset($_POST['undo_post_rating'])){ // post rating UNDO
			$idpost = $_POST['undo_post_rating'];
			$sql = 'DELETE FROM wall_post_rating WHERE id_user='.$userid.' AND id_post='.$idpost;
			$query = mysql_query($sql); // Post rating UNDONE
		}

		if(isset($_POST['delete_post'])){ // delete post
			$idpost = $_POST['delete_post'];
			$sql = 'DELETE FROM wall_post WHERE id='.$idpost;
			$query = mysql_query($sql); // Post DELETED
		}

		if(isset($_POST['share_with_group']) && isset($_POST['post_allowed'])){ // grant access to group
			$idgroup = $_POST['share_with_group'];
			$idpost = $_POST['post_allowed'];
			$sql = 'INSERT INTO wall_post_permission(id_wall_post, id_group) VALUES('.$idpost.', '.$idgroup.')';
			$query = mysql_query($sql); // Acces granted to group for post
		}

		if(isset($_POST['remove_permission']) && isset($_POST['post_restricted'])){
			$idgroup = $_POST['remove_permission'];
			$idpost = $_POST['post_restricted'];
			$sql = 'DELETE FROM wall_post_permission WHERE id_wall_post='.$idpost.' AND id_group='.$idgroup;
			$query = mysql_query($sql); // Acces restricted to group for post
		}

		////////////////////////////// COMMENT MODIFICATIONS!!!! //////////////////////////////////////

		if(isset($_POST['newcomment']) && isset($_POST['idpost'])){ // NEW COMMENT!!
			$comment = $_POST['newcomment'];
			$idpost = $_POST['idpost'];
			if(isset($_GET['id']) && $_GET['id']!=$userid){ // poster is not owner
				$sql = 'INSERT INTO wall_post_comment(id_wall_post, id_poster, comment)
				VALUES('.$idpost.', '.$userid.', "'.$comment.'")';
			}else{
				$sql = 'INSERT INTO wall_post_comment(id_wall_post, id_poster, comment, status)
				VALUES('.$idpost.', '.$userid.', "'.$comment.'", 1)';
			}
			$query = mysql_query($sql); // comment posted
		}

		if(isset($_POST['rating_comment']) && isset($_POST['rating'])){ // Rate a comment
			$idcomment = $_POST['rating_comment'];
			$rating = $_POST['rating'];
			$sql = 'INSERT INTO wall_post_comment_rating(id_comment, id_user, rating)
			VALUES('.$idcomment.', '.$userid.', '.$rating.')';
			$query = mysql_query($sql); // comment rated
		}

		if(isset($_POST['undo_comment_rating'])){ // undo comment rating
			$idcomment = $_POST['undo_comment_rating'];
			$sql = 'DELETE FROM wall_post_comment_rating WHERE id_user='.$userid.' AND id_comment='.$idcomment;
			$query = mysql_query($sql); // Comment rating UNDONE
		}

		if(isset($_POST['delete_comment'])){ // delete post
			$idcomment = $_POST['delete_comment'];
			$sql = 'DELETE FROM wall_post_comment WHERE id='.$idcomment;
			$query = mysql_query($sql); // Comment DELETED
		}

	}


printDocument('Homepage');

?>

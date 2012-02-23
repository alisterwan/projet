<?php
include './header.php';

if (isset($_SESSION['id'])) {
	// vérification si logué en tant qu'utilisateur

	$useraddinfos = retrieve_user_add_infos($_SESSION['id']);

	$html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>";

	if ($useraddinfos) {
		$html .= "<h4>Born in $useraddinfos[date_birth], Works at $useraddinfos[job] Listen to $useraddinfos[music]</h4>";
	}

	if (isset($_GET[id_user])) {
		$userinfos    = retrieve_user_infos($_GET[id_user]);
		$useraddinfos = retrieve_user_add_infos($_GET[id_user]);

		$html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>";

		if($useraddinfos) {
			$html .= "
			<h4>Born in $useraddinfos[date_birth], Works at $useraddinfos[job] Listen to $useraddinfos[music]</h4> <a href='#'>Add as Friend</a>";
		} else {
			$html .= "<div>Sorry, there's no content available to show.</div> <a href='#'>Add as Friend</a>";
		}
	}
} else if (isset($_GET[id_user])) {
	// pour les visiteurs
	$userinfos    = retrieve_user_infos($_GET[id_user]);
	$useraddinfos = retrieve_user_add_infos($_GET[id_user]);

	$html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1>";

	if ($useraddinfos) {
		$html .= "
		<h4>Born in $useraddinfos[date_birth], Works at $useraddinfos[job] Listen to $useraddinfos[music]</h4> <a href='#'>Add as Friend</a>";
	} else {
		$html .= "<div>Sorry, there's no content available to show.</div> <a href='#'>Add as Friend</a>";
	}
}

printDocument('Profile Page');

?>

<?php
  include './header.php';
  header('Content-type: text/plain');


  $approval = 1;
  
  $groupid = getGroupId($_GET[id_user]);
  $creatorid = getGroupCreator($_GET[idgroup]);
  $userinfos = retrieve_user_infos($_GET[id_user]);
  
  
  	$query = sprintf("UPDATE groups_relations SET approval='%s' WHERE id_group='$_GET[idgroup]' AND id_user='$_GET[id_user]';",
		mysql_real_escape_string(strip_tags($approval)));
		$res = @mysql_query($query);
		
		if(!$res){
			die("Error: ".mysql_error());
			}
		
		$query2 ="INSERT INTO groups_relations(id_group,id_user,approval) VALUES ($groupid,$creatorid,1)";
		
		$res2 = @mysql_query($query2);
		
		if(!$res2){
			die("Error: Query 2".mysql_error());
			}
		
		echo "You are now friend with $userinfos[firstname] $userinfos[surname]($userinfos[username])";
	

		
		
			 	
 

  
?>
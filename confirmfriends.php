<?php
  include './header.php';
  header('Content-type: text/plain');


  //met a jour dans un sens la requete
  $query = sprintf("UPDATE groups_relations SET approval='1', status='1' WHERE id_group='$_GET[idgroup]' AND id_user='$_GET[id_user]'");
		$res = @mysql_query($query);
		
		if(!$res){
			die("Error: ".mysql_error());
			}

  //on choisit le id user 
  $sql = "SELECT id_creator FROM groups WHERE id='$_GET[idgroup]' AND name='Friends'";
	$result = mysql_query($sql);

	if($result){
	 $row =	mysql_fetch_assoc($result);
	}
	
	
	$sql2 = "SELECT id FROM groups WHERE id_creator='$_GET[id_user]' AND name='Friends'";
	$result2 = mysql_query($sql2);

	if($result2){
	 $row2 =	mysql_fetch_assoc($result2);
	}

  $userinfos = retrieve_user_infos($row['id_creator']);
  
  
  			
		$query2 ="INSERT INTO groups_relations(id_group,id_user,approval,status) VALUES ($row2[id],$row[id_creator],1,1)";
		
		$res2 = @mysql_query($query2);
		
		if(!$res2){
			die("Error: Query 2".mysql_error());
			}
		
		echo "You are now friend with $userinfos[firstname] $userinfos[surname]($userinfos[username])";
		
	
	

  
?>
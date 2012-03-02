<?php


function QuerybyTableandId($table,$id){
	$sql = "SELECT * from $table WHERE id='$id'";
	$result = mysql_query($sql);
	$verif = mysql_num_rows($result);
		
		if ($verif>0){
			return $row = mysql_fetch_assoc($result);
		}

		else return false;
}


function DeleteOnTable($table,$id){
	$sql = "DELETE * from $table WHERE id='$id'";
	$result = mysql_query($sql);


  	if(!$result) die("Error: ".mysql_error());
}


function GetCountryById($id){
	$sql = "SELECT * from country WHERE id_country='$id'";
	$result = mysql_query($sql);
	$verif = mysql_num_rows($result);
		
		if ($verif>0){
			return $row = mysql_fetch_assoc($result);
		}

		else return false;

}


function printFormCustomer($id){
	$customer = QuerybyTableandId('users',$id);
	$country = GetCountryById($customer['country']);
	
	$html = "
	<form action='./index.php?mode=customers' method='post'>
	<p>Delete or update its details.</p>
	<div>Firstname: <input type='text' name='firstname' value='$customer[firstname]' required></div>
	<div>Surname: <input type='text' name='surname' value='$customer[surname]' required></div>
	<div>Username: <input type='text' name='username' value='$customer[username]' required></div>
	<div>Address: <input type='text' name='address' value='$customer[address]'></div>
	<div>Country: <input type='text' name='country' list='countryList' value='$country[name_en]'></div>
	<div>Email: <input type='text' name='mail' value='$customer[mail]' required></div>
	<div>
		<button type='submit' name='update' value='$customer[id]'>Update</button>
		<button type='submit' name='delete' value='$customer[id]'>Delete</button>
	</div>
	</form>";
	
	return $html;
}

function printFormRecipe($id){
	$recipe = QuerybyTableandId('recipes',$id);
	$country = GetCountryById($recipe['country_origin']);
	
	$html = "
	<form action='./index.php?mode=recipes' method='post'>
	<p>Delete or update its details.</p>
	<div>Name: <input type='text' name='name' value='$recipe[name_en]' required></div>
	<div>Description: <input type='text' name='description' value='$recipe[description_en]' required></div>
	<div>Origin: <input type='text' name='origin' value='$country[name_en]' required></div>
	<div>Difficulty: <input type='text' name='difficulty' value='$recipe[difficulty]' required></div>
	<div>Serves: <input type='text' name='serves' value='$recipe[num_serves]' required></div>
	<div>Preparation: <input type='text' name='preparation' value='$recipe[duration_preparation]' required></div>
	<div>Cook: <input type='text' name='cook' value='$recipe[duration_cook]' required></div>
	<div>Method:<textarea name='method'>$recipe[preparation_en]'</textarea></div>
	<div>
		<button type='submit' name='update' value='$customer[id]'>Update</button>
		<button type='submit' name='delete' value='$customer[id]'>Delete</button>
	</div>
	</form>";
	return $html;

}


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


function printFooter() {
		echo "</body></html>";
	}



?>
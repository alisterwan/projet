<?php
	include ("header.php");

	// Fonction qui insere un new user dans la bdd
	function insertUser($firstname,$surname,$sex,$user,$pass,$email,$avatar){
		$query = sprintf("INSERT INTO users(firstname,surname,sex,username,password,mail,avatar) VALUES('%s','%s','%s','%s','%s','%s','%s');", 
		mysql_real_escape_string(strip_tags($firstname)),
		mysql_real_escape_string(strip_tags($surname)),
		mysql_real_escape_string(strip_tags($sex)),
		mysql_real_escape_string(strip_tags($user)),
		mysql_real_escape_string(strip_tags($pass)),
		mysql_real_escape_string(strip_tags($email)),
		mysql_real_escape_string(strip_tags($avatar)));
		$res = @mysql_query($query);
		if(!$res)
			die("Error: ".mysql_error());
		else
			return $res;
	}
	
	
	function createDefaultGroup($iduser,$name){
	$query = sprintf("INSERT INTO groups(id_creator,name) VALUES('%s','%s');", 
		mysql_real_escape_string(strip_tags($iduser)),
		mysql_real_escape_string(strip_tags($name)));
		$res = @mysql_query($query);
		if(!$res)
			die("Error: ".mysql_error());
		else
			return $res;

	}
	

	// Formulaire affichant les champs à remplir pour l'inscription.
	function printForm($firstname,$surname,$sex,$user,$email) {
		global $html;
		$html .= "
			<p>In order to register, please provide necessary information. You username will be used in order to log in. Your password must be at least 6 characters long.</p>
			<table>
			<form action='./registration.php' method='post'>
				<tr>
					<td>Username</td>
					<td><input type='text' name='username' value='$user' required></td>
				</tr>
				<tr>
					<td>Firstname</td>
					<td><input type='text' name='firstname' value='$firstname' required></td>
				</tr>
				<tr>
					<td>Surname</td>
					<td><input type='text' name='surname' value='$surname' required></td>
				</tr>
				<tr>
					<td>I am</td>
					<td><select name='sex' value='$sex'>
						<option value='1' selected='selected'>Male</option>
						<option value='2'>Female</option>
						</select></td>
				</tr>
				<tr>
					<td>E-mail</td>
					<td><input type='text' name='email' value='$email' required></td>
				</tr>
				<tr>
					<td>Password</td>
					<td><input type='password' name='password' required></td>
				</tr>
				<tr>
					<td>Confirm password</td>
					<td><input type='password' name='passcheck' required></td>
				</tr>

				<tr>
					<td><input type='submit' name='proceed' value='Submit'></td>
				</tr>

			</form>
			</table>";
}

	$html = "<div class='form'>";

	if ($_POST) { 
		$firstname = $_POST['firstname'];
		$surname   = $_POST['surname'];
		$sex       = $_POST['sex'];
		$user      = $_POST['username'];
		$pass      = $_POST['password'];
		$email     = $_POST['email'];
		
		// On vérifie si le mdp est est de 8 charactères minimum et l'utilisateur l'a bien saisie.
		if (strlen($pass)<6 || $pass != $_POST['passcheck']) {
			$message = "<p class='error'>Password invalid or too short.</p>";
			printForm($firstname,$surname,$sex,$user,$email);
		}

		// On vérifie si la valeur du mail saisie correspond à une adresse valide.
		else if (!filter_var($email,FILTER_VALIDATE_EMAIL)){//(!preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/',$email)) {
			$message = "<p class='error'>E-mail invalid.</p>";
			printForm($firstname,$surname,$sex,$user,'');
		}

		// Vérification si le username du client est déjà dans la base de donnée
		else if (mysql_num_rows(mysql_query("SELECT username FROM users WHERE username='$user'"))) {
			$message = "<p class='error'>This username is already being used. Please change it.</p>";
			printForm($firstname,$surname,$sex,'',$email);
		}

		// Vérification si le mail n'est pas déjà dans la base de donnée
		else if (mysql_num_rows(mysql_query("SELECT mail FROM users WHERE mail='$email'"))) {
			$message = "<p class='error'>This e-mail is already registered. Please change it.</p>";
			printForm($firstname,$surname,$sex,$user,'');
		}


		// Ajout d'un nouveau client dans la base de donnée
		else {
			$pass = sha1($pass);
			
			if ($sex=='1'){
				$avatar = './img/avatar/man_default.png';	
			}else{ 
				$avatar = './img/avatar/woman_default.png'; 
			}
			
			$res1 = insertUser($firstname,$surname,$sex,$user,$pass,$email,$avatar);
			
			if (!$res1) {
				$message = "<p class='error'>Query error.</p>";
				printForm($firstname,$surname,$sex,$user,$email);
			}
			
			else
			$lastid = mysql_insert_id();
			$namegroup = "friends";
			
			$res2 = createDefaultGroup($lastid,$namegroup);
			if (!$res2) {
				$message = "<p class='error'>Cannot create group.</p>";
			}
			
			
			//$headers ='From: "digeat"<digeat@gmail.com>'."\n";
			//$headers .='Content-Type: text/plain; charset="iso-8859-1"'."\n";
			//$headers .='Content-Transfer-Encoding: 8bit';
			//mail($email,"Registration to Digeat","You have been correctly registered.", $headers);
			$message = "<p>You have been successfully registered. Start <a href='./index.php'>here</a></p>";
			
		}
		
		
	
	}

	else
		printForm('','','','','','','','');

	$html .= "</div>";
	printDocument('Registration');
?>

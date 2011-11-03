<?php
	include './header.php';

	function productForm($Categorie,$Element,$Role) {
		echo "
		<p>Voici la gestion de la base de donnees des categories d'aliments. Remplissez le formulaire ci dessous pour ajouter une nouvelle categorie.</p>
		<form action='new-categorie.php' method='post'>
			<table>
				<tr>
					<td>Categorie</td>
					<td><input type='text' name='Categorie' value='$Categorie' required></td>
				</tr>
				<tr>
					<td>Elements nutritif</td>
					<td><textarea name='Element' required>$Element</textarea></td>
				</tr>
				<tr>
					<td>Role</td>
					<td><input type='text' name='Role' value='$Role' required></td>
				</tr>
				<tr>
					<td><input type='submit' name='proceed' value='Valider'></td>
				</tr>
			</table>
		</form>";
	}

	if ($_POST) {
		$Categorie   = $_POST[Categorie];
		$Element     = $_POST[Element];
		$Role        = $_POST[Role];
	
		// Vérification si le modèle existe déjà dans la base de donnée
		$result = mysql_query("SELECT Categorie from categories where Categorie='$Categorie'", $conn);
		if (mysql_num_rows($result)) {
			echo "<p class='error'>Cette categorie existe deja.</p>";
		}

		// Ajout d'un nouveau produit dans la base de donnée
		$req = mysql_query("INSERT INTO categories VALUES ('$Categorie','$Element','$Role')", $conn);
		if (!$req) {
			echo "<p class='error'>Erreur de remplissage. Erreur de requete.</p>";
			return productForm($Categorie,$Element,$Role);
		}
		else
			echo "<p>Vous avez enregistrer ces donnees avec success.<br>
				<a href='./new-categorie.php'>Cliquez ici</a></p>";
	}

	else
		productForm('','','');

	printFooter();
?>

<?php
	include './header.php';
	printHeader('Chatroom page');
	
	echo "
	<form action='chat_post.php' method='post'>
        <p>
        <label for='message'>Message</label> : <input type='text' name='message' id='message' /><br />

        <input type='submit' value='Send' />
	</p>
    </form>";

    
    // Affichage de chaque message (toutes les données sont protégées par htmlspecialchars)
   
   $chat = pg_query($conn,"SELECT message,id_cust FROM chat");	
	
	while ($row = pg_fetch_row($chat)) {
 	 echo "id:$row[1] $row[0]";
  	echo "<br />\n";
	}	
			
?>

<?php printFooter(); ?>	
<?php
  // Mise à jour des données
  if ($_POST) {
    session_start();
    $conn = pg_connect("host=sqletud.univ-mlv.fr port=5432 dbname=mboivent_db user=mboivent password=equina4");
    $query = pg_query($conn,
      "UPDATE
        users
      SET
        firstname = '$_POST[firstname]',
        surname   = '$_POST[surname]',
        address   = '$_POST[address]',
        city      = '$_POST[city]',
        country   = '$_POST[country]',
        username  = '$_POST[username]',
        mail      = '$_POST[mail]'
      WHERE
        id_customer = $_SESSION[id];"
    );
    $message = $query ? "<p>Successful update.</p>" : "<p class='error'>Query error.</p>";
    // Ne pas envoyer le POST dans header.php
    unset($_POST);
  }

  include './header.php';

  $html = "
  <p>Modify your account :</p>
  <form action='./modifyaccount.php' method='post'>
    <div><input type='text' name='firstname' value='$user[firstname]' required> Firstname</div>
    <div><input type='text' name='surname' value='$user[surname]' required> Surname</div>
    <div><input type='text' name='username' value='$user[username]' required> Username</div>
    <div><input type='text' name='address' value='$user[address]' required> Address</div>
    <div><input type='text' name='city' value='$user[city]' required> City</div>
    <div><input type='text' name='country' value='$user[country]' required> Country</div>
    <div><input type='text' name='mail' value='$user[mail]' required> Email</div>
    <div><button type='submit'>Update</button></div>
  </form>";

  printDocument('Account overview');
?>

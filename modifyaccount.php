<?php
  // Mise à jour des données
  if ($_POST) {
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
        id_customer = $_POST[id];"
    );
    $message = $query ? "<p>Successful update.</p>" : "<p class='error'>Query error.</p>";
    // Ne pas envoyer le POST dans header
    unset($_POST);
  }

  include './header.php';

  $html = "
  <p>Modify your account :</p>
  <form action='./modifyaccount.php' method='post'>
    <input type='hidden' name='id' value='$customer[7]'>
    <div><input type='text' name='firstname' value='$customer[0]' required> Firstname</div>
    <div><input type='text' name='surname' value='$customer[1]' required> Surname</div>
    <div><input type='text' name='username' value='$customer[6]' required> Username</div>
    <div><input type='text' name='address' value='$customer[2]' required> Address</div>
    <div><input type='text' name='city' value='$customer[3]' required> City</div>
    <div><input type='text' name='country' value='$customer[4]' required> Country</div>
    <div><input type='text' name='mail' value='$customer[5]' required> Email</div>
    <div><button type='submit'>Update</button></div>
  </form>";

  printDocument('Account overview');
?>

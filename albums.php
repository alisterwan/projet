<?php

include './header.php';

if (isset($_SESSION['id'])) {
    // v�rification si logu� ou pas
    $html = "<h1>$userinfos[firstname] $userinfos[surname] ($userinfos[username])</h1> <h3>My Albums</h3>";
    printDocument('My Albums');
} else {
    header('Location: index.php');
}

?>

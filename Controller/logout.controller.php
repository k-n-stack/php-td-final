<?php

// route lorsqu'on se deconnecte
if($page == "process-logout" and isset($_SESSION)) {

    // on detruit la map de l'utilisateur
    $_SESSION['user']->destroyMap();
    // on detruit la session puis on renvois vers le "routeur"
    unset($_SESSION);
    session_destroy();
    header("location: index.php");
    exit;
}
header("location: index.php");
exit;
<?php

// on verrifie deja si on a un utilisateur connecter
if(!empty($_SESSION['user'])) {
    // ca, c'est la route lorsqu'on genere une nouvelle map
    if($page == "process-map" and !empty($_POST)) {
        
        // on vide les messages d'erreurs
        if(isset($_SESSION['login-error'])) {
            unset($_SESSION['login-error']);
        }
        if(isset($_SESSION['register-error'])) {
            unset($_SESSION['register-error']);
        }
        // on detruit la valeur "map" de $_SESSION si elle existe
        if(isset($_SESSION['map'])) {
            unset($_SESSION['map']);
        }

        // on verifie que les champ de coordonner sont valide
        // sinon, on renvois sur la page de creation de map
        if(!isset($_POST['x_length'])
        or !isset($_POST['y_length'])
        or intval($_POST['x_length']) < 10
        or intval($_POST['x_length']) > 20
        or intval($_POST['y_length']) < 10
        or intval($_POST['y_length']) > 20) {
            include_once("View/map.view.php");
            exit;
        // sioui, on instantie un map alelatoire, qu'on stocke en session
        } else {
            $map = new Map($_SESSION['user']->name, intval($_POST['x_length']), intval($_POST['y_length']));
            $_SESSION['map'] = $map;
            include_once("View/map.view.php");
            exit;
        }

    }
    // ??????
    include_once("View/map.view.php");
} else {
    header('location: index.php');
}
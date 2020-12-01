<?php

// si l'user n'est pas connecter
if(empty($_SESSION['user'])){
    include_once("View/login.view.php");
// sinon, on verrifie si le process de login c'est bien passer ...
} else {
    // si oui on accede a la page d'acceuil
    if($_SESSION['user']->login() === true){
        include_once("View/home.view.php");
    // sinon on retourne a la page de login
    } else {
        include_once("View/login.view.php");
    }
}
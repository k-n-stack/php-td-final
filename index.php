<?php

require_once('Model/Autoloader.php');
Autoloader::register();

session_start();

Database::createConnexion();

// convertis tout les charactere speciaux des valeur de $_POST
if(isset($_POST) and !empty($_POST)) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = htmlentities($value);
    }
}

// si une valeur est passer dans l'URL, on convertit toute la valeur en minuscule
// si aucune valeur est passer dan l'URL, on definit une valeur par default
if(!empty($_GET['page'])) {
    $page = strtolower($_GET['page']);
} else {
    $page = 'home';
}

// consequemment, si la valeur est "home", on redirige vers un controlleur pas default
if(is_file("Controller/".$page."controller.php")) {
    require_once("Controller/".$page."controller.php");

//sinon, on "redirige" vers une route en fonction de la valeur passer dans l'URL
} else {
    // route lorsqu'on soumet le formulaire de conncetion
    if($page == "process-login") {
        require_once("Controller/login.controller.php");
    // route lorsqu'on sounet le formulaire d'inscription
    } elseif($page == "process-register") {
        require_once("Controller/register.controller.php");
    // route lorsque l'utilisateur se deconnecte
    } elseif($page == "process-logout") {
        require_once("Controller/logout.controller.php");
    // route lorsqu'on genere une nouvelle map
    } elseif($page == "process-map") {
        require_once("Controller/map.controller.php");
    // route lorsqu'on valide la carte
    } elseif($page == "start-game") {
        require_once("Controller/game.controller.php");
    // route quand on passe au tour suivant
    } elseif($page == "next-turn") {
        require_once("Controller/game.controller.php");
    // route quand on clique sur une case de la map
    } elseif($page == "select-box") {
        require_once("Controller/game.controller.php");
    // route quand on construit une tour
    } elseif($page == "build-tower") {
        require_once("Controller/game.controller.php");
    // route quand on vend une tour
    } elseif($page == "sell-tower") {
        require_once("Controller/game.controller.php");
    // route par default
    } else {
        require_once("Controller/home.controller.php");
    }
}


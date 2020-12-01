<?php

// plusieur route mene au jeux. On ne peut y acceder si on est pas connecter
if(!empty($_SESSION['user'])) {

    // si on a prealablement valider le formulaire qui genere une map, le controlleur map nous a defnis une valeur "map" dans session.
    // si c'est le cas, on prend cette route
    if($page == "start-game" and isset($_SESSION['map'])) {
    
        // on vide les tableau de la DB de notre utilisateur qui serviront pour le jeux
        // on commence donc une nouvelle partie
        $_SESSION['user']->destroyUI();
        $_SESSION['user']->destroyEnemies();
        $_SESSION['user']->destroyMap();
        $_SESSION['user']->destroyPathOrder();
        $_SESSION['user']->destroyTowers();
        
        // on sauvegarde la map genere dans la DB
        $_SESSION['map']->saveMap();
        $_SESSION['map']->savePathOrder();
    
        // on cree une Inteface Utilisateur puis on la sauvegarde dans la DB
        $ui = new UI($_SESSION['user']->name);
        $ui->turn = 0;
        $ui->hp = 10;
        $ui->gold = 10;
        $ui->difficulty = 4;
        $ui->score = 0;
        $ui->saveUI();

        // on vide nos variable
        unset($ui);
        unset($_SESSION['map']);
    
        require_once('View/game.view.php');
    
    // si on essayer d'acceder a la route "start-game" une seconde fois, on verrifie si la valeur "map" de session a ete vider
    // si c'est le cas, ca veut dire que nous ne venons pas du controlleur map.
    // ca veut dire que nous venons de la route sus coder
    } elseif($page == "start-game" and !isset($_SESSION['map'])) {
        
        // on recup l'UI de la DB
        if(!isset($ui)) {
            $ui = new UI($_SESSION['user']->name);
            $ui->loadUI();
            // si pas de UI dans la DB on est pas dans la bonne route. on "redirige" vers la page de creation de map
            if(!$ui->findUI()) {
                require_once('View/map.view.php');
                exit;
            }
            // si il y a un UI, on est sur la bonne route, on verrifie si on est pas mort
            if($ui->hp <= 0) {
                require_once('View/gameover.view.php');
                exit;
            }
        }

        require_once('View/game.view.php');
        
    // ca, c'est la route quand on passe au tour suivant
    } elseif($page == "next-turn") {

        // on recup l'UI de la DB puis on incremente le nombre de tours de un
        $ui = new UI($_SESSION['user']->name);
        $ui->loadUI();
        $ui->nextTurn();

        // la, on recupere tous les ennemies et toutes les tours de la DB
        $allEnemies = $_SESSION['user']->indexEnemies();
        $allTowers = $_SESSION['user']->indexTower();

        // on compare toutes les zones d'attaque de toutes les tours avec la position de tous les ennemies
        // si ca match, l'enemies prend les degats a valeur de l'attaque de la tour
        foreach($allTowers as $tower) {
            $tower_o = new Tower($_SESSION['user']->name, $tower['t_type'], $tower['x'], $tower['y']);

            foreach($tower_o->pattern as $pattern) {

                foreach($allEnemies as $enemy) {
                    $enemy_o = new Enemy($_SESSION['user']->name, 0);
                    $enemy_o->constructEnemy($_SESSION['user']->name, $enemy['enemy_nb']);
                    $x = $tower_o->x + $pattern['x'];
                    $y = $tower_o->y + $pattern['y'];

                    if($tower_o->x + $pattern['x'] == $enemy_o->x and $tower_o->y + $pattern['y'] == $enemy_o->y) {
                        $tower_o->attack($enemy_o);
                        $enemy_o->updateEnemy($enemy['enemy_nb']);

                    }
                    
                }
            }
        }

        // on verifie si des enemis sont mort
        $_SESSION['user']->checkKills($ui);
        
        // on met a jour la nouvelle position de l'enemi (il avance d'une case)
        // egalement, on verifie si l'ennemi a atteint la base auquel cas il nous inflige des degats
        foreach($_SESSION['user']->loadEnemies() as $value) {
            $enemy = new Enemy($value['user'], $value['attack_pt']);
            $enemy->health_pt = $value['health_pt'];
            $enemy->enemy_nb = $value['enemy_nb'];
            $enemy->path_pos = $value['path_pos'] + 1;
            $enemy->setCoord();
            $enemy->updateEnemy($value['enemy_nb']);
            $enemy->attack($ui, count($_SESSION['user']->indexPathOrder()) - 1);
            unset($enemy);
        }

        // on genere un nouvel ennemie alelatoirement en fonction de la difficulte.
        if($ui->rollDice()) {
            $enemy = new Enemy($_SESSION['user']->name, 1);
            $enemy->storeEnemy();
            unset($enemy);
        }

        // on met a jour l'UI dans la db (pour l'incrementation du tour notemment)
        $ui->updateUI();

        // on verrifie si on est pas mort
        if($ui->hp <= 0) {
            require_once('View/gameover.view.php');
            exit;
        }

        require_once('View/game.view.php');

    // ici, c'est la route lorsqu'on clique sur une case "grass" de la map. on verifie si il n'y a pas de tour dessus ...
    } elseif($page == "select-box" and isset($_POST['box'])) {
        $_SESSION['box_x'] = $_POST['box_x'];
        $_SESSION['box_y'] = $_POST['box_y'];
        require_once('View/game.view.php');
        
    // et la pareil, mais on verifie si il y a une tour
    } elseif($page == "select-box" and isset($_POST['tower'])) {
        $_SESSION['tower_x'] = $_POST['box_x'];
        $_SESSION['tower_y'] = $_POST['box_y'];
        $_SESSION['type'] = $_POST['type'];
        require_once('View/game.view.php');

    // ca, c'est la route lorqu'on decide de contruire une tour
    } elseif($page == "build-tower" and isset($_POST['type'])) {

        // on recup l'UI de la DB
        $ui = new UI($_SESSION['user']->name);
        $ui->loadUI();

        // on instantie une tour, avec en param le type et la position
        $tower = new Tower($_SESSION['user']->name, $_POST['type'], $_SESSION['box_x'], $_SESSION['box_y']);

        // on verifie si une tour existe deja sur la case cible...
        if($tower->towerExist()) {
            require_once('View/game.view.php');
        // on verifier si on a assez d'argent pour acheter la tour...
        } elseif(!$ui->canBuy($tower)) {
            require_once('View/game.view.php');
        // ... sinon on l'achete, on la stocke en DB, on et deduit le cout de notre bourse.
        } else {
            $tower->storeTower();
            $ui->gold -= $tower->cost;
            $ui->updateUI();

            require_once('View/game.view.php');
        }

    // ici, la route quand on decide de vendre une tour
    } elseif($page == "sell-tower" and isset($_POST['sell'])) {
        
        // on recup l'UI de la DB
        $ui = new UI($_SESSION['user']->name);
        $ui->loadUI();

        // on instantie une tour, avec en param le type et la position
        $tower = new Tower($_SESSION['user']->name, $_SESSION['type'], $_SESSION['tower_x'], $_SESSION['tower_y']);

        // on recup l'argent de la vente (1/3)
        $ui->gold += $tower->sellAmount();
        $ui->updateUI();

        // on retire la tour de la DB
        $tower->destroyTower();

        require_once('View/game.view.php');

    // route pas default
    } else {
        header('location: index.php');
    }
    
// route par default (lorsque l'utilisateur non conncter)
} else {
    header('location: index.php');
}
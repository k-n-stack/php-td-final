<?php

class UI {
    private $user;
    private $turn;
    private $hp;
    private $gold;
    
    private $difficulty;
    private $score;

    public function __construct($user) {
        $this->user = $user;
    }

    public function __get($value) {
        return $this->$value;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function saveUI() {

        $sql = "INSERT INTO `ui` (`id`, `user`, `turn`, `hp`, `gold`, `difficulty`, `score`) 
        VALUES (NULL, :user, :turn, :hp, :gold, :difficulty, :score)";

        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $this->user);
        $req->bindValue(":turn", $this->turn);
        $req->bindValue(":hp", $this->hp);
        $req->bindValue(":gold", $this->gold);
        $req->bindValue(":difficulty", $this->difficulty);
        $req->bindValue(":score", $this->score);

        if($req->execute()) {
            return true;
        } else {
            return "DATABASE ERROR!";
        }       
    }

    public function updateUI() {

        $sql = "UPDATE `ui` 
            SET `turn` = :turn, `hp` = :hp, `gold` = :gold, `difficulty` = :difficulty, `score` = :score
            WHERE `ui`.`user` = :user";

        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $this->user);
        $req->bindValue(":turn", $this->turn);
        $req->bindValue(":hp", $this->hp);
        $req->bindValue(":gold", $this->gold);
        $req->bindValue(":difficulty", $this->difficulty);
        $req->bindValue(":score", $this->score);
        $req->execute();
    }

    public function findUI() {
        $sql = "SELECT * FROM `ui` WHERE user = :user";

        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $this->user);
        $req->execute();
        if($req->rowCount() == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function loadUI() {
        $sql = "SELECT * FROM `ui` WHERE user = :user";
        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $this->user);
        $req->execute();
        $array = $req->fetch(PDO::FETCH_ASSOC);

        $this->turn = $array['turn'];
        $this->hp = $array['hp'];
        $this->gold = $array['gold'];
        $this->difficulty = $array['difficulty'];
        $this->score = $array['score'];
    }

    public function nextTurn() {
        $this->turn += 1;
    }

    public function rollDice() {
        $result = rand(1, 10);
        if($result <= $this->difficulty) {
            return true;
        }
        return false;
    }

    public function canBuy(Tower $tower) {
        if($this->gold < $tower->cost) {
            return false;
        }
        return true;
    }

    public function checkKill() {
        
    }
}


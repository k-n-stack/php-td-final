<?php

class Tower {

    private $user;
    private $t_type;
    private $x;
    private $y;
    private $cost;
    
    private $attack_pt;
    private $pattern;

    public function __construct($user, $t_type, $x, $y) {
        $this->user = $user;
        $this->t_type = $t_type;
        $this->x = $x;
        $this->y = $y;
        $this->setTowerType();
    }

    public function __get($value) {
        return $this->$value;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function setTowerType() {
        switch($this->t_type) {

            case "arrow":
                $this->cost = 3;
                $this->attack_pt = 1;
                $this->pattern = [
                    ['x' => +2, 'y' => 0],
                    ['x' => -2, 'y' => 0],
                    ['x' => 0, 'y' => +2],
                    ['x' => 0, 'y' => -2],
                ];
            break;

            case "fire":
                $this->cost = 7;
                $this->attack_pt = 3;
                $this->pattern = [
                    ['x' => +3, 'y' => 0],
                    ['x' => -3, 'y' => 0],
                    ['x' => 0, 'y' => +3],
                    ['x' => 0, 'y' => -3],
                ];
            break;
                
            case "lazer":
                $this->cost = 12;
                $this->attack_pt = 6;
                $this->pattern = [
                    ['x' => +4, 'y' => 0],
                    ['x' => -4, 'y' => 0],
                    ['x' => 0, 'y' => +4],
                    ['x' => 0, 'y' => -4],
                    ['x' => +5, 'y' => 0],
                    ['x' => -5, 'y' => 0],
                    ['x' => 0, 'y' => +5],
                    ['x' => 0, 'y' => -5],
                ];
            break;
        }
    }

    public function storeTower() {

        $sql = "INSERT INTO `towers` (`id`, `user`, `t_type`, `x`, `y`, `cost`, `attack_pt`) 
            VALUES (NULL, :user, :t_type, :x, :y, :cost, :attack_pt) ";

        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $this->user);
        $req->bindValue(":t_type", $this->t_type);
        $req->bindValue(":x", $this->x);
        $req->bindValue(":y", $this->y);
        $req->bindValue(":cost", $this->cost);
        $req->bindValue(":attack_pt", $this->attack_pt);

        $req->execute();

    }

    public function destroyTower() {
        $sql = "DELETE FROM `towers` WHERE user = :user AND x = :x AND y = :y";
        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $this->user);
        $req->bindValue(":x", $this->x);
        $req->bindValue(":y", $this->y);
        $req->execute();
    }

    public function towerExist() {

        $sql = "SELECT * FROM `towers` WHERE user = :user AND x = :x AND y = :y";
        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $this->user);
        $req->bindValue(":x", $this->x);
        $req->bindValue(":y", $this->y);
        $req->execute();

        if($req->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public function sellAmount() {
        $amount = intval($this->cost/3);
        // echo $amount;
        // echo $this->cost;
        return $amount;
    }

    public function attack(Enemy $enemy) {
        $enemy->health_pt -= $this->attack_pt;
    }
}
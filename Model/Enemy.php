<?php

class Enemy {

    private $user;
    private $enemy_nb;
    private $health_pt;
    private $attack_pt;
    private $path_pos;
    private $x;
    private $y;

    ### CONSTUCTOR

    public function __construct($user, $attack_pt) {

        $this->user = $user;
        $this->health_pt = intval(rand(3, 6) * 2);
        $this->attack_pt = $attack_pt;
        $this->path_pos = 0;
        $this->enemy_nb = $this->countEnemy();
        $this->setCoord();

        // echo "new Enemy-objet instantiated";
    }

    public function __get($value) {
        return $this->$value;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function setCoord() {
        $this->x = $this->getCoord()['x'];
        $this->y = $this->getCoord()['y'];
    }

    public function storeEnemy() {

        $sql = "INSERT INTO `enemies` (`id`, `user`, `enemy_nb`, `health_pt`, `attack_pt`, `path_pos`, `x`, `y`) 
        VALUES (NULL, :user, :enemy_nb, :health_pt, :attack_pt, :path_pos, :x, :y)";

        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $this->user);
        $req->bindValue(":enemy_nb", $this->enemy_nb);
        $req->bindValue(":health_pt", $this->health_pt);
        $req->bindValue(":attack_pt", $this->attack_pt);
        $req->bindValue(":path_pos", $this->path_pos);
        $req->bindValue(":x", $this->x);
        $req->bindValue(":y", $this->y);

        $req->execute();

    }

    public function constructEnemy($user, $enemy_nb) {

        $sql = "SELECT * FROM `enemies` WHERE user = :user AND enemy_nb = :enemy_nb";
        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $user);
        $req->bindValue(":enemy_nb", $enemy_nb);
        $req->execute();
        $enemy = $req->fetch(PDO::FETCH_ASSOC);

        $this->user = $enemy['user'];
        $this->enemy_nb = $enemy['enemy_nb'];
        $this->health_pt = $enemy['health_pt'];
        $this->attack_pt = $enemy['attack_pt'];
        $this->path_pos = $enemy['path_pos'];
        $this->x = $enemy['x'];
        $this->y = $enemy['y'];

        // echo "constructed";

    }

    public function updateEnemy($id) {

        $sql = "UPDATE `enemies` 
            SET `enemy_nb` = :enemy_nb, `health_pt` = :health_pt, `path_pos` = :path_pos, `x` = :x, `y` = :y
            WHERE `enemies`.`enemy_nb` = :id AND `enemies`.`user` = :user";

        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":id", $id);
        $req->bindValue(":enemy_nb", $this->enemy_nb);
        $req->bindValue(":user", $this->user);
        $req->bindValue(":health_pt", $this->health_pt);
        $req->bindValue(":path_pos", $this->path_pos);
        $req->bindValue(":x", $this->x);
        $req->bindValue(":y", $this->y);

        $req->execute();

    }

    public function getCoord() {
        $sql = "SELECT * FROM `path_order` WHERE path_index = :path_index AND user = :user";
        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $this->user);
        $req->bindValue(":path_index", $this->path_pos);
        $req->execute();
        $path = $req->fetch(PDO::FETCH_ASSOC);

        // var_dump($path);
        return $path;
    }

    public function countEnemy() {
        $sql = "SELECT * FROM `enemies` WHERE user = :user";
        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $this->user);
        $req->execute();
        $count = $req->rowCount() + 1;

        return $count;
    }

    public function attack(UI $ui, $lenght) {
        if($this->path_pos == $lenght) {
            $ui->hp -= $this->attack_pt; 
        }
    }

}
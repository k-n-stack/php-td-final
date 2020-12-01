<?php

class User {
    private $name;
    private $password;
    private $password_check;

    public function __construct($name, $password, $password_check = null) {
        $this->name = $name;
        $this->password = $password;
        $this->password_check = $password_check;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function passCrypt($value) {
        return password_hash($value, PASSWORD_DEFAULT);
    }

    public function validate($login = true) {
        $errors = "";
        
        if(empty($this->name)) {
            $errors .= "empty name field<br>";
        }
        if(empty($this->password)) {
            $errors .= "empty password field<br>";
        }
        if(!$login and strlen($this->name) < 5) {
            $errors .= "name too short<br>";
        }
        if(!$login and strlen($this->name) > 20) {
            $errors .= "name too long<br>";
        }
        if(!$login and empty($this->password_check)) {
            $errors .= "empty password check<br>";
        }
        if(!$login and $this->password !== $this->password_check) {
            $errors .= "passwords don't match";
        }

        return $errors;
    }

    public function register() {
        $error = $this->validate(false);

        if(empty($error)) {
            $sql = "INSERT INTO `users` (`name`, `password`) VALUE (:name, :password)";
            $req = Database::$_connection->prepare($sql);
            $req->bindValue(":name", $this->name);
            $req->bindValue(":password", $this->passCrypt($this->password));
            if($req->execute()) {
                return true;
            } else {
                return "user already exist";
            }
        } else {
            return $error;
        }
    }

    public function login() {
        $error = $this->validate();

        if(empty($error)) {
            $sql = "SELECT * FROM `users` WHERE name = :name";
            $req = Database::$_connection->prepare($sql);
            $req->bindValue(":name", $this->name);
            $req->execute();
            if($req->rowCount() == 1) {
                $user = $req->fetch();
                if(password_verify($this->password, $user["password"])) {
                    $this->name = $user["name"];
                    return true;
                } else {
                    $error = "login failed";
                    return $error;
                }
            } else {
                $error = "login failed";
                return $error;
            }
        } else {
            return $error;
        }
    }

    public function indexMap() {
        $sql = "SELECT * FROM `map_coord` WHERE user = :user";
        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $this->name);
        $req->execute();
        if(empty($req)) {
            return false;
        } else {
            $map = $req->fetchAll(PDO::FETCH_ASSOC);
            return $map;
        }
        
    }

    public function indexPathOrder() {
        $sql = "SELECT * FROM `path_order` WHERE user = :user";
        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $this->name);
        $req->execute();
        if(empty($req)) {
            return false;
        } else {
            $path = $req->fetchAll(PDO::FETCH_ASSOC);
            return $path;
        }
        
    }

    public function indexTower() {
        $sql = "SELECT * FROM `towers` WHERE user = :user";
        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $this->name);
        $req->execute();

        $towers = $req->fetchAll(PDO::FETCH_ASSOC);
        return $towers;
        
    }

    public function indexEnemies() {
        $sql = "SELECT * FROM `enemies` WHERE user = :user";
        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $this->name);
        $req->execute();

        $enemies = $req->fetchAll(PDO::FETCH_ASSOC);
        return $enemies;
        
    }

    public function destroyMap() {
        $sql = "DELETE FROM `map_coord` WHERE user = :user";
        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $this->name);
        $req->execute();

        return "destroying map for user : $this->name...";
    }

    public function destroyPathOrder() {
        $sql = "DELETE FROM `path_order` WHERE user = :user";
        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $this->name);
        $req->execute();
    }

    public function destroyEnemies() {
        $sql = "DELETE FROM `enemies` WHERE user = :user";
        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $this->name);
        $req->execute();
    }

    public function destroyTowers() {
        $sql = "DELETE FROM `towers` WHERE user = :user";
        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $this->name);
        $req->execute();
    }

    public function destroyUI() {
        $sql = "DELETE FROM `ui` WHERE user = :user";
        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $this->name);
        $req->execute();
    }
    
    public function loadEnemies() {
        
        $sql = "SELECT * FROM `enemies` WHERE user = :user";
        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $this->name);
        $req->execute();
        $array = $req->fetchAll(PDO::FETCH_ASSOC);

        return $array;
        
    }

    public function loadTowers() {

        $sql = "SELECT * FROM `towers` WHERE user = :user";
        $req = Database::$_connection->prepare($sql);
        $req->bindValue(":user", $this->name);
        $req->execute();
        $array = $req->fetchAll(PDO::FETCH_ASSOC);

        return $array;
        
    }

    public function checkKills(UI $ui) {
        $sql = "SELECT * FROM `enemies` WHERE health_pt <= 0";
        $req = Database::$_connection->prepare($sql);
        $req->execute();

        $ui->gold += $req->rowCount();
        $ui->score += $req->rowCount();

        $sql2 = "DELETE FROM `enemies` WHERE health_pt <= 0";
        $req2 = Database::$_connection->prepare($sql2);
        $req2->execute();
    }
}
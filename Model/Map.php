<?php

class Map {

    private $user;
    private $x_length;
    private $y_length;
    private $home_coord = ['x' => 1, 'y' => 1];
    private $path;
    private $water;
    private $map;

    public function __construct($user, $x_length, $y_length) {
        $this->user = $user;
        $this->x_length = $x_length;
        $this->y_length = $y_length;
        $this->path = $this->buildPath();
        $this->water = $this->buildWater();
        $this->map = $this->buildMap();
    }

    public function __get($value) {
        return $this->$value;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function buildPath() {

        do {

            $path[] = ['x' => 1, 'y' => 1];
            $x = $path[0]['x'];
            $y = $path[0]['y'];
            $failed_attempt = 0;
        
            do {
        
                switch(rand(1,4)) {
                    case 1 :
                        $x++;
                        if($this->neighbour_verification($path, $x, $y) > 1 or $x > $this->x_length) {
                            $failed_attempt++;
                            $x--;
                        break;
                        } else {
                            $failed_attempt = 0;
                            $path[] = ['x' => $x, 'y' => $y];
                        break;
                        }
        
                    case 2 :
                        $x--;
                        if($this->neighbour_verification($path, $x, $y) > 1 or $x < 1) {
                            $failed_attempt++;
                            $x++;
                        break;
                        } else {
                            $failed_attempt = 0;
                            $path[] = ['x' => $x, 'y' => $y];
                        break;
                        }
        
                    case 3 :
                        $y++;
                        if($this->neighbour_verification($path, $x, $y) > 1 or $y > $this->y_length) {
                            $failed_attempt++;
                            $y--;
                        break;
                        } else {
                            $failed_attempt = 0;
                            $path[] = ['x' => $x, 'y' => $y];
                        break;
                        }
        
                    case 4 :
                        $y--;
                        if($this->neighbour_verification($path, $x, $y) > 1 or $y < 1) {
                            $failed_attempt++;
                            $y++;
                        break;
                        } else {
                            $failed_attempt = 0;
                            $path[] = ['x' => $x, 'y' => $y];
                        break;
                        }
                }
        
                if($failed_attempt > 7) {
                    $failed_attempt = 0;
                    $path = [];
                    $path[] = ['x' => 1, 'y' => 1];
                    $x = $path[0]['x'];
                    $y = $path[0]['y'];
                }
        
            } while ( $x != $this->x_length or $y != $this->y_length);
        
        } while (count($path) < intval($this->difficultyRate($this->x_length, $this->y_length) * $this->x_length * $this->y_length));

        return $path;
    }

    public function buildWater() {

        switch(rand(1, 2)) {
            case 1 :
                $x = rand(2, $this->x_length - 3);
                for($y = 1; $y <= $this->y_length; $y++) {
                    $path[] = ['x' => $x, 'y' => $y];
                    $path[] = ['x' => $x + 1, 'y' => $y];
                    $path[] = ['x' => $x + 2, 'y' => $y];
                }
            break;

            case 2 :
                $y = rand(2, $this->y_length - 3);
                for($x = 1; $x <= $this->x_length; $x++) {
                    $path[] = ['x' => $x, 'y' => $y];
                    $path[] = ['x' => $x, 'y' => $y + 1];
                    $path[] = ['x' => $x, 'y' => $y + 2];
                }
            break;
        }

        return $path;

    }

    public function buildMap() {
        for($x=1; $x <= $this->x_length; $x++) {
            for($y=1; $y <= $this->y_length; $y++) {

                if($this->home_coord['x'] == $x and $this->home_coord['y'] == $y) {
                    $map[] = ['x' => $x, 'y' => $y, 'type' => 'home'];
                } elseif($this->case_analyzer($this->path, $x, $y)) {
                    $map[] = ['x' => $x, 'y' => $y, 'type' => 'path'];
                } elseif($this->case_analyzer($this->water, $x, $y)) {
                    $map[] = ['x' => $x, 'y' => $y, 'type' => 'water'];
                } else {
                    $map[] = ['x' => $x, 'y' => $y, 'type' => 'grass'];
                }
                        
            }
        }

        return $map;
    }

    public function difficultyRate($x, $y) {
        if($x * $y < 150) {
            return 0.45;
        }
        if($x * $y < 250) {
            return 0.36;
        }
        if($x * $y < 300) {
            return 0.30;
        }
        if($x * $y < 350) {
            return 0.26;      
        }
        return 0.21;
    }

    // check how many path box will be beside next random path box
    public function neighbour_verification($array, $x, $y) {

        $test_match = 0;

        foreach($array as $value) {
            if ($value['x'] == $x + 1 and $value['y'] == $y) {$test_match++;}
            if ($value['x'] == $x - 1 and $value['y'] == $y) {$test_match++;}
            if ($value['x'] == $x and $value['y'] == $y + 1) {$test_match++;}
            if ($value['x'] == $x and $value['y'] == $y - 1) {$test_match++;}
        }

        return $test_match;
    }

    public function case_analyzer(array $array, $x, $y) {

        foreach($array as $value) {
            if($value['x'] == $x and $value['y'] == $y) {
                return true;
            } 
        }
        return false;
    }

    public function saveMap() {

        $sql = "INSERT INTO `map_coord` (`id`, `user`, `x`, `y`, `type`) VALUES ";

        foreach($this->map as $key => $value) {
            $sql .= "(NULL, :user, :x$key, :y$key, :type$key), ";
        }

        $sql = substr($sql, 0, -2);

        $req = Database::$_connection->prepare($sql);

        foreach($this->map as $key =>$value) {
            $req->bindValue(":x$key", $value['x']);
            $req->bindValue(":y$key", $value['y']);
            $req->bindValue(":type$key", $value['type']);
        }

        $req->bindValue(":user", $this->user);

        if($req->execute()) {
            return true;
        } else {
            return "DATABASE ERROR!";
        }

    }

    public function savePathOrder() {

        $array = array_reverse($this->path);

        $i = 0;
        $j = 0;

        $sql = "INSERT INTO `path_order` (`id`, `user`, `path_index`, `x`, `y`) VALUES ";
        
        foreach($array as $key => $value) {
            $sql .= "(NULL, :user, :path_index$i, :x$i, :y$i), ";
            $i++;
        }
        
        $sql = substr($sql, 0, -2);
        
        $req = Database::$_connection->prepare($sql);
        
        // echo $sql;

        foreach($array as $key => $value) {
            $req->bindValue(":path_index$j", $j);
            $req->bindValue(":x$j", $value['x']);
            $req->bindValue(":y$j", $value['y']);
            $j++;
        }

        $req->bindValue(":user", $this->user);

        if($req->execute()) {
            return true;
        } else {
            return "DATABASE ERROR!";
        }
    }
    
}
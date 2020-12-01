<?php

$map = $_SESSION['user']->indexMap();
$ui = new UI($_SESSION['user']->name);
$ui->loadUI();
$enemies = $_SESSION['user']->loadEnemies();
$towers = $_SESSION['user']->loadTowers();

$x_length = end($map)['x'];
$y_length = end($map)['y'];

$case_size = 90/$x_length;

$map_width = $case_size * $y_length;
$map_height = $case_size * $x_length;

?>

<style>

.map {
    width: <?php echo $map_width;?>vmin;
    height: <?php echo $map_height;?>vmin;

    margin-left: -<?php echo $map_width/2;?>vmin;
   
    grid-template-rows: repeat(<?php echo $x_length;?>, 1fr);
    grid-template-columns: repeat(<?php echo $y_length;?>, 1fr);
}

.mapbutton {
    width: <?php echo $case_size-(10/$x_length);?>vmin;
    height: <?php echo $case_size-(10/$x_length);?>vmin;
}

</style>

<?php

foreach($map as $value) {

    if($value['type'] == 'home') {
        echo '<div class="case home"><img src=".\Img\home.png" height="50%" width="50%"><br>hp:'.$ui->hp.'</div>';
    }

    if($value['type'] == 'path') {
        $check = false;
        foreach($enemies as $value2) {
            if($value2['x'] == $value['x'] and $value2['y'] == $value['y']) {
                echo '<div class="case enemy"><img src=".\Img\enemy1.png" height="50%" width="50%"><br>hp:'.$value2['health_pt'].'</div>';
                $check = true;
            }
        }
        if($check == false) {
            echo "<div class='case path'></div>";
        }
    }

    if($value['type'] == 'grass') {
        $check = false;
        foreach($towers as $value2) {
            if($value2['x'] == $value['x'] and $value2['y'] == $value['y']) {
                if($value2['t_type'] == "arrow") {
                    echo '<div class="case tower '.$value2['t_type'].'"><img src=".\Img\arrow.png" height="50%" width="50%"><br>atk:'.$value2['attack_pt'].'</div>';
                }
                if($value2['t_type'] == "fire") {
                    echo '<div class="case tower '.$value2['t_type'].'"><img src=".\Img\fire.png" height="50%" width="50%"><br>atk:'.$value2['attack_pt'].'</div>';
                }
                if($value2['t_type'] == "lazer") {
                    echo '<div class="case tower '.$value2['t_type'].'"><img src=".\Img\lazer.png" height="50%" width="50%"><br>atk:'.$value2['attack_pt'].'</div>';
                }
                $check = true;
            }
        }
        if($check == false) {
            echo "<div class='case gras'></div>";
        }


    }

    if($value['type'] == 'water') {
        echo "<div class='case wate'></div>";
    }
}
<?php

$map = $_SESSION['user']->indexMap();
$enemies = $_SESSION['user']->loadEnemies();

// echo "<pre>";
// var_dump($map);
// var_dump($enemies);
// echo "</pre>";

$x_length = end($map)['x'];
$y_length = end($map)['y'];

$case_size = 85/$x_length;

$map_width = $case_size * $y_length;
$map_height = $case_size * $x_length;

?>

<style>

.enemy-map {
    width: <?php echo $map_width;?>vmin;
    height: <?php echo $map_height;?>vmin;

    margin-left: -<?php echo $map_width/2;?>vmin;
   
    grid-template-rows: repeat(<?php echo $x_length;?>, 1fr);
    grid-template-columns: repeat(<?php echo $y_length;?>, 1fr);
}

</style>

<?php

// echo "<pre>";
// var_dump($map);
// echo "</pre>";

foreach($map as $value) {
    if($value['type'] == 'home') {
        echo "<div class='case void'></div>";
    }
    if($value['type'] == 'grass') {
        echo "<div class='case void'></div>";
    }
    if($value['type'] == 'path') {
        $check = false;
        foreach($enemies as $value2) {
            if($value2['x'] == $value['x'] and $value2['y'] == $value['y']) {
                echo '<div class="case enemy"><img src=".\Img\enemy1.png" height="40%" width="40%">hp:'.$value2['health_pt'].'</div>';
                $check = true;
            }
        }
        if($check == false) {
            echo "<div class='case path'></div>";
        }
    }
    if($value['type'] == 'water') {
        echo "<div class='case void'></div>";
    }
}


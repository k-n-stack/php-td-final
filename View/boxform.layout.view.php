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

.boxform-map {
    width: <?php echo $map_width;?>vmin;
    height: <?php echo $map_height;?>vmin;

    margin-left: -<?php echo $map_width/2;?>vmin;
   
    grid-template-rows: repeat(<?php echo $x_length;?>, 1fr);
    grid-template-columns: repeat(<?php echo $y_length;?>, 1fr);
}

</style>

<?php

foreach($map as $value) {

    if($value['type'] == 'grass') {
        $check = false;
        foreach($towers as $value2) {
            if($value2['x'] == $value['x'] and $value2['y'] == $value['y']) {
                echo 
                "<div class='case void ".$value2['t_type']."'>
                    <form action='?page=select-box' method='post'>
                        <input class='mapbutton' type='submit' name='tower' value='select'>
                        <input name='box_y' type='hidden' value='".$value['y']."'>
                        <input name='box_x' type='hidden' value='".$value['x']."'>
                        <input name='type' type='hidden' value='".$value2['t_type']."'>
                    </form>
                </div>";
                $check = true;
            }
        }
        if($check == false) {
            echo 
            "<div class='case void'>
                <form action='?page=select-box' method='post'>
                    <input class='mapbutton' type='submit' name='box' value='select'>
                    <input name='box_y' type='hidden' value='".$value['y']."'>
                    <input name='box_x' type='hidden' value='".$value['x']."'>
                </form>
            </div>";
            
        }

    } else {
        echo "<div class='case void'></div>";
    }
}
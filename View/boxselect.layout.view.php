<?php

$map = $_SESSION['user']->indexMap();

$x_length = end($map)['x'];
$y_length = end($map)['y'];

$case_size = 90/$x_length;

$map_width = $case_size * $y_length;
$map_height = $case_size * $x_length;

?>

<style>

.boxselect-map {
    width: <?php echo $map_width;?>vmin;
    height: <?php echo $map_height;?>vmin;

    margin-left: -<?php echo $map_width/2;?>vmin;
   
    grid-template-rows: repeat(<?php echo $x_length;?>, 1fr);
    grid-template-columns: repeat(<?php echo $y_length;?>, 1fr);
}

</style>

<?php

if(isset($_POST['tower'])) {
    $tower = new Tower($_SESSION['user']->name, $_POST['type'], $_POST['box_x'], $_POST['box_y']);

    foreach($map as $value) {

        $check = false;

        if($value['x'] == $_POST['box_x'] and $value['y'] == $_POST['box_y']) {
            echo "<div class='case selection'></div>";
            $check = true;
        } else {

            foreach($tower->pattern as $pattern) {

                $x = $_POST['box_x'] + $pattern['x'];
                $y = $_POST['box_y'] + $pattern['y'];

                if($value['x'] == $x and $value['y'] == $y) {
                    echo "<div class='case attack'></div>";
                    $check = true;
                }
            }
        }

        if(!$check) {
            echo "<div class='case void'></div>";
        }
    }






} else {
    foreach($map as $value) {
        if($value['x'] == $_POST['box_x'] and $value['y'] == $_POST['box_y']) {
            echo "<div class='case selection'></div>";
        } else {
            echo "<div class='case void'></div>";
        }
    }
}
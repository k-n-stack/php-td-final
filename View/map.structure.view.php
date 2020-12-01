<?php
if(isset($map)) {
    $case_size = 70/$map->x_length;

    $map_width = $case_size * $map->y_length;
    $map_height = $case_size * $map->x_length;
}
?>

<style>

.map {
    width: <?php echo $map_width;?>vmin;
    height: <?php echo $map_height;?>vmin;

    margin-left: -<?php echo $map_width/2;?>vmin;
    
    grid-template-rows: repeat(<?php echo $map->x_length;?>, 1fr);
    grid-template-columns: repeat(<?php echo $map->y_length;?>, 1fr);
}

</style>

<?php

if(isset($map)) {
    foreach($map->map as $value) {
        if($value['type'] == 'home') {
            echo "<div class='case home'></div>";
        }
        if($value['type'] == 'grass') {
            echo "<div class='case gras'></div>";
        }
        if($value['type'] == 'path') {
            echo "<div class='case path'></div>";
        }
        if($value['type'] == 'water') {
            echo "<div class='case wate'></div>";
        }
    }
}
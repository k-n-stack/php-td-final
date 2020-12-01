<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="main.style.css">
    <link rel="stylesheet" type="text/css" href="gamemap.style.css">
    <link rel="stylesheet" type="text/css" href="ui.style.css">
    <title>Document</title>
</head>
<body>

        <?php 
            if(isset($_POST['box_x']) and isset($_POST['box_x'])) {
                echo '<div class="boxselect-map">';
                include_once('boxselect.layout.view.php');
                echo '</div>';
            }
        ?>

        <div class="boxform-map">
            <?php include_once('boxform.layout.view.php');?>
        </div>

        <div class="map">
            <?php include_once('map.layout.view.php');?>
        </div>
        
        <div id="inputpanel">
            <?php 
                $ui = new UI($_SESSION['user']->name);
                $ui->loadUI();

                echo
                '<p>user : '.$ui->user.
                '<br>turn : '.$ui->turn.
                '<br>hp : '.$ui->hp.
                '<br>gold : '.$ui->gold.
                '<br>difficulty : '.$ui->difficulty.
                '<br>score : '.$ui->score.
                '</p>';

                unset($ui);
                include_once('input.layout.view.php');
            ?>
        </div>

</body>
</html>